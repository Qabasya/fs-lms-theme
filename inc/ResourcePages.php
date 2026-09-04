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
 * Содержимое страницы «Учебник»/«Тренажёр»: центрированный заголовок с
 * разделителем (по мокапу), вводный абзац, сетка из 4 карточек направлений.
 *
 * @param string $title    «Учебник» либо «Тренажёр».
 * @param string $intro    Вводный абзац под заголовком.
 * @param string $page_key 'articles' либо 'trainer' — второй аргумент `fs_lms_theme_subject_url()`.
 * @param string $icon_svg Иконка карточек (одна из констант выше).
 * @param array<string, array{text: string, button: string}> $card_content Ключ — ключ предмета, значение — текст карточки и текст кнопки (у обоих мокапов кнопка называет предмет отдельно, не общей фразой).
 */
function fs_lms_theme_resource_page_blocks( string $title, string $intro, string $page_key, string $icon_svg, array $card_content ): string {
	$cards = '';

	foreach ( fs_lms_theme_resource_subjects() as $subject_key => $subject ) {
		$url         = esc_url( fs_lms_theme_subject_url( $subject_key, $page_key ) );
		$card_text   = $card_content[ $subject_key ]['text'];
		$button_text = $card_content[ $subject_key ]['button'];

		$cards .= <<<HTML

		<!-- wp:group {"className":"fs-subject-more-card fs-subject-more-card--{$subject['modifier']}"} -->
		<div class="wp-block-group fs-subject-more-card fs-subject-more-card--{$subject['modifier']}">
			<!-- wp:html -->
			<span class="fs-subject-more-card__icon">{$icon_svg}</span>
			<!-- /wp:html -->

			<!-- wp:paragraph {"className":"fs-subject-more-card__title"} -->
			<p class="fs-subject-more-card__title">{$subject['title']}</p>
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

	return <<<HTML
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
	<h1 class="wp-block-heading has-text-align-center has-xxl-font-size">{$title}</h1>
	<!-- /wp:heading -->

	<!-- wp:separator {"className":"fs-title-divider"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity fs-title-divider"/>
	<!-- /wp:separator -->

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
	return fs_lms_theme_resource_page_blocks(
		'Учебник',
		'Учебник — это теория по всем темам экзамена, собранная в одном месте: разборы заданий, примеры решений и конспекты, которые можно открыть с компьютера и с телефона. Материалы структурированы по номерам заданий, поэтому вы всегда видите, что уже разобрано, а что осталось. Доступ открывается ученикам курсов и доступен в течение всего учебного года.',
		'articles',
		FS_LMS_THEME_RESOURCE_ICON_ARTICLES,
		array(
			'inf_ege' => array(
				'text'   => '27 заданий: от кодирования информации и таблиц истинности до программирования на Python. Каждая тема — теория, разобранные примеры и типичные ошибки на экзамене.',
				'button' => 'Открыть учебник ЕГЭ',
			),
			'inf_oge' => array(
				'text'   => 'Теория к первой части и подробные разборы практических заданий 13–15: работа с файлами, электронные таблицы и написание программы.',
				'button' => 'Открыть учебник ОГЭ',
			),
		)
	);
}

function fs_lms_theme_trainer_page_blocks(): string {
	return fs_lms_theme_resource_page_blocks(
		'Тренажёр',
		'Тренажёр — это задачи по номерам заданий с моментальной проверкой ответа. Можно решать отдельную тему, пока она не начнёт получаться, или собрать вариант целиком и уложиться в экзаменационное время. Статистика показывает, сколько задач решено и где чаще всего возникают ошибки.',
		'trainer',
		FS_LMS_THEME_RESOURCE_ICON_TRAINER,
		array(
			'inf_ege' => array(
				'text'   => 'Задачи ко всем 27 заданиям, задания с файлами и полные варианты с таймером. Ответы проверяются автоматически, к сложным задачам есть разбор решения.',
				'button' => 'Перейти в тренажёр ЕГЭ',
			),
			'inf_oge' => array(
				'text'   => 'Тестовая часть с проверкой ответа и практические задания 13–15 с файлами, которые нужно скачать, выполнить и загрузить обратно.',
				'button' => 'Перейти в тренажёр ОГЭ',
			),
		)
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
		'articles' => array( 'title' => 'Учебник', 'blocks' => 'fs_lms_theme_articles_page_blocks' ),
		'tasks'    => array( 'title' => 'Тренажёр', 'blocks' => 'fs_lms_theme_trainer_page_blocks' ),
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
				'post_content' => call_user_func( $page['blocks'] ),
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
