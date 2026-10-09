<?php
/**
 * Реальная отправка лид-форм темы (Фаза 14) — `#hero-form`
 * (`patterns/hero.php`, `subject-hero.php`) и `#signup`
 * (`patterns/contact-section.php`, `subject-contact.php`). Простая
 * лид-форма «оставьте контакты, перезвоним» на `wp_mail()` — НЕ тот же
 * поток, что заявка на зачисление плагина (`ApplicationCallbacks`,
 * OTP/шифрование PII/личный кабинет), плагин не трогаем, своя защита в
 * теме (не тянем классы плагина как хард-зависимость — см.
 * `inc/PluginRoutes.php` для того же принципа с URL).
 *
 * Защита — тот же стек, что у формы заявки плагина, но свой инстанс:
 * honeypot + подписанный HMAC-таймер (по образцу
 * `Inc\Services\Security\FormGuardService` плагина) + невидимая Yandex
 * SmartCaptcha (`inc/SmartCaptcha.php`, свои ключи в настройках темы)
 * + rate-limit по IP на transient.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Минимальное «человеческое» время заполнения формы, сек. */
const FS_LMS_THEME_FORM_MIN_FILL_SECONDS = 3;

/** Срок годности токена формы, сек (защита от reuse старых меток). */
const FS_LMS_THEME_FORM_MAX_TOKEN_AGE = HOUR_IN_SECONDS;

/** Лимит сабмитов с одного IP в окне rate-limit. */
const FS_LMS_THEME_FORM_RATE_LIMIT = 2;

/** Окно rate-limit по IP, сек. */
const FS_LMS_THEME_FORM_RATE_WINDOW = 15 * MINUTE_IN_SECONDS;

/**
 * Отдельный лимит по номеру телефона: спамер меняет IP (прокси, мобильная
 * сеть), но номер в форме обычно оставляет один и тот же. Считаем только
 * цифры номера, чтобы «+7 (999)…» и «8999…» попадали в один счётчик.
 */
const FS_LMS_THEME_FORM_PHONE_LIMIT = 2;

/** Окно лимита по номеру телефона, сек. */
const FS_LMS_THEME_FORM_PHONE_WINDOW = HOUR_IN_SECONDS;

/**
 * Лимит на заявки без токена капчи — общий на весь сайт, не по IP.
 *
 * BugFix (2026-09-12, решение пользователя): если у посетителя капча не
 * загрузилась (блокировщик рекламы, сеть), заявку всё равно принимаем.
 * Сервер не может отличить такого посетителя от бота, который просто не
 * прислал токен, поэтому поток «без капчи» ограничен целиком: при атаке
 * упрётся в лимит он, а заявки с пройденной капчей идут как обычно.
 */
const FS_LMS_THEME_FORM_NO_CAPTCHA_LIMIT = 5;

/** Окно лимита заявок без капчи, сек. */
const FS_LMS_THEME_FORM_NO_CAPTCHA_WINDOW = HOUR_IN_SECONDS;

/*
 * Получатель писем с лид-форм (решение 2, обсуждение 2026-09-02) — с этапа 4
 * (2026-09-13) поле «Куда приходят заявки с форм» в «Настройках сайта»
 * (`inc/Showcase/Site_Settings.php`), по умолчанию прежний info@future-step.ru.
 */

/** Тема письма — одна на все формы (2026-09-12, по указанию пользователя). */
const FS_LMS_THEME_FORM_MAIL_SUBJECT = 'Новая заявка на сайте';

/**
 * Подписи форм главной страницы в письме, `form_id` → подпись: форм там
 * две, и одна ссылка на страницу их не различает.
 */
const FS_LMS_THEME_FRONT_PAGE_FORMS = array(
	'hero'   => 'Форма 1',
	'signup' => 'Форма 2',
);

/**
 * BugFix.4 (2026-09-05): правило для полей с именем — то же, что у плагина
 * fs-lms (`cyrillicName`: `/^[А-Яа-яЁё\s-]+$/u` — буквы кириллицы, пробелы
 * и дефис для двойных имён). Длину плагин задаёт разметкой поля, своего
 * значения там нет: берём 2–80 символов — короче осмысленного имени не
 * бывает, длиннее в заявке не нужно.
 *
 * Тело без якорей и квантификатора — из него собираются и `pattern` в
 * разметке (там якоря подставляет сам браузер), и серверные `preg_match`
 * лид-формы и оформления заказа, чтобы правило жило в одном месте.
 */
const FS_LMS_THEME_NAME_CHARS = 'А-Яа-яЁё\s\-';

/** Минимальная длина имени. */
const FS_LMS_THEME_NAME_MIN = 2;

/** Максимальная длина имени. */
const FS_LMS_THEME_NAME_MAX = 80;

/**
 * Проверка значения поля с именем по правилу выше.
 */
