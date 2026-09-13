<?php
/**
 * Карусели главной с картинками (этап 1, 2026-09-13): выпускники, логотипы
 * вузов.
 *
 * Поверх общей механики `FS_LMS_Theme_Content_Type` — изображение записи
 * (фото/логотип) со столбцом-миниатюрой в списке админки
 * (`FS_LMS_Theme_Featured_Image`) и сборка слайдов Splide из опубликованных
 * записей.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class FS_LMS_Theme_Showcase_Type extends FS_LMS_Theme_Content_Type {

	use FS_LMS_Theme_Featured_Image;

	/** Разметка одного слайда карусели. */
	abstract protected function render_slide( WP_Post $post ): string;

	protected function supports(): array {
		return array( 'title', 'thumbnail', 'page-attributes' );
	}

	/** Слайды всех опубликованных записей подряд. */
	public function slides_markup(): string {
		return implode( "\n\n", array_map( array( $this, 'render_slide' ), $this->items() ) );
	}
}
