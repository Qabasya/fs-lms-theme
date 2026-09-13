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
 * `src/js/carousels.js` (Фаза 12.0).
 *
 * BugFix (tasks.md): контейнер карусели вузов фиксирован на 160px по
 * высоте (`.fs-carousel-mask--strip` в `theme.scss`) — модификатор
 * отдельный от `.fs-carousel-mask`, т.к. карусель выпускников
 * (`alumni-carousel.php`) использует ту же базовую разметку, но с
 * высотой по содержимому карточек, а не фиксированной.
 *
 * Задача 5 (tasks.md, 2026-09-04): названия вузов текстом заменены на
 * `wp:image` — временно везде один и тот же плейсхолдер
 * `images/main_univer_5.png` (по прямому указанию), `alt` оставлен
 * названием вуза (доступность/SEO не теряются, даже когда картинка у
 * всех слайдов одна и та же). Реальные логотипы редактор подставит
 * через медиатеку на каждый слайд отдельно.
 *
 * BugFix (tasks.md, 2026-09-13, п.2 и п.5): класс `fs-alumni-strip` на серой
 * карточке — на телефоне её боковой паддинг (ядро даёт группам с фоном
 * 2.375em) уменьшается до 1.25rem, логотипы — до 80px в высоту
 * (`theme.scss`), иначе один логотип не помещался на экран.
 */
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
					<!-- wp:image {"className":"fs-alumni-logo splide__slide"} -->
					<figure class="wp-block-image fs-alumni-logo splide__slide"><img src="<?php echo esc_url( get_theme_file_uri( 'images/main_univer_5.png' ) ); ?>" alt="БФУ им. Канта"/></figure>
					<!-- /wp:image -->

					<!-- wp:image {"className":"fs-alumni-logo splide__slide"} -->
					<figure class="wp-block-image fs-alumni-logo splide__slide"><img src="<?php echo esc_url( get_theme_file_uri( 'images/main_univer_5.png' ) ); ?>" alt="МИРЭА"/></figure>
					<!-- /wp:image -->

					<!-- wp:image {"className":"fs-alumni-logo splide__slide"} -->
					<figure class="wp-block-image fs-alumni-logo splide__slide"><img src="<?php echo esc_url( get_theme_file_uri( 'images/main_univer_5.png' ) ); ?>" alt="ИТМО"/></figure>
					<!-- /wp:image -->

					<!-- wp:image {"className":"fs-alumni-logo splide__slide"} -->
					<figure class="wp-block-image fs-alumni-logo splide__slide"><img src="<?php echo esc_url( get_theme_file_uri( 'images/main_univer_5.png' ) ); ?>" alt="МГТУ"/></figure>
					<!-- /wp:image -->

					<!-- wp:image {"className":"fs-alumni-logo splide__slide"} -->
					<figure class="wp-block-image fs-alumni-logo splide__slide"><img src="<?php echo esc_url( get_theme_file_uri( 'images/main_univer_5.png' ) ); ?>" alt="РУДН"/></figure>
					<!-- /wp:image -->
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
