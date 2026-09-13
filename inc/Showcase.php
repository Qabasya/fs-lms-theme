<?php
/**
 * Содержимое каруселей главной — записями в админке (этап 1, 2026-09-13):
 * «Выпускники» и «Вузы». Общая механика — `inc/Showcase/Showcase_Type.php`.
 *
 * Паттерны берут готовые слайды через `FS_LMS_Theme_Showcase::alumni()` /
 * `::universities()`: один экземпляр на тип, те же объекты, что повесили хуки.
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/Showcase/Showcase_Type.php';
require_once __DIR__ . '/Showcase/Alumni.php';
require_once __DIR__ . '/Showcase/Universities.php';

final class FS_LMS_Theme_Showcase {

	/** @var FS_LMS_Theme_Alumni|null */
	private static $alumni = null;

	/** @var FS_LMS_Theme_Universities|null */
	private static $universities = null;

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
}

FS_LMS_Theme_Showcase::alumni()->register();
FS_LMS_Theme_Showcase::universities()->register();
