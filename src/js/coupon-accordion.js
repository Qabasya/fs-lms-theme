/**
 * Купон в корзине — аккордеон, как в макетах.
 *
 * В `cart/cart.php` поле купона лежит прямо в строке действий таблицы, без
 * заголовка и без сворачивания (на оформлении заказа переключатель есть
 * штатно — там мы только переносим блок под поля покупателя и красим его,
 * см. `inc/Checkout.php` и `_woocommerce.scss`). Здесь оборачиваем
 * существующий блок в свой аккордеон: сам `.coupon` не пересоздаём, а
 * переносим внутрь панели — он остаётся внутри `<form>` корзины, поэтому
 * поле по-прежнему уходит в POST и купон применяется штатно.
 */

const TOGGLE_LABEL = 'Есть купон?';

export function initCouponAccordion() {
	const coupon = document.querySelector( '.woocommerce-cart-form .actions .coupon' );

	if ( ! coupon || coupon.closest( '.fs-coupon' ) ) {
		return;
	}

	const accordion = document.createElement( 'div' );
	accordion.className = 'fs-coupon';

	const toggle = document.createElement( 'button' );
	toggle.type = 'button';
	toggle.className = 'fs-coupon__toggle';
	toggle.setAttribute( 'aria-expanded', 'false' );
	toggle.textContent = TOGGLE_LABEL;

	const panel = document.createElement( 'div' );
	panel.className = 'fs-coupon__panel';
	panel.hidden = true;

	coupon.parentNode.insertBefore( accordion, coupon );
	accordion.append( toggle, panel );
	panel.append( coupon );

	toggle.addEventListener( 'click', () => {
		const expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';

		toggle.setAttribute( 'aria-expanded', String( ! expanded ) );
		panel.hidden = expanded;
	} );
}
