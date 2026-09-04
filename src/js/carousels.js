/**
 * Карусели на Splide (Фаза 12.0) — «Наши выпускники» и «Наши выпускники
 * поступают» (Фаза 12.3/12.4) используют один и тот же generic-инициализатор.
 *
 * Разметка: `<div class="splide fs-carousel ...">`, внутри —
 * `.splide__track > .splide__list > .splide__slide` (структура Splide,
 * см. https://splidejs.com/).
 *
 * Фаза 17.3: настройки читаются из классов-модификаторов, а не только из
 * `data-`-атрибутов. Причина — переход секций с сырого `wp:html` на
 * обычные блоки `wp:group`: у группы в редакторе можно задать
 * дополнительный CSS-класс (панель «Дополнительно»), а произвольный
 * `data-`-атрибут — нельзя. Старые `data-`-атрибуты по-прежнему
 * поддерживаются и имеют приоритет — разметка, написанная руками, не
 * ломается.
 *
 *   fs-carousel--per-<N>   число слайдов на десктопе (по умолчанию 1)
 *   fs-carousel--autoplay  автопрокрутка
 *   fs-carousel--no-arrows спрятать стрелки
 */

import Splide from '@splidejs/splide';

function readPerPage( el ) {
	if ( el.dataset.perPage ) {
		return parseInt( el.dataset.perPage, 10 );
	}

	const modifier = [ ...el.classList ].find( ( name ) =>
		name.startsWith( 'fs-carousel--per-' )
	);

	return modifier ? parseInt( modifier.replace( 'fs-carousel--per-', '' ), 10 ) : 1;
}

function readFlag( el, dataKey, className, fallback ) {
	if ( el.dataset[ dataKey ] ) {
		return el.dataset[ dataKey ] === 'true';
	}

	return el.classList.contains( className ) ? ! fallback : fallback;
}

export function initCarousels() {
	const selector = '[data-fs-carousel], .fs-carousel';

	document.querySelectorAll( selector ).forEach( ( el ) => {
		const perPage = readPerPage( el ) || 1;
		const perPageTablet = Math.min( perPage, 2 );

		new Splide( el, {
			type: 'loop',
			perPage,
			gap: '1.25rem',
			pagination: false,
			arrows: readFlag( el, 'arrows', 'fs-carousel--no-arrows', true ),
			autoplay: readFlag( el, 'autoplay', 'fs-carousel--autoplay', false ),
			interval: 3000,
			pauseOnHover: true,
			breakpoints: {
				991: { perPage: perPageTablet, gap: '1rem' },
				767: { perPage: 1, gap: '0.75rem' },
			},
		} ).mount();
	} );
}
