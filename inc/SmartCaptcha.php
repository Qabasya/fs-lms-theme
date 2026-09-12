<?php
/**
 * Yandex SmartCaptcha для лид-форм темы: настройки (Настройки → Формы),
 * контейнер виджета в разметке форм и серверная проверка токена.
 *
 * BugFix (2026-09-12): вынесено из `inc/Forms.php`, капча стала невидимой.
 *
 * 1. Виджет рендерит `src/js/captcha.js` с `invisible: true` — задание
 *    Яндекс показывает только при подозрении на бота. Раньше в разметке
 *    стоял `<div class="smart-captcha" data-sitekey>`, и `captcha.js`
 *    Яндекса сам превращал его в видимый чекбокс «Я не робот».
 * 2. `captcha.js` больше не подключается через `wp_enqueue_script` — его
 *    вставляет JS при первом фокусе в форме. На проде WP Rocket минифицировал
 *    внешний скрипт и отдавал его со своего домена
 *    (`/wp-content/cache/min/1/captcha.js`), а Яндекс строит адрес iframe
 *    виджета от `src` собственного скрипта: iframe открывал 404-страницу
 *    сайта («мини-версия сайта» вместо капчи). Скрипт, вставленный из JS,
 *    оптимизаторы HTML не переписывают.
 *
 * Ключи — свои у темы, независимо от капчи плагина fs-lms
 * (`SmartCaptchaSettingsController` не переиспользуем: тема не знает о
 * классах плагина).
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Smart_Captcha {

	private const HOST = 'https://smartcaptcha.yandexcloud.net';

	private const OPTION_GROUP = 'fs_lms_theme_forms';

	private const OPTION_SITE_KEY = 'fs_lms_theme_captcha_site_key';

	private const OPTION_SERVER_KEY = 'fs_lms_theme_captcha_server_key';

	private const SETTINGS_PAGE = 'fs-lms-theme-forms';

	/** Таймаут запроса к API проверки токена, сек. */
	private const VALIDATE_TIMEOUT = 5;

	public function register(): void {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_filter( 'wp_resource_hints', array( $this, 'add_resource_hints' ), 10, 2 );
	}

	/**
	 * Капча включена, только когда заданы оба ключа — иначе формы работают
	 * на honeypot, таймере и лимитах, не ломаясь на голой установке.
	 */
	public function is_configured(): bool {
		return '' !== $this->site_key() && '' !== $this->server_key();
	}

	public function site_key(): string {
		return (string) get_option( self::OPTION_SITE_KEY, '' );
	}

	private function server_key(): string {
		return (string) get_option( self::OPTION_SERVER_KEY, '' );
	}

	/**
	 * Пустой контейнер, в который `src/js/captcha.js` рендерит невидимый
	 * виджет. Ключ в разметку не кладём — JS берёт его из `fsLmsTheme`.
	 */
	public function slot_html(): string {
		return $this->is_configured() ? '<div class="fs-form-captcha" data-fs-captcha></div>' : '';
	}

	/**
	 * Проверка токена через API Яндекса. Fail-open при недоступности API,
	 * как у `YandexSmartCaptchaProvider` плагина: отказ стороннего сервиса не
	 * должен блокировать легитимных посетителей.
	 */
	public function validate( string $token, string $ip ): bool {
		if ( '' === $this->server_key() ) {
			return true;
		}

		if ( '' === $token ) {
			return false;
		}

		$response = wp_remote_post( self::HOST . '/validate', array(
			'timeout' => self::VALIDATE_TIMEOUT,
			'body'    => array(
				'secret' => $this->server_key(),
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

	/**
	 * Скрипт капчи грузится лениво, на первом фокусе в форме, — заранее
	 * открытое соединение убирает задержку на этом шаге.
	 *
	 * @param array<int, string|array<string, string>> $urls
	 *
	 * @return array<int, string|array<string, string>>
	 */
	public function add_resource_hints( array $urls, string $relation ): array {
		if ( 'preconnect' === $relation && ! is_admin() && $this->is_configured() ) {
			$urls[] = self::HOST;
		}

		return $urls;
	}

	public function add_settings_page(): void {
		add_options_page(
			__( 'Формы сайта', 'fs-lms-theme' ),
			__( 'Формы', 'fs-lms-theme' ),
			'manage_options',
			self::SETTINGS_PAGE,
			array( $this, 'render_settings_page' )
		);
	}

	public function register_settings(): void {
		foreach ( array( self::OPTION_SITE_KEY, self::OPTION_SERVER_KEY ) as $option ) {
			register_setting( self::OPTION_GROUP, $option, array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
			) );
		}
	}

	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Настройки форм', 'fs-lms-theme' ); ?></h1>
			<p><?php esc_html_e( 'Ключи Yandex SmartCaptcha для лид-форм темы (#hero-form, #signup). Независимо от настроек капчи плагина.', 'fs-lms-theme' ); ?></p>
			<form method="post" action="options.php">
				<?php settings_fields( self::OPTION_GROUP ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="<?php echo esc_attr( self::OPTION_SITE_KEY ); ?>"><?php esc_html_e( 'Site key', 'fs-lms-theme' ); ?></label></th>
						<td><input type="text" class="regular-text" id="<?php echo esc_attr( self::OPTION_SITE_KEY ); ?>" name="<?php echo esc_attr( self::OPTION_SITE_KEY ); ?>" value="<?php echo esc_attr( $this->site_key() ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="<?php echo esc_attr( self::OPTION_SERVER_KEY ); ?>"><?php esc_html_e( 'Server key', 'fs-lms-theme' ); ?></label></th>
						<td><input type="text" class="regular-text" id="<?php echo esc_attr( self::OPTION_SERVER_KEY ); ?>" name="<?php echo esc_attr( self::OPTION_SERVER_KEY ); ?>" value="<?php echo esc_attr( $this->server_key() ); ?>"></td>
					</tr>
				</table>
				<p class="description"><?php esc_html_e( 'Капча невидимая: задание появляется только при подозрении на бота. Если оба поля пустые, формы отправляются без капчи — защита держится на honeypot, таймере заполнения и лимитах.', 'fs-lms-theme' ); ?></p>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

( new FS_LMS_Theme_Smart_Captcha() )->register();

/**
 * Обёртки для разметки паттернов и `inc/Assets.php` — тот же приём, что у
 * `fs_lms_theme_name_field_attrs_html()` в `inc/Forms.php`.
 */
function fs_lms_theme_captcha_configured(): bool {
	return ( new FS_LMS_Theme_Smart_Captcha() )->is_configured();
}

function fs_lms_theme_captcha_slot_html(): string {
	return ( new FS_LMS_Theme_Smart_Captcha() )->slot_html();
}
