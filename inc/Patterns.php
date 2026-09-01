<?php
/**
 * Категории паттернов темы — библиотека блоков-секций, из которых страницы
 * собираются вручную в редакторе (аналог библиотеки элементов Woodmart, но
 * на нативных блоках Gutenberg). Сами паттерны — файлы в patterns/,
 * WordPress регистрирует их автоматически по заголовку в докблоке файла.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function (): void {
	register_block_pattern_category( 'fs-lms-layout', array( 'label' => __( 'FS LMS — шапка и футер', 'fs-lms-theme' ) ) );
	register_block_pattern_category( 'fs-lms-sections', array( 'label' => __( 'FS LMS — секции страницы', 'fs-lms-theme' ) ) );
} );
