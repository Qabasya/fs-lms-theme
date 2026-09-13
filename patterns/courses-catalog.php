<?php
/**
 * Title: Курсы — заголовок, фильтр по классу, 4 карточки направлений
 * Slug: fs-lms-theme/courses-catalog
 * Categories: fs-lms-sections
 * Keywords: курсы, направления, каталог, courses
 *
 * Фаза 16.5 (источник дизайна — `Курсы - мокап.dc.html`, тот же Claude
 * Design проект, что и Фазы 12/13/15, импортирован через `DesignSync`).
 * Новая страница-каталог `/courses/` (подтверждено пользователем
 * 2026-09-03: отдельная страница со своим URL, НЕ встраивается в
 * страницы предметов плагина, карточки — обычные ссылки на них).
 *
 * `<h1>` НЕ дублируется — рендерится `wp:post-title` в `templates/page-wide.html`
 * (тот же подход, что у `/about/`, Фаза 15/16.1); паттерн начинается с
 * описания и фильтра по классу. Размер заголовка с 2026-09-07 — `xxl`
 * (`has-xxl-font-size`, было `xxxl`): единый для всех внутренних
 * страниц, см. `inc/StaticPages.php`.
 *
 * Фаза 17.3 — всё содержимое правится в редакторе, без кода:
 *  - карточки — блок `fs-lms/catalog-card` (`src/blocks/catalog-card/`):
 *    обложка через медиатеку, тексты RichText, теги/кнопка/класс для
 *    фильтра — в инспекторе, цвет бейджа — из палитры theme.json
 *    (`BADGE_COLORS`, те же пары «текст+подложка», что у
 *    `fs-lms/course-card`). Раньше это была статичная разметка `wp:html`,
 *    которую можно было править только в коде;
 *  - чипсы фильтра — обычные `wp:button`, значение фильтра лежит в адресе
 *    ссылки (`#grade-11`), см. `src/js/course-filter.js`.
 *
 * Цвета бейджей — то же сопоставление направление↔цвет, что на главной
 * (`courses-grid.php`, BugFix.6): Робототехника=`violet`, Python=`ok`,
 * ОГЭ=`subject-yellow-text`, ЕГЭ=`info`.
 *
 * Кнопка — `fs_lms_theme_subject_url( $key, 'overview' )` (корневая
 * страница предмета, case `'overview'` в `inc/PluginRoutes.php`). Вся
 * карточка кликабельна приёмом stretched-link (см.
 * `src/blocks/catalog-card/style.scss`).
 *
 * Этап 3 (2026-09-13): карточки — из записей «Направления» в админке
 * (`inc/Showcase/Directions.php`): класс для фильтра, частота, пояснение к
 * цене и широкая картинка каталога — поля записи. Чипсы фильтра остаются
 * здесь: значения фильтра в записи выбираются из тех же пяти.
 */
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">

	<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md","style":{"typography":{"fontWeight":"300"}}} -->
		<p class="has-text-secondary-color has-text-color has-md-font-size" style="font-weight:300;max-width:35rem">Обучение программированию с 5 по 11 класс</p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"className":"fs-course-filter"} -->
		<div class="wp-block-group fs-course-filter">
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
			<!-- wp:button {"className":"fs-course-filter__chip-button"} -->
			<div class="wp-block-button fs-course-filter__chip-button"><a class="wp-block-button__link wp-element-button" href="#grade-all">Все классы</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"fs-course-filter__chip-button"} -->
			<div class="wp-block-button fs-course-filter__chip-button"><a class="wp-block-button__link wp-element-button" href="#grade-5-8">5–8</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"fs-course-filter__chip-button"} -->
			<div class="wp-block-button fs-course-filter__chip-button"><a class="wp-block-button__link wp-element-button" href="#grade-9">9</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"fs-course-filter__chip-button"} -->
			<div class="wp-block-button fs-course-filter__chip-button"><a class="wp-block-button__link wp-element-button" href="#grade-10">10</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"fs-course-filter__chip-button"} -->
			<div class="wp-block-button fs-course-filter__chip-button"><a class="wp-block-button__link wp-element-button" href="#grade-11">11</a></div>
			<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"fs-course-catalog"} -->
	<div class="wp-block-group fs-course-catalog">

<?php echo FS_LMS_Theme_Showcase::directions()->catalog_cards_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- карточки собраны с экранированием в FS_LMS_Theme_Directions. ?>

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
