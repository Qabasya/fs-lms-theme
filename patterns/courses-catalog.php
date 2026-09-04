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
 * описания и фильтра по классу.
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
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">

	<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md","style":{"typography":{"fontWeight":"300"}}} -->
		<p class="has-text-secondary-color has-text-color has-md-font-size" style="font-weight:300;max-width:35rem">Четыре направления с 5 по 11 класс. Можно начать с любого возраста и перейти на следующую ступень внутри школы — программа продолжается, преподаватели те же.</p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"className":"fs-course-filter"} -->
		<div class="wp-block-group fs-course-filter">
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
			<!-- wp:button {"className":"fs-course-filter__chip-button"} -->
			<div class="wp-block-button fs-course-filter__chip-button"><a class="wp-block-button__link wp-element-button is-active" href="#grade-all">Все классы</a></div>
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

	<!-- wp:fs-lms/catalog-card {"imageUrl":"<?php echo esc_url( get_theme_file_uri( 'img/dir-ege.png' ) ); ?>","imageAlt":"ЕГЭ по информатике","badgeText":"11 класс","badgeColor":"info","formatText":"2 раза в неделю","title":"ЕГЭ по информатике","text":"Все 27 заданий экзамена, программирование на Python, пробники в формате ЕГЭ и индивидуальные консультации.","tags":"Python, Пробники каждый месяц, Группы до 8","priceAmount":"6 400 ₽","priceNote":"800 ₽ за час, занятие 2 часа","buttonText":"Подробнее","buttonUrl":"<?php echo esc_url( fs_lms_theme_subject_url( 'inf_ege', 'overview' ) ); ?>","grade":"11"} -->
	<div class="wp-block-fs-lms-catalog-card fs-course-catalog-card" data-grade="11"><div class="fs-course-catalog-card__media"><img src="<?php echo esc_url( get_theme_file_uri( 'img/dir-ege.png' ) ); ?>" alt="ЕГЭ по информатике"/></div><div class="fs-course-catalog-card__body"><div class="fs-course-catalog-card__meta"><span class="fs-course-catalog-card__badge has-info-color has-info-soft-background-color has-text-color has-background">11 класс</span><span class="fs-course-catalog-card__format">2 раза в неделю</span></div><h3 class="fs-course-catalog-card__title">ЕГЭ по информатике</h3><p class="fs-course-catalog-card__text">Все 27 заданий экзамена, программирование на Python, пробники в формате ЕГЭ и индивидуальные консультации.</p><div class="fs-course-catalog-card__tags"><span class="fs-course-catalog-card__tag">Python</span><span class="fs-course-catalog-card__tag">Пробники каждый месяц</span><span class="fs-course-catalog-card__tag">Группы до 8</span></div><div class="fs-course-catalog-card__footer"><div class="fs-course-catalog-card__price"><span class="fs-course-catalog-card__price-amount">6 400 ₽</span><span class="fs-course-catalog-card__price-note">800 ₽ за час, занятие 2 часа</span></div><div class="fs-course-catalog-card__actions"><a class="fs-course-catalog-card__button fs-course-catalog-card__button--solid" href="<?php echo esc_url( fs_lms_theme_subject_url( 'inf_ege', 'overview' ) ); ?>">Подробнее</a></div></div></div></div>
	<!-- /wp:fs-lms/catalog-card -->

	<!-- wp:fs-lms/catalog-card {"imageUrl":"<?php echo esc_url( get_theme_file_uri( 'img/dir-oge.png' ) ); ?>","imageAlt":"ОГЭ по информатике","badgeText":"9 класс","badgeColor":"subject-yellow-text","formatText":"2 раза в неделю","title":"ОГЭ по информатике","text":"Полный разбор формата экзамена, практика в реальных программах и работа над ошибками.","tags":"Практическая часть, Работа над ошибками, Группы до 8","priceAmount":"6 400 ₽","priceNote":"800 ₽ за час, занятие 2 часа","buttonText":"Подробнее","buttonUrl":"<?php echo esc_url( fs_lms_theme_subject_url( 'inf_oge', 'overview' ) ); ?>","grade":"9"} -->
	<div class="wp-block-fs-lms-catalog-card fs-course-catalog-card" data-grade="9"><div class="fs-course-catalog-card__media"><img src="<?php echo esc_url( get_theme_file_uri( 'img/dir-oge.png' ) ); ?>" alt="ОГЭ по информатике"/></div><div class="fs-course-catalog-card__body"><div class="fs-course-catalog-card__meta"><span class="fs-course-catalog-card__badge has-subject-yellow-text-color has-subject-yellow-background-color has-text-color has-background">9 класс</span><span class="fs-course-catalog-card__format">2 раза в неделю</span></div><h3 class="fs-course-catalog-card__title">ОГЭ по информатике</h3><p class="fs-course-catalog-card__text">Полный разбор формата экзамена, практика в реальных программах и работа над ошибками.</p><div class="fs-course-catalog-card__tags"><span class="fs-course-catalog-card__tag">Практическая часть</span><span class="fs-course-catalog-card__tag">Работа над ошибками</span><span class="fs-course-catalog-card__tag">Группы до 8</span></div><div class="fs-course-catalog-card__footer"><div class="fs-course-catalog-card__price"><span class="fs-course-catalog-card__price-amount">6 400 ₽</span><span class="fs-course-catalog-card__price-note">800 ₽ за час, занятие 2 часа</span></div><div class="fs-course-catalog-card__actions"><a class="fs-course-catalog-card__button fs-course-catalog-card__button--solid" href="<?php echo esc_url( fs_lms_theme_subject_url( 'inf_oge', 'overview' ) ); ?>">Подробнее</a></div></div></div></div>
	<!-- /wp:fs-lms/catalog-card -->

	<!-- wp:fs-lms/catalog-card {"imageUrl":"<?php echo esc_url( get_theme_file_uri( 'img/dir-py.png' ) ); ?>","imageAlt":"Разработка на Python","badgeText":"10 класс","badgeColor":"ok","formatText":"2 раза в неделю","title":"Разработка на Python","text":"От первой строчки кода до собственного проекта: алгоритмы, данные, боты и небольшие приложения.","tags":"Проект в портфолио, Алгоритмы, Боты","priceAmount":"6 400 ₽","priceNote":"800 ₽ за час, занятие 2 часа","buttonText":"Подробнее","buttonUrl":"<?php echo esc_url( fs_lms_theme_subject_url( 'python', 'overview' ) ); ?>","grade":"10"} -->
	<div class="wp-block-fs-lms-catalog-card fs-course-catalog-card" data-grade="10"><div class="fs-course-catalog-card__media"><img src="<?php echo esc_url( get_theme_file_uri( 'img/dir-py.png' ) ); ?>" alt="Разработка на Python"/></div><div class="fs-course-catalog-card__body"><div class="fs-course-catalog-card__meta"><span class="fs-course-catalog-card__badge has-ok-color has-ok-soft-background-color has-text-color has-background">10 класс</span><span class="fs-course-catalog-card__format">2 раза в неделю</span></div><h3 class="fs-course-catalog-card__title">Разработка на Python</h3><p class="fs-course-catalog-card__text">От первой строчки кода до собственного проекта: алгоритмы, данные, боты и небольшие приложения.</p><div class="fs-course-catalog-card__tags"><span class="fs-course-catalog-card__tag">Проект в портфолио</span><span class="fs-course-catalog-card__tag">Алгоритмы</span><span class="fs-course-catalog-card__tag">Боты</span></div><div class="fs-course-catalog-card__footer"><div class="fs-course-catalog-card__price"><span class="fs-course-catalog-card__price-amount">6 400 ₽</span><span class="fs-course-catalog-card__price-note">800 ₽ за час, занятие 2 часа</span></div><div class="fs-course-catalog-card__actions"><a class="fs-course-catalog-card__button fs-course-catalog-card__button--solid" href="<?php echo esc_url( fs_lms_theme_subject_url( 'python', 'overview' ) ); ?>">Подробнее</a></div></div></div></div>
	<!-- /wp:fs-lms/catalog-card -->

	<!-- wp:fs-lms/catalog-card {"imageUrl":"<?php echo esc_url( get_theme_file_uri( 'img/dir-robo.png' ) ); ?>","imageAlt":"Робототехника","badgeText":"5–8 класс","badgeColor":"violet","formatText":"2 раза в неделю","title":"Робототехника","text":"Собираем и программируем роботов, разбираем механику и датчики, готовимся к соревнованиям.","tags":"Соревнования, Датчики и механика, Оборудование школы","priceAmount":"6 400 ₽","priceNote":"800 ₽ за час, занятие 2 часа","buttonText":"Подробнее","buttonUrl":"<?php echo esc_url( fs_lms_theme_subject_url( 'robo', 'overview' ) ); ?>","grade":"5-8"} -->
	<div class="wp-block-fs-lms-catalog-card fs-course-catalog-card" data-grade="5-8"><div class="fs-course-catalog-card__media"><img src="<?php echo esc_url( get_theme_file_uri( 'img/dir-robo.png' ) ); ?>" alt="Робототехника"/></div><div class="fs-course-catalog-card__body"><div class="fs-course-catalog-card__meta"><span class="fs-course-catalog-card__badge has-violet-color has-violet-soft-background-color has-text-color has-background">5–8 класс</span><span class="fs-course-catalog-card__format">2 раза в неделю</span></div><h3 class="fs-course-catalog-card__title">Робототехника</h3><p class="fs-course-catalog-card__text">Собираем и программируем роботов, разбираем механику и датчики, готовимся к соревнованиям.</p><div class="fs-course-catalog-card__tags"><span class="fs-course-catalog-card__tag">Соревнования</span><span class="fs-course-catalog-card__tag">Датчики и механика</span><span class="fs-course-catalog-card__tag">Оборудование школы</span></div><div class="fs-course-catalog-card__footer"><div class="fs-course-catalog-card__price"><span class="fs-course-catalog-card__price-amount">6 400 ₽</span><span class="fs-course-catalog-card__price-note">800 ₽ за час, занятие 2 часа</span></div><div class="fs-course-catalog-card__actions"><a class="fs-course-catalog-card__button fs-course-catalog-card__button--solid" href="<?php echo esc_url( fs_lms_theme_subject_url( 'robo', 'overview' ) ); ?>">Подробнее</a></div></div></div></div>
	<!-- /wp:fs-lms/catalog-card -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
