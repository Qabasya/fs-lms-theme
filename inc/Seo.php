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

/** Контакты организации — те же, что в шапке и футере. */
const FS_LMS_THEME_ORG_PHONE   = '+7 995 326 44 86';
const FS_LMS_THEME_ORG_EMAIL   = 'info@future-step.ru';
const FS_LMS_THEME_ORG_STREET  = 'ул. Черняховского, д. 6, каб. 316';
const FS_LMS_THEME_ORG_CITY    = 'Калининград';
const FS_LMS_THEME_ORG_ZIP     = '236006';

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
	$url         = home_url( add_query_arg( array() ) );

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
 */
add_action( 'wp_head', function (): void {
	if ( fs_lms_theme_seo_plugin_active() || ! is_front_page() ) {
		return;
	}

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'EducationalOrganization',
		'name'          => get_bloginfo( 'name' ),
		'description'   => get_bloginfo( 'description', 'display' ),
		'url'           => home_url( '/' ),
		'logo'          => get_theme_file_uri( 'img/logo-header.png' ),
		'email'         => FS_LMS_THEME_ORG_EMAIL,
		'telephone'     => FS_LMS_THEME_ORG_PHONE,
		'address'       => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => FS_LMS_THEME_ORG_STREET,
			'addressLocality' => FS_LMS_THEME_ORG_CITY,
			'postalCode'      => FS_LMS_THEME_ORG_ZIP,
			'addressCountry'  => 'RU',
		),
	);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
	);
}, 5 );

/**
 * Шрифты грузятся с Google Fonts (`inc/Assets.php`) — заранее открываем
 * соединение с CDN, иначе браузер тратит на это время уже во время
 * отрисовки текста.
 */
add_filter( 'wp_resource_hints', function ( array $hints, string $relation ): array {
	if ( 'preconnect' !== $relation ) {
		return $hints;
	}

	$hints[] = array(
		'href'        => 'https://fonts.gstatic.com',
		'crossorigin' => 'anonymous',
	);

	return $hints;
}, 10, 2 );
