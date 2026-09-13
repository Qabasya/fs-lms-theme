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
 * это повторной ошибкой). Ряд логотипов — Splide-карусель (`loop`, без
 * стрелок — автопрокрутка, как в мокапе), разбирает `src/js/carousels.js`
 * (Фаза 12.0).
 *
 * BugFix (tasks.md): контейнер карусели вузов фиксирован на 160px по
 * высоте (`.fs-carousel-mask--strip` в `theme.scss`) — модификатор
 * отдельный от `.fs-carousel-mask`, т.к. карусель выпускников
 * (`alumni-carousel.php`) использует ту же базовую разметку, но с
 * высотой по содержимому карточек, а не фиксированной.
 *
 * BugFix (tasks.md, 2026-09-13, п.2 и п.5): класс `fs-alumni-strip` на серой
 * карточке — на телефоне её боковой паддинг (ядро даёт группам с фоном
 * 2.375em) уменьшается до 1.25rem, логотипы — до 80px в высоту
 * (`theme.scss`), иначе один логотип не помещался на экран.
 *
 * Этап 1 (2026-09-13): логотипы — записи «Вузы» в админке
 * (`inc/Showcase/Universities.php`): название — `alt`, изображение записи —
 * логотип. Паттерн собирает слайды из опубликованных; нет ни одного вуза —
 * секции нет.
 */

$fs_university_slides = FS_LMS_Theme_Showcase::universities()->slides_markup();

if ( '' === $fs_university_slides ) {
	return;
}
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:group {"className":"fs-alumni-strip","backgroundColor":"surface-2","style":{"border":{"color":"var:preset|color|border","width":"1px","radius":"var:preset|spacing|md"},"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group fs-alumni-strip has-border-color has-surface-2-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--preset--spacing--md);padding-top:var(--wp--preset--spacing--xl);padding-bottom:var(--wp--preset--spacing--xxl)">

		<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
		<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Наши выпускники поступают</h2>
		<!-- /wp:heading -->

		<!-- wp:group {"className":"splide fs-carousel fs-carousel--auto-width fs-carousel--autoplay fs-carousel--no-arrows fs-carousel-mask fs-carousel-mask--strip"} -->
		<div class="wp-block-group splide fs-carousel fs-carousel--auto-width fs-carousel--autoplay fs-carousel--no-arrows fs-carousel-mask fs-carousel-mask--strip">
			<!-- wp:group {"className":"splide__track"} -->
			<div class="wp-block-group splide__track">
				<!-- wp:group {"className":"splide__list"} -->
				<div class="wp-block-group splide__list">
<?php echo $fs_university_slides; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- слайды собраны с экранированием в FS_LMS_Theme_Universities::render_slide(). ?>

				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
