<?php
/**
 * Обновление темы из GitHub Releases (`Qabasya/fs-lms-theme`) — тот же мост,
 * что у плагина (`fs-lms/inc/Services/Update/GithubReleaseUpdater.php`):
 * тема не в каталоге wordpress.org, и штатная проверка обновлений её не
 * видит.
 *
 * ### Что делает
 *
 * - `pre_set_site_transient_update_themes` — в цикле проверки WP (обычно раз
 *   в ~12ч или кнопкой «Проверить снова» в «Консоль → Обновления») сверяет
 *   `tag_name` последнего релиза с `Version:` из `style.css`. Если релиз
 *   новее — кладёт в транзиент ссылку на ZIP-ассет, который собирает
 *   `.github/workflows/release.yml`, и экран «Внешний вид → Темы» показывает
 *   стандартное «Доступна новая версия», обновление — по кнопке.
 * - «Посмотреть информацию о версии» открывает окно с текстом релиза: WP
 *   грузит адрес из транзиента во фрейм, а страницу GitHub во фрейме
 *   открыть нельзя, поэтому адрес ведёт на свой `admin-ajax`-обработчик.
 *
 * ### Приватный репозиторий
 *
 * Без токена GitHub API отдаёт по приватному репозиторию 404 — проверка
 * тихо не находит обновлений. Токен (fine-grained, доступ только к
 * `fs-lms-theme`, права `Contents: Read-only`) задаётся константой в
 * `wp-config.php`: `define( 'FS_LMS_THEME_GITHUB_TOKEN', '…' );` — не в
 * базе и не в коде темы. С токеном пакет качается через API ассета: GitHub
 * отвечает редиректом на временную подписанную ссылку, и её забираем уже
 * без токена (`upgrader_pre_download`). HTTP-клиент WP пересылает заголовки
 * при редиректе, а подписанная ссылка с лишним `Authorization` не
 * открывается.
 *
 * ### Отказоустойчивость
 *
 * Сеть недоступна / API упал / рейт-лимит — fail-open: тема работает на
 * текущей версии, обновление в этом цикле просто не появляется. Ответ API
 * кэшируется на 6 часов (без токена лимит — 60 запросов в час на IP);
 * «Проверить снова» кэш обходит.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Github_Updater {

	private const REPO = 'Qabasya/fs-lms-theme';

	private const CACHE_KEY = 'fs_lms_theme_github_release';

	private const CACHE_TTL = 6 * HOUR_IN_SECONDS;

	private const NOTES_ACTION = 'fs_lms_theme_release_notes';

	public function register(): void {
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_update' ) );
		add_filter( 'upgrader_pre_download', array( $this, 'download_private_package' ), 10, 2 );
		add_action( 'wp_ajax_' . self::NOTES_ACTION, array( $this, 'render_release_notes' ) );
	}

	/**
	 * @param mixed $transient Значение транзиента `update_themes` (обычно stdClass).
	 *
	 * @return mixed
	 */
	public function check_for_update( $transient ) {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}

		$release = $this->latest_release();

		if ( null === $release || null === $release['package'] ) {
			return $transient;
		}

		$theme = get_template();

		if ( ! version_compare( $release['version'], (string) wp_get_theme( $theme )->get( 'Version' ), '>' ) ) {
			return $transient;
		}

		if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
			$transient->response = array();
		}

		$transient->response[ $theme ] = array(
			'theme'        => $theme,
			'new_version'  => $release['version'],
			'url'          => add_query_arg( 'action', self::NOTES_ACTION, admin_url( 'admin-ajax.php' ) ),
			'package'      => $release['package'],
			'requires'     => '',
			'requires_php' => '',
		);

		return $transient;
	}

	/**
	 * Пакет из приватного репозитория: авторизованный запрос к API ассета
	 * без перехода по редиректу, затем скачивание по подписанной ссылке.
	 *
	 * @param mixed  $reply   `false` — пусть WP качает сам; путь к файлу или WP_Error.
	 * @param string $package Адрес пакета из транзиента.
	 *
	 * @return mixed
	 */
	public function download_private_package( $reply, $package ) {
		if ( false !== $reply || '' === $this->token() || 0 !== strpos( (string) $package, $this->assets_api_url() ) ) {
			return $reply;
		}

		$response = wp_remote_get(
			(string) $package,
			array(
				'timeout'     => 30,
				'redirection' => 0,
				'headers'     => $this->api_headers( 'application/octet-stream' ),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$location = (string) wp_remote_retrieve_header( $response, 'location' );

		if ( '' === $location ) {
			return new WP_Error(
				'fs_lms_theme_package',
				__( 'GitHub не отдал ссылку на пакет темы — проверьте токен FS_LMS_THEME_GITHUB_TOKEN.', 'fs-lms-theme' )
			);
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';

		return download_url( $location, 300 );
	}

	/**
	 * Окно «Посмотреть информацию о версии» на экране тем.
	 */
	public function render_release_notes(): void {
		if ( ! current_user_can( 'update_themes' ) ) {
			wp_die( esc_html__( 'Недостаточно прав.', 'fs-lms-theme' ), '', array( 'response' => 403 ) );
		}

		$release = $this->latest_release();

		if ( null === $release ) {
			wp_die( esc_html__( 'Не удалось получить релиз с GitHub, попробуйте позже.', 'fs-lms-theme' ) );
		}

		printf(
			'<!doctype html><html lang="ru"><head><meta charset="utf-8"><title>%1$s</title></head><body style="margin:24px;font:15px/1.6 -apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif"><h1 style="font-size:22px">%1$s</h1><p><a href="%2$s" target="_blank" rel="noopener">%3$s</a></p><pre style="white-space:pre-wrap;font:inherit">%4$s</pre></body></html>',
			esc_html( sprintf( 'FS LMS Theme %s', $release['version'] ) ),
			esc_url( $release['html_url'] ),
			esc_html__( 'Полный релиз на GitHub', 'fs-lms-theme' ),
			esc_html( $release['body'] )
		);
		exit;
	}

	/**
	 * Последний релиз из кэша либо GitHub API. `null` — сеть/API недоступны
	 * или ответ неожиданный: вызывающий код трактует это как «обновлений нет».
	 *
	 * @return array{version: string, html_url: string, body: string, package: ?string}|null
	 */
	private function latest_release(): ?array {
		$cached = get_site_transient( self::CACHE_KEY );

		if ( is_array( $cached ) && ! $this->is_forced_check() ) {
			return $cached;
		}

		$response = wp_remote_get(
			sprintf( 'https://api.github.com/repos/%s/releases/latest', self::REPO ),
			array(
				'timeout' => 5,
				'headers' => $this->api_headers( 'application/vnd.github+json' ),
			)
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return null;
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $data ) || empty( $data['tag_name'] ) ) {
			return null;
		}

		$release = array(
			'version'  => ltrim( (string) $data['tag_name'], 'v' ),
			'html_url' => (string) ( $data['html_url'] ?? 'https://github.com/' . self::REPO . '/releases/latest' ),
			'body'     => (string) ( $data['body'] ?? '' ),
			'package'  => $this->package_url( is_array( $data['assets'] ?? null ) ? $data['assets'] : array() ),
		);

		set_site_transient( self::CACHE_KEY, $release, self::CACHE_TTL );

		return $release;
	}

	/**
	 * Ссылка на ZIP-ассет релиза (`fs-lms-theme-X.Y.Z.zip`). Для приватного
	 * репозитория — адрес ассета в API: публичная `browser_download_url`
	 * без сессии GitHub не открывается.
	 *
	 * @param array<int, array<string, mixed>> $assets Секция `assets` ответа API.
	 */
	private function package_url( array $assets ): ?string {
		foreach ( $assets as $asset ) {
			if ( '.zip' !== substr( (string) ( $asset['name'] ?? '' ), -4 ) ) {
				continue;
			}

			$key = '' === $this->token() ? 'browser_download_url' : 'url';

			return isset( $asset[ $key ] ) ? (string) $asset[ $key ] : null;
		}

		return null;
	}

	/**
	 * @return array<string, string>
	 */
	private function api_headers( string $accept ): array {
		$headers = array(
			'Accept'     => $accept,
			'User-Agent' => 'FS-LMS-Theme-Updater',
		);

		if ( '' !== $this->token() ) {
			$headers['Authorization'] = 'Bearer ' . $this->token();
		}

		return $headers;
	}

	private function token(): string {
		return defined( 'FS_LMS_THEME_GITHUB_TOKEN' ) ? (string) FS_LMS_THEME_GITHUB_TOKEN : '';
	}

	private function assets_api_url(): string {
		return sprintf( 'https://api.github.com/repos/%s/releases/assets/', self::REPO );
	}

	/**
	 * «Проверить снова» (`update-core.php?force-check=1`) — мимо кэша, иначе
	 * свежий релиз был бы виден только через 6 часов.
	 */
	private function is_forced_check(): bool {
		return is_admin() && isset( $_GET['force-check'] ) && 1 === absint( wp_unslash( $_GET['force-check'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- только читаем флаг, как само ядро на update-core.php.
	}
}

( new FS_LMS_Theme_Github_Updater() )->register();
