<?php
/**
 * Title: Наши выпускники — карусель отзывов
 * Slug: fs-lms-theme/alumni-carousel
 * Categories: fs-lms-sections
 * Keywords: выпускники, отзывы, alumni, карусель
 *
 * Фаза 12.3 (`Главная v4 - сборка.dc.html`): новый блок `fs-lms/alumni-card`
 * (фото + баллы + имя + короткий отзыв) в Splide-карусели —
 * `data-fs-carousel` разметку разбирает `src/js/carousels.js` (Фаза 12.0),
 * 3 слайда десктоп / 2 планшет / 1 мобильный (брейкпоинты заданы в
 * `initCarousels()`), стрелки — стандартные Splide, перекрашены под мокап в
 * `theme.scss`, `mask-image` по краям — класс `.fs-carousel-mask`.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Наши выпускники</h2>
	<!-- /wp:heading -->

	<!-- wp:group {"className":"splide fs-carousel fs-carousel--per-3 fs-carousel-mask"} -->
	<div class="wp-block-group splide fs-carousel fs-carousel--per-3 fs-carousel-mask">
		<!-- wp:group {"className":"splide__track"} -->
		<div class="wp-block-group splide__track">
			<!-- wp:group {"className":"splide__list"} -->
			<div class="wp-block-group splide__list">

	<!-- wp:fs-lms/alumni-card {"scoreText":"98 баллов","authorName":"Петрова Мария","quote":"Пришла с нуля в 10 классе, поступила в ИТМО на бюджет."} -->
	<div class="wp-block-fs-lms-alumni-card fs-alumni-card splide__slide"><div class="fs-alumni-card__media fs-placeholder-tile"></div><div class="fs-alumni-card__body"><div class="fs-alumni-card__score">98 баллов</div><div class="fs-alumni-card__name">Петрова Мария</div><p class="fs-alumni-card__quote">Пришла с нуля в 10 классе, поступила в ИТМО на бюджет.</p></div></div>
	<!-- /wp:fs-lms/alumni-card -->

	<!-- wp:fs-lms/alumni-card {"scoreText":"100 баллов","authorName":"Иванов Иван","quote":"Обожаю информатику, поступил в вуз мечты, кайф."} -->
	<div class="wp-block-fs-lms-alumni-card fs-alumni-card splide__slide"><div class="fs-alumni-card__media fs-placeholder-tile"></div><div class="fs-alumni-card__body"><div class="fs-alumni-card__score">100 баллов</div><div class="fs-alumni-card__name">Иванов Иван</div><p class="fs-alumni-card__quote">Обожаю информатику, поступил в вуз мечты, кайф.</p></div></div>
	<!-- /wp:fs-lms/alumni-card -->

	<!-- wp:fs-lms/alumni-card {"scoreText":"92 балла","authorName":"Соколов Артём","quote":"Два года робототехники, теперь учусь в МИРЭА."} -->
	<div class="wp-block-fs-lms-alumni-card fs-alumni-card splide__slide"><div class="fs-alumni-card__media fs-placeholder-tile"></div><div class="fs-alumni-card__body"><div class="fs-alumni-card__score">92 балла</div><div class="fs-alumni-card__name">Соколов Артём</div><p class="fs-alumni-card__quote">Два года робототехники, теперь учусь в МИРЭА.</p></div></div>
	<!-- /wp:fs-lms/alumni-card -->

			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
