/**
 * Задача 8 (tasks.md, 2026-09-04): липкая шапка — скрывается при
 * прокрутке вниз, появляется при прокрутке вверх. Шапка уже `position:
 * sticky` (`theme.scss`, `.fs-site-header`), здесь только переключение
 * класса `is-hidden` (`transform: translateY(-100%)`) по направлению
 * скролла. У самого верха страницы (`scrollY` меньше высоты шапки) не
 * прячем — иначе шапка дёргается на границе `sticky`-точки.
 */

const HIDE_CLASS = 'is-hidden';
const MIN_DELTA = 8;

export function initHeaderScroll() {
	const header = document.querySelector( '.fs-site-header' );

	if ( ! header ) {
		return;
	}

	let lastScrollY = window.scrollY;
	let ticking = false;

	window.addEventListener( 'scroll', () => {
		if ( ticking ) {
			return;
		}

		ticking = true;
		window.requestAnimationFrame( () => {
			const currentScrollY = window.scrollY;
			const delta = currentScrollY - lastScrollY;

			if ( currentScrollY <= header.offsetHeight ) {
				header.classList.remove( HIDE_CLASS );
			} else if ( delta > MIN_DELTA ) {
				header.classList.add( HIDE_CLASS );
			} else if ( delta < -MIN_DELTA ) {
				header.classList.remove( HIDE_CLASS );
			}

			lastScrollY = currentScrollY;
			ticking = false;
		} );
	}, { passive: true } );
}
