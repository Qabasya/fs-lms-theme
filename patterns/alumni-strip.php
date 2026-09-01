<?php
/**
 * Title: Наши выпускники поступают — плашка с вузами
 * Slug: fs-lms-theme/alumni-strip
 * Categories: fs-lms-sections
 * Keywords: выпускники, вузы, alumni
 *
 * Источник: блок «выпускники» в «Главная — мокап.dc.html» (1a). Логотипы
 * вузов — заглушки `.fs-placeholder-tile` (реальные логотипы вставляет
 * редактор через wp:image), подписи — текст поверх плейсхолдера убран из
 * блочной версии, названия вузов идут отдельным подписанным текстом под
 * плиткой, чтобы не хардкодить текст поверх картинки.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"var:preset|spacing|xxxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxxl);padding-left:var(--wp--preset--spacing--xxl)">
	<!-- wp:group {"className":"is-style-card","backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group is-style-card has-white-background-color has-background" style="padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--xxl)">
		<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xs","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em"}}} -->
		<p class="has-muted-2-color has-text-color has-xs-font-size" style="letter-spacing:0.05em;text-transform:uppercase">Наши выпускники поступают</p>
		<!-- /wp:paragraph -->

		<!-- wp:columns -->
		<div class="wp-block-columns">
			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:group {"className":"fs-placeholder-tile","style":{"dimensions":{"minHeight":"72px"},"border":{"radius":"var:preset|spacing|md"}},"layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center"}} -->
				<div class="wp-block-group fs-placeholder-tile" style="border-radius:var(--wp--preset--spacing--md);min-height:72px">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
					<p class="has-muted-color has-text-color has-sm-font-size" style="font-weight:600">МИРЭА</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:group {"className":"fs-placeholder-tile","style":{"dimensions":{"minHeight":"72px"},"border":{"radius":"var:preset|spacing|md"}},"layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center"}} -->
				<div class="wp-block-group fs-placeholder-tile" style="border-radius:var(--wp--preset--spacing--md);min-height:72px">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
					<p class="has-muted-color has-text-color has-sm-font-size" style="font-weight:600">ИТМО</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:group {"className":"fs-placeholder-tile","style":{"dimensions":{"minHeight":"72px"},"border":{"radius":"var:preset|spacing|md"}},"layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center"}} -->
				<div class="wp-block-group fs-placeholder-tile" style="border-radius:var(--wp--preset--spacing--md);min-height:72px">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
					<p class="has-muted-color has-text-color has-sm-font-size" style="font-weight:600">РУДН</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:group {"className":"fs-placeholder-tile","style":{"dimensions":{"minHeight":"72px"},"border":{"radius":"var:preset|spacing|md"}},"layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center"}} -->
				<div class="wp-block-group fs-placeholder-tile" style="border-radius:var(--wp--preset--spacing--md);min-height:72px">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
					<p class="has-muted-color has-text-color has-sm-font-size" style="font-weight:600">МГУ</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
