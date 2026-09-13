<?php
/**
 * Title: Hero страницы направления — Робототехника
 * Slug: fs-lms-theme/subject-hero-robo
 * Categories: fs-lms-sections
 * Keywords: hero, направление, робототехника, предмет, форма
 *
 * Копия `patterns/subject-hero.php` (ЕГЭ-вариант, Фаза 13.0) под
 * направление «Робототехника» — тот же визуальный шаблон, свой
 * бейдж/заголовок/описание и модификатор цвета `--robo` (BugFix.7,
 * сопоставление цвет↔направление — то же, что бейджи `courses-grid.php`,
 * BugFix.6: Робототехника = фиолетовый). Текст — черновик, сгенерирован
 * по образцу остальных описаний направлений в теме (`courses-grid.php`),
 * редактор правит вручную после вставки на страницу предмета (`robo`).
 *
 * Правая колонка (мини-форма `#hero-form` + плашка из 3 статов) — тот же
 * `wp:html`, что и в `hero.php`/`subject-hero.php`, без изменений.
 *
 * Этап 3 (2026-09-13): разметка первого экрана — общая для всех направлений
 * (`inc/Showcase/views/subject-hero.php`), данные — запись «Направления» с
 * ключом предмета `robo` (`inc/Showcase/Directions.php`): класс, заголовок,
 * текст, цвет плашки и факты под формой. Нет записи — стартовые данные темы.
 * Файл паттерна остаётся: страницы направлений ссылаются на него по слагу.
 */

echo FS_LMS_Theme_Showcase::directions()->subject_hero_markup( 'robo' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- экранирование в inc/Showcase/views/subject-hero.php.
