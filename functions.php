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
 *   - Seo.php      — description/Open Graph, микроразметка организации,
 *                     preconnect к CDN шрифтов (молчит, если стоит SEO-плагин)
 *   - PluginRoutes.php — резолвер URL служебных страниц плагина (Фаза 7)
 *   - SubjectPages.php — автосборка пустых страниц направлений (Фаза 13)
 *   - ResourcePages.php — страницы-хабы «Учебник»/«Тренажёр» (задача 10, tasks.md)
 *   - Navigation.php — меню шапки как объект `wp_navigation` (Фаза 17.4)
 *   - WooCommerce.php — каталог товаров (Фаза 10.3+)
 *   - Checkout.php — корзина/оформление заказа, степпер шагов (Фаза 16.3+)
 *   - SmartCaptcha.php — невидимая Yandex SmartCaptcha лид-форм: ключи,
 *                     контейнер виджета, проверка токена
 *   - SubjectCardIcons.php — иконки карточек «Учебник»/«Тренажёр» при выводе
 *   - ContentUpgrades.php — разовые правки сохранённого контента страниц
 *   - Forms.php    — AJAX-приём лид-форм (`#hero-form`/`#signup`), honeypot +
 *                     HMAC-таймер + rate-limit (Фаза 14)
 *   - Updates.php  — обновление темы из GitHub Releases кнопкой в админке
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

foreach ( array( 'Setup', 'Patterns', 'Assets', 'Blocks', 'Seo', 'PluginRoutes', 'SubjectPages', 'ResourcePages', 'Navigation', 'WooCommerce', 'Checkout', 'SmartCaptcha', 'SubjectCardIcons', 'ContentUpgrades', 'Forms', 'Updates' ) as $module ) {
	require_once get_template_directory() . "/inc/{$module}.php";
}
