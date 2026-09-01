<?php
/**
 * Title: Hero — заголовок, текст, кнопки, картинка
 * Slug: fs-lms-theme/hero-split
 * Categories: fs-lms-sections
 * Keywords: hero, обложка, заголовок
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|xxxxl","bottom":"var:preset|spacing|xxxxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--xxxxl);padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxxxl);padding-left:var(--wp--preset--spacing--xxl)">
	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"backgroundColor":"accent-soft","textColor":"accent-700","fontSize":"xs","style":{"spacing":{"padding":{"top":"var:preset|spacing|xs","bottom":"var:preset|spacing|xs","left":"var:preset|spacing|md","right":"var:preset|spacing|md"}}}} -->
			<p class="has-accent-700-color has-accent-soft-background-color has-text-color has-background has-xs-font-size" style="padding-top:var(--wp--preset--spacing--xs);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xs);padding-left:var(--wp--preset--spacing--md)">КАЛИНИНГРАД · ГРУППЫ ДО 8 ЧЕЛОВЕК</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1,"fontSize":"xxxl"} -->
			<h1 class="wp-block-heading has-xxxl-font-size">Подготовка ЕГЭ по информатике</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"lead"} -->
			<p class="has-text-secondary-color has-text-color has-lead-font-size">Персональная поддержка профессионального репетитора для успешного обучения.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"accent"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"backgroundColor":"white","textColor":"text-secondary","className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-secondary-color has-white-background-color has-text-color has-background wp-element-button">О занятиях</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"sizeSlug":"large"} -->
			<figure class="wp-block-image size-large"><img src="" alt="Скриншот кода / занятия"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
