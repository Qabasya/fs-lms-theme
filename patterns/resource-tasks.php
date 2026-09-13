<?php
/**
 * Title: Тренажёр — заголовок, вводный текст, карточки ЕГЭ и ОГЭ
 * Slug: fs-lms-theme/resource-tasks
 * Categories: fs-lms-sections
 * Keywords: тренажёр, tasks, trainer, направления
 *
 * Содержимое страницы `/tasks/` (2026-09-13): тексты — «Настройки темы →
 * Учебник и тренажёр» (`inc/Showcase/Resource_Texts.php`), разметка —
 * `fs_lms_theme_trainer_page_blocks()` (`inc/ResourcePages.php`). Страница
 * ссылается на этот паттерн — правка текстов в админке видна сразу.
 */

echo fs_lms_theme_trainer_page_blocks(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- тексты экранируются в fs_lms_theme_resource_page_blocks().
