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

	/**
	 * Магазин WooCommerce (Фаза 10.3) — страница `/shop/` (каталог курсов
	 * как товаров). Без этого WooCommerce рендерит архив/одиночный товар
	 * блочными шаблонами по умолчанию, игнорируя часть хуков темы (сайдбар,
	 * колонки сетки — inc/WooCommerce.php), на которые опирается Фаза 10.4/10.5.
	 */
	add_theme_support( 'woocommerce' );

	/**
	 * Логотип-марка в шапке/футере (`wp:site-logo`, паттерны Фазы 3). Квадрат
	 * под мокап («ШБ» на синем фоне) — редактор загружает картинку сам, без
	 * жёстко зашитых инициалов в паттерне.
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 76,
		'width'       => 76,
		'flex-height' => true,
		'flex-width'  => true,
	) );

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

/**
 * Сайдбар страницы магазина `/shop/` (Фаза 10.4: категории товаров + ценовой
 * фильтр) удалён в Фазе 16.2 — новый макет (`Магазин - мокап.dc.html`) чистая
 * сетка без сайдбара, по решению пользователя убран, а не просто перекрашен.
 * `sidebar-shop.php` тоже удалён; связанные хуки — в `inc/WooCommerce.php`.
 */
