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

/** Лимит сабмитов с одного IP в окне rate-limit. */
const FS_LMS_THEME_FORM_RATE_LIMIT = 2;

/** Окно rate-limit по IP, сек. */
const FS_LMS_THEME_FORM_RATE_WINDOW = 15 * MINUTE_IN_SECONDS;

/**
 * Отдельный лимит по номеру телефона: спамер меняет IP (прокси, мобильная
 * сеть), но номер в форме обычно оставляет один и тот же. Считаем только
 * цифры номера, чтобы «+7 (999)…» и «8999…» попадали в один счётчик.
 */
const FS_LMS_THEME_FORM_PHONE_LIMIT = 2;

/** Окно лимита по номеру телефона, сек. */
const FS_LMS_THEME_FORM_PHONE_WINDOW = HOUR_IN_SECONDS;

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
 * Задача 9 (tasks.md, 2026-09-04): URL кнопки «Записаться» в шапке
 * (`patterns/header-nav.php`). Если на текущей странице есть форма
 * записи — просто якорь на неё (`#hero-form` у hero/subject-hero,
 * `#signup` у contact-section/subject-contact/courses-contact — берём тот,
 * что встречается раньше по разметке паттернов страницы). Если формы нет
 * (страницы WooCommerce, `/about/`, личный кабинет и т.д.) — якорь на
 * форму главной страницы.
 *
 * Определение «есть ли форма» — по наличию слага паттерна в
 * `post_content` текущей страницы: сами паттерны рендерятся как
 * `<!-- wp:pattern {"slug":"..."} /-->`, поэтому подстроки узнаваемы без
 * разбора блоков. Главная — особый случай: её hero/contact-section зашиты
 * прямо в `templates/front-page.html`, а не в `post_content` страницы.
 */
function fs_lms_theme_signup_button_url(): string {
	$home_anchor = home_url( '/#hero-form' );

	if ( is_front_page() ) {
		return '#hero-form';
	}

	$page = get_queried_object();

	if ( ! ( $page instanceof WP_Post ) ) {
		return $home_anchor;
	}

	$content = $page->post_content;

	if ( false !== strpos( $content, 'fs-lms-theme/hero' ) || false !== strpos( $content, 'fs-lms-theme/subject-hero' ) ) {
		return '#hero-form';
	}

	if (
		false !== strpos( $content, 'fs-lms-theme/contact-section' )
		|| false !== strpos( $content, 'fs-lms-theme/subject-contact' )
		|| false !== strpos( $content, 'fs-lms-theme/courses-contact' )
	) {
		return '#signup';
	}

	return $home_anchor;
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

/**
 * Счётчик отправок по произвольному ключу (IP, номер телефона).
 *
 * @param string $bucket Что считаем: 'ip:1.2.3.4', 'phone:79995551122'.
 * @param int    $limit  Сколько отправок допускаем в окне.
 * @param int    $window Длина окна, сек.
 *
 * @return bool true — лимит исчерпан, отправку принимать нельзя.
 */
function fs_lms_theme_form_rate_limited( string $bucket, int $limit, int $window ): bool {
	$key   = 'fs_theme_form_' . sha1( $bucket );
	$count = (int) get_transient( $key );

	if ( $count >= $limit ) {
		return true;
	}

	set_transient( $key, $count + 1, $window );

	return false;
}

/** Только цифры номера — чтобы «+7 (999) 555-11-22» и «8999…» считались одним. */
function fs_lms_theme_phone_digits( string $phone ): string {
	$digits = preg_replace( '/\D+/', '', $phone );

	if ( null === $digits || '' === $digits ) {
		return '';
	}

	// 8XXXXXXXXXX и 7XXXXXXXXXX — один и тот же номер.
	if ( 11 === strlen( $digits ) && '8' === $digits[0] ) {
		$digits = '7' . substr( $digits, 1 );
	}

	return $digits;
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

	if ( fs_lms_theme_form_rate_limited( 'ip:' . $ip, FS_LMS_THEME_FORM_RATE_LIMIT, FS_LMS_THEME_FORM_RATE_WINDOW ) ) {
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

	/**
	 * BugFix.4 (2026-09-05): имя — только кириллица, по тому же правилу, что
	 * в плагине fs-lms (`src/js/common/validators/CyrillicNameValidator.js`:
	 * `/^[А-Яа-яЁё\s-]+$/u` — буквы, пробелы и дефис для двойных имён).
	 * Ограничение длины плагин задаёт разметкой поля, своего значения там
	 * нет — берём 2–80 символов: короче осмысленного имени не бывает, а
	 * длиннее в заявке не нужно.
	 *
	 * Проверка на сервере, а не только в разметке и в `src/js/forms.js`:
	 * `pattern`/`minlength` и клиентский JS обходятся прямым запросом к
	 * `admin-ajax.php`.
	 */
	if ( ! preg_match( '/^[А-Яа-яЁё\s\-]{2,80}$/u', $name ) ) {
		wp_send_json_error(
			array( 'message' => __( 'В имени разрешены только буквы кириллицы, пробелы и дефис.', 'fs-lms-theme' ) ),
			400
		);
	}

	if ( ! preg_match( '/^[\d\s()+\-]{5,20}$/u', $phone ) ) {
		wp_send_json_error( array( 'message' => __( 'Проверьте номер телефона.', 'fs-lms-theme' ) ), 400 );
	}

	// Второй рубеж после лимита по IP: один и тот же номер, даже с разных
	// адресов, принимаем не чаще, чем задано окном.
	$phone_digits = fs_lms_theme_phone_digits( $phone );

	if ( '' !== $phone_digits && fs_lms_theme_form_rate_limited( 'phone:' . $phone_digits, FS_LMS_THEME_FORM_PHONE_LIMIT, FS_LMS_THEME_FORM_PHONE_WINDOW ) ) {
		wp_send_json_error( array( 'message' => __( 'Слишком много попыток. Попробуйте немного позже.', 'fs-lms-theme' ) ), 429 );
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
		wp_send_json_error( array( 'message' => __( 'Не получилось отправить заявку, попробуйте позже или позвоните нам: +7(995)326-44-86', 'fs-lms-theme' ) ), 500 );
	}

	wp_send_json_success( array( 'message' => __( 'Спасибо! Мы перезвоним в течение рабочего дня.', 'fs-lms-theme' ) ) );
}
