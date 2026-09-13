<?php
/**
 * Первый экран страницы направления — общая разметка четырёх паттернов
 * `patterns/subject-hero*.php` (этап 3, 2026-09-13). Блоки те же, что стояли
 * в паттернах: слева плашка `.fs-subject-hero-box--{цвет}` с классом,
 * заголовком и абзацами, справа мини-форма `#hero-form` и факты.
 *
 * Подключается из `FS_LMS_Theme_Directions::subject_hero_markup()`.
 *
 * @var array<string, mixed>        $direction     Данные направления.
 * @var string                      $hero_modifier Модификатор цвета плашки.
 * @var string[]                    $paragraphs    Абзацы текста.
 * @var array<int, array{0: string, 1: string}> $stats Факты: значение, подпись.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fs_box_class = 'fs-subject-hero-box fs-subject-hero-box--' . $hero_modifier;
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:columns {"className":"fs-hero-columns","style":{"spacing":{"blockGap":{"left":"2.5rem"}}}} -->
	<div class="wp-block-columns fs-hero-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"<?php echo esc_attr( $fs_box_class ); ?>","layout":{"type":"constrained"}} -->
			<div class="wp-block-group <?php echo esc_attr( $fs_box_class ); ?>">
				<!-- wp:paragraph {"className":"fs-subject-hero-box__badge"} -->
				<p class="fs-subject-hero-box__badge"><?php echo esc_html( $direction['grade_label'] ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":1,"fontSize":"xxl"} -->
				<h1 class="wp-block-heading has-xxl-font-size"><?php echo esc_html( $direction['title'] ); ?></h1>
				<!-- /wp:heading -->
<?php foreach ( $paragraphs as $fs_paragraph ) : ?>

				<!-- wp:paragraph {"fontSize":"lead"} -->
				<p class="has-lead-font-size"><?php echo nl2br( esc_html( $fs_paragraph ) ); ?></p>
				<!-- /wp:paragraph -->
<?php endforeach; ?>
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:html -->
			<form id="hero-form" class="fs-hero-form" data-fs-form>
				<div class="fs-hero-form__title">Запишитесь на пробное занятие</div>
				<div class="fs-hero-form__row">
					<div class="fs-form-field">
						<label for="fs-subject-hero-name">ФИО родителя</label>
						<input type="text" id="fs-subject-hero-name" name="parent_name" placeholder="Иванова Анна Ивановна" autocomplete="name" <?php echo fs_lms_theme_name_field_attrs_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- атрибуты экранированы внутри. ?> required>
					</div>
					<div class="fs-form-field">
						<label for="fs-subject-hero-phone">Телефон</label>
						<input type="tel" id="fs-subject-hero-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" required>
					</div>
				</div>
				<input type="hidden" name="form_id" value="hero">
				<input type="hidden" name="fs_form_token" value="<?php echo esc_attr( fs_lms_theme_form_timestamp_token() ); ?>">
				<input type="text" name="<?php echo esc_attr( fs_lms_theme_honeypot_field() ); ?>" class="fs-form-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
				<?php echo fs_lms_theme_captcha_slot_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- разметка виджета капчи. ?>
				<button type="submit" class="fs-hero-form__submit">Отправить</button>
				<div class="fs-hero-form__note"><?php echo fs_lms_theme_form_consent_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ссылка собрана с esc_url. ?></div>
				<div class="fs-form-message" role="status"></div>
			</form>
			<!-- /wp:html -->
<?php if ( array() !== $stats ) : ?>

			<!-- wp:group {"className":"fs-hero-stats"} -->
			<div class="wp-block-group fs-hero-stats">
<?php foreach ( $stats as $fs_stat ) : ?>
				<!-- wp:group {"className":"fs-hero-stats__item"} -->
				<div class="wp-block-group fs-hero-stats__item">
					<!-- wp:paragraph {"className":"fs-hero-stats__value"} -->
					<p class="fs-hero-stats__value"><?php echo esc_html( $fs_stat[0] ); ?></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"className":"fs-hero-stats__label"} -->
					<p class="fs-hero-stats__label"><?php echo esc_html( $fs_stat[1] ); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
<?php endforeach; ?>
			</div>
			<!-- /wp:group -->
<?php endif; ?>
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
