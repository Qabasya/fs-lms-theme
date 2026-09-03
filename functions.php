<?php
/**
 * FS LMS Theme — bootstrap.
 *
 * Лёгкая блочная (FSE) тема под fs-lms: вся структура и токены — в
 * theme.json, PHP-слой только подключает inc/-модули. Каждый модуль — одна
 * зона ответственности (см. tasks.md):
 *   - Setup.php    — поддержка темы (theme supports), стили блоков дизайн-системы
 *   - Patterns.php — категории паттернов
 *   - Assets.php   — шрифты и собранные CSS/JS (assets/, см. gulpfile.js)
 *   - Blocks.php   — регистрация кастомных блоков (src/blocks/*, Фаза 4)
 *   - PluginRoutes.php — резолвер URL служебных страниц плагина (Фаза 7)
 *   - WooCommerce.php — каталог товаров (Фаза 10.3+)
 *   - Forms.php    — AJAX-приём лид-форм (`#hero-form`/`#signup`), honeypot +
 *                     HMAC-таймер + Yandex SmartCaptcha + rate-limit (Фаза 14)
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

foreach ( array( 'Setup', 'Patterns', 'Assets', 'Blocks', 'PluginRoutes', 'WooCommerce', 'Forms' ) as $module ) {
	require_once get_template_directory() . "/inc/{$module}.php";
}
