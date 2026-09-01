<?php
/**
 * Границы тема/плагин — резолвер URL служебных страниц fs-lms (Фаза 7).
 *
 * Плагин не предоставляет `apply_filters()` для этих URL (проверено: страницы
 * заявки/входа/личного кабинета — просто `home_url('/' . slug . '/')` в
 * `Inc\Enums\Wp\PageRoutes::url()`, см. fs-lms/inc/Enums/Wp/PageRoutes.php).
 * Поэтому паттерны темы не хардкодят URL напрямую, а зовут
 * `fs_lms_theme_url( $route )` — если плагин активен, слаг берётся из его же
 * enum (переживёт смену слага на стороне плагина); если плагин выключен
 * (или его enum когда-нибудь уберёт этот case) — тот же `home_url()`-паттерн
 * повторяется локально как fallback, чтобы паттерны не ломались на чистой
 * установке темы без плагина.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param string $route Один из: 'apply', 'sign-in', 'profile'.
 *
 * @return string Абсолютный URL страницы плагина (или её fallback-адрес).
 */
function fs_lms_theme_url( string $route ): string {
	$enum_class = 'Inc\\Enums\\Wp\\PageRoutes';

	if ( class_exists( $enum_class ) ) {
		$case_by_route = array(
			'apply'    => 'Apply',
			'sign-in'  => 'SignIn',
			'profile'  => 'UserProfile',
		);

		$case_name = $case_by_route[ $route ] ?? null;
		if ( null !== $case_name ) {
			foreach ( $enum_class::cases() as $case ) {
				if ( $case->name === $case_name ) {
					return $case->url();
				}
			}
		}
	}

	return esc_url( home_url( '/' . $route . '/' ) );
}
