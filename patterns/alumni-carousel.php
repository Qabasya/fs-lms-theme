<?php
/**
 * Title: Наши выпускники — карусель отзывов
 * Slug: fs-lms-theme/alumni-carousel
 * Categories: fs-lms-sections
 * Keywords: выпускники, отзывы, alumni, карусель
 *
 * Фаза 12.3 (`Главная v4 - сборка.dc.html`): карточки `fs-lms/alumni-card`
 * (фото + баллы + имя + короткий отзыв) в Splide-карусели —
 * разметку разбирает `src/js/carousels.js` (Фаза 12.0), 3 слайда десктоп /
 * 2 планшет / 1 мобильный (брейкпоинты заданы в `initCarousels()`),
 * стрелки — стандартные Splide, перекрашены под мокап в `theme.scss`,
 * `mask-image` по краям — класс `.fs-carousel-mask`.
 *
 * Этап 1 (2026-09-13): карточки больше не зашиты в паттерн — это записи
 * «Выпускники» в админке (`inc/Showcase/Alumni.php`), паттерн собирает
 * слайды из опубликованных. Нет ни одного выпускника — секции нет.
 */

$fs_alumni_slides = FS_LMS_Theme_Showcase::alumni()->slides_markup();

if ( '' === $fs_alumni_slides ) {
	return;
}
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Наши выпускники</h2>
	<!-- /wp:heading -->

	<!-- wp:group {"className":"splide fs-carousel fs-carousel--per-3 fs-carousel-mask"} -->
	<div class="wp-block-group splide fs-carousel fs-carousel--per-3 fs-carousel-mask">
		<!-- wp:group {"className":"splide__track"} -->
		<div class="wp-block-group splide__track">
			<!-- wp:group {"className":"splide__list"} -->
			<div class="wp-block-group splide__list">
<?php echo $fs_alumni_slides; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- слайды собраны с экранированием в FS_LMS_Theme_Alumni::render_slide(). ?>

			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