function fs_lms_theme_is_valid_name( string $value ): bool {
	return 1 === preg_match(
		'/^[' . FS_LMS_THEME_NAME_CHARS . ']{' . FS_LMS_THEME_NAME_MIN . ',' . FS_LMS_THEME_NAME_MAX . '}$/u',
		trim( $value )
	);
}

/**
 * Атрибуты нативной валидации для поля с именем — чтобы браузер показывал
 * ошибку до отправки. Не замена серверной проверки: `pattern` снимается
 * инструментами разработчика, а форму можно отправить и мимо страницы.
 *
 * @return array<string, string>
 */
function fs_lms_theme_name_field_attributes(): array {
	return array(
		'pattern'   => '[' . FS_LMS_THEME_NAME_CHARS . ']{' . FS_LMS_THEME_NAME_MIN . ',' . FS_LMS_THEME_NAME_MAX . '}',
		'minlength' => (string) FS_LMS_THEME_NAME_MIN,
		'maxlength' => (string) FS_LMS_THEME_NAME_MAX,
		'title'     => __( 'Только буквы кириллицы, пробелы и дефис', 'fs-lms-theme' ),
	);
}

/**
 * Те же атрибуты строкой — для разметки лид-форм в паттернах
 * (`hero.php`, `contact-section.php`, `subject-hero*.php`,
 * `subject-contact.php`, `courses-contact.php`). Раньше правило было
 * скопировано в каждый из восьми файлов руками и разъезжалось при правках.
 */
function fs_lms_theme_name_field_attrs_html(): string {
	$attributes = array();

	foreach ( fs_lms_theme_name_field_attributes() as $name => $value ) {
		$attributes[] = sprintf( '%s="%s"', $name, esc_attr( $value ) );
	}

	return implode( ' ', $attributes );
}

/**
 * Сколько слов в поле «ФИО родителя» лид-формы минимум (2026-10-09): боты пишут
 * одно имя («Диана»), фамилию не вводят. Правило только для лид-форм: оформление
 * заказа проверяет имя через `fs_lms_theme_is_valid_name()` и слов не считает.
 */
const FS_LMS_THEME_LEAD_NAME_WORDS_MIN = 2;

/**
 * В имени лид-формы достаточно слов (имя и фамилия). Зеркало
 * `validateLeadName()` в `src/js/forms.js`.
 */
function fs_lms_theme_lead_name_has_surname( string $value ): bool {
	$words = preg_split( '/\s+/u', trim( $value ), -1, PREG_SPLIT_NO_EMPTY );

	return false !== $words && count( $words ) >= FS_LMS_THEME_LEAD_NAME_WORDS_MIN;
}

/**
 * Передаёт заявку лид-формы подписчикам (плагин fs-lms складывает её в таблицу
 * «Пользователи → Заявки с сайта»). Тема от плагина не зависит: без подписчиков
 * фильтр ничего не меняет, а заявка считается несохранённой.
 *
 * @return bool Подписчик сохранил заявку.
 *
 * Вызывается только после проверки согласия на обработку ПДн — без отметки
 * данные посетителя нигде не сохраняются.
 *
 * @param array<string, mixed> $lead Поля заявки (см. `fs_lms_theme_handle_form_submit()`).
 */
function fs_lms_theme_dispatch_lead( array $lead ): bool {
	/**
	 * Заявка лид-формы темы (принятая или отклонённая). Фильтр, а не действие:
	 * подписчик отвечает `true`, если заявку сохранил, — тогда принятая заявка не
	 * считается потерянной, даже если письмо не ушло. Без подписчиков — `false`.
	 *
	 * @param bool                 $stored Заявка сохранена подписчиком (по умолчанию false).
	 * @param array<string, mixed> $lead {
	 *     @type string      $verdict           'accepted' | 'rejected'.
	 *     @type string      $reason            Причина отказа (пусто у принятых): name_format,
	 *                                          name_single_word, phone_format, rate_ip, rate_phone,
	 *                                          captcha_failed, no_captcha_limit.
	 *     @type string      $name              Имя из формы.
	 *     @type string      $phone             Телефон из формы.
	 *     @type string      $form_id           'hero' | 'signup'.
	 *     @type string      $page_url          Страница, с которой отправили форму.
	 *     @type string      $ip                IP посетителя.
	 *     @type string      $user_agent        Браузер.
	 *     @type int         $fill_seconds      Секунд от первого фокуса в форме до отправки.
	 *     @type string      $captcha           'passed' | 'failed' | 'unavailable' | 'skipped' | 'off'.
	 *     @type bool        $captcha_challenge Посетителю показали задание.
	 *     @type bool        $mobile            Телефон или планшет.
	 *     @type int         $received_at       Unix-время.
	 *     @type bool|null   $mail_sent         Результат wp_mail(); null — письмо не отправлялось.
	 * }
	 */
	return (bool) apply_filters( 'fs_lms_theme_lead_submitted', false, $lead );
}

/** Имя чекбокса согласия на обработку ПДн в лид-формах. */
const FS_LMS_THEME_FORM_CONSENT_FIELD = 'pd_consent';

