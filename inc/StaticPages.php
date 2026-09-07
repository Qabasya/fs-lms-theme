<?php
/**
 * Страницы «О нас» (`/about/`), «Курсы» (`/courses/`) и «Контакты»
 * (`/contacts/`) — автосоздание.
 *
 * BugFix (2026-09-07): на проде этих двух страниц не было, потому что их
 * никто не создавал — на локалке они были заведены руками. Меню шапки
 * (`inc/Navigation.php`) на них ссылается, `get_page_by_path()` возвращал
 * `null`, пункт вырождался в произвольную ссылку `/courses/`, та давала
 * 404, а `redirect_guess_404_permalink()` (`wp-includes/canonical.php`,
 * нестрогий режим `post_name LIKE 'courses%'`) уводил посетителя на
 * `/{$subject}/courses/` — дочернюю страницу предмета, которую заводит
 * плагин (`Inc\Enums\Wp\SubjectPageType::Courses`). «О нас» под угадыватель
 * не подпадала и просто отдавала 404.
 *
 * Отдельный модуль, а не довесок к `inc/ResourcePages.php`: тот отвечает за
 * хабы «Учебник»/«Тренажёр» и сам генерирует их содержимое из данных
 * направлений. Здесь содержимое — только ссылки на паттерны, которые
 * редактор дальше правит мышкой; общего кода между модулями нет.
 *
 * Шаблон всех трёх страниц — `page-wide`, `<h1>` рисует `wp:post-title` из
 * `templates/page-wide.html`, поэтому заголовок страницы — часть дизайна
 * (у «О нас» это реквизиты ИП в две строки), а паттерны свой `<h1>` не
 * дублируют (см. шапку `patterns/courses-catalog.php`). Размер этого
 * `<h1>` — `xxl` (2026-09-07, было `xxxl`), общий для обеих страниц и
 * совпадает с заголовками `/articles/`, `/tasks/` и `/shop/`; шаблон
 * используется только этими двумя страницами (корзина и оформление
 * заказа сидят на `page-subject`).
 *
 * Идемпотентно: существующая страница с таким слагом никогда не трогается,
 * правки редактора не теряются.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Слаг → заголовок, шаблон и содержимое страницы.
 *
 * Содержимое — ровно те же две ссылки на паттерны, что лежат на проверенных
 * страницах локальной установки: весь контент живёт в паттернах темы
 * (`patterns/about-*.php`, `patterns/courses-*.php`), страница только
 * собирает их в нужном порядке.
 *
 * @return array<string, array{title: string, template: string, content: string}>
 */
function fs_lms_theme_static_pages(): array {
	return array(
		'about'    => array(
			'title'    => 'Индивидуальный предприниматель<br>Иванов Борис Олегович',
			'template' => 'page-wide',
			'content'  => "<!-- wp:pattern {\"slug\":\"fs-lms-theme/about-header\"} /-->\n\n"
				. "<!-- wp:pattern {\"slug\":\"fs-lms-theme/about-accordion\"} /-->\n",
		),
		'courses'  => array(
			'title'    => 'Курсы',
			'template' => 'page-wide',
			'content'  => "<!-- wp:pattern {\"slug\":\"fs-lms-theme/courses-catalog\"} /-->\n\n"
				. "<!-- wp:pattern {\"slug\":\"fs-lms-theme/courses-contact\"} /-->\n",
		),
		'contacts' => array(
			'title'    => 'Контакты',
			'template' => 'page-wide',
			'content'  => "<!-- wp:pattern {\"slug\":\"fs-lms-theme/contacts-info\"} /-->\n",
		),
	);
}

/**
 * Вставка записи в обход kses.
 *
 * На `init` текущего пользователя нет, поэтому `kses_init_filters()` вешает
 * `wp_filter_kses` на `title_save_pre`, а он режет заголовок по узкому
 * списку `$allowedtags` — без `<br>`. Заголовок «О нас» этот перенос строки
 * содержит осмысленно (две строки реквизитов в макете), поэтому фильтр
 * снимается на время вставки и возвращается на место, только если он там
 * действительно был.
 *
 * @param array<string, mixed> $postarr Аргументы `wp_insert_post()`.
 *
 * @return int ID созданной записи либо 0.
 */
function fs_lms_theme_insert_post_unfiltered( array $postarr ): int {
	$had_filter = has_filter( 'title_save_pre', 'wp_filter_kses' );

	if ( false !== $had_filter ) {
		remove_filter( 'title_save_pre', 'wp_filter_kses', (int) $had_filter );
	}

	$post_id = wp_insert_post( $postarr );

	if ( false !== $had_filter ) {
		add_filter( 'title_save_pre', 'wp_filter_kses', (int) $had_filter );
	}

	return is_wp_error( $post_id ) ? 0 : (int) $post_id;
}

/**
 * Заводит `/about/` и `/courses/`, если их ещё нет.
 *
 * Тот же приём, что у `fs_lms_theme_ensure_resource_pages()`
 * (`inc/ResourcePages.php`): `after_switch_theme` для честной активации плюс
 * `init` как подстраховка для уже активной темы, когда код приезжает
 * обновлением без переключения темы.
 */
function fs_lms_theme_ensure_static_pages(): void {
	foreach ( fs_lms_theme_static_pages() as $slug => $page ) {
		if ( get_page_by_path( $slug ) instanceof WP_Post ) {
			continue;
		}

		$page_id = fs_lms_theme_insert_post_unfiltered(
			array(
				'post_type'    => 'page',
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_content' => $page['content'],
			)
		);

		if ( $page_id > 0 ) {
			update_post_meta( $page_id, '_wp_page_template', $page['template'] );
		}
	}
}
add_action( 'after_switch_theme', 'fs_lms_theme_ensure_static_pages' );
add_action( 'init', 'fs_lms_theme_ensure_static_pages' );
