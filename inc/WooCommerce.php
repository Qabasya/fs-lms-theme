<?php
/**
 * Каталог WooCommerce (Фаза 10.3+, редизайн Фаза 16.2) — база подключения,
 * без переопределения шаблонов WooCommerce целиком: используем штатные
 * хуки/фильтры плагина, чтобы пережить его обновления и не тащить в тему
 * копию его PHP-шаблонов (см. tasks.md, Фаза 10, план `sequential-nibbling-cat.md`).
 *
 * `add_theme_support('woocommerce')` — в inc/Setup.php.
 *
 * Фаза 16.2 (источник дизайна — `Магазин - мокап.dc.html`, Claude Design,
 * импортирован через `DesignSync`): макет — чистая сетка 4 карточки в ряд,
 * без сайдбара (категории/фильтр цены) и без тулбара (счётчик/сортировка),
 * которые строила Фаза 10.4 — по прямому решению пользователя убраны
 * полностью, а не просто перекрашены (см. `tasks.md`, обсуждение
 * 2026-09-03). `sidebar-shop.php`/зона `shop-sidebar` (`inc/Setup.php`)
 * удалены вместе с этим. Quick view (Фаза 10.5) в новом макете тоже не
 * показан — убран целиком (кнопка на карточке, AJAX-хендлер,
 * `src/js/quick-view.js`, `.fs-quick-view*` в CSS).
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
 * 4 карточки товара в ряд (Фаза 16.2, по макету) — было 3 (Фаза 10, под
 * прежний двухколоночный layout с сайдбаром, которого больше нет).
 */
add_filter( 'loop_shop_columns', function (): int {
	return 4;
} );

/**
 * Вводный абзац под заголовком «Магазин» (Фаза 16.2) — в макете это
 * статичный маркетинговый текст, не поле WooCommerce/CPT; выводится только
 * на корневом архиве магазина (`is_shop()`), не на страницах категорий —
 * там читать «Материалы для самостоятельной подготовки…» не в тему
 * конкретной категории.
 */
add_action( 'woocommerce_before_shop_loop', function (): void {
	if ( ! is_shop() ) {
		return;
	}

	echo '<p class="fs-shop-intro">' . esc_html__( 'Материалы для самостоятельной подготовки и наборы для занятий. Доступ к электронным товарам открывается сразу после оплаты.', 'fs-lms-theme' ) . '</p>';
}, 5 );

/**
 * Тулбар (счётчик результатов + сортировка) — в новом макете его нет,
 * снят целиком (Фаза 16.2, было в Фазе 10.4 как часть `.fs-shop-layout`).
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

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
 * BugFix (2026-09-04): та же причина, что у рейтинга в каталоге (никто не
 * ведёт отзывы/оценки) — по указанию пользователя убрана и звезда рейтинга
 * над заголовком страницы товара, и сама вкладка «Отзывы» (вместе с формой
 * комментария) в `woocommerce_product_tabs`.
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

add_filter( 'woocommerce_product_tabs', function ( array $tabs ): array {
	unset( $tabs['reviews'] );

	return $tabs;
} );

/**
 * Карточка товара (Фаза 16.2, по макету) — перестроена целиком относительно
 * дефолтного порядка хуков WooCommerce:
 *
 * 1. Бейдж первой категории товара (было — простая ссылка `.fs-product-cat`
 *    под заголовком, Фаза 10.5) — теперь бейдж-пилюля НАД заголовком, в
 *    языке `fs-course-catalog-card__badge` (Фаза 16.5) — на
 *    `before_shop_loop_item_title` после миниатюры (приоритет 15, миниатюра
 *    — 10), т.е. до заголовка.
 * 2. Ссылка карточки (`woocommerce_template_loop_product_link_open/close`)
 *    закрывается сразу после заголовка, а не после цены, как в дефолте —
 *    цена и кнопка «В корзину» лежат в общем футере СНАРУЖИ ссылки (кнопка
 *    внутри `<a>` — невалидная вложенность интерактивных элементов, тот же
 *    принцип, что был у quick view в Фазе 10.5, здесь применён к самой
 *    ссылке карточки).
 * 3. Короткое описание (новое, `get_short_description()`) — между
 *    заголовком и футером, как в макете.
 * 4. Цена и «В корзину» — один общий футер (`.fs-shop-card__footer`,
 *    flex space-between, как в макете), а не два раздельных хука в разных
 *    местах разметки (дефолт: цена на `after_shop_loop_item_title`, кнопка
 *    — на `after_shop_loop_item`).
 */
add_action( 'woocommerce_before_shop_loop_item_title', function (): void {
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
		'<span class="fs-shop-card__badge">%s</span>',
		esc_html( $term->name )
	);
}, 15 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 20 );