/**
 * Согласие на обработку ПДн под кнопкой лид-формы — одно на все паттерны.
 *
 * 152-ФЗ (2026-09-29): раньше здесь было «Нажимая кнопку, вы соглашаетесь с
 * политикой конфиденциальности». С 01.09.2025 согласие на обработку ПДн
 * оформляется отдельно от иных документов (ч. 1 ст. 9), и согласие с
 * политикой им не является. Теперь — чекбокс (не отмечен заранее,
 * `required`) со ссылкой на документ согласия плагина (`pd_processing`,
 * `fs_lms_theme_pd_consent_url()`), политика — отдельной ссылкой. Сервер
 * проверяет отметку сам (`fs_lms_theme_handle_form_submit()`).
 *
 * 2026-09-29 (по указанию пользователя): подпись сокращена до «Даю согласие
 * на обработку персональных данных» (ссылка — на сам документ согласия),
 * зачем данные и ссылка на политику — в поповере по кнопке «i»
 * (`fs_lms_theme_pd_consent_info_html()`), там же — уведомление про
 * SmartCaptcha (`fs_lms_theme_captcha_notice_html()`): под формой остаётся
 * одна строка с галочкой, вёрстка hero не меняется.
 */
function fs_lms_theme_form_consent_html(): string {
	return sprintf(
		'<div class="fs-form-consent"><label class="fs-form-consent__label"><input type="checkbox" name="%s" value="1" required><span>%s</span></label>%s</div>',
		esc_attr( FS_LMS_THEME_FORM_CONSENT_FIELD ),
		fs_lms_theme_pd_consent_text_html(),
		fs_lms_theme_pd_consent_info_html( 'Имя и телефон нужны, только чтобы связаться с вами по заявке.', fs_lms_theme_captcha_notice_html() )
	);
}

/**
 * Текст рядом с чекбоксом согласия — общий для лид-форм и оформления
 * заказа WooCommerce (`inc/Checkout.php`). Ссылка — на документ согласия
 * плагина; если его нет, текст без ссылки (политика — в поповере).
 *
 * @return string Готовый HTML (ссылка собрана с esc_url).
 */
function fs_lms_theme_pd_consent_text_html(): string {
	$consent_url = fs_lms_theme_pd_consent_url();

	return '' !== $consent_url
		? sprintf( 'Даю <a href="%s" target="_blank" rel="noopener">согласие на обработку персональных данных</a>', esc_url( $consent_url ) )
		: 'Даю согласие на обработку персональных данных';
}

/**
 * Кнопка «i» и поповер с подробностями согласия (нативный `popover`, без
 * JS): зачем нужны данные, ссылка на политику конфиденциальности и, если
 * передано, дополнительный абзац (уведомление SmartCaptcha у лид-форм).
 *
 * Кнопку и поповер ставят рядом с `<label>`, а не внутрь: щелчок по тексту
 * поповера внутри `<label>` переключал бы чекбокс. Поповер — `<span>`:
 * на оформлении заказа блок стоит внутри `<p>`, и `<div>` разорвал бы его.
 * `id` уникален на страницу — форм с согласием на одной странице бывает
 * две (hero + signup).
 *
 * @param string $purpose Зачем форме данные — одно предложение.
 * @param string $extra   Готовый HTML дополнительного абзаца (уже экранирован).
 */
function fs_lms_theme_pd_consent_info_html( string $purpose, string $extra = '' ): string {
	static $counter = 0;
	++$counter;

	$id = 'fs-consent-info-' . $counter;

	return sprintf(
		'<button type="button" class="fs-form-consent__info" popovertarget="%1$s" aria-label="%2$s">i</button><span id="%1$s" class="fs-form-consent__popover" popover>%3$s %4$s%5$s</span>',
		esc_attr( $id ),
		esc_attr__( 'Подробнее об обработке данных', 'fs-lms-theme' ),
		esc_html( $purpose ),
		sprintf(
			/* translators: %s — ссылка на политику конфиденциальности. */
			esc_html__( 'Как мы храним и защищаем данные — в %s.', 'fs-lms-theme' ),
			'<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'политике конфиденциальности', 'fs-lms-theme' ) . '</a>'
		),
		$extra
	);
}

/**
 * Уведомление об обработке данных Yandex SmartCaptcha (2026-09-29). Значок
 * капчи скрыт (`hideShield` в `src/js/captcha.js`), а правила сервиса
 * требуют тогда сообщить пользователю иным способом, что его данные
 * обрабатывает SmartCaptcha. Выводится абзацем в поповере согласия, ссылка —
 * на «Уведомление об условиях обработки данных» Яндекса. Пусто, если ключи
 * капчи не заданы: без капчи и сообщать не о чем. `<span>`, а не `<div>` —
 * поповер сам `<span>` (см. `fs_lms_theme_pd_consent_info_html()`).
 */
