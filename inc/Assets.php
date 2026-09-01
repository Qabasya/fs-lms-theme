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
	$css_path = get_template_directory() . '/assets/css/theme.min.css';
	if ( file_exists( $css_path ) ) {
		wp_enqueue_style( 'fs-lms-theme', get_template_directory_uri() . '/assets/css/theme.min.css', array(), filemtime( $css_path ) );
	}

	$js_path = get_template_directory() . '/assets/js/theme.min.js';
	if ( file_exists( $js_path ) ) {
		wp_enqueue_script( 'fs-lms-theme', get_template_directory_uri() . '/assets/js/theme.min.js', array(), filemtime( $js_path ), true );

		/**
		 * Quick view (Фаза 10.5, src/js/quick-view.js) — AJAX на
		 * `admin-ajax.php`, нужен `ajaxurl` (в футере/шапке WP его не отдаёт
		 * фронту сам, только в админке) и nonce под тот же `check_ajax_referer`,
		 * что в `inc/WooCommerce.php`.
		 */
		wp_localize_script( 'fs-lms-theme', 'fsLmsTheme', array(
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'quickViewNonce' => wp_create_nonce( 'fs-quick-view' ),
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
