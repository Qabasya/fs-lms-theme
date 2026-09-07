<?php
/**
 * Title: Контакты — телефон/почта, мессенджеры и карты, адрес + форма записи
 * Slug: fs-lms-theme/contacts-info
 * Categories: fs-lms-sections
 * Keywords: контакты, адрес, телефон, почта, мессенджеры, карты, contacts
 *
 * Источник дизайна — «Контакты - мокап.dc.html» (тот же Claude Design
 * проект, что и Фазы 12/13/15/16, импортирован через `DesignSync`).
 * Новая страница `/contacts/` (`inc/StaticPages.php`).
 *
 * Разметка макета НЕ переносится один в один: по прямому указанию
 * пользователя (2026-09-07) собрано из стандартных блоков темы —
 *  - плитки контактов и карточки сервисов — обычные `wp:group` с классом
 *    и общим hover-подъёмом карточек (`@include fs-card-lift`,
 *    `src/scss/_mixins.scss`), тем же, что у `.fs-subject-more-card` и
 *    `.wp-block-group.is-style-card`; в макете hover не был показан;
 *  - вертикальные отступы — `.fs-section` + дефолтный `blockGap` темы
 *    (1.25rem), а не инлайновые `gap: 32px/16px` макета;
 *  - форма — тот же `fs-apply-form`, что на главной
 *    (`patterns/contact-section.php`), один в один, включая honeypot/
 *    HMAC-таймер/капчу Фазы 14; отличаются только `id` полей и `form_id`
 *    (`contacts-signup` — чтобы письмо называло источник заявки, как
 *    `courses-signup` у `patterns/courses-contact.php`).
 *
 * `id="signup"` — тот же якорь формы, что у `contact-section.php` и
 * `courses-contact.php`: на него наводится кнопка «Записаться» в шапке
 * (`fs_lms_theme_signup_button_url()`, `inc/Forms.php` — паттерн добавлен
 * в её список, иначе кнопка уводила бы с этой страницы на главную).
 *
 * `<h1>` («Контакты») не дублируется — его рисует `wp:post-title` из
 * `templates/page-wide.html`, как на `/about/` и `/courses/`.
 *
 * Иконки телефона/почты — те же маски, что у `.fs-contact-list`
 * (`$fs-mask-phone`/`$fs-mask-mail` в `src/scss/theme.scss`), логотипы
 * четырёх сервисов — файлы темы в `img/`.
 *
 * Адреса всех четырёх сервисов даны пользователем (2026-09-07) —
 * настоящие, не заглушки: MAX, сообщество ВКонтакте `future_step39`,
 * короткая ссылка Яндекс Карт и карточка организации в 2ГИС. Открываются
 * в новой вкладке (`target="_blank"` + `rel="noreferrer noopener"`) —
 * это внешние сайты, в отличие от всех остальных ссылок темы.
 */
