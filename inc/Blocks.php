<?php
/**
 * Кастомные Gutenberg-блоки (Фаза 4).
 *
 * Один источник правды — файловая структура src/blocks/<name>/block.json
 * (см. src/blocks/README.md): цикл по glob() сам находит все блоки, ничего
 * не хардкодится списком. Для каждого блока — если собранные assets/
 * существуют — сперва регистрируются JS/CSS-хендлы под именами, которые
 * ждёт block.json (`editorScript`/`style`), затем register_block_type()
 * читает сам block.json и подключает эти уже зарегистрированные хендлы.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Отдельная категория инсёртера для карточек дизайн-системы — чтобы не
 * путать с обычными «текст/медиа/дизайн» категориями ядра.
 */
add_filter( 'block_categories_all', function ( array $categories ): array {
	array_unshift( $categories, array(
		'slug'  => 'fs-lms-cards',
		'title' => __( 'FS LMS — карточки', 'fs-lms-theme' ),
	) );

	return $categories;
} );

/**
 * Фаза 17.3 — диагностика «Этот блок имеет неожидаемое или неверное
 * содержимое» на каждом кастомном блоке в редакторе.
 *
 * Причина такой картины почти всегда одна: тема развёрнута без собранных
 * `assets/` (каталог в `.gitignore`, его нет ни в git, ни в архиве после
 * `git archive`). PHP-регистрация блока при этом проходит (см. ниже
 * `register_block_type()` вызывается всегда), а вот JS-описание блока с
 * его `save()` в редактор не попадает — редактор видит блок, для которого
 * у него нет определения, и помечает содержимое как неверное.
 *
 * Разметка самих паттернов тут ни при чём — она сверена с выводом `save()`
 * всех блоков (26 вхождений, расхождений нет). Поэтому вместо молчаливой
 * поломки показываем администратору прямую подсказку.
 */
add_action( 'admin_notices', function (): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$blocks   = glob( get_template_directory() . '/src/blocks/*/block.json' );
	$built_js = glob( get_template_directory() . '/assets/js/blocks/*.min.js' );

	if ( empty( $blocks ) || ! empty( $built_js ) ) {
		return;
	}

	printf(
		'<div class="notice notice-error"><p><strong>%s</strong> %s <code>npm install &amp;&amp; npm run build:prod</code>.</p></div>',
		esc_html__( 'FS LMS Theme: тема развёрнута без собранных ассетов.', 'fs-lms-theme' ),
		esc_html__( 'Кастомные блоки будут показываться в редакторе как «неожидаемое или неверное содержимое», а часть стилей не подключится. Соберите тему командой', 'fs-lms-theme' )
	);
} );

add_action( 'init', function (): void {
	$blocks_dir    = get_template_directory() . '/src/blocks';
	$assets_js_dir = get_template_directory() . '/assets/js/blocks';
	$assets_js_url = get_template_directory_uri() . '/assets/js/blocks';
	$assets_css_dir = get_template_directory() . '/assets/css/blocks';
	$assets_css_url = get_template_directory_uri() . '/assets/css/blocks';

	$block_json_files = glob( $blocks_dir . '/*/block.json' );
	if ( ! $block_json_files ) {
		return;
	}

	foreach ( $block_json_files as $block_json_path ) {
		$dir  = dirname( $block_json_path );
		$name = basename( $dir );

		$editor_script_path = "{$assets_js_dir}/{$name}.min.js";
		if ( file_exists( $editor_script_path ) ) {
			wp_register_script(
				"fs-lms-{$name}-editor-script",
				"{$assets_js_url}/{$name}.min.js",
				array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
				filemtime( $editor_script_path ),
				true
			);
		}

		$editor_style_path = "{$assets_css_dir}/{$name}-editor.min.css";
		if ( file_exists( $editor_style_path ) ) {
			wp_register_style(
				"fs-lms-{$name}-editor-style",
				"{$assets_css_url}/{$name}-editor.min.css",
				array(),
				filemtime( $editor_style_path )
			);
		}

		$style_path = "{$assets_css_dir}/{$name}.min.css";
		if ( file_exists( $style_path ) ) {
			wp_register_style(
				"fs-lms-{$name}-style",
				"{$assets_css_url}/{$name}.min.css",
				array(),
				filemtime( $style_path )
			);
		}

		register_block_type( $dir );
	}
} );
