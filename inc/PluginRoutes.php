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

/**
 * URL раздела страницы направления (Фаза 13) — «Учебник»/«Тренажёр» в
 * `patterns/subject-more.php`. Плагин не даёт под это `apply_filters()`
 * (тот же случай, что `fs_lms_theme_url()` выше): слаг берётся из его же
 * `Inc\Enums\Wp\SubjectPageType::path()`, если плагин активен, иначе —
 * тот же `/{$subjectKey}/{$page}/`-паттерн локально (решение 2, Фаза 13).
 *
 * Фаза 16.5: добавлен `'overview'` — корневая страница предмета
 * (`SubjectPageType::Overview`, слаг = сам `$subject_key`, путь без
 * дополнительного сегмента) — кнопка «Программа» на карточках
 * `patterns/courses-catalog.php` ведёт именно туда, а не на учебник/
 * тренажёр.
 *
 * @param string $subject_key Ключ предмета плагина (`inf_ege`, `inf_oge`, `python`, `robo`).
 * @param string $page        'overview' (корневая), 'articles' (учебник) или 'trainer' (тренажёр).
 *
 * @return string Абсолютный URL раздела предмета.
 */
function fs_lms_theme_subject_url( string $subject_key, string $page ): string {
	$enum_class = 'Inc\\Enums\\Wp\\SubjectPageType';

	if ( class_exists( $enum_class ) ) {
		$case_by_page = array(
			'overview' => 'Overview',
			'articles' => 'Articles',
			'trainer'  => 'Trainer',
		);

		$case_name = $case_by_page[ $page ] ?? null;
		if ( null !== $case_name ) {
			foreach ( $enum_class::cases() as $case ) {
				if ( $case->name === $case_name ) {
					return esc_url( home_url( '/' . $case->path( $subject_key ) . '/' ) );
				}
			}
		}
	}

	if ( 'overview' === $page ) {
		return esc_url( home_url( '/' . $subject_key . '/' ) );
	}

	return esc_url( home_url( '/' . $subject_key . '/' . $page . '/' ) );
}
