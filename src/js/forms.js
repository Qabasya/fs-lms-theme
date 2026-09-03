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
 */

export function initForms() {
	initPhoneMask();

	if ( typeof window.fsLmsTheme === 'undefined' ) {
		return;
	}

	document.querySelectorAll( 'form[data-fs-form]' ).forEach( ( form ) => {
		form.addEventListener( 'submit', ( event ) => {
			event.preventDefault();
			submitForm( form );
		} );
	} );
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

function submitForm( form ) {
	const message = form.querySelector( '.fs-form-message' );
	const submitButton = form.querySelector( 'button[type="submit"]' );

	if ( message ) {
		message.textContent = '';
		message.classList.remove( 'is-success', 'is-error' );
	}
	if ( submitButton ) {
		submitButton.disabled = true;
	}

	const formData = new FormData( form );
	formData.set( 'action', 'fs_theme_submit_form' );
	formData.set( 'nonce', window.fsLmsTheme.formNonce );
	formData.set( 'page_url', window.location.href );

	fetch( window.fsLmsTheme.ajaxUrl, {
		method: 'POST',
		body: formData,
	} )
		.then( ( response ) => response.json() )
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
		.catch( () => {
			showMessage( message, 'Не получилось отправить форму, проверьте соединение и попробуйте ещё раз.', false );
		} )
		.finally( () => {
			if ( submitButton ) {
				submitButton.disabled = false;
			}
		} );
}

function showMessage( element, text, success ) {
	if ( ! element ) {
		return;
	}

	element.textContent = text;
	element.classList.add( success ? 'is-success' : 'is-error' );
}
