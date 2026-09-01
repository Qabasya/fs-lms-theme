/**
 * Общий пикер картинки для редактора (course-card, teacher-card,
 * testimonial-card). Только edit-контекст — в save.js не импортируется:
 * там либо реальный <img>, либо .fs-placeholder-tile.
 */

import { createElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

export function ImageControl( { imageId, imageUrl, imageAlt, onSelect, onRemove, className } ) {
	return createElement(
		MediaUploadCheck,
		null,
		createElement( MediaUpload, {
			onSelect: ( media ) => onSelect( { imageId: media.id, imageUrl: media.url, imageAlt: media.alt || '' } ),
			allowedTypes: [ 'image' ],
			value: imageId,
			render: ( { open } ) => createElement(
				'div',
				{ className },
				imageUrl
					? createElement( 'img', { src: imageUrl, alt: imageAlt, onClick: open, role: 'button', tabIndex: 0, style: { cursor: 'pointer' } } )
					: createElement( Button, { variant: 'secondary', onClick: open }, __( 'Выбрать изображение', 'fs-lms-theme' ) ),
				imageUrl && createElement( Button, { variant: 'link', isDestructive: true, onClick: onRemove }, __( 'Удалить', 'fs-lms-theme' ) )
			),
		} )
	);
}
