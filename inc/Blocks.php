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