function fs_lms_theme_captcha_notice_html(): string {
	if ( ! fs_lms_theme_captcha_configured() ) {
		return '';
	}

	return sprintf(
		'<span class="fs-form-captcha-notice">%s <a href="https://yandex.ru/legal/smartcaptcha_notice/" target="_blank" rel="noopener">%s</a>.</span>',
		esc_html__( 'Форма защищена от спама сервисом Yandex SmartCaptcha — он получает технические данные браузера и сведения о действиях на странице.', 'fs-lms-theme' ),
		esc_html__( 'Условия обработки данных', 'fs-lms-theme' )
	);
}

/**
 * Формы, вставленные на страницы из инсёртера, сохранены в `post_content`
 * со старой подписью «Нажимая кнопку…» (см. `inc/ContentUpgrades.php`) —
 * правка функции выше до них не доходит, а без чекбокса сервер такие заявки
 * теперь отклонял бы. Подменяем подпись при выводе блока, а не в базе:
 * сохранённую страницу не трогаем, и подпись всегда совпадает с текущей
 * функцией.
 */
add_filter( 'render_block_core/html', function ( string $content ): string {
	if ( ! str_contains( $content, 'Нажимая кнопку, вы соглашаетесь с' ) ) {
		return $content;
	}

	return (string) preg_replace_callback(
		'#Нажимая кнопку, вы соглашаетесь с <a [^>]*>политикой конфиденциальности</a>\.?#u',
		static fn(): string => fs_lms_theme_form_consent_html(),
		$content
	);
} );

/**
 * Задача 9 (tasks.md, 2026-09-04): URL кнопки «Записаться» в шапке
 * (`patterns/header-nav.php`). Если на текущей странице есть форма
 * записи — просто якорь на неё (`#hero-form` у hero/subject-hero,
 * `#signup` у contact-section/subject-contact/courses-contact — берём тот,
 * что встречается раньше по разметке паттернов страницы). Если формы нет
 * (страницы WooCommerce, `/about/`, личный кабинет и т.д.) — якорь на
 * форму главной страницы.
 *
 * Определение «есть ли форма» — по наличию слага паттерна в
 * `post_content` текущей страницы: сами паттерны рендерятся как
 * `<!-- wp:pattern {"slug":"..."} /-->`, поэтому подстроки узнаваемы без
 * разбора блоков. Главная — особый случай: её hero/contact-section зашиты
 * прямо в `templates/front-page.html`, а не в `post_content` страницы.
 */
function fs_lms_theme_signup_button_url(): string {
	$home_anchor = home_url( '/#hero-form' );

	if ( is_front_page() ) {
		return '#hero-form';
	}

	$page = get_queried_object();

	if ( ! ( $page instanceof WP_Post ) ) {
		return $home_anchor;
	}

	$content = $page->post_content;

	if ( false !== strpos( $content, 'fs-lms-theme/hero' ) || false !== strpos( $content, 'fs-lms-theme/subject-hero' ) ) {
		return '#hero-form';
	}

	if (
		false !== strpos( $content, 'fs-lms-theme/contact-section' )
		|| false !== strpos( $content, 'fs-lms-theme/subject-contact' )
		|| false !== strpos( $content, 'fs-lms-theme/courses-contact' )
		|| false !== strpos( $content, 'fs-lms-theme/contacts-info' )
	) {
		return '#signup';
	}

	return $home_anchor;
}

/* --------------------------------------------------------------------
 * FormGuard — honeypot + HMAC-таймер (1:1 логика с FormGuardService
 * плагина, свой инстанс в теме).
 * ------------------------------------------------------------------ */

function fs_lms_theme_honeypot_field(): string {
	return 'fs_company';
}

function fs_lms_theme_form_salt(): string {
	if ( defined( 'FS_LMS_THEME_FORM_SALT' ) ) {
		return FS_LMS_THEME_FORM_SALT;
	}

	return defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
}

function fs_lms_theme_form_sign( string $timestamp ): string {
	return hash_hmac( 'sha256', $timestamp, fs_lms_theme_form_salt() );
}

/**
 * @return string Формат: "{timestamp}.{hmac}"
 */
function fs_lms_theme_form_timestamp_token(): string {
	$ts = (string) time();

	return $ts . '.' . fs_lms_theme_form_sign( $ts );
}

/**
 * Сколько секунд прошло с выдачи метки формы.
 *
 * @return int|null Null — метка битая или подпись не сходится.
 */
function fs_lms_theme_form_token_age( string $token ): ?int {
	$parts = explode( '.', $token, 2 );
	if ( 2 !== count( $parts ) ) {
		return null;
	}

	list( $ts, $sig ) = $parts;

	if ( ! ctype_digit( $ts ) || ! hash_equals( fs_lms_theme_form_sign( $ts ), $sig ) ) {
		return null;
	}

	return time() - (int) $ts;
}

