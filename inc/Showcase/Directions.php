<?php
/**
 * «Направления» — одна запись на направление питает все места, где оно
 * выводится (этап 3, 2026-09-13):
 * - список «Направления подготовки» в первом экране главной (`patterns/hero.php`);
 * - карточки курсов главной (`patterns/courses-grid.php`);
 * - карточки каталога `/courses/` (`patterns/courses-catalog.php`);
 * - первый экран страницы направления (`patterns/subject-hero*.php`).
 *
 * До этого одно и то же название/класс/описание/цена жили в трёх-четырёх
 * файлах. Разметка всех мест прежняя: карточки — те же `fs-lms/course-card` и
 * `fs-lms/catalog-card`, первый экран — те же блоки, что стояли в паттернах.
 *
 * Запись связана с предметом плагина по ключу (`inf_ege`, `inf_oge`,
 * `python`, `robo`): из него строятся ссылки (`fs_lms_theme_subject_url()`),
 * по нему страница направления находит свой первый экран.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Directions extends FS_LMS_Theme_Content_Type {

	// Свои `admin_columns()`/`render_admin_column()` ниже перекрывают методы
	// трейта — его версии доступны под этими именами.
	use FS_LMS_Theme_Featured_Image {
		admin_columns as featured_image_columns;
		render_admin_column as featured_image_column;
	}

	private const NONCE = 'fs_lms_theme_direction_meta';

	/** Текстовые поля записи: ключ формы → meta_key. */
	private const TEXT_FIELDS = array(
		'key'          => '_fs_direction_key',
		'grade_label'  => '_fs_direction_grade_label',
		'grade_filter' => '_fs_direction_grade_filter',
		'color'        => '_fs_direction_color',
		'caption'      => '_fs_direction_caption',
		'price'        => '_fs_direction_price',
		'price_note'   => '_fs_direction_price_note',
		'format'       => '_fs_direction_format',
		'hero_text'    => '_fs_direction_hero_text',
	);

	/** Многострочные поля — `sanitize_textarea_field`, остальные — `sanitize_text_field`. */
	private const TEXTAREA_FIELDS = array( 'caption', 'hero_text' );

	private const META_CATALOG_IMAGE = '_fs_direction_catalog_image_id';

	/** Картинки темы из стартовых записей — пока в админке не выбраны свои. */
	private const META_THEME_CARD_IMAGE    = '_fs_direction_theme_card_image';
	private const META_THEME_CATALOG_IMAGE = '_fs_direction_theme_catalog_image';

	/** Число фактов в плашке первого экрана. */
	private const STATS_COUNT = 3;

	/**
	 * Цвет направления → классы бейджа (текст + подложка из палитры
	 * theme.json, те же пары, что `src/blocks/shared/colors.js`) и
	 * модификатор плашки первого экрана (`.fs-subject-hero-box--*`).
	 */
	private const COLORS = array(
		'blue'   => array( 'text' => 'info', 'background' => 'info-soft', 'hero' => 'ege' ),
		'violet' => array( 'text' => 'violet', 'background' => 'violet-soft', 'hero' => 'oge' ),
		'green'  => array( 'text' => 'ok', 'background' => 'ok-soft', 'hero' => 'python' ),
		'yellow' => array( 'text' => 'subject-yellow-text', 'background' => 'subject-yellow', 'hero' => 'robo' ),
	);

	/** Значения фильтра по классу на `/courses/` — те же, что у чипсов в паттерне. */
	private const GRADE_FILTERS = array( '5-8', '9', '10', '11' );

	public function post_type(): string {
		return 'fs_direction';
	}

	/** Опция разового обновления порядка стартовых записей. */
	private const ORDER_OPTION = 'fs_lms_theme_directions_order';

	/**
	 * Порядок стартовых записей: ключ предмета → «Порядок» до (1.4.0) и после
	 * (по указанию пользователя, 2026-09-13: ЕГЭ → Python → ОГЭ → Робототехника).
	 */
	private const ORDER_CHANGE = array(
		'inf_ege' => array( 10, 10 ),
		'inf_oge' => array( 20, 30 ),
		'python'  => array( 30, 20 ),
		'robo'    => array( 40, 40 ),
	);

	public function register(): void {
		parent::register();

		add_action( 'init', array( $this, 'upgrade_order' ), 30 );
		add_action( 'add_meta_boxes_' . $this->post_type(), array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_' . $this->post_type(), array( $this, 'save_meta' ), 5, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_filter( 'enter_title_here', array( $this, 'title_placeholder' ), 10, 2 );
	}

	protected function labels(): array {
		return array(
			'name'                  => __( 'Направления', 'fs-lms-theme' ),
			'singular_name'         => __( 'Направление', 'fs-lms-theme' ),
			'menu_name'             => __( 'Направления', 'fs-lms-theme' ),
			'add_new'               => __( 'Добавить направление', 'fs-lms-theme' ),
			'add_new_item'          => __( 'Новое направление', 'fs-lms-theme' ),
			'edit_item'             => __( 'Редактировать направление', 'fs-lms-theme' ),
			'new_item'              => __( 'Новое направление', 'fs-lms-theme' ),
			'search_items'          => __( 'Найти направление', 'fs-lms-theme' ),
			'not_found'             => __( 'Направлений нет', 'fs-lms-theme' ),
			'not_found_in_trash'    => __( 'В корзине направлений нет', 'fs-lms-theme' ),
			'all_items'             => __( 'Направления', 'fs-lms-theme' ),
			'featured_image'        => __( 'Картинка на главной', 'fs-lms-theme' ),
			'set_featured_image'    => __( 'Выбрать картинку на главной', 'fs-lms-theme' ),
			'remove_featured_image' => __( 'Убрать картинку на главной', 'fs-lms-theme' ),
			'use_featured_image'    => __( 'Использовать на главной', 'fs-lms-theme' ),
			'attributes'            => __( 'Порядок', 'fs-lms-theme' ),
		);
	}

	protected function menu_icon(): string {
		return 'dashicons-book-alt';
	}

	protected function supports(): array {
		return array( 'title', 'thumbnail', 'page-attributes' );
	}

	protected function seed_items(): array {
		$items = require __DIR__ . '/seeds/directions.php';

		return array_map(
			static function ( array $item ): array {
				$meta = array(
					self::META_THEME_CARD_IMAGE    => $item['card_image'],
					self::META_THEME_CATALOG_IMAGE => $item['catalog_image'],
				);

				foreach ( self::TEXT_FIELDS as $field => $meta_key ) {
					$meta[ $meta_key ] = $item[ $field ];
				}

				foreach ( $item['stats'] as $index => $stat ) {
					$meta[ self::stat_meta_key( $index, 'value' ) ] = $stat[0];
					$meta[ self::stat_meta_key( $index, 'label' ) ] = $stat[1];
				}

				return array(
					'title' => $item['title'],
					'meta'  => $meta,
				);
			},
			$items
		);
	}

	/**
	 * Разово переставляет стартовые записи в порядок ЕГЭ → Python → ОГЭ →
	 * Робототехника. Трогает только запись, у которой «Порядок» остался
	 * стартовым из 1.4.0: если его уже поменяли руками, запись не меняется.
	 * На свежей установке записи сразу заводятся в новом порядке
	 * (`seeds/directions.php`), и обновлять нечего.
	 */
	public function upgrade_order(): void {
		if ( ! add_option( self::ORDER_OPTION, '1', '', true ) ) {
			return;
		}

		foreach ( self::ORDER_CHANGE as $subject_key => $orders ) {
			$posts = get_posts(
				array(
					'post_type'        => $this->post_type(),
					'post_status'      => 'any',
					'numberposts'      => -1,
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- единицы записей.
					'meta_query'       => array(
						array(
							'key'   => self::TEXT_FIELDS['key'],
							'value' => $subject_key,
						),
					),
					'suppress_filters' => false,
				)
			);

			foreach ( $posts as $post ) {
				if ( (int) $post->menu_order === $orders[0] && $orders[0] !== $orders[1] ) {
					wp_update_post(
						array(
							'ID'         => $post->ID,
							'menu_order' => $orders[1],
						)
					);
				}
			}
		}
	}

	private static function stat_meta_key( int $index, string $part ): string {
		return sprintf( '_fs_direction_stat_%d_%s', $index + 1, $part );
	}

	/* --------------------------------------------------------------------
	 * Данные направления
	 * ------------------------------------------------------------------ */

	/**
	 * Направление в виде плоского массива — одинаково из записи и из
	 * стартовых данных (запасной вариант первого экрана, если записи нет).
	 *
	 * @return array<string, mixed>
	 */
	private function data( WP_Post $post ): array {
		$data = array(
			'title'            => get_the_title( $post ),
			'card_image_id'    => (int) get_post_thumbnail_id( $post ),
			'catalog_image_id' => (int) get_post_meta( $post->ID, self::META_CATALOG_IMAGE, true ),
			'card_image'       => (string) get_post_meta( $post->ID, self::META_THEME_CARD_IMAGE, true ),
			'catalog_image'    => (string) get_post_meta( $post->ID, self::META_THEME_CATALOG_IMAGE, true ),
			'stats'            => array(),
		);

		foreach ( self::TEXT_FIELDS as $field => $meta_key ) {
			$data[ $field ] = (string) get_post_meta( $post->ID, $meta_key, true );
		}

		for ( $i = 0; $i < self::STATS_COUNT; $i++ ) {
			$data['stats'][] = array(
				(string) get_post_meta( $post->ID, self::stat_meta_key( $i, 'value' ), true ),
				(string) get_post_meta( $post->ID, self::stat_meta_key( $i, 'label' ), true ),
			);
		}

		return $data;
	}

	/**
	 * Направление по ключу предмета: запись (любой статус, кроме корзины —
	 * черновик прячет направление из списков, но не ломает первый экран его
	 * страницы), иначе стартовые данные темы, иначе `null`.
	 *
	 * @return array<string, mixed>|null
	 */
	public function find( string $subject_key ): ?array {
		$posts = get_posts(
			array(
				'post_type'        => $this->post_type(),
				'post_status'      => array( 'publish', 'draft', 'pending', 'private', 'future' ),
				'numberposts'      => 1,
				'orderby'          => array(
					'menu_order' => 'ASC',
					'ID'         => 'ASC',
				),
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- единицы записей.
				'meta_query'       => array(
					array(
						'key'   => self::TEXT_FIELDS['key'],
						'value' => $subject_key,
					),
				),
				'no_found_rows'    => true,
				'suppress_filters' => false,
			)
		);

		if ( array() !== $posts ) {
			return $this->data( $posts[0] );
		}

		foreach ( require __DIR__ . '/seeds/directions.php' as $item ) {
			if ( $item['key'] === $subject_key ) {
				return array_merge(
					$item,
					array(
						'card_image_id'    => 0,
						'catalog_image_id' => 0,
					)
				);
			}
		}

		return null;
	}

	/**
	 * Опубликованные направления в порядке показа.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private function published(): array {
		return array_map( array( $this, 'data' ), $this->items() );
	}

	/** Адрес страницы направления; без ключа — каталог `/courses/`. */
	private function url( array $direction ): string {
		return '' === $direction['key']
			? esc_url( home_url( '/courses/' ) )
			: fs_lms_theme_subject_url( $direction['key'], 'overview' );
	}

	/**
	 * Классы бейджа из цвета направления.
	 */
	private function badge_classes( array $direction ): string {
		$color = self::COLORS[ $direction['color'] ] ?? self::COLORS['blue'];

		return sprintf( 'has-%s-color has-%s-background-color has-text-color has-background', $color['text'], $color['background'] );
	}

	/* --------------------------------------------------------------------
	 * Разметка
	 * ------------------------------------------------------------------ */

	/**
	 * Пункты списка «Направления подготовки» первого экрана главной.
	 *
	 * Название и класс — в общей группе `fs-hero-dirs__text` (2026-09-14):
	 * стрелка пункта (`::before`) стоит слева от неё и всегда в одной строке
	 * с названием, текст переносится внутри своей колонки (`theme.scss`).
	 */
	public function hero_list_markup(): string {
		$directions = $this->published();
		$last       = count( $directions ) - 1;
		$items      = array();

		foreach ( $directions as $index => $direction ) {
			$class_name = 'fs-hero-dirs__item' . ( $index === $last ? ' fs-hero-dirs__item--last' : '' );

			$name = get_comment_delimited_block_content(
				'core/paragraph',
				array( 'className' => 'fs-hero-dirs__name' ),
				sprintf( '<p class="fs-hero-dirs__name"><a href="%s">%s</a></p>', esc_url( $this->url( $direction ) ), esc_html( $direction['title'] ) )
			);

			$grade = get_comment_delimited_block_content(
				'core/paragraph',
				array( 'className' => 'fs-hero-dirs__grade' ),
				sprintf( '<p class="fs-hero-dirs__grade">%s</p>', esc_html( $direction['grade_label'] ) )
			);

			$text = get_comment_delimited_block_content(
				'core/group',
				array( 'className' => 'fs-hero-dirs__text' ),
				sprintf( '<div class="wp-block-group fs-hero-dirs__text">%s%s</div>', $name, $grade )
			);

			$items[] = get_comment_delimited_block_content(
				'core/group',
				array( 'className' => $class_name ),
				sprintf( '<div class="wp-block-group %s">%s</div>', esc_attr( $class_name ), $text )
			);
		}

		return implode( "\n\n", $items );
	}

	/** Карточки курсов главной — `fs-lms/course-card`. */
	public function course_cards_markup(): string {
		$cards = array_map(
			function ( array $direction ): string {
				$image = $this->attachment_or_fallback_html( $direction['card_image_id'], 'medium_large', $direction['card_image'], $direction['title'] );
				$price = '' === $direction['price'] ? '' : sprintf(
					'<div class="fs-course-card__price"><span class="fs-course-card__price-amount">%s</span><span class="fs-course-card__price-unit">/ мес</span></div>',
					esc_html( $direction['price'] )
				);

				$html = sprintf(
					'<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media%s">%s</div><div class="fs-course-card__body"><span class="fs-course-card__badge %s">%s</span><h3 class="fs-course-card__title">%s</h3><p class="fs-course-card__caption">%s</p><div class="fs-course-card__footer">%s<a class="fs-course-card__button wp-element-button" href="%s">%s</a></div></div></div>',
					'' === $image ? ' fs-placeholder-tile' : '',
					$image,
					esc_attr( $this->badge_classes( $direction ) ),
					esc_html( $direction['grade_label'] ),
					esc_html( $direction['title'] ),
					esc_html( $direction['caption'] ),
					$price,
					esc_url( $this->url( $direction ) ),
					esc_html__( 'Подробнее', 'fs-lms-theme' )
				);

				return get_comment_delimited_block_content( 'fs-lms/course-card', array(), $html );
			},
			$this->published()
		);

		return implode( "\n\n", $cards );
	}

	/** Карточки каталога `/courses/` — `fs-lms/catalog-card`. */
	public function catalog_cards_markup(): string {
		$cards = array_map(
			function ( array $direction ): string {
				$image = $this->attachment_or_fallback_html( $direction['catalog_image_id'], 'large', $direction['catalog_image'], $direction['title'] );

				$html = sprintf(
					'<div class="wp-block-fs-lms-catalog-card fs-course-catalog-card"%s><div class="fs-course-catalog-card__media%s">%s</div><div class="fs-course-catalog-card__body"><div class="fs-course-catalog-card__meta"><span class="fs-course-catalog-card__badge %s">%s</span><span class="fs-course-catalog-card__format">%s</span></div><h3 class="fs-course-catalog-card__title">%s</h3><p class="fs-course-catalog-card__text">%s</p><div class="fs-course-catalog-card__footer"><div class="fs-course-catalog-card__price"><span class="fs-course-catalog-card__price-amount">%s</span><span class="fs-course-catalog-card__price-note">%s</span></div><div class="fs-course-catalog-card__actions"><a class="fs-course-catalog-card__button fs-course-catalog-card__button--solid" href="%s">%s</a></div></div></div></div>',
					'' === $direction['grade_filter'] ? '' : sprintf( ' data-grade="%s"', esc_attr( $direction['grade_filter'] ) ),
					'' === $image ? ' fs-placeholder-tile' : '',
					$image,
					esc_attr( $this->badge_classes( $direction ) ),
					esc_html( $direction['grade_label'] ),
					esc_html( $direction['format'] ),
					esc_html( $direction['title'] ),
					esc_html( $direction['caption'] ),
					esc_html( $direction['price'] ),
					esc_html( $direction['price_note'] ),
					esc_url( $this->url( $direction ) ),
					esc_html__( 'Подробнее', 'fs-lms-theme' )
				);

				return get_comment_delimited_block_content( 'fs-lms/catalog-card', array(), $html );
			},
			$this->published()
		);

		return implode( "\n\n", $cards );
	}

	/**
	 * Первый экран страницы направления: плашка с текстом, форма записи и
	 * факты. Разметка — `inc/Showcase/views/subject-hero.php`.
	 */
	public function subject_hero_markup( string $subject_key ): string {
		$direction = $this->find( $subject_key );

		if ( null === $direction ) {
			return '';
		}

		$hero_modifier = ( self::COLORS[ $direction['color'] ] ?? self::COLORS['blue'] )['hero'];
		$paragraphs    = array_values( array_filter( array_map( 'trim', preg_split( "/\R\s*\R/u", $direction['hero_text'] ) ) ) );
		$stats         = array_values(
			array_filter(
				$direction['stats'],
				static function ( array $stat ): bool {
					return '' !== $stat[0] || '' !== $stat[1];
				}
			)
		);

		ob_start();
		include __DIR__ . '/views/subject-hero.php';

		return (string) ob_get_clean();
	}

	/* --------------------------------------------------------------------
	 * Админка
	 * ------------------------------------------------------------------ */

	/**
	 * Кэш WP Rocket — всего сайта: направление выводится на главной, в
	 * каталоге и на своей странице.
	 */
	public function purge_page_cache(): void {
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
	}

	public function add_meta_boxes(): void {
		add_meta_box( 'fs-direction-subject', __( 'Предмет', 'fs-lms-theme' ), array( $this, 'render_subject_box' ), $this->post_type(), 'side', 'high' );
		add_meta_box( 'fs-direction-cards', __( 'Карточки на главной и в каталоге «Курсы»', 'fs-lms-theme' ), array( $this, 'render_cards_box' ), $this->post_type(), 'normal', 'high' );
		add_meta_box( 'fs-direction-hero', __( 'Первый экран страницы направления', 'fs-lms-theme' ), array( $this, 'render_hero_box' ), $this->post_type(), 'normal', 'default' );
	}

	public function render_subject_box( WP_Post $post ): void {
		wp_nonce_field( self::NONCE, self::NONCE );

		$key = (string) get_post_meta( $post->ID, self::TEXT_FIELDS['key'], true );
		?>
		<p>
			<label for="fs-direction-key"><strong><?php esc_html_e( 'Ключ предмета', 'fs-lms-theme' ); ?></strong></label><br>
			<input type="text" id="fs-direction-key" name="fs_direction[key]" value="<?php echo esc_attr( $key ); ?>" list="fs-direction-keys" class="widefat" placeholder="inf_ege">
			<datalist id="fs-direction-keys">
				<?php foreach ( array_keys( fs_lms_theme_subject_hero_patterns() ) as $known_key ) : ?>
					<option value="<?php echo esc_attr( $known_key ); ?>"></option>
				<?php endforeach; ?>
			</datalist>
		</p>
		<p class="description"><?php esc_html_e( 'Слаг страницы предмета в плагине: inf_ege, inf_oge, python, robo. Из него строится ссылка «Подробнее», по нему страница направления берёт свой первый экран.', 'fs-lms-theme' ); ?></p>
		<?php
	}

	public function render_cards_box( WP_Post $post ): void {
		$value = function ( string $field ) use ( $post ): string {
			return (string) get_post_meta( $post->ID, self::TEXT_FIELDS[ $field ], true );
		};

		$color_labels = array(
			'blue'   => __( 'Синий (как у ЕГЭ)', 'fs-lms-theme' ),
			'violet' => __( 'Фиолетовый (как у ОГЭ)', 'fs-lms-theme' ),
			'green'  => __( 'Зелёный (как у Python)', 'fs-lms-theme' ),
			'yellow' => __( 'Жёлтый (как у робототехники)', 'fs-lms-theme' ),
		);

		$catalog_image_id = (int) get_post_meta( $post->ID, self::META_CATALOG_IMAGE, true );
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="fs-direction-grade-label"><?php esc_html_e( 'Класс', 'fs-lms-theme' ); ?></label></th>
				<td><input type="text" id="fs-direction-grade-label" name="fs_direction[grade_label]" value="<?php echo esc_attr( $value( 'grade_label' ) ); ?>" class="regular-text" placeholder="<?php esc_attr_e( '11 класс', 'fs-lms-theme' ); ?>">
				<p class="description"><?php esc_html_e( 'Бейдж на карточках, в списке первого экрана главной и на странице направления.', 'fs-lms-theme' ); ?></p></td>
			</tr>
			<tr>
				<th scope="row"><label for="fs-direction-grade-filter"><?php esc_html_e( 'Фильтр в каталоге', 'fs-lms-theme' ); ?></label></th>
				<td><select id="fs-direction-grade-filter" name="fs_direction[grade_filter]">
					<option value=""><?php esc_html_e( '— показывать только при «Все классы»', 'fs-lms-theme' ); ?></option>
					<?php foreach ( self::GRADE_FILTERS as $grade ) : ?>
						<option value="<?php echo esc_attr( $grade ); ?>"<?php selected( $value( 'grade_filter' ), $grade ); ?>><?php echo esc_html( str_replace( '-', '–', $grade ) ); ?></option>
					<?php endforeach; ?>
				</select></td>
			</tr>
			<tr>
				<th scope="row"><label for="fs-direction-color"><?php esc_html_e( 'Цвет', 'fs-lms-theme' ); ?></label></th>
				<td><select id="fs-direction-color" name="fs_direction[color]">
					<?php foreach ( $color_labels as $color => $label ) : ?>
						<option value="<?php echo esc_attr( $color ); ?>"<?php selected( $value( 'color' ), $color ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php esc_html_e( 'Бейдж класса и фон первого экрана страницы направления.', 'fs-lms-theme' ); ?></p></td>
			</tr>
			<tr>
				<th scope="row"><label for="fs-direction-caption"><?php esc_html_e( 'Описание', 'fs-lms-theme' ); ?></label></th>
				<td><textarea id="fs-direction-caption" name="fs_direction[caption]" rows="2" class="large-text"><?php echo esc_textarea( $value( 'caption' ) ); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="fs-direction-price"><?php esc_html_e( 'Цена в месяц', 'fs-lms-theme' ); ?></label></th>
				<td><input type="text" id="fs-direction-price" name="fs_direction[price]" value="<?php echo esc_attr( $value( 'price' ) ); ?>" class="regular-text" placeholder="<?php esc_attr_e( '14 000 ₽', 'fs-lms-theme' ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="fs-direction-price-note"><?php esc_html_e( 'Пояснение к цене', 'fs-lms-theme' ); ?></label></th>
				<td><input type="text" id="fs-direction-price-note" name="fs_direction[price_note]" value="<?php echo esc_attr( $value( 'price_note' ) ); ?>" class="regular-text" placeholder="<?php esc_attr_e( '875 ₽ за час, занятие 2 часа', 'fs-lms-theme' ); ?>">
				<p class="description"><?php esc_html_e( 'Только в каталоге «Курсы».', 'fs-lms-theme' ); ?></p></td>
			</tr>
			<tr>
				<th scope="row"><label for="fs-direction-format"><?php esc_html_e( 'Частота занятий', 'fs-lms-theme' ); ?></label></th>
				<td><input type="text" id="fs-direction-format" name="fs_direction[format]" value="<?php echo esc_attr( $value( 'format' ) ); ?>" class="regular-text" placeholder="<?php esc_attr_e( '2 раза в неделю', 'fs-lms-theme' ); ?>">
				<p class="description"><?php esc_html_e( 'Только в каталоге «Курсы».', 'fs-lms-theme' ); ?></p></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Картинка в каталоге', 'fs-lms-theme' ); ?></th>
				<td>
					<div class="fs-media-field" data-title="<?php esc_attr_e( 'Картинка в каталоге «Курсы»', 'fs-lms-theme' ); ?>" data-button="<?php esc_attr_e( 'Выбрать', 'fs-lms-theme' ); ?>">
						<input type="hidden" name="fs_direction[catalog_image_id]" value="<?php echo esc_attr( (string) $catalog_image_id ); ?>" class="fs-media-field__input">
						<div class="fs-media-field__preview"><?php echo $catalog_image_id > 0 ? wp_get_attachment_image( $catalog_image_id, 'medium' ) : ''; ?></div>
						<p>
							<button type="button" class="button fs-media-field__select"><?php esc_html_e( 'Выбрать картинку', 'fs-lms-theme' ); ?></button>
							<button type="button" class="button-link button-link-delete fs-media-field__remove"<?php echo $catalog_image_id > 0 ? '' : ' hidden'; ?>><?php esc_html_e( 'Убрать', 'fs-lms-theme' ); ?></button>
						</p>
					</div>
					<p class="description"><?php esc_html_e( 'Широкая, примерно 2,4 : 1 (например, 1920×800), показывается целиком. Картинка карточки на главной — справа, «Картинка на главной», примерно 4 : 3, обрезается по краям. Пока картинки не выбраны, показываются прежние.', 'fs-lms-theme' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	public function render_hero_box( WP_Post $post ): void {
		$hero_text = (string) get_post_meta( $post->ID, self::TEXT_FIELDS['hero_text'], true );
		?>
		<p><label for="fs-direction-hero-text"><strong><?php esc_html_e( 'Текст слева от формы', 'fs-lms-theme' ); ?></strong></label></p>
		<textarea id="fs-direction-hero-text" name="fs_direction[hero_text]" rows="6" class="large-text"><?php echo esc_textarea( $hero_text ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Над текстом — класс и заголовок (название направления). Абзацы разделяйте пустой строкой.', 'fs-lms-theme' ); ?></p>

		<p><strong><?php esc_html_e( 'Факты под формой', 'fs-lms-theme' ); ?></strong></p>
		<table class="widefat striped" style="max-width:40rem">
			<thead><tr><th><?php esc_html_e( 'Значение', 'fs-lms-theme' ); ?></th><th><?php esc_html_e( 'Подпись', 'fs-lms-theme' ); ?></th></tr></thead>
			<tbody>
				<?php for ( $i = 0; $i < self::STATS_COUNT; $i++ ) : ?>
					<tr>
						<td><input type="text" name="fs_direction[stats][<?php echo (int) $i; ?>][value]" value="<?php echo esc_attr( (string) get_post_meta( $post->ID, self::stat_meta_key( $i, 'value' ), true ) ); ?>" class="widefat" placeholder="<?php esc_attr_e( '84', 'fs-lms-theme' ); ?>"></td>
						<td><input type="text" name="fs_direction[stats][<?php echo (int) $i; ?>][label]" value="<?php echo esc_attr( (string) get_post_meta( $post->ID, self::stat_meta_key( $i, 'label' ), true ) ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'средний балл', 'fs-lms-theme' ); ?>"></td>
					</tr>
				<?php endfor; ?>
			</tbody>
		</table>
		<p class="description"><?php esc_html_e( 'Пустая строка — факт не показывается.', 'fs-lms-theme' ); ?></p>
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

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- каждое поле очищается ниже.
		$input = isset( $_POST['fs_direction'] ) && is_array( $_POST['fs_direction'] ) ? wp_unslash( $_POST['fs_direction'] ) : array();

		foreach ( self::TEXT_FIELDS as $field => $meta_key ) {
			$raw   = isset( $input[ $field ] ) && is_string( $input[ $field ] ) ? $input[ $field ] : '';
			$value = in_array( $field, self::TEXTAREA_FIELDS, true ) ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );

			if ( 'key' === $field ) {
				$value = sanitize_key( $value );
			} elseif ( 'color' === $field && ! array_key_exists( $value, self::COLORS ) ) {
				$value = 'blue';
			} elseif ( 'grade_filter' === $field && ! in_array( $value, self::GRADE_FILTERS, true ) ) {
				$value = '';
			}

			update_post_meta( $post_id, $meta_key, $value );
		}

		update_post_meta( $post_id, self::META_CATALOG_IMAGE, isset( $input['catalog_image_id'] ) ? absint( $input['catalog_image_id'] ) : 0 );

		$stats = isset( $input['stats'] ) && is_array( $input['stats'] ) ? $input['stats'] : array();

		for ( $i = 0; $i < self::STATS_COUNT; $i++ ) {
			foreach ( array( 'value', 'label' ) as $part ) {
				$raw = isset( $stats[ $i ][ $part ] ) && is_string( $stats[ $i ][ $part ] ) ? $stats[ $i ][ $part ] : '';

				update_post_meta( $post_id, self::stat_meta_key( $i, $part ), sanitize_text_field( $raw ) );
			}
		}
	}

	/** Выбор картинки каталога из медиатеки — только на экране направления. */
	public function enqueue_admin_assets( string $hook ): void {
		$screen = get_current_screen();

		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || null === $screen || $this->post_type() !== $screen->post_type ) {
			return;
		}

		wp_enqueue_media();

		$path = get_theme_file_path( 'inc/Showcase/admin/media-field.js' );

		wp_enqueue_script( 'fs-lms-theme-media-field', get_theme_file_uri( 'inc/Showcase/admin/media-field.js' ), array( 'media-editor' ), (string) filemtime( $path ), true );
	}

	public function title_placeholder( string $placeholder, WP_Post $post ): string {
		return $this->post_type() === $post->post_type ? __( 'Название направления', 'fs-lms-theme' ) : $placeholder;
	}

	/**
	 * Столбцы списка: ключ предмета и класс перед «Порядком».
	 *
	 * @param array<string, string> $columns Столбцы ядра.
	 *
	 * @return array<string, string>
	 */
	public function admin_columns( array $columns ): array {
		$columns = $this->featured_image_columns( $columns );
		$order   = $columns['fs_menu_order'];

		unset( $columns['fs_menu_order'] );
		$columns['fs_direction_key']   = __( 'Предмет', 'fs-lms-theme' );
		$columns['fs_direction_grade'] = __( 'Класс', 'fs-lms-theme' );
		$columns['fs_direction_price'] = __( 'Цена', 'fs-lms-theme' );
		$columns['fs_menu_order']      = $order;

		return $columns;
	}

	public function render_admin_column( string $column, int $post_id ): void {
		$this->featured_image_column( $column, $post_id );

		$fields = array(
			'fs_direction_key'   => 'key',
			'fs_direction_grade' => 'grade_label',
			'fs_direction_price' => 'price',
		);

		if ( isset( $fields[ $column ] ) ) {
			echo esc_html( (string) get_post_meta( $post_id, self::TEXT_FIELDS[ $fields[ $column ] ], true ) );
		}
	}
}
