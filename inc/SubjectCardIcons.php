<?php
/**
 * Иконки карточек «Учебник»/«Тренажёр» (`.fs-subject-more-card__icon`)
 * подставляются при выводе страницы, а не хранятся в её контенте.
 *
 * BugFix (2026-09-12): на проде иконки пропали на страницах направлений и
 * на хабах `/articles/`, `/tasks/` — `<span>` иконки пустой. Эти страницы
 * тема записывает в базу сама (`inc/SubjectPages.php`,
 * `inc/ResourcePages.php`) на хуках `init`/`wp_loaded`/`template_redirect`,
 * то есть в том числе во время запроса анонимного посетителя. У него нет
 * права `unfiltered_html`, WordPress пропускает контент через kses, а тот
 * вырезает `<svg>`. Локально страницы создавались под администратором, и
 * иконки уцелели.
 *
 * Фильтр заполняет только пустой `<span>`: там, где SVG в контенте есть,
 * разметка не меняется. Уже испорченные страницы чинятся без миграции.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Subject_Card_Icons {

	private const CARD_CLASS = 'fs-subject-more-card';

	private const EMPTY_ICON = '#(<span class="fs-subject-more-card__icon[^"]*">)\s*(</span>)#u';

	public function register(): void {
		add_filter( 'render_block_core/group', array( $this, 'fill_empty_icon' ), 10, 2 );
	}

	/**
	 * @param array<string, mixed> $block Разобранный блок `core/group`.
	 */
	public function fill_empty_icon( string $content, array $block ): string {
		$class_name = (string) ( $block['attrs']['className'] ?? '' );

		if ( false === strpos( $class_name, self::CARD_CLASS ) ) {
			return $content;
		}

		$icon = $this->icon_for( $class_name );

		if ( '' === $icon ) {
			return $content;
		}

		return (string) preg_replace_callback(
			self::EMPTY_ICON,
			static fn ( array $matches ): string => $matches[1] . $icon . $matches[2],
			$content,
			1
		);
	}

	/**
	 * На страницах направлений тип карточки задан модификатором
	 * (`--articles`/`--trainer`). На хабах модификатор — предмет
	 * (`--ege`/`--oge`), а тип — сама страница: все карточки `/tasks/` —
	 * тренажёр, все карточки `/articles/` — учебник.
	 */
	private function icon_for( string $class_name ): string {
		if ( false !== strpos( $class_name, self::CARD_CLASS . '--trainer' ) || is_page( 'tasks' ) ) {
			return FS_LMS_THEME_RESOURCE_ICON_TRAINER;
		}

		if ( false !== strpos( $class_name, self::CARD_CLASS . '--articles' ) || is_page( 'articles' ) ) {
			return FS_LMS_THEME_RESOURCE_ICON_ARTICLES;
		}

		return '';
	}
}

( new FS_LMS_Theme_Subject_Card_Icons() )->register();
