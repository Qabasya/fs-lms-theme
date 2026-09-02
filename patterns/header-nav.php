<?php
/**
 * Title: Шапка — лого, меню, кнопка записи
 * Slug: fs-lms-theme/header-nav
 * Categories: fs-lms-layout
 * Block Types: core/template-part/header
 * Keywords: шапка, хедер, header, меню, навигация
 *
 * По мокапу («Главная — мокап.dc.html»): тонкая инфо-полоса + основная
 * навигация (лого, меню, CTA). Ссылка «Записаться» ведёт на страницу заявки
 * плагина через `fs_lms_theme_url('apply')` (см. inc/PluginRoutes.php,
 * Фаза 7); «Курсы» — на страницу магазина WooCommerce через
 * `fs_lms_theme_shop_url()` (Фаза 10.3, inc/WooCommerce.php). «Учебник»/
 * «Тренажёр» остаются заглушкой `#` — под них ещё нет страниц темы.
 *
 * Соцсети (YouTube/VK/Telegram) и иконка корзины — перенесены из шапки
 * текущего сайта (`refs/ЕГЭ по информатике в Калининграде.html`, WoodMart),
 * их не было в исходном мокапе Фазы 3.
 */
?>
<!-- wp:group {"tagName":"div","backgroundColor":"surface-2","style":{"spacing":{"padding":{"top":"var:preset|spacing|xs","bottom":"var:preset|spacing|xs"}},"border":{"bottom":{"color":"var:preset|color|border-light","width":"1px"}}}} -->
<div class="wp-block-group has-surface-2-background-color has-background" style="border-bottom-color:var(--wp--preset--color--border-light);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--xs);padding-bottom:var(--wp--preset--spacing--xs)">
	<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
		<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<div class="wp-block-group">
			<!-- wp:site-tagline {"textColor":"muted","fontSize":"xs"} /-->

			<!-- wp:group {"layout":{"type":"flex"},"style":{"spacing":{"blockGap":"var:preset|spacing|xl","margin":{"left":"auto"}}}} -->
			<div class="wp-block-group" style="margin-left:auto">
				<!-- wp:paragraph {"textColor":"muted","fontSize":"xs"} -->
				<p class="has-muted-color has-text-color has-xs-font-size">+7 995 326 44 86</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"muted","fontSize":"xs"} -->
				<p class="has-muted-color has-text-color has-xs-font-size">info@future-step.ru</p>
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
<div class="wp-block-group has-white-background-color has-background" style="margin-top:0;border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--md)">
	<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
		<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<div class="wp-block-group">
			<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|md"}}} -->
			<div class="wp-block-group">
				<!-- wp:site-logo {"width":38,"shouldSyncIcon":false,"style":{"border":{"radius":"var:preset|spacing|sm"}}} /-->

				<!-- wp:group {"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:site-title {"level":0,"fontSize":"sm","style":{"typography":{"fontWeight":"700","letterSpacing":"0.06em","textTransform":"uppercase"}}} /-->

					<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xxs"} -->
					<p class="has-muted-2-color has-text-color has-xxs-font-size">Подготовка к ЕГЭ по информатике</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"center"},"style":{"typography":{"fontSize":"sm"}}} -->
			<!-- wp:navigation-link {"label":"Главная","url":"<?php echo esc_url( home_url( '/' ) ); ?>","className":"current-menu-item"} /-->

			<!-- wp:navigation-link {"label":"О нас","url":"<?php echo esc_url( home_url( '/about/' ) ); ?>"} /-->

			<!-- wp:navigation-link {"label":"Курсы","url":"<?php echo esc_url( fs_lms_theme_shop_url() ); ?>"} /-->

			<!-- wp:navigation-link {"label":"Учебник","url":"#"} /-->

			<!-- wp:navigation-link {"label":"Тренажёр","url":"#"} /-->
			<!-- /wp:navigation -->

			<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|lg"}}} -->
			<div class="wp-block-group">
				<!-- wp:group {"layout":{"type":"flex"},"style":{"spacing":{"blockGap":"var:preset|spacing|sm"}}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"xs"} -->
					<p class="has-muted-color has-text-color has-xs-font-size"><a href="https://www.youtube.com/@future-step">YouTube</a></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"textColor":"muted","fontSize":"xs"} -->
					<p class="has-muted-color has-text-color has-xs-font-size"><a href="https://vk.com/future_step39">VK</a></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"textColor":"muted","fontSize":"xs"} -->
					<p class="has-muted-color has-text-color has-xs-font-size"><a href="https://t.me/+wMAEBd_RtqJhMDAy">Telegram</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"backgroundColor":"accent"} -->
					<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться</a></div>
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
