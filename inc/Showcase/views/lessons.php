<?php
/**
 * Секция «Как устроены занятия» — общая разметка для главной и страниц
 * направлений (2026-09-13). Блоки те же, что стояли в
 * `patterns/intensive-split.php`: слева фото 4 : 3, справа заголовок,
 * подзаголовок, чек-лист, плашка длительности и цены, кнопки.
 *
 * Значения плашки — прежние по умолчанию; фильтр «Настроек сайта»
 * (`FS_LMS_Theme_Site_Settings::fill_lesson_plaque()`) подставляет в них
 * значения из настроек при выводе.
 *
 * Подключается из `FS_LMS_Theme_Lessons::section_markup()`.
 *
 * @var array{heading: string, lead: string, items: string[], image: string} $lessons Данные варианта.
 * @var bool                                                                  $accent  Акцентный цвет цены (страницы направлений).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fs_price_class = 'fs-price-plaque__value' . ( $accent ? ' fs-price-plaque__value--accent' : '' );
?>
<!-- wp:group {"className":"fs-section"} -->
<div id="lessons" class="wp-block-group fs-section">
	<!-- wp:columns {"className":"fs-lessons-columns","style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns fs-lessons-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"className":"fs-aspect-4-3","style":{"border":{"radius":"var:preset|spacing|md"}}} -->
			<figure class="wp-block-image fs-aspect-4-3" style="border-radius:var(--wp--preset--spacing--md)"><?php echo $lessons['image']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() или img с esc_url/esc_attr. ?></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"480px"} -->
		<div class="wp-block-column" style="flex-basis:480px">
			<!-- wp:heading {"fontSize":"xxl"} -->
			<h2 class="wp-block-heading has-xxl-font-size"><?php echo esc_html( $lessons['heading'] ); ?></h2>
			<!-- /wp:heading -->
<?php if ( '' !== $lessons['lead'] ) : ?>

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"base"} -->
			<p class="has-text-secondary-color has-text-color has-base-font-size"><?php echo nl2br( esc_html( $lessons['lead'] ) ); ?></p>
			<!-- /wp:paragraph -->
<?php endif; ?>
<?php if ( array() !== $lessons['items'] ) : ?>

			<!-- wp:group {"className":"fs-checklist"} -->
			<div class="wp-block-group fs-checklist">
<?php foreach ( $lessons['items'] as $fs_item ) : ?>
				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item"><?php echo esc_html( $fs_item ); ?></p>
				<!-- /wp:paragraph -->

<?php endforeach; ?>
			</div>
			<!-- /wp:group -->
<?php endif; ?>

			<!-- wp:group {"className":"fs-price-plaque"} -->
			<div class="wp-block-group fs-price-plaque">
				<!-- wp:group {"className":"fs-price-plaque__part"} -->
				<div class="wp-block-group fs-price-plaque__part">
					<!-- wp:paragraph {"className":"fs-price-plaque__value"} -->
					<p class="fs-price-plaque__value">2 часа</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-price-plaque__label"} -->
					<p class="fs-price-plaque__label">одно занятие</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"fs-price-plaque__part"} -->
				<div class="wp-block-group fs-price-plaque__part">
					<!-- wp:paragraph {"className":"fs-price-plaque__value fs-price-plaque__value--accent"} -->
					<p class="<?php echo esc_attr( $fs_price_class ); ?>">800 ₽</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-price-plaque__label"} -->
					<p class="fs-price-plaque__label">за час</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"accent-2"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-accent-2-background-color has-background wp-element-button" href="#signup">Записаться</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"backgroundColor":"white","textColor":"text-secondary","className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-secondary-color has-white-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url( home_url( '/courses/' ) ); ?>">Все направления</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
