/**
 * Фильтр по классу на странице «Курсы» (Фаза 16.5, `patterns/courses-catalog.php`).
 * Чипсы показывают/прячут карточки `.fs-course-catalog-card[data-grade]` в
 * той же секции — без бэкенда, карточек всего 4 и все они на странице сразу.
 *
 * Фаза 17.3: чипсы — обычные кнопки Gutenberg (`wp:button`), а значение
 * фильтра лежит в адресе ссылки: `#grade-11` → `11`, `#grade-all` →
 * показать все. Так редактор правит подписи/набор чипсов штатным
 * интерфейсом кнопок, без сырого HTML и без своего блока. Старая разметка
 * (`<button class="fs-course-filter__chip" data-filter="…">`) продолжает
 * работать — значение берётся из `data-filter`, если оно есть.
 */

const GRADE_PREFIX = '#grade-';

function chipGrade( chip ) {
	if ( chip.dataset.filter ) {
		return chip.dataset.filter;
	}

	const href = chip.getAttribute( 'href' ) || '';

	return href.startsWith( GRADE_PREFIX ) ? href.slice( GRADE_PREFIX.length ) : '';
}

export function initCourseFilter() {
	const containers = document.querySelectorAll(
		'[data-fs-course-filter], .fs-course-filter'
	);

	containers.forEach( ( filterEl ) => {
		const chipSelector = `.fs-course-filter__chip, a[href^="${ GRADE_PREFIX }"]`;
		const cards = document.querySelectorAll( '.fs-course-catalog-card' );

		filterEl.addEventListener( 'click', ( event ) => {
			const chip = event.target.closest( chipSelector );

			if ( ! chip || ! filterEl.contains( chip ) ) {
				return;
			}

			// Чипс-ссылка не должна прыгать по якорю — фильтруем на месте.
			event.preventDefault();

			filterEl.querySelectorAll( chipSelector ).forEach( ( el ) => {
				el.classList.toggle( 'is-active', el === chip );
			} );

			const grade = chipGrade( chip );

			cards.forEach( ( card ) => {
				card.hidden = 'all' !== grade && card.dataset.grade !== grade;
			} );
		} );
	} );
}
