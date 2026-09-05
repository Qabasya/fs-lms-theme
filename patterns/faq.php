<?php
/**
 * Title: FAQ — заголовок + список вопросов
 * Slug: fs-lms-theme/faq
 * Categories: fs-lms-sections
 * Keywords: faq, вопросы, ответы
 *
 * BugFix.6 (2026-09-05): секция приведена к последнему блоку макета
 * `Главная v4 - сборка.dc.html` (Design MCP) и подключена последней секцией
 * главной (`templates/front-page.html`). Изменилось три вещи:
 *
 * 1. Вид — «связанный», как аккордеон страницы «О нас» (по указанию
 *    пользователя): одна рамка со скруглением на весь список, пункты
 *    разделены внутренней чертой. Даёт его класс-модификатор
 *    `.fs-faq-list` на обёртке (`theme.scss`); сами пункты остаются
 *    блоками `fs-lms/faq-item`, то есть правятся в редакторе — в отличие
 *    от статичной разметки `.fs-legal-accordion` на `/about/`.
 * 2. Ширина — как у остальных секций главной, а не 640px: в макете
 *    аккордеон занимает всю колонку контента.
 * 3. Вопросы и ответы — из макета (5 штук вместо прежних 4).
 *
 * Плавное раскрытие — общее для всех аккордеонов темы, см. BugFix.7
 * в `theme.scss`.
 */
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Частые вопросы</h2>
	<!-- /wp:heading -->

	<!-- wp:group {"className":"fs-faq-list"} -->
	<div class="wp-block-group fs-faq-list">
		<!-- wp:fs-lms/faq-item {} -->
		<details class="wp-block-fs-lms-faq-item fs-faq-item"><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">Как проходят пробные занятия?</span></summary><p class="fs-faq-item__answer">Бесплатное пробное занятие проходит в мини-группе или индивидуально — записаться можно через форму на сайте, мы перезвоним и подберём удобное время.</p></details>
		<!-- /wp:fs-lms/faq-item -->

		<!-- wp:fs-lms/faq-item {} -->
		<details class="wp-block-fs-lms-faq-item fs-faq-item"><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">Что если ребёнок пропустит занятие?</span></summary><p class="fs-faq-item__answer">Каждое занятие записывается, запись доступна в личном кабинете — можно посмотреть материал и получить консультацию преподавателя по пропущенной теме.</p></details>
		<!-- /wp:fs-lms/faq-item -->

		<!-- wp:fs-lms/faq-item {} -->
		<details class="wp-block-fs-lms-faq-item fs-faq-item"><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">Нужна ли начальная подготовка?</span></summary><p class="fs-faq-item__answer">Нет, набор идёт с любого уровня — программу подбираем по итогам пробного занятия и результатам входного тестирования.</p></details>
		<!-- /wp:fs-lms/faq-item -->

		<!-- wp:fs-lms/faq-item {} -->
		<details class="wp-block-fs-lms-faq-item fs-faq-item"><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">Как оплачивать занятия?</span></summary><p class="fs-faq-item__answer">Оплата помесячная, картой онлайн или в личном кабинете. Стоимость и способы оплаты указаны на странице курса.</p></details>
		<!-- /wp:fs-lms/faq-item -->

		<!-- wp:fs-lms/faq-item {} -->
		<details class="wp-block-fs-lms-faq-item fs-faq-item"><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">Можно ли перейти на другое направление?</span></summary><p class="fs-faq-item__answer">Да, перевод между направлениями и группами возможен в любой момент — обсудите это с преподавателем или администратором школы.</p></details>
		<!-- /wp:fs-lms/faq-item -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
