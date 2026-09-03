<?php
/**
 * Реальная отправка лид-форм темы (Фаза 14) — `#hero-form`
 * (`patterns/hero.php`, `subject-hero.php`) и `#signup`
 * (`patterns/contact-section.php`, `subject-contact.php`). Простая
 * лид-форма «оставьте контакты, перезвоним» на `wp_mail()` — НЕ тот же
 * поток, что заявка на зачисление плагина (`ApplicationCallbacks`,
 * OTP/шифрование PII/личный кабинет), плагин не трогаем, своя защита в
 * теме (не тянем классы плагина как хард-зависимость — см.
 * `inc/PluginRoutes.php` для того же принципа с URL).
 *
 * Защита — тот же стек, что у формы заявки плагина, но свой инстанс:
 * honeypot + подписанный HMAC-таймер (по образцу
 * `Inc\Services\Security\FormGuardService` плагина) + Yandex SmartCaptcha
 * (по образцу `YandexSmartCaptchaProvider`, свои ключи в настройках темы)
 * + rate-limit по IP на transient.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Минимальное «человеческое» время заполнения формы, сек. */
const FS_LMS_THEME_FORM_MIN_FILL_SECONDS = 3;

/** Срок годности токена формы, сек (защита от reuse старых меток). */
const FS_LMS_THEME_FORM_MAX_TOKEN_AGE = HOUR_IN_SECONDS;

/** Лимит сабмитов на IP в окне rate-limit. */
const FS_LMS_THEME_FORM_RATE_LIMIT = 5;

/** Окно rate-limit, сек. */
const FS_LMS_THEME_FORM_RATE_WINDOW = 10 * MINUTE_IN_SECONDS;

/** Получатель писем с лид-форм (решение 2, обсуждение 2026-09-02). */
const FS_LMS_THEME_FORM_RECIPIENT = 'info@future-step.ru';

/* --------------------------------------------------------------------
 * Настройки: Настройки → Формы (Yandex SmartCaptcha, независимо от
 * настроек плагина — `SmartCaptchaSettingsController` не переиспользуем,
 * тема не должна знать о классах плагина).
 * ------------------------------------------------------------------ */

add_action( 'admin_menu', function (): void {
	add_options_page(
		__( 'Формы сайта', 'fs-lms-theme' ),
		__( 'Формы', 'fs-lms-theme' ),
		'manage_options',
		'fs-lms-theme-forms',
		'fs_lms_theme_render_forms_settings_page'
	);
} );

add_action( 'admin_init', function (): void {
	register_setting( 'fs_lms_theme_forms', 'fs_lms_theme_captcha_site_key', array(
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '',
	) );
	register_setting( 'fs_lms_theme_forms', 'fs_lms_theme_captcha_server_key', array(
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '',
	) );
} );

