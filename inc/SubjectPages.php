<?php
/**
 * Автосборка страниц направлений (Фаза 13) на контенте темы.
 *
 * Страницы предметов (`/inf_ege/`, `/inf_oge/`, `/python/`, `/robo/`)
 * создаёт плагин (`SubjectPagesService`) — пустыми. Раньше вставка секций
 * темы в них была ручным шагом редактора (Фаза 13.1–13.4), из-за чего
 * только что заведённый предмет отдавал пустую страницу до тех пор, пока
 * кто-то не соберёт её в редакторе руками. Этот модуль закрывает разрыв:
 * при первом открытии такой страницы, если её контент пуст, тема один раз
 * записывает в неё готовый набор паттернов направления.
 *
 * Идемпотентно и неразрушающе: непустой контент не трогается никогда, так
 * что любые последующие правки редактора переживают этот код — повторно он
 * на этой странице уже не сработает.
 *
 * Определение направления — по слагу страницы, а не запросом к CPT плагина
 * (тот же приём, что `is_page('about')`/`is_page('courses')` в
 * `patterns/header-nav.php`): граница «тема не читает данные плагина»
 * (Фаза 7) остаётся нетронутой.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Слаг страницы направления → паттерн hero этого направления.
 *
 * Ключи — те же, что у `fs_lms_theme_subject_url()` (Фаза 13, решение 2).
 * Остальные секции у всех четырёх общие, отличается только hero.
 *
 * @return array<string, string>
 */
function fs_lms_theme_subject_hero_patterns(): array {
	return array(
		'inf_ege' => 'fs-lms-theme/subject-hero',
		'inf_oge' => 'fs-lms-theme/subject-hero-oge',
		'python'  => 'fs-lms-theme/subject-hero-python',
		'robo'    => 'fs-lms-theme/subject-hero-robo',
	);
}

/**
 * Секция «Хочешь больше?» с ссылками на учебник/тренажёр конкретного предмета.
 *
 * Разметка — копия `patterns/subject-more.php`, но с `$subject_key`
 * параметром: сам паттерн статичен (его `$subject_key` правит редактор
 * после вставки, см. заголовок файла паттерна), а автосборке нужен нужный
 * ключ сразу. Дублирование осознанное — тот же принцип, что у 4 копий
 * `subject-hero-*.php`.
 *
 * 2026-09-04 (по указанию пользователя): здесь оставалась ДОредизайновая
 * версия карточек (`is-style-card`, заголовок-ссылка «Открыть учебник»,
 * без иконки и кнопки) — редизайн задачи 1 обновил только сам паттерн, а
 * страницы направлений собираются этой функцией, поэтому на них по-прежнему
 * жил старый вид. Теперь разметка и тексты — из мокапа «ЕГЭ информатика -
 * мокап.dc.html» (DesignSync), название экзамена/курса подставляется по
 * направлению.
 *
 * @param string $subject_key Ключ предмета (`inf_ege`, `inf_oge`, `python`, `robo`).
 */
