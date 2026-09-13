<?php
/**
 * Страницы-хабы «Учебник» (`/articles/`) и «Тренажёр» (`/tasks/`) — задача
 * 10 (tasks.md, 2026-09-04, источники дизайна — «Учебник - мокап.dc.html»/
 * «Тренажёр - мокап.dc.html», Claude Design, тот же проект, что и остальные
 * страницы). В отличие от per-предметных `/{$subject}/articles/`/
 * `/{$subject}/trainer/` (плагин, `SubjectPageType`), это ОДНА общая
 * страница на весь сайт со ссылками на все 4 направления — раньше эти
 * пункты меню были выпадающими подменю (`fs_lms_theme_nav_subject_submenu()`,
 * `inc/Navigation.php`), теперь простые ссылки сюда.
 *
 * Хедер/футер — общие темы (`templates/page-subject.html`, без дублей
 * `wp:post-title` — тот же приём, что у страниц направлений,
 * `inc/SubjectPages.php`); из мокапов взята центральная часть (заголовок +
 * вводный абзац + карточки направлений, `.fs-subject-more-card` — тот же
 * компонент, что в `patterns/subject-more.php`, задача 1), включая текст
 * карточек ЕГЭ/ОГЭ — он взят из мокапа дословно, не сгенерирован заново.
 *
 * Карточек ровно 2 (ЕГЭ и ОГЭ) — как в мокапах и по прямому указанию
 * пользователя (tasks.md, новый список, п.1): Python и Робототехника сюда
 * не выносятся. Цвет — чередование двух фирменных акцентов (ЕГЭ оранжевый,
 * ОГЭ синий), не схема бейджей каталога курсов; ширина текста — весь
 * контейнер 1200px (`contentSize` темы), без своей узкой колонки.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Направление → название и модификатор карточки (`fs-subject-more-card--*`,
 * `theme.scss`) — цвет карточки не хранится здесь: он полностью на стороне
 * CSS-модификатора (чередование `accent`/`accent-2`, как в мокапе).
 *
 * @return array<string, array{title: string, modifier: string}>
 */
function fs_lms_theme_resource_subjects(): array {
	return array(
		'inf_ege' => array( 'title' => 'ЕГЭ по информатике', 'modifier' => 'ege' ),
		'inf_oge' => array( 'title' => 'ОГЭ по информатике', 'modifier' => 'oge' ),
	);
}

const FS_LMS_THEME_RESOURCE_ICON_ARTICLES = '<svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 5.5C8.6 4.4 6.8 4 4.5 4v10c2.3 0 4.1.4 5.5 1.5 1.4-1.1 3.2-1.5 5.5-1.5V4c-2.3 0-4.1.4-5.5 1.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"></path><path d="M10 5.5v10" stroke="currentColor" stroke-width="1.6"></path></svg>';
const FS_LMS_THEME_RESOURCE_ICON_TRAINER  = '<svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3" y="3" width="14" height="14" rx="2.5" stroke="currentColor" stroke-width="1.6"></rect><path d="m6.5 9.5 2 2 5-5M7 14h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path></svg>';

/**
 * Содержимое страницы «Учебник»/«Тренажёр»: заголовок, вводный абзац,
 * сетка карточек направлений.
 *
 * 2026-09-07: заголовок больше не центрируется и черта-разделитель
 * (`.fs-title-divider`) под ним убрана — обе страницы приведены к
 * оформлению `/courses/` (`patterns/courses-catalog.php`): текст слева,
 * сразу под заголовком вводный абзац. Размер заголовка (`xxl`) не
 * менялся. В мокапах «Учебник»/«Тренажёр» заголовок был по центру с
 * чертой — расхождение с макетом осознанное, по решению пользователя
 * в пользу единого вида всех внутренних страниц.
 *
 * 2026-09-13 (по указанию пользователя): тексты — «Настройки темы → Учебник и
 * тренажёр» (`inc/Showcase/Resource_Texts.php`), названия направлений на
 * карточках — из «Направлений». Разметку выводят паттерны `resource-articles`/
 * `resource-tasks`, в содержимом страниц — ссылка на них.
 *
 * @param string $slug     Слаг страницы (`articles`/`tasks`) — префикс её текстов.
 * @param string $page_key 'articles' либо 'trainer' — второй аргумент `fs_lms_theme_subject_url()`.
 * @param string $icon_svg Иконка карточек (одна из констант выше).
 */
