<?php
/**
 * «Вопросы» — оба аккордеона сайта (этап 2, 2026-09-13):
 * «Частые вопросы» на главной (`patterns/faq.php`) и сведения об
 * образовательной организации на `/about/` (`patterns/about-accordion.php`).
 *
 * Запись: заголовок — вопрос или название раздела, текст — ответ в обычном
 * визуальном редакторе (абзацы, списки, ссылки на PDF из медиатеки — всё,
 * что нужно разделам «О нас»), «Где показывать», «Открыт по умолчанию» и
 * «Порядок». Разметка аккордеонов прежняя: у главной — блоки
 * `fs-lms/faq-item` в `.fs-faq-list`, у «О нас» — `.fs-legal-accordion`.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Questions extends FS_LMS_Theme_Content_Type {

	public const PLACE_HOME  = 'home';
	public const PLACE_ABOUT = 'about';

	private const META_PLACE = '_fs_question_place';
	private const META_OPEN  = '_fs_question_open';
	private const NONCE      = 'fs_lms_theme_question_meta';

	/** GET-параметр фильтра списка по месту показа. */
	private const FILTER_ARG = 'fs_question_place';

	public function post_type(): string {
		return 'fs_question';
	}

	/**
	 * Места показа: значение поля → подпись в админке.
	 *
	 * @return array<string, string>
	 */
	public function places(): array {
		return array(
			self::PLACE_HOME  => __( 'Главная — «Частые вопросы»', 'fs-lms-theme' ),
			self::PLACE_ABOUT => __( '«О нас» — сведения об организации', 'fs-lms-theme' ),
		);
	}

	public function register(): void {
		parent::register();

		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'add_meta_boxes_' . $this->post_type(), array( $this, 'add_meta_box' ) );
		add_action( 'save_post_' . $this->post_type(), array( $this, 'save_meta' ), 5, 2 );
		add_filter( 'enter_title_here', array( $this, 'title_placeholder' ), 10, 2 );
		add_action( 'restrict_manage_posts', array( $this, 'render_place_filter' ) );
		add_action( 'pre_get_posts', array( $this, 'filter_admin_list' ) );
	}

	protected function labels(): array {
		return array(
			'name'               => __( 'Вопросы', 'fs-lms-theme' ),
			'singular_name'      => __( 'Вопрос', 'fs-lms-theme' ),
			'menu_name'          => __( 'Вопросы', 'fs-lms-theme' ),
			'add_new'            => __( 'Добавить вопрос', 'fs-lms-theme' ),
			'add_new_item'       => __( 'Новый вопрос', 'fs-lms-theme' ),
			'edit_item'          => __( 'Редактировать вопрос', 'fs-lms-theme' ),
			'new_item'           => __( 'Новый вопрос', 'fs-lms-theme' ),
			'search_items'       => __( 'Найти вопрос', 'fs-lms-theme' ),
			'not_found'          => __( 'Вопросов нет', 'fs-lms-theme' ),
			'not_found_in_trash' => __( 'В корзине вопросов нет', 'fs-lms-theme' ),
			'all_items'          => __( 'Вопросы', 'fs-lms-theme' ),
			'attributes'         => __( 'Порядок в аккордеоне', 'fs-lms-theme' ),
		);
	}

	protected function menu_icon(): string {
		return 'dashicons-editor-help';
	}

	protected function supports(): array {
		return array( 'title', 'editor', 'page-attributes' );
	}

	protected function seed_items(): array {
		$items = require __DIR__ . '/seeds/questions.php';

		return array_map(
			static function ( array $item ): array {
				return array(
					'title'   => $item['title'],
					'content' => $item['content'],
					'meta'    => array(
						self::META_PLACE => $item['place'],
						self::META_OPEN  => $item['open'] ? '1' : '',
					),
				);
			},
			$items
		);
	}

	/**
	 * Опубликованные вопросы одного места в порядке показа.
	 *
	 * @return WP_Post[]
	 */
	public function items_for( string $place ): array {
		return $this->items(
			array(
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- десятки записей, выборка по индексу meta_key.
				'meta_query' => array(
					array(
						'key'   => self::META_PLACE,
						'value' => $place,
					),
				),
			)
		);
	}

	/** Пункты «Частых вопросов» главной — блоки `fs-lms/faq-item`. */
	public function faq_markup(): string {
		$blocks = array_map(
			function ( WP_Post $post ): string {
				$html = sprintf(
					'<details class="wp-block-fs-lms-faq-item fs-faq-item"%s><summary class="fs-faq-item__question"><span class="fs-faq-item__question-text">%s</span></summary><div class="fs-faq-item__answer">%s</div></details>',
					$this->is_open( $post ) ? ' open' : '',
					esc_html( get_the_title( $post ) ),
					$this->answer_html( $post )
				);

				// Комментарий блока — чтобы ядро подключило стили `fs-lms/faq-item`.
				return get_comment_delimited_block_content( 'fs-lms/faq-item', array(), $html );
			},
			$this->items_for( self::PLACE_HOME )
		);

		return implode( "\n\n", $blocks );
	}

	/** Пункты аккордеона «О нас» — `.fs-legal-accordion__item`. */
	public function about_markup(): string {
		$items = array_map(
			function ( WP_Post $post ): string {
				return sprintf(
					'<details class="fs-legal-accordion__item"%s><summary class="fs-legal-accordion__question">%s</summary><div class="fs-legal-accordion__answer">%s</div></details>',
					$this->is_open( $post ) ? ' open' : '',
					esc_html( get_the_title( $post ) ),
					$this->answer_html( $post )
				);
			},
			$this->items_for( self::PLACE_ABOUT )
		);

		return implode( "\n", $items );
	}

	private function is_open( WP_Post $post ): bool {
		return '1' === get_post_meta( $post->ID, self::META_OPEN, true );
	}

	/**
	 * Ответ: абзацы из переносов строк (как их сохраняет классический
	 * редактор), только разрешённая в записях разметка.
	 */
	private function answer_html( WP_Post $post ): string {
		return wpautop( wp_kses_post( $post->post_content ) );
	}

	/**
	 * Кэш WP Rocket — главная и страница «О нас».
	 */
	public function purge_page_cache(): void {
		parent::purge_page_cache();

		$about = get_page_by_path( 'about' );

		if ( $about instanceof WP_Post && function_exists( 'rocket_clean_post' ) ) {
			rocket_clean_post( $about->ID );
		}
	}

	public function register_meta(): void {
		$auth = static function ( bool $allowed, string $meta_key, int $post_id ): bool {
			return current_user_can( 'edit_post', $post_id );
		};

		register_post_meta(
			$this->post_type(),
			self::META_PLACE,
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => self::PLACE_HOME,
				'sanitize_callback' => array( $this, 'sanitize_place' ),
				'auth_callback'     => $auth,
				'show_in_rest'      => false,
			)
		);

		register_post_meta(
			$this->post_type(),
			self::META_OPEN,
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => '',
				'sanitize_callback' => static function ( $value ): string {
					return '1' === (string) $value ? '1' : '';
				},
				'auth_callback'     => $auth,
				'show_in_rest'      => false,
			)
		);
	}

	/** Место показа — только из списка, иначе главная. */
	public function sanitize_place( $value ): string {
		$value = sanitize_key( (string) $value );

		return array_key_exists( $value, $this->places() ) ? $value : self::PLACE_HOME;
	}

	public function add_meta_box(): void {
		add_meta_box(
			'fs-question-settings',
			__( 'Где показывать', 'fs-lms-theme' ),
			array( $this, 'render_meta_box' ),
			$this->post_type(),
			'side',
			'high'
		);
	}

	public function render_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::NONCE, self::NONCE );

		$place = $this->sanitize_place( get_post_meta( $post->ID, self::META_PLACE, true ) );
		$open  = $this->is_open( $post );

		foreach ( $this->places() as $value => $label ) {
			printf(
				'<p><label><input type="radio" name="fs_question_place" value="%s"%s> %s</label></p>',
				esc_attr( $value ),
				checked( $place, $value, false ),
				esc_html( $label )
			);
		}
		?>
		<hr>
		<p>
			<label><input type="checkbox" name="fs_question_open" value="1"<?php checked( $open ); ?>> <?php esc_html_e( 'Открыт по умолчанию', 'fs-lms-theme' ); ?></label>
		</p>
		<p class="description"><?php esc_html_e( 'Вопрос — в заголовке записи, ответ — в тексте ниже. Место в аккордеоне — «Порядок» (меньше — выше).', 'fs-lms-theme' ); ?></p>
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

		$place = isset( $_POST['fs_question_place'] ) ? sanitize_key( wp_unslash( $_POST['fs_question_place'] ) ) : self::PLACE_HOME;

		update_post_meta( $post_id, self::META_PLACE, $this->sanitize_place( $place ) );
		update_post_meta( $post_id, self::META_OPEN, isset( $_POST['fs_question_open'] ) ? '1' : '' );
	}

	public function title_placeholder( string $placeholder, WP_Post $post ): string {
		return $this->post_type() === $post->post_type ? __( 'Вопрос или название раздела', 'fs-lms-theme' ) : $placeholder;
	}

	/**
	 * Столбцы списка: «Где показывать» перед «Порядком».
	 *
	 * @param array<string, string> $columns Столбцы ядра.
	 *
	 * @return array<string, string>
	 */
	public function admin_columns( array $columns ): array {
		$columns = parent::admin_columns( $columns );
		$order   = $columns['fs_menu_order'];

		unset( $columns['fs_menu_order'] );
		$columns['fs_place']      = __( 'Где показывать', 'fs-lms-theme' );
		$columns['fs_menu_order'] = $order;

		return $columns;
	}

	public function render_admin_column( string $column, int $post_id ): void {
		parent::render_admin_column( $column, $post_id );

		if ( 'fs_place' === $column ) {
			$place = $this->sanitize_place( get_post_meta( $post_id, self::META_PLACE, true ) );

			echo esc_html( $this->places()[ $place ] );
		}
	}

	/** Выпадающий фильтр «Где показывать» над списком. */
	public function render_place_filter( string $post_type ): void {
		if ( $this->post_type() !== $post_type ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- фильтр списка, только чтение.
		$current = isset( $_GET[ self::FILTER_ARG ] ) ? sanitize_key( wp_unslash( $_GET[ self::FILTER_ARG ] ) ) : '';

		printf( '<select name="%s"><option value="">%s</option>', esc_attr( self::FILTER_ARG ), esc_html__( 'Все места', 'fs-lms-theme' ) );

		foreach ( $this->places() as $value => $label ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $value ), selected( $current, $value, false ), esc_html( $label ) );
		}

		echo '</select>';
	}

	public function filter_admin_list( WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() || $this->post_type() !== $query->get( 'post_type' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- фильтр списка, только чтение.
		$place = isset( $_GET[ self::FILTER_ARG ] ) ? sanitize_key( wp_unslash( $_GET[ self::FILTER_ARG ] ) ) : '';

		if ( array_key_exists( $place, $this->places() ) ) {
			$query->set(
				'meta_query',
				array(
					array(
						'key'   => self::META_PLACE,
						'value' => $place,
					),
				)
			);
		}
	}
}