function fs_lms_theme_form_is_human( string $honeypot_value, string $token ): bool {
	if ( '' !== trim( $honeypot_value ) ) {
		return false;
	}

	$elapsed = fs_lms_theme_form_token_age( $token );

	return null !== $elapsed && $elapsed >= FS_LMS_THEME_FORM_MIN_FILL_SECONDS && $elapsed <= FS_LMS_THEME_FORM_MAX_TOKEN_AGE;
}

/* --------------------------------------------------------------------
 * Rate-limit — простой transient-счётчик по хэшу IP (не копия
 * RateLimitService плагина).
 * ------------------------------------------------------------------ */

function fs_lms_theme_client_ip(): string {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
}

/**
 * Счётчик отправок по произвольному ключу (IP, номер телефона).
 *
 * @param string $bucket Что считаем: 'ip:1.2.3.4', 'phone:79995551122'.
 * @param int    $limit  Сколько отправок допускаем в окне.
 * @param int    $window Длина окна, сек.
 *
 * @return bool true — лимит исчерпан, отправку принимать нельзя.
 */
function fs_lms_theme_form_rate_limited( string $bucket, int $limit, int $window ): bool {
	$key   = 'fs_theme_form_' . sha1( $bucket );
	$count = (int) get_transient( $key );

	if ( $count >= $limit ) {
		return true;
	}

	set_transient( $key, $count + 1, $window );

	return false;
}

/** Только цифры номера — чтобы «+7 (999) 555-11-22» и «8999…» считались одним. */
function fs_lms_theme_phone_digits( string $phone ): string {
	$digits = preg_replace( '/\D+/', '', $phone );

	if ( null === $digits || '' === $digits ) {
		return '';
	}

	// 8XXXXXXXXXX и 7XXXXXXXXXX — один и тот же номер.
	if ( 11 === strlen( $digits ) && '8' === $digits[0] ) {
		$digits = '7' . substr( $digits, 1 );
	}

	return $digits;
}

/**
 * Номер похож на настоящий (2026-10-09): отсекает случайные цифры. Не проверка
 * на существование — это бы потребовало запроса к оператору (HLR), от него отказались.
 *
 * Правила: 11 цифр с кодом 7; код региона/оператора не начинается с 0, 1, 2 (городские
 * 3xx–5xx, 8xx и мобильные 9xx проходят); в номере не меньше 4 разных цифр
 * (9999999999, 9000000000); последние 7 цифр — не ряд подряд (1234567, 7654321).
 * Зеркало `validatePhoneValue()` в `src/js/forms.js`.
 */
function fs_lms_theme_phone_looks_real( string $phone ): bool {
	$digits = fs_lms_theme_phone_digits( $phone );

	if ( 11 !== strlen( $digits ) || '7' !== $digits[0] || in_array( $digits[1], array( '0', '1', '2' ), true ) ) {
		return false;
	}

	$national = substr( $digits, 1 );

	if ( count( array_unique( str_split( $national ) ) ) < 4 ) {
		return false;
	}

	$tail = array_map( 'intval', str_split( substr( $national, -7 ) ) );
	$step = $tail[1] - $tail[0];

	if ( 1 === abs( $step ) ) {
		$is_run = true;
		for ( $i = 1; $i < 7; $i++ ) {
			if ( $tail[ $i ] - $tail[ $i - 1 ] !== $step ) {
				$is_run = false;
				break;
			}
		}

		if ( $is_run ) {
			return false;
		}
	}

	return true;
}

/**
 * Откуда пришла заявка — для строки «Форма:» в письме: ссылка на страницу, а
 * на главной ещё и подпись формы (`FS_LMS_THEME_FRONT_PAGE_FORMS`).
 *
 * Адрес присылает браузер (`page_url`, `src/js/forms.js`), поэтому ссылку на
 * чужой хост не выводим (`wp_validate_redirect()`), а якорь вроде
 * `#hero-form` срезаем: о странице он ничего не говорит.
 *
 * @param string $form_id  Скрытое поле `form_id` формы.
 * @param string $page_url Адрес страницы, с которой отправили форму.
 */
function fs_lms_theme_form_source( string $form_id, string $page_url ): string {
	$url = explode( '#', wp_validate_redirect( $page_url, '' ), 2 )[0];

	if ( '' === $url ) {
		return $form_id;
	}

	$path      = '/' . trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
	$home_path = '/' . trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );

	if ( $path === $home_path && isset( FS_LMS_THEME_FRONT_PAGE_FORMS[ $form_id ] ) ) {
		return $url . ' — ' . FS_LMS_THEME_FRONT_PAGE_FORMS[ $form_id ];
	}

	return $url;
}

/**
 * Строка «Согласие на обработку ПДн:» технического блока письма (152-ФЗ,
 * 2026-09-29) — какую редакцию документа принял посетитель: согласие в
 * плагине версионируется ревизиями своей страницы, поэтому фиксируем
 * страницу и дату её последней правки на момент заявки.
 */
