<?php
/**
 * Title: Контакты — текст + витрина заявки на пробное занятие
 * Slug: fs-lms-theme/contact-section
 * Categories: fs-lms-sections
 * Keywords: контакты, заявка, форма, contact
 *
 * Источник: блок «форма» в «Главная v4 - сборка.dc.html» (Фаза 12.8,
 * `id="signup"` — якорь из hero/курсов/блока занятий, решение 5 Фазы 12).
 * Форма — по-прежнему без реальной отправки (Фаза 14, отдельная задача):
 * поля для визуального соответствия макету, `<form>` без `action`/`method`
 * — раньше вела на страницу заявки плагина (`fs_lms_theme_url('apply')`),
 * теперь эта ссылка не используется с главной вовсе (решение 5).
 *
 * Фаза 12.8: набор полей синхронизирован с v4 — «Имя родителя» + Телефон +
 * Класс (100px) + Направление (`<select>`, в паре с Классом) — поле «ФИО
 * ребёнка» (было в Фазе 11) убрано, в v4 его нет.
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
			<form class="fs-apply-form" id="apply-form">
				<div class="fs-apply-form__title">Записаться на пробное занятие</div>

				<div class="fs-form-field">
					<label for="fs-apply-name">Имя родителя</label>
					<input type="text" id="fs-apply-name" name="parent_name" placeholder="Анна" autocomplete="name">
				</div>

				<div class="fs-form-field">
					<label for="fs-apply-phone">Телефон</label>
					<input type="tel" id="fs-apply-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel">
				</div>

				<div class="fs-form-row" style="grid-template-columns:100px 1fr">
					<div class="fs-form-field">
						<label for="fs-apply-grade">Класс</label>
						<select id="fs-apply-grade" name="grade">
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
							<option selected>11</option>
						</select>
					</div>

					<div class="fs-form-field">
						<label for="fs-apply-subject">Направление</label>
						<select id="fs-apply-subject" name="subject">
							<option>ЕГЭ по информатике</option>
							<option>ОГЭ по информатике</option>
							<option>Разработка на Python</option>
							<option>Робототехника</option>
						</select>
					</div>
				</div>

				<button type="submit" class="fs-apply-form__submit">Отправить заявку</button>
				<div class="fs-apply-form__note">Перезвоним в течение рабочего дня. Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности.</div>
			</form>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
