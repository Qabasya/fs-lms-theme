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
 *
 * Фаза 11: секция не рендерится, если опубликованных постов меньше 3 —
 * пустой/недобитый ряд карточек выглядит хуже, чем отсутствие секции.
 * Паттерн исполняется как обычный PHP (`ob_start()+include`, см. Фазу 3),
 * поэтому условие — просто `if` вокруг разметки. Обложка — `aspectRatio`
 * вместо фиксированной `height` (16/9 по макету); `core/post-featured-image`
 * — динамический блок (свой `render_callback`), поэтому нестандартный
 * атрибут не вызывает несовпадение при валидации блока в редакторе (в
 * отличие от статичных блоков типа `core/group`, см. tasks.md Фаза 11).
 */

if ( wp_count_posts()->publish < 3 ) {
	return;
}
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"fontSize":"xxl"} -->
		<h2 class="wp-block-heading has-xxl-font-size">Как проходят наши занятия</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
		<p class="has-sm-font-size" style="font-weight:600"><a href="#">Все записи →</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"className":"is-style-card"} -->
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","style":{"border":{"radius":"0"}}} /-->

			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|lg","bottom":"var:preset|spacing|lg","left":"var:preset|spacing|lg","right":"var:preset|spacing|lg"}}}} -->
			<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--lg);padding-right:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--lg);padding-left:var(--wp--preset--spacing--lg)">
				<!-- wp:post-date {"textColor":"muted-2","fontSize":"xxs"} /-->

				<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"lg"} /-->

				<!-- wp:post-excerpt {"excerptLength":22,"textColor":"muted","fontSize":"sm"} /-->

				<!-- wp:read-more {"content":"Читать →","textColor":"text","fontSize":"sm"} /-->
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