function fs_lms_theme_subject_more_blocks( string $subject_key ): string {
	$articles_url = esc_url( fs_lms_theme_subject_url( $subject_key, 'articles' ) );
	$trainer_url  = esc_url( fs_lms_theme_subject_url( $subject_key, 'trainer' ) );

	$texts = array(
		'inf_ege' => array(
			'articles' => 'Собрали для тебя статьи по каждой теме в ЕГЭ: теория, разбор формата задания и приёмы, которые экономят время на экзамене.',
			'trainer'  => 'Сборник заданий по каждой теме ЕГЭ с подробным решением: тренируйся по одному номеру или собирай вариант целиком.',
		),
		'inf_oge' => array(
			'articles' => 'Собрали для тебя статьи по каждой теме в ОГЭ: теория, разбор формата задания и приёмы, которые экономят время на экзамене.',
			'trainer'  => 'Сборник заданий по каждой теме ОГЭ с подробным решением: тренируйся по одному номеру или собирай вариант целиком.',
		),
		'python'  => array(
			'articles' => 'Собрали для тебя статьи по каждой теме курса: теория, разбор синтаксиса и приёмы, которые ускоряют написание кода.',
			'trainer'  => 'Сборник задач по каждой теме курса с подробным решением: тренируйся по одной теме или собирай проект целиком.',
		),
		'robo'    => array(
			'articles' => 'Собрали для тебя статьи по каждой теме курса: теория, разбор схем и приёмы, которые помогают быстрее собрать робота.',
			'trainer'  => 'Сборник заданий по каждой теме курса с подробным решением: тренируйся по одной теме или собирай проект целиком.',
		),
	);

	$articles_text = $texts[ $subject_key ]['articles'] ?? $texts['inf_ege']['articles'];
	$trainer_text  = $texts[ $subject_key ]['trainer'] ?? $texts['inf_ege']['trainer'];

	// Иконки — общие с страницами-хабами «Учебник»/«Тренажёр»
	// (`inc/ResourcePages.php`), чтобы не держать третью копию SVG.
	$icon_articles = FS_LMS_THEME_RESOURCE_ICON_ARTICLES;
	$icon_trainer  = FS_LMS_THEME_RESOURCE_ICON_TRAINER;

	return <<<HTML
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:heading {"fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-xxl-font-size">Хочешь больше?</h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"1.25rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-subject-more-card fs-subject-more-card--articles"} -->
			<div class="wp-block-group fs-subject-more-card fs-subject-more-card--articles">
				<!-- wp:html -->
				<span class="fs-subject-more-card__icon has-accent-color has-accent-soft-background-color has-text-color has-background">{$icon_articles}</span>
				<!-- /wp:html -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__title"} -->
				<p class="fs-subject-more-card__title">Учебник</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__text"} -->
				<p class="fs-subject-more-card__text">{$articles_text}</p>
				<!-- /wp:paragraph -->

				<!-- wp:html -->
				<a class="fs-subject-more-card__button" href="{$articles_url}">Открыть учебник</a>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-subject-more-card fs-subject-more-card--trainer"} -->
			<div class="wp-block-group fs-subject-more-card fs-subject-more-card--trainer">
				<!-- wp:html -->
				<span class="fs-subject-more-card__icon has-accent-2-color has-accent-2-soft-background-color has-text-color has-background">{$icon_trainer}</span>
				<!-- /wp:html -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__title"} -->
				<p class="fs-subject-more-card__title">Тренажёр</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__text"} -->
				<p class="fs-subject-more-card__text">{$trainer_text}</p>
				<!-- /wp:paragraph -->

				<!-- wp:html -->
				<a class="fs-subject-more-card__button" href="{$trainer_url}">Открыть тренажёр</a>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
HTML;
}

/**
 * Секция «Как устроены занятия» — та же разметка, что у паттерна
 * `intensive-split.php`, но вставляется НЕ как `wp:pattern`-ссылка, а как
 * обычные блоки (задача 6, tasks.md, 2026-09-04).
 *
 * `wp:pattern` — это живая ссылка на зарегистрированный PHP-паттерн: на
 * главной (`templates/front-page.html`) она и нужна такой, тот же контент
 * для всех. На странице направления это раньше означало, что текст/фото
 * секции нельзя было поменять по-предметно — редактор всегда видел (и
 * правил бы вникуда) содержимое общего паттерна. Раз секция вставляется
 * как обычные блоки, WordPress сохраняет их в `post_content` конкретной
 * страницы направления — дальше редактор меняет текст/фото именно на ней,
 * не трогая остальные три (тот же приём, что уже применён к
 * `fs_lms_theme_subject_more_blocks()` выше).
 *
 * Стартовый контент — копия `patterns/intensive-split.php` (общий текст,
 * "Формат одинаковый на всех направлениях"); после первой вставки его
 * можно свободно переписать под конкретный предмет.
 */
