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
 * см. tasks.md 12.2), правая — карточка формы `id="hero-form"` и плашка
 * из 3 фактов.
 *
 * `hero-split.php` не удалён — остаётся в библиотеке паттернов на случай,
 * если понадобится для другой страницы; из `front-page.html` исключён.
 *
 * Фаза 14: `#hero-form` — реальная форма (`<form data-fs-form>`, не
 * `<div>`), с honeypot/HMAC-таймером/капчей (если настроена) — их кладёт
 * сюда PHP (`inc/Forms.php`) при рендере паттерна, `src/js/forms.js`
 * перехватывает `submit` и шлёт AJAX. Кнопка — `<button type="submit">`
 * вместо якоря `<a href="#hero-form">`.
 *
 * BugFix.1 (2026-09-03): поля формы приведены к тому же варианту, что
 * `subject-contact.php`/`courses-contact.php` — `<label>` над каждым
 * полем, плейсхолдеры «Анна»/«+7 (___) ___-__-__» вместо «ФИО»/«Телефон»
 * (горизонтальная сетка `.fs-hero-form__row` сохранена). Класс
 * `fs-hero-columns` на `wp:columns` — растягивает `.fs-hero-form` +
 * `.fs-hero-stats` на всю высоту левой колонки (см. `theme.scss`).
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"4.5rem","bottom":"0"}}}} -->
<div class="wp-block-group" style="padding-top:4.5rem;padding-bottom:0">
	<!-- wp:columns {"className":"fs-hero-columns","style":{"spacing":{"blockGap":{"left":"2.5rem"}}}} -->
	<div class="wp-block-columns fs-hero-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:html -->
			<div class="fs-hero-dirs">
				<h2 class="fs-hero-dirs__title">Направления<br>подготовки</h2>
				<a href="<?php echo esc_url( fs_lms_theme_subject_url( 'inf_ege', 'overview' ) ); ?>" class="fs-hero-dirs__item">
					<span class="fs-hero-dirs__arrow">→</span>
					<span class="fs-hero-dirs__name">ЕГЭ по информатике</span>
					<span class="fs-hero-dirs__grade">11 класс</span>
				</a>
				<a href="<?php echo esc_url( fs_lms_theme_subject_url( 'inf_oge', 'overview' ) ); ?>" class="fs-hero-dirs__item">
					<span class="fs-hero-dirs__arrow">→</span>
					<span class="fs-hero-dirs__name">ОГЭ по информатике</span>
					<span class="fs-hero-dirs__grade">9 класс</span>
				</a>
				<a href="<?php echo esc_url( fs_lms_theme_subject_url( 'python', 'overview' ) ); ?>" class="fs-hero-dirs__item">
					<span class="fs-hero-dirs__arrow">→</span>
					<span class="fs-hero-dirs__name">Разработка на Python</span>
					<span class="fs-hero-dirs__grade">10 класс</span>
				</a>
				<a href="<?php echo esc_url( fs_lms_theme_subject_url( 'robo', 'overview' ) ); ?>" class="fs-hero-dirs__item fs-hero-dirs__item--last">
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
			<form id="hero-form" class="fs-hero-form" data-fs-form>
				<div class="fs-hero-form__title">Запишитесь на пробное занятие</div>
				<div class="fs-hero-form__row">
					<div class="fs-form-field">
						<label for="fs-hero-name">Имя родителя</label>
						<input type="text" id="fs-hero-name" name="parent_name" placeholder="Анна" autocomplete="name" required>
					</div>
					<div class="fs-form-field">
						<label for="fs-hero-phone">Телефон</label>
						<input type="tel" id="fs-hero-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
					</div>
				</div>
				<input type="hidden" name="form_id" value="hero">
				<input type="hidden" name="fs_form_token" value="<?php echo esc_attr( fs_lms_theme_form_timestamp_token() ); ?>">
				<input type="text" name="<?php echo esc_attr( fs_lms_theme_honeypot_field() ); ?>" class="fs-form-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
				<?php if ( function_exists( 'fs_lms_theme_captcha_configured' ) && fs_lms_theme_captcha_configured() ) : ?>
				<div class="smart-captcha" data-sitekey="<?php echo esc_attr( get_option( 'fs_lms_theme_captcha_site_key', '' ) ); ?>"></div>
				<?php endif; ?>
				<button type="submit" class="fs-hero-form__submit">Отправить</button>
				<div class="fs-hero-form__note">Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности</div>
				<div class="fs-form-message" role="status"></div>
			</form>
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
