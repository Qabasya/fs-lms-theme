<?php
/**
 * Изображение записи для типов из `inc/Showcase/*`: `<img>` из медиатеки или
 * заглушки темы и столбец-миниатюра в списке админки.
 *
 * Трейт, а не ещё один уровень наследования: изображение нужно и каруселям
 * (`FS_LMS_Theme_Showcase_Type`), и «Направлениям», которые каруселью не
 * являются. Подключается к наследнику `FS_LMS_Theme_Content_Type` —
 * `parent::` в методах ниже указывает на него.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait FS_LMS_Theme_Featured_Image {

	/**
	 * `<img>` изображения записи, иначе заглушки темы, иначе пустая строка.
	 *
	 * @param WP_Post     $post         Запись.
	 * @param string      $size         Размер из медиатеки.
	 * @param string|null $fallback_uri Путь к заглушке внутри темы.
	 * @param string      $alt          Альтернативный текст.
	 */
	protected function image_html( WP_Post $post, string $size, ?string $fallback_uri, string $alt ): string {
		return $this->attachment_or_fallback_html( (int) get_post_thumbnail_id( $post ), $size, $fallback_uri, $alt );
	}

	/**
	 * `<img>` вложения, иначе заглушки темы, иначе пустая строка.
	 *
	 * @param int         $attachment_id ID вложения (0 — не выбрано).
	 * @param string      $size          Размер из медиатеки.
	 * @param string|null $fallback_uri  Путь к заглушке внутри темы.
	 * @param string      $alt           Альтернативный текст.
	 */
	protected function attachment_or_fallback_html( int $attachment_id, string $size, ?string $fallback_uri, string $alt ): string {
		if ( $attachment_id > 0 ) {
			$image = wp_get_attachment_image( $attachment_id, $size, false, array( 'alt' => $alt ) );

			if ( '' !== $image ) {
				return $image;
			}
		}

		if ( null === $fallback_uri || '' === $fallback_uri || ! file_exists( get_theme_file_path( $fallback_uri ) ) ) {
			return '';
		}

		return sprintf(
			'<img src="%s" alt="%s" loading="lazy" decoding="async"/>',
			esc_url( get_theme_file_uri( $fallback_uri ) ),
			esc_attr( $alt )
		);
	}

	/**
	 * Столбцы списка: миниатюра после флажка, затем общие.
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
