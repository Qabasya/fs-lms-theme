<?php
/**
 * Title: Контакты — текст + витрина заявки на пробное занятие
 * Slug: fs-lms-theme/contact-section
 * Categories: fs-lms-sections
 * Keywords: контакты, заявка, форма, contact
 *
 * Источник: блок «форма» в «Главная — мокап.dc.html» (1a). Правая колонка —
 * НЕ форма с реальной отправкой (это зона плагина, см. tasks.md Фаза 0):
 * статичная витрина полей для вида + кнопка-ссылка на страницу заявки
 * плагина (`ApplyPageController`), реальный маршрут — `fs_lms_theme_url('apply')`
 * (Фаза 7, см. inc/PluginRoutes.php).
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"var:preset|spacing|xxxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxxl);padding-left:var(--wp--preset--spacing--xxl)">
	<!-- wp:group {"className":"is-style-card","backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|xxl","bottom":"var:preset|spacing|xxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group is-style-card has-white-background-color has-background" style="padding-top:var(--wp--preset--spacing--xxl);padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
		<!-- wp:columns -->
		<div class="wp-block-columns">
			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:heading {"fontSize":"xxl"} -->
				<h2 class="wp-block-heading has-xxl-font-size">Приготовься сделать<br>Шаг в будущее</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
				<p class="has-text-secondary-color has-text-color has-sm-font-size">Заполни форму обратной связи и мы ответим на все интересующие вопросы</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"base"} -->
				<p class="has-text-secondary-color has-text-color has-base-font-size">» +7 995 326 44 86</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"base"} -->
				<p class="has-text-secondary-color has-text-color has-base-font-size">» info@future-step.ru</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"base"} -->
				<p class="has-text-secondary-color has-text-color has-base-font-size">» Калининград, центр города</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"backgroundColor":"surface-2","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|xl","right":"var:preset|spacing|xl"}},"border":{"radius":"var:preset|spacing|md"}}} -->
			<div class="wp-block-column has-surface-2-background-color has-background" style="border-radius:var(--wp--preset--spacing--md);padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--xl);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--xl)">
				<!-- wp:paragraph {"align":"center","fontSize":"lead"} -->
				<p class="has-text-align-center has-lead-font-size">Записаться на пробное занятие</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"muted","fontSize":"xs"} -->
				<p class="has-muted-color has-text-color has-xs-font-size">Имя родителя, телефон, класс и направление — реальная форма заявки находится на странице записи, кнопка ниже ведёт туда.</p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
				<div class="wp-block-buttons">
					<!-- wp:button {"backgroundColor":"accent","width":100} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться на пробное занятие</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

				<!-- wp:paragraph {"align":"center","textColor":"muted-2","fontSize":"xxs"} -->
				<p class="has-text-align-center has-muted-2-color has-text-color has-xxs-font-size">Перезвоним в течение рабочего дня</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
