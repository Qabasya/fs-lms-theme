<?php
/**
 * Сайдбар страницы магазина (`get_sidebar('shop')`, Фаза 10.4) — вызывается
 * из хука `woocommerce_before_main_content` в `inc/WooCommerce.php`.
 *
 * Если редактор ничего не настроил в Виджетах (зона `shop-sidebar`,
 * `inc/Setup.php`) — дефолт из кода: категории товаров + ценовой фильтр,
 * тот же принцип «контент по умолчанию в коде», что и у остальных
 * паттернов темы, без обязательной ручной настройки после активации.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<aside class="fs-shop-sidebar">
	<?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
		<?php dynamic_sidebar( 'shop-sidebar' ); ?>
	<?php elseif ( class_exists( 'WooCommerce' ) ) : ?>
		<?php
		/**
		 * `the_widget()` само оборачивает вывод в `before_widget`/`after_widget`
		 * (по умолчанию `<div class="widget %s">`) — здесь передан тот же
		 * формат, что и `register_sidebar()` выше, но с одним `%s` вместо
		 * `%1$s`/`%2$s`: `the_widget()` подставляет в `sprintf()` только класс
		 * виджета, без id (в отличие от `dynamic_sidebar()`).
		 */
		$widget_args = array(
			'before_widget' => '<div class="fs-widget sidebar-widget %s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="fs-widget__title widget-title">',
			'after_title'   => '</h5>',
		);
		?>
		<?php the_widget( 'WC_Widget_Product_Categories', array(
			'title'        => __( 'Категории', 'fs-lms-theme' ),
			'count'        => true,
			'hierarchical' => true,
			'dropdown'     => false,
		), $widget_args ); ?>
		<?php the_widget( 'WC_Widget_Price_Filter', array(
			'title' => __( 'Цена', 'fs-lms-theme' ),
		), $widget_args ); ?>
	<?php endif; ?>
</aside>