add_action( 'woocommerce_after_shop_loop_item_title', function (): void {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$excerpt = $product->get_short_description();
	if ( '' === $excerpt ) {
		$excerpt = $product->get_description();
	}
	if ( '' === $excerpt ) {
		return;
	}

	printf(
		'<p class="fs-shop-card__excerpt">%s</p>',
		esc_html( wp_trim_words( wp_strip_all_tags( $excerpt ), 18 ) )
	);
}, 8 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

add_action( 'woocommerce_after_shop_loop_item_title', function (): void {
	echo '<div class="fs-shop-card__footer">';
	woocommerce_template_loop_price();
	woocommerce_template_loop_add_to_cart();
	echo '</div>';
}, 10 );

/**
 * BugFix.14 (2026-09-03, живой прогон + фикс): фолбэк-картинки товаров по
 * категории направления (`img/shop-*.png`) — реальные фото товаров
 * задаются как обычно через featured image в админке. Слаги категорий —
 * из `refs/Абонементы - ЕГЭ по информатике Калининград.html` (tasks.md,
 * Фаза 10.3): `kege`/`koge`/`python-10`/`robo`.
 *
 * Изначально хук стоял на `woocommerce_placeholder_img_src` — не
 * срабатывал: подтверждено живьём (Docker-стенд, WC 10.2.1) —
 * `wc_placeholder_img()` вызывает `wc_placeholder_img_src()` только в
 * ветке «нет настроенного `woocommerce_placeholder_image`»; на этом
 * стенде опция уже указывает на медиа-вложение
 * (`woocommerce-placeholder.webp`), и функция идёт через
 * `wp_get_attachment_image()`, вообще не вызывая `wc_placeholder_img_src()`.
 * `woocommerce_placeholder_img` (фильтрует готовый `<img>` HTML,
 * `wc-product-functions.php:446`) вызывается в обеих ветках — переехали
 * на него, чтобы не зависеть от того, настроена ли опция
 * `woocommerce_placeholder_image` на конкретном сайте.
 */
/**
 * URL фолбэк-картинки товара по его категории направления, либо '' —
 * если категория не из списка. Общая точка для витрины (плейсхолдер
 * WooCommerce) и корзины (`woocommerce_cart_item_thumbnail`), чтобы
 * сопоставление «категория → файл» жило в одном месте.
 */
function fs_lms_theme_product_fallback_image( WC_Product $product ): string {
	$image_by_category_slug = array(
		'kege'      => 'shop-ege.png',
		'koge'      => 'shop-oge.png',
		'python-10' => 'shop-py.png',
		'robo'      => 'shop-robo.png',
	);

	$terms = get_the_terms( $product->get_id(), 'product_cat' );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}

	foreach ( $terms as $term ) {
		if ( isset( $image_by_category_slug[ $term->slug ] ) ) {
			return get_theme_file_uri( 'img/' . $image_by_category_slug[ $term->slug ] );
		}
	}

	return '';
}

add_filter( 'woocommerce_placeholder_img', function ( string $html, string $size, array $dimensions ) {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return $html;
	}

	$url = fs_lms_theme_product_fallback_image( $product );

	if ( '' === $url ) {
		return $html;
	}

	return sprintf(
		'<img src="%1$s" width="%2$d" height="%3$d" alt="%4$s" class="woocommerce-placeholder wp-post-image" />',
		esc_url( $url ),
		(int) $dimensions['width'],
		(int) $dimensions['height'],
		esc_attr( $product->get_name() )
	);
}, 10, 3 );

/**
 * Миниатюра товара в корзине. Фильтр плейсхолдера выше здесь не работает:
 * он читает `global $product`, а `cart/cart.php` вызывает
 * `$_product->get_image()` без установки этой глобальной — в корзине
 * поэтому показывалась серая заглушка WooCommerce вместо картинки
 * направления. У этого фильтра сам товар приходит в `$cart_item`.
 */
add_filter( 'woocommerce_cart_item_thumbnail', function ( $html, $cart_item ) {
	$product = is_array( $cart_item ) && isset( $cart_item['data'] ) ? $cart_item['data'] : null;

	if ( ! $product instanceof WC_Product || $product->get_image_id() ) {
		return $html;
	}

	$url = fs_lms_theme_product_fallback_image( $product );

	if ( '' === $url ) {
		return $html;
	}

	return sprintf(
		'<img src="%1$s" alt="%2$s" class="woocommerce-placeholder wp-post-image" />',
		esc_url( $url ),
		esc_attr( $product->get_name() )
	);
}, 10, 2 );

/**
 * Текст ссылки, которую WooCommerce дописывает после добавления товара в
 * корзину (`a.added_to_cart`, `assets/js/frontend/add-to-cart.js` →
 * `wc_add_to_cart_params.i18n_view_cart`). По умолчанию это «Просмотр
 * корзины»/«View cart» — длинная надпись ломала футер карточки каталога
 * (tasks.md, новый список, п.2). Кнопка «В корзину» после добавления
 * прячется (`_woocommerce.scss`), и на её месте остаётся эта ссылка —
 * поэтому текст короткий.
 *
 * `woocommerce_get_script_data` — штатный фильтр локализованных данных
 * скриптов плагина (`class-wc-frontend-scripts.php`).
 */
add_filter( 'woocommerce_get_script_data', function ( $params, string $handle ) {
	if ( 'wc-add-to-cart' === $handle && is_array( $params ) ) {
		$params['i18n_view_cart'] = __( 'Перейти', 'fs-lms-theme' );
	}

	return $params;
}, 10, 2 );

/**
 * URL страницы магазина (Фаза 10, использовалась в `patterns/header-nav.php`
 * до Фазы 16.5, где пункт «Курсы» переключили на новый каталог направлений
 * `/courses/`). Страница `/shop/` по решению пользователя (2026-09-03) не
 * входит в меню — функция остаётся на случай, если понадобится сослаться
 * на магазин из другого места (например, с карточек `courses-catalog.php`
 * в будущем). WooCommerce сам создаёт эту страницу при активации плагина
 * (`wc_get_page_permalink` резолвит её реальный slug, не хардкод `/shop/`).
 * Без активного плагина — `#`, как и остальные пункты меню без готовой
 * страницы темы.
 *
 * @return string
 */
function fs_lms_theme_shop_url(): string {
	if ( ! function_exists( 'wc_get_page_permalink' ) ) {
		return '#';
	}

	return esc_url( wc_get_page_permalink( 'shop' ) );
}
