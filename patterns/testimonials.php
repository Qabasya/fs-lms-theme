<?php
/**
 * Title: Отзывы — сетка карточек отзывов
 * Slug: fs-lms-theme/testimonials
 * Categories: fs-lms-sections
 * Keywords: отзывы, testimonials, рейтинг
 *
 * Не привязано к конкретному блоку мокапа главной (в «Главная — мокап.dc.html»
 * такой секции нет) — паттерн из библиотеки для страниц, где отзывы нужны.
 * Без JS-слайдера на первом проходе — просто сетка (см. tasks.md backlog).
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xxxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxxl);padding-left:var(--wp--preset--spacing--xxl)">
	<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Отзывы учеников и родителей</h2>
	<!-- /wp:heading -->

	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:fs-lms/testimonial-card {"authorName":"Анна Петрова","authorRole":"Родитель, 11 класс","quote":"Сын подтянул информатику за полгода, преподаватели всегда на связи и подробно объясняют домашние задания.","rating":5} -->
			<div class="wp-block-fs-lms-testimonial-card fs-testimonial-card"><div class="fs-testimonial-card__rating" aria-hidden="true">★★★★★</div><span class="screen-reader-text">Оценка 5 из 5</span><p class="fs-testimonial-card__quote">Сын подтянул информатику за полгода, преподаватели всегда на связи и подробно объясняют домашние задания.</p><div class="fs-testimonial-card__author"><div class="fs-testimonial-card__avatar has-accent-700-color has-accent-soft-background-color has-text-color has-background">АП</div><div><div class="fs-testimonial-card__name">Анна Петрова</div><div class="fs-testimonial-card__role">Родитель, 11 класс</div></div></div></div>
			<!-- /wp:fs-lms/testimonial-card -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:fs-lms/testimonial-card {"authorName":"Игорь Соколов","authorRole":"Ученик, 11 класс","quote":"Понравился формат занятий в группе — можно разбирать сложные задачи вместе, а не бояться задавать вопросы.","rating":5} -->
			<div class="wp-block-fs-lms-testimonial-card fs-testimonial-card"><div class="fs-testimonial-card__rating" aria-hidden="true">★★★★★</div><span class="screen-reader-text">Оценка 5 из 5</span><p class="fs-testimonial-card__quote">Понравился формат занятий в группе — можно разбирать сложные задачи вместе, а не бояться задавать вопросы.</p><div class="fs-testimonial-card__author"><div class="fs-testimonial-card__avatar has-accent-700-color has-accent-soft-background-color has-text-color has-background">ИС</div><div><div class="fs-testimonial-card__name">Игорь Соколов</div><div class="fs-testimonial-card__role">Ученик, 11 класс</div></div></div></div>
			<!-- /wp:fs-lms/testimonial-card -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:fs-lms/testimonial-card {"authorName":"Мария Волкова","authorRole":"Родитель, 9 класс","quote":"Записи занятий сильно выручают — дочь пересматривает разборы перед пробниками и точно знает, что подтянуть.","rating":4} -->
			<div class="wp-block-fs-lms-testimonial-card fs-testimonial-card"><div class="fs-testimonial-card__rating" aria-hidden="true">★★★★☆</div><span class="screen-reader-text">Оценка 4 из 5</span><p class="fs-testimonial-card__quote">Записи занятий сильно выручают — дочь пересматривает разборы перед пробниками и точно знает, что подтянуть.</p><div class="fs-testimonial-card__author"><div class="fs-testimonial-card__avatar has-accent-700-color has-accent-soft-background-color has-text-color has-background">МВ</div><div><div class="fs-testimonial-card__name">Мария Волкова</div><div class="fs-testimonial-card__role">Родитель, 9 класс</div></div></div></div>
			<!-- /wp:fs-lms/testimonial-card -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