?>
<!-- wp:group {"anchor":"signup","className":"fs-section"} -->
<div id="signup" class="wp-block-group fs-section">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-contact-tiles","layout":{"type":"grid","columnCount":2}} -->
			<div class="wp-block-group fs-contact-tiles">
				<!-- wp:group {"className":"fs-contact-tile"} -->
				<div class="wp-block-group fs-contact-tile">
					<!-- wp:html -->
					<span class="fs-contact-tile__icon fs-contact-tile__icon--phone has-accent-2-color has-accent-2-soft-background-color has-text-color has-background" aria-hidden="true"></span>
					<!-- /wp:html -->

					<!-- wp:paragraph {"className":"fs-contact-tile__label"} -->
					<p class="fs-contact-tile__label">Телефон</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-contact-tile__value"} -->
					<p class="fs-contact-tile__value"><a href="tel:+79953264486">+7 995 326 44 86</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"fs-contact-tile"} -->
				<div class="wp-block-group fs-contact-tile">
					<!-- wp:html -->
					<span class="fs-contact-tile__icon fs-contact-tile__icon--mail has-accent-color has-accent-soft-background-color has-text-color has-background" aria-hidden="true"></span>
					<!-- /wp:html -->

					<!-- wp:paragraph {"className":"fs-contact-tile__label"} -->
					<p class="fs-contact-tile__label">Почта</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-contact-tile__value"} -->
					<p class="fs-contact-tile__value"><a href="mailto:info@future-step.ru">info@future-step.ru</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:heading {"level":2,"fontSize":"lg"} -->
			<h2 class="wp-block-heading has-lg-font-size">Мы в мессенджерах и на картах</h2>
			<!-- /wp:heading -->

			<!-- wp:group {"className":"fs-contact-apps","layout":{"type":"grid","columnCount":4}} -->
			<div class="wp-block-group fs-contact-apps">
				<!-- wp:group {"className":"fs-contact-app"} -->
				<div class="wp-block-group fs-contact-app">
					<!-- wp:image {"width":"44px","className":"fs-contact-app__logo"} -->
					<figure class="wp-block-image is-resized fs-contact-app__logo"><img src="<?php echo esc_url( get_theme_file_uri( 'img/max-messenger-sign-logo.png' ) ); ?>" alt="MAX" style="width:44px"/></figure>
					<!-- /wp:image -->

					<!-- wp:paragraph {"className":"fs-contact-app__label"} -->
					<p class="fs-contact-app__label"><a href="https://max.ru/u/f9LHodD0cOKoJqQrKkMSbocYBDaed99orfRNtpWEvXtVKst1I0xZAk2tjvg" target="_blank" rel="noreferrer noopener">MAX</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"fs-contact-app"} -->
				<div class="wp-block-group fs-contact-app">
					<!-- wp:image {"width":"44px","className":"fs-contact-app__logo"} -->
					<figure class="wp-block-image is-resized fs-contact-app__logo"><img src="<?php echo esc_url( get_theme_file_uri( 'img/vk-logo.png' ) ); ?>" alt="ВКонтакте" style="width:44px"/></figure>
					<!-- /wp:image -->

					<!-- wp:paragraph {"className":"fs-contact-app__label"} -->
					<p class="fs-contact-app__label"><a href="https://vk.ru/future_step39" target="_blank" rel="noreferrer noopener">ВКонтакте</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"fs-contact-app"} -->
				<div class="wp-block-group fs-contact-app">
					<!-- wp:image {"width":"44px","className":"fs-contact-app__logo"} -->
					<figure class="wp-block-image is-resized fs-contact-app__logo"><img src="<?php echo esc_url( get_theme_file_uri( 'img/yandex-maps-logo.png' ) ); ?>" alt="Яндекс Карты" style="width:44px"/></figure>
					<!-- /wp:image -->

					<!-- wp:paragraph {"className":"fs-contact-app__label"} -->
					<p class="fs-contact-app__label"><a href="https://yandex.ru/maps/-/CTdGaCPB" target="_blank" rel="noreferrer noopener">Яндекс Карты</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"fs-contact-app"} -->
				<div class="wp-block-group fs-contact-app">
					<!-- wp:image {"width":"44px","className":"fs-contact-app__logo"} -->
					<figure class="wp-block-image is-resized fs-contact-app__logo"><img src="<?php echo esc_url( get_theme_file_uri( 'img/2gis-icon-logo.png' ) ); ?>" alt="2ГИС" style="width:44px"/></figure>
					<!-- /wp:image -->

					<!-- wp:paragraph {"className":"fs-contact-app__label"} -->
					<p class="fs-contact-app__label"><a href="https://2gis.ru/kaliningrad/firm/70000001080562359/tab/reviews" target="_blank" rel="noreferrer noopener">2ГИС</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:heading {"level":2,"fontSize":"lg"} -->
			<h2 class="wp-block-heading has-lg-font-size">Адрес</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md","style":{"typography":{"fontWeight":"300"}}} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size" style="font-weight:300">236006, г. Калининград, ул. Черняховского, д. 6, каб. 316</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"480px"} -->
		<div class="wp-block-column" style="flex-basis:480px">
			<!-- wp:html -->
			<form class="fs-apply-form" id="contacts-apply-form" data-fs-form>
				<div class="fs-apply-form__title">Записаться на пробное занятие</div>

				<div class="fs-form-field">
					<label for="fs-contacts-apply-name">ФИО родителя</label>
					<input type="text" id="fs-contacts-apply-name" name="parent_name" placeholder="Иванова Анна Ивановна" autocomplete="name" <?php echo fs_lms_theme_name_field_attrs_html(); ?> required>
				</div>

				<div class="fs-form-field">
					<label for="fs-contacts-apply-phone">Телефон</label>
					<input type="tel" id="fs-contacts-apply-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
				</div>

				<input type="hidden" name="form_id" value="contacts-signup">
				<input type="hidden" name="fs_form_token" value="<?php echo esc_attr( fs_lms_theme_form_timestamp_token() ); ?>">
				<input type="text" name="<?php echo esc_attr( fs_lms_theme_honeypot_field() ); ?>" class="fs-form-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
				<?php if ( function_exists( 'fs_lms_theme_captcha_configured' ) && fs_lms_theme_captcha_configured() ) : ?>
				<div class="smart-captcha" data-sitekey="<?php echo esc_attr( get_option( 'fs_lms_theme_captcha_site_key', '' ) ); ?>"></div>
				<?php endif; ?>
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
