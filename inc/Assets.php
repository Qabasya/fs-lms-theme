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
const FS_LMS_THEME_FONT_URL = 'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&family=JetBrains+Mono:wght@400;500&display=swap';

/**
 * CSS-бандл темы в порядке подключения — один список на фронт и на редактор.
 *
 * BugFix (2026-09-07): раньше список жил только внутри `wp_enqueue_scripts`,
 * а редактору через `add_editor_style()` отдавался отдельный крошечный
 * `editor.min.css` — он «зеркалил» лишь несколько правил. В итоге холст
 * Редактора сайта рисовал страницы без `.fs-*`-стилей: шапка выглядела
 * прилично (её держат theme.json и стили блоков ядра, которые редактор
 * грузит сам), а секции — голым HTML. Теперь редактор получает ровно тот же
 * бандл, что и фронт, и разъехаться они больше не могут.
 *
 * @return string[] Пути относительно каталога темы; отсутствующие файлы
 *                  отсеиваются (сборки может не быть до `npm run build`).
 */
function fs_lms_theme_style_bundle(): array {
	$files = array(
		'assets/css/vendor/splide-core.min.css',
		'assets/css/theme.min.css',
	);

	return array_values(
		array_filter(
			$files,
			static fn( string $file ): bool => file_exists( get_template_directory() . '/' . $file )
		)
	);
}

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

	wp_enqueue_style( 'fs-lms-theme-ubuntu', FS_LMS_THEME_FONT_URL, array(), null );
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
	$handles = array(
		'assets/css/vendor/splide-core.min.css' => 'fs-lms-theme-splide',
		'assets/css/theme.min.css'              => 'fs-lms-theme',
	);

	$deps = array();

	foreach ( fs_lms_theme_style_bundle() as $file ) {
		$handle = $handles[ $file ] ?? 'fs-lms-theme-' . sanitize_key( basename( $file, '.css' ) );

		wp_enqueue_style(
			$handle,
			get_template_directory_uri() . '/' . $file,
			$deps,
			filemtime( get_template_directory() . '/' . $file )
		);

		$deps[] = $handle;
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
 * Стили внутри редактора: тот же бандл, что на фронте, плюс шрифты и
 * `editor.min.css` последним — только правки, осмысленные лишь в холсте
 * (`src/scss/editor.scss`), они должны перебивать общий бандл.
 *
 * `add_editor_style()` сам скоупит правила под `.editor-styles-wrapper` и
 * умеет внешние URL (`get_editor_stylesheets()`, `wp-includes/theme.php`,
 * ветка `preg_match( '~^(https?:)?//~' )`) — шрифт нужен здесь отдельно:
 * `wp_enqueue_scripts` внутри iframe редактора не отрабатывает, поэтому ни
 * подключение темы, ни `BundleLoader` плагина в холст не попадают, и текст
 * рисовался бы запасной гарнитурой.
 */
add_action( 'after_setup_theme', function (): void {
	$styles = array_merge( array( FS_LMS_THEME_FONT_URL ), fs_lms_theme_style_bundle() );

	if ( file_exists( get_template_directory() . '/assets/css/editor.min.css' ) ) {
		$styles[] = 'assets/css/editor.min.css';
	}

	add_editor_style( $styles );
} );
