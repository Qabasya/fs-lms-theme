/**
 * Рендер звёзд рейтинга (fs-lms/testimonial-card) — общая функция для
 * edit.js и save.js, чтобы разметка совпадала.
 */

import { createElement } from '@wordpress/element';

export function Rating( { value } ) {
	const filled = '★'.repeat( value );
	const empty = '☆'.repeat( 5 - value );

	return createElement(
		'div',
		{ className: 'fs-testimonial-card__rating', 'aria-hidden': 'true' },
		filled + empty
	);
}
