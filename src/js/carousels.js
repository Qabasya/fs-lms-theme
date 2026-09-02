/**
 * Карусели на Splide (Фаза 12.0) — «Наши выпускники» и «Наши выпускники
 * поступают» (Фаза 12.3/12.4) используют один и тот же generic-инициализатор:
 * разметка сама объявляет число слайдов/автопрокрутку через data-атрибуты,
 * а не через отдельный JS-модуль на каждую секцию.
 *
 * Разметка: `<div data-fs-carousel data-per-page="3" data-autoplay="true">`,
 * внутри — `.splide__track > .splide__list > .splide__slide` (Splide сам
 * оборачивает содержимое по этой структуре, см. https://splidejs.com/).
 */

import Splide from '@splidejs/splide';

export function initCarousels() {
	document.querySelectorAll( '[data-fs-carousel]' ).forEach( ( el ) => {
		const perPage = parseInt( el.dataset.perPage || '1', 10 );
		const perPageTablet = Math.min( perPage, 2 );

		new Splide( el, {
			type: 'loop',
			perPage,
			gap: '1.25rem',
			pagination: false,
			arrows: el.dataset.arrows !== 'false',
			autoplay: el.dataset.autoplay === 'true',
			interval: 3000,
			pauseOnHover: true,
			breakpoints: {
				991: { perPage: perPageTablet, gap: '1rem' },
				767: { perPage: 1, gap: '0.75rem' },
			},
		} ).mount();
	} );
}
