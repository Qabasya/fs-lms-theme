<?php
/**
 * FS LMS Theme — bootstrap.
 *
 * Лёгкая блочная (FSE) тема под fs-lms: вся структура и токены — в theme.json,
 * PHP-слой только регистрирует поддержку блочных фич и грузит интерфейсный
 * шрифт тем же способом, что и плагин (см. BundleLoader::enqueueUiFont()).
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function (): void {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

	load_theme_textdomain( 'fs-lms-theme', get_template_directory() . '/languages' );
} );

/**
 * Категории паттернов темы — библиотека блоков-секций, из которых страницы
 * собираются вручную в редакторе (аналог библиотеки элементов Woodmart, но
 * на нативных блоках Gutenberg). Сами паттерны — файлы в patterns/,
 * WordPress регистрирует их автоматически по заголовку в докблоке файла.
 */
add_action( 'init', function (): void {
	register_block_pattern_category( 'fs-lms-layout', array( 'label' => __( 'FS LMS — шапка и футер', 'fs-lms-theme' ) ) );
	register_block_pattern_category( 'fs-lms-sections', array( 'label' => __( 'FS LMS — секции страницы', 'fs-lms-theme' ) ) );
} );

/**
 * Интерфейсные шрифты — Ubuntu (тот же источник, что и плагин, см.
 * Inc\Core\Assets\BundleLoader::enqueueUiFont()) плюс вес 300 и JetBrains Mono,
 * которые требует дизайн главной страницы (мокап «Главная — 1a»: лид-абзацы
 * весом 300, блок кода моноширинным).
 */
add_action( 'wp_enqueue_scripts', function (): void {
	wp_enqueue_style(
		'fs-lms-theme-ubuntu',
		'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&family=JetBrains+Mono:wght@400;500&display=swap',
		array(),
		null
	);
} );
