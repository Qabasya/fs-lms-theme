<?php
/**
 * Title: Наши выпускники поступают — ряд логотипов
 * Slug: fs-lms-theme/alumni-strip
 * Categories: fs-lms-sections
 * Keywords: выпускники, вузы, alumni
 *
 * Фаза 11 (`refs/Главная v2 - мокап.dc.html`): убрана белая
 * карточка-обёртка (была не в макете — Фаза 5 добавила её от себя) —
 * ряд логотипов идёт прямо на сером фоне страницы, подпись
 * `12px/uppercase/muted`. Логотипы — заглушки `.fs-placeholder-tile`,
 * реальные вставляет редактор через `wp:image`.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:paragraph {"textColor":"muted","fontSize":"xxs","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em","fontWeight":"500"}}} -->
	<p class="has-muted-color has-text-color has-xxs-font-size" style="font-weight:500;letter-spacing:0.08em;text-transform:uppercase">Наши выпускники поступают</p>
	<!-- /wp:paragraph -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.5rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-alumni-logo","style":{"dimensions":{"minHeight":"44px"},"border":{"radius":"var:preset|spacing|md"}},"backgroundColor":"border-light","layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center"}} -->
			<div class="wp-block-group fs-alumni-logo has-border-light-background-color has-background" style="border-radius:var(--wp--preset--spacing--md);min-height:44px">
				<!-- wp:paragraph {"textColor":"muted-2","fontSize":"sm","style":{"typography":{"fontWeight":"500"}}} -->
				<p class="has-muted-2-color has-text-color has-sm-font-size" style="font-weight:500">МИРЭА</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-alumni-logo","style":{"dimensions":{"minHeight":"44px"},"border":{"radius":"var:preset|spacing|md"}},"backgroundColor":"border-light","layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center"}} -->
			<div class="wp-block-group fs-alumni-logo has-border-light-background-color has-background" style="border-radius:var(--wp--preset--spacing--md);min-height:44px">
				<!-- wp:paragraph {"textColor":"muted-2","fontSize":"sm","style":{"typography":{"fontWeight":"500"}}} -->
				<p class="has-muted-2-color has-text-color has-sm-font-size" style="font-weight:500">ИТМО</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-alumni-logo","style":{"dimensions":{"minHeight":"44px"},"border":{"radius":"var:preset|spacing|md"}},"backgroundColor":"border-light","layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center"}} -->
			<div class="wp-block-group fs-alumni-logo has-border-light-background-color has-background" style="border-radius:var(--wp--preset--spacing--md);min-height:44px">
				<!-- wp:paragraph {"textColor":"muted-2","fontSize":"sm","style":{"typography":{"fontWeight":"500"}}} -->
				<p class="has-muted-2-color has-text-color has-sm-font-size" style="font-weight:500">РУДН</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-alumni-logo","style":{"dimensions":{"minHeight":"44px"},"border":{"radius":"var:preset|spacing|md"}},"backgroundColor":"border-light","layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center"}} -->
			<div class="wp-block-group fs-alumni-logo has-border-light-background-color has-background" style="border-radius:var(--wp--preset--spacing--md);min-height:44px">
				<!-- wp:paragraph {"textColor":"muted-2","fontSize":"sm","style":{"typography":{"fontWeight":"500"}}} -->
				<p class="has-muted-2-color has-text-color has-sm-font-size" style="font-weight:500">МГУ</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
