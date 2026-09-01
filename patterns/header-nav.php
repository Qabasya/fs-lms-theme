<?php
/**
 * Title: Шапка — лого, меню, кнопка записи
 * Slug: fs-lms-theme/header-nav
 * Categories: fs-lms-layout
 * Block Types: core/template-part/header
 *
 * По мокапу («Главная — мокап.dc.html»): тонкая инфо-полоса + основная
 * навигация (лого, меню, CTA). Ссылка «Записаться» — заглушка `#`,
 * реальный маршрут плагина подключается в Фазе 7 (граница тема/плагин).
 */
?>
<!-- wp:group {"tagName":"div","layout":{"type":"flex","justifyContent":"space-between"},"backgroundColor":"surface-2","style":{"spacing":{"padding":{"top":"var:preset|spacing|xs","bottom":"var:preset|spacing|xs","left":"var:preset|spacing|2xl","right":"var:preset|spacing|2xl"}},"border":{"bottom":{"color":"var:preset|color|border-light","width":"1px"}}}} -->
<div class="wp-block-group has-surface-2-background-color has-background" style="border-bottom-color:var(--wp--preset--color--border-light);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--xs);padding-right:var(--wp--preset--spacing--2xl);padding-bottom:var(--wp--preset--spacing--xs);padding-left:var(--wp--preset--spacing--2xl)">
	<!-- wp:site-tagline {"textColor":"muted","fontSize":"xs"} /-->

	<!-- wp:group {"layout":{"type":"flex"},"style":{"spacing":{"blockGap":"var:preset|spacing|xl"}}} -->
	<div class="wp-block-group">
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

<!-- wp:group {"tagName":"div","layout":{"type":"flex","justifyContent":"space-between"},"backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md","left":"var:preset|spacing|2xl","right":"var:preset|spacing|2xl"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}}}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--md);padding-right:var(--wp--preset--spacing--2xl);padding-bottom:var(--wp--preset--spacing--md);padding-left:var(--wp--preset--spacing--2xl)">

	<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|md"}}} -->
	<div class="wp-block-group">
		<!-- wp:site-logo {"width":38,"shouldSyncIcon":false,"style":{"border":{"radius":"var:preset|spacing|sm"}}} /-->

		<!-- wp:group {"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:site-title {"level":0,"fontSize":"sm","style":{"typography":{"fontWeight":"700","letterSpacing":"0.06em","textTransform":"uppercase"}}} /-->

			<!-- wp:paragraph {"textColor":"muted-2","fontSize":"2xs"} -->
			<p class="has-muted-2-color has-text-color has-2xs-font-size">Подготовка к ЕГЭ по информатике</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"center"},"style":{"typography":{"fontSize":"sm"}}} -->
	<!-- wp:navigation-link {"label":"Главная","url":"<?php echo esc_url( home_url( '/' ) ); ?>","className":"current-menu-item"} /-->

	<!-- wp:navigation-link {"label":"О нас","url":"#"} /-->

	<!-- wp:navigation-link {"label":"Курсы","url":"#"} /-->

	<!-- wp:navigation-link {"label":"Учебник","url":"#"} /-->

	<!-- wp:navigation-link {"label":"Тренажёр","url":"#"} /-->
	<!-- /wp:navigation -->

	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"accent"} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button" href="#">Записаться</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