function fs_lms_theme_form_consent_note(): string {
	$page = fs_lms_theme_pd_consent_page();

	if ( ! $page ) {
		return 'получено (чекбокс), документ — политика конфиденциальности ' . home_url( '/privacy-policy/' );
	}

	return sprintf(
		'получено (чекбокс), документ %s (страница #%d, редакция от %s)',
		get_permalink( $page ),
		$page->ID,
		get_post_modified_time( 'd.m.Y H:i', false, $page )
	);
}

/**
 * Строка «Капча:» технического блока письма (2026-09-12, по указанию
 * пользователя) — чем закончилась проверка у этой заявки.
 *
 * Показывали ли посетителю задание, знает только браузер
 * (`captcha_challenge`, `src/js/captcha.js`), — это пометка для чтения
 * письма, на приём заявки она не влияет.
 *
 * @param bool   $configured Ключи капчи заданы.
 * @param bool   $skipped    Токена нет — капча не загрузилась у посетителя.
 * @param string $result     Исход `FS_LMS_Theme_Smart_Captcha::verify()`.
 * @param bool   $challenge  Посетителю показали задание.
 */
function fs_lms_theme_form_captcha_note( bool $configured, bool $skipped, string $result, bool $challenge ): string {
	if ( ! $configured ) {
		return 'выключена (ключи не заданы в Настройки → Формы)';
	}

	if ( $skipped ) {
		return 'не загрузилась у посетителя, заявка принята без неё';
	}

	if ( FS_LMS_Theme_Smart_Captcha::UNAVAILABLE === $result ) {
		return 'не проверена — API Яндекса не ответил, заявка принята';
	}

	return $challenge ? 'пройдена, посетитель решал задание' : 'пройдена без задания';
}

/**
 * Строка «Заполнение формы:» — возраст метки `fs_form_token` на момент
 * прихода заявки. Метку `src/js/forms.js` берёт на первом фокусе в форме,
 * так что это время от первого клика в форме до отправки: у ботов — секунды.
 *
 * @param int $seconds `fs_lms_theme_form_token_age()`.
 */
function fs_lms_theme_form_fill_time( int $seconds ): string {
	if ( $seconds < MINUTE_IN_SECONDS ) {
		return sprintf( '%d сек', $seconds );
	}

	return sprintf( '%d мин %d сек', intdiv( $seconds, MINUTE_IN_SECONDS ), $seconds % MINUTE_IN_SECONDS );
}

/* --------------------------------------------------------------------
 * Свежая метка времени для формы.
 *
 * BugFix (2026-09-12): `fs_form_token` печатается в разметку при рендере
 * страницы, а кэш страниц (WP Rocket на проде) отдаёт эту разметку часами.
 * Метка старше `FS_LMS_THEME_FORM_MAX_TOKEN_AGE` не проходит проверку, и
 * посетитель кэшированной страницы не мог отправить форму — «обновите
 * страницу» не помогало, обновлялся тот же кэш. `src/js/forms.js` берёт
 * метку и nonce здесь при первом фокусе в форме; `admin-ajax.php` кэш
 * страниц не трогает.
 * ------------------------------------------------------------------ */

add_action( 'wp_ajax_fs_theme_form_token', 'fs_lms_theme_handle_form_token' );
add_action( 'wp_ajax_nopriv_fs_theme_form_token', 'fs_lms_theme_handle_form_token' );

function fs_lms_theme_handle_form_token(): void {
	wp_send_json_success( array(
		'token' => fs_lms_theme_form_timestamp_token(),
		'nonce' => wp_create_nonce( 'fs-theme-form' ),
	) );
}

/* --------------------------------------------------------------------
 * AJAX-обработчик — общий для всех форм темы, различает форму по
 * скрытому полю `form_id` ('signup' / 'hero').
 * ------------------------------------------------------------------ */

add_action( 'wp_ajax_fs_theme_submit_form', 'fs_lms_theme_handle_form_submit' );
add_action( 'wp_ajax_nopriv_fs_theme_submit_form', 'fs_lms_theme_handle_form_submit' );