function fs_lms_theme_subject_intensive_blocks(): string {
	$photo_url   = esc_url( get_theme_file_uri( 'img/photo.png' ) );
	$courses_url = esc_url( home_url( '/courses/' ) );

	return <<<HTML
<!-- wp:group {"className":"fs-section"} -->
<div id="lessons" class="wp-block-group fs-section">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"className":"fs-aspect-4-3","style":{"border":{"radius":"var:preset|spacing|md"}}} -->
			<figure class="wp-block-image fs-aspect-4-3" style="border-radius:var(--wp--preset--spacing--md)"><img src="{$photo_url}" alt="Фото занятия" /></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"480px"} -->
		<div class="wp-block-column" style="flex-basis:480px">
			<!-- wp:heading {"fontSize":"xxl"} -->
			<h2 class="wp-block-heading has-xxl-font-size">Как устроены занятия</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"base"} -->
			<p class="has-text-secondary-color has-text-color has-base-font-size">Формат одинаковый на всех направлениях — меняется только программа.</p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"className":"fs-checklist"} -->
			<div class="wp-block-group fs-checklist">
				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Занятия 2 раза в неделю</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Параллельная онлайн-трансляция каждого занятия</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Видеозаписи занятий в личном кабинете</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Домашнее задание после каждого занятия</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Регулярные контрольные и пробные экзамены</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Индивидуальные консультации с преподавателем</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Вся теория и шпаргалки в личном кабинете</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Дополнительные материалы по каждой теме</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"className":"fs-price-plaque"} -->
			<div class="wp-block-group fs-price-plaque">
				<!-- wp:group {"className":"fs-price-plaque__part"} -->
				<div class="wp-block-group fs-price-plaque__part">
					<!-- wp:paragraph {"className":"fs-price-plaque__value"} -->
					<p class="fs-price-plaque__value">2 часа</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-price-plaque__label"} -->
					<p class="fs-price-plaque__label">одно занятие</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"fs-price-plaque__part"} -->
				<div class="wp-block-group fs-price-plaque__part">
					<!-- wp:paragraph {"className":"fs-price-plaque__value fs-price-plaque__value--accent"} -->
					<p class="fs-price-plaque__value fs-price-plaque__value--accent">800 ₽</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-price-plaque__label"} -->
					<p class="fs-price-plaque__label">за час</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"accent-2"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-accent-2-background-color has-background wp-element-button" href="#signup">Записаться</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"backgroundColor":"white","textColor":"text-secondary","className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-secondary-color has-white-background-color has-text-color has-background wp-element-button" href="{$courses_url}">Все направления</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
HTML;
}

/**
 * Полный стартовый контент страницы направления: hero + общие секции.
 *
 * @param string $subject_key Ключ предмета.
 */
function fs_lms_theme_subject_page_blocks( string $subject_key ): string {
	$hero_pattern = fs_lms_theme_subject_hero_patterns()[ $subject_key ] ?? null;

	if ( null === $hero_pattern ) {
		return '';
	}

	$sections = array(
		sprintf( '<!-- wp:pattern {"slug":"%s"} /-->', $hero_pattern ),
		'<!-- wp:pattern {"slug":"fs-lms-theme/features-grid"} /-->',
		fs_lms_theme_subject_intensive_blocks(),
		'<!-- wp:pattern {"slug":"fs-lms-theme/subject-contact"} /-->',
	);

	if ( fs_lms_theme_subject_has_resources( $subject_key ) ) {
		$sections[] = fs_lms_theme_subject_more_blocks( $subject_key );
	}

	return implode( "\n\n", $sections ) . "\n";
}

/**
 * Есть ли у направления учебник и тренажёр.
 *
 * Плагин заводит эти два раздела только предмету с собственным банком
 * заданий/статей (`SubjectPageType::forSubject()`: `requiresBank()`), поэтому
 * у Python и Робототехники их нет — есть только корневая страница и курсы.
 * Секция «Хочешь больше?» ведёт ровно туда, и без этих страниц её ссылки
 * упираются в 404, который `redirect_guess_404_permalink()` уводит на
 * учебник/тренажёр ЧУЖОГО предмета (`/python/articles/` → `/inf_ege/articles/`
 * — тот же угадыватель, что ломал «Курсы» в меню, см. `inc/StaticPages.php`).
 *
 * Проверка — по факту существования страниц, а не по списку предметов:
 * банк предмету можно завести и позже, и тогда секция появится на вновь
 * собираемых страницах сама.
 *
 * @param string $subject_key Ключ предмета.
 */
function fs_lms_theme_subject_has_resources( string $subject_key ): bool {
	foreach ( array( 'articles', 'trainer' ) as $section ) {
		if ( ! get_page_by_path( $subject_key . '/' . $section ) instanceof WP_Post ) {
			return false;
		}
	}

	return true;
}

/**
 * Вырезает секцию «Хочешь больше?» из готового содержимого страницы.
 *
 * Ищет по классу компонента (`fs-subject-more-card`), а не точным
 * совпадением сгенерированной разметки: секцию могли поправить в редакторе,
 * и точное сравнение бы её тогда не нашло. Разбор блоками, а не регуляркой,
 * — чтобы удалить секцию целиком вместе с обёрткой `wp:group`, не оставив
 * висящих открывающих/закрывающих комментариев.
 *
 * @param string $content Содержимое страницы направления.
 *
 * @return string Содержимое без секции (или исходное, если её там нет).
 */
