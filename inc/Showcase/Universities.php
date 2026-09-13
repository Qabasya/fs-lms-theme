<?php
/**
 * «Вузы» — логотипы ленты «Наши выпускники поступают» на главной
 * (`patterns/alumni-strip.php`).
 *
 * Запись: заголовок — название вуза (он же `alt` логотипа), изображение
 * записи — логотип, «Порядок» — позиция в ленте. Слайд — тот же `wp:image`
 * с классами `fs-alumni-logo splide__slide`, что стоял в паттерне.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Universities extends FS_LMS_Theme_Showcase_Type {

	/** Заглушка логотипа — та же картинка, что стояла в паттерне. */
	private const FALLBACK_LOGO = 'images/main_univer_5.png';

	public function post_type(): string {
		return 'fs_university';
	}

	public function register(): void {
		parent::register();

		add_filter( 'enter_title_here', array( $this, 'title_placeholder' ), 10, 2 );
		add_action( 'edit_form_after_title', array( $this, 'render_hint' ) );
	}

	protected function labels(): array {
		return array(
			'name'                  => __( 'Вузы', 'fs-lms-theme' ),
			'singular_name'         => __( 'Вуз', 'fs-lms-theme' ),
			'menu_name'             => __( 'Вузы', 'fs-lms-theme' ),
			'add_new'               => __( 'Добавить вуз', 'fs-lms-theme' ),
			'add_new_item'          => __( 'Новый вуз', 'fs-lms-theme' ),
			'edit_item'             => __( 'Редактировать вуз', 'fs-lms-theme' ),
			'new_item'              => __( 'Новый вуз', 'fs-lms-theme' ),
			'search_items'          => __( 'Найти вуз', 'fs-lms-theme' ),
			'not_found'             => __( 'Вузов нет', 'fs-lms-theme' ),
			'not_found_in_trash'    => __( 'В корзине вузов нет', 'fs-lms-theme' ),
			'all_items'             => __( 'Вузы', 'fs-lms-theme' ),
			'featured_image'        => __( 'Логотип', 'fs-lms-theme' ),
			'set_featured_image'    => __( 'Выбрать логотип', 'fs-lms-theme' ),
			'remove_featured_image' => __( 'Убрать логотип', 'fs-lms-theme' ),
			'use_featured_image'    => __( 'Использовать как логотип', 'fs-lms-theme' ),
			'attributes'            => __( 'Порядок в ленте', 'fs-lms-theme' ),
		);
	}

	protected function menu_icon(): string {
		return 'dashicons-building';
	}

	protected function seed_items(): array {
		return array_map(
			static function ( string $title ): array {
				return array( 'title' => $title );
			},
			array( 'БФУ им. Канта', 'МИРЭА', 'ИТМО', 'МГТУ', 'РУДН' )
		);
	}

	protected function render_slide( WP_Post $post ): string {
		$html = sprintf(
			'<figure class="wp-block-image fs-alumni-logo splide__slide">%s</figure>',
			$this->image_html( $post, 'medium_large', self::FALLBACK_LOGO, get_the_title( $post ) )
		);

		return get_comment_delimited_block_content( 'core/image', array( 'className' => 'fs-alumni-logo splide__slide' ), $html );
	}

	public function title_placeholder( string $placeholder, WP_Post $post ): string {
		return $this->post_type() === $post->post_type ? __( 'Название вуза', 'fs-lms-theme' ) : $placeholder;
	}

	public function render_hint( WP_Post $post ): void {
		if ( $this->post_type() !== $post->post_type ) {
			return;
		}

		printf(
			'<p class="description" style="margin:12px 0 0">%s</p>',
			esc_html__( 'Название показывается скринридерам и поисковикам вместо логотипа. Логотип — в блоке «Логотип» справа, место в ленте — «Порядок» (меньше — раньше). Лучше всего подходит PNG или SVG с прозрачным фоном.', 'fs-lms-theme' )
		);
	}
}
