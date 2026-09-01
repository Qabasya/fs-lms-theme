/**
 * Небольшой встроенный набор иконок для fs-lms/feature-card — не полноценная
 * библиотека, а фиксированный список, покрывающий секции мокапа главной
 * (группы, запись занятий, материалы, результат, гарантия, консультация).
 * Загрузка своей SVG-иконки — за рамками Фазы 4 (см. tasks.md backlog).
 */

import { createElement } from '@wordpress/element';

const PATHS = {
	users: [
		'M7.5 9.5A2.5 2.5 0 1 0 7.5 4.5 2.5 2.5 0 0 0 7.5 9.5Z',
		'M3 18c0-2.5 2-4.5 4.5-4.5S12 15.5 12 18',
		'M14 6.7a2.5 2.5 0 0 1 0 4.6',
		'M17 18c0-2-1.2-3.7-3-4.3',
	],
	record: [
		'M2.5 6h10v8h-10z',
		'M12.5 9.2 17 6.5v7l-4.5-2.7z',
	],
	document: [
		'M5 2.5h6.2L15.5 6.8V17.5H5V2.5z',
		'M11 3v4.3h4.3',
	],
	spark: [
		'M10 3 12 7l4.5.6-3.3 3.2.8 4.5L10 13.2 6 15.5l.8-4.5L3.5 7.7 8 7z',
	],
	shield: [
		'M10 2.5 16 5v5c0 4-2.6 6.7-6 8-3.4-1.3-6-4-6-8V5z',
	],
	chat: [
		'M3 4.5h14v9H8l-3 3v-3H3z',
	],
};

export const ICON_OPTIONS = [
	{ label: 'Группа', value: 'users' },
	{ label: 'Запись занятий', value: 'record' },
	{ label: 'Материалы', value: 'document' },
	{ label: 'Результат', value: 'spark' },
	{ label: 'Гарантия', value: 'shield' },
	{ label: 'Консультация', value: 'chat' },
];

export function FeatureIcon( { icon } ) {
	const paths = PATHS[ icon ] || PATHS.users;

	return createElement(
		'svg',
		{ width: 19, height: 19, viewBox: '0 0 20 20', fill: 'none', 'aria-hidden': 'true' },
		paths.map( ( d, index ) => createElement( 'path', {
			key: index,
			d,
			stroke: 'currentColor',
			strokeWidth: 1.5,
			strokeLinejoin: 'round',
			strokeLinecap: 'round',
		} ) )
	);
}
