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
			esc_url( fs_lms_theme_nav_relative_url( get_permalink( $page ) ) )
		);
	}

	return sprintf(
		'<!-- wp:navigation-link {"label":"%s","url":"%s","kind":"custom"} /-->',
		esc_attr( $label ),
		esc_url( fs_lms_theme_nav_relative_url( home_url( '/' . $slug . '/' ) ) )
	);
}

/**
 * Адрес пункта меню без схемы и хоста.
 *
 * `render_block_core_navigation_link()` строит `href` из атрибута `url` и
 * НЕ восстанавливает его по `id` (см. `wp-includes/blocks/navigation-link.php`),
 * поэтому URL приходится хранить. Но меню — запись в БД, которая живёт
 * дольше домена: абсолютный адрес переживает перенос базы с локалки на
 * прод и уводит всё меню на `http://localhost:8080`. `wp_make_link_relative()`
 * оставляет путь целиком, включая подкаталог установки, — ссылка остаётся
 * рабочей и на сайте в подпапке.
 *
 * @param string $url Абсолютный URL страницы.
 *
 * @return string Путь вида `/about/`.
 */
function fs_lms_theme_nav_relative_url( string $url ): string {
	$relative = wp_make_link_relative( $url );

	return '' === $relative ? $url : $relative;
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
 *
 * BugFix.6 (2026-09-12): «Контакты» (`/contacts/`, `inc/StaticPages.php`)
 * — после «Тренажёра», по прямому указанию пользователя (7 сентября пункт
 * по его же указанию убирали). В уже созданное меню его дописывает
 * починка ниже — `fs_lms_theme_add_missing_nav_items()`.
 */
function fs_lms_theme_nav_default_content(): string {
	$items = array(
		'<!-- wp:home-link {"label":"Главная"} /-->',
		fs_lms_theme_nav_page_link( 'about', 'О нас' ),
		fs_lms_theme_nav_page_link( 'courses', 'Курсы' ),
		fs_lms_theme_nav_page_link( 'articles', 'Учебник' ),
		fs_lms_theme_nav_page_link( 'tasks', 'Тренажёр' ),
		fs_lms_theme_nav_page_link( 'contacts', 'Контакты' ),
	);

	return implode( "\n\n", $items );
}

/**
 * ID уже существующего меню шапки: из опции либо из записи по слагу.
 *
 * Отделено от создания: починка меню ниже не должна заводить его на
 * ровном месте — она чинит только то, что уже есть.
 *
 * @return int ID записи `wp_navigation` либо 0, если меню ещё нет.
 */
function fs_lms_theme_find_navigation_id(): int {
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

	return 0;
}

/**
 * ID меню шапки: найденного либо свежесозданного.
 *
 * @return int ID записи `wp_navigation` либо 0, если создать не удалось
 *             (тогда паттерн отрисует запасные инлайновые пункты).
 */
function fs_lms_theme_navigation_id(): int {
	$found = fs_lms_theme_find_navigation_id();

	if ( $found > 0 ) {
		return $found;
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

/**
 * Опция-счётчик выполненных починок меню и её текущая версия.
 *
 * Версия 3, а не 2: двойка 7 сентября несколько часов дописывала
 * «Контакты» и могла успеть записаться в опцию — на таком сайте починка с
 * версией 2 уже не запустилась бы.
 */
const FS_LMS_THEME_NAV_REPAIR_OPTION  = 'fs_lms_theme_nav_repaired';
const FS_LMS_THEME_NAV_REPAIR_VERSION = 3;

/**
 * Слаг страницы из пути ссылки: `/about/` → `about`, с учётом установки
 * WordPress в подкаталог (`/wp/about/` → `about`).
 *
 * @param string $path Путь из `wp_parse_url()`.
 *
 * @return string Путь страницы для `get_page_by_path()`.
 */
function fs_lms_theme_nav_path_to_slug( string $path ): string {
	$base = '/' . trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	$path = '/' . ltrim( $path, '/' );

	if ( '/' !== $base && 0 === strpos( $path, $base . '/' ) ) {
		$path = substr( $path, strlen( $base ) );
	}

	return trim( $path, '/' );
}

/**
 * Рекурсивная починка пунктов меню (подменю тоже).
 *
 * Два правила, оба консервативные — трогаем ровно то, что сломано:
 *
 *  1. Пункт `kind: post-type` — это ссылка на страницу сайта. Её адрес
 *     пересчитывается по слагу из пути и записывается относительным:
 *     чинит и протухший ID после переноса базы, и абсолютный
 *     `http://localhost:8080/...`.
 *  2. Пункт `kind: custom` повышается до ссылки на страницу, только если
 *     страница с таким путём реально есть на этом сайте. Внешние ссылки
 *     (другой хост, страницы нет) не трогаются вообще — иначе починка
 *     сломала бы то, что редактор добавил руками.
 *
 * @param array<int, array<string, mixed>> $blocks  Разобранные блоки меню.
 * @param bool                             $changed Флаг «содержимое изменилось».
 *
 * @return array<int, array<string, mixed>> Блоки с исправленными атрибутами.
 */
function fs_lms_theme_repair_nav_blocks( array $blocks, bool &$changed ): array {
	foreach ( $blocks as $index => $block ) {
		if ( ! empty( $block['innerBlocks'] ) ) {
			$blocks[ $index ]['innerBlocks'] = fs_lms_theme_repair_nav_blocks( $block['innerBlocks'], $changed );
		}

		if ( 'core/navigation-link' !== ( $block['blockName'] ?? '' ) ) {
			continue;
		}

		$attrs = $block['attrs'] ?? array();
		$url   = (string) ( $attrs['url'] ?? '' );

		if ( '' === $url ) {
			continue;
		}

		$is_page_link = 'post-type' === ( $attrs['kind'] ?? '' );

		if ( ! $is_page_link ) {
			$host      = (string) wp_parse_url( $url, PHP_URL_HOST );
			$home_host = (string) wp_parse_url( home_url(), PHP_URL_HOST );

			if ( '' !== $host && $host !== $home_host ) {
				continue;
			}
		}

		$page = get_page_by_path( fs_lms_theme_nav_path_to_slug( (string) wp_parse_url( $url, PHP_URL_PATH ) ) );

		if ( ! $page instanceof WP_Post ) {
			continue;
		}

		$fixed = array_merge(
			$attrs,
			array(
				'type' => 'page',
				'id'   => $page->ID,
				'kind' => 'post-type',
				'url'  => fs_lms_theme_nav_relative_url( get_permalink( $page ) ),
			)
		);

		if ( $fixed !== $attrs ) {
			$blocks[ $index ]['attrs'] = $fixed;
			$changed                   = true;
		}
	}

	return $blocks;
}

/**
 * Страницы, ссылка на которые обязана быть в меню шапки.
 *
 * Меню заводится один раз и никогда не перезаписывается (правки редактора
 * дороже), поэтому пункты, появившиеся в теме позже первого запуска сайта,
 * в меню сами не попадают. Такой пункт дописывается починкой ниже — ровно
 * один раз на версию (`FS_LMS_THEME_NAV_REPAIR_VERSION`); если редактор
 * его потом удалит или переставит, второй раз тема не вмешается.
 *
 * @return array<string, array{label: string, after: string}> Слаг страницы →
 *         подпись пункта и слаг пункта, за которым он встаёт (нет такого
 *         пункта в меню — в конец).
 */
function fs_lms_theme_nav_required_pages(): array {
	return array(
		'contacts' => array(
			'label' => 'Контакты',
			'after' => 'tasks',
		),
	);
}

/**
 * Слаг страницы, на которую ведёт пункт меню, — по пути ссылки, а не по ID:
 * ID протухает после переноса базы, а путь чинит
 * `fs_lms_theme_repair_nav_blocks()` перед этой проверкой.
 *
 * @param array<string, mixed> $block Разобранный блок меню.
 *
 * @return string Слаг либо пустая строка, если это не ссылка.
 */
function fs_lms_theme_nav_link_slug( array $block ): string {
	if ( 'core/navigation-link' !== ( $block['blockName'] ?? '' ) ) {
		return '';
	}

	$url = (string) ( $block['attrs']['url'] ?? '' );

	return '' === $url ? '' : fs_lms_theme_nav_path_to_slug( (string) wp_parse_url( $url, PHP_URL_PATH ) );
}

/**
 * Есть ли в меню (включая подменю) пункт, ведущий на страницу с этим слагом.
 *
 * @param array<int, array<string, mixed>> $blocks Разобранные блоки меню.
 * @param string                           $slug   Слаг страницы.
 */
function fs_lms_theme_nav_has_page_link( array $blocks, string $slug ): bool {
	foreach ( $blocks as $block ) {
		if ( $slug === fs_lms_theme_nav_link_slug( $block ) ) {
			return true;
		}

		if ( ! empty( $block['innerBlocks'] ) && fs_lms_theme_nav_has_page_link( $block['innerBlocks'], $slug ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Дописывает пункты на страницы, которых в меню ещё нет, — сразу за
 * пунктом из `after` верхнего уровня, а без него в конец.
 *
 * @param array<int, array<string, mixed>> $blocks  Разобранные блоки меню.
 * @param bool                             $changed Флаг «содержимое изменилось».
 *
 * @return array<int, array<string, mixed>> Блоки с добавленными пунктами.
 */
function fs_lms_theme_add_missing_nav_items( array $blocks, bool &$changed ): array {
	foreach ( fs_lms_theme_nav_required_pages() as $slug => $item ) {
		if ( fs_lms_theme_nav_has_page_link( $blocks, $slug ) ) {
			continue;
		}

		$position = count( $blocks );

		foreach ( $blocks as $index => $block ) {
			if ( $item['after'] === fs_lms_theme_nav_link_slug( $block ) ) {
				$position = $index + 1;
				break;
			}
		}

		// Перенос строк впереди `parse_blocks()` отдаёт отдельным пустым
		// блоком — в сохранённом меню пункты остаются разделены, как их
		// пишет редактор.
		array_splice( $blocks, $position, 0, parse_blocks( "\n\n" . fs_lms_theme_nav_page_link( $slug, $item['label'] ) ) );
		$changed = true;
	}

	return $blocks;
}

/**
 * Одноразовая починка уже созданного меню.
 *
 * Меню заводится один раз и сознательно никогда не перезаписывается, чтобы
 * не терять правки редактора. Обратная сторона: сайт, где меню собралось
 * раньше страниц (прод до BugFix 2026-09-07, `inc/StaticPages.php`), навсегда
 * остаётся с битыми пунктами «О нас»/«Курсы» — новых страниц он уже не
 * заметит. Поэтому вместо перезаписи — точечный проход по пунктам плюс
 * (версия 3, 2026-09-12) добавление отсутствующих пунктов из
 * `fs_lms_theme_nav_required_pages()` — сейчас это «Контакты».
 *
 * Приоритет 20: `fs_lms_theme_ensure_static_pages()` и
 * `fs_lms_theme_ensure_resource_pages()` висят на `init` с приоритетом по
 * умолчанию, страницы к этому моменту уже созданы.
 */
function fs_lms_theme_repair_navigation(): void {
	if ( (int) get_option( FS_LMS_THEME_NAV_REPAIR_OPTION, 0 ) >= FS_LMS_THEME_NAV_REPAIR_VERSION ) {
		return;
	}

	$nav_id = fs_lms_theme_find_navigation_id();

	if ( $nav_id > 0 ) {
		$nav = get_post( $nav_id );

		if ( $nav instanceof WP_Post ) {
			$changed = false;
			$blocks  = fs_lms_theme_repair_nav_blocks( parse_blocks( $nav->post_content ), $changed );
			$blocks  = fs_lms_theme_add_missing_nav_items( $blocks, $changed );

			if ( $changed ) {
				wp_update_post(
					array(
						'ID'           => $nav_id,
						'post_content' => serialize_blocks( $blocks ),
					)
				);
			}
		}
	}

	update_option( FS_LMS_THEME_NAV_REPAIR_OPTION, FS_LMS_THEME_NAV_REPAIR_VERSION );
}
add_action( 'init', 'fs_lms_theme_repair_navigation', 20 );
