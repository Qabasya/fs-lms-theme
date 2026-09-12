/**
 * Невидимая Yandex SmartCaptcha лид-форм темы (BugFix, 2026-09-12).
 *
 * По образцу капчи формы заявки плагина fs-lms
 * (`src/js/frontend/services/captcha.js`): виджет рендерится с
 * `invisible: true`, токен запрашивается на отправке через `execute()`, а
 * задание Яндекс показывает только при подозрении на бота. Отличие от
 * плагина — форм на странице может быть несколько (на главной их две),
 * поэтому у каждой формы свой виджет.
 *
 * Скрипт Яндекса вставляется отсюда, а не через `wp_enqueue_script`:
 * `captcha.js` строит адрес iframe от собственного `src`, и после
 * минификации WP Rocket (скрипт отдавался с домена сайта) iframe открывал
 * 404-страницу сайта. Скрипт, вставленный из JS, оптимизаторы HTML не
 * переписывают.
 *
 * Если капча не загрузилась (блокировщик, сеть), `getCaptchaToken()` отдаёт
 * пустую строку — форма уходит без токена, и сервер принимает её под
 * отдельным лимитом (`FS_LMS_THEME_FORM_NO_CAPTCHA_LIMIT`, `inc/Forms.php`).
 * Закрытое без решения задание — другое дело: промис отклоняется, форма не
 * отправляется (`isCaptchaDismissed()`).
 */

const SCRIPT_SRC = 'https://smartcaptcha.yandexcloud.net/captcha.js';
const ONLOAD_CALLBACK = '__fsThemeSmartCaptchaReady';

/** Сколько ждать загрузки скрипта Яндекса, мс. */
const LOAD_TIMEOUT = 8000;

/**
 * Сколько ждать токена после `execute()`, пока задание не показано, мс.
 * Показанное задание таймер снимает: решать его человек может сколько угодно.
 */
const EXECUTE_TIMEOUT = 10000;

/**
 * Задание закрывается и после успешного решения — даём токену дойти до
 * `callback`, прежде чем считать задание брошенным, мс.
 */
const DISMISS_GRACE = 300;

const DISMISSED = 'captcha-dismissed';

/** @type {Promise<object>|null} */
let loading = null;

/** @type {WeakMap<HTMLFormElement, {id: number, pending: object|null}>} */
const widgets = new WeakMap();

export function isCaptchaEnabled() {
	return Boolean( window.fsLmsTheme && window.fsLmsTheme.captchaSiteKey );
}

export function isCaptchaDismissed( error ) {
	return Boolean( error ) && error.message === DISMISSED;
}

function hasApi() {
	return Boolean( window.smartCaptcha ) && typeof window.smartCaptcha.render === 'function';
}

/**
 * Скрипт на странице уже может быть — у формы заявки плагина fs-lms свой.
 * Второй экземпляр Яндекс не поддерживает, поэтому ждём, пока загрузится
 * тот, а свой не вставляем.
 */
function waitForForeignScript( resolve, reject ) {
	const startedAt = Date.now();

	const check = () => {
		if ( hasApi() ) {
			resolve( window.smartCaptcha );
		} else if ( Date.now() - startedAt > LOAD_TIMEOUT ) {
			reject( new Error( 'captcha-timeout' ) );
		} else {
			window.setTimeout( check, 200 );
		}
	};

	check();
}