function fs_lms_theme_render_forms_settings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Настройки форм', 'fs-lms-theme' ); ?></h1>
		<p><?php esc_html_e( 'Ключи Yandex SmartCaptcha для лид-форм темы (#hero-form, #signup). Независимо от настроек капчи плагина.', 'fs-lms-theme' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'fs_lms_theme_forms' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="fs_lms_theme_captcha_site_key"><?php esc_html_e( 'Site key', 'fs-lms-theme' ); ?></label></th>
					<td><input type="text" class="regular-text" id="fs_lms_theme_captcha_site_key" name="fs_lms_theme_captcha_site_key" value="<?php echo esc_attr( get_option( 'fs_lms_theme_captcha_site_key', '' ) ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="fs_lms_theme_captcha_server_key"><?php esc_html_e( 'Server key', 'fs-lms-theme' ); ?></label></th>
					<td><input type="text" class="regular-text" id="fs_lms_theme_captcha_server_key" name="fs_lms_theme_captcha_server_key" value="<?php echo esc_attr( get_option( 'fs_lms_theme_captcha_server_key', '' ) ); ?>"></td>
				</tr>
			</table>
			<p class="description"><?php esc_html_e( 'Если оба поля пустые, формы отправляются без капчи — защита держится на honeypot и таймере заполнения.', 'fs-lms-theme' ); ?></p>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

function fs_lms_theme_captcha_configured(): bool {
	return '' !== get_option( 'fs_lms_theme_captcha_site_key', '' ) && '' !== get_option( 'fs_lms_theme_captcha_server_key', '' );
}

/**
 * Подключение `captcha.js` — только если обе настройки заданы (иначе
 * форма отправляется без капчи, не ломается на голой установке).
 */
add_action( 'wp_enqueue_scripts', function (): void {
	if ( fs_lms_theme_captcha_configured() ) {
		wp_enqueue_script( 'fs-lms-theme-smartcaptcha', 'https://smartcaptcha.yandexcloud.net/captcha.js', array(), null, true );
	}
}, 25 );

/* --------------------------------------------------------------------
 * FormGuard — honeypot + HMAC-таймер (1:1 логика с FormGuardService
 * плагина, свой инстанс в теме).
 * ------------------------------------------------------------------ */

function fs_lms_theme_honeypot_field(): string {
	return 'fs_company';
}

function fs_lms_theme_form_salt(): string {
	if ( defined( 'FS_LMS_THEME_FORM_SALT' ) ) {
		return FS_LMS_THEME_FORM_SALT;
	}

	return defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
}

function fs_lms_theme_form_sign( string $timestamp ): string {
	return hash_hmac( 'sha256', $timestamp, fs_lms_theme_form_salt() );
}

/**
 * @return string Формат: "{timestamp}.{hmac}"
 */
function fs_lms_theme_form_timestamp_token(): string {
	$ts = (string) time();

	return $ts . '.' . fs_lms_theme_form_sign( $ts );
}

function fs_lms_theme_form_is_human( string $honeypot_value, string $token ): bool {
	if ( '' !== trim( $honeypot_value ) ) {
		return false;
	}

	$parts = explode( '.', $token, 2 );
	if ( 2 !== count( $parts ) ) {
		return false;
	}

	list( $ts, $sig ) = $parts;

	if ( ! ctype_digit( $ts ) || ! hash_equals( fs_lms_theme_form_sign( $ts ), $sig ) ) {
		return false;
	}

	$elapsed = time() - (int) $ts;

	return $elapsed >= FS_LMS_THEME_FORM_MIN_FILL_SECONDS && $elapsed <= FS_LMS_THEME_FORM_MAX_TOKEN_AGE;
}

/* --------------------------------------------------------------------
 * Капча — серверная проверка токена (fail-open при недоступности API,
 * как у YandexSmartCaptchaProvider плагина — не блокируем легитимных
 * пользователей отказом стороннего сервиса).
 * ------------------------------------------------------------------ */

function fs_lms_theme_captcha_validate( string $token, string $ip ): bool {
	$server_key = get_option( 'fs_lms_theme_captcha_server_key', '' );

	if ( '' === $server_key ) {
		return true;
	}

	if ( '' === $token ) {
		return false;
	}

	$response = wp_remote_post( 'https://smartcaptcha.yandexcloud.net/validate', array(
		'timeout' => 5,
		'body'    => array(
			'secret' => $server_key,
			'token'  => $token,
			'ip'     => $ip,
		),
	) );

	if ( is_wp_error( $response ) ) {
		return true;
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );

	if ( 200 !== $code || ! is_array( $body ) ) {
		return true;
	}

	return isset( $body['status'] ) && 'ok' === $body['status'];
}

/* --------------------------------------------------------------------
 * Rate-limit — простой transient-счётчик по хэшу IP (не копия
 * RateLimitService плагина).
 * ------------------------------------------------------------------ */

function fs_lms_theme_client_ip(): string {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
}

function fs_lms_theme_form_rate_limited( string $ip ): bool {
	$key   = 'fs_theme_form_' . sha1( $ip );
	$count = (int) get_transient( $key );

	if ( $count >= FS_LMS_THEME_FORM_RATE_LIMIT ) {
		return true;
	}

	set_transient( $key, $count + 1, FS_LMS_THEME_FORM_RATE_WINDOW );

	return false;
}

/* --------------------------------------------------------------------
 * AJAX-обработчик — общий для всех форм темы, различает форму по
 * скрытому полю `form_id` ('signup' / 'hero').
 * ------------------------------------------------------------------ */

add_action( 'wp_ajax_fs_theme_submit_form', 'fs_lms_theme_handle_form_submit' );
add_action( 'wp_ajax_nopriv_fs_theme_submit_form', 'fs_lms_theme_handle_form_submit' );

function fs_lms_theme_handle_form_submit(): void {
	check_ajax_referer( 'fs-theme-form', 'nonce' );

	$honeypot = isset( $_POST[ fs_lms_theme_honeypot_field() ] )
		? (string) wp_unslash( $_POST[ fs_lms_theme_honeypot_field() ] )
		: '';
	$token = isset( $_POST['fs_form_token'] ) ? (string) wp_unslash( $_POST['fs_form_token'] ) : '';

	if ( ! fs_lms_theme_form_is_human( $honeypot, $token ) ) {
		wp_send_json_error( array( 'message' => __( 'Не удалось отправить форму. Обновите страницу и попробуйте ещё раз.', 'fs-lms-theme' ) ), 400 );
	}

	$ip = fs_lms_theme_client_ip();

	if ( fs_lms_theme_form_rate_limited( $ip ) ) {
		wp_send_json_error( array( 'message' => __( 'Слишком много попыток. Попробуйте немного позже.', 'fs-lms-theme' ) ), 429 );
	}

	$captcha_token = isset( $_POST['smart-token'] ) ? sanitize_text_field( wp_unslash( $_POST['smart-token'] ) ) : '';
	if ( fs_lms_theme_captcha_configured() && ! fs_lms_theme_captcha_validate( $captcha_token, $ip ) ) {
		wp_send_json_error( array( 'message' => __( 'Проверка «Я не робот» не пройдена, попробуйте ещё раз.', 'fs-lms-theme' ) ), 400 );
	}

	$form_id = isset( $_POST['form_id'] ) ? sanitize_key( wp_unslash( $_POST['form_id'] ) ) : 'signup';
	$name    = isset( $_POST['parent_name'] ) ? sanitize_text_field( wp_unslash( $_POST['parent_name'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$grade   = isset( $_POST['grade'] ) ? sanitize_text_field( wp_unslash( $_POST['grade'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$page_url = isset( $_POST['page_url'] ) ? esc_url_raw( wp_unslash( $_POST['page_url'] ) ) : '';

	if ( '' === $name || '' === $phone ) {
		wp_send_json_error( array( 'message' => __( 'Заполните имя и телефон.', 'fs-lms-theme' ) ), 400 );
	}

	if ( ! preg_match( '/^[\d\s()+\-]{5,20}$/u', $phone ) ) {
		wp_send_json_error( array( 'message' => __( 'Проверьте номер телефона.', 'fs-lms-theme' ) ), 400 );
	}

	$lines = array(
		sprintf( 'Форма: %s', $form_id ),
		sprintf( 'Имя: %s', $name ),
		sprintf( 'Телефон: %s', $phone ),
	);

	if ( '' !== $grade ) {
		$lines[] = sprintf( 'Класс: %s', $grade );
	}
	if ( '' !== $subject ) {
		$lines[] = sprintf( 'Направление: %s', $subject );
	}

	$lines[] = sprintf( 'IP: %s', $ip );
	$lines[] = sprintf( 'Страница: %s', $page_url );

	$sent = wp_mail(
		FS_LMS_THEME_FORM_RECIPIENT,
		sprintf( 'Заявка с сайта — %s', $form_id ),
		implode( "\n", $lines )
	);

	if ( ! $sent ) {
		wp_send_json_error( array( 'message' => __( 'Не получилось отправить заявку, попробуйте позже или позвоните нам.', 'fs-lms-theme' ) ), 500 );
	}

	wp_send_json_success( array( 'message' => __( 'Спасибо! Мы перезвоним в течение рабочего дня.', 'fs-lms-theme' ) ) );
}
