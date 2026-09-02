<?php
/**
 * Title: Hero — направления + форма записи
 * Slug: fs-lms-theme/hero
 * Categories: fs-lms-sections
 * Keywords: hero, обложка, направления, форма
 *
 * Фаза 12 (`Главная v4 - сборка.dc.html`): полная замена первого экрана —
 * заменяет `hero-split.php` (Фаза 11, код-карточка `main.py`) в
 * `templates/front-page.html`. Левая колонка — список 4 направлений
 * (простые ссылки, не `fs-lms/course-card` — другая разметка и контент,
 * см. tasks.md 12.2), правая — карточка формы `id="hero-form"` (поля ФИО +
 * Телефон, без реальной отправки — Фаза 14) и плашка из 3 фактов.
 *
 * `hero-split.php` не удалён — остаётся в библиотеке паттернов на случай,
 * если понадобится для другой страницы; из `front-page.html` исключён.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"4.5rem","bottom":"0"}}}} -->
<div class="wp-block-group" style="padding-top:4.5rem;padding-bottom:0">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.5rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:html -->
			<div class="fs-hero-dirs">
				<h2 class="fs-hero-dirs__title">Направления<br>подготовки</h2>
				<a href="#dirs" class="fs-hero-dirs__item">
					<span class="fs-hero-dirs__arrow">→</span>
					<span class="fs-hero-dirs__name">ЕГЭ по информатике</span>
					<span class="fs-hero-dirs__grade">11 класс</span>
				</a>
				<a href="#dirs" class="fs-hero-dirs__item">
					<span class="fs-hero-dirs__arrow">→</span>
					<span class="fs-hero-dirs__name">ОГЭ по информатике</span>
					<span class="fs-hero-dirs__grade">9 класс</span>
				</a>
				<a href="#dirs" class="fs-hero-dirs__item">
					<span class="fs-hero-dirs__arrow">→</span>
					<span class="fs-hero-dirs__name">Разработка на Python</span>
					<span class="fs-hero-dirs__grade">10 класс</span>
				</a>
				<a href="#dirs" class="fs-hero-dirs__item fs-hero-dirs__item--last">
					<span class="fs-hero-dirs__arrow">→</span>
					<span class="fs-hero-dirs__name">Робототехника</span>
					<span class="fs-hero-dirs__grade">5–8 класс</span>
				</a>
			</div>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:html -->
			<div id="hero-form" class="fs-hero-form">
				<div class="fs-hero-form__title">Запишитесь на пробное занятие</div>
				<div class="fs-hero-form__row">
					<input type="text" name="parent_name" placeholder="ФИО" autocomplete="name">
					<input type="tel" name="phone" placeholder="Телефон" autocomplete="tel">
				</div>
				<a href="#hero-form" class="fs-hero-form__submit">Отправить</a>
				<div class="fs-hero-form__note">Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности</div>
			</div>
			<!-- /wp:html -->

			<!-- wp:html -->
			<div class="fs-hero-stats">
				<div class="fs-hero-stats__item"><div class="fs-hero-stats__value">84</div><div class="fs-hero-stats__label">средний балл</div></div>
				<div class="fs-hero-stats__item"><div class="fs-hero-stats__value">800 ₽</div><div class="fs-hero-stats__label">час занятий</div></div>
				<div class="fs-hero-stats__item"><div class="fs-hero-stats__value">до 8</div><div class="fs-hero-stats__label">человек в группе</div></div>
			</div>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
