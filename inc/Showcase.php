<?php
/**
 * Контент, который редактируется в админке, а выводится разметкой темы (план
 * редактирования, 2026-09-13). Всё собрано в один пункт меню «Настройки темы»
 * (`MENU_SLUG`):
 * - «Настройки сайта» — контакты, ссылки, цены и факты (`Site_Settings`, экран настроек);
 * - «Направления» — список и карточки на главной, каталог «Курсы», первый экран страниц направлений;
 * - «Как устроены занятия» — пять вариантов секции: главная и страницы направлений (`Lessons`);
 * - «Вопросы» — FAQ главной и аккордеон «О нас»;
 * - «Выпускники» и «Вузы» — карусели главной;
 * - «Учебник и тренажёр» — тексты страниц `/articles/` и `/tasks/` (`Resource_Texts`, экран настроек).
 *
 * Общая механика типов записей — `inc/Showcase/Content_Type.php`, изображение
 * записи — трейт `inc/Showcase/Featured_Image.php`, карусели —
 * `inc/Showcase/Showcase_Type.php`.
 *
 * Паттерны берут готовую разметку через `FS_LMS_Theme_Showcase::…()`: один
 * экземпляр на раздел, те же объекты, что повесили хуки.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/Showcase/Content_Type.php';
require_once __DIR__ . '/Showcase/Featured_Image.php';
require_once __DIR__ . '/Showcase/Showcase_Type.php';
require_once __DIR__ . '/Showcase/Alumni.php';
require_once __DIR__ . '/Showcase/Universities.php';
require_once __DIR__ . '/Showcase/Questions.php';
require_once __DIR__ . '/Showcase/Directions.php';
require_once __DIR__ . '/Showcase/Lessons.php';
require_once __DIR__ . '/Showcase/Site_Settings.php';
require_once __DIR__ . '/Showcase/Resource_Texts.php';

final class FS_LMS_Theme_Showcase {

	/**
	 * Слаг общего пункта меню «Настройки темы» — он же экран «Настройки
	 * сайта», первый подпункт. Остальные разделы — подпункты под ним.
	 */
	public const MENU_SLUG = 'fs-lms-theme-site-settings';

	/** @var array<string, object> Экземпляры разделов по имени метода. */
	private static $instances = array();

	/**
	 * Один экземпляр класса на раздел.
	 *
	 * @template T of object
	 * @param class-string<T> $class_name Класс раздела.
	 *
	 * @return T
	 */
	private static function instance( string $class_name ) {
		if ( ! isset( self::$instances[ $class_name ] ) ) {
			self::$instances[ $class_name ] = new $class_name();
		}

		return self::$instances[ $class_name ];
	}

	public static function settings(): FS_LMS_Theme_Site_Settings {
		return self::instance( FS_LMS_Theme_Site_Settings::class );
	}

	public static function directions(): FS_LMS_Theme_Directions {
		return self::instance( FS_LMS_Theme_Directions::class );
	}

	public static function lessons(): FS_LMS_Theme_Lessons {
		return self::instance( FS_LMS_Theme_Lessons::class );
	}

	public static function questions(): FS_LMS_Theme_Questions {
		return self::instance( FS_LMS_Theme_Questions::class );
	}

	public static function alumni(): FS_LMS_Theme_Alumni {
		return self::instance( FS_LMS_Theme_Alumni::class );
	}

	public static function universities(): FS_LMS_Theme_Universities {
		return self::instance( FS_LMS_Theme_Universities::class );
	}

	public static function resource_texts(): FS_LMS_Theme_Resource_Texts {
		return self::instance( FS_LMS_Theme_Resource_Texts::class );
	}
}

// Порядок регистрации типов записей = порядок подпунктов в «Настройках темы»
// (ядро добавляет их в порядке регистрации); «Настройки сайта» — первым
// (`admin_menu`, приоритет 9), «Учебник и тренажёр» — последним (приоритет 11).
FS_LMS_Theme_Showcase::settings()->register();
FS_LMS_Theme_Showcase::directions()->register();
FS_LMS_Theme_Showcase::lessons()->register();
FS_LMS_Theme_Showcase::questions()->register();
FS_LMS_Theme_Showcase::alumni()->register();
FS_LMS_Theme_Showcase::universities()->register();
FS_LMS_Theme_Showcase::resource_texts()->register();
