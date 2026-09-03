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
 * `<h1>` НЕ дублируется — рендерится `wp:post-title` в `templates/page.html`
 * (тот же подход, что у `/about/`, Фаза 15/16.1); паттерн начинается с
 * описания и фильтра по классу.
 *
 * Карточки — статичная разметка `wp:html`, 4 штуки хардкодом (не PHP-цикл
 * по массиву — ни один паттерн темы так не делает, см. `about-accordion.php`
 * с 12 хардкодед-секциями; не `fs-lms/course-card`, Фаза 12.6: у блока нет
 * атрибутов под формат/теги/вторую кнопку/примечание к цене, которые есть
 * в этом макете — расширять схему блока ради одной страницы не стали, тот
 * же выбор, что `subject-more.php` Фазы 13 сделал для карточек-ссылок).
 * Текст описаний — из скрипта макета (те же 4 направления, что на
 * главной, `courses-grid.php`, но своя формулировка под эту страницу).
 *
 * Фильтр по классу — реальная фильтрация (не декоративные чипсы макета):
 * `data-grade` на карточке, `data-filter` на чипсе,
 * `src/js/course-filter.js` (делегированный клик, показывает/прячет
 * карточки). Раз карточек всего 4 и всё на статичной разметке — не
 * заводили под это CPT/ACF.
 *
 * Кнопка «Программа» — `fs_lms_theme_subject_url( $key, 'overview' )`
 * (корневая страница предмета, новый case `'overview'` в
 * `inc/PluginRoutes.php`, Фаза 16.5); «Записаться» — якорь `#signup` на
 * форму `courses-contact.php` этой же страницы.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">

	<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md","style":{"typography":{"fontWeight":"300"}}} -->
		<p class="has-text-secondary-color has-text-color has-md-font-size" style="font-weight:300;max-width:35rem">Четыре направления с 5 по 11 класс. Можно начать с любого возраста и перейти на следующую ступень внутри школы — программа продолжается, преподаватели те же.</p>
		<!-- /wp:paragraph -->

		<!-- wp:html -->
		<div class="fs-course-filter" data-fs-course-filter role="group" aria-label="Фильтр по классу">
			<button type="button" class="fs-course-filter__chip is-active" data-filter="all">Все классы</button>
			<button type="button" class="fs-course-filter__chip" data-filter="5-8">5–8</button>
			<button type="button" class="fs-course-filter__chip" data-filter="9">9</button>
			<button type="button" class="fs-course-filter__chip" data-filter="10">10</button>
			<button type="button" class="fs-course-filter__chip" data-filter="11">11</button>
		</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->

	<!-- wp:html -->
	<div class="fs-course-catalog">

		<div class="fs-course-catalog-card" data-grade="11">
			<div class="fs-course-catalog-card__media fs-placeholder-tile"></div>
			<div class="fs-course-catalog-card__body">
				<div class="fs-course-catalog-card__meta">
					<span class="fs-course-catalog-card__badge">11 класс</span>
					<span class="fs-course-catalog-card__format">2 раза в неделю</span>
				</div>
				<h3 class="fs-course-catalog-card__title">ЕГЭ по информатике</h3>
				<p class="fs-course-catalog-card__text">Все 27 заданий экзамена, программирование на Python, пробники в формате ЕГЭ и индивидуальные консультации.</p>
				<div class="fs-course-catalog-card__tags">
					<span class="fs-course-catalog-card__tag">Python</span>
					<span class="fs-course-catalog-card__tag">Пробники каждый месяц</span>
					<span class="fs-course-catalog-card__tag">Группы до 8</span>
				</div>
				<div class="fs-course-catalog-card__footer">
					<div class="fs-course-catalog-card__price">
						<span class="fs-course-catalog-card__price-amount">6 400 ₽</span>
						<span class="fs-course-catalog-card__price-note">800 ₽ за час, занятие 2 часа</span>
					</div>
					<div class="fs-course-catalog-card__actions">
						<a class="fs-course-catalog-card__button fs-course-catalog-card__button--ghost" href="<?php echo esc_url( fs_lms_theme_subject_url( 'inf_ege', 'overview' ) ); ?>">Программа</a>
						<a class="fs-course-catalog-card__button fs-course-catalog-card__button--solid" href="#signup">Записаться</a>
					</div>
				</div>
			</div>
		</div>

		<div class="fs-course-catalog-card" data-grade="9">
			<div class="fs-course-catalog-card__media fs-placeholder-tile"></div>
			<div class="fs-course-catalog-card__body">
				<div class="fs-course-catalog-card__meta">
					<span class="fs-course-catalog-card__badge">9 класс</span>
					<span class="fs-course-catalog-card__format">2 раза в неделю</span>
				</div>
				<h3 class="fs-course-catalog-card__title">ОГЭ по информатике</h3>
				<p class="fs-course-catalog-card__text">Полный разбор формата экзамена, практика в реальных программах и работа над ошибками.</p>
				<div class="fs-course-catalog-card__tags">
					<span class="fs-course-catalog-card__tag">Практическая часть</span>
					<span class="fs-course-catalog-card__tag">Работа над ошибками</span>
					<span class="fs-course-catalog-card__tag">Группы до 8</span>
				</div>
				<div class="fs-course-catalog-card__footer">
					<div class="fs-course-catalog-card__price">
						<span class="fs-course-catalog-card__price-amount">6 400 ₽</span>
						<span class="fs-course-catalog-card__price-note">800 ₽ за час, занятие 2 часа</span>
					</div>
					<div class="fs-course-catalog-card__actions">
						<a class="fs-course-catalog-card__button fs-course-catalog-card__button--ghost" href="<?php echo esc_url( fs_lms_theme_subject_url( 'inf_oge', 'overview' ) ); ?>">Программа</a>
						<a class="fs-course-catalog-card__button fs-course-catalog-card__button--solid" href="#signup">Записаться</a>
					</div>
				</div>
			</div>
		</div>

		<div class="fs-course-catalog-card" data-grade="10">
			<div class="fs-course-catalog-card__media fs-placeholder-tile"></div>
			<div class="fs-course-catalog-card__body">
				<div class="fs-course-catalog-card__meta">
					<span class="fs-course-catalog-card__badge">10 класс</span>
					<span class="fs-course-catalog-card__format">2 раза в неделю</span>
				</div>
				<h3 class="fs-course-catalog-card__title">Разработка на Python</h3>
				<p class="fs-course-catalog-card__text">От первой строчки кода до собственного проекта: алгоритмы, данные, боты и небольшие приложения.</p>
				<div class="fs-course-catalog-card__tags">
					<span class="fs-course-catalog-card__tag">Проект в портфолио</span>
					<span class="fs-course-catalog-card__tag">Алгоритмы</span>
					<span class="fs-course-catalog-card__tag">Боты</span>
				</div>
				<div class="fs-course-catalog-card__footer">
					<div class="fs-course-catalog-card__price">
						<span class="fs-course-catalog-card__price-amount">6 400 ₽</span>
						<span class="fs-course-catalog-card__price-note">800 ₽ за час, занятие 2 часа</span>
					</div>
					<div class="fs-course-catalog-card__actions">
						<a class="fs-course-catalog-card__button fs-course-catalog-card__button--ghost" href="<?php echo esc_url( fs_lms_theme_subject_url( 'python', 'overview' ) ); ?>">Программа</a>
						<a class="fs-course-catalog-card__button fs-course-catalog-card__button--solid" href="#signup">Записаться</a>
					</div>
				</div>
			</div>
		</div>

		<div class="fs-course-catalog-card" data-grade="5-8">
			<div class="fs-course-catalog-card__media fs-placeholder-tile"></div>
			<div class="fs-course-catalog-card__body">
				<div class="fs-course-catalog-card__meta">
					<span class="fs-course-catalog-card__badge">5–8 класс</span>
					<span class="fs-course-catalog-card__format">2 раза в неделю</span>
				</div>
				<h3 class="fs-course-catalog-card__title">Робототехника</h3>
				<p class="fs-course-catalog-card__text">Собираем и программируем роботов, разбираем механику и датчики, готовимся к соревнованиям.</p>
				<div class="fs-course-catalog-card__tags">
					<span class="fs-course-catalog-card__tag">Соревнования</span>
					<span class="fs-course-catalog-card__tag">Датчики и механика</span>
					<span class="fs-course-catalog-card__tag">Оборудование школы</span>
				</div>
				<div class="fs-course-catalog-card__footer">
					<div class="fs-course-catalog-card__price">
						<span class="fs-course-catalog-card__price-amount">6 400 ₽</span>
						<span class="fs-course-catalog-card__price-note">800 ₽ за час, занятие 2 часа</span>
					</div>
					<div class="fs-course-catalog-card__actions">
						<a class="fs-course-catalog-card__button fs-course-catalog-card__button--ghost" href="<?php echo esc_url( fs_lms_theme_subject_url( 'robo', 'overview' ) ); ?>">Программа</a>
						<a class="fs-course-catalog-card__button fs-course-catalog-card__button--solid" href="#signup">Записаться</a>
					</div>
				</div>
			</div>
		</div>

	</div>
	<!-- /wp:html -->

</div>
<!-- /wp:group -->
