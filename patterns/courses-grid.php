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
 * тот же приём, что и `fs-features-grid`), не `wp:columns`. Кнопки
 * «Записаться» — якорь `#signup` (блок 12.8, Фаза 12 решение 5), не
 * `fs_lms_theme_url('apply')` напрямую. Бейдж класса — цвет `accent-2`
 * (новый вариант в `BADGE_COLORS`, Фаза 12.6), как в макете v4.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div id="dirs" class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
	<div class="wp-block-group">
		<!-- wp:group {"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"fontSize":"xxl"} -->
			<h2 class="wp-block-heading has-xxl-font-size">Направления</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Можно начать с любого возраста и перейти на следующую ступень внутри школы.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:paragraph {"fontSize":"sm","style":{"typography":{"fontWeight":"500"}}} -->
		<p class="has-sm-font-size" style="font-weight:500"><a href="#dirs">Все курсы и цены →</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"fs-courses-grid","layout":{"type":"grid","columnCount":4}} -->
	<div class="wp-block-group fs-courses-grid">

		<!-- wp:fs-lms/course-card {"badgeText":"5–8 класс","badgeColor":"accent-2","title":"Робототехника","caption":"Собираем и программируем роботов, разбираем механику и датчики, готовимся к соревнованиям.","price":"6 400 ₽","priceUnit":"/ мес","buttonText":"Записаться","buttonUrl":"#signup"} -->
		<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media fs-placeholder-tile"></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-accent-2-color has-accent-2-soft-background-color has-text-color has-background">5–8 класс</span><h3 class="fs-course-card__title">Робототехника</h3><p class="fs-course-card__caption">Собираем и программируем роботов, разбираем механику и датчики, готовимся к соревнованиям.</p><div class="fs-course-card__footer"><div class="fs-course-card__price"><span class="fs-course-card__price-amount">6 400 ₽</span><span class="fs-course-card__price-unit">/ мес</span></div><a class="fs-course-card__button wp-element-button" href="#signup">Записаться</a></div></div></div>
		<!-- /wp:fs-lms/course-card -->

		<!-- wp:fs-lms/course-card {"badgeText":"10 класс","badgeColor":"accent-2","title":"Разработка на Python","caption":"От первой строчки кода до собственного проекта: алгоритмы, данные, боты и небольшие приложения.","price":"6 400 ₽","priceUnit":"/ мес","buttonText":"Записаться","buttonUrl":"#signup"} -->
		<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media fs-placeholder-tile"></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-accent-2-color has-accent-2-soft-background-color has-text-color has-background">10 класс</span><h3 class="fs-course-card__title">Разработка на Python</h3><p class="fs-course-card__caption">От первой строчки кода до собственного проекта: алгоритмы, данные, боты и небольшие приложения.</p><div class="fs-course-card__footer"><div class="fs-course-card__price"><span class="fs-course-card__price-amount">6 400 ₽</span><span class="fs-course-card__price-unit">/ мес</span></div><a class="fs-course-card__button wp-element-button" href="#signup">Записаться</a></div></div></div>
		<!-- /wp:fs-lms/course-card -->

		<!-- wp:fs-lms/course-card {"badgeText":"9 класс","badgeColor":"accent-2","title":"ОГЭ по информатике","caption":"Полный разбор формата экзамена, регулярные пробники и работа над ошибками.","price":"6 400 ₽","priceUnit":"/ мес","buttonText":"Записаться","buttonUrl":"#signup"} -->
		<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media fs-placeholder-tile"></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-accent-2-color has-accent-2-soft-background-color has-text-color has-background">9 класс</span><h3 class="fs-course-card__title">ОГЭ по информатике</h3><p class="fs-course-card__caption">Полный разбор формата экзамена, регулярные пробники и работа над ошибками.</p><div class="fs-course-card__footer"><div class="fs-course-card__price"><span class="fs-course-card__price-amount">6 400 ₽</span><span class="fs-course-card__price-unit">/ мес</span></div><a class="fs-course-card__button wp-element-button" href="#signup">Записаться</a></div></div></div>
		<!-- /wp:fs-lms/course-card -->

		<!-- wp:fs-lms/course-card {"badgeText":"11 класс","badgeColor":"accent-2","title":"ЕГЭ по информатике","caption":"Все задания экзамена, программирование на Python и индивидуальные консультации с репетитором.","price":"6 400 ₽","priceUnit":"/ мес","buttonText":"Записаться","buttonUrl":"#signup"} -->
		<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media fs-placeholder-tile"></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-accent-2-color has-accent-2-soft-background-color has-text-color has-background">11 класс</span><h3 class="fs-course-card__title">ЕГЭ по информатике</h3><p class="fs-course-card__caption">Все задания экзамена, программирование на Python и индивидуальные консультации с репетитором.</p><div class="fs-course-card__footer"><div class="fs-course-card__price"><span class="fs-course-card__price-amount">6 400 ₽</span><span class="fs-course-card__price-unit">/ мес</span></div><a class="fs-course-card__button wp-element-button" href="#signup">Записаться</a></div></div></div>
		<!-- /wp:fs-lms/course-card -->

	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
