<?php
/**
 * Карусели главной с картинками (этап 1, 2026-09-13): выпускники, логотипы
 * вузов.
 *
 * Поверх общей механики `FS_LMS_Theme_Content_Type` — изображение записи
 * (фото/логотип) со столбцом-миниатюрой в списке админки и сборка слайдов
 * Splide из опубликованных записей.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class FS_LMS_Theme_Showcase_Type extends FS_LMS_Theme_Content_Type {

	/** Разметка одного слайда карусели. */
	abstract protected function render_slide( WP_Post $post ): string;

	protected function supports(): array {
		return array( 'title', 'thumbnail', 'page-attributes' );
	}

	/** Слайды всех опубликованных записей подряд. */
	public function slides_markup(): string {
		return implode( "\n\n", array_map( array( $this, 'render_slide' ), $this->items() ) );
	}

	/**
	 * `<img>` изображения записи либо заглушки темы, если изображение не задано
	 * (стартовые записи заводятся без него — на главной остаётся та же
	 * картинка, что была в паттерне).
	 *
	 * @param WP_Post $post         Запись.
	 * @param string  $size         Размер из медиатеки.
	 * @param string  $fallback_uri Путь к заглушке внутри темы.
	 * @param string  $alt          Альтернативный текст.
	 */
	protected function image_html( WP_Post $post, string $size, string $fallback_uri, string $alt ): string {
		$thumbnail_id = (int) get_post_thumbnail_id( $post );

		if ( $thumbnail_id > 0 ) {
			$image = wp_get_attachment_image( $thumbnail_id, $size, false, array( 'alt' => $alt ) );

			if ( '' !== $image ) {
				return $image;
			}
		}

		return sprintf(
			'<img src="%s" alt="%s" loading="lazy" decoding="async"/>',
			esc_url( get_theme_file_uri( $fallback_uri ) ),
			esc_attr( $alt )
		);
	}

	/**
	 * Столбцы списка: миниатюра после флажка, затем общие (без даты, «Порядок»).
	 *
	 * @param array<string, string> $columns Столбцы ядра.
	 *
	 * @return array<string, string>
	 */
	public function admin_columns( array $columns ): array {
		$result = array();

		foreach ( parent::admin_columns( $columns ) as $key => $label ) {
			$result[ $key ] = $label;

			if ( 'cb' === $key ) {
				$result['fs_thumbnail'] = $this->labels()['featured_image'] ?? __( 'Изображение', 'fs-lms-theme' );
			}
		}

		return $result;
	}

	public function render_admin_column( string $column, int $post_id ): void {
		parent::render_admin_column( $column, $post_id );

		if ( 'fs_thumbnail' === $column ) {
			$thumbnail_id = (int) get_post_thumbnail_id( $post_id );

			echo $thumbnail_id > 0
				? wp_get_attachment_image( $thumbnail_id, array( 60, 60 ), false, array( 'style' => 'width:60px;height:60px;object-fit:contain' ) )
				: '—';
		}
	}
}
