<?php
/**
 * Title: Футер — о нас, разделы, контакты, соцсети
 * Slug: fs-lms-theme/footer-columns
 * Categories: fs-lms-layout
 * Block Types: core/template-part/footer
 */
?>
<!-- wp:group {"backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|3xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|2xl","right":"var:preset|spacing|2xl"}},"border":{"top":{"color":"var:preset|color|border","width":"1px"}}}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-top-color:var(--wp--preset--color--border);border-top-width:1px;padding-top:var(--wp--preset--spacing--3xl);padding-right:var(--wp--preset--spacing--2xl);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--2xl)">
	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|sm"}}} -->
			<div class="wp-block-group">
				<!-- wp:site-logo {"width":32,"shouldSyncIcon":false,"style":{"border":{"radius":"var:preset|spacing|xs"}}} /-->
				<!-- wp:site-title {"level":3,"fontSize":"sm"} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:paragraph {"textColor":"muted","fontSize":"sm"} -->
			<p class="has-muted-color has-text-color has-sm-font-size">Подготовка ЕГЭ по информатике в Калининграде</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xs"} -->
			<p class="has-muted-2-color has-text-color has-xs-font-size">РАЗДЕЛЫ</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
			<p class="has-text-secondary-color has-text-color has-sm-font-size"><a href="#">Главная</a><br><a href="#">О нас</a><br><a href="#">Курсы</a><br><a href="#">Учебник</a><br><a href="#">Тренажёр</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xs"} -->
			<p class="has-muted-2-color has-text-color has-xs-font-size">КОНТАКТЫ</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
			<p class="has-text-secondary-color has-text-color has-sm-font-size">+7 995 326 44 86<br>info@future-step.ru<br>Калининград</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xs"} -->
			<p class="has-muted-2-color has-text-color has-xs-font-size">СОЦСЕТИ</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"sm"} -->
			<p class="has-sm-font-size"><a href="#">YouTube</a> · <a href="#">VK</a> · <a href="#">Telegram</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:separator {"backgroundColor":"border-light","className":"is-style-wide"} -->
	<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background is-style-wide"/>
	<!-- /wp:separator -->

	<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xs"} -->
	<p class="has-muted-2-color has-text-color has-xs-font-size">© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
