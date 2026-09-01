<?php
/**
 * Title: FAQ — заголовок + список вопросов
 * Slug: fs-lms-theme/faq
 * Categories: fs-lms-sections
 * Keywords: faq, вопросы, ответы
 *
 * Не привязано к конкретному блоку мокапа главной (в «Главная — мокап.dc.html»
 * такой секции нет) — паттерн из библиотеки для страниц, где нужен блок
 * частых вопросов.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"var:preset|spacing|xxxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxxl);padding-left:var(--wp--preset--spacing--xxl)">
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"640px"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
		<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Частые вопросы</h2>
		<!-- /wp:heading -->

		<!-- wp:fs-lms/faq-item {"question":"Сколько человек в группе?","answer":"До 8 человек — так у преподавателя остаётся время разобрать вопросы каждого ученика."} -->
		<details class="wp-block-fs-lms-faq-item fs-faq-item"><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">Сколько человек в группе?</span></summary><p class="fs-faq-item__answer">До 8 человек — так у преподавателя остаётся время разобрать вопросы каждого ученика.</p></details>
		<!-- /wp:fs-lms/faq-item -->

		<!-- wp:fs-lms/faq-item {"question":"Что если пропущу занятие?","answer":"Каждое занятие записывается — можно посмотреть в записи и прорешать материал самостоятельно с поддержкой преподавателя."} -->
		<details class="wp-block-fs-lms-faq-item fs-faq-item"><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">Что если пропущу занятие?</span></summary><p class="fs-faq-item__answer">Каждое занятие записывается — можно посмотреть в записи и прорешать материал самостоятельно с поддержкой преподавателя.</p></details>
		<!-- /wp:fs-lms/faq-item -->

		<!-- wp:fs-lms/faq-item {"question":"Как проходит запись на пробное занятие?","answer":"Заполни форму обратной связи на сайте — мы перезвоним в течение рабочего дня и подберём удобное время."} -->
		<details class="wp-block-fs-lms-faq-item fs-faq-item"><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">Как проходит запись на пробное занятие?</span></summary><p class="fs-faq-item__answer">Заполни форму обратной связи на сайте — мы перезвоним в течение рабочего дня и подберём удобное время.</p></details>
		<!-- /wp:fs-lms/faq-item -->

		<!-- wp:fs-lms/faq-item {"question":"Сколько стоит занятие?","answer":"800 ₽ в час при абонементе на месяц — дешевле, чем у индивидуального репетитора."} -->
		<details class="wp-block-fs-lms-faq-item fs-faq-item"><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">Сколько стоит занятие?</span></summary><p class="fs-faq-item__answer">800 ₽ в час при абонементе на месяц — дешевле, чем у индивидуального репетитора.</p></details>
		<!-- /wp:fs-lms/faq-item -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
