<?php
/**
 * Title: Контакты — текст + витрина заявки на пробное занятие
 * Slug: fs-lms-theme/contact-section
 * Categories: fs-lms-sections
 * Keywords: контакты, заявка, форма, contact
 *
 * Источник: блок «форма» в «Главная v4 - сборка.dc.html» (Фаза 12.8,
 * `id="signup"` — якорь из hero/курсов/блока занятий, решение 5 Фазы 12).
 *
 * Фаза 12.8: набор полей синхронизирован с v4 — «Имя родителя» + Телефон +
 * Класс (100px) + Направление (`<select>`, в паре с Классом) — поле «ФИО
 * ребёнка» (было в Фазе 11) убрано, в v4 его нет.
 *
 * BugFix (tasks.md): поля «Класс»/«Направление» убраны из этой формы —
 * форма осталась с «Имя родителя» + Телефон. `grade`/`subject`
 * по-прежнему опциональны на бэкенде (`inc/Forms.php`,
 * `fs_lms_theme_handle_form_submit()`) — просто не придут в `$_POST`,
 * письмо соберётся без строк «Класс:»/«Направление:». `.fs-form-row`
 * (grid-обёртка для пары полей) удалена из `theme.scss` — использовалась
 * только этими двумя полями.
 *
 * Фаза 14: реальная отправка — `data-fs-form` + honeypot/HMAC-таймер/капча
 * (если настроена в Настройки → Формы), `src/js/forms.js` перехватывает
 * `submit` и шлёт AJAX на `inc/Forms.php`, без `action`/редиректа.
 */
?>
<!-- wp:group {"className":"fs-section"} -->
<div id="signup" class="wp-block-group fs-section">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"fontSize":"xxl"} -->
			<h2 class="wp-block-heading has-xxl-font-size">Приготовься сделать<br><span style="color:var(--wp--preset--color--accent)">Шаг в будущее</span></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Заполните форму и мы ответим на все интересующие вопросы.<br>Первое занятие — бесплатно.<br><br>Контакты для связи:</p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"className":"fs-contact-list"} -->
			<div class="wp-block-group fs-contact-list">
				<!-- wp:paragraph {"className":"fs-contact-list__item fs-contact-list__item--phone"} -->
				<p class="fs-contact-list__item fs-contact-list__item--phone"><a href="tel:+79953264486">+7 995 326 44 86</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-contact-list__item fs-contact-list__item--mail"} -->
				<p class="fs-contact-list__item fs-contact-list__item--mail"><a href="mailto:info@future-step.ru">info@future-step.ru</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-contact-list__item fs-contact-list__item--pin"} -->
				<p class="fs-contact-list__item fs-contact-list__item--pin">Калининград, ул. Черняховского, 6, каб. 316</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"480px"} -->
		<div class="wp-block-column" style="flex-basis:480px">
			<!-- wp:html -->
			<form class="fs-apply-form" id="apply-form" data-fs-form>
				<div class="fs-apply-form__title">Записаться на пробное занятие</div>

				<div class="fs-form-field">
					<label for="fs-apply-name">ФИО родителя</label>
					<input type="text" id="fs-apply-name" name="parent_name" placeholder="Иванова Анна Ивановна" autocomplete="name" <?php echo fs_lms_theme_name_field_attrs_html(); ?> required>
				</div>

				<div class="fs-form-field">
					<label for="fs-apply-phone">Телефон</label>
					<input type="tel" id="fs-apply-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
				</div>

				<input type="hidden" name="form_id" value="signup">
				<input type="hidden" name="fs_form_token" value="<?php echo esc_attr( fs_lms_theme_form_timestamp_token() ); ?>">
				<input type="text" name="<?php echo esc_attr( fs_lms_theme_honeypot_field() ); ?>" class="fs-form-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
				<?php echo fs_lms_theme_captcha_slot_html(); ?>
				<button type="submit" class="fs-apply-form__submit">Отправить заявку</button>
				<div class="fs-apply-form__note">Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности.</div>
				<div class="fs-form-message" role="status"></div>
			</form>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
