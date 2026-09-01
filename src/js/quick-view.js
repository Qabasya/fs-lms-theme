/**
 * Quick view карточки товара WooCommerce (Фаза 10.5).
 *
 * Клик по `.fs-quick-view` (кнопка на карточке, см. `inc/WooCommerce.php`)
 * → AJAX-запрос на `fs_quick_view` → вставка HTML-ответа в модалку. Модуль
 * ничего не делает, если на странице нет карточек товаров — не нужен
 * условный enqueue per-page, theme.js уже грузится сайтвайд.
 */

export function initQuickView() {
	const trigger = document.querySelector( '.fs-quick-view' );
	if ( ! trigger || typeof window.fsLmsTheme === 'undefined' ) {
		return;
	}

	const modal = document.createElement( 'div' );
	modal.className = 'fs-quick-view-modal';
	modal.innerHTML = '<div class="fs-quick-view-modal__backdrop" data-quick-view-close></div>' +
		'<div class="fs-quick-view-modal__panel" role="dialog" aria-modal="true">' +
		'<button type="button" class="fs-quick-view-modal__close" data-quick-view-close aria-label="Закрыть">&times;</button>' +
		'<div class="fs-quick-view-modal__content"></div>' +
		'</div>';
	document.body.appendChild( modal );

	const content = modal.querySelector( '.fs-quick-view-modal__content' );

	function closeModal() {
		modal.classList.remove( 'is-open' );
	}

	function openModal() {
		modal.classList.add( 'is-open' );
	}

	modal.addEventListener( 'click', ( event ) => {
		if ( event.target.hasAttribute( 'data-quick-view-close' ) ) {
			closeModal();
		}
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( 'Escape' === event.key && modal.classList.contains( 'is-open' ) ) {
			closeModal();
		}
	} );

	document.addEventListener( 'click', ( event ) => {
		const button = event.target.closest( '.fs-quick-view' );
		if ( ! button ) {
			return;
		}

		event.preventDefault();
		event.stopPropagation();

		const productId = button.getAttribute( 'data-product-id' );
		content.innerHTML = '';
		openModal();

		const body = new URLSearchParams( {
			action: 'fs_quick_view',
			product_id: productId,
			nonce: window.fsLmsTheme.quickViewNonce,
		} );

		fetch( window.fsLmsTheme.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body,
		} )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				content.innerHTML = data && data.success ? data.data.html : '';
			} )
			.catch( () => {
				content.innerHTML = '';
			} );
	} );
}
