<?php
/**
 * Title: Учебник — заголовок, вводный текст, карточки ЕГЭ и ОГЭ
 * Slug: fs-lms-theme/resource-articles
 * Categories: fs-lms-sections
 * Keywords: учебник, articles, направления
 *
 * Содержимое страницы `/articles/` (2026-09-13): тексты — «Настройки темы →
 * Учебник и тренажёр» (`inc/Showcase/Resource_Texts.php`), разметка —
 * `fs_lms_theme_articles_page_blocks()` (`inc/ResourcePages.php`). Страница
 * ссылается на этот паттерн — правка текстов в админке видна сразу.
 */

echo fs_lms_theme_articles_page_blocks(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- тексты экранируются в fs_lms_theme_resource_page_blocks().
