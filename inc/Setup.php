<?php
/**
 * Базовые возможности темы и общие стили блоков (дизайн-система, Фаза 2).
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
 * Стиль блока «Карточка» (core/group, is-style-card) — общая база для всех
 * карточных секций (курсы, преподаватели, отзывы, блог, статистика). Сама
 * CSS — src/scss/theme.scss, класс переиспользуют паттерны и кастомные
 * блоки Фазы 4/5.
 */
add_action( 'init', function (): void {
	register_block_style( 'core/group', array(
		'name'  => 'card',
		'label' => __( 'Карточка', 'fs-lms-theme' ),
	) );
} );
