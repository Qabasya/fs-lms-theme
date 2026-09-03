<?php
/**
 * Title: Курсы — форма записи
 * Slug: fs-lms-theme/courses-contact
 * Categories: fs-lms-sections
 * Keywords: контакты, заявка, форма, курсы, contact
 *
 * Фаза 16.5 (источник дизайна — `Курсы - мокап.dc.html`). Копия
 * `contact-section.php` (Фаза 12.8), НЕ переиспользована как есть —
 * свой текст описания под заголовком («Не знаете, какое направление
 * подойдёт?..»), не текст главной.
 *
 * Поля «Класс»/«Направление» из макета этой страницы (и вообще из всех
 * форм записи темы) убраны по прямому указанию пользователя 2026-09-03
 * («и в форме записи убери везде и дальше тоже поля класс и
 * направление») — решение перекрывает более раннюю правку BugFix
 * (tasks.md), которая убирала эти поля только из `contact-section.php`
 * на главной; теперь их не должно быть ни в одной форме записи темы, и
 * новые формы их тоже не заводят. Форма — «Имя родителя» + Телефон,
 * как в текущей `contact-section.php`.
 *
 * `id="signup"` — тот же якорь, на который ссылаются кнопки «Записаться»
 * карточек `courses-catalog.php` этой же страницы.
 *
 * Фаза 14: реальная отправка — `data-fs-form` + honeypot/HMAC-таймер/капча
 * (если настроена), `src/js/forms.js` перехватывает `submit` и шлёт AJAX
 * на `inc/Forms.php` — без изменений, тот же обработчик.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"4.5rem","bottom":"0"}}}} -->
<div id="signup" class="wp-block-group" style="padding-top:4.5rem;padding-bottom:0">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"fontSize":"xxl"} -->
			<h2 class="wp-block-heading has-xxl-font-size">Приготовься сделать<br><span style="color:var(--wp--preset--color--accent)">Шаг в будущее</span></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Не знаете, какое направление подойдёт? Заполните форму — подскажем уровень и группу. Первое занятие бесплатно.</p>
			<!-- /wp:paragraph -->

			<!-- wp:html -->
			<div class="fs-contact-list">
				<div class="fs-contact-list__item"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4.5 3.5h3l1.2 3-1.7 1.3a10 10 0 0 0 4.2 4.2l1.3-1.7 3 1.2v3a1 1 0 0 1-1.1 1C8.6 15 5 11.4 3.5 4.6a1 1 0 0 1 1-1.1z" stroke="#3b5bdb" stroke-width="1.6" stroke-linejoin="round"/></svg><a href="tel:+79953264486">+7 995 326 44 86</a></div>
				<div class="fs-contact-list__item"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="2.5" y="4.5" width="15" height="11" rx="2" stroke="#3b5bdb" stroke-width="1.6"/><path d="m3 6 7 5 7-5" stroke="#3b5bdb" stroke-width="1.6" stroke-linejoin="round"/></svg><a href="mailto:info@future-step.ru">info@future-step.ru</a></div>
				<div class="fs-contact-list__item"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 17.5s5.5-4.9 5.5-9a5.5 5.5 0 1 0-11 0c0 4.1 5.5 9 5.5 9z" stroke="#3b5bdb" stroke-width="1.6" stroke-linejoin="round"/><circle cx="10" cy="8.5" r="2" stroke="#3b5bdb" stroke-width="1.6"/></svg><span>Калининград, ул. Черняховского, 6, каб. 316</span></div>
			</div>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"480px"} -->
		<div class="wp-block-column" style="flex-basis:480px">
			<!-- wp:html -->
			<form class="fs-apply-form" id="courses-apply-form" data-fs-form>
				<div class="fs-apply-form__title">Записаться на пробное занятие</div>

				<div class="fs-form-field">
					<label for="fs-courses-apply-name">Имя родителя</label>
					<input type="text" id="fs-courses-apply-name" name="parent_name" placeholder="Анна" autocomplete="name" required>
				</div>

				<div class="fs-form-field">
					<label for="fs-courses-apply-phone">Телефон</label>
					<input type="tel" id="fs-courses-apply-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
				</div>

				<input type="hidden" name="form_id" value="courses-signup">
				<input type="hidden" name="fs_form_token" value="<?php echo esc_attr( fs_lms_theme_form_timestamp_token() ); ?>">
				<input type="text" name="<?php echo esc_attr( fs_lms_theme_honeypot_field() ); ?>" class="fs-form-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
				<?php if ( function_exists( 'fs_lms_theme_captcha_configured' ) && fs_lms_theme_captcha_configured() ) : ?>
				<div class="smart-captcha" data-sitekey="<?php echo esc_attr( get_option( 'fs_lms_theme_captcha_site_key', '' ) ); ?>"></div>
				<?php endif; ?>
				<button type="submit" class="fs-apply-form__submit">Отправить заявку</button>
				<div class="fs-apply-form__note">Перезвоним в течение рабочего дня. Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности.</div>
				<div class="fs-form-message" role="status"></div>
			</form>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
