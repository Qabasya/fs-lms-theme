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

		<!-- wp:fs-lms/course-card {"imageUrl":"<?php echo esc_url( get_theme_file_uri( 'img/dir-robo.png' ) ); ?>","imageAlt":"Робототехника","badgeText":"5–8 класс","badgeColor":"subject-yellow-text","title":"Робототехника","caption":"Собираем и программируем роботов, разбираем механику и датчики, готовимся к соревнованиям.","price":"6 400 ₽","priceUnit":"/ мес","buttonText":"Подробнее","buttonUrl":"<?php echo fs_lms_theme_subject_url( 'robo', 'overview' ); ?>"} -->
		<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media"><img src="<?php echo esc_url( get_theme_file_uri( 'img/dir-robo.png' ) ); ?>" alt="Робототехника" /></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-subject-yellow-text-color has-subject-yellow-background-color  has-text-color has-background">5–8 класс</span><h3 class="fs-course-card__title">Робототехника</h3><p class="fs-course-card__caption">Собираем электронные схемы и учимся программировать микроконтроллеры</p><div class="fs-course-card__footer"><div class="fs-course-card__price"><span class="fs-course-card__price-amount">9 000 ₽</span><span class="fs-course-card__price-unit">/ мес</span></div><a class="fs-course-card__button wp-element-button" href="<?php echo fs_lms_theme_subject_url( 'robo', 'overview' ); ?>">Подробнее</a></div></div></div>
		<!-- /wp:fs-lms/course-card -->

		<!-- wp:fs-lms/course-card {"imageUrl":"<?php echo esc_url( get_theme_file_uri( 'img/dir-py.png' ) ); ?>","imageAlt":"Разработка на Python","badgeText":"10 класс","badgeColor":"ok","title":"Разработка на Python","caption":"От первой строчки кода до собственного проекта: алгоритмы, данные, боты и небольшие приложения.","price":"6 400 ₽","priceUnit":"/ мес","buttonText":"Подробнее","buttonUrl":"<?php echo fs_lms_theme_subject_url( 'python', 'overview' ); ?>"} -->
		<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media"><img src="<?php echo esc_url( get_theme_file_uri( 'img/dir-py.png' ) ); ?>" alt="Разработка на Python" /></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-ok-color has-ok-soft-background-color has-text-color has-background">10 класс</span><h3 class="fs-course-card__title">Разработка на Python</h3><p class="fs-course-card__caption">Изучаем язык программирования Python с опорой на задания ЕГЭ</p><div class="fs-course-card__footer"><div class="fs-course-card__price"><span class="fs-course-card__price-amount">12 000 ₽</span><span class="fs-course-card__price-unit">/ мес</span></div><a class="fs-course-card__button wp-element-button" href="<?php echo fs_lms_theme_subject_url( 'python', 'overview' ); ?>">Подробнее</a></div></div></div>
		<!-- /wp:fs-lms/course-card -->

		<!-- wp:fs-lms/course-card {"imageUrl":"<?php echo esc_url( get_theme_file_uri( 'img/dir-oge.png' ) ); ?>","imageAlt":"ОГЭ по информатике","badgeText":"9 класс","badgeColor":"violet","title":"ОГЭ по информатике","caption":"Полный разбор формата экзамена, регулярные пробники и работа над ошибками.","price":"6 400 ₽","priceUnit":"/ мес","buttonText":"Подробнее","buttonUrl":"<?php echo fs_lms_theme_subject_url( 'inf_oge', 'overview' ); ?>"} -->
		<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media"><img src="<?php echo esc_url( get_theme_file_uri( 'img/dir-oge.png' ) ); ?>" alt="ОГЭ по информатике" /></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-violet-color has-violet-soft-background-color  has-text-color has-background">9 класс</span><h3 class="fs-course-card__title">ОГЭ по информатике</h3><p class="fs-course-card__caption">Готовимся к основному экзамену и&nbsp;изучаем основы Python</p><div class="fs-course-card__footer"><div class="fs-course-card__price"><span class="fs-course-card__price-amount">10 000 ₽</span><span class="fs-course-card__price-unit">/ мес</span></div><a class="fs-course-card__button wp-element-button" href="<?php echo fs_lms_theme_subject_url( 'inf_oge', 'overview' ); ?>">Подробнее</a></div></div></div>
		<!-- /wp:fs-lms/course-card -->

		<!-- wp:fs-lms/course-card {"imageUrl":"<?php echo esc_url( get_theme_file_uri( 'img/dir-ege.png' ) ); ?>","imageAlt":"ЕГЭ по информатике","badgeText":"11 класс","badgeColor":"info","title":"ЕГЭ по информатике","caption":"Все задания экзамена, программирование на Python и индивидуальные консультации с репетитором.","price":"6 400 ₽","priceUnit":"/ мес","buttonText":"Подробнее","buttonUrl":"<?php echo fs_lms_theme_subject_url( 'inf_ege', 'overview' ); ?>"} -->
		<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media"><img src="<?php echo esc_url( get_theme_file_uri( 'img/dir-ege.png' ) ); ?>" alt="ЕГЭ по информатике" /></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-info-color has-info-soft-background-color has-text-color has-background">11 класс</span><h3 class="fs-course-card__title">ЕГЭ по информатике</h3><p class="fs-course-card__caption">Разбираем все задания экзамена и&nbsp;учимся программировать на Python</p><div class="fs-course-card__footer"><div class="fs-course-card__price"><span class="fs-course-card__price-amount">14 000 ₽</span><span class="fs-course-card__price-unit">/ мес</span></div><a class="fs-course-card__button wp-element-button" href="<?php echo fs_lms_theme_subject_url( 'inf_ege', 'overview' ); ?>">Подробнее</a></div></div></div>
		<!-- /wp:fs-lms/course-card -->

	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
