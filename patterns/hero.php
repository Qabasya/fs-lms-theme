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
 *
 * Этап 3 (2026-09-13): пункты списка направлений — из записей «Направления»
 * в админке (`inc/Showcase/Directions.php`): название, класс, ссылка на
 * страницу предмета. Факты под формой — «Настройки сайта» (этап 4,
 * `inc/Showcase/Site_Settings.php`).
 */
?>
<!-- wp:group {"className":"fs-section-lead"} -->
<div class="wp-block-group fs-section-lead">
	<!-- wp:columns {"className":"fs-hero-columns","style":{"spacing":{"blockGap":{"left":"2.5rem"}}}} -->
	<div class="wp-block-columns fs-hero-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-hero-dirs"} -->
			<div class="wp-block-group fs-hero-dirs">
				<!-- wp:heading {"className":"fs-hero-dirs__title"} -->
				<h2 class="wp-block-heading fs-hero-dirs__title">Направления подготовки</h2>
				<!-- /wp:heading -->

<?php echo FS_LMS_Theme_Showcase::directions()->hero_list_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- пункты собраны с экранированием в FS_LMS_Theme_Directions::hero_list_markup(). ?>
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:html -->
			<form id="hero-form" class="fs-hero-form" data-fs-form>
				<div class="fs-hero-form__title">Запишитесь на пробное занятие</div>
				<div class="fs-hero-form__row">
					<div class="fs-form-field">
						<label for="fs-hero-name">ФИО родителя</label>
						<input type="text" id="fs-hero-name" name="parent_name" placeholder="Иванова Анна Ивановна" autocomplete="name" <?php echo fs_lms_theme_name_field_attrs_html(); ?> required>
					</div>
					<div class="fs-form-field">
						<label for="fs-hero-phone">Телефон</label>
						<input type="tel" id="fs-hero-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
					</div>
				</div>
				<input type="hidden" name="form_id" value="hero">
				<input type="hidden" name="fs_form_token" value="<?php echo esc_attr( fs_lms_theme_form_timestamp_token() ); ?>">
				<input type="text" name="<?php echo esc_attr( fs_lms_theme_honeypot_field() ); ?>" class="fs-form-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
				<?php echo fs_lms_theme_captcha_slot_html(); ?>
				<button type="submit" class="fs-hero-form__submit">Отправить</button>
				<div class="fs-hero-form__note"><?php echo fs_lms_theme_form_consent_html(); ?></div>
				<div class="fs-form-message" role="status"></div>
			</form>
			<!-- /wp:html -->

<?php $fs_hero_stats = FS_LMS_Theme_Showcase::settings()->hero_stats(); ?>
<?php if ( array() !== $fs_hero_stats ) : ?>
			<!-- wp:group {"className":"fs-hero-stats"} -->
			<div class="wp-block-group fs-hero-stats">
<?php foreach ( $fs_hero_stats as $fs_stat ) : ?>
				<!-- wp:group {"className":"fs-hero-stats__item"} -->
				<div class="wp-block-group fs-hero-stats__item">
					<!-- wp:paragraph {"className":"fs-hero-stats__value"} -->
					<p class="fs-hero-stats__value"><?php echo esc_html( $fs_stat[0] ); ?></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-hero-stats__label"} -->
					<p class="fs-hero-stats__label"><?php echo esc_html( $fs_stat[1] ); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
<?php endforeach; ?>
			</div>
			<!-- /wp:group -->
<?php endif; ?>
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
