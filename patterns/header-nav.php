<?php
/**
 * Title: Шапка — лого, меню, кнопка записи
 * Slug: fs-lms-theme/header-nav
 * Categories: fs-lms-layout
 * Block Types: core/template-part/header
 * Keywords: шапка, хедер, header, меню, навигация
 *
 * По мокапу v4 («Главная v4 - сборка.dc.html», Фаза 12): тонкая инфо-полоса +
 * основная навигация (лого, меню, CTA). Ссылка «Записаться» — якорь
 * `#hero-form` на форму первого экрана (Фаза 12, решение 5) — раньше вела на
 * страницу заявки плагина через `fs_lms_theme_url('apply')`, эта функция
 * остаётся для других страниц сайта. «Учебник»/«Тренажёр» остаются
 * заглушкой `#` — под них ещё нет страниц темы.
 *
 * Фаза 16.5: «Курсы» — на новую страницу-каталог направлений `/courses/`
 * (`home_url('/courses/')`, тот же приём, что «О нас» → `/about/`), было —
 * на страницу магазина WooCommerce через `fs_lms_theme_shop_url()` (Фаза
 * 10.3) — подтверждено пользователем 2026-09-03: «Курсы» — это каталог
 * направлений (ЕГЭ/ОГЭ/Python/Робототехника), не витрина товаров магазина.
 * `fs_lms_theme_shop_url()` остаётся в `inc/WooCommerce.php` для витрины
 * магазина (Фаза 16.2), просто эта конкретная ссылка её больше не
 * использует.
 *
 * Соцсети (YouTube/VK/Telegram) в макете v4 нет (Фаза 12, решение 7) — в
 * шапке их и не было с самого начала (Фаза 3), убирать нечего. Иконка
 * корзины — из текущего сайта (WoodMart), в макете v4 её тоже нет, но она
 * не относится к соцсетям и оставлена как есть (см. tasks.md 12.1).
 *
 * BugFix (2026-09-03): «Главная» была захардкожена с `className:
 * current-menu-item` безусловно — на `/courses/` (и на любой другой
 * странице) подсвечивалась «Главная», а не текущий раздел. `wp:navigation-link`
 * со статичным `url` (не ссылкой на реальный ID поста через
 * `"kind":"post-type"`) не получает подсветку от ядра автоматически —
 * ниже вычисляем активный пункт вручную через `is_front_page()`/`is_page()`
 * и подставляем `className` в JSON-атрибуты блока (та же техника, что
 * `esc_url()` в `url` этих же блоков).
 *
 * BugFix.14 (2026-09-03): логотип переключён на отдельный файл
 * `img/logo-header.png` (было — общий с футером `images/logo.png`,
 * решение 2 Фазы 12, разворот по прямому указанию пользователя); ширина
 * пересчитана под реальное соотношение сторон файла (2800×816), высота
 * не менялась (64px).
 */
$fs_nav_current_home    = is_front_page() ? 'current-menu-item' : '';
$fs_nav_current_about   = is_page( 'about' ) ? 'current-menu-item' : '';
$fs_nav_current_courses = is_page( 'courses' ) ? 'current-menu-item' : '';
?>
<!-- wp:group {"tagName":"div","backgroundColor":"surface-2","style":{"spacing":{"padding":{"top":"var:preset|spacing|xs","bottom":"var:preset|spacing|xs"}},"border":{"bottom":{"color":"var:preset|color|border-light","width":"1px"}}}} -->
<div class="wp-block-group has-surface-2-background-color has-background" style="border-bottom-color:var(--wp--preset--color--border-light);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--xs);padding-bottom:var(--wp--preset--spacing--xs)">
	<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
		<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<div class="wp-block-group">
			<!-- wp:site-tagline {"textColor":"muted","fontSize":"xxs"} /-->
            <p class="has-muted-2-color has-text-color has-xxs-font-size">ЕГЭ, ОГЭ, программирование и робототехника в Калининграде</p>
			<!-- wp:group {"layout":{"type":"flex"},"style":{"spacing":{"blockGap":"var:preset|spacing|xl","margin":{"left":"auto"}}}} -->
			<div class="wp-block-group" style="margin-left:auto">
				<!-- wp:paragraph {"textColor":"muted","fontSize":"xxs"} -->
				<p class="has-muted-color has-text-color has-xxs-font-size">+7 995 326 44 86</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"muted","fontSize":"xxs"} -->
				<p class="has-muted-color has-text-color has-xxs-font-size">info@future-step.ru</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"div","backgroundColor":"white","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}}}} -->
<div class="wp-block-group has-white-background-color has-background" style="margin-top:0;border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--lg)">
	<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
		<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<div class="wp-block-group">
			<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|md"}}} -->
			<div class="wp-block-group">
				<!-- wp:html -->
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fs-header-logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'img/logo-header.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="220" height="64" style="display:block;width:220px;height:64px;object-fit:contain;" /></a>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->

			<!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"center"},"style":{"typography":{"fontSize":"sm"}}} -->
			<!-- wp:navigation-link {"label":"Главная","url":"<?php echo esc_url( home_url( '/' ) ); ?>","className":"<?php echo esc_attr( $fs_nav_current_home ); ?>"} /-->

			<!-- wp:navigation-link {"label":"О нас","url":"<?php echo esc_url( home_url( '/about/' ) ); ?>","className":"<?php echo esc_attr( $fs_nav_current_about ); ?>"} /-->

			<!-- wp:navigation-link {"label":"Курсы","url":"<?php echo esc_url( home_url( '/courses/' ) ); ?>","className":"<?php echo esc_attr( $fs_nav_current_courses ); ?>"} /-->

			<!-- wp:navigation-link {"label":"Учебник","url":"#"} /-->

			<!-- wp:navigation-link {"label":"Тренажёр","url":"#"} /-->
			<!-- /wp:navigation -->

			<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|lg"}}} -->
			<div class="wp-block-group">

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"backgroundColor":"accent-2"} -->
					<div class="wp-block-button"><a class="wp-block-button__link has-accent-2-background-color has-background wp-element-button" href="#hero-form">Записаться</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

				<?php if ( function_exists( 'wc_get_cart_url' ) ) : ?>
				<!-- wp:html -->
				<a class="fs-header-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php echo esc_attr__( 'Корзина', 'fs-lms-theme' ); ?>"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 4h2l2.4 12.2a2 2 0 0 0 2 1.8h7.2a2 2 0 0 0 2-1.6L20 8H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="21" r="1.5" fill="currentColor"/><circle cx="17" cy="21" r="1.5" fill="currentColor"/></svg></a>
				<!-- /wp:html -->
				<?php endif; ?>
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
