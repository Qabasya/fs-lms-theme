/**
 * Баннер согласия на аналитические cookie (152-ФЗ, 2026-09-29) — см.
 * `inc/CookieConsent.php`: сервер помечает счётчик Метрики как
 * `<script type="text/plain" data-fs-consent="analytics">`, а здесь он
 * запускается только после «Принять все».
 *
 * Отдельная точка входа (assets/js/cookie-consent.min.js), не часть
 * theme.min.js: подключается и на bare-шеллах плагина, где бандла темы нет.
 */

const COOKIE = 'fs_cookie_consent';
const MAX_AGE = 365 * 24 * 60 * 60;
const ALL = 'all';
const NECESSARY = 'necessary';

function readChoice() {
	const match = document.cookie.match( new RegExp( '(?:^|; )' + COOKIE + '=([^;]*)' ) );
	const value = match ? decodeURIComponent( match[ 1 ] ) : '';

	return value === ALL || value === NECESSARY ? value : '';
}

function saveChoice( value ) {
	const secure = window.location.protocol === 'https:' ? '; Secure' : '';

	document.cookie = COOKIE + '=' + value + '; Max-Age=' + MAX_AGE + '; Path=/; SameSite=Lax' + secure;
}

/**
 * Исполняет отложенные скрипты: `type="text/plain"` браузер не запускает,
 * и смена атрибута у готового тега тоже не поможет — нужен новый элемент.
 */
function runDeferred() {
	document.querySelectorAll( 'script[type="text/plain"][data-fs-consent="analytics"]' ).forEach( ( placeholder ) => {
		const script = document.createElement( 'script' );

		Array.from( placeholder.attributes ).forEach( ( attr ) => {
			if ( attr.name !== 'type' && attr.name !== 'data-fs-consent' ) {
				script.setAttribute( attr.name, attr.value );
			}
		} );

		script.text = placeholder.text;
		placeholder.replaceWith( script );
	} );
}

/**
 * Отзыв согласия: убираем cookie и хранилище Метрики (`_ym*`) на нашем
 * домене. Сам tag.js из памяти страницы не выгрузить — поэтому после отзыва
 * страница перезагружается.
 */
function clearAnalytics() {
	const host = window.location.hostname;
	const domains = [ '', host, '.' + host, '.' + host.split( '.' ).slice( -2 ).join( '.' ) ];

	document.cookie.split( '; ' ).forEach( ( pair ) => {
		const name = pair.split( '=' )[ 0 ];

		if ( ! /^_ym/.test( name ) ) {
			return;
		}

		domains.forEach( ( domain ) => {
			document.cookie = name + '=; Max-Age=0; Path=/' + ( domain ? '; Domain=' + domain : '' );
		} );
	} );

	try {
		Object.keys( window.localStorage ).forEach( ( key ) => {
			if ( /^_ym/.test( key ) ) {
				window.localStorage.removeItem( key );
			}
		} );
	} catch ( e ) {
		// Хранилище недоступно (приватный режим, запрет сайта) — чистить нечего.
	}
}

function initCookieConsent() {
	const banner = document.querySelector( '[data-fs-cookie-banner]' );
	let choice = readChoice();

	if ( choice === ALL ) {
		runDeferred();
	}

	if ( ! banner ) {
		return;
	}

	if ( ! choice ) {
		banner.hidden = false;
	}

	banner.addEventListener( 'click', ( event ) => {
		const button = event.target.closest( '[data-fs-cookie-choice]' );

		if ( ! button ) {
			return;
		}

		const next = button.getAttribute( 'data-fs-cookie-choice' ) === ALL ? ALL : NECESSARY;
		const revoked = choice === ALL && next === NECESSARY;

		saveChoice( next );
		banner.hidden = true;

		if ( next === ALL && choice !== ALL ) {
			runDeferred();
		}

		if ( revoked ) {
			clearAnalytics();
			window.location.reload();
		}

		choice = next;
	} );

	/**
	 * «Настройки cookie» (ссылка в подвале, `data-fs-cookie-settings`) —
	 * снова показать баннер, чтобы изменить или отозвать выбор.
	 */
	document.addEventListener( 'click', ( event ) => {
		const trigger = event.target.closest( '[data-fs-cookie-settings]' );

		if ( ! trigger ) {
			return;
		}

		event.preventDefault();
		banner.hidden = false;
		banner.querySelector( '[data-fs-cookie-choice]' )?.focus();
	} );
}

if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', initCookieConsent );
} else {
	initCookieConsent();
}
