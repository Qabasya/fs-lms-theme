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
 * BugFix (2026-09-03): поле «Класс» тоже убрано — по прямому указанию
 * пользователя убрать «Класс»/«Направление» из форм записи везде, не
 * только на главной (см. `contact-section.php`/`courses-contact.php`).
 * После этого набор полей совпадает с `contact-section.php» 1:1 (Имя
 * родителя + Телефон) — паттерн всё равно оставлен отдельным файлом
 * (не переиспользован напрямую): свой `id` формы (`apply-form`, тот же,
 * что уже был, дублирование id на разных страницах не проблема) и он
 * логически привязан к разделу «Фаза 13», решение 5 которой (без
 * «Направление») остаётся в силе само по себе.
 *
 * Фаза 14: реальная отправка — `data-fs-form` + honeypot/HMAC-таймер/капча
 * (если настроена), `src/js/forms.js` перехватывает `submit` и шлёт AJAX.
 *
 * 2026-09-13 (по указанию пользователя): «Первое занятие — бесплатно.» —
 * с новой строки (`<br>`, как в `contact-section.php`). Страницы
 * направлений подключают паттерн ссылкой `wp:pattern`, правка видна сразу.
 *
 * Этап 4 (2026-09-13): телефон, почта, адрес — из «Настроек сайта»
 * (`inc/Showcase/Site_Settings.php`), `$fs_settings` ниже.
 */
$fs_settings = FS_LMS_Theme_Showcase::settings();
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
			<p class="has-text-secondary-color has-text-color has-md-font-size">Заполни форму и мы ответим на все интересующие вопросы.<br>Первое занятие — бесплатно.</p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"className":"fs-contact-list"} -->
			<div class="wp-block-group fs-contact-list">
				<!-- wp:paragraph {"className":"fs-contact-list__item fs-contact-list__item--phone"} -->
				<p class="fs-contact-list__item fs-contact-list__item--phone"><a href="<?php echo esc_url( $fs_settings->phone_href() ); ?>"><?php echo esc_html( $fs_settings->get( 'phone' ) ); ?></a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-contact-list__item fs-contact-list__item--mail"} -->
				<p class="fs-contact-list__item fs-contact-list__item--mail"><a href="<?php echo esc_url( 'mailto:' . $fs_settings->get( 'email' ) ); ?>"><?php echo esc_html( $fs_settings->get( 'email' ) ); ?></a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-contact-list__item fs-contact-list__item--pin"} -->
				<p class="fs-contact-list__item fs-contact-list__item--pin"><?php echo esc_html( $fs_settings->address_short() ); ?></p>
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
					<label for="fs-subject-apply-name">ФИО родителя</label>
					<input type="text" id="fs-subject-apply-name" name="parent_name" placeholder="Иванова Анна Ивановна" autocomplete="name" <?php echo fs_lms_theme_name_field_attrs_html(); ?> required>
				</div>

				<div class="fs-form-field">
					<label for="fs-subject-apply-phone">Телефон</label>
					<input type="tel" id="fs-subject-apply-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
				</div>

				<input type="hidden" name="form_id" value="signup">
				<input type="hidden" name="fs_form_token" value="<?php echo esc_attr( fs_lms_theme_form_timestamp_token() ); ?>">
				<input type="text" name="<?php echo esc_attr( fs_lms_theme_honeypot_field() ); ?>" class="fs-form-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
				<?php echo fs_lms_theme_captcha_slot_html(); ?>
				<button type="submit" class="fs-apply-form__submit">Отправить заявку</button>
				<div class="fs-apply-form__note"><?php echo fs_lms_theme_form_consent_html(); ?>.</div>
				<div class="fs-form-message" role="status"></div>
			</form>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