function fs_lms_theme_handle_form_submit(): void {
	check_ajax_referer( 'fs-theme-form', 'nonce' );

	$received_at = time();

	$form_id  = isset( $_POST['form_id'] ) ? sanitize_key( wp_unslash( $_POST['form_id'] ) ) : 'signup';
	$name     = isset( $_POST['parent_name'] ) ? sanitize_text_field( wp_unslash( $_POST['parent_name'] ) ) : '';
	$phone    = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$page_url = isset( $_POST['page_url'] ) ? esc_url_raw( wp_unslash( $_POST['page_url'] ) ) : '';

	$captcha_challenge = isset( $_POST['captcha_challenge'] ) && '1' === sanitize_text_field( wp_unslash( $_POST['captcha_challenge'] ) );
	$user_agent        = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';

	// 152-ФЗ: без отметки согласия данные не обрабатываем (чекбокс `required`
	// в разметке обходится прямым запросом к admin-ajax.php).
	$pd_consent = isset( $_POST[ FS_LMS_THEME_FORM_CONSENT_FIELD ] ) && '1' === sanitize_text_field( wp_unslash( $_POST[ FS_LMS_THEME_FORM_CONSENT_FIELD ] ) );

	$honeypot = isset( $_POST[ fs_lms_theme_honeypot_field() ] )
		? (string) wp_unslash( $_POST[ fs_lms_theme_honeypot_field() ] )
		: '';
	$token = isset( $_POST['fs_form_token'] ) ? (string) wp_unslash( $_POST['fs_form_token'] ) : '';

	if ( ! fs_lms_theme_form_is_human( $honeypot, $token ) ) {
		wp_send_json_error( array( 'message' => __( 'Не удалось отправить форму. Обновите страницу и попробуйте ещё раз.', 'fs-lms-theme' ) ), 400 );
	}

	$fill_seconds = (int) fs_lms_theme_form_token_age( $token );

	$ip = fs_lms_theme_client_ip();

	$captcha         = new FS_LMS_Theme_Smart_Captcha();
	$captcha_token   = isset( $_POST['smart-token'] ) ? sanitize_text_field( wp_unslash( $_POST['smart-token'] ) ) : '';
	$captcha_skipped = $captcha->is_configured() && '' === $captcha_token;
	$captcha_result  = '';

	/**
	 * Заявка для плагина (`fs_lms_theme_lead_submitted`): принятая и отклонённая —
	 * обе, чтобы по отказам было видно ложные срабатывания. Только с отметкой
	 * согласия и заполненными полями: иначе сохранять нечего и не на каком основании.
	 *
	 * @param string    $verdict   'accepted' | 'rejected'.
	 * @param string    $reason    Причина отказа.
	 * @param bool|null $mail_sent Результат wp_mail().
	 */
	$record = static function ( string $verdict, string $reason, ?bool $mail_sent = null ) use (
		$pd_consent, $name, $phone, $form_id, $page_url, $ip, $user_agent, $fill_seconds, $captcha, $captcha_skipped, &$captcha_result, $captcha_challenge, $received_at
	): bool {
		if ( ! $pd_consent || '' === $name || '' === $phone ) {
			return false;
		}

		if ( ! $captcha->is_configured() ) {
			$captcha_state = 'off';
		} elseif ( $captcha_skipped ) {
			$captcha_state = 'skipped';
		} else {
			$captcha_state = '' !== $captcha_result ? $captcha_result : 'off';
		}

		return fs_lms_theme_dispatch_lead( array(
			'verdict'           => $verdict,
			'reason'            => $reason,
			'name'              => $name,
			'phone'             => $phone,
			'form_id'           => $form_id,
			'page_url'          => $page_url,
			'ip'                => $ip,
			'user_agent'        => $user_agent,
			'fill_seconds'      => $fill_seconds,
			'captcha'           => $captcha_state,
			'captcha_challenge' => $captcha_challenge,
			'mobile'            => wp_is_mobile(),
			'received_at'       => $received_at,
			'mail_sent'         => $mail_sent,
		) );
	};

	/**
	 * Единая точка отказа: фиксирует заявку для плагина и отвечает посетителю.
	 */
	$reject = static function ( string $reason, string $message, int $status ) use ( $record ): void {
		$record( 'rejected', $reason );
		wp_send_json_error( array( 'message' => $message ), $status );
	};

	if ( fs_lms_theme_form_rate_limited( 'ip:' . $ip, FS_LMS_THEME_FORM_RATE_LIMIT, FS_LMS_THEME_FORM_RATE_WINDOW ) ) {
		$reject( 'rate_ip', __( 'Слишком много попыток. Попробуйте немного позже.', 'fs-lms-theme' ), 429 );
	}

	// Пустой токен — капча не загрузилась у посетителя, такие заявки идут под
	// общим лимитом ниже. Присланный, но неверный токен — отказ.
	if ( $captcha->is_configured() && ! $captcha_skipped ) {
		$captcha_result = $captcha->verify( $captcha_token, $ip );

		if ( FS_LMS_Theme_Smart_Captcha::FAILED === $captcha_result ) {
			$reject( 'captcha_failed', __( 'Проверка «Я не робот» не пройдена, попробуйте ещё раз.', 'fs-lms-theme' ), 400 );
		}
	}

	if ( '' === $name || '' === $phone ) {
		wp_send_json_error( array( 'message' => __( 'Заполните имя и телефон.', 'fs-lms-theme' ) ), 400 );
	}

	if ( ! $pd_consent ) {
		wp_send_json_error( array( 'message' => __( 'Подтвердите согласие на обработку персональных данных.', 'fs-lms-theme' ) ), 400 );
	}

	/**
	 * BugFix.4 (2026-09-05): имя — только кириллица (правило и его причины —
	 * у `FS_LMS_THEME_NAME_CHARS` выше). Проверка на сервере, а не только в
	 * разметке и в `src/js/forms.js`: `pattern`/`minlength` и клиентский JS
	 * обходятся прямым запросом к `admin-ajax.php`.
	 */
	if ( ! fs_lms_theme_is_valid_name( $name ) ) {
		$reject( 'name_format', __( 'В имени разрешены только буквы кириллицы, пробелы и дефис.', 'fs-lms-theme' ), 400 );
	}

	// 2026-10-09: одного имени мало — боты фамилию не вводят.
	if ( ! fs_lms_theme_lead_name_has_surname( $name ) ) {
		$reject( 'name_single_word', __( 'Укажите имя и фамилию.', 'fs-lms-theme' ), 400 );
	}

	if ( ! preg_match( '/^[\d\s()+\-]{5,20}$/u', $phone ) || ! fs_lms_theme_phone_looks_real( $phone ) ) {
		$reject( 'phone_format', __( 'Проверьте номер телефона.', 'fs-lms-theme' ), 400 );
	}

	// Второй рубеж после лимита по IP: один и тот же номер, даже с разных
	// адресов, принимаем не чаще, чем задано окном.
	$phone_digits = fs_lms_theme_phone_digits( $phone );

	if ( '' !== $phone_digits && fs_lms_theme_form_rate_limited( 'phone:' . $phone_digits, FS_LMS_THEME_FORM_PHONE_LIMIT, FS_LMS_THEME_FORM_PHONE_WINDOW ) ) {
		$reject( 'rate_phone', __( 'Слишком много попыток. Попробуйте немного позже.', 'fs-lms-theme' ), 429 );
	}

	// Считаем только прошедшие валидацию заявки, чтобы мусорные запросы не
	// выедали лимит у настоящих посетителей без капчи.
	if ( $captcha_skipped && fs_lms_theme_form_rate_limited( 'no-captcha', FS_LMS_THEME_FORM_NO_CAPTCHA_LIMIT, FS_LMS_THEME_FORM_NO_CAPTCHA_WINDOW ) ) {
		$reject( 'no_captcha_limit', __( 'Не получилось отправить заявку, попробуйте позже или позвоните нам: +7(995)326-44-86', 'fs-lms-theme' ), 429 );
	}

	$lines = array(
		sprintf( 'Имя: %s', $name ),
		sprintf( 'Телефон: %s', $phone ),
		sprintf( 'Форма: %s', fs_lms_theme_form_source( $form_id, $page_url ) ),
		'',
		'Техническая информация',
		sprintf( 'Получена: %s', wp_date( 'd.m.Y H:i:s T', $received_at ) ),
		sprintf( 'Согласие на обработку ПДн: %s', fs_lms_theme_form_consent_note() ),
		sprintf( 'Капча: %s', fs_lms_theme_form_captcha_note( $captcha->is_configured(), $captcha_skipped, $captcha_result, $captcha_challenge ) ),
		sprintf( 'Заполнение формы: %s', fs_lms_theme_form_fill_time( $fill_seconds ) ),
		sprintf( 'Устройство: %s', wp_is_mobile() ? 'телефон или планшет' : 'компьютер' ),
		sprintf( 'IP: %s', $ip ),
		sprintf( 'Браузер: %s', '' === $user_agent ? 'не передан' : $user_agent ),
	);

	// Причина сбоя письма — в лог сервера: посетителю её показывать незачем, а
	// понять, почему почта не работает, нужно.
	$mail_error = '';
	$on_failure = static function ( WP_Error $error ) use ( &$mail_error ): void {
		$mail_error = $error->get_error_message();
	};
	add_action( 'wp_mail_failed', $on_failure );

	$sent = wp_mail( FS_LMS_Theme_Showcase::settings()->get( 'leads_email' ), FS_LMS_THEME_FORM_MAIL_SUBJECT, implode( "\n", $lines ) );

	remove_action( 'wp_mail_failed', $on_failure );

	// Принятая заявка сохраняется независимо от исхода письма — она не должна теряться.
	$stored = $record( 'accepted', '', (bool) $sent );

	if ( ! $sent ) {
		error_log( sprintf( '[FS LMS Theme] Письмо с заявкой не отправлено: %s. Заявка %s.', '' !== $mail_error ? $mail_error : 'wp_mail() вернула false', $stored ? 'сохранена в «Пользователи → Заявки с сайта»' : 'НЕ сохранена' ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	// Заявка дошла до таблицы в админке — для посетителя всё в порядке, а письмо
	// чиним мы. Сообщаем об ошибке, только когда данные не ушли никуда.
	if ( ! $sent && ! $stored ) {
		wp_send_json_error(
			array( 'message' => __( 'Заявка не дошла до нас: на сервере не работает отправка почты. Позвоните нам: +7(995)326-44-86', 'fs-lms-theme' ) ),
			500
		);
	}

	wp_send_json_success( array( 'message' => __( 'Спасибо! Мы перезвоним в течение рабочего дня.', 'fs-lms-theme' ) ) );
}
