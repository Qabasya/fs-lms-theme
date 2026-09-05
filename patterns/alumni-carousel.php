<?php
/**
 * Title: Наши выпускники — карусель отзывов
 * Slug: fs-lms-theme/alumni-carousel
 * Categories: fs-lms-sections
 * Keywords: выпускники, отзывы, alumni, карусель
 *
 * Фаза 12.3 (`Главная v4 - сборка.dc.html`): новый блок `fs-lms/alumni-card`
 * (фото + баллы + имя + короткий отзыв) в Splide-карусели —
 * `data-fs-carousel` разметку разбирает `src/js/carousels.js` (Фаза 12.0),
 * 3 слайда десктоп / 2 планшет / 1 мобильный (брейкпоинты заданы в
 * `initCarousels()`), стрелки — стандартные Splide, перекрашены под мокап в
 * `theme.scss`, `mask-image` по краям — класс `.fs-carousel-mask`.
 *
 * BugFix.1 (2026-09-05): фото карточек — `img/alumni.png` из темы вместо
 * заглушки `.fs-placeholder-tile`. Это ЗНАЧЕНИЕ ПО УМОЛЧАНИЮ для новой
 * вставки паттерна: у блока есть свой пикер медиатеки (панель «Фото» в
 * настройках блока и клик по самой картинке), поэтому на уже созданной
 * странице фото меняются в редакторе, без правки кода — см. `edit.js`
 * блока и `src/blocks/shared/ImageControl.js`.
 *
 * BugFix.3 (2026-09-05): у двух последних карточек атрибуты в комментарии
 * блока не совпадали с их же HTML («92 балла / Соколов Артём» против
 * «102 балла / Несоколов Неартём»). Для полей с `"source": "html"`
 * (`block.json`) редактор берёт значение из разметки, а из комментария —
 * только поля без источника, так что расхождение тихо переживало вставку,
 * но при первом же сохранении текст менялся на неожиданный. Атрибуты
 * приведены к содержимому.
 */

$fs_alumni_photo = get_theme_file_uri( 'img/alumni.png' );
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Наши выпускники</h2>
	<!-- /wp:heading -->

	<!-- wp:group {"className":"splide fs-carousel fs-carousel--per-3 fs-carousel-mask"} -->
	<div class="wp-block-group splide fs-carousel fs-carousel--per-3 fs-carousel-mask">
		<!-- wp:group {"className":"splide__track"} -->
		<div class="wp-block-group splide__track">
			<!-- wp:group {"className":"splide__list"} -->
			<div class="wp-block-group splide__list">

	<!-- wp:fs-lms/alumni-card {"imageUrl":"<?php echo esc_url( $fs_alumni_photo ); ?>","imageAlt":"Петрова Мария","scoreText":"98 баллов","authorName":"Петрова Мария","quote":"Пришла с нуля в 10 классе, поступила в ИТМО на бюджет."} -->
	<div class="wp-block-fs-lms-alumni-card fs-alumni-card splide__slide"><div class="fs-alumni-card__media"><img src="<?php echo esc_url( $fs_alumni_photo ); ?>" alt="Петрова Мария"/></div><div class="fs-alumni-card__body"><div class="fs-alumni-card__score">98 баллов</div><div class="fs-alumni-card__name">Петрова Мария</div><p class="fs-alumni-card__quote">Пришла с нуля в 10 классе, поступила в ИТМО на бюджет.</p></div></div>
	<!-- /wp:fs-lms/alumni-card -->

	<!-- wp:fs-lms/alumni-card {"imageUrl":"<?php echo esc_url( $fs_alumni_photo ); ?>","imageAlt":"Иванов Иван","scoreText":"100 баллов","authorName":"Иванов Иван","quote":"Обожаю информатику, поступил в вуз мечты, кайф."} -->
	<div class="wp-block-fs-lms-alumni-card fs-alumni-card splide__slide"><div class="fs-alumni-card__media"><img src="<?php echo esc_url( $fs_alumni_photo ); ?>" alt="Иванов Иван"/></div><div class="fs-alumni-card__body"><div class="fs-alumni-card__score">100 баллов</div><div class="fs-alumni-card__name">Иванов Иван</div><p class="fs-alumni-card__quote">Обожаю информатику, поступил в вуз мечты, кайф.</p></div></div>
	<!-- /wp:fs-lms/alumni-card -->

	<!-- wp:fs-lms/alumni-card {"imageUrl":"<?php echo esc_url( $fs_alumni_photo ); ?>","imageAlt":"Соколов Артём","scoreText":"92 балла","authorName":"Соколов Артём","quote":"Два года робототехники, теперь учусь в МИРЭА."} -->
	<div class="wp-block-fs-lms-alumni-card fs-alumni-card splide__slide"><div class="fs-alumni-card__media"><img src="<?php echo esc_url( $fs_alumni_photo ); ?>" alt="Соколов Артём"/></div><div class="fs-alumni-card__body"><div class="fs-alumni-card__score">92 балла</div><div class="fs-alumni-card__name">Соколов Артём</div><p class="fs-alumni-card__quote">Два года робототехники, теперь учусь в МИРЭА.</p></div></div>
	<!-- /wp:fs-lms/alumni-card -->

	<!-- wp:fs-lms/alumni-card {"imageUrl":"<?php echo esc_url( $fs_alumni_photo ); ?>","imageAlt":"Несоколов Неартём","scoreText":"102 балла","authorName":"Несоколов Неартём","quote":"Поступил в мгу после одной недели в шаге в будущем"} -->
	<div class="wp-block-fs-lms-alumni-card fs-alumni-card splide__slide"><div class="fs-alumni-card__media"><img src="<?php echo esc_url( $fs_alumni_photo ); ?>" alt="Несоколов Неартём"/></div><div class="fs-alumni-card__body"><div class="fs-alumni-card__score">102 балла</div><div class="fs-alumni-card__name">Несоколов Неартём</div><p class="fs-alumni-card__quote">Поступил в мгу после одной недели в шаге в будущем</p></div></div>
	<!-- /wp:fs-lms/alumni-card -->

	<!-- wp:fs-lms/alumni-card {"imageUrl":"<?php echo esc_url( $fs_alumni_photo ); ?>","imageAlt":"Головач Лена","scoreText":"22 балла","authorName":"Головач Лена","quote":"Ходила три года, поступила в МГТУФУ на бюджет"} -->
	<div class="wp-block-fs-lms-alumni-card fs-alumni-card splide__slide"><div class="fs-alumni-card__media"><img src="<?php echo esc_url( $fs_alumni_photo ); ?>" alt="Головач Лена"/></div><div class="fs-alumni-card__body"><div class="fs-alumni-card__score">22 балла</div><div class="fs-alumni-card__name">Головач Лена</div><p class="fs-alumni-card__quote">Ходила три года, поступила в МГТУФУ на бюджет</p></div></div>
	<!-- /wp:fs-lms/alumni-card -->

			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
