<?php
/**
 * «Как устроены занятия» — пять вариантов секции: на главной и на каждой из
 * четырёх страниц направлений (по указанию пользователя, 2026-09-13).
 *
 * До этого на главной секция была паттерном в коде, а на страницах
 * направлений — копией блоков в `post_content`, которую можно было править
 * только HTML в классическом редакторе. Теперь это записи в админке:
 * «Где показывать» (главная или направление), фото, заголовок, подзаголовок и
 * пункты списка. Плашка длительности и цены — общая, из «Настроек сайта»;
 * кнопки — те же, что были.
 *
 * Секцию выводит паттерн `fs-lms-theme/intensive-split`: на главной — вариант
 * «Главная», на странице направления — вариант его ключа (слаг страницы), а если
 * такого варианта нет — тоже «Главная». Страницы направлений переведены на
 * этот паттерн разовым обновлением (`inc/SubjectPages.php`, раскладка 5).
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Lessons extends FS_LMS_Theme_Content_Type {

	use FS_LMS_Theme_Featured_Image {
		admin_columns as featured_image_columns;
		render_admin_column as featured_image_column;
	}

	public const PLACE_HOME = 'home';

	private const META_PLACE       = '_fs_lessons_place';
	private const META_HEADING     = '_fs_lessons_heading';
	private const META_LEAD        = '_fs_lessons_lead';
	private const META_ITEMS       = '_fs_lessons_items';
	private const META_THEME_PHOTO = '_fs_lessons_theme_photo';
	private const NONCE            = 'fs_lms_theme_lessons_meta';

	/**
	 * Не `fs_lessons`: плагин fs-lms узнаёт свои типы записей по суффиксу
	 * (`PostTypeResolver`: `_lessons`, `_tasks`, `_articles`, `_works`,
	 * `_courses`, `_assessments`) и принял бы этот тип за «банк уроков»
	 * предмета `fs` — со своими столбцами, фильтрами и логикой.
	 */
	public function post_type(): string {
		return 'fs_lesson_format';
	}

	public function register(): void {
		parent::register();

		add_action( 'add_meta_boxes_' . $this->post_type(), array( $this, 'add_meta_box' ) );
		add_action( 'save_post_' . $this->post_type(), array( $this, 'save_meta' ), 5, 2 );
		add_filter( 'enter_title_here', array( $this, 'title_placeholder' ), 10, 2 );
	}

	protected function labels(): array {
		return array(
			'name'                  => __( 'Как устроены занятия', 'fs-lms-theme' ),
			'singular_name'         => __( 'Вариант «Как устроены занятия»', 'fs-lms-theme' ),
			'menu_name'             => __( 'Как устроены занятия', 'fs-lms-theme' ),
			'add_new'               => __( 'Добавить вариант', 'fs-lms-theme' ),
			'add_new_item'          => __( 'Новый вариант', 'fs-lms-theme' ),
			'edit_item'             => __( 'Редактировать вариант', 'fs-lms-theme' ),
			'new_item'              => __( 'Новый вариант', 'fs-lms-theme' ),
			'search_items'          => __( 'Найти вариант', 'fs-lms-theme' ),
			'not_found'             => __( 'Вариантов нет', 'fs-lms-theme' ),
			'not_found_in_trash'    => __( 'В корзине вариантов нет', 'fs-lms-theme' ),
			'all_items'             => __( 'Как устроены занятия', 'fs-lms-theme' ),
			'featured_image'        => __( 'Фото', 'fs-lms-theme' ),
			'set_featured_image'    => __( 'Выбрать фото', 'fs-lms-theme' ),
			'remove_featured_image' => __( 'Убрать фото', 'fs-lms-theme' ),
			'use_featured_image'    => __( 'Использовать как фото', 'fs-lms-theme' ),
		);
	}

	protected function menu_icon(): string {
		return 'dashicons-format-image';
	}

	protected function supports(): array {
		return array( 'title', 'thumbnail' );
	}

	/**
	 * Места показа: «Главная» и направления из `fs_lms_theme_subject_hero_patterns()`.
	 *
	 * @return array<string, string> Ключ → подпись.
	 */
	public function places(): array {
		$places = array( self::PLACE_HOME => __( 'Главная', 'fs-lms-theme' ) );

		foreach ( array_keys( fs_lms_theme_subject_hero_patterns() ) as $subject_key ) {
			$direction              = FS_LMS_Theme_Showcase::directions()->find( $subject_key );
			$places[ $subject_key ] = null === $direction ? $subject_key : $direction['title'];
		}

		return $places;
	}

	/* --------------------------------------------------------------------
	 * Стартовые записи
	 * ------------------------------------------------------------------ */

	/**
	 * «Главная» — из стартовых данных, направления — с их страниц: текст и фото,
	 * которые там стояли в копии секции (их могли поправить по-предметно).
	 * Нет страницы или секции — стартовые данные.
	 */
	protected function seed_items(): array {
		$home  = require __DIR__ . '/seeds/lessons.php';
		$items = array( $this->seed_item( self::PLACE_HOME, __( 'Главная', 'fs-lms-theme' ), $home ) );

		foreach ( $this->places() as $place => $label ) {
			if ( self::PLACE_HOME !== $place ) {
				$items[] = $this->seed_item( $place, $label, $this->from_page( $place ) ?? $home );
			}
		}

		return $items;
	}

	/**
	 * @param array{heading: string, lead: string, items: string[], photo: string, photo_id?: int} $data Данные варианта.
	 *
	 * @return array{title: string, meta: array<string, mixed>}
	 */
	private function seed_item( string $place, string $title, array $data ): array {
		$meta = array(
			self::META_PLACE       => $place,
			self::META_HEADING     => $data['heading'],
			self::META_LEAD        => $data['lead'],
			self::META_ITEMS       => implode( "\n", $data['items'] ),
			self::META_THEME_PHOTO => $data['photo'],
		);

		if ( ! empty( $data['photo_id'] ) ) {
			$meta['_thumbnail_id'] = (int) $data['photo_id'];
		}

		return array(
			'title' => $title,
			'meta'  => $meta,
		);
	}

	/**
	 * Текст и фото секции «Как устроены занятия» из содержимого страницы
	 * направления — копии блоков, которую туда вставляла тема.
	 *
	 * @return array{heading: string, lead: string, items: string[], photo: string, photo_id: int}|null
	 */
	public function from_page( string $subject_key ): ?array {
		$page = get_page_by_path( $subject_key );

		if ( ! $page instanceof WP_Post || ! class_exists( 'DOMDocument' ) ) {
			return null;
		}

		$section = '';

		foreach ( parse_blocks( $page->post_content ) as $block ) {
			if ( null !== $block['blockName'] && false !== strpos( serialize_block( $block ), 'fs-checklist' ) ) {
				$section = serialize_block( $block );
				break;
			}
		}

		if ( '' === $section ) {
			return null;
		}

		$previous = libxml_use_internal_errors( true );
		$document = new DOMDocument();
		$document->loadHTML( '<?xml encoding="UTF-8"><div>' . $section . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();
		libxml_use_internal_errors( $previous );

		$xpath = new DOMXPath( $document );
		$text  = static function ( ?DOMNode $node ): string {
			return null === $node ? '' : trim( (string) preg_replace( '/\s+/u', ' ', $node->textContent ) );
		};

		$items = array();

		foreach ( $xpath->query( "//p[contains(concat(' ', normalize-space(@class), ' '), ' fs-checklist__item ')]" ) as $item ) {
			$items[] = $text( $item );
		}

		$default = require __DIR__ . '/seeds/lessons.php';
		$image   = $xpath->query( '//img' )->item( 0 );
		$src     = $image instanceof DOMElement ? $image->getAttribute( 'src' ) : '';
		$photo   = $default['photo'];
		$photo_id = 0;

		if ( preg_match( '#/wp-content/themes/[^/]+/(.+)$#', $src, $matches ) ) {
			$photo = $matches[1];
		} elseif ( '' !== $src ) {
			$photo_id = attachment_url_to_postid( (string) preg_replace( '/-\d+x\d+(\.[a-z0-9]+)$/i', '$1', $src ) );
		}

		return array(
			'heading'  => $text( $xpath->query( '//h2' )->item( 0 ) ),
			'lead'     => $text( $xpath->query( "//p[contains(@class, 'has-text-secondary-color')]" )->item( 0 ) ),
			'items'    => array_values( array_filter( $items ) ),
			'photo'    => $photo,
			'photo_id' => (int) $photo_id,
		);
	}

	/* --------------------------------------------------------------------
	 * Вывод
	 * ------------------------------------------------------------------ */

	/**
	 * Место показа для текущего запроса: главная — «Главная», страница —
	 * её слаг (на странице направления это ключ предмета).
	 */
	public function current_place(): string {
		if ( is_front_page() ) {
			return self::PLACE_HOME;
		}

		$object = get_queried_object();

		return $object instanceof WP_Post && 'page' === $object->post_type ? $object->post_name : self::PLACE_HOME;
	}

	/** Опубликованный вариант места. */
	private function find( string $place ): ?WP_Post {
		$posts = $this->items(
			array(
				'numberposts' => 1,
				'orderby'     => array( 'ID' => 'ASC' ),
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- единицы записей.
				'meta_query'  => array(
					array(
						'key'   => self::META_PLACE,
						'value' => $place,
					),
				),
			)
		);

		return $posts[0] ?? null;
	}

	/**
	 * Секция для места: его вариант, иначе «Главная», иначе стартовые данные.
	 */
	public function section_markup( string $place ): string {
		$post = $this->find( $place ) ?? $this->find( self::PLACE_HOME );

		if ( null !== $post ) {
			$heading = (string) get_post_meta( $post->ID, self::META_HEADING, true );
			$lessons = array(
				'heading' => '' === $heading ? __( 'Как устроены занятия', 'fs-lms-theme' ) : $heading,
				'lead'    => (string) get_post_meta( $post->ID, self::META_LEAD, true ),
				'items'   => $this->lines( (string) get_post_meta( $post->ID, self::META_ITEMS, true ) ),
				'image'   => $this->image_html( $post, 'large', (string) get_post_meta( $post->ID, self::META_THEME_PHOTO, true ), __( 'Фото занятия', 'fs-lms-theme' ) ),
			);
		} else {
			$default = require __DIR__ . '/seeds/lessons.php';
			$lessons = array(
				'heading' => $default['heading'],
				'lead'    => $default['lead'],
				'items'   => $default['items'],
				'image'   => $this->attachment_or_fallback_html( 0, 'large', $default['photo'], $default['alt'] ),
			);
		}

		// Цена акцентным цветом — на страницах направлений, как было в их копиях секции.
		$accent = self::PLACE_HOME !== $place && array_key_exists( $place, fs_lms_theme_subject_hero_patterns() );

		ob_start();
		include __DIR__ . '/views/lessons.php';

		return (string) ob_get_clean();
	}

	/**
	 * Строки списка: по одной на строку, пустые пропускаются.
	 *
	 * @return string[]
	 */
	private function lines( string $text ): array {
		return array_values( array_filter( array_map( 'trim', preg_split( '/\R/u', $text ) ) ) );
	}

	/* --------------------------------------------------------------------
	 * Админка
	 * ------------------------------------------------------------------ */

	/** Кэш WP Rocket — всего сайта: секция на главной и на страницах направлений. */
	public function purge_page_cache(): void {
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
	}

	public function add_meta_box(): void {
		add_meta_box( 'fs-lessons-block', __( 'Блок «Как устроены занятия»', 'fs-lms-theme' ), array( $this, 'render_meta_box' ), $this->post_type(), 'normal', 'high' );
	}

	public function render_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::NONCE, self::NONCE );

		$place = (string) get_post_meta( $post->ID, self::META_PLACE, true );
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="fs-lessons-place"><?php esc_html_e( 'Где показывать', 'fs-lms-theme' ); ?></label></th>
				<td><select id="fs-lessons-place" name="fs_lessons[place]">
					<?php foreach ( $this->places() as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $place, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php esc_html_e( 'Если у страницы направления нет своего варианта, на ней показывается вариант «Главная».', 'fs-lms-theme' ); ?></p></td>
			</tr>
			<tr>
				<th scope="row"><label for="fs-lessons-heading"><?php esc_html_e( 'Заголовок', 'fs-lms-theme' ); ?></label></th>
				<td><input type="text" id="fs-lessons-heading" name="fs_lessons[heading]" value="<?php echo esc_attr( (string) get_post_meta( $post->ID, self::META_HEADING, true ) ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Как устроены занятия', 'fs-lms-theme' ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="fs-lessons-lead"><?php esc_html_e( 'Подзаголовок', 'fs-lms-theme' ); ?></label></th>
				<td><textarea id="fs-lessons-lead" name="fs_lessons[lead]" rows="2" class="large-text"><?php echo esc_textarea( (string) get_post_meta( $post->ID, self::META_LEAD, true ) ); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="fs-lessons-items"><?php esc_html_e( 'Пункты списка', 'fs-lms-theme' ); ?></label></th>
				<td><textarea id="fs-lessons-items" name="fs_lessons[items]" rows="9" class="large-text"><?php echo esc_textarea( (string) get_post_meta( $post->ID, self::META_ITEMS, true ) ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Каждый пункт — с новой строки.', 'fs-lms-theme' ); ?></p></td>
			</tr>
		</table>
		<p class="description"><?php esc_html_e( 'Фото — справа, «Фото», примерно 4 : 3 (например, 1600×1200), обрезается по краям. Пока фото не выбрано, показывается прежнее. Длительность и цена под списком — в «Настройках сайта».', 'fs-lms-theme' ); ?></p>
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
		$input = isset( $_POST['fs_lessons'] ) && is_array( $_POST['fs_lessons'] ) ? wp_unslash( $_POST['fs_lessons'] ) : array();
		$field = static function ( string $key ) use ( $input ): string {
			return isset( $input[ $key ] ) && is_string( $input[ $key ] ) ? $input[ $key ] : '';
		};

		$place = sanitize_key( $field( 'place' ) );

		update_post_meta( $post_id, self::META_PLACE, array_key_exists( $place, $this->places() ) ? $place : self::PLACE_HOME );
		update_post_meta( $post_id, self::META_HEADING, sanitize_text_field( $field( 'heading' ) ) );
		update_post_meta( $post_id, self::META_LEAD, sanitize_textarea_field( $field( 'lead' ) ) );
		update_post_meta( $post_id, self::META_ITEMS, implode( "\n", array_map( 'sanitize_text_field', $this->lines( $field( 'items' ) ) ) ) );
	}

	public function title_placeholder( string $placeholder, WP_Post $post ): string {
		return $this->post_type() === $post->post_type ? __( 'Название варианта (видно только в админке)', 'fs-lms-theme' ) : $placeholder;
	}

	/**
	 * Столбцы списка: фото, название, «Где показывать» (без «Порядка»).
	 *
	 * @param array<string, string> $columns Столбцы ядра.
	 *
	 * @return array<string, string>
	 */
	public function admin_columns( array $columns ): array {
		$columns = $this->featured_image_columns( $columns );

		unset( $columns['fs_menu_order'] );
		$columns['fs_lessons_place'] = __( 'Где показывать', 'fs-lms-theme' );

		return $columns;
	}

	public function render_admin_column( string $column, int $post_id ): void {
		$this->featured_image_column( $column, $post_id );

		if ( 'fs_lessons_place' === $column ) {
			$place = (string) get_post_meta( $post_id, self::META_PLACE, true );

			echo esc_html( $this->places()[ $place ] ?? $place );
		}
	}
}
