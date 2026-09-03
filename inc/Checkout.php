<?php
/**
 * Корзина, оформление заказа и «Заказ получен» (Фаза 16.3-16.4) —
 * редизайн по `Корзина - мокап.dc.html`/`Оформление заказа - мокап.dc.html`
 * (Claude Design, тот же проект, что и остальные страницы Фазы 16).
 *
 * Обе страницы (Cart/Checkout) WooCommerce создаёт по умолчанию с
 * контентом на блоках `woocommerce/cart`/`woocommerce/checkout`
 * (React-рендер, Cart & Checkout Blocks) — это совсем другая система
 * рендера, без классических PHP-хуков, на которые рассчитана вся
 * остальная интеграция темы (см. `inc/WooCommerce.php`, тот же вывод,
 * что был сделан для каталога — Фаза 16.2, где архив тоже по умолчанию
 * оказался на новом блочном шаблоне вместо classic `legacy-template`).
 * Стилизовать разметку React-блоков через CSS менее предсказуемо и не
 * даёт того контроля, что нужен под макет (степпер шагов, кастомная
 * сетка) — поэтому страницы переключены на классические шорткоды
 * (`[woocommerce_cart]`) — **ручной шаг данных**, не код темы (см.
 * tasks.md, Фаза 16.3): содержимое страницы Cart/Checkout поменяно
 * через `wp-cli`/редактор на классический шорткод вместо блока.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Степпер шагов оформления заказа (Фаза 16.3, по макету) — общий для
 * страниц «Корзина»/«Оформление заказа»/«Заказ завершён» (последняя пока
 * не в скоупе темы, шаг просто не подсвечивается активным ни на одной
 * реализованной странице).
 *
 * @param int $active_step 1 — Корзина, 2 — Оформление заказа, 3 — Заказ завершён.
 */
function fs_lms_theme_checkout_steps( int $active_step ): void {
	$steps = array(
		1 => __( 'Корзина', 'fs-lms-theme' ),
		2 => __( 'Оформление заказа', 'fs-lms-theme' ),
		3 => __( 'Заказ завершён', 'fs-lms-theme' ),
	);

	echo '<div class="fs-checkout-steps">';

	$i = 0;
	foreach ( $steps as $num => $label ) {
		++$i;
		if ( $i > 1 ) {
			echo '<span class="fs-checkout-steps__arrow" aria-hidden="true">&rarr;</span>';
		}

		$state = '';
		if ( $num === $active_step ) {
			$state = 'is-active';
		} elseif ( $num < $active_step ) {
			$state = 'is-done';
		}

		printf(
			'<span class="fs-checkout-steps__step %1$s"><span class="fs-checkout-steps__num">%2$d</span>%3$s</span>',
			esc_attr( $state ),
			(int) $num,
			esc_html( $label )
		);
	}

	echo '</div>';
}

/**
 * Корзина (Фаза 16.3) — степпер (шаг 1) + двухколоночная сетка (форма +
 * сводка заказа 380px), тот же приём, что у `.fs-shop-layout` (Фаза 10,
 * убран в 16.2) — здесь новый класс, т.к. содержимое другое (форма
 * корзины, не сайдбар с виджетами).
 */
add_action( 'woocommerce_before_cart', function (): void {
	fs_lms_theme_checkout_steps( 1 );
	echo '<div class="fs-cart-layout">';
}, 1 );

add_action( 'woocommerce_after_cart', function (): void {
	echo '</div>';
}, 99 );

/**
 * «Вернуться в магазин» (Фаза 16.3, по макету) — у классического шаблона
 * `cart/cart.php` такой ссылки нет по умолчанию (это решение макета, не
 * стандартный элемент WooCommerce), выводим сами сразу после таблицы
 * товаров/действий (купон, обновить корзину).
 */
add_action( 'woocommerce_after_cart_table', function (): void {
	printf(
		'<div class="fs-cart-continue"><a href="%1$s">&larr; %2$s</a></div>',
		esc_url( fs_lms_theme_shop_url() ),
		esc_html__( 'Вернуться в магазин', 'fs-lms-theme' )
	);
} );

/**
 * Товары «Возможно, вас заинтересует» (кросс-селл под таблицей корзины) —
 * в макете их нет, убраны целиком (тот же принцип, что у тулбара/сайдбара
 * магазина, Фаза 16.2 — не оставлять неоформленные под новый визуальный
 * язык блоки).
 */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

/**
 * Оформление заказа (Фаза 16.4, по `Оформление заказа - мокап.dc.html`) —
 * степпер (шаг 2), тот же `fs_lms_theme_checkout_steps()`, что на
 * «Корзине» (шаг 1). Сетка «форма + сводка заказа» — без обёртки, чистой
 * CSS-сеткой прямо на `form.checkout` (`_woocommerce.scss`,
 * `.woocommerce-checkout form.checkout` — явный `grid-column`/`grid-row`
 * на `#customer_details`/`#order_review_heading`/`#order_review`), в
 * отличие от корзины обёртку в PHP заводить не пришлось — у `form.checkout`
 * и так один родитель на все нужные элементы.
 */
add_action( 'woocommerce_before_checkout_form', function (): void {
	fs_lms_theme_checkout_steps( 2 );
}, 5 );

/**
 * Страница «Заказ получен» (thank-you, `order-received.php`) — третий шаг
 * степпера, для единообразия с «Корзиной»/«Оформлением заказа» (сама
 * страница и её контент — целиком штатный вывод WooCommerce, тема ничего
 * в нём не меняет и не подменяет, только достраивает степпер сверху).
 */
add_action( 'woocommerce_before_thankyou', function (): void {
	fs_lms_theme_checkout_steps( 3 );
} );
