<?php
/**
 * Title: Наши выпускники поступают — карусель логотипов
 * Slug: fs-lms-theme/alumni-strip
 * Categories: fs-lms-sections
 * Keywords: выпускники, вузы, alumni, карусель
 *
 * Фаза 12.4 (`Главная v4 - сборка.dc.html`): разворот решения Фазы 11 —
 * серая карточка-обёртка вокруг логотипов возвращается (в v4 она есть,
 * в Фазе 11 её убрали, посчитав добавленной сверх мокапа v1 — не считать
 * это повторной ошибкой). Ряд логотипов — Splide-карусель (5 в ряд, `loop`,
 * без стрелок — автопрокрутка, как в мокапе), `data-fs-carousel` разбирает
 * `src/js/carousels.js` (Фаза 12.0). Логотипы — заглушки `.fs-placeholder-tile`,
 * реальные вставляет редактор через `wp:image`.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:group {"backgroundColor":"surface-2","style":{"border":{"color":"var:preset|color|border","width":"1px","radius":"var:preset|spacing|md"},"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group has-surface-2-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--preset--spacing--md);padding-top:var(--wp--preset--spacing--xl);padding-bottom:var(--wp--preset--spacing--xxl)">
		<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm","style":{"typography":{"textAlign":"center"}}} -->
		<p class="has-text-secondary-color has-text-color has-sm-font-size" style="text-align:center">Наши выпускники поступают</p>
		<!-- /wp:paragraph -->

		<!-- wp:html -->
		<div class="fs-carousel-mask" data-fs-carousel data-per-page="5" data-autoplay="true" data-arrows="false">
			<div class="splide__track">
				<div class="splide__list">
					<div class="fs-alumni-logo splide__slide">БФУ им. Канта</div>
					<div class="fs-alumni-logo splide__slide">МИРЭА</div>
					<div class="fs-alumni-logo splide__slide">ИТМО</div>
					<div class="fs-alumni-logo splide__slide">МГТУ</div>
					<div class="fs-alumni-logo splide__slide">РУДН</div>
				</div>
			</div>
		</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
