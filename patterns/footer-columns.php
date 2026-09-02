<?php
/**
 * Title: Футер — контакты, карта, юридическая информация
 * Slug: fs-lms-theme/footer-columns
 * Categories: fs-lms-layout
 * Block Types: core/template-part/footer
 * Keywords: футер, подвал, footer, карта, реквизиты
 *
 * Перестроен по содержимому текущего сайта (`refs/ЕГЭ по информатике в
 * Калининграде.html`, WoodMart) — 3 колонки (лого+адрес / карта / юр.
 * реквизиты), а не абстрактная «РАЗДЕЛЫ/КОНТАКТЫ/СОЦСЕТИ» сетка из
 * исходного мокапа: соцсети переехали в шапку (`header-nav.php`), карта и
 * ИНН/ОГРН/оферта — обязательный минимум для организации, оказывающей
 * платные услуги. Ссылки на «Публичная оферта»/«Политика
 * конфиденциальности» — реальные slug'и текущего сайта (`/public-offer/`,
 * `/privacy-policy/`); страниц темы под них пока нет, но URL уже верный —
 * когда появятся, ссылки просто заработают.
 */
?>
<!-- wp:group {"backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|xxxl","bottom":"var:preset|spacing|xl"}},"border":{"top":{"color":"var:preset|color|border","width":"1px"}}}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-top-color:var(--wp--preset--color--border);border-top-width:1px;padding-top:var(--wp--preset--spacing--xxxl);padding-bottom:var(--wp--preset--spacing--xl)">
	<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
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

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
			<p class="has-text-secondary-color has-text-color has-sm-font-size"><a href="https://yandex.ru/maps/-/CHcWUGo2">236006, г. Калининград, ул. Черняховского, д. 6, каб. 316</a><br><a href="tel:+79953264486">+7 995 326 44 86</a><br><a href="mailto:info@future-step.ru">info@future-step.ru</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:html -->
			<iframe class="fs-aspect-16-9" src="https://yandex.ru/map-widget/v1/?indoorLevel=1&amp;ll=20.503606%2C54.718401&amp;oid=187566652967&amp;ol=biz&amp;z=16.53" width="100%" loading="lazy" style="border:0;border-radius:var(--wp--custom--radius--md)" title="<?php echo esc_attr__( 'Карта — где мы находимся', 'fs-lms-theme' ); ?>"></iframe>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xs"} -->
			<p class="has-muted-2-color has-text-color has-xs-font-size">ИНФОРМАЦИЯ</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
			<p class="has-text-secondary-color has-text-color has-sm-font-size">ИП Иванов Борис Олегович<br>ИНН: 390407910400<br>ОГРН: 322390000000350</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
			<p class="has-text-secondary-color has-text-color has-sm-font-size"><a href="<?php echo esc_url( home_url( '/public-offer/' ) ); ?>">Публичная оферта</a><br><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Политика конфиденциальности</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:separator {"backgroundColor":"border-light","className":"is-style-wide"} -->
	<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background is-style-wide"/>
	<!-- /wp:separator -->

	<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xs"} -->
	<p class="has-muted-2-color has-text-color has-xs-font-size">© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. Репетитор ЕГЭ по информатике. Россия, Калининград.</p>
	<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
