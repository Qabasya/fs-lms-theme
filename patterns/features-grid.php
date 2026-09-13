<?php
/**
 * Title: Сделаем вместе — заголовок + сетка преимуществ
 * Slug: fs-lms-theme/features-grid
 * Categories: fs-lms-sections
 * Keywords: преимущества, фичи, features
 *
 * Источник: блок «сделаем вместе + фичи» в «Главная — мокап.dc.html» (1a).
 *
 * Фаза 11: маркеры `»` перед тезисами убраны (это не список, а короткие
 * абзацы); сетка карточек — `wp:group{"layout":{"type":"grid"}}` (2×2)
 * вместо двух рядов `wp:columns` — карточки были разной высоты, потому
 * что верхний и нижний ряд не выравнивались друг относительно друга,
 * настоящий CSS grid со `stretch` растягивает все четыре сразу.
 *
 * Фаза 12.5 (`Главная v4 - сборка.dc.html`): тексты 3 вопросов-абзацев и
 * 4 карточек обновлены под v4 (менее «ЕГЭ-специфичные» формулировки — этот
 * блок теперь общий для всех направлений, не только информатики). Все 4
 * иконки в v4 — один и тот же цвет (`accent`/`accent-soft`), варианты
 * `violet`/`practice`/`ok` из Фазы 4 здесь больше не используются. Кнопка
 * «О занятиях» — якорь `#lessons` (блок 12.7) вместо заглушки `#`.
 *
 * BugFix.3/5 (2026-09-03): кнопка «О занятиях» перекрашена в оранжевый
 * (`accent-2`, как остальные primary-кнопки темы). Иконки карточек —
 * обратно к 4 разным цветам (пастельные токены BugFix.4:
 * `subject-blue`/`subject-purple`/`subject-green`/`subject-yellow` +
 * насыщенный цвет иконки `info`/`violet`/`ok`/`subject-yellow-text`),
 * решение v4 «все 4 одного цвета» пересмотрено пользователем.
 */
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column {"width":"360px"} -->
		<div class="wp-block-column" style="flex-basis:360px">
			<!-- wp:heading {"fontSize":"xxl"} -->
			<h2 class="wp-block-heading has-xxl-font-size">Сделаем вместе<br><span style="color:var(--wp--preset--color--accent)">Шаг в будущее</span></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Ребёнку интересны компьютеры, но&nbsp;кружки в школе этого не дают?</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Не хватает мотивации заниматься самостоятельно?</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Хочется, чтобы за увлечением стояла настоящая профессия?</p>
			<!-- /wp:paragraph -->

            <!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
            <p class="has-text-secondary-color has-text-color has-md-font-size">Как нам это знакомо.</p>
            <!-- /wp:paragraph -->
            <!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
            <p class="has-text-secondary-color has-text-color has-md-font-size">Присоединяйся, вместе мы со всем справимся!</p>
            <!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"accent-2"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-accent-2-background-color has-background wp-element-button" href="#lessons">О занятиях</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-features-grid","layout":{"type":"grid","columnCount":2}} -->
			<div class="wp-block-group fs-features-grid">
				<!-- wp:fs-lms/feature-card {"icon":"users","iconColor":"info","iconBackground":"subject-blue"} -->
				<div class="wp-block-fs-lms-feature-card fs-feature-card"><span class="fs-feature-card__icon has-info-color has-subject-blue-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M7.5 9.5A2.5 2.5 0 1 0 7.5 4.5 2.5 2.5 0 0 0 7.5 9.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M3 18c0-2.5 2-4.5 4.5-4.5S12 15.5 12 18" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M14 6.7a2.5 2.5 0 0 1 0 4.6" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M17 18c0-2-1.2-3.7-3-4.3" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path></svg></span><h3 class="fs-feature-card__title">Группы до 8 человек</h3><p class="fs-feature-card__text">Занятия в приятной дружеской атмосфере в&nbsp;центре Калининграда. Можно заниматься вместе со своими друзьями или найти единомышленников на&nbsp;занятиях</p></div>
				<!-- /wp:fs-lms/feature-card -->

				<!-- wp:fs-lms/feature-card {"icon":"record","iconColor":"violet","iconBackground":"subject-purple"} -->
				<div class="wp-block-fs-lms-feature-card fs-feature-card"><span class="fs-feature-card__icon has-violet-color has-subject-purple-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M2.5 6h10v8h-10z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M12.5 9.2 17 6.5v7l-4.5-2.7z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path></svg></span><h3 class="fs-feature-card__title">Запись каждого занятия</h3><p class="fs-feature-card__text">Пришлось пропустить занятие? Не&nbsp;переживай, его&nbsp;можно посмотреть в записи и прорешать весь пройденный материал самостоятельно с&nbsp;поддержкой преподавателя</p></div>
				<!-- /wp:fs-lms/feature-card -->

				<!-- wp:fs-lms/feature-card {"icon":"document","iconColor":"ok","iconBackground":"subject-green"} -->
				<div class="wp-block-fs-lms-feature-card fs-feature-card"><span class="fs-feature-card__icon has-ok-color has-subject-green-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 2.5h6.2L15.5 6.8V17.5H5V2.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M11 3v4.3h4.3" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path></svg></span><h3 class="fs-feature-card__title">Своя учебная платформа</h3><p class="fs-feature-card__text">Ученикам доступны конспекты, записи занятий и&nbsp;дополнительные задания по&nbsp;каждой теме — можно закрепить знания и&nbsp;вспомнить всё необходимое перед контрольной или экзаменом</p></div>
				<!-- /wp:fs-lms/feature-card -->

				<!-- wp:fs-lms/feature-card {"icon":"spark","iconColor":"subject-yellow-text","iconBackground":"subject-yellow"} -->
				<div class="wp-block-fs-lms-feature-card fs-feature-card"><span class="fs-feature-card__icon has-subject-yellow-text-color has-subject-yellow-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 3 12 7l4.5.6-3.3 3.2.8 4.5L10 13.2 6 15.5l.8-4.5L3.5 7.7 8 7z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path></svg></span><h3 class="fs-feature-card__title">Регулярные тренировки</h3><p class="fs-feature-card__text">Тебя ждут регулярные контрольные работы и&nbsp;пробники экзаменов, а также система мотивации и&nbsp;контроля домашних работ для достижения наилучших результатов в&nbsp;подготовке!</p></div>
				<!-- /wp:fs-lms/feature-card -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
