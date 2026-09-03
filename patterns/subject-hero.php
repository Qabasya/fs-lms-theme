<?php
/**
 * Title: Hero страницы направления — инфобокс + мини-форма записи
 * Slug: fs-lms-theme/subject-hero
 * Categories: fs-lms-sections
 * Keywords: hero, направление, предмет, форма
 *
 * Фаза 13.0 (источник дизайна — `ЕГЭ информатика - мокап.dc.html`, Claude
 * Design проект той же Фазы 12 — файл недоступен локально в этой сессии,
 * только по ссылке на облачный проект; текст ниже приближен к нему по
 * данным, уже согласованным в теме — homepage `patterns/hero.php`
 * (факты 84/800 ₽/до 8) и карточка «ЕГЭ по информатике» в
 * `patterns/courses-grid.php`, а не выдуман заново). Один визуальный
 * шаблон переиспользуется под все 4 направления (решение Фазы 13) —
 * при вставке на страницу конкретного предмета редактор правит бейдж
 * класса/заголовок/описание вручную в Gutenberg-блоках (это НЕ статичный
 * `wp:html`, в отличие от списка направлений в `hero.php` на главной —
 * там переиспользуемый список ссылок, здесь — уникальный на каждую
 * страницу текст, ему нужны настоящие редактируемые блоки).
 *
 * Правая колонка (мини-форма `#hero-form` + плашка из 3 статов) — тот же
 * `wp:html`, что и в `hero.php` (разметка идентична дизайн-системе, менять
 * нечего), только без списка направлений слева — вместо него инфобокс
 * предмета.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"4.5rem","bottom":"0"}}}} -->
<div class="wp-block-group" style="padding-top:4.5rem;padding-bottom:0">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.5rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-subject-hero-box","layout":{"type":"constrained"}} -->
			<div class="wp-block-group fs-subject-hero-box">
				<!-- wp:paragraph {"className":"fs-subject-hero-box__badge"} -->
				<p class="fs-subject-hero-box__badge">11 класс</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":1,"fontSize":"xxxl"} -->
				<h1 class="wp-block-heading has-xxxl-font-size">ЕГЭ по информатике</h1>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"fontSize":"lead"} -->
				<p class="has-lead-font-size">Все задания экзамена, программирование на Python и индивидуальные консультации с репетитором.</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
				<p class="has-text-secondary-color has-text-color has-md-font-size">Разбираем каждый тип задания от простого к сложному, регулярно решаем полные варианты в форме реального экзамена и разбираем ошибки — к июню ученик подходит с уверенным пониманием формата, а не только теории.</p>
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
					<input type="text" name="parent_name" placeholder="ФИО" autocomplete="name" required>
					<input type="tel" name="phone" placeholder="Телефон" autocomplete="tel" required>
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
