/**
 * Реальная отправка лид-форм темы (Фаза 14) — `#hero-form`
 * (`patterns/hero.php`/`subject-hero.php`) и `#signup`
 * (`patterns/contact-section.php`/`subject-contact.php`). Обе — обычные
 * `<form data-fs-form>` с honeypot/HMAC-таймером/капчей уже в разметке
 * (`inc/Forms.php` их туда кладёт через паттерн) — этот модуль только
 * перехватывает `submit` и шлёт `fetch` на `admin-ajax.php`
 * (`fs_theme_submit_form`), без перезагрузки страницы.
 *
 * BugFix.2 (2026-09-03): `initPhoneMask()` — маска +7 на всех полях
 * `input[name="phone"]` (не только внутри `[data-fs-form]` — на случай
 * форм без AJAX-обвязки в будущем), не зависит от `window.fsLmsTheme`.
 *
 * `initAnchorAutofocus()` (задача 3, 2026-09-04) — тоже не зависит от
 * `window.fsLmsTheme`: переход по якорю на форму записи ставит фокус на
 * поле «ФИО родителя», это чистый UX-хук, не связанный с AJAX-отправкой.
 *
 * BugFix (2026-09-12): на первом фокусе в форме — свежая метка времени
 * `fs_form_token` с сервера (метка из разметки в кэше страниц устаревает,
 * см. `fs_lms_theme_handle_form_token()` в `inc/Forms.php`) и прогрев
 * невидимой капчи (`captcha.js`).
 */

import { getCaptchaToken, isCaptchaDismissed, preloadCaptcha, resetCaptcha, wasCaptchaChallengeShown } from './captcha.js';

/**
 * Сервер не принимает форму раньше, чем через
 * `FS_LMS_THEME_FORM_MIN_FILL_SECONDS` после выдачи метки, мс. С запасом на
 * округление секунд на сервере.
 */
const MIN_FILL_TIME = 3200;

/** Метку старше этого перед отправкой берём заново (сервер держит час), мс. */
const TOKEN_REFRESH_AFTER = 30 * 60 * 1000;

const CAPTCHA_DISMISSED_MESSAGE = 'Подтвердите, что вы не робот, и отправьте форму ещё раз.';
const NETWORK_ERROR_MESSAGE = 'Не получилось отправить форму, проверьте соединение и попробуйте ещё раз.';

/** @type {WeakMap<HTMLFormElement, {issuedAt: number|null, request: Promise|null}>} */
const formTokens = new WeakMap();

export function initForms() {
	initPhoneMask();
	initAnchorAutofocus();

	if ( typeof window.fsLmsTheme === 'undefined' ) {
		return;
	}

	document.querySelectorAll( 'form[data-fs-form]' ).forEach( ( form ) => {
		form.addEventListener( 'focusin', () => {
			requestFormToken( form );
			preloadCaptcha( form );
		}, { once: true } );

		form.addEventListener( 'submit', ( event ) => {
			event.preventDefault();
			submitForm( form );
		} );
	} );
}

function getTokenState( form ) {
	if ( ! formTokens.has( form ) ) {
		formTokens.set( form, { issuedAt: null, request: null } );
	}

	return formTokens.get( form );
}

/**
 * Если запрос не удался, в форме остаётся метка из разметки — на
 * некэшированной странице она и так свежая.
 */
function requestFormToken( form ) {
	const state = getTokenState( form );

	if ( state.request ) {
		return state.request;
	}

	const body = new FormData();
	body.set( 'action', 'fs_theme_form_token' );

	state.request = fetch( window.fsLmsTheme.ajaxUrl, { method: 'POST', body } )
		.then( ( response ) => response.json() )
		.then( ( data ) => {
			if ( ! data || ! data.success ) {
				return;
			}

			const input = form.querySelector( 'input[name="fs_form_token"]' );

			if ( input ) {
				input.value = data.data.token;
			}

			window.fsLmsTheme.formNonce = data.data.nonce;
			state.issuedAt = window.performance.now();
		} )
		.catch( () => {} )
		.finally( () => {
			state.request = null;
		} );

	return state.request;
}

function ensureFreshToken( form ) {
	const state = getTokenState( form );
	const isStale = null === state.issuedAt || window.performance.now() - state.issuedAt > TOKEN_REFRESH_AFTER;

	return state.request || ( isStale ? requestFormToken( form ) : Promise.resolve() );
}

/**
 * Метку выдали только что (автозаполнение и сразу «Отправить») — ждём
 * остаток минимального времени, вместо того чтобы получить отказ сервера.
 */
function waitMinFillTime( form ) {
	const { issuedAt } = getTokenState( form );
	const remaining = null === issuedAt ? 0 : MIN_FILL_TIME - ( window.performance.now() - issuedAt );

	return remaining > 0
		? new Promise( ( resolve ) => window.setTimeout( resolve, remaining ) )
		: Promise.resolve();
}

/**
 * Нормализует ввод к формату «+7 (999) 123-45-67». Первый символ «8»/«7»
 * трактуется как код страны и заменяется на «+7», остальное — цифры
 * пользователя (макс. 10 после кода). Пересчитывается с нуля на каждый
 * `input` (учитывает paste и backspace без отдельной обработки).
 */
