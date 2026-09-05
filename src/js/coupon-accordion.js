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

/**
 * Оформление заказа: заголовок «Есть купон?»
 * (`.woocommerce-form-coupon-toggle`) и сама форма (`form.checkout_coupon`) —
 * два соседних блока, которые шаблон печатает подряд. В сетке страницы это
 * две разные ячейки, между ними стоит `row-gap`, и карточка аккордеона
 * разъезжалась пополам — тем сильнее, чем выше карточка заказа справа
 * (она растянута на эти же строки и раздаёт им лишнюю высоту).
 *
 * Переносим форму внутрь заголовка: это соседний узел того же уровня, форма
 * НЕ попадает внутрь `form.checkout` (вложенные формы браузер выбрасывает,
 * см. `inc/Checkout.php`), а штатный JS WooCommerce ищет её глобально
 * (`$( '.checkout_coupon' ).slideToggle()`), поэтому применение купона
 * продолжает работать.
 */
function moveCheckoutCouponForm() {
	const toggle = document.querySelector( '.woocommerce-form-coupon-toggle' );
	const form = document.querySelector( 'form.checkout_coupon' );

	if ( ! toggle || ! form || toggle.contains( form ) ) {
		return;
	}

	toggle.append( form );
}

export function initCouponAccordion() {
	moveCheckoutCouponForm();

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

	/**
	 * BugFix.7 (2026-09-05): раскрытие плавное, поэтому состояние держит
	 * только `aria-expanded` кнопки, а не `hidden` панели: `hidden` — это
	 * `display: none`, между ним и обычным потоком анимации нет. Панель
	 * закрыта нулевой строкой грида (`_woocommerce.scss`), от скринридеров
	 * её прячет `visibility: hidden` там же.
	 *
	 * Внутренняя обёртка нужна той же раскладке: строку грида схлопывает до
	 * нуля высота ЭЛЕМЕНТА, а отступы содержимого обрезает уже её
	 * `overflow: hidden`.
	 */
	const panel = document.createElement( 'div' );
	panel.className = 'fs-coupon__panel';

	const panelInner = document.createElement( 'div' );
	panelInner.className = 'fs-coupon__panel-inner';

	coupon.parentNode.insertBefore( accordion, coupon );
	accordion.append( toggle, panel );
	panel.append( panelInner );
	panelInner.append( coupon );

	toggle.addEventListener( 'click', () => {
		const expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';

		toggle.setAttribute( 'aria-expanded', String( ! expanded ) );
	} );
}
