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
 * Интерфейсные шрифты — Ubuntu 300/400/500/700 и JetBrains Mono 400/500
 * (мокап «Главная — 1a»: лид-абзацы весом 300, блок кода моноширинным).
 *
 * 152-ФЗ (2026-09-29): шрифты отдаются с нашего домена
 * (`src/scss/fonts.scss` → `assets/css/fonts.min.css`), а не с Google Fonts —
 * иначе IP каждого посетителя уходит Google (США), а политика
 * конфиденциальности (п. 10.1) заявляет, что трансграничной передачи нет.
 *
 * Путь относительно каталога темы.
 */
const FS_LMS_THEME_FONT_CSS = 'assets/css/fonts.min.css';

/**
 * URL локального CSS шрифтов с версией по filemtime().
 *
 * @return string Пустая строка — сборки ещё нет (до `npm run build`).
 */
function fs_lms_theme_font_css_url(): string {
	$path = get_template_directory() . '/' . FS_LMS_THEME_FONT_CSS;

	if ( ! file_exists( $path ) ) {
		return '';
	}

	return add_query_arg( 'ver', (string) filemtime( $path ), get_template_directory_uri() . '/' . FS_LMS_THEME_FONT_CSS );
}

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

/**
 * У плагина fs-lms свой локальный файл шрифтов (хендл `fs-lms-fonts`,
 * Ubuntu 400/500/700 + JetBrains Mono + Roboto) — тема подключает свой
 * всегда: веса 300 у плагина нет. Одинаковые @font-face двух файлов браузер
 * не качает дважды — берёт последнее объявленное правило.
 */
add_action( 'wp_enqueue_scripts', function (): void {
	$url = fs_lms_theme_font_css_url();

	if ( '' !== $url ) {
		wp_enqueue_style( 'fs-lms-theme-fonts', $url, array(), null );
	}
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
 * `add_editor_style()` сам скоупит правила под `.editor-styles-wrapper` —
 * шрифт нужен здесь отдельно: `wp_enqueue_scripts` внутри iframe редактора
 * не отрабатывает, поэтому ни подключение темы, ни `BundleLoader` плагина в
 * холст не попадают, и текст рисовался бы запасной гарнитурой. Локальный
 * файл редактор инлайнит с `baseURL` = URL файла, так что относительные
 * `url('../fonts/…')` в @font-face резолвятся верно.
 */
add_action( 'after_setup_theme', function (): void {
	$styles = fs_lms_theme_style_bundle();

	if ( file_exists( get_template_directory() . '/' . FS_LMS_THEME_FONT_CSS ) ) {
		array_unshift( $styles, FS_LMS_THEME_FONT_CSS );
	}

	if ( file_exists( get_template_directory() . '/assets/css/editor.min.css' ) ) {
		$styles[] = 'assets/css/editor.min.css';
	}

	add_editor_style( $styles );
} );