function fs_lms_theme_resource_page_blocks( string $slug, string $page_key, string $icon_svg ): string {
	$texts = FS_LMS_Theme_Showcase::resource_texts();
	$cards = '';

	foreach ( fs_lms_theme_resource_subjects() as $subject_key => $subject ) {
		$card        = (string) array_search( $subject_key, FS_LMS_Theme_Resource_Texts::CARDS, true );
		$direction   = FS_LMS_Theme_Showcase::directions()->find( $subject_key );
		$title       = esc_html( null === $direction ? $subject['title'] : $direction['title'] );
		$url         = esc_url( fs_lms_theme_subject_url( $subject_key, $page_key ) );
		$card_text   = nl2br( esc_html( $texts->get( "{$slug}_{$card}_text" ) ) );
		$button_text = esc_html( $texts->get( "{$slug}_{$card}_button" ) );

		$cards .= <<<HTML

		<!-- wp:group {"className":"fs-subject-more-card fs-subject-more-card--{$subject['modifier']}"} -->
		<div class="wp-block-group fs-subject-more-card fs-subject-more-card--{$subject['modifier']}">
			<!-- wp:html -->
			<span class="fs-subject-more-card__icon">{$icon_svg}</span>
			<!-- /wp:html -->

			<!-- wp:paragraph {"className":"fs-subject-more-card__title"} -->
			<p class="fs-subject-more-card__title">{$title}</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"fs-subject-more-card__text"} -->
			<p class="fs-subject-more-card__text">{$card_text}</p>
			<!-- /wp:paragraph -->

			<!-- wp:html -->
			<a class="fs-subject-more-card__button" href="{$url}">{$button_text}</a>
			<!-- /wp:html -->
		</div>
		<!-- /wp:group -->
HTML;
	}

	$heading = esc_html( $texts->get( "{$slug}_title" ) );
	$intro   = nl2br( esc_html( $texts->get( "{$slug}_intro" ) ) );

	return <<<HTML
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:heading {"level":1,"fontSize":"xxl"} -->
	<h1 class="wp-block-heading has-xxl-font-size">{$heading}</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md","style":{"typography":{"fontWeight":"300"}}} -->
	<p class="has-text-secondary-color has-text-color has-md-font-size" style="font-weight:300">{$intro}</p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"className":"fs-course-catalog"} -->
	<div class="wp-block-group fs-course-catalog">
{$cards}
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
HTML;
}

function fs_lms_theme_articles_page_blocks(): string {
	return fs_lms_theme_resource_page_blocks( 'articles', 'articles', FS_LMS_THEME_RESOURCE_ICON_ARTICLES );
}

function fs_lms_theme_trainer_page_blocks(): string {
	return fs_lms_theme_resource_page_blocks( 'tasks', 'trainer', FS_LMS_THEME_RESOURCE_ICON_TRAINER );
}

/**
 * Содержимое страниц-хабов — ссылка на паттерн (2026-09-13): страница
 * показывает актуальные тексты из настроек, а не копию на момент создания.
 *
 * @return array<string, string> Слаг страницы → ссылка на паттерн.
 */
function fs_lms_theme_resource_page_patterns(): array {
	return array(
		'articles' => '<!-- wp:pattern {"slug":"fs-lms-theme/resource-articles"} /-->',
		'tasks'    => '<!-- wp:pattern {"slug":"fs-lms-theme/resource-tasks"} /-->',
	);
}

/**
 * Заводит страницы `/articles/` и `/tasks/`, если их ещё нет (идемпотентно
 * — как и остальные автосоздаваемые страницы темы, см. `inc/SubjectPages.php`
 * для того же приёма с направлениями). В отличие от направлений, эти две
 * страницы не привязаны к плагину — тема создаёт и наполняет их сама, один
 * раз, дальше содержимое свободно правится в редакторе.
 */
function fs_lms_theme_ensure_resource_pages(): void {
	$pages = array(
		'articles' => array( 'title' => 'Учебник' ),
		'tasks'    => array( 'title' => 'Тренажёр' ),
	);

	foreach ( $pages as $slug => $page ) {
		$existing = get_page_by_path( $slug );

		if ( $existing instanceof WP_Post ) {
			continue;
		}

		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_content' => fs_lms_theme_resource_page_patterns()[ $slug ],
			)
		);

		if ( ! is_wp_error( $page_id ) && $page_id > 0 ) {
			update_post_meta( $page_id, '_wp_page_template', 'page-subject' );
		}
	}
}
add_action( 'after_switch_theme', 'fs_lms_theme_ensure_resource_pages' );

