<?php
/**
 * Title: FAQ — заголовок + список вопросов
 * Slug: fs-lms-theme/faq
 * Categories: fs-lms-sections
 * Keywords: faq, вопросы, ответы
 *
 * BugFix.6 (2026-09-05): секция приведена к последнему блоку макета
 * `Главная v4 - сборка.dc.html` (Design MCP) и подключена последней секцией
 * главной (`templates/front-page.html`). Изменилось три вещи:
 *
 * 1. Вид — «связанный», как аккордеон страницы «О нас» (по указанию
 *    пользователя): одна рамка со скруглением на весь список, пункты
 *    разделены внутренней чертой. Даёт его класс-модификатор
 *    `.fs-faq-list` на обёртке (`theme.scss`).
 * 2. Ширина — как у остальных секций главной, а не 640px: в макете
 *    аккордеон занимает всю колонку контента.
 * 3. Вопросы и ответы — из макета (5 штук вместо прежних 4).
 *
 * Плавное раскрытие — общее для всех аккордеонов темы, см. BugFix.7
 * в `theme.scss`.
 *
 * Этап 2 (2026-09-13): вопросы — записи «Вопросы» в админке с местом
 * «Главная» (`inc/Showcase/Questions.php`). Разметка пункта — прежняя
 * `fs-lms/faq-item`, только ответ теперь `<div>` с абзацами: в админке
 * ответ может быть в несколько абзацев и со списками. Нет ни одного
 * вопроса — секции нет.
 */

$fs_faq_items = FS_LMS_Theme_Showcase::questions()->faq_markup();

if ( '' === $fs_faq_items ) {
	return;
}
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Частые вопросы</h2>
	<!-- /wp:heading -->

	<!-- wp:group {"className":"fs-faq-list"} -->
	<div class="wp-block-group fs-faq-list">
<?php echo $fs_faq_items; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- пункты собраны с экранированием в FS_LMS_Theme_Questions::faq_markup(). ?>

	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
