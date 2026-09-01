<?php
/**
 * Title: Интенсивная подготовка — сетка миниатюр + список + цена
 * Slug: fs-lms-theme/intensive-split
 * Categories: fs-lms-sections
 * Keywords: интенсив, подготовка, цена
 *
 * Источник: блок «интенсивная подготовка» в «Главная — мокап.dc.html» (1a).
 * Левая сетка миниатюр — декоративные плейсхолдеры (`fs-placeholder-tile`),
 * без реальных фото на первом проходе. Кнопка «Записаться» ведёт на
 * страницу заявки плагина через `fs_lms_theme_url('apply')` (Фаза 7);
 * «Все курсы» — заглушка `#` (см. courses-grid.php).
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"var:preset|spacing|xxxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxxl);padding-left:var(--wp--preset--spacing--xxl)">
	<!-- wp:group {"className":"is-style-card","style":{"border":{"radius":"var:preset|spacing|md"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group is-style-card" style="border-radius:var(--wp--preset--spacing--md)">
		<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"0"}}}} -->
		<div class="wp-block-columns">
			<!-- wp:column {"backgroundColor":"surface-2","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl","left":"var:preset|spacing|xl","right":"var:preset|spacing|xl"}},"border":{"right":{"color":"var:preset|color|border-light","width":"1px"}}}} -->
			<div class="wp-block-column has-surface-2-background-color has-background" style="border-right-color:var(--wp--preset--color--border-light);border-right-width:1px;padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--xl);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--xl)">
				<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|sm","top":"var:preset|spacing|sm"}}}} -->
				<div class="wp-block-columns">
					<!-- wp:column -->
					<div class="wp-block-column">
						<!-- wp:group {"className":"fs-placeholder-tile","style":{"dimensions":{"minHeight":"112px"},"border":{"radius":"var:preset|spacing|sm"}}} -->
						<div class="wp-block-group fs-placeholder-tile" style="border-radius:var(--wp--preset--spacing--sm);min-height:112px"></div>
						<!-- /wp:group -->
					</div>
					<!-- /wp:column -->

					<!-- wp:column -->
					<div class="wp-block-column">
						<!-- wp:group {"className":"fs-placeholder-tile","style":{"dimensions":{"minHeight":"234px"},"border":{"radius":"var:preset|spacing|sm"}}} -->
						<div class="wp-block-group fs-placeholder-tile" style="border-radius:var(--wp--preset--spacing--sm);min-height:234px"></div>
						<!-- /wp:group -->
					</div>
					<!-- /wp:column -->

					<!-- wp:column -->
					<div class="wp-block-column">
						<!-- wp:group {"className":"fs-placeholder-tile","style":{"dimensions":{"minHeight":"112px"},"border":{"radius":"var:preset|spacing|sm"}}} -->
						<div class="wp-block-group fs-placeholder-tile" style="border-radius:var(--wp--preset--spacing--sm);min-height:112px"></div>
						<!-- /wp:group -->
					</div>
					<!-- /wp:column -->
				</div>
				<!-- /wp:columns -->

				<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|sm","top":"var:preset|spacing|sm"}}}} -->
				<div class="wp-block-columns">
					<!-- wp:column -->
					<div class="wp-block-column">
						<!-- wp:group {"className":"fs-placeholder-tile","style":{"dimensions":{"minHeight":"112px"},"border":{"radius":"var:preset|spacing|sm"}}} -->
						<div class="wp-block-group fs-placeholder-tile" style="border-radius:var(--wp--preset--spacing--sm);min-height:112px"></div>
						<!-- /wp:group -->
					</div>
					<!-- /wp:column -->

					<!-- wp:column {"width":"66.66%"} -->
					<div class="wp-block-column" style="flex-basis:66.66%">
						<!-- wp:group {"className":"fs-placeholder-tile","style":{"dimensions":{"minHeight":"112px"},"border":{"radius":"var:preset|spacing|sm"}}} -->
						<div class="wp-block-group fs-placeholder-tile" style="border-radius:var(--wp--preset--spacing--sm);min-height:112px"></div>
						<!-- /wp:group -->
					</div>
					<!-- /wp:column -->
				</div>
				<!-- /wp:columns -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"style":{"spacing":{"padding":{"top":"var:preset|spacing|xxl","bottom":"var:preset|spacing|xxl","left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
			<div class="wp-block-column" style="padding-top:var(--wp--preset--spacing--xxl);padding-right:var(--wp--preset--spacing--xxl);padding-bottom:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
				<!-- wp:heading {"fontSize":"xxl"} -->
				<h2 class="wp-block-heading has-xxl-font-size">Интенсивная подготовка</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
				<p class="has-text-secondary-color has-text-color has-sm-font-size">» Занятия 2 раза в неделю</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
				<p class="has-text-secondary-color has-text-color has-sm-font-size">» Запись и онлайн трансляция каждого занятия</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
				<p class="has-text-secondary-color has-text-color has-sm-font-size">» Домашнее задание после каждого занятия</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
				<p class="has-text-secondary-color has-text-color has-sm-font-size">» Индивидуальные консультации с репетитором</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
				<p class="has-text-secondary-color has-text-color has-sm-font-size">» Дополнительные материалы, теория и шпаргалки</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
				<p class="has-text-secondary-color has-text-color has-sm-font-size">» Дополнительные видеоматериалы по каждой теме</p>
				<!-- /wp:paragraph -->

				<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|md"}}}} -->
				<div class="wp-block-columns">
					<!-- wp:column {"backgroundColor":"surface-2","style":{"spacing":{"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md","left":"var:preset|spacing|lg","right":"var:preset|spacing|lg"}},"border":{"radius":"var:preset|spacing|sm","color":"var:preset|color|border-light","width":"1px"}}} -->
					<div class="wp-block-column has-surface-2-background-color has-background" style="border-color:var(--wp--preset--color--border-light);border-width:1px;border-radius:var(--wp--preset--spacing--sm);padding-top:var(--wp--preset--spacing--md);padding-right:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--md);padding-left:var(--wp--preset--spacing--lg)">
						<!-- wp:paragraph {"fontSize":"xl","style":{"typography":{"fontWeight":"700"}}} -->
						<p class="has-xl-font-size" style="font-weight:700">2 часа</p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph {"textColor":"muted","fontSize":"sm"} -->
						<p class="has-muted-color has-text-color has-sm-font-size">занятие</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:column -->

					<!-- wp:column {"backgroundColor":"accent-soft","style":{"spacing":{"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md","left":"var:preset|spacing|lg","right":"var:preset|spacing|lg"}},"border":{"radius":"var:preset|spacing|sm"}}} -->
					<div class="wp-block-column has-accent-soft-background-color has-background" style="border-radius:var(--wp--preset--spacing--sm);padding-top:var(--wp--preset--spacing--md);padding-right:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--md);padding-left:var(--wp--preset--spacing--lg)">
						<!-- wp:paragraph {"textColor":"accent-700","fontSize":"xl","style":{"typography":{"fontWeight":"700"}}} -->
						<p class="has-accent-700-color has-text-color has-xl-font-size" style="font-weight:700">800 ₽</p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph {"textColor":"accent-700","fontSize":"sm"} -->
						<p class="has-accent-700-color has-text-color has-sm-font-size">час</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:column -->
				</div>
				<!-- /wp:columns -->

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"backgroundColor":"accent"} -->
					<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться</a></div>
					<!-- /wp:button -->

					<!-- wp:button {"backgroundColor":"white","textColor":"text-secondary","className":"is-style-outline"} -->
					<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-secondary-color has-white-background-color has-text-color has-background wp-element-button" href="#">Все курсы</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
