/**
 * Поле «картинка из медиатеки» в метабоксах `inc/Showcase/*` (этап 3,
 * 2026-09-13) — картинка каталога у «Направлений». Разметка поля —
 * `.fs-media-field` в `FS_LMS_Theme_Directions::render_cards_box()`: скрытый
 * input с ID вложения, превью и кнопки «Выбрать»/«Убрать».
 *
 * Лежит рядом с PHP, а не в `src/js/`: админке не нужна сборка темы, и файл
 * попадает в релиз как есть (`assets/` собирается только для фронта).
 */
( function () {
	'use strict';

	function init( field ) {
		const input = field.querySelector( '.fs-media-field__input' );
		const preview = field.querySelector( '.fs-media-field__preview' );
		const selectButton = field.querySelector( '.fs-media-field__select' );
		const removeButton = field.querySelector( '.fs-media-field__remove' );
		let frame = null;

		selectButton.addEventListener( 'click', function () {
			if ( ! frame ) {
				frame = window.wp.media( {
					title: field.dataset.title,
					button: { text: field.dataset.button },
					library: { type: 'image' },
					multiple: false,
				} );

				frame.on( 'select', function () {
					const attachment = frame.state().get( 'selection' ).first().toJSON();
					const size = attachment.sizes && ( attachment.sizes.medium || attachment.sizes.full );
					const image = document.createElement( 'img' );

					image.src = size ? size.url : attachment.url;
					image.alt = '';
					image.style.maxWidth = '100%';
					image.style.height = 'auto';

					input.value = String( attachment.id );
					preview.replaceChildren( image );
					removeButton.hidden = false;
				} );
			}

			frame.open();
		} );

		removeButton.addEventListener( 'click', function () {
			input.value = '0';
			preview.replaceChildren();
			removeButton.hidden = true;
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( '.fs-media-field' ).forEach( init );
	} );
}() );