function fs_lms_theme_strip_subject_more( string $content ): string {
	$blocks  = parse_blocks( $content );
	$kept    = array();
	$changed = false;

	foreach ( $blocks as $block ) {
		if ( null !== $block['blockName'] && str_contains( serialize_block( $block ), 'fs-subject-more-card' ) ) {
			$changed = true;
			continue;
		}

		$kept[] = $block;
	}

	return $changed ? serialize_blocks( $kept ) : $content;
}

/**
 * Заполняет пустую страницу направления секциями темы при её открытии.
 */
function fs_lms_theme_seed_subject_page(): void {
	if ( is_admin() || ! is_page() || ! is_main_query() ) {
		return;
	}

	$page = get_queried_object();

	if ( ! $page instanceof WP_Post || '' !== trim( $page->post_content ) ) {
		return;
	}

	$blocks = fs_lms_theme_subject_page_blocks( $page->post_name );

	if ( '' === $blocks ) {
		return;
	}

	wp_update_post(
		array(
			'ID'           => $page->ID,
			'post_content' => $blocks,
		)
	);

	// Ширина 1200px, как у остальных страниц темы (Фаза 16, общее правило);
	// без h1 wp-block-post-title (tasks.md, п. после разделителя — заголовок
	// предмета дублировал h1 внутри hero-паттерна).
	update_post_meta( $page->ID, '_wp_page_template', 'page-subject' );

	wp_safe_redirect( get_permalink( $page->ID ) );
	exit;
}
add_action( 'template_redirect', 'fs_lms_theme_seed_subject_page' );

/**
 * Переводит вводный абзац секции «Как устроены занятия» с размера `md` на
 * `base` (раскладка 4).
 *
 * 2026-09-13 (tasks.md, шаг 9): абзац «Формат одинаковый на всех
 * направлениях…» по указанию пользователя — размер `base`. В паттерне и в
 * стартовом контенте (`fs_lms_theme_subject_intensive_blocks()`) это
 * исправлено, но на уже созданных страницах направлений секция лежит копией
 * в `post_content` — там абзац остался `md`, а в редакторе размер поменять
 * не удалось.
 *
 * Секцию ищем по классу чек-листа (`fs-checklist`), абзац — по размеру
 * `md`, а не по тексту: текст на странице могли переписать под предмет,
 * а других абзацев `md` в секции нет. Разбор блоками, а не заменой строки —
 * меняется и атрибут блока, и класс в разметке, иначе редактор посчитал бы
 * блок повреждённым.
 *
 * `md` проверяется и в атрибуте, и в классе: на странице «Робототехника»
 * атрибут уже поправили руками на `base`, а класс `has-md-font-size`
 * остался — такой блок тоже доводим до согласованного вида.
 *
 * @param string $content Содержимое страницы направления.
 *
 * @return string Содержимое с абзацем `base` (или исходное, если менять нечего).
 */
function fs_lms_theme_subject_intensive_lead_to_base( string $content ): string {
	$blocks  = parse_blocks( $content );
	$changed = false;

	foreach ( $blocks as $index => $block ) {
		if ( null !== $block['blockName'] && str_contains( serialize_block( $block ), 'fs-checklist' ) ) {
			$blocks[ $index ] = fs_lms_theme_paragraphs_md_to_base( $block, $changed );
		}
	}

	return $changed ? serialize_blocks( $blocks ) : $content;
}

/**
 * Рекурсивно переводит абзацы размера `md` внутри блока на `base`.
 *
 * @param array $block   Блок в формате `parse_blocks()`.
 * @param bool  $changed Становится `true`, если хоть один абзац изменён.
 *
 * @return array Блок с изменёнными абзацами.
 */
function fs_lms_theme_paragraphs_md_to_base( array $block, bool &$changed ): array {
	$is_md = 'md' === ( $block['attrs']['fontSize'] ?? '' ) || str_contains( $block['innerHTML'], 'has-md-font-size' );

	if ( 'core/paragraph' === $block['blockName'] && $is_md ) {
		$block['attrs']['fontSize'] = 'base';
		$block['innerHTML']         = str_replace( 'has-md-font-size', 'has-base-font-size', $block['innerHTML'] );
		$block['innerContent']      = array_map(
			static function ( $part ) {
				return is_string( $part ) ? str_replace( 'has-md-font-size', 'has-base-font-size', $part ) : $part;
			},
			$block['innerContent']
		);
		$changed                    = true;
	}

	foreach ( $block['innerBlocks'] as $index => $inner ) {
		$block['innerBlocks'][ $index ] = fs_lms_theme_paragraphs_md_to_base( $inner, $changed );
	}

	return $block;
}

