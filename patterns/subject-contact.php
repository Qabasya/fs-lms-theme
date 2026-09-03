<?php
/**
 * Title: Контакты — форма записи страницы направления (без выбора предмета)
 * Slug: fs-lms-theme/subject-contact
 * Categories: fs-lms-sections
 * Keywords: контакты, заявка, форма, направление, contact
 *
 * Фаза 13.0, решение 5: та же секция, что `patterns/contact-section.php`
 * (Фаза 12.8), но без поля «Направление» — на странице направления оно
 * избыточно, предмет и так зафиксирован контекстом страницы. Класс формы
 * `.fs-apply-form`/`#signup` — общий с главной, стили не дублируются.
 *
 * Фаза 14: реальная отправка — `data-fs-form` + honeypot/HMAC-таймер/капча
 * (если настроена), `src/js/forms.js` перехватывает `submit` и шлёт AJAX.
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div id="signup" class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"fontSize":"xxl"} -->
			<h2 class="wp-block-heading has-xxl-font-size">Приготовься сделать<br><span style="color:var(--wp--preset--color--accent)">Шаг в будущее</span></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Заполни форму и мы ответим на все интересующие вопросы. Первое занятие — бесплатно.</p>
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
			<form class="fs-apply-form" id="apply-form" data-fs-form>
				<div class="fs-apply-form__title">Записаться на пробное занятие</div>

				<div class="fs-form-field">
					<label for="fs-subject-apply-name">Имя родителя</label>
					<input type="text" id="fs-subject-apply-name" name="parent_name" placeholder="Анна" autocomplete="name" required>
				</div>

				<div class="fs-form-field">
					<label for="fs-subject-apply-phone">Телефон</label>
					<input type="tel" id="fs-subject-apply-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
				</div>

				<div class="fs-form-field" style="max-width:100px">
					<label for="fs-subject-apply-grade">Класс</label>
					<select id="fs-subject-apply-grade" name="grade">
						<option>5</option>
						<option>6</option>
						<option>7</option>
						<option>8</option>
						<option>9</option>
						<option>10</option>
						<option selected>11</option>
					</select>
				</div>

				<input type="hidden" name="form_id" value="signup">
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
