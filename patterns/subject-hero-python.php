<?php
/**
 * Title: Hero страницы направления — Разработка на Python
 * Slug: fs-lms-theme/subject-hero-python
 * Categories: fs-lms-sections
 * Keywords: hero, направление, python, предмет, форма
 *
 * Копия `patterns/subject-hero.php` (ЕГЭ-вариант, Фаза 13.0) под
 * направление «Разработка на Python» — тот же визуальный шаблон, свой
 * бейдж/заголовок/описание и модификатор цвета `--python` (BugFix.7,
 * сопоставление цвет↔направление — то же, что бейджи `courses-grid.php`,
 * BugFix.6: Python = зелёный). Текст — черновик, сгенерирован по образцу
 * остальных описаний направлений в теме (`courses-grid.php`), редактор
 * правит вручную после вставки на страницу предмета (`python`).
 *
 * Правая колонка (мини-форма `#hero-form` + плашка из 3 статов) — тот же
 * `wp:html`, что и в `hero.php`/`subject-hero.php`, без изменений.
 */
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:columns {"className":"fs-hero-columns","style":{"spacing":{"blockGap":{"left":"2.5rem"}}}} -->
	<div class="wp-block-columns fs-hero-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-subject-hero-box fs-subject-hero-box--python","layout":{"type":"constrained"}} -->
			<div class="wp-block-group fs-subject-hero-box fs-subject-hero-box--python">
				<!-- wp:paragraph {"className":"fs-subject-hero-box__badge"} -->
				<p class="fs-subject-hero-box__badge">10 класс</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":1,"fontSize":"xxxl"} -->
				<h1 class="wp-block-heading has-xxxl-font-size">Разработка на Python</h1>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"fontSize":"lead"} -->
				<p class="has-lead-font-size">От основ синтаксиса до своего проекта: алгоритмы, структуры данных, боты и мини-приложения.</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
				<p class="has-text-secondary-color has-text-color has-md-font-size">Каждая тема закрепляется практикой на реальных задачах, а не абстрактными примерами — к концу курса у ученика есть законченный проект в портфолио и уверенное владение языком для дальнейшего обучения.</p>
				<!-- /wp:paragraph -->
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
						<label for="fs-subject-hero-name">ФИО родителя</label>
						<input type="text" id="fs-subject-hero-name" name="parent_name" placeholder="Иванова Анна Ивановна" autocomplete="name" pattern="[А-Яа-яЁё\s\-]{2,80}" minlength="2" maxlength="80" title="Только буквы кириллицы, пробелы и дефис" required>
					</div>
					<div class="fs-form-field">
						<label for="fs-subject-hero-phone">Телефон</label>
						<input type="tel" id="fs-subject-hero-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
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

			<!-- wp:group {"className":"fs-hero-stats"} -->
			<div class="wp-block-group fs-hero-stats">
				<!-- wp:group {"className":"fs-hero-stats__item"} -->
				<div class="wp-block-group fs-hero-stats__item">
					<!-- wp:paragraph {"className":"fs-hero-stats__value"} -->
					<p class="fs-hero-stats__value">84</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-hero-stats__label"} -->
					<p class="fs-hero-stats__label">средний балл</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"fs-hero-stats__item"} -->
				<div class="wp-block-group fs-hero-stats__item">
					<!-- wp:paragraph {"className":"fs-hero-stats__value"} -->
					<p class="fs-hero-stats__value">800 ₽</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-hero-stats__label"} -->
					<p class="fs-hero-stats__label">час занятий</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"fs-hero-stats__item"} -->
				<div class="wp-block-group fs-hero-stats__item">
					<!-- wp:paragraph {"className":"fs-hero-stats__value"} -->
					<p class="fs-hero-stats__value">до 8</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-hero-stats__label"} -->
					<p class="fs-hero-stats__label">человек в группе</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
