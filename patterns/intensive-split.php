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
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div id="lessons" class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-placeholder-tile fs-aspect-4-3","style":{"border":{"radius":"var:preset|spacing|md","color":"var:preset|color|border","width":"1px"}},"layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center"}} -->
			<div class="wp-block-group fs-placeholder-tile fs-aspect-4-3" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:var(--wp--preset--spacing--md)">
				<!-- wp:paragraph {"textColor":"muted-2","fontSize":"xs"} -->
				<p class="has-muted-2-color has-text-color has-xs-font-size">фото занятия</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
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

			<!-- wp:html -->
			<div class="fs-checklist">
				<div class="fs-checklist__item"><span class="fs-checklist__icon"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="10" cy="10" r="8" fill="#edf0fe"/><path d="m6.5 10.2 2.4 2.3 4.6-4.8" stroke="#3b5bdb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span>Занятия 2 раза в неделю</span></div>
				<div class="fs-checklist__item"><span class="fs-checklist__icon"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="10" cy="10" r="8" fill="#edf0fe"/><path d="m6.5 10.2 2.4 2.3 4.6-4.8" stroke="#3b5bdb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span>Запись и онлайн-трансляция каждого занятия</span></div>
				<div class="fs-checklist__item"><span class="fs-checklist__icon"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="10" cy="10" r="8" fill="#edf0fe"/><path d="m6.5 10.2 2.4 2.3 4.6-4.8" stroke="#3b5bdb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span>Домашнее задание после каждого занятия</span></div>
				<div class="fs-checklist__item"><span class="fs-checklist__icon"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="10" cy="10" r="8" fill="#edf0fe"/><path d="m6.5 10.2 2.4 2.3 4.6-4.8" stroke="#3b5bdb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span>Индивидуальные консультации с преподавателем</span></div>
				<div class="fs-checklist__item"><span class="fs-checklist__icon"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="10" cy="10" r="8" fill="#edf0fe"/><path d="m6.5 10.2 2.4 2.3 4.6-4.8" stroke="#3b5bdb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span>Материалы, теория и шпаргалки в личном кабинете</span></div>
				<div class="fs-checklist__item"><span class="fs-checklist__icon"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="10" cy="10" r="8" fill="#edf0fe"/><path d="m6.5 10.2 2.4 2.3 4.6-4.8" stroke="#3b5bdb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span>Дополнительные видеоматериалы по каждой теме</span></div>
			</div>
			<!-- /wp:html -->

			<!-- wp:html -->
			<div class="fs-price-plaque">
				<div><div class="fs-price-plaque__value">2 часа</div><div class="fs-price-plaque__label">одно занятие</div></div>
				<div class="fs-price-plaque__divider"></div>
				<div><div class="fs-price-plaque__value fs-price-plaque__value--accent">800 ₽</div><div class="fs-price-plaque__label">за час</div></div>
			</div>
			<!-- /wp:html -->

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
