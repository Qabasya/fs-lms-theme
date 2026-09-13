<?php
/**
 * Общая механика «витринных» записей главной (этап 1 плана редактирования,
 * 2026-09-13): выпускники, логотипы вузов.
 *
 * Карусели главной раньше были статичными блоками в паттернах темы — их
 * содержимое правилось только в коде или сохранением шаблона главной в
 * «Редактировать сайт», после которого шаблон отвязывается от темы (правки
 * вёрстки из кода до главной больше не доходят, «Записаться» в шапке
 * застывает). Здесь содержимое — обычные записи в админке (заголовок,
 * изображение записи, порядок, свои поля), а разметку каруселей по-прежнему
 * печатает тема: паттерн собирает слайды из опубликованных записей.
 *
 * Тип записи закрыт для фронта (`public => false`): отдельных страниц у
 * выпускника или вуза нет, в поиск и карту сайта они не попадают. Редактор —
 * классический экран с метабоксами (`show_in_rest => false`), он работает
 * одинаково и на проде с WPBakery, и локально с Classic Editor.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class FS_LMS_Theme_Showcase_Type {

	/** Префикс опции «стартовые записи уже заведены» (`{prefix}{post_type}`). */
	private const SEEDED_OPTION_PREFIX = 'fs_lms_theme_seeded_';

	/** Слаг типа записи (до 20 символов). */
	abstract public function post_type(): string;

	/**
	 * Подписи типа записи для админки.
	 *
	 * @return array<string, string>
	 */
	abstract protected function labels(): array;

	/** Dashicon пункта меню. */
	abstract protected function menu_icon(): string;

	/**
	 * Стартовые записи — то, что до этапа 1 было зашито в паттерне, чтобы
	 * после обновления главная выглядела так же.
	 *
	 * @return array<int, array{title: string, meta?: array<string, string>}>
	 */
	abstract protected function seed_items(): array;

	/** Разметка одного слайда карусели. */
	abstract protected function render_slide( WP_Post $post ): string;

	public function register(): void {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'seed' ), 20 );
		add_action( 'pre_get_posts', array( $this, 'order_admin_list' ) );
		add_action( 'save_post_' . $this->post_type(), array( $this, 'purge_page_cache' ) );
		add_action( 'trashed_post', array( $this, 'purge_page_cache_for' ) );
		add_action( 'untrashed_post', array( $this, 'purge_page_cache_for' ) );
		add_action( 'deleted_post', array( $this, 'purge_page_cache_for' ) );
		add_filter( 'manage_' . $this->post_type() . '_posts_columns', array( $this, 'admin_columns' ) );
		add_action( 'manage_' . $this->post_type() . '_posts_custom_column', array( $this, 'render_admin_column' ), 10, 2 );
	}

	public function register_post_type(): void {
		register_post_type(
			$this->post_type(),
			array(
				'labels'              => $this->labels(),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_rest'        => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'has_archive'         => false,
				'rewrite'             => false,
				'menu_position'       => 21,
				'menu_icon'           => $this->menu_icon(),
				'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
			)
		);
	}

	/**
	 * Опубликованные записи в порядке показа: поле «Порядок», затем название.
	 *
	 * @return WP_Post[]
	 */
	public function items(): array {
		return get_posts(
			array(
				'post_type'        => $this->post_type(),
				'post_status'      => 'publish',
				'numberposts'      => -1,
				'orderby'          => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'no_found_rows'    => true,
				'suppress_filters' => false,
			)
		);
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
	 * Один раз заводит стартовые записи.
	 *
	 * Отметка ставится до вставки через `add_option()` — он ничего не пишет,
	 * если опция уже есть, поэтому два одновременных первых запроса после
	 * обновления темы не заведут записи дважды. Если записи этого типа уже
	 * есть (например, завели руками), стартовые не добавляются. Удалённые
	 * потом записи тема не возвращает. Опция автозагружаемая — проверка на
	 * каждом запросе не стоит отдельного запроса к базе.
	 */
	public function seed(): void {
		if ( ! add_option( self::SEEDED_OPTION_PREFIX . $this->post_type(), '1', '', true ) ) {
			return;
		}

		$existing = get_posts(
			array(
				'post_type'        => $this->post_type(),
				'post_status'      => 'any',
				'numberposts'      => 1,
				'fields'           => 'ids',
				'no_found_rows'    => true,
				'suppress_filters' => false,
			)
		);

		if ( array() !== $existing ) {
			return;
		}

		foreach ( $this->seed_items() as $index => $item ) {
			wp_insert_post(
				wp_slash(
					array(
						'post_type'   => $this->post_type(),
						'post_status' => 'publish',
						'post_title'  => $item['title'],
						'menu_order'  => ( $index + 1 ) * 10,
						'meta_input'  => $item['meta'] ?? array(),
					)
				)
			);
		}
	}

	/**
	 * Список записей в админке — в порядке показа на сайте, пока редактор
	 * сам не выбрал сортировку по столбцу.
	 */
	public function order_admin_list( WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() || $this->post_type() !== $query->get( 'post_type' ) ) {
			return;
		}

		if ( '' === (string) $query->get( 'orderby' ) ) {
			$query->set(
				'orderby',
				array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				)
			);
		}
	}

	/**
	 * Сброс кэша страниц WP Rocket для главной: при сохранении записи он
	 * чистит только её собственный адрес, а у этих записей адреса нет —
	 * карусель на главной оставалась бы старой до ручной очистки кэша.
	 */
	public function purge_page_cache(): void {
		if ( function_exists( 'rocket_clean_home' ) ) {
			rocket_clean_home();
		}
	}

	/** То же для удаления, корзины и восстановления из неё. */
	public function purge_page_cache_for( int $post_id ): void {
		if ( get_post_type( $post_id ) === $this->post_type() ) {
			$this->purge_page_cache();
		}
	}

	/**
	 * Столбцы списка: миниатюра после флажка, «Порядок» в конце.
	 *
	 * @param array<string, string> $columns Столбцы ядра.
	 *
	 * @return array<string, string>
	 */
	public function admin_columns( array $columns ): array {
		$result = array();

		foreach ( $columns as $key => $label ) {
			$result[ $key ] = $label;

			if ( 'cb' === $key ) {
				$result['fs_thumbnail'] = $this->labels()['featured_image'] ?? __( 'Изображение', 'fs-lms-theme' );
			}
		}

		unset( $result['date'] );
		$result['fs_menu_order'] = __( 'Порядок', 'fs-lms-theme' );

		return $result;
	}

	public function render_admin_column( string $column, int $post_id ): void {
		if ( 'fs_thumbnail' === $column ) {
			$thumbnail_id = (int) get_post_thumbnail_id( $post_id );

			echo $thumbnail_id > 0
				? wp_get_attachment_image( $thumbnail_id, array( 60, 60 ), false, array( 'style' => 'width:60px;height:60px;object-fit:contain' ) )
				: '—';
		}

		if ( 'fs_menu_order' === $column ) {
			echo esc_html( (string) get_post_field( 'menu_order', $post_id ) );
		}
	}
}