export function formatPhoneValue( raw ) {
	let digits = raw.replace( /\D/g, '' );

	if ( ! digits ) {
		return '';
	}

	if ( digits[ 0 ] === '8' || digits[ 0 ] === '7' ) {
		digits = '7' + digits.slice( 1 );
	} else {
		digits = '7' + digits;
	}
	digits = digits.slice( 0, 11 );

	const rest = digits.slice( 1 );
	let result = '+7';

	if ( rest.length > 0 ) {
		result += ' (' + rest.slice( 0, 3 );
	}
	if ( rest.length >= 3 ) {
		result += ')';
	}
	if ( rest.length > 3 ) {
		result += ' ' + rest.slice( 3, 6 );
	}
	if ( rest.length > 6 ) {
		result += '-' + rest.slice( 6, 8 );
	}
	if ( rest.length > 8 ) {
		result += '-' + rest.slice( 8, 10 );
	}

	return result;
}

/**
 * Задача 3 (tasks.md, 2026-09-04): переход по якорю на форму записи
 * (`#hero-form`/`#signup` — кнопки «Записаться» в шапке/секциях,
 * `patterns/hero.php`/`subject-hero*.php`/`contact-section.php`/
 * `subject-contact.php`/`courses-contact.php`) — ставит фокус на поле
 * «ФИО родителя» (`input[name="parent_name"]`), а не только скроллит к
 * форме. Ловит и клик по ссылке на этой же странице, и заход по прямой
 * ссылке с хэшем (`/#hero-form` с другой страницы, см. задачу 9).
 * Задержка перед фокусом — под длительность плавного скролла
 * (`scroll-behavior: smooth`, `theme.scss`): фокус раньше окончания
 * скролла сбивает позицию прокрутки в некоторых браузерах.
 */
function initAnchorAutofocus() {
	document.querySelectorAll( 'a[href^="#"]' ).forEach( ( link ) => {
		link.addEventListener( 'click', () => {
			focusFormNameField( link.getAttribute( 'href' ) );
		} );
	} );

	if ( window.location.hash ) {
		focusFormNameField( window.location.hash );
	}
}

function focusFormNameField( hash ) {
	let target;

	try {
		target = document.querySelector( hash );
	} catch {
		return;
	}

	if ( ! target ) {
		return;
	}

	const form = target.matches( 'form' ) ? target : target.querySelector( 'form[data-fs-form]' );
	const nameField = form && form.querySelector( 'input[name="parent_name"]' );

	if ( ! nameField ) {
		return;
	}

	window.setTimeout( () => nameField.focus(), 500 );
}

function initPhoneMask() {
	document.querySelectorAll( 'input[name="phone"]' ).forEach( ( input ) => {
		input.addEventListener( 'input', () => {
			const atEnd = input.selectionStart === input.value.length;
			input.value = formatPhoneValue( input.value );
			if ( atEnd ) {
				input.setSelectionRange( input.value.length, input.value.length );
			}
		} );
	} );
}

/**
 * BugFix.4 (2026-09-05): имя — только кириллица, пробелы и дефис. То же
 * правило, что у плагина fs-lms
 * (`src/js/common/validators/CyrillicNameValidator.js`) и у серверной
 * проверки в `inc/Forms.php` — здесь оно только для того, чтобы ошибка
 * показывалась сразу, без обращения к серверу.
 */
const NAME_PATTERN = /^[А-Яа-яЁё\s-]{2,80}$/u;
const NAME_ERROR = 'В имени разрешены только буквы кириллицы, пробелы и дефис.';

export function validateNameField( value ) {
	return NAME_PATTERN.test( value.trim() );
}

function submitForm( form ) {
	const message = form.querySelector( '.fs-form-message' );
	const submitButton = form.querySelector( 'button[type="submit"]' );
	const nameField = form.querySelector( 'input[name="parent_name"]' );

	if ( message ) {
		message.textContent = '';
		message.classList.remove( 'is-success', 'is-error' );
	}

	if ( nameField && ! validateNameField( nameField.value ) ) {
		showMessage( message, NAME_ERROR, false );
		nameField.focus();

		return;
	}

	if ( submitButton ) {
		submitButton.disabled = true;
	}

	ensureFreshToken( form )
		.then( () => waitMinFillTime( form ) )
		.then( () => getCaptchaToken( form ) )
		.then( ( captchaToken ) => sendForm( form, captchaToken ) )
		.then( ( data ) => {
			const success = Boolean( data && data.success );
			const text = data && data.data && data.data.message
				? data.data.message
				: success
					? 'Спасибо! Мы скоро свяжемся с вами.'
					: 'Не получилось отправить форму, попробуйте ещё раз.';

			showMessage( message, text, success );

			if ( success ) {
				form.reset();
			}
		} )
		.catch( ( error ) => {
			showMessage( message, isCaptchaDismissed( error ) ? CAPTCHA_DISMISSED_MESSAGE : NETWORK_ERROR_MESSAGE, false );
		} )
		.finally( () => {
			resetCaptcha( form );

			if ( submitButton ) {
				submitButton.disabled = false;
			}
		} );
}

/**
 * Пустой `smart-token` сервер понимает как «капча не загрузилась». Поле
 * задаём явно: виджет Яндекса держит в форме своё скрытое поле с тем же
 * именем, и там может остаться токен прошлой отправки.
 */
function sendForm( form, captchaToken ) {
	const formData = new FormData( form );
	formData.set( 'action', 'fs_theme_submit_form' );
	formData.set( 'nonce', window.fsLmsTheme.formNonce );
	formData.set( 'page_url', window.location.href );
	formData.set( 'smart-token', captchaToken );
	formData.set( 'captcha_challenge', wasCaptchaChallengeShown( form ) ? '1' : '0' );

	return fetch( window.fsLmsTheme.ajaxUrl, {
		method: 'POST',
		body: formData,
	} ).then( ( response ) => response.json() );
}

function showMessage( element, text, success ) {
	if ( ! element ) {
		return;
	}

	element.textContent = text;
	element.classList.add( success ? 'is-success' : 'is-error' );
}