/**
 * Подстраховка для уже активной темы (правка кода без переключения темы
 * не бьёт `after_switch_theme`) — та же идемпотентная проверка, просто
 * на `init`, лёгкая (2 `get_page_by_path()` на несуществующий в 99%
 * запросов случай, кэшируется в объектном кэше WP).
 */
add_action( 'init', 'fs_lms_theme_ensure_resource_pages' );

/**
 * Версия раскладки страниц «Учебник»/«Тренажёр» — как
 * `FS_LMS_THEME_SUBJECT_PAGES_LAYOUT` у направлений
 * (`inc/SubjectPages.php`): меняется, когда вид страниц правится в коде и
 * уже созданные страницы нужно перевести на новый.
 */
const FS_LMS_THEME_RESOURCE_PAGES_LAYOUT = 4;

/**
 * Раскладка 2-3 (2026-09-07): заголовок слева и без черты-разделителя, как
 * на `/courses/`.
 *
 * `fs_lms_theme_ensure_resource_pages()` наполняет страницу ровно один раз,
 * при создании, и существующую больше не трогает — правка генератора сама
 * по себе ничего не меняет ни на локалке, ни на проде. Отсюда разовая
 * миграция: снять `textAlign` с `<h1>` и удалить блок `wp:separator`
 * `.fs-title-divider` под ним.
 *
 * Правится разметка, а не только классы: `textAlign` живёт и в атрибутах
 * блока (иначе редактор покажет старое выравнивание в тулбаре и вернёт
 * класс при первом же сохранении), и в самом `class` заголовка. Атрибут
 * снимается регуляркой по началу блока, а не точным совпадением всей
 * строки: страницы, созданные до задачи с `level:1`, лежат в базе с
 * набором атрибутов `{"textAlign":"center","fontSize":"xxl"}` — точная
 * замена мимо них промахивалась.
 *
 * Ручные правки редактора миграция не теряет: она не перезаписывает
 * содержимое целиком, а точечно снимает центрирование и убирает
 * разделитель; если пользователь уже сделал это сам, замена ничего не
 * находит и страница не трогается.
 */
function fs_lms_theme_resource_page_align_left( string $content ): string {
	$content = preg_replace(
		'~\s*<!-- wp:separator \{"className":"fs-title-divider"\} -->.*?<!-- /wp:separator -->~s',
		'',
		$content
	);

	$content = preg_replace(
		'~(<!-- wp:heading \{)"textAlign":"center",~',
		'$1',
		$content
	);

	$content = str_replace( ' has-text-align-center has-xxl-font-size', ' has-xxl-font-size', $content );

	return $content;
}

/**
 * Прогоняет миграцию по обеим страницам-хабам. Разовая: отметка о
 * выполнении — в опции, тем же приёмом, что у направлений.
 */
function fs_lms_theme_upgrade_resource_pages(): void {
	if ( (int) get_option( 'fs_lms_theme_resource_pages_layout', 0 ) >= FS_LMS_THEME_RESOURCE_PAGES_LAYOUT ) {
		return;
	}

	foreach ( array( 'articles', 'tasks' ) as $slug ) {
		$page = get_page_by_path( $slug );

		if ( ! $page instanceof WP_Post ) {
			continue;
		}

		$updated = fs_lms_theme_resource_page_align_left( $page->post_content );

		/*
		 * Раскладка 4 (2026-09-13): тексты со страницы — в «Учебник и тренажёр»
		 * (только ещё не сохранённые там поля), содержимое — ссылка на паттерн.
		 * Страницу без карточек-копии (её уже переписали руками) не трогаем.
		 */
		if ( str_contains( $updated, 'fs-subject-more-card' ) ) {
			FS_LMS_Theme_Showcase::resource_texts()->import_from_content( $slug, $updated );
			$updated = fs_lms_theme_resource_page_patterns()[ $slug ];
		}

		if ( $updated === $page->post_content ) {
			continue;
		}

		// Хук срабатывает и у анонимного посетителя — kses снимаем на время
		// записи, контент — через `wp_slash()` (см. `inc/ContentUpgrades.php`).
		$kses_active = false !== has_filter( 'content_save_pre', 'wp_filter_post_kses' );

		if ( $kses_active ) {
			kses_remove_filters();
		}

		wp_update_post(
			wp_slash(
				array(
					'ID'           => $page->ID,
					'post_content' => $updated,
				)
			)
		);

		if ( $kses_active ) {
			kses_init_filters();
		}
	}

	update_option( 'fs_lms_theme_resource_pages_layout', FS_LMS_THEME_RESOURCE_PAGES_LAYOUT );
}
add_action( 'wp_loaded', 'fs_lms_theme_upgrade_resource_pages' );
