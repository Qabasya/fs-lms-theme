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
 * Заголовок страницы (`<h1>`) — печатаем сами, после степпера.
 *
 * В макетах («Корзина - мокап.dc.html»/«Оформление заказа - мокап.dc.html»)
 * порядок именно такой: сначала шаги, потом заголовок. Штатный
 * `wp:post-title` шаблона рисовался ДО контента, то есть над степпером, и
 * другим кеглем — поэтому страницам корзины/оформления назначается шаблон
 * без заголовка (`page-subject`, см. ниже), а `<h1>` выводится здесь.
 *
 * @param string $title Заголовок; по умолчанию — название страницы.
 */
function fs_lms_theme_checkout_heading( string $title = '' ): void {
	printf(
		'<h1 class="fs-page-title">%s</h1>',
		esc_html( '' !== $title ? $title : get_the_title() )
	);
}

/**
 * Шаблон без `wp:post-title` для страниц корзины и оформления заказа —
 * иначе заголовок дублировался бы с тем, что печатает
 * `fs_lms_theme_checkout_heading()`. Идемпотентно, как и остальные
 * назначения шаблонов в теме (`inc/SubjectPages.php`,
 * `inc/ResourcePages.php`).
 */
add_action( 'init', function (): void {
	if ( ! function_exists( 'wc_get_page_id' ) ) {
		return;
	}

	foreach ( array( 'cart', 'checkout' ) as $wc_page ) {
		$page_id = wc_get_page_id( $wc_page );

		if ( $page_id > 0 && 'page-subject' !== get_post_meta( $page_id, '_wp_page_template', true ) ) {
			update_post_meta( $page_id, '_wp_page_template', 'page-subject' );
		}
	}
}, 20 );

/**
 * Корзина (Фаза 16.3) — степпер (шаг 1) + двухколоночная сетка (форма +
 * сводка заказа 380px), тот же приём, что у `.fs-shop-layout` (Фаза 10,
 * убран в 16.2) — здесь новый класс, т.к. содержимое другое (форма
 * корзины, не сайдбар с виджетами).
 */
