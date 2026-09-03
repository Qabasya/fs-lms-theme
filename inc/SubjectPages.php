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
 * @param string $subject_key Ключ предмета (`inf_ege`, `inf_oge`, `python`, `robo`).
 */
function fs_lms_theme_subject_more_blocks( string $subject_key ): string {
	$articles_url = esc_url( fs_lms_theme_subject_url( $subject_key, 'articles' ) );
	$trainer_url  = esc_url( fs_lms_theme_subject_url( $subject_key, 'trainer' ) );

	return <<<HTML
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Хочешь больше?</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textAlign":"center","textColor":"text-secondary","fontSize":"md"} -->
	<p class="has-text-align-center has-text-secondary-color has-text-color has-md-font-size">Учебник с теорией и тренажёр с заданиями доступны каждому ученику направления.</p>
	<!-- /wp:paragraph -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"1.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:html -->
			<a href="{$articles_url}" class="wp-block-group is-style-card fs-subject-more-card">
				<span class="fs-subject-more-card__icon"><svg width="22" height="22" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 2.5h6.2L15.5 6.8V17.5H5V2.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/><path d="M11 3v4.3h4.3" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/></svg></span>
				<span class="fs-subject-more-card__title">Открыть учебник</span>
				<span class="fs-subject-more-card__text">Конспекты, шпаргалки и памятки по каждой теме направления.</span>
			</a>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:html -->
			<a href="{$trainer_url}" class="wp-block-group is-style-card fs-subject-more-card">
				<span class="fs-subject-more-card__icon"><svg width="22" height="22" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 3 12 7l4.5.6-3.3 3.2.8 4.5L10 13.2 6 15.5l.8-4.5L3.5 7.7 8 7z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/></svg></span>
				<span class="fs-subject-more-card__title">Открыть тренажёр</span>
				<span class="fs-subject-more-card__text">Задания по темам направления с проверкой и разбором ошибок.</span>
			</a>
			<!-- /wp:html -->
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
		'<!-- wp:pattern {"slug":"fs-lms-theme/intensive-split"} /-->',
		'<!-- wp:pattern {"slug":"fs-lms-theme/subject-contact"} /-->',
		fs_lms_theme_subject_more_blocks( $subject_key ),
	);

	return implode( "\n\n", $sections ) . "\n";
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

	// Ширина 1200px, как у остальных страниц темы (Фаза 16, общее правило).
	update_post_meta( $page->ID, '_wp_page_template', 'page-wide' );

	wp_safe_redirect( get_permalink( $page->ID ) );
	exit;
}
add_action( 'template_redirect', 'fs_lms_theme_seed_subject_page' );
