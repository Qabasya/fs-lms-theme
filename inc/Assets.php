<?php
/**
 * Подключение шрифтов и собранных CSS/JS темы (assets/, см. gulpfile.js).
 *
 * Версионирование — filemtime(), тем же способом, что и
 * Inc\Core\Assets\BundleLoader плагина: кэш сбрасывается сам при каждой
 * пересборке, без ручного бампа номера версии.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Интерфейсные шрифты — Ubuntu (тот же источник, что и плагин, см.
 * Inc\Core\Assets\BundleLoader::enqueueUiFont(), хендл `fs-lms-ubuntu`)
 * плюс вес 300 и JetBrains Mono, которые требует дизайн главной страницы
 * (мокап «Главная — 1a»: лид-абзацы весом 300, блок кода моноширинным).
 *
 * `BundleLoader::enqueueUiFont()` вешается на тот же `wp_enqueue_scripts`
 * безусловно на каждом фронт-запросе (см. Inc\Core\Enqueue::enqueue()) —
 * значит на любой странице, где активен плагин, Ubuntu 400/500/700 уже
 * загружен под хендлом `fs-lms-ubuntu`. Поздний приоритет (20) даёт этому
 * хуку плагина отработать первым, и если хендл уже зарегистрирован —
 * тема не заказывает те же веса Ubuntu повторно, а докидывает отдельным
 * запросом только то, чего у плагина нет (вес 300 + JetBrains Mono). Без
 * плагина (или если хендл не найден) — тема заказывает полный набор сама.
 */
add_action( 'wp_enqueue_scripts', function (): void {
	if ( wp_style_is( 'fs-lms-ubuntu', 'registered' ) || wp_style_is( 'fs-lms-ubuntu', 'enqueued' ) ) {
		wp_enqueue_style(
			'fs-lms-theme-ubuntu-extra',
			'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&family=JetBrains+Mono:wght@400;500&display=swap',
			array(),
			null
		);
		return;
	}

	wp_enqueue_style(
		'fs-lms-theme-ubuntu',
		'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&family=JetBrains+Mono:wght@400;500&display=swap',
		array(),
		null
	);
}, 20 );

/**
 * Фронт темы — src/scss/theme.scss → assets/css/theme.min.css,
 * src/js/theme.js → assets/js/theme.min.js.
 */
add_action( 'wp_enqueue_scripts', function (): void {
	/**
	 * Splide core CSS (Фаза 12.0, gulpfile.js — таск `styles:vendor`,
	 * копия из node_modules без сборки) — подключается раньше темы, чтобы
	 * .fs-* классы каруселей в theme.min.css могли переопределять его при
	 * необходимости.
	 */
	$splide_css_path = get_template_directory() . '/assets/css/vendor/splide-core.min.css';
	if ( file_exists( $splide_css_path ) ) {
		wp_enqueue_style( 'fs-lms-theme-splide', get_template_directory_uri() . '/assets/css/vendor/splide-core.min.css', array(), filemtime( $splide_css_path ) );
	}

	$css_path = get_template_directory() . '/assets/css/theme.min.css';
	if ( file_exists( $css_path ) ) {
		wp_enqueue_style( 'fs-lms-theme', get_template_directory_uri() . '/assets/css/theme.min.css', array_filter( array( file_exists( $splide_css_path ) ? 'fs-lms-theme-splide' : null ) ), filemtime( $css_path ) );
	}

	$js_path = get_template_directory() . '/assets/js/theme.min.js';
	if ( file_exists( $js_path ) ) {
		wp_enqueue_script( 'fs-lms-theme', get_template_directory_uri() . '/assets/js/theme.min.js', array(), filemtime( $js_path ), true );

		/**
		 * Формы (Фаза 14, src/js/forms.js) — AJAX на `admin-ajax.php`, нужен
		 * `ajaxurl` (в футере/шапке WP его не отдаёт фронту сам, только в
		 * админке) и nonce под `check_ajax_referer('fs-theme-form', …)` в
		 * `inc/Forms.php`. `quickViewNonce` здесь был до Фазы 16.2 (quick view
		 * каталога WooCommerce, Фаза 10.5, убран — новый макет магазина его
		 * не предусматривает).
		 */
		$captcha = new FS_LMS_Theme_Smart_Captcha();

		wp_localize_script( 'fs-lms-theme', 'fsLmsTheme', array(
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'formNonce'      => wp_create_nonce( 'fs-theme-form' ),
			/**
			 * `captchaSiteKey` пуст, если Yandex SmartCaptcha не настроена в
			 * Настройки → Формы — тогда `src/js/captcha.js` не грузит скрипт
			 * Яндекса, форма отправляется без капчи.
			 */
			'captchaSiteKey' => $captcha->is_configured() ? $captcha->site_key() : '',
		) );
	}
} );

/**
 * Стили внутри редактора — src/scss/editor.scss → assets/css/editor.min.css.
 * add_editor_style() сам скоупит правила под .editor-styles-wrapper.
 */
add_action( 'after_setup_theme', function (): void {
	$css_path = get_template_directory() . '/assets/css/editor.min.css';
	if ( file_exists( $css_path ) ) {
		add_editor_style( 'assets/css/editor.min.css' );
	}
} );
