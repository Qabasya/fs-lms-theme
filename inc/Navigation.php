<?php
/**
 * Меню шапки как настоящий объект меню WordPress (Фаза 17.4).
 *
 * Раньше пункты меню были инлайновыми `wp:navigation-link` прямо в
 * `patterns/header-nav.php` — такое меню правится только в коде паттерна:
 * редактор не мог ни переставить пункты, ни добавить свой, ни собрать
 * выпадающее подменю. Здесь тема один раз заводит запись типа
 * `wp_navigation` (та же сущность, что создаёт «Редактор сайта →
 * Навигация»), а паттерн ссылается на неё через `ref`. Дальше всё
 * редактирование — мышкой в Редакторе сайта, тема в содержимое меню
 * больше не вмешивается.
 *
 * Идемпотентно: ID найденного/созданного меню кэшируется в опции, при
 * удалении меню — заводится заново. Существующее меню никогда не
 * перезаписывается, поэтому правки редактора не теряются.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const FS_LMS_THEME_NAV_SLUG   = 'fs-lms-theme-header';
const FS_LMS_THEME_NAV_OPTION = 'fs_lms_theme_nav_id';

/**
 * Пункт меню-ссылка на страницу по слагу.
 *
 * Ссылки на реальные страницы отдаём как `kind: post-type` с ID — тогда
 * ядро само подсвечивает активный пункт классом `current-menu-item`
 * (для произвольных URL оно этого не делает, из-за чего в паттерне
 * раньше жила PHP-логика `is_page(...)`).
 */
function fs_lms_theme_nav_page_link( string $slug, string $label ): string {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		return sprintf(
			'<!-- wp:navigation-link {"label":"%s","type":"page","id":%d,"kind":"post-type","url":"%s"} /-->',
			esc_attr( $label ),
			$page->ID,
			esc_url( get_permalink( $page ) )
		);
	}

	return sprintf(
		'<!-- wp:navigation-link {"label":"%s","url":"%s","kind":"custom"} /-->',
		esc_attr( $label ),
		esc_url( home_url( '/' . $slug . '/' ) )
	);
}

/**
 * Стартовое содержимое меню — то же, что раньше было зашито в паттерне,
 * плюс «Учебник»/«Тренажёр».
 *
 * BugFix (2026-09-04): пункт «Личный кабинет» убран из меню по прямому
 * указанию пользователя (дублировал одноимённую кнопку в шапке, которая
 * тоже убрана — см. `patterns/header-nav.php`).
 *
 * Задача 10 (tasks.md, 2026-09-04): «Учебник»/«Тренажёр» были выпадающими
 * подменю по 4 направлениям (`fs_lms_theme_nav_subject_submenu()`,
 * убрана) — по прямому указанию пользователя стали обычными ссылками на
 * новые страницы-хабы `/articles/`/`/tasks/` (`inc/ResourcePages.php`),
 * которые сами ведут дальше на страницы конкретных направлений.
 */
function fs_lms_theme_nav_default_content(): string {
	$items = array(
		'<!-- wp:home-link {"label":"Главная"} /-->',
		fs_lms_theme_nav_page_link( 'about', 'О нас' ),
		fs_lms_theme_nav_page_link( 'courses', 'Курсы' ),
		fs_lms_theme_nav_page_link( 'articles', 'Учебник' ),
		fs_lms_theme_nav_page_link( 'tasks', 'Тренажёр' ),
	);

	return implode( "\n\n", $items );
}

/**
 * ID меню шапки: из опции, из существующей записи или из свежесозданной.
 *
 * @return int ID записи `wp_navigation` либо 0, если создать не удалось
 *             (тогда паттерн отрисует запасные инлайновые пункты).
 */
function fs_lms_theme_navigation_id(): int {
	$cached = (int) get_option( FS_LMS_THEME_NAV_OPTION, 0 );

	if ( $cached > 0 ) {
		$post = get_post( $cached );

		if ( $post instanceof WP_Post && 'wp_navigation' === $post->post_type && 'trash' !== $post->post_status ) {
			return $cached;
		}
	}

	$existing = get_posts(
		array(
			'post_type'        => 'wp_navigation',
			'name'             => FS_LMS_THEME_NAV_SLUG,
			'post_status'      => array( 'publish', 'draft' ),
			'numberposts'      => 1,
			'suppress_filters' => false,
		)
	);

	if ( ! empty( $existing ) ) {
		update_option( FS_LMS_THEME_NAV_OPTION, $existing[0]->ID );

		return (int) $existing[0]->ID;
	}

	$new_id = wp_insert_post(
		array(
			'post_type'    => 'wp_navigation',
			'post_title'   => __( 'Меню шапки', 'fs-lms-theme' ),
			'post_name'    => FS_LMS_THEME_NAV_SLUG,
			'post_status'  => 'publish',
			'post_content' => fs_lms_theme_nav_default_content(),
		)
	);

	if ( is_wp_error( $new_id ) || 0 === $new_id ) {
		return 0;
	}

	update_option( FS_LMS_THEME_NAV_OPTION, $new_id );

	return (int) $new_id;
}
