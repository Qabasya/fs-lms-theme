<?php
/**
 * «Учебник и тренажёр» — тексты страниц-хабов `/articles/` и `/tasks/`
 * (по указанию пользователя, 2026-09-13): заголовок, вводный текст, текст и
 * кнопка карточек ЕГЭ и ОГЭ.
 *
 * Страницы тема заполняла один раз при создании (`inc/ResourcePages.php`), и
 * тексты жили копией в `post_content` — только HTML в классическом редакторе.
 * Теперь содержимое страниц — ссылка на паттерн (`resource-articles`/
 * `resource-tasks`), а тексты — здесь. Текущие тексты со страниц переносятся
 * сюда разовым обновлением (`import_from_content()`), значения по умолчанию —
 * прежние из генератора.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FS_LMS_Theme_Resource_Texts {

	private const OPTION = 'fs_lms_theme_resource_texts';
	private const GROUP  = 'fs_lms_theme_resource_texts_group';
	private const PAGE   = 'fs-lms-theme-resource-texts';

	/** Страница → слаг, заголовок раздела на экране. */
	public const PAGES = array(
		'articles' => 'Учебник',
		'tasks'    => 'Тренажёр',
	);

	/** Карточка → ключ предмета и модификатор карточки (`fs-subject-more-card--*`). */
	public const CARDS = array(
		'ege' => 'inf_ege',
		'oge' => 'inf_oge',
	);

	private const DEFAULTS = array(
		'articles_title'      => 'Учебник',
		'articles_intro'      => 'Учебник — это теория по всем темам экзамена, собранная в одном месте: разборы заданий, примеры решений и конспекты, которые можно открыть с компьютера и с телефона. Материалы структурированы по номерам заданий, поэтому вы всегда видите, что уже разобрано, а что осталось. Доступ открывается ученикам курсов и доступен в течение всего учебного года.',
		'articles_ege_text'   => '27 заданий: от кодирования информации и таблиц истинности до программирования на Python. Каждая тема — теория, разобранные примеры и типичные ошибки на экзамене.',
		'articles_ege_button' => 'Открыть учебник ЕГЭ',
		'articles_oge_text'   => 'Теория к первой части и подробные разборы практических заданий 13–15: работа с файлами, электронные таблицы и написание программы.',
		'articles_oge_button' => 'Открыть учебник ОГЭ',
		'tasks_title'         => 'Тренажёр',
		'tasks_intro'         => 'Тренажёр — это задачи по номерам заданий с моментальной проверкой ответа. Можно решать отдельную тему, пока она не начнёт получаться, или собрать вариант целиком и уложиться в экзаменационное время. Статистика показывает, сколько задач решено и где чаще всего возникают ошибки.',
		'tasks_ege_text'      => 'Задачи ко всем 27 заданиям, задания с файлами и полные варианты с таймером. Ответы проверяются автоматически, к сложным задачам есть разбор решения.',
		'tasks_ege_button'    => 'Перейти в тренажёр ЕГЭ',
		'tasks_oge_text'      => 'Тестовая часть с проверкой ответа и практические задания 13–15 с файлами, которые нужно скачать, выполнить и загрузить обратно.',
		'tasks_oge_button'    => 'Перейти в тренажёр ОГЭ',
	);

	/** Многострочные поля. */
	private const TEXTAREA_SUFFIXES = array( '_intro', '_text' );

	/** @var array<string, string>|null */
	private $values = null;

	public function register(): void {
		// После подпунктов типов записей (приоритет 10) — последним в «Настройках темы».
		add_action( 'admin_menu', array( $this, 'add_page' ), 11 );
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'update_option_' . self::OPTION, array( $this, 'after_update' ) );
	}

	public function get( string $key ): string {
		if ( null === $this->values ) {
			$stored       = get_option( self::OPTION, array() );
			$this->values = array_merge( self::DEFAULTS, is_array( $stored ) ? array_intersect_key( $stored, self::DEFAULTS ) : array() );
		}

		return (string) ( $this->values[ $key ] ?? '' );
	}

	/**
	 * Разово переносит тексты со страницы (прежняя копия в `post_content`) в
	 * настройки — только поля, которые в настройках ещё не сохраняли.
	 *
	 * @param string $slug    `articles` или `tasks`.
	 * @param string $content Содержимое страницы.
	 */
	public function import_from_content( string $slug, string $content ): void {
		if ( ! isset( self::PAGES[ $slug ] ) || false === strpos( $content, 'fs-subject-more-card' ) || ! class_exists( 'DOMDocument' ) ) {
			return;
		}

		$previous = libxml_use_internal_errors( true );
		$document = new DOMDocument();
		$document->loadHTML( '<?xml encoding="UTF-8"><div>' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();
		libxml_use_internal_errors( $previous );

		$xpath = new DOMXPath( $document );
		$text  = static function ( ?DOMNode $node ): string {
			return null === $node ? '' : trim( (string) preg_replace( '/\s+/u', ' ', $node->textContent ) );
		};
		$class = static function ( string $name ): string {
			return "contains(concat(' ', normalize-space(@class), ' '), ' {$name} ')";
		};

		$found = array(
			$slug . '_title' => $text( $xpath->query( '//h1' )->item( 0 ) ),
			$slug . '_intro' => $text( $xpath->query( "//p[not(ancestor::*[{$class( 'fs-subject-more-card' )}])]" )->item( 0 ) ),
		);

		foreach ( array_keys( self::CARDS ) as $card ) {
			$node = $xpath->query( "//*[{$class( 'fs-subject-more-card--' . $card )}]" )->item( 0 );

			if ( null === $node ) {
				continue;
			}

			$found[ "{$slug}_{$card}_text" ]   = $text( $xpath->query( ".//*[{$class( 'fs-subject-more-card__text' )}]", $node )->item( 0 ) );
			$found[ "{$slug}_{$card}_button" ] = $text( $xpath->query( ".//*[{$class( 'fs-subject-more-card__button' )}]", $node )->item( 0 ) );
		}

		$stored = get_option( self::OPTION, array() );
		$stored = is_array( $stored ) ? $stored : array();

		foreach ( $found as $key => $value ) {
			if ( '' !== $value && ! array_key_exists( $key, $stored ) ) {
				$stored[ $key ] = $value;
			}
		}

		update_option( self::OPTION, $stored );
		$this->values = null;
	}

	/* --------------------------------------------------------------------
	 * Экран
	 * ------------------------------------------------------------------ */

	public function add_page(): void {
		add_submenu_page(
			FS_LMS_Theme_Showcase::MENU_SLUG,
			__( 'Учебник и тренажёр', 'fs-lms-theme' ),
			__( 'Учебник и тренажёр', 'fs-lms-theme' ),
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
	 * @param mixed $input Данные формы.
	 *
	 * @return array<string, string>
	 */
	public function sanitize( $input ): array {
		$input  = is_array( $input ) ? $input : array();
		$result = array();

		foreach ( array_keys( self::DEFAULTS ) as $key ) {
			$raw = isset( $input[ $key ] ) && is_string( $input[ $key ] ) ? $input[ $key ] : '';

			$result[ $key ] = $this->is_textarea( $key ) ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
		}

		return $result;
	}

	private function is_textarea( string $key ): bool {
		foreach ( self::TEXTAREA_SUFFIXES as $suffix ) {
			if ( substr( $key, -strlen( $suffix ) ) === $suffix ) {
				return true;
			}
		}

		return false;
	}

	/** Сброс значений в памяти и кэша WP Rocket обеих страниц. */
	public function after_update(): void {
		$this->values = null;

		foreach ( array_keys( self::PAGES ) as $slug ) {
			$page = get_page_by_path( $slug );

			if ( $page instanceof WP_Post && function_exists( 'rocket_clean_post' ) ) {
				rocket_clean_post( $page->ID );
			}
		}
	}

	public function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$labels = array(
			'title'      => __( 'Заголовок', 'fs-lms-theme' ),
			'intro'      => __( 'Вводный текст', 'fs-lms-theme' ),
			'ege_text'   => __( 'Карточка ЕГЭ: текст', 'fs-lms-theme' ),
			'ege_button' => __( 'Карточка ЕГЭ: кнопка', 'fs-lms-theme' ),
			'oge_text'   => __( 'Карточка ОГЭ: текст', 'fs-lms-theme' ),
			'oge_button' => __( 'Карточка ОГЭ: кнопка', 'fs-lms-theme' ),
		);
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Учебник и тренажёр', 'fs-lms-theme' ); ?></h1>
			<p><?php esc_html_e( 'Тексты страниц «Учебник» и «Тренажёр» из меню шапки. Названия направлений на карточках берутся из «Направлений».', 'fs-lms-theme' ); ?></p>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php settings_fields( self::GROUP ); ?>
				<?php foreach ( self::PAGES as $slug => $page_title ) : ?>
					<h2 class="title"><?php echo esc_html( $page_title ); ?> <a href="<?php echo esc_url( home_url( '/' . $slug . '/' ) ); ?>" target="_blank" rel="noopener" style="font-size:13px;font-weight:400"><?php esc_html_e( 'открыть страницу', 'fs-lms-theme' ); ?></a></h2>
					<table class="form-table" role="presentation">
						<?php foreach ( $labels as $suffix => $label ) : ?>
							<?php $key = $slug . '_' . $suffix; ?>
							<tr>
								<th scope="row"><label for="<?php echo esc_attr( 'fs-resource-' . $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
								<td>
									<?php if ( $this->is_textarea( $key ) ) : ?>
										<textarea id="<?php echo esc_attr( 'fs-resource-' . $key ); ?>" name="<?php echo esc_attr( self::OPTION . '[' . $key . ']' ); ?>" rows="<?php echo 'intro' === $suffix ? 5 : 3; ?>" class="large-text"><?php echo esc_textarea( $this->get( $key ) ); ?></textarea>
									<?php else : ?>
										<input type="text" id="<?php echo esc_attr( 'fs-resource-' . $key ); ?>" name="<?php echo esc_attr( self::OPTION . '[' . $key . ']' ); ?>" value="<?php echo esc_attr( $this->get( $key ) ); ?>" class="regular-text">
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				<?php endforeach; ?>
				<?php submit_button( __( 'Сохранить тексты', 'fs-lms-theme' ) ); ?>
			</form>
		</div>
		<?php
	}
}
