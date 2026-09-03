/**
 * Реальная отправка лид-форм темы (Фаза 14) — `#hero-form`
 * (`patterns/hero.php`/`subject-hero.php`) и `#signup`
 * (`patterns/contact-section.php`/`subject-contact.php`). Обе — обычные
 * `<form data-fs-form>` с honeypot/HMAC-таймером/капчей уже в разметке
 * (`inc/Forms.php` их туда кладёт через паттерн) — этот модуль только
 * перехватывает `submit` и шлёт `fetch` на `admin-ajax.php`
 * (`fs_theme_submit_form`), без перезагрузки страницы.
 */

export function initForms() {
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