/**
 * Версия «раскладки» страниц направлений. Меняется, когда очередная секция
 * переезжает со ссылки на паттерн в собственные блоки страницы и уже
 * созданные страницы нужно перевести на новый вид.
 *
 * 4 (2026-09-13) — абзац «Формат одинаковый…» с `md` на `base`
 * (`fs_lms_theme_subject_intensive_lead_to_base()`).
 */
const FS_LMS_THEME_SUBJECT_PAGES_LAYOUT = 4;

/**
 * BugFix.5 (2026-09-05): разворачивает ссылки на общие паттерны в блоки
 * самой страницы направления.
 *
 * Заполнение страницы (`fs_lms_theme_seed_subject_page()`) срабатывает
 * только на ПУСТОМ `post_content`, поэтому страницы, созданные до задачи 6
 * (2026-09-04), так и остались со ссылкой `<!-- wp:pattern
 * {"slug":"fs-lms-theme/intensive-split"} /-->`. А `wp:pattern` — это живая
 * ссылка на общий PHP-паттерн: редактор показывает её содержимое, но правки
 * идти некуда, текст один на все четыре направления. Отсюда и вопрос «как
 * поменять текст у других направлений» — никак, пока ссылка не развёрнута.
 *
 * Замена — ровно то же содержимое, что печатал паттерн, поэтому страница
 * выглядит как раньше; меняется только то, что блоки теперь лежат в
 * `post_content` конкретной страницы и правятся по-предметно. Разовая:
 * отметка о выполнении хранится в опции, да и повторный проход ничего не
 * найдёт — ссылок уже нет.
 */
function fs_lms_theme_upgrade_subject_pages(): void {
	if ( (int) get_option( 'fs_lms_theme_subject_pages_layout', 0 ) >= FS_LMS_THEME_SUBJECT_PAGES_LAYOUT ) {
		return;
	}

	foreach ( array_keys( fs_lms_theme_subject_hero_patterns() ) as $subject_key ) {
		$page = get_page_by_path( $subject_key );

		if ( ! $page instanceof WP_Post ) {
			continue;
		}

		$content = $page->post_content;
		$updated = str_replace(
			array(
				'<!-- wp:pattern {"slug":"fs-lms-theme/intensive-split"} /-->',
				'<!-- wp:pattern {"slug":"fs-lms-theme/subject-more"} /-->',
			),
			array(
				fs_lms_theme_subject_intensive_blocks(),
				fs_lms_theme_subject_more_blocks( $subject_key ),
			),
			$content
		);

		/**
		 * Раскладка 3 (2026-09-07): у направления без учебника/тренажёра
		 * секция «Хочешь больше?» ведёт в никуда — убираем её со страниц,
		 * собранных до того, как эта проверка появилась.
		 */
		if ( ! fs_lms_theme_subject_has_resources( $subject_key ) ) {
			$updated = fs_lms_theme_strip_subject_more( $updated );
		}

		$updated = fs_lms_theme_subject_intensive_lead_to_base( $updated );

		if ( $updated === $content ) {
			continue;
		}

		/*
		 * `wp_loaded` срабатывает и на запросе анонимного посетителя, а у него
		 * контент при сохранении проходит kses — со страницы пропало бы всё,
		 * чего нет в белом списке (так на проде уже пропадали SVG-иконки, см.
		 * `inc/ContentUpgrades.php`). Меняем только известные фрагменты, поэтому
		 * фильтр снимаем на время записи.
		 */
		$kses_active = false !== has_filter( 'content_save_pre', 'wp_filter_post_kses' );

		if ( $kses_active ) {
			kses_remove_filters();
		}

		// `wp_insert_post()` снимает слеши с данных (`wp_unslash()`), а
		// `serialize_blocks()` пишет в атрибуты экранирование вида `-`:
		// без `wp_slash()` обратные слеши пропали бы и атрибуты блоков сломались.
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

	update_option( 'fs_lms_theme_subject_pages_layout', FS_LMS_THEME_SUBJECT_PAGES_LAYOUT );
}
add_action( 'wp_loaded', 'fs_lms_theme_upgrade_subject_pages' );
