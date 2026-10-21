<?php
/**
 * Базовая SEO-обвязка темы: описание страницы, Open Graph/Twitter,
 * микроразметка организации и подсказки браузеру по шрифтам.
 *
 * WordPress из коробки закрывает title, canonical и sitemap
 * (`/wp-sitemap.xml`), но не выводит ни `description`, ни OG-теги, ни
 * JSON-LD об организации — этим и занимается модуль.
 *
 * Если на сайте включён полноценный SEO-плагин (Yoast, Rank Math,
 * SEOPress, AIOSEO), тема молчит: дублирующиеся мета-теги хуже, чем их
 * отсутствие, а плагин даёт редактору контроль над каждым полем.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Длина автоописания страницы, символов. */
const FS_LMS_THEME_SEO_DESCRIPTION_LENGTH = 160;

/**
 * Активен ли SEO-плагин, который сам печатает мета-теги.
 */
function fs_lms_theme_seo_plugin_active(): bool {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'AIOSEO_VERSION' );
}

/**
 * Описание текущей страницы: своё для записи/товара, иначе — слоган сайта.
 */
function fs_lms_theme_seo_description(): string {
	$description = get_bloginfo( 'description', 'display' );

	if ( is_singular() ) {
		$post = get_queried_object();

		if ( $post instanceof WP_Post ) {
			$source = '' !== $post->post_excerpt ? $post->post_excerpt : $post->post_content;
			$source = wp_strip_all_tags( strip_shortcodes( $source ), true );

			if ( '' !== trim( $source ) ) {
				$description = $source;
			}
		}
	}

	$description = trim( preg_replace( '/\s+/u', ' ', (string) $description ) ?? '' );

	if ( mb_strlen( $description ) > FS_LMS_THEME_SEO_DESCRIPTION_LENGTH ) {
		$description = rtrim( mb_substr( $description, 0, FS_LMS_THEME_SEO_DESCRIPTION_LENGTH - 1 ), " ,.;:—-" ) . '…';
	}

	return $description;
}

/**
 * Картинка для карточки в соцсетях: обложка записи, иначе логотип сайта.
 */
function fs_lms_theme_seo_image(): string {
	if ( is_singular() && has_post_thumbnail() ) {
		$url = get_the_post_thumbnail_url( null, 'full' );

		if ( is_string( $url ) ) {
			return $url;
		}
	}

	return get_theme_file_uri( 'img/logo-header.png' );
}

/**
 * `description`, Open Graph и Twitter-карточка.
 */
add_action( 'wp_head', function (): void {
	if ( fs_lms_theme_seo_plugin_active() || is_404() ) {
		return;
	}

	$description = fs_lms_theme_seo_description();
	$title       = wp_get_document_title();
	// Без строки запроса: адрес страницы с фильтрами или меткой — тот же документ, что и без них.
	$url         = home_url( (string) strtok( add_query_arg( array() ), '?' ) );

	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
		printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $description ) );
		printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $description ) );
	}

	printf( '<meta property="og:type" content="%s" />' . "\n", is_singular() ? 'article' : 'website' );
	printf( '<meta property="og:locale" content="%s" />' . "\n", esc_attr( get_locale() ) );
	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $url ) );
	printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( fs_lms_theme_seo_image() ) );
	printf( '<meta name="twitter:card" content="summary_large_image" />' . "\n" );
	printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );
}, 5 );

/**
 * Микроразметка организации (schema.org/EducationalOrganization) — одна на
 * весь сайт, печатаем на главной: поисковикам достаточно одной страницы с
 * описанием организации, дублировать на каждой не нужно.
 *
 * Этап 4 (2026-09-13): контакты — из «Настроек сайта»
 * (`inc/Showcase/Site_Settings.php`), те же, что в шапке и подвале; были
 * константами здесь.
 */
