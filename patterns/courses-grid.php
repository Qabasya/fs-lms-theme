<?php
/**
 * Title: Направления — заголовок + 4 карточки курсов
 * Slug: fs-lms-theme/courses-grid
 * Categories: fs-lms-sections
 * Keywords: курсы, направления, courses
 *
 * Источник: блок «направления» в «Главная v4 - сборка.dc.html» (Фаза 12.6).
 * `id="dirs"` — якорь для ссылок из `hero.php` (список направлений) и
 * `header-nav.php` («Курсы»). Карточек теперь 4 (добавлена «Робототехника»,
 * которой не было в Фазе 11), порядок как в v4: Робототехника, Python,
 * ОГЭ, ЕГЭ. Сетка — `wp:group{"layout":{"type":"grid"}}` (4 колонки,
 * тот же приём, что и `fs-features-grid`), не `wp:columns`.
 *
 * BugFix.6 (2026-09-03): бейдж класса — цвет по направлению вместо
 * единого `accent-2`: Python=`ok`, ЕГЭ=`info`.
 *
 * Задача 2 (2026-09-04, tasks.md): цвета ОГЭ/Робототехники поменяны
 * местами — ОГЭ=`violet`, Робототехника=`subject-yellow-text` (пара с
 * подложкой `subject-yellow` через `BG_OVERRIDE`, вариант «Жёлтый» в
 * `BADGE_COLORS`/`colors.js`).
 *
 * BugFix.8 (2026-09-03): вся карточка кликабельна («stretched link»,
 * `src/blocks/course-card/style.scss`), кнопка — одна, «Подробнее»,
 * ведёт на страницу направления в плагине через
 * `fs_lms_theme_subject_url( $key, 'overview' )` (тот же хелпер, что уже
 * использует кнопка «Программа» в `courses-catalog.php`) вместо якоря
 * `#signup`.
 *
 * Этап 3 (2026-09-13): карточки — из записей «Направления» в админке
 * (`inc/Showcase/Directions.php`), в порядке поля «Порядок» — тот же, что у
 * списка первого экрана и каталога «Курсы» (раньше здесь был обратный:
 * Робототехника, Python, ОГЭ, ЕГЭ). Разметка карточки прежняя.
 */
?>
<!-- wp:group {"className":"fs-section"} -->
<div id="dirs" class="wp-block-group fs-section">
	<!-- wp:group {"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"fontSize":"xxl"} -->
		<h2 class="wp-block-heading has-xxl-font-size">Направления</h2>
		<!-- /wp:heading -->


	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"fs-courses-grid","layout":{"type":"grid","columnCount":4}} -->
	<div class="wp-block-group fs-courses-grid">

<?php echo FS_LMS_Theme_Showcase::directions()->course_cards_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- карточки собраны с экранированием в FS_LMS_Theme_Directions. ?>

	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
