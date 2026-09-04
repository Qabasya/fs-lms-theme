<?php
/**
 * Title: Интенсивная подготовка — фото + чек-лист + цена
 * Slug: fs-lms-theme/intensive-split
 * Categories: fs-lms-sections
 * Keywords: интенсив, подготовка, цена
 *
 * Источник: блок «интенсивная подготовка» в «Главная — мокап.dc.html» (1a).
 * Кнопка «Записаться» ведёт на страницу заявки плагина через
 * `fs_lms_theme_url('apply')` (Фаза 7); «Все курсы» — заглушка `#` (см.
 * courses-grid.php).
 *
 * Фаза 11 (`refs/Главная v2 - мокап.dc.html`): мозаика из 5 плейсхолдеров
 * заменена на одно фото `aspect-ratio:4/3` (мозаика не по макету — Фаза 5
 * добавила от себя); маркеры `»` в чек-листе — на иконку галочки в кружке
 * `accent-soft`; две отдельные цветные плашки цены/длительности — на одну
 * карточку с вертикальным разделителем.
 *
 * Фаза 12.7 (`Главная v4 - сборка.dc.html`): заголовок секции — «Как
 * устроены занятия» (было «Интенсивная подготовка», текст макета v4
 * общий для всех направлений, не «интенсив» как отдельная услуга) + новый
 * подзаголовок-пояснение; `id="lessons"` — якорь из `features-grid.php`
 * (12.5) и `header-nav.php`. Пункты 4/5 чек-листа переформулированы под
 * v4. Кнопка «Записаться» — якорь `#signup` (12.8), «Все курсы» — `#dirs`
 * (12.6, секция сменила id с `#courses` на `#dirs`).
 *
 * BugFix.14 (2026-09-03): плейсхолдер «фото занятия» заменён на реальное
 * фото `img/photo.png`, `fs-aspect-4-3` сохранён на `<img>` (тот же
 * класс, что раньше держал соотношение сторон на заглушке).
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div id="lessons" class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"className":"fs-aspect-4-3","style":{"border":{"radius":"var:preset|spacing|md"}}} -->
			<figure class="wp-block-image fs-aspect-4-3" style="border-radius:var(--wp--preset--spacing--md)"><img src="<?php echo esc_url( get_theme_file_uri( 'img/photo.png' ) ); ?>" alt="Фото занятия" /></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"480px"} -->
		<div class="wp-block-column" style="flex-basis:480px">
			<!-- wp:heading {"fontSize":"xxl"} -->
			<h2 class="wp-block-heading has-xxl-font-size">Как устроены занятия</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Формат одинаковый на всех направлениях — меняется только программа.</p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"className":"fs-checklist"} -->
			<div class="wp-block-group fs-checklist">
				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Занятия 2 раза в неделю</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Запись и онлайн-трансляция каждого занятия</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Домашнее задание после каждого занятия</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Индивидуальные консультации с преподавателем</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Материалы, теория и шпаргалки в личном кабинете</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-checklist__item"} -->
				<p class="fs-checklist__item">Дополнительные видеоматериалы по каждой теме</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

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
					<p class="fs-price-plaque__value fs-price-plaque__value--accent">800 ₽</p>
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
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-secondary-color has-white-background-color has-text-color has-background wp-element-button" href="#dirs">Все направления</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
