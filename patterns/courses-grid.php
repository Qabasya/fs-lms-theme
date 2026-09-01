<?php
/**
 * Title: Курсы и направления — заголовок + 3 карточки курсов
 * Slug: fs-lms-theme/courses-grid
 * Categories: fs-lms-sections
 * Keywords: курсы, направления, courses
 *
 * Источник: блок «направления» в «Главная — мокап.dc.html» (1a). Кнопки
 * «Записаться» ведут на страницу заявки плагина через
 * `fs_lms_theme_url('apply')` (Фаза 7); ссылка «Все услуги» — заглушка `#`
 * (страница каталога курсов темы вне рамок текущего плана, см. tasks.md).
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"var:preset|spacing|xxxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxxl);padding-left:var(--wp--preset--spacing--xxl)">
	<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"fontSize":"xxl"} -->
		<h2 class="wp-block-heading has-xxl-font-size">Курсы и направления</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"textColor":"accent","fontSize":"sm","style":{"typography":{"fontWeight":"600"}}} -->
		<p class="has-accent-color has-text-color has-sm-font-size" style="font-weight:600"><a href="#">Все услуги →</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:fs-lms/course-card {"badgeText":"9 класс","badgeColor":"accent","title":"ОГЭ по информатике","caption":"Абонемент на месяц, два занятия в неделю","buttonText":"Записаться","buttonUrl":"<?php echo esc_js( fs_lms_theme_url( 'apply' ) ); ?>"} -->
			<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media fs-placeholder-tile"></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-accent-700-color has-accent-soft-background-color has-text-color has-background">9 класс</span><h3 class="fs-course-card__title">ОГЭ по информатике</h3><p class="fs-course-card__caption">Абонемент на месяц, два занятия в неделю</p><a class="fs-course-card__button wp-element-button" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться</a></div></div>
			<!-- /wp:fs-lms/course-card -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:fs-lms/course-card {"badgeText":"10 класс","badgeColor":"ok","title":"Python 10 класс","caption":"Абонемент на месяц, два занятия в неделю","buttonText":"Записаться","buttonUrl":"<?php echo esc_js( fs_lms_theme_url( 'apply' ) ); ?>"} -->
			<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media fs-placeholder-tile"></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-ok-color has-ok-soft-background-color has-text-color has-background">10 класс</span><h3 class="fs-course-card__title">Python 10 класс</h3><p class="fs-course-card__caption">Абонемент на месяц, два занятия в неделю</p><a class="fs-course-card__button wp-element-button" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться</a></div></div>
			<!-- /wp:fs-lms/course-card -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:fs-lms/course-card {"badgeText":"11 класс","badgeColor":"wait","title":"ЕГЭ по информатике","caption":"Абонемент на месяц, два занятия в неделю","buttonText":"Записаться","buttonUrl":"<?php echo esc_js( fs_lms_theme_url( 'apply' ) ); ?>"} -->
			<div class="wp-block-fs-lms-course-card fs-course-card"><div class="fs-course-card__media fs-placeholder-tile"></div><div class="fs-course-card__body"><span class="fs-course-card__badge has-wait-color has-wait-soft-background-color has-text-color has-background">11 класс</span><h3 class="fs-course-card__title">ЕГЭ по информатике</h3><p class="fs-course-card__caption">Абонемент на месяц, два занятия в неделю</p><a class="fs-course-card__button wp-element-button" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться</a></div></div>
			<!-- /wp:fs-lms/course-card -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
