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
 *   fs-carousel--per-<N>     число слайдов на десктопе (по умолчанию 1)
 *   fs-carousel--auto-width  ширина слайда — по его содержимому
 *   fs-carousel--autoplay    автопрокрутка
 *   fs-carousel--no-arrows   спрятать стрелки
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

		/**
		 * `fs-carousel--auto-width` — ширину слайда задаёт его содержимое,
		 * а не число слайдов на экран. Нужен полосе логотипов вузов
		 * (`patterns/alumni-strip.php`): у логотипов фиксированная высота и
		 * своя пропорция, поэтому и слайды должны быть разной ширины —
		 * при фиксированном `perPage` широкий логотип упирался в границу
		 * слайда и уменьшался по высоте.
		 */
		const autoWidth = el.classList.contains( 'fs-carousel--auto-width' );

		const options = {
			type: 'loop',

			/**
			 * BugFix.2 (2026-09-05): без явного `perMove` Splide берёт шаг
			 * равным `perPage` — карусель выпускников листала сразу по три
			 * карточки, и это читалось как «прыжок через несколько».
			 * Прокрутка везде по одной карточке.
			 */
			perMove: 1,
			gap: '1.25rem',
			pagination: false,
			arrows: readFlag( el, 'arrows', 'fs-carousel--no-arrows', true ),
			autoplay: readFlag( el, 'autoplay', 'fs-carousel--autoplay', false ),
			interval: 3000,
			pauseOnHover: true,
		};

		if ( autoWidth ) {
			options.autoWidth = true;
			options.breakpoints = { 767: { gap: '0.75rem' } };
		} else {
			options.perPage = perPage;
			options.breakpoints = {
				991: { perPage: perPageTablet, gap: '1rem' },
				767: { perPage: 1, gap: '0.75rem' },
			};

			/**
			 * BugFix.2 (2026-09-05): в `type: 'loop'` Splide сам считает,
			 * сколько слайдов клонировать по краям, и этого хватает, только
			 * пока слайдов не меньше `perPage * 2`. У «Наших выпускников»
			 * их 5 при `perPage: 3` — клонов не хватало на полный оборот,
			 * и вместо шага на одну карточку лента перескакивала через
			 * несколько, чтобы вернуться к началу. Задаём число клонов
			 * явно: `perPage` с каждой стороны хватает на любой набор.
			 */
			const slides = el.querySelectorAll( '.splide__slide' ).length;

			if ( slides > 0 && slides < perPage * 2 ) {
				options.clones = perPage;
			}
		}

		new Splide( el, options ).mount();
	} );
}