add_action( 'woocommerce_before_cart', function (): void {
	fs_lms_theme_checkout_steps( 1 );
	fs_lms_theme_checkout_heading();
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
 * «Корзине» (шаг 1).
 */
add_action( 'woocommerce_before_checkout_form', function (): void {
	fs_lms_theme_checkout_steps( 2 );
	fs_lms_theme_checkout_heading();
}, 5 );

/**
 * Заголовок «Ваш заказ» и сама сводка — разные прямые потомки формы;
 * оборачиваем их в один div, чтобы правая колонка была ОДНИМ элементом
 * сетки: иначе высота растянутых строк распределялась между ними и
 * заголовок отрывался от сводки.
 */
add_action( 'woocommerce_checkout_before_order_review_heading', function (): void {
	echo '<div class="fs-checkout-order-review">';
} );

add_action( 'woocommerce_checkout_after_order_review', function (): void {
	echo '</div>';
} );

/**
 * Задача 7 (tasks.md, 2026-09-04): поля «Данные покупателя» на
 * оформлении заказа — по прямому указанию пользователя, поверх макета
 * (`Оформление заказа - мокап.dc.html`, который здесь не буквально
 * повторяем): вместо Имя/Фамилия — одно поле «ФИО родителя» (тот же
 * формат, что у лид-формы записи, `patterns/hero.php`,
 * `input[name="parent_name"]`), плюс новое поле «ФИО ребёнка», Email
 * остаётся; «Примечание к заказу» убрано. Адресные поля (страна, город,
 * индекс и т.д.) тоже убраны — товары виртуальные (курсы), доставки нет,
 * в макете их и не было.
 */
add_filter( 'woocommerce_checkout_fields', function ( array $fields ): array {
	unset(
		$fields['billing']['billing_company'],
		$fields['billing']['billing_address_1'],
		$fields['billing']['billing_address_2'],
		$fields['billing']['billing_city'],
		$fields['billing']['billing_state'],
		$fields['billing']['billing_postcode'],
		$fields['billing']['billing_country']
	);

	// По указанию пользователя (2026-09-05): ФИО родителя и ФИО ребёнка —
	// каждое на свою строку (длинные значения), телефон и почта — в одну.
	$fields['billing']['billing_first_name']['label']       = __( 'ФИО родителя', 'fs-lms-theme' );
	$fields['billing']['billing_first_name']['placeholder'] = __( 'Иванова Анна Ивановна', 'fs-lms-theme' );
	$fields['billing']['billing_first_name']['class']       = array( 'form-row-wide' );
	$fields['billing']['billing_first_name']['priority']    = 10;
	unset( $fields['billing']['billing_last_name'] );

	$fields['billing']['billing_child_name'] = array(
		'label'       => __( 'ФИО ребёнка', 'fs-lms-theme' ),
		'placeholder' => __( 'Иванов Пётр Игоревич', 'fs-lms-theme' ),
		'required'    => true,
		'class'       => array( 'form-row-wide' ),
		'priority'    => 20,
	);

	$fields['billing']['billing_phone']['class']    = array( 'form-row-first' );
	$fields['billing']['billing_phone']['required'] = true;
	$fields['billing']['billing_phone']['priority'] = 30;

	$fields['billing']['billing_email']['label']    = __( 'Email', 'fs-lms-theme' );
	$fields['billing']['billing_email']['class']    = array( 'form-row-last' );
	$fields['billing']['billing_email']['priority'] = 40;

	unset( $fields['order']['order_comments'] );

	return $fields;
} );

/**
 * Купон на оформлении заказа остаётся на своём штатном хуке
 * (`woocommerce_before_checkout_form`) СПЕЦИАЛЬНО, хотя по макету он идёт
 * под полями покупателя: его разметка — отдельный `<form
 * class="checkout_coupon">`, и любой перенос внутрь `form.checkout`
 * (например на `woocommerce_checkout_after_customer_details`) даёт
 * вложенную форму. Браузер такую форму выбрасывает при разборе HTML —
 * поле и кнопка расползаются по сетке, а AJAX применения купона ломается.
 * Поэтому блок переставляется визуально, порядком флекса
 * (`.woocommerce-checkout .woocommerce` в `_woocommerce.scss`).
 */

/**
 * Блок «Дополнительная информация» целиком (заголовок + поле примечания) —
 * поля примечания мы убрали (см. фильтр выше), и от блока оставался только
 * пустой заголовок. Это штатный выключатель WooCommerce для всей секции.
 */
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );

/**
 * Сохраняем «ФИО ребёнка» в мету заказа — своего сеттера у WooCommerce
 * для кастомных полей `billing` нет (`WC_Checkout::create_order()`
 * автоматически маппит только известные свойства `WC_Order`, вроде
 * `first_name`/`phone`), поэтому пишем сами по образцу
 * `woocommerce_checkout_update_order_meta`.
 */
add_action( 'woocommerce_checkout_update_order_meta', function ( int $order_id ): void {
	if ( ! isset( $_POST['billing_child_name'] ) ) {
		return;
	}

	update_post_meta(
		$order_id,
		'_billing_child_name',
		sanitize_text_field( wp_unslash( $_POST['billing_child_name'] ) )
	);
} );

/**
 * Показываем «ФИО ребёнка» в заказе в админке — рядом с адресом
 * покупателя, там же, где остальные billing-поля.
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', function ( $order ): void {
	if ( ! $order instanceof WC_Order ) {
		return;
	}

	$child_name = $order->get_meta( '_billing_child_name' );

	if ( '' === $child_name ) {
		return;
	}

	printf(
		'<p><strong>%s:</strong> %s</p>',
		esc_html__( 'ФИО ребёнка', 'fs-lms-theme' ),
		esc_html( $child_name )
	);
} );

/**
 * Страница «Заказ получен» (thank-you) — по указанию пользователя
 * (2026-09-05) и макету `Спасибо за заказ - мокап.dc.html` на ней остаётся
 * только благодарность: галочка, заголовок, две строки текста и кнопка на
 * главную. Степпера в макете нет — здесь его больше не рисуем (на «Корзине»
 * и «Оформлении заказа» он остаётся).
 *
 * Разметку даёт переопределённый шаблон `woocommerce/checkout/thankyou.php`.
 * Чтобы он вообще попал в вывод, у темы есть свой `templates/
 * order-confirmation.html`: тема блочная, и для эндпоинта `order-received`
 * WooCommerce иначе отдаёт СВОЙ блочный шаблон того же слага
 * (`woocommerce/templates/templates/order-confirmation.html`, набор блоков
 * `order-confirmation-*`), где PHP-шаблон не участвует вовсе. Наш шаблон
 * зовёт штатный шорткод `[woocommerce_checkout]` — он роутит эндпоинт сам
 * (`WC_Shortcode_Checkout::output()`), вместе со всеми проверками ключа
 * заказа и подтверждения почты для гостевых заказов.
 *
 * Сводку заказа, реквизиты и адрес печатает сам плагин — колбэком
 * `woocommerce_order_details_table` на хуке `woocommerce_thankyou`
 * (`wc-template-hooks.php`). Снимаем именно колбэк, а не хук: на том же
 * хуке платёжные шлюзы выводят свои инструкции после оплаты.
 */
add_action( 'wp_loaded', function (): void {
	remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
} );
