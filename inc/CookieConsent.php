<?php
/**
 * Согласие на аналитические cookie (152-ФЗ, 2026-09-29).
 *
 * Политика конфиденциальности (раздел 8) называет основанием для «данных
 * cookie и веб-аналитики» согласие субъекта — значит Яндекс Метрика не
 * должна запускаться, пока посетитель его не дал. Счётчик выводит сторонний
 * плагин `wp-yandex-metrika` (инлайн-скрипт в `wp_head`, `<noscript>`-пиксель
 * в `wp_footer`), настроек «ждать согласия» у него нет, поэтому:
 *
 * 1. Вывод `wp_head`/`wp_footer` буферизуется, и `<script>` со ссылкой на
 *    `mc.yandex.ru/metrika/tag.js` получает `type="text/plain"
 *    data-fs-consent="analytics"` — браузер его не выполняет. `<noscript>`-
 *    пиксель вырезается: без JS согласие спросить нельзя. Ищем по адресу
 *    Метрики, а не по разметке плагина — переживёт обновления плагина и
 *    ручную вставку счётчика.
 * 2. `src/js/cookie-consent.js` показывает баннер; по «Принять» — выполняет
 *    отложенные скрипты, «Только необходимые» — нет. Выбор хранится в
 *    cookie `fs_cookie_consent` (сама она — техническая, без неё выбор
 *    не запомнить). Кнопка «Настройки cookie» в подвале снова открывает
 *    баннер — согласие можно отозвать.
 * 3. До согласия в `<head>` стоит заглушка `window.ym` с очередью: скрипты
 *    плагина (`frontend.min.js`, электронная коммерция) вызывают `ym()`
 *    сразу, без неё они падали бы с ReferenceError. Очередь живёт только в
 *    памяти страницы и уходит в Яндекс, лишь если счётчик запустится.
 *
 * Выбор проверяется в браузере, а не на сервере: WP Rocket отдаёт всем одну
 * закэшированную страницу. Скрипт и стили — отдельными файлами, а не в
 * бандле темы: bare-шеллы плагина (кабинет, плеер, контрольная) печатают
 * `wp_head`/`wp_footer`, а значит и счётчик, но стилей/скриптов темы там нет.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Cookie_Consent {

	/** Хендл скрипта и стилей баннера. */
	private const HANDLE = 'fs-lms-theme-cookie-consent';

	/** Метка отложенных скриптов — её же ищет `src/js/cookie-consent.js`. */
	private const CATEGORY = 'analytics';

	/** Адрес загрузчика Метрики внутри инлайн-счётчика. */
	private const METRIKA_TAG = 'mc.yandex.ru/metrika/tag.js';

	/** Адрес пикселя Метрики в `<noscript>`. */
	private const METRIKA_PIXEL = 'mc.yandex.ru/watch/';

	public function register(): void {
		if ( is_admin() ) {
			return;
		}

		add_action( 'wp_head', array( $this, 'print_ym_stub' ), PHP_INT_MIN );
		add_action( 'wp_head', array( $this, 'start_buffer' ), PHP_INT_MIN );
		add_action( 'wp_head', array( $this, 'flush_buffer' ), PHP_INT_MAX );
		add_action( 'wp_footer', array( $this, 'start_buffer' ), PHP_INT_MIN );
		add_action( 'wp_footer', array( $this, 'flush_buffer' ), PHP_INT_MAX );
		add_action( 'wp_footer', array( $this, 'print_banner' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_filter( 'script_loader_tag', array( $this, 'exclude_from_delay' ), 10, 2 );
	}

	/**
	 * Заглушка `ym()` с очередью — та же, что в начале официального кода
	 * счётчика (он делает `m[i] = m[i] || …` и нашу не перезапишет).
	 */
	public function print_ym_stub(): void {
		echo "<script>window.ym=window.ym||function(){(window.ym.a=window.ym.a||[]).push(arguments)};window.ym.l=1*new Date();</script>\n";
	}

	public function start_buffer(): void {
		ob_start();
	}

	public function flush_buffer(): void {
		$html = ob_get_clean();

		if ( false === $html ) {
			return;
		}

		echo $this->defer_trackers( $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- уже готовый вывод wp_head/wp_footer других модулей, меняем только атрибуты <script>.
	}

	/**
	 * Отключает счётчик Метрики в готовом HTML до согласия.
	 *
	 * @param string $html Вывод `wp_head` или `wp_footer`.
	 */
	public function defer_trackers( string $html ): string {
		if ( ! str_contains( $html, 'mc.yandex.ru' ) ) {
			return $html;
		}

		$html = (string) preg_replace_callback(
			'#<script\b([^>]*)>((?:(?!</script>).)*?' . preg_quote( self::METRIKA_TAG, '#' ) . '.*?)</script>#is',
			static function ( array $m ): string {
				$attrs = (string) preg_replace( '#\s+type=(["\'])[^"\']*\1#i', '', $m[1] );

				return '<script type="text/plain" data-fs-consent="' . self::CATEGORY . '"' . $attrs . '>' . $m[2] . '</script>';
			},
			$html
		);

		return (string) preg_replace(
			'#<noscript\b[^>]*>(?:(?!</noscript>).)*?' . preg_quote( self::METRIKA_PIXEL, '#' ) . '.*?</noscript>#is',
			'',
			$html
		);
	}

	public function enqueue(): void {
		$dir = get_template_directory();
		$uri = get_template_directory_uri();

		if ( file_exists( $dir . '/assets/css/cookie-consent.min.css' ) ) {
			wp_enqueue_style( self::HANDLE, $uri . '/assets/css/cookie-consent.min.css', array(), (string) filemtime( $dir . '/assets/css/cookie-consent.min.css' ) );
		}

		if ( file_exists( $dir . '/assets/js/cookie-consent.min.js' ) ) {
			wp_enqueue_script( self::HANDLE, $uri . '/assets/js/cookie-consent.min.js', array(), (string) filemtime( $dir . '/assets/js/cookie-consent.min.js' ), true );
		}
	}

	/**
	 * WP Rocket «Delay JavaScript execution» откладывает скрипты до первого
	 * движения мыши/скролла — баннер появлялся бы с опозданием. `nowprocket`
	 * исключает скрипт из отложенной загрузки и минификации WP Rocket,
	 * `data-no-optimize` — то же для LiteSpeed Cache/Autoptimize.
	 *
	 * @param string $tag    Готовый тег `<script>`.
	 * @param string $handle Хендл скрипта.
	 */
	public function exclude_from_delay( string $tag, string $handle ): string {
		if ( self::HANDLE !== $handle ) {
			return $tag;
		}

		return str_replace( '<script ', '<script nowprocket data-no-optimize="1" ', $tag );
	}

	/**
	 * Разметка баннера — скрыта до решения скрипта (без JS не показывается:
	 * счётчик без JS и так не работает).
	 */
	public function print_banner(): void {
		?>
		<div class="fs-cookie-banner" role="dialog" aria-live="polite" aria-label="<?php esc_attr_e( 'Согласие на использование cookie', 'fs-lms-theme' ); ?>" data-fs-cookie-banner hidden>
			<p class="fs-cookie-banner__text">
				<?php esc_html_e( 'Мы используем файлы cookie. Необходимые обеспечивают работу сайта: корзину и вход в личный кабинет. С вашего согласия мы также используем сервис Яндекс Метрика, чтобы анализировать посещаемость и улучшать сайт.', 'fs-lms-theme' ); ?>
				<br>
				<?php
				printf(
					/* translators: %s — ссылка на политику конфиденциальности. */
					esc_html__( 'Подробнее — в %s.', 'fs-lms-theme' ),
					'<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'политике конфиденциальности', 'fs-lms-theme' ) . '</a>'
				);
				?>
			</p>
			<div class="fs-cookie-banner__actions">
				<button type="button" class="fs-cookie-banner__btn fs-cookie-banner__btn--secondary" data-fs-cookie-choice="necessary"><?php esc_html_e( 'Только необходимые', 'fs-lms-theme' ); ?></button>
				<button type="button" class="fs-cookie-banner__btn fs-cookie-banner__btn--primary" data-fs-cookie-choice="all"><?php esc_html_e( 'Принять все', 'fs-lms-theme' ); ?></button>
			</div>
		</div>
		<?php
	}
}

( new FS_LMS_Theme_Cookie_Consent() )->register();