add_action( 'wp_head', function (): void {
	if ( fs_lms_theme_seo_plugin_active() || ! is_front_page() ) {
		return;
	}

	$settings = FS_LMS_Theme_Showcase::settings();

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'EducationalOrganization',
		'name'          => get_bloginfo( 'name' ),
		'description'   => get_bloginfo( 'description', 'display' ),
		'url'           => home_url( '/' ),
		'logo'          => get_theme_file_uri( 'img/logo-header.png' ),
		'email'         => $settings->get( 'email' ),
		'telephone'     => $settings->get( 'phone' ),
		'address'       => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $settings->get( 'street' ),
			'addressLocality' => $settings->get( 'city' ),
			'postalCode'      => $settings->get( 'zip' ),
			'addressCountry'  => 'RU',
		),
	);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
	);
}, 5 );


/**
 * Значение настройки Rank Math; null — плагина нет или значение пустое.
 *
 * @param string $key Ключ вида `titles.price_range`.
 *
 * @return mixed
 */
function fs_lms_theme_seo_rank_math_setting( string $key ) {
	if ( ! class_exists( '\RankMath\Helper' ) ) {
		return null;
	}

	$value = \RankMath\Helper::get_settings( $key );

	return ( '' === $value || array() === $value ) ? null : $value;
}

/**
 * Часы работы из «Местное SEO» Rank Math в виде `OpeningHoursSpecification`.
 *
 * Для типа «Образовательная организация» Rank Math часы в разметку не выводит,
 * хотя редактор их заполнил: берём те же данные, чтобы не вести их в двух местах.
 *
 * @return array<int, array<string, mixed>>
 */
function fs_lms_theme_seo_opening_hours(): array {
	$rows = fs_lms_theme_seo_rank_math_setting( 'titles.opening_hours' );

	if ( ! is_array( $rows ) ) {
		return array();
	}

	$specs = array();

	foreach ( $rows as $row ) {
		$day   = is_array( $row ) ? trim( (string) ( $row['day'] ?? '' ) ) : '';
		$parts = is_array( $row ) ? explode( '-', (string) ( $row['time'] ?? '' ) ) : array();

		if ( '' === $day || 2 !== count( $parts ) ) {
			continue;
		}

		$specs[] = array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => $day,
			'opens'     => trim( $parts[0] ),
			'closes'    => trim( $parts[1] ),
		);
	}

	return $specs;
}

/**
 * Дополнение к организации: часы работы и ценовой диапазон.
 *
 * Тот же `@id`, что у организации Rank Math, — поисковики соединяют узлы с одним
 * идентификатором. Только главная, как и сама организация.
 */
add_action(
	'wp_head',
	function (): void {
		if ( ! is_front_page() || ! class_exists( '\RankMath\Helper' ) ) {
			return;
		}

		$hours = fs_lms_theme_seo_opening_hours();
		$price = fs_lms_theme_seo_rank_math_setting( 'titles.price_range' );

		if ( array() === $hours && ! is_string( $price ) ) {
			return;
		}

		$node = array(
			'@context' => 'https://schema.org',
			'@type'    => 'EducationalOrganization',
			'@id'      => home_url( '/#organization' ),
		);

		if ( array() !== $hours ) {
			$node['openingHoursSpecification'] = $hours;
		}

		if ( is_string( $price ) ) {
			$node['priceRange'] = $price;
		}

		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( $node, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG )
		);
	},
	6
);

/**
 * `FAQPage` для аккордеона «Частые вопросы» — на главной и страницах направлений.
 *
 * Разметка строится из тех же записей «Вопросы», что и аккордеон на странице, —
 * текст в разметке и на экране совпадает. От SEO-плагина не зависит: свой
 * аккордеон Rank Math не знает.
 */
add_action(
	'wp_head',
	function (): void {
		if ( is_admin() || ! class_exists( 'FS_LMS_Theme_Showcase' ) ) {
			return;
		}

		$questions = FS_LMS_Theme_Showcase::questions();

		if ( ! $questions->faq_is_displayed() ) {
			return;
		}

		$entities = array();

		foreach ( $questions->faq_schema_items() as $item ) {
			if ( '' === $item['question'] || '' === $item['answer'] ) {
				continue;
			}

			$entities[] = array(
				'@type'          => 'Question',
				'name'           => $item['question'],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $item['answer'],
				),
			);
		}

		if ( array() === $entities ) {
			return;
		}

		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode(
				array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $entities,
				),
				JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG
			)
		);
	},
	6
);
