<?php
/**
 * Title: Шапка — лого, меню, кнопка записи
 * Slug: fs-lms-theme/header-nav
 * Categories: fs-lms-layout
 * Block Types: core/template-part/header
 */
?>
<!-- wp:group {"backgroundColor":"surface-2","style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm","left":"var:preset|spacing|2xl","right":"var:preset|spacing|2xl"}},"border":{"bottom":{"color":"var:preset|color|border-light","width":"1px"}}}} -->
<div class="wp-block-group has-surface-2-background-color has-background" style="border-bottom-color:var(--wp--preset--color--border-light);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--sm);padding-right:var(--wp--preset--spacing--2xl);padding-bottom:var(--wp--preset--spacing--sm);padding-left:var(--wp--preset--spacing--2xl)">
	<!-- wp:paragraph {"textColor":"muted","fontSize":"xs"} -->
	<p class="has-muted-color has-text-color has-xs-font-size">Подготовка ЕГЭ по информатике · +7 995 326 44 86 · info@future-step.ru</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md","left":"var:preset|spacing|2xl","right":"var:preset|spacing|2xl"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}}}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--md);padding-right:var(--wp--preset--spacing--2xl);padding-bottom:var(--wp--preset--spacing--md);padding-left:var(--wp--preset--spacing--2xl)">
	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column {"width":"30%"} -->
		<div class="wp-block-column" style="flex-basis:30%">
			<!-- wp:site-title {"level":0,"fontSize":"lead"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"45%"} -->
		<div class="wp-block-column" style="flex-basis:45%">
			<!-- wp:navigation {"overlayMenu":"mobile"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"25%"} -->
		<div class="wp-block-column" style="flex-basis:25%">
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"accent"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button">Записаться</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
