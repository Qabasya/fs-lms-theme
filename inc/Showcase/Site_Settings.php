<?php
/**
 * «Настройки сайта» — контакты, ссылки, цены и факты одним экраном в админке
 * (этап 4, 2026-09-13).
 *
 * До этого телефон, почта и адрес были зашиты примерно в 20 местах темы
 * (топбар, подвал, секции с формами, «Контакты», «О нас», микроразметка,
 * адрес для заявок), цены и факты — в паттернах главной и в копиях секции
 * «Как устроены занятия» на страницах направлений. Теперь всё это читается
 * отсюда. Значения по умолчанию — те, что стояли в теме, поэтому после
 * обновления сайт выглядит так же, пока настройки не поменяли.
 *
 * Где что выводится — в подсказках к полям (`fields()`).
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Site_Settings {

	private const OPTION = 'fs_lms_theme_site_settings';
	private const GROUP  = 'fs_lms_theme_site_settings_group';
	private const PAGE   = FS_LMS_Theme_Showcase::MENU_SLUG;

	/** Значения по умолчанию — прежние значения из кода темы. */
	private const DEFAULTS = array(
		'phone'               => '+7 995 326 44 86',
		'email'               => 'info@future-step.ru',
		'leads_email'         => 'info@future-step.ru',
		'zip'                 => '236006',
		'city'                => 'Калининград',
		'street'              => 'ул. Черняховского, д. 6, каб. 316',
		'hours'               => 'с понедельника по субботу с 11.00 до 21.00 (по московскому времени)',
		'max_url'             => 'https://max.ru/u/f9LHodD0cOKoJqQrKkMSbocYBDaed99orfRNtpWEvXtVKst1I0xZAk2tjvg',
		'vk_url'              => 'https://vk.ru/future_step39',
		'yandex_maps_url'     => 'https://yandex.ru/maps/-/CTdGaCPB',
		'twogis_url'          => 'https://2gis.ru/kaliningrad/firm/70000001080562359/tab/reviews',
		'hero_stat_1_value'   => '84',
		'hero_stat_1_label'   => 'средний балл',
		'hero_stat_2_value'   => 'до 875 ₽',
		'hero_stat_2_label'   => 'час занятий',
		'hero_stat_3_value'   => 'до 8',
		'hero_stat_3_label'   => 'человек в группе',
		'lesson_length_value' => '2 часа',
		'lesson_length_label' => 'одно занятие',
		'lesson_price_value'  => '800 ₽',
		'lesson_price_label'  => 'за час',
	);

	/** Поля-адреса почты и ссылки — своя проверка при сохранении. */
	private const EMAIL_FIELDS = array( 'email', 'leads_email' );
	private const URL_FIELDS   = array( 'max_url', 'vk_url', 'yandex_maps_url', 'twogis_url' );

	/** Плашка «Как устроены занятия»: поле настройки ↔ класс абзаца. */
	private const PLAQUE_FIELDS = array(
		'lesson_length_value' => 'fs-price-plaque__value',
		'lesson_length_label' => 'fs-price-plaque__label',
		'lesson_price_value'  => 'fs-price-plaque__value',
		'lesson_price_label'  => 'fs-price-plaque__label',
	);

	/** @var array<string, string>|null */
	private $values = null;

	public function register(): void {
		// Приоритет 9: пункт «Настройки темы» и его первый подпункт должны
		// появиться раньше подпунктов типов записей (`_add_post_type_submenus`,
		// приоритет 10), иначе «Настройки сайта» окажутся в конце списка.
		add_action( 'admin_menu', array( $this, 'add_page' ), 9 );
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'update_option_' . self::OPTION, array( $this, 'after_update' ) );
		add_filter( 'render_block_core/paragraph', array( $this, 'fill_lesson_plaque' ), 10, 2 );
	}

	/* --------------------------------------------------------------------
	 * Значения
	 * ------------------------------------------------------------------ */

	public function get( string $key ): string {
		if ( null === $this->values ) {
			$stored       = get_option( self::OPTION, array() );
			$this->values = array_merge( self::DEFAULTS, is_array( $stored ) ? array_intersect_key( $stored, self::DEFAULTS ) : array() );
		}

		return (string) ( $this->values[ $key ] ?? '' );
	}

	/** Телефон для ссылки `tel:` — только цифры и плюс. */
	public function phone_href(): string {
		return 'tel:' . preg_replace( '/[^\d+]/', '', $this->get( 'phone' ) );
	}

	/** «236006, г. Калининград, ул. Черняховского, д. 6, каб. 316». */
	public function address_full(): string {
		$city = '' === $this->get( 'city' ) ? '' : 'г. ' . $this->get( 'city' );

		return implode( ', ', array_filter( array( $this->get( 'zip' ), $city, $this->get( 'street' ) ) ) );
	}

	/** «Калининград, ул. Черняховского, д. 6, каб. 316» — в секциях с формами. */
	public function address_short(): string {
		return implode( ', ', array_filter( array( $this->get( 'city' ), $this->get( 'street' ) ) ) );
	}

	/**
	 * Адрес в две строки для подвала: «236006, г. Калининград,» и улица.
	 *
	 * @return string[]
	 */
	public function address_lines(): array {
		$city = '' === $this->get( 'city' ) ? '' : 'г. ' . $this->get( 'city' );
		$head = implode( ', ', array_filter( array( $this->get( 'zip' ), $city ) ) );

		return array_values( array_filter( array( '' === $head ? '' : $head . ',', $this->get( 'street' ) ) ) );
	}

	/**
	 * Факты под формой первого экрана главной; пустые не выводятся.
	 *
	 * @return array<int, array{0: string, 1: string}>
	 */
	public function hero_stats(): array {
		$stats = array();

		for ( $i = 1; $i <= 3; $i++ ) {
			$value = $this->get( "hero_stat_{$i}_value" );
			$label = $this->get( "hero_stat_{$i}_label" );

			if ( '' !== $value || '' !== $label ) {
				$stats[] = array( $value, $label );
			}
		}

		return $stats;
	}

	/* --------------------------------------------------------------------
	 * Плашка «Как устроены занятия»
	 * ------------------------------------------------------------------ */

	/**
	 * Значения плашки «2 часа / одно занятие / 800 ₽ / за час» из настроек.
	 *
	 * Плашку выводит секция «Как устроены занятия» (`inc/Showcase/views/lessons.php`)
	 * с прежними значениями по умолчанию. Подмена при выводе: абзац плашки,
	 * в котором стоит прежнее значение, показывает значение из настроек;
	 * другое значение (например, в старой копии секции, которую на странице
	 * переписали руками) остаётся своим. Разметка и классы абзаца (акцентный
	 * цвет цены на страницах направлений) не трогаются.
	 *
	 * @param string               $content Готовый HTML абзаца.
	 * @param array<string, mixed> $block   Разобранный блок.
	 */
	public function fill_lesson_plaque( string $content, array $block ): string {
		$class_name = (string) ( $block['attrs']['className'] ?? '' );

		if ( false === strpos( $class_name, 'fs-price-plaque__' ) ) {
			return $content;
		}

		$text = trim( wp_strip_all_tags( $content ) );

		foreach ( self::PLAQUE_FIELDS as $field => $required_class ) {
			if ( self::DEFAULTS[ $field ] !== $text || false === strpos( $class_name, $required_class ) ) {
				continue;
			}

			$value = $this->get( $field );

			if ( $value === $text ) {
				return $content;
			}

			return (string) preg_replace( '/>(\s*)' . preg_quote( $text, '/' ) . '(\s*)</u', '>${1}' . str_replace( array( '\\', '$' ), array( '\\\\', '\\$' ), esc_html( $value ) ) . '${2}<', $content, 1 );
		}

		return $content;
	}

	/* --------------------------------------------------------------------
	 * Экран настроек
	 * ------------------------------------------------------------------ */

	/**
	 * Общий пункт меню «Настройки темы» (по указанию пользователя, 2026-09-13):
	 * все разделы темы — подпунктами под ним. Сам пункт открывает этот экран,
	 * первый подпункт называется «Настройки сайта».
	 */
	public function add_page(): void {
		add_menu_page(
			__( 'Настройки сайта', 'fs-lms-theme' ),
			__( 'Настройки темы', 'fs-lms-theme' ),
			'manage_options',
			self::PAGE,
			array( $this, 'render_page' ),
			'dashicons-admin-site-alt3',
			21
		);

		add_submenu_page(
			self::PAGE,
			__( 'Настройки сайта', 'fs-lms-theme' ),
			__( 'Настройки сайта', 'fs-lms-theme' ),
			'manage_options',
			self::PAGE,
			array( $this, 'render_page' )
		);
	}

	public function register_setting(): void {
		register_setting(
			self::GROUP,
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize' ),
				'default'           => self::DEFAULTS,
				'show_in_rest'      => false,
			)
		);
	}

	/**
	 * Разделы и поля экрана: ключ → подпись и подсказка «где выводится».
	 *
	 * @return array<string, array{title: string, fields: array<string, array{label: string, help?: string}>}>
	 */
	private function fields(): array {
		return array(
			'contacts' => array(
				'title'  => __( 'Контакты', 'fs-lms-theme' ),
				'fields' => array(
					'phone'       => array(
						'label' => __( 'Телефон', 'fs-lms-theme' ),
						'help'  => __( 'Шапка, подвал, секции с формами, «Контакты», «О нас», микроразметка для поисковиков. Ссылка для звонка собирается из цифр.', 'fs-lms-theme' ),
					),
					'email'       => array(
						'label' => __( 'Почта', 'fs-lms-theme' ),
						'help'  => __( 'Там же, где телефон.', 'fs-lms-theme' ),
					),
					'zip'         => array( 'label' => __( 'Индекс', 'fs-lms-theme' ) ),
					'city'        => array( 'label' => __( 'Город', 'fs-lms-theme' ) ),
					'street'      => array(
						'label' => __( 'Улица, дом, кабинет', 'fs-lms-theme' ),
						'help'  => __( 'Подвал и «Контакты» — с индексом и городом, секции с формами — с городом.', 'fs-lms-theme' ),
					),
					'hours'       => array(
						'label' => __( 'Режим работы', 'fs-lms-theme' ),
						'help'  => __( 'Страница «О нас».', 'fs-lms-theme' ),
					),
					'leads_email' => array(
						'label' => __( 'Куда приходят заявки с форм', 'fs-lms-theme' ),
						'help'  => __( 'Адрес для писем с форм записи. На сайте не показывается.', 'fs-lms-theme' ),
					),
				),
			),
			'links'    => array(
				'title'  => __( 'Ссылки на странице «Контакты»', 'fs-lms-theme' ),
				'fields' => array(
					'max_url'         => array( 'label' => 'MAX' ),
					'vk_url'          => array( 'label' => 'ВКонтакте' ),
					'yandex_maps_url' => array( 'label' => __( 'Яндекс Карты', 'fs-lms-theme' ) ),
					'twogis_url'      => array( 'label' => __( '2ГИС', 'fs-lms-theme' ) ),
				),
			),
			'facts'    => array(
				'title'  => __( 'Цены и факты', 'fs-lms-theme' ),
				'fields' => array(
					'hero_stat_1_value'   => array(
						'label' => __( 'Главная, под формой: факт 1', 'fs-lms-theme' ),
						'help'  => __( 'Значение и подпись. Пустые значение и подпись — факт не показывается. Факты на страницах направлений — в «Направлениях».', 'fs-lms-theme' ),
					),
					'hero_stat_1_label'   => array( 'label' => '' ),
					'hero_stat_2_value'   => array( 'label' => __( 'Главная, под формой: факт 2', 'fs-lms-theme' ) ),
					'hero_stat_2_label'   => array( 'label' => '' ),
					'hero_stat_3_value'   => array( 'label' => __( 'Главная, под формой: факт 3', 'fs-lms-theme' ) ),
					'hero_stat_3_label'   => array( 'label' => '' ),
					'lesson_length_value' => array(
						'label' => __( '«Как устроены занятия»: длительность', 'fs-lms-theme' ),
						'help'  => __( 'Плашка под списком «Как устроены занятия» — на главной и на страницах направлений.', 'fs-lms-theme' ),
					),
					'lesson_length_label' => array( 'label' => '' ),
					'lesson_price_value'  => array( 'label' => __( '«Как устроены занятия»: цена', 'fs-lms-theme' ) ),
					'lesson_price_label'  => array( 'label' => '' ),
				),
			),
		);
	}

	/**
	 * Очистка при сохранении. Неверная почта или ссылка не сохраняется —
	 * остаётся прежнее значение, а сверху экрана появляется предупреждение.
	 *
	 * @param mixed $input Данные формы.
	 *
	 * @return array<string, string>
	 */
	public function sanitize( $input ): array {
		$input  = is_array( $input ) ? $input : array();
		$result = array();

		foreach ( array_keys( self::DEFAULTS ) as $key ) {
			$raw = isset( $input[ $key ] ) && is_string( $input[ $key ] ) ? trim( $input[ $key ] ) : '';

			if ( in_array( $key, self::EMAIL_FIELDS, true ) ) {
				$email = sanitize_email( $raw );

				if ( '' === $email || ! is_email( $email ) ) {
					add_settings_error( self::OPTION, $key, sprintf( /* translators: %s: адрес */ __( 'Адрес почты «%s» не сохранён — он неверный.', 'fs-lms-theme' ), $raw ) );
					$email = $this->get( $key );
				}

				$result[ $key ] = $email;
				continue;
			}

			if ( in_array( $key, self::URL_FIELDS, true ) ) {
				$url = '' === $raw ? '' : esc_url_raw( $raw, array( 'https', 'http' ) );

				if ( '' !== $raw && '' === $url ) {
					add_settings_error( self::OPTION, $key, sprintf( /* translators: %s: ссылка */ __( 'Ссылка «%s» не сохранена — она неверная.', 'fs-lms-theme' ), $raw ) );
					$url = $this->get( $key );
				}

				$result[ $key ] = $url;
				continue;
			}

			$result[ $key ] = sanitize_text_field( $raw );
		}

		return $result;
	}

	/**
	 * После сохранения: сброс значений в памяти и кэша WP Rocket всего сайта —
	 * контакты есть на каждой странице.
	 */
	public function after_update(): void {
		$this->values = null;

		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
	}

	public function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Настройки сайта', 'fs-lms-theme' ); ?></h1>
			<p><?php esc_html_e( 'Контакты, ссылки, цены и факты, которые повторяются на разных страницах. Меняются здесь один раз — на сайте везде сразу.', 'fs-lms-theme' ); ?></p>
			<?php
			// Без аргумента: у страницы в своём пункте меню ядро само не
			// выводит «Настройки сохранены» (это делает только options-head.php
			// на страницах «Настроек»), а сообщение лежит под ключом `general`.
			settings_errors();
			?>
			<form method="post" action="options.php">
				<?php settings_fields( self::GROUP ); ?>
				<?php foreach ( $this->fields() as $section ) : ?>
					<h2 class="title"><?php echo esc_html( $section['title'] ); ?></h2>
					<table class="form-table" role="presentation">
						<?php $this->render_rows( $section['fields'] ); ?>
					</table>
				<?php endforeach; ?>
				<?php submit_button( __( 'Сохранить настройки', 'fs-lms-theme' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Строки таблицы. Пары «значение + подпись» (`*_value` и следующий за
	 * ним `*_label` с пустой подписью) — два поля в одной строке.
	 *
	 * @param array<string, array{label: string, help?: string}> $fields Поля раздела.
	 */
	private function render_rows( array $fields ): void {
		foreach ( $fields as $key => $field ) {
			if ( '' === $field['label'] ) {
				continue; // Подпись пары выводится вместе со значением.
			}

			$pair_key = preg_match( '/_value$/', $key ) ? preg_replace( '/_value$/', '_label', $key ) : null;
			$type     = in_array( $key, self::EMAIL_FIELDS, true ) ? 'email' : ( in_array( $key, self::URL_FIELDS, true ) ? 'url' : 'text' );
			$wide     = 'url' === $type || in_array( $key, array( 'street', 'hours' ), true );
			?>
			<tr>
				<th scope="row"><label for="<?php echo esc_attr( 'fs-setting-' . $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
				<td>
					<input type="<?php echo esc_attr( $type ); ?>" id="<?php echo esc_attr( 'fs-setting-' . $key ); ?>" name="<?php echo esc_attr( self::OPTION . '[' . $key . ']' ); ?>" value="<?php echo esc_attr( $this->get( $key ) ); ?>" class="<?php echo esc_attr( null !== $pair_key ? 'small-text' : ( $wide ? 'large-text' : 'regular-text' ) ); ?>"<?php echo null === $pair_key ? '' : ' style="width:8rem"'; ?>>
					<?php if ( null !== $pair_key && isset( $fields[ $pair_key ] ) ) : ?>
						<input type="text" name="<?php echo esc_attr( self::OPTION . '[' . $pair_key . ']' ); ?>" value="<?php echo esc_attr( $this->get( $pair_key ) ); ?>" class="regular-text" aria-label="<?php echo esc_attr( $field['label'] . ' — ' . __( 'подпись', 'fs-lms-theme' ) ); ?>">
					<?php endif; ?>
					<?php if ( ! empty( $field['help'] ) ) : ?>
						<p class="description"><?php echo esc_html( $field['help'] ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		}
	}
}
