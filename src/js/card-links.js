/**
 * Кликабельная карточка целиком (tasks.md, новый список, п.3).
 *
 * Раньше это делал приём stretched-link: `::after` кнопки растягивался на
 * всю карточку. Побочный эффект — карточка целиком считалась «кнопкой»,
 * поэтому кнопка темнела при наведении в любую её точку, а собственного
 * hover-эффекта у карточки быть не могло. Теперь клик по карточке ведёт
 * туда же, куда её основная кнопка, а `:hover` кнопки срабатывает только
 * на самой кнопке.
 *
 * Клики по настоящим ссылкам/кнопкам внутри карточки не перехватываем, и
 * выделение текста мышью тоже не превращаем в переход.
 */

const CARD_SELECTORS = '.fs-course-card, .fs-course-catalog-card';
const BUTTON_SELECTOR = '.fs-course-card__button, .fs-course-catalog-card__button';

export function initCardLinks() {
	document.querySelectorAll( CARD_SELECTORS ).forEach( ( card ) => {
		const button = card.querySelector( BUTTON_SELECTOR );

		if ( ! button || ! button.href ) {
			return;
		}

		card.style.cursor = 'pointer';

		card.addEventListener( 'click', ( event ) => {
			if ( event.target.closest( 'a, button, input, label' ) ) {
				return;
			}

			const selection = window.getSelection();
			if ( selection && selection.toString() ) {
				return;
			}

			button.click();
		} );
	} );
}
