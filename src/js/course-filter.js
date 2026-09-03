/**
 * Фильтр по классу на странице «Курсы» (Фаза 16.5, `patterns/courses-catalog.php`).
 * Чипсы `.fs-course-filter__chip[data-filter]` показывают/прячут карточки
 * `.fs-course-catalog-card[data-grade]` в той же секции — без бэкенда,
 * карточек всего 4 и все они на странице сразу.
 */

export function initCourseFilter() {
	document.querySelectorAll( '[data-fs-course-filter]' ).forEach( ( filterEl ) => {
		const cards = document.querySelectorAll( '.fs-course-catalog-card' );

		filterEl.addEventListener( 'click', ( event ) => {
			const chip = event.target.closest( '.fs-course-filter__chip' );
			if ( ! chip ) {
				return;
			}

			filterEl.querySelectorAll( '.fs-course-filter__chip' ).forEach( ( el ) => {
				el.classList.toggle( 'is-active', el === chip );
			} );

			const grade = chip.dataset.filter;
			cards.forEach( ( card ) => {
				card.hidden = 'all' !== grade && card.dataset.grade !== grade;
			} );
		} );
	} );
}
