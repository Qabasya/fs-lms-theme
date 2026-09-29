/**
 * Плавное раскрытие аккордеонов на `<details>` там, где CSS не справляется
 * (2026-09-29).
 *
 * Основной путь — CSS (`::details-content` + `interpolate-size`, см.
 * BugFix.7 в `theme.scss`). В Safari и Firefox `interpolate-size` нет, а
 * запасной `max-block-size` в Safari не анимировался — на iPhone пункты
 * открывались скачком. Там анимируем высоту самого `<details>` через Web
 * Animations API: перехватываем клик по `<summary>`, меряем высоту до и
 * после и проигрываем переход между ними. Клавиатура тоже покрыта — Enter
 * и пробел на `<summary>` приходят как `click`.
 */

const SELECTOR = '.fs-faq-item, .fs-legal-accordion__item';
const DURATION = 250;
const EASING = 'ease';
const CLOSING_CLASS = 'is-closing';

const running = new WeakMap();

/**
 * Высота свёрнутого пункта: `<summary>` плюс рамки самого `<details>`.
 *
 * @param {HTMLDetailsElement} details
 * @param {HTMLElement}        summary
 * @return {number} Высота в px.
 */
function closedHeight( details, summary ) {
	const style = getComputedStyle( details );

	return (
		summary.offsetHeight +
		parseFloat( style.borderTopWidth ) +
		parseFloat( style.borderBottomWidth ) +
		parseFloat( style.paddingTop ) +
		parseFloat( style.paddingBottom )
	);
}

/**
 * @param {HTMLDetailsElement} details
 * @param {HTMLElement}        summary
 */
function toggle( details, summary ) {
	const startHeight = details.getBoundingClientRect().height;
	const closing = details.open && ! details.classList.contains( CLOSING_CLASS );

	running.get( details )?.cancel();

	let endHeight;

	if ( closing ) {
		details.classList.add( CLOSING_CLASS );
		endHeight = closedHeight( details, summary );
	} else {
		details.classList.remove( CLOSING_CLASS );
		details.open = true;
		endHeight = details.getBoundingClientRect().height;
	}

	details.style.overflow = 'hidden';

	const animation = details.animate(
		{ height: [ `${ startHeight }px`, `${ endHeight }px` ] },
		{ duration: DURATION, easing: EASING }
	);

	running.set( details, animation );

	animation.onfinish = () => {
		if ( closing ) {
			details.open = false;
			details.classList.remove( CLOSING_CLASS );
		}

		details.style.overflow = '';
		running.delete( details );
	};
}

export function initDetailsAccordion() {
	if (
		typeof Element.prototype.animate !== 'function' ||
		( window.CSS?.supports && CSS.supports( 'interpolate-size', 'allow-keywords' ) )
	) {
		return;
	}

	document.documentElement.classList.add( 'fs-details-js' );

	const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	document.addEventListener( 'click', ( event ) => {
		const summary = event.target.closest?.( 'summary' );
		const details = summary?.parentElement;

		if (
			! details ||
			! details.matches( SELECTOR ) ||
			details.querySelector( ':scope > summary' ) !== summary ||
			reducedMotion.matches
		) {
			return;
		}

		event.preventDefault();
		toggle( details, summary );
	} );
}
