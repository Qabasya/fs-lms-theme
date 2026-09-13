<?php
/**
 * Контент, который редактируется записями в админке, а выводится разметкой
 * темы (план редактирования, 2026-09-13):
 * - этап 1 — «Выпускники» и «Вузы» (карусели главной);
 * - этап 2 — «Вопросы» (FAQ главной и аккордеон «О нас»).
 * Общая механика — `inc/Showcase/Content_Type.php`, карусели с картинками —
 * `inc/Showcase/Showcase_Type.php`.
 *
 * Паттерны берут готовую разметку через `FS_LMS_Theme_Showcase::alumni()` /
 * `::universities()` / `::questions()`: один экземпляр на тип, те же
 * объекты, что повесили хуки.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/Showcase/Content_Type.php';
require_once __DIR__ . '/Showcase/Showcase_Type.php';
require_once __DIR__ . '/Showcase/Alumni.php';
require_once __DIR__ . '/Showcase/Universities.php';
require_once __DIR__ . '/Showcase/Questions.php';

final class FS_LMS_Theme_Showcase {

	/** @var FS_LMS_Theme_Alumni|null */
	private static $alumni = null;

	/** @var FS_LMS_Theme_Universities|null */
	private static $universities = null;

	/** @var FS_LMS_Theme_Questions|null */
	private static $questions = null;

	public static function alumni(): FS_LMS_Theme_Alumni {
		if ( null === self::$alumni ) {
			self::$alumni = new FS_LMS_Theme_Alumni();
		}

		return self::$alumni;
	}

	public static function universities(): FS_LMS_Theme_Universities {
		if ( null === self::$universities ) {
			self::$universities = new FS_LMS_Theme_Universities();
		}

		return self::$universities;
	}

	public static function questions(): FS_LMS_Theme_Questions {
		if ( null === self::$questions ) {
			self::$questions = new FS_LMS_Theme_Questions();
		}

		return self::$questions;
	}
}

FS_LMS_Theme_Showcase::alumni()->register();
FS_LMS_Theme_Showcase::universities()->register();
FS_LMS_Theme_Showcase::questions()->register();