function loadScript() {
	if ( hasApi() ) {
		return Promise.resolve( window.smartCaptcha );
	}

	if ( loading ) {
		return loading;
	}

	loading = new Promise( ( resolve, reject ) => {
		if ( document.querySelector( `script[src^="${ SCRIPT_SRC }"]` ) ) {
			waitForForeignScript( resolve, reject );
			return;
		}

		const timer = window.setTimeout( () => reject( new Error( 'captcha-timeout' ) ), LOAD_TIMEOUT );

		window[ ONLOAD_CALLBACK ] = () => {
			window.clearTimeout( timer );
			resolve( window.smartCaptcha );
		};

		const script = document.createElement( 'script' );
		script.src = `${ SCRIPT_SRC }?render=onload&onload=${ ONLOAD_CALLBACK }`;
		script.async = true;
		script.onerror = () => {
			window.clearTimeout( timer );
			// Иначе следующая попытка приняла бы заблокированный тег за чужой
			// скрипт и ждала бы его весь `LOAD_TIMEOUT`.
			script.remove();
			reject( new Error( 'captcha-load-error' ) );
		};

		document.head.appendChild( script );
	} ).catch( ( error ) => {
		// Следующая отправка попробует снова: скрипт мог просто не успеть.
		loading = null;
		throw error;
	} );

	return loading;
}

function settle( widget, action ) {
	const { pending } = widget;

	if ( ! pending ) {
		return;
	}

	widget.pending = null;
	window.clearTimeout( pending.timer );
	action( pending );
}

function getWidget( form, captcha ) {
	if ( widgets.has( form ) ) {
		return widgets.get( form );
	}

	const slot = form.querySelector( '[data-fs-captcha]' );

	if ( ! slot ) {
		return null;
	}

	const widget = { id: null, pending: null };

	widget.id = captcha.render( slot, {
		sitekey: window.fsLmsTheme.captchaSiteKey,
		invisible: true,
		hl: 'ru',
		callback: ( token ) => settle( widget, ( pending ) => pending.resolve( token ) ),
	} );

	captcha.subscribe( widget.id, 'challenge-visible', () => {
		if ( widget.pending ) {
			window.clearTimeout( widget.pending.timer );
		}
	} );

	captcha.subscribe( widget.id, 'challenge-hidden', () => {
		window.setTimeout( () => {
			const token = captcha.getResponse( widget.id );

			settle( widget, ( pending ) => ( token ? pending.resolve( token ) : pending.reject( new Error( DISMISSED ) ) ) );
		}, DISMISS_GRACE );
	} );

	// Сбой на стороне Яндекса — как незагрузившаяся капча, не как бот.
	[ 'network-error', 'javascript-error' ].forEach( ( event ) => {
		captcha.subscribe( widget.id, event, () => settle( widget, ( pending ) => pending.resolve( '' ) ) );
	} );

	widgets.set( form, widget );

	return widget;
}

/**
 * Прогрев на первом фокусе в форме: к моменту отправки скрипт уже загружен,
 * а виджет отрисован.
 */
export function preloadCaptcha( form ) {
	if ( ! isCaptchaEnabled() ) {
		return;
	}

	loadScript()
		.then( ( captcha ) => getWidget( form, captcha ) )
		.catch( () => {} );
}

/**
 * @return {Promise<string>} Токен; пустая строка — капча не настроена или не
 *                           загрузилась. Отклоняется, если посетитель закрыл
 *                           задание, не решив его.
 */
export function getCaptchaToken( form ) {
	if ( ! isCaptchaEnabled() ) {
		return Promise.resolve( '' );
	}

	return loadScript()
		.then( ( captcha ) => {
			const widget = getWidget( form, captcha );

			if ( ! widget ) {
				return '';
			}

			return new Promise( ( resolve, reject ) => {
				widget.pending = {
					resolve,
					reject,
					timer: window.setTimeout( () => settle( widget, ( pending ) => pending.resolve( '' ) ), EXECUTE_TIMEOUT ),
				};

				captcha.execute( widget.id );
			} );
		} )
		.catch( ( error ) => {
			if ( isCaptchaDismissed( error ) ) {
				throw error;
			}

			return '';
		} );
}

/** Токен одноразовый — после каждой отправки виджет сбрасываем. */
export function resetCaptcha( form ) {
	if ( widgets.has( form ) && hasApi() ) {
		window.smartCaptcha.reset( widgets.get( form ).id );
	}
}
