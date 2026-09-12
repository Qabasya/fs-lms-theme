<?php
/**
 * Разовые правки уже сохранённого контента страниц.
 *
 * Паттерн, вставленный на страницу из инсёртера, сохраняется в её
 * `post_content` блоками — правка файла паттерна до такой страницы не
 * доходит. Здесь — точечные замены известного фрагмента, каждая
 * выполняется один раз (отметка в опции).
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Лицензия на образовательную деятельность в реестре Рособрнадзора. */
const FS_LMS_THEME_LICENSE_URL = 'https://islod.obrnadzor.gov.ru/view/164117';

final class FS_LMS_Theme_Content_Upgrades {

	private const OPTION = 'fs_lms_theme_content_upgrades';

	public function register(): void {
		add_action( 'wp_loaded', array( $this, 'run' ) );
	}

	public function run(): void {
		$done    = (array) get_option( self::OPTION, array() );
		$pending = array_diff_key( $this->upgrades(), array_flip( $done ) );

		if ( array() === $pending ) {
			return;
		}

		foreach ( $pending as $id => $upgrade ) {
			$this->replace_in_page( $upgrade['page'], $upgrade['search'], $upgrade['replace'] );
			$done[] = $id;
		}

		update_option( self::OPTION, $done );
	}

	/**
	 * @return array<string, array{page: string, search: string, replace: string}>
	 */
	private function upgrades(): array {
		return array(
			// BugFix.5 (2026-09-12): заглушка `href="#"` у ссылки на лицензию
			// (`patterns/about-header.php`).
			'about-license-link' => array(
				'page'    => 'about',
				'search'  => '<a href="#">Посмотреть лицензию',
				'replace' => sprintf( '<a href="%s" target="_blank" rel="noopener">Посмотреть лицензию', esc_url( FS_LMS_THEME_LICENSE_URL ) ),
			),
		);
	}

	private function replace_in_page( string $slug, string $search, string $replace ): void {
		$page = get_page_by_path( $slug );

		if ( ! $page instanceof WP_Post || false === strpos( $page->post_content, $search ) ) {
			return;
		}

		/*
		 * Хук срабатывает и на запросе анонимного посетителя, а у него
		 * контент при сохранении проходит kses: из остальной страницы
		 * пропало бы всё, чего нет в белом списке, — так на проде уже
		 * пропали SVG-иконки (`inc/SubjectCardIcons.php`). Меняется только
		 * известный фрагмент сохранённого контента, поэтому фильтр снимаем
		 * на время записи.
		 */
		$kses_active = false !== has_filter( 'content_save_pre', 'wp_filter_post_kses' );

		if ( $kses_active ) {
			kses_remove_filters();
		}

		// `wp_update_post()` снимает слеши сам — без `wp_slash()` пострадали
		// бы экранированные символы в JSON комментариев блоков.
		wp_update_post( wp_slash( array(
			'ID'           => $page->ID,
			'post_content' => str_replace( $search, $replace, $page->post_content ),
		) ) );

		if ( $kses_active ) {
			kses_init_filters();
		}
	}
}

( new FS_LMS_Theme_Content_Upgrades() )->register();
