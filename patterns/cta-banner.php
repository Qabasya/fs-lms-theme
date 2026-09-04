<?php
/**
 * Title: CTA — секция-обёртка вокруг баннера призыва к действию
 * Slug: fs-lms-theme/cta-banner
 * Categories: fs-lms-sections
 * Keywords: cta, призыв, баннер
 *
 * Не привязано к конкретному блоку мокапа главной (в «Главная — мокап.dc.html»
 * такой отдельной секции нет — там призыв к действию встроен в форму
 * контактов, см. `contact-section.php`) — паттерн из библиотеки для
 * страниц, где нужен самостоятельный CTA-блок. Кнопка «Записаться» ведёт на
 * страницу заявки плагина через `fs_lms_theme_url('apply')` (Фаза 7);
 * «Все курсы» — заглушка `#` (см. courses-grid.php).
 */
?>
<!-- wp:group {"className":"fs-section","style":{"spacing":{"padding":{"left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
<div class="wp-block-group fs-section" style="padding-right:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
	<!-- wp:fs-lms/cta-banner {"heading":"Приготовься сделать Шаг в будущее","text":"Заполни форму обратной связи и мы ответим на все интересующие вопросы","primaryText":"Записаться","primaryUrl":"<?php echo esc_js( fs_lms_theme_url( 'apply' ) ); ?>","secondaryText":"Все курсы","secondaryUrl":"#","variant":"solid"} -->
	<div class="wp-block-fs-lms-cta-banner fs-cta-banner is-solid"><h2 class="fs-cta-banner__heading">Приготовься сделать Шаг в будущее</h2><p class="fs-cta-banner__text">Заполни форму обратной связи и мы ответим на все интересующие вопросы</p><div class="fs-cta-banner__actions"><a class="fs-cta-banner__button fs-cta-banner__button--primary" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться</a><a class="fs-cta-banner__button fs-cta-banner__button--secondary" href="#">Все курсы</a></div></div>
	<!-- /wp:fs-lms/cta-banner -->
</div>
<!-- /wp:group -->
