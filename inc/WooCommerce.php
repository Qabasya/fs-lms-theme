<?php
/**
 * Каталог WooCommerce (Фаза 10.3+) — база подключения, без переопределения
 * шаблонов WooCommerce целиком: используем штатные хуки/фильтры плагина,
 * чтобы пережить его обновления и не тащить в тему копию его PHP-шаблонов
 * (см. tasks.md, Фаза 10, план `sequential-nibbling-cat.md`).
 *
 * `add_theme_support('woocommerce')` — в inc/Setup.php.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Blocks (Mini Cart + Customer Account) автоматически
 * вставляют себя рядом с любым `core/navigation` через Block Hooks API
 * (см. `refs/woocommerce/src/Blocks/Utils/BlockHooksTrait.php`,
 * `register_hooked_block()` на фильтре `hooked_block_types`) — в шапке
 * (`patterns/header-nav.php`) появлялись лишние иконки корзины и «личного
 * кабинета», которых нет в референсе и которые дублируют уже реализованную
 * в паттерне свою иконку корзины (`fs_lms_theme_shop_url()`,
 * `.fs-header-cart`). Официальный способ отключить — выставить опцию,
 * которую сам плагин проверяет в `register_hooked_block()`.
 */
add_action( 'after_setup_theme', function (): void {
	if ( 'no' !== get_option( 'woocommerce_hooked_blocks_version' ) ) {
		update_option( 'woocommerce_hooked_blocks_version', 'no' );
	}
} );

/**
 * 3 карточки товара в ряд на архиве/категории (как на референсе
 * `/shop/`, Фаза 10, а не дефолтные 4 у WooCommerce/WoodMart-настроек
 * Customizer'а, которые на чистой установке не заданы).
 */
add_filter( 'loop_shop_columns', function (): int {
	return 3;
} );

/**
 * Двухколоночный layout страницы магазина (Фаза 10.4): сайдбар (категории +
 * фильтр цены, `sidebar-shop.php`) слева от сетки товаров. Даже в блочной
 * FSE-теме дефолтный шаблон WooCommerce `archive-product.html` рендерит
 * каталог через блок `woocommerce/legacy-template`, который вызывает те же
 * классические хуки, что и обычный `archive-product.php`
 * (проверено по `refs/woocommerce/src/Blocks/BlockTypes/ClassicTemplate.php`)
 * — значит эти хуки надёжно срабатывают и здесь, без переопределения
 * блочного шаблона.
 */
add_action( 'woocommerce_before_main_content', function (): void {
	echo '<div class="fs-shop-layout">';
	get_sidebar( 'shop' );
	echo '<div class="fs-shop-layout__content">';
}, 5 );

add_action( 'woocommerce_after_main_content', function (): void {
	echo '</div></div>';
}, 15 );

/**
 * В референсе (`refs/Абонементы - ЕГЭ по информатике Калининград.html`,
 * WoodMart) на карточке товара нет звёзд рейтинга — у курсов-абонементов
 * отзывов/оценок в WooCommerce никто не ведёт, пустая строка звёзд смотрелась
 * бы как баг, а не как «нет отзывов». `inc/WooCommerce.php` подключается из
 * `functions.php` на `after_setup_theme`/загрузке темы — уже после того, как
 * `WC_Template_Hooks` (плагин, `plugins_loaded`) зарегистрировал дефолтный
 * колбэк, так что снять его можно сразу, без обёртки в ещё один хук.
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

/**
 * Первая категория товара под заголовком карточки (Фаза 10.5, аналог
 * `wd-product-cats` в референсе WoodMart). Приоритет 6 — между снятым
 * рейтингом (был на 5) и ценой (10).
 */
add_action( 'woocommerce_after_shop_loop_item_title', function (): void {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}

	$term = reset( $terms );
	printf(
		'<div class="fs-product-cat"><a href="%1$s">%2$s</a></div>',
		esc_url( get_term_link( $term ) ),
		esc_html( $term->name )
	);
}, 6 );

/**
 * Кнопка quick view — не внутри `<a>`, которую открывает
 * `woocommerce_template_loop_product_link_open` на `woocommerce_before_shop_loop_item`
 * приоритетом 10 (сама ссылка оборачивает миниатюру+заголовок+цену до
 * `woocommerce_after_shop_loop_item` приоритета 5). Кнопка внутри `<a>` —
 * невалидная вложенность интерактивных элементов, поэтому хук — на том же
 * `woocommerce_before_shop_loop_item`, но раньше (приоритет 5, до открытия
 * ссылки): в разметке кнопка выходит перед `<a>`, визуально ложится поверх
 * миниатюры через `position: absolute` (`_woocommerce.scss`, `li.product`
 * — `position: relative`).
 */
add_action( 'woocommerce_before_shop_loop_item', function (): void {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	printf(
		'<button type="button" class="fs-quick-view" data-product-id="%1$d" aria-label="%2$s"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg></button>',
		$product->get_id(),
		esc_attr__( 'Быстрый просмотр', 'fs-lms-theme' )
	);
}, 5 );

/**
 * AJAX quick view (Фаза 10.5) — по `product_id` собирает мини-карточку
 * (фото/заголовок/цена/краткое описание/кнопка «В корзину») и возвращает
 * HTML; `src/js/quick-view.js` вставляет его в модалку. Кнопка «В корзину»
 * — тот же `woocommerce_template_loop_add_to_cart()`, что и в сетке, значит
 * AJAX-добавление в корзину работает и из модалки без дублирования кода.
 */
function fs_lms_theme_ajax_quick_view(): void {
	check_ajax_referer( 'fs-quick-view', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$product    = $product_id ? wc_get_product( $product_id ) : null;

	if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
		wp_send_json_error( array( 'message' => __( 'Товар не найден.', 'fs-lms-theme' ) ), 404 );
	}

	ob_start();
	global $post;
	$post = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride -- нужен для setup_postdata() ниже, как в стандартном WC-цикле.
	setup_postdata( $post );
	?>
	<div class="fs-quick-view-modal__media"><?php echo wp_kses_post( $product->get_image( 'woocommerce_single' ) ); ?></div>
	<div class="fs-quick-view-modal__body">
		<h3 class="fs-quick-view-modal__title"><?php echo esc_html( $product->get_name() ); ?></h3>
		<div class="fs-quick-view-modal__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
		<div class="fs-quick-view-modal__excerpt"><?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?></div>
		<?php woocommerce_template_loop_add_to_cart(); ?>
	</div>
	<?php
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_fs_quick_view', 'fs_lms_theme_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_fs_quick_view', 'fs_lms_theme_ajax_quick_view' );

/**
 * URL страницы магазина (Фаза 10, `patterns/header-nav.php`) — WooCommerce
 * сам создаёт эту страницу при активации плагина (`wc_get_page_permalink`
 * резолвит её реальный slug, не хардкод `/shop/`). Без активного плагина —
 * `#`, как и остальные пункты меню без готовой страницы темы.
 *
 * @return string
 */
function fs_lms_theme_shop_url(): string {
	if ( ! function_exists( 'wc_get_page_permalink' ) ) {
		return '#';
	}

	return esc_url( wc_get_page_permalink( 'shop' ) );
}
