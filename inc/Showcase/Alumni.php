<?php
/**
 * «Выпускники» — карточки карусели «Наши выпускники» на главной
 * (`patterns/alumni-carousel.php`).
 *
 * Запись: заголовок — имя, изображение записи — фото, поля «Результат»
 * («98 баллов») и «Отзыв», «Порядок» — позиция в карусели. Разметка
 * слайда та же, что сохранял блок `fs-lms/alumni-card`, — стили блока
 * подключаются как раньше.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Alumni extends FS_LMS_Theme_Showcase_Type {

	private const META_SCORE = '_fs_alumnus_score';
	private const META_QUOTE = '_fs_alumnus_quote';
	private const NONCE      = 'fs_lms_theme_alumnus_meta';

	/** Заглушка фото — та же картинка, что стояла в паттерне. */
	private const FALLBACK_PHOTO = 'img/alumni.png';

	public function post_type(): string {
		return 'fs_alumnus';
	}

	public function register(): void {
		parent::register();

		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'add_meta_boxes_' . $this->post_type(), array( $this, 'add_meta_box' ) );
		add_action( 'save_post_' . $this->post_type(), array( $this, 'save_meta' ), 5, 2 );
		add_filter( 'enter_title_here', array( $this, 'title_placeholder' ), 10, 2 );
	}

	protected function labels(): array {
		return array(
			'name'                  => __( 'Выпускники', 'fs-lms-theme' ),
			'singular_name'         => __( 'Выпускник', 'fs-lms-theme' ),
			'menu_name'             => __( 'Выпускники', 'fs-lms-theme' ),
			'add_new'               => __( 'Добавить выпускника', 'fs-lms-theme' ),
			'add_new_item'          => __( 'Новый выпускник', 'fs-lms-theme' ),
			'edit_item'             => __( 'Редактировать выпускника', 'fs-lms-theme' ),
			'new_item'              => __( 'Новый выпускник', 'fs-lms-theme' ),
			'search_items'          => __( 'Найти выпускника', 'fs-lms-theme' ),
			'not_found'             => __( 'Выпускников нет', 'fs-lms-theme' ),
			'not_found_in_trash'    => __( 'В корзине выпускников нет', 'fs-lms-theme' ),
			'all_items'             => __( 'Выпускники', 'fs-lms-theme' ),
			'featured_image'        => __( 'Фото', 'fs-lms-theme' ),
			'set_featured_image'    => __( 'Выбрать фото', 'fs-lms-theme' ),
			'remove_featured_image' => __( 'Убрать фото', 'fs-lms-theme' ),
			'use_featured_image'    => __( 'Использовать как фото', 'fs-lms-theme' ),
			'attributes'            => __( 'Порядок в карусели', 'fs-lms-theme' ),
		);
	}

	protected function menu_icon(): string {
		return 'dashicons-welcome-learn-more';
	}

	protected function seed_items(): array {
		$items = array(
			array( 'Петрова Мария', '98 баллов', 'Пришла с нуля в 10 классе, поступила в ИТМО на бюджет.' ),
			array( 'Иванов Иван', '100 баллов', 'Обожаю информатику, поступил в вуз мечты, кайф.' ),
			array( 'Соколов Артём', '92 балла', 'Два года робототехники, теперь учусь в МИРЭА.' ),
			array( 'Несоколов Неартём', '102 балла', 'Поступил в мгу после одной недели в шаге в будущем' ),
			array( 'Головач Лена', '22 балла', 'Ходила три года, поступила в МГТУФУ на бюджет' ),
		);

		return array_map(
			static function ( array $item ): array {
				return array(
					'title' => $item[0],
					'meta'  => array(
						self::META_SCORE => $item[1],
						self::META_QUOTE => $item[2],
					),
				);
			},
			$items
		);
	}

	protected function render_slide( WP_Post $post ): string {
		$name  = get_the_title( $post );
		$score = (string) get_post_meta( $post->ID, self::META_SCORE, true );
		$quote = (string) get_post_meta( $post->ID, self::META_QUOTE, true );

		$html = sprintf(
			'<div class="wp-block-fs-lms-alumni-card fs-alumni-card splide__slide"><div class="fs-alumni-card__media">%s</div><div class="fs-alumni-card__body"><div class="fs-alumni-card__score">%s</div><div class="fs-alumni-card__name">%s</div><p class="fs-alumni-card__quote">%s</p></div></div>',
			$this->image_html( $post, 'medium', self::FALLBACK_PHOTO, $name ),
			esc_html( $score ),
			esc_html( $name ),
			nl2br( esc_html( $quote ) )
		);

		// Комментарий блока — чтобы ядро подключило стили `fs-lms/alumni-card`
		// (стили блока грузятся, только когда блок встречается при рендере).
		return get_comment_delimited_block_content( 'fs-lms/alumni-card', array(), $html );
	}

	public function register_meta(): void {
		foreach ( array( self::META_SCORE => 'sanitize_text_field', self::META_QUOTE => 'sanitize_textarea_field' ) as $key => $sanitize ) {
			register_post_meta(
				$this->post_type(),
				$key,
				array(
					'type'              => 'string',
					'single'            => true,
					'default'           => '',
					'sanitize_callback' => $sanitize,
					'auth_callback'     => static function ( bool $allowed, string $meta_key, int $post_id ): bool {
						return current_user_can( 'edit_post', $post_id );
					},
					'show_in_rest'      => false,
				)
			);
		}
	}

	public function add_meta_box(): void {
		add_meta_box(
			'fs-alumnus-card',
			__( 'Карточка выпускника', 'fs-lms-theme' ),
			array( $this, 'render_meta_box' ),
			$this->post_type(),
			'normal',
			'high'
		);
	}

	public function render_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::NONCE, self::NONCE );

		$score = (string) get_post_meta( $post->ID, self::META_SCORE, true );
		$quote = (string) get_post_meta( $post->ID, self::META_QUOTE, true );
		?>
		<p>
			<label for="fs-alumnus-score"><strong><?php esc_html_e( 'Результат', 'fs-lms-theme' ); ?></strong></label><br>
			<input type="text" id="fs-alumnus-score" name="fs_alumnus_score" value="<?php echo esc_attr( $score ); ?>" class="regular-text" placeholder="<?php esc_attr_e( '98 баллов', 'fs-lms-theme' ); ?>">
		</p>
		<p>
			<label for="fs-alumnus-quote"><strong><?php esc_html_e( 'Отзыв', 'fs-lms-theme' ); ?></strong></label><br>
			<textarea id="fs-alumnus-quote" name="fs_alumnus_quote" rows="4" class="large-text" placeholder="<?php esc_attr_e( 'Пришла с нуля в 10 классе, поступила в ИТМО на бюджет.', 'fs-lms-theme' ); ?>"><?php echo esc_textarea( $quote ); ?></textarea>
		</p>
		<p class="description"><?php esc_html_e( 'Имя — в заголовке записи, фото — в блоке «Фото» справа, место в карусели — «Порядок» (меньше — раньше).', 'fs-lms-theme' ); ?></p>
		<?php
	}

	public function save_meta( int $post_id, WP_Post $post ): void {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$nonce = isset( $_POST[ self::NONCE ] ) ? sanitize_text_field( wp_unslash( $_POST[ self::NONCE ] ) ) : '';

		if ( '' === $nonce || ! wp_verify_nonce( $nonce, self::NONCE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$score = isset( $_POST['fs_alumnus_score'] ) ? sanitize_text_field( wp_unslash( $_POST['fs_alumnus_score'] ) ) : '';
		$quote = isset( $_POST['fs_alumnus_quote'] ) ? sanitize_textarea_field( wp_unslash( $_POST['fs_alumnus_quote'] ) ) : '';

		update_post_meta( $post_id, self::META_SCORE, $score );
		update_post_meta( $post_id, self::META_QUOTE, $quote );
	}

	public function title_placeholder( string $placeholder, WP_Post $post ): string {
		return $this->post_type() === $post->post_type ? __( 'Имя и фамилия', 'fs-lms-theme' ) : $placeholder;
	}
}
