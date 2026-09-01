<?php
/**
 * Title: Как проходят наши занятия — сетка последних записей блога
 * Slug: fs-lms-theme/blog-grid
 * Categories: fs-lms-sections
 * Keywords: блог, новости, записи, blog
 *
 * Источник: блок «как проходят занятия» в «Главная — мокап.dc.html» (1a).
 * В отличие от остальных секций — это реальные посты блога (`core/query` +
 * `core/post-template`), не статичные карточки: у мокапа тут даты/бейджи
 * категорий/эксцерпт конкретных постов, а не переиспользуемая карточка.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"var:preset|spacing|xxxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxxl);padding-left:var(--wp--preset--spacing--xxl)">
	<!-- wp:heading {"fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-xxl-font-size">Как проходят наши занятия</h2>
	<!-- /wp:heading -->

	<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"className":"is-style-card"} -->
			<!-- wp:post-featured-image {"isLink":true,"height":"180px","style":{"border":{"radius":"0"}}} /-->

			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|lg","bottom":"var:preset|spacing|lg","left":"var:preset|spacing|lg","right":"var:preset|spacing|lg"}}}} -->
			<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--lg);padding-right:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--lg);padding-left:var(--wp--preset--spacing--lg)">
				<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"md"} /-->

				<!-- wp:group {"layout":{"type":"flex"},"style":{"spacing":{"blockGap":"var:preset|spacing|xs"}}} -->
				<div class="wp-block-group">
					<!-- wp:post-date {"textColor":"muted-2","fontSize":"xxs"} /-->

					<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xxs"} -->
					<p class="has-muted-2-color has-text-color has-xxs-font-size">·</p>
					<!-- /wp:paragraph -->

					<!-- wp:post-author-name {"textColor":"muted-2","fontSize":"xxs"} /-->
				</div>
				<!-- /wp:group -->

				<!-- wp:post-excerpt {"excerptLength":22,"textColor":"text-secondary","fontSize":"sm"} /-->

				<!-- wp:read-more {"content":"Продолжить чтение →","textColor":"accent","fontSize":"xs"} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"white","textColor":"text-secondary","className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-secondary-color has-white-background-color has-text-color has-background wp-element-button" href="#">Больше записей</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
