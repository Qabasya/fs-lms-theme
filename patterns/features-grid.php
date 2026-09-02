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
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--xl);padding-bottom:5.5rem">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column {"width":"360px"} -->
		<div class="wp-block-column" style="flex-basis:360px">
			<!-- wp:heading {"fontSize":"xxl"} -->
			<h2 class="wp-block-heading has-xxl-font-size">Сделаем вместе<br><span style="color:var(--wp--preset--color--accent)">Шаг в будущее</span></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Тебе не хватает мотивации заниматься самостоятельно?</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Сомневаешься, что школьный учитель эффективно подготовит тебя к ЕГЭ?</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"md"} -->
			<p class="has-text-secondary-color has-text-color has-md-font-size">Хочешь поступить в вуз твоей мечты и переехать в крупный город?</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"md"} -->
			<p class="has-md-font-size">Как нам это знакомо. Присоединяйся, вместе мы со всем справимся!</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"white","textColor":"text-secondary","className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-secondary-color has-white-background-color has-text-color has-background wp-element-button" href="#">О занятиях</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-features-grid","layout":{"type":"grid","columnCount":2}} -->
			<div class="wp-block-group fs-features-grid">
				<!-- wp:fs-lms/feature-card {"icon":"users","iconColor":"accent","iconBackground":"accent-soft","title":"Занятия в группах до 8 человек","text":"Подготовка к ЕГЭ в приятной атмосфере в центре Калининграда. Ты можешь заниматься вместе со своими друзьями или найти единомышленников на занятиях"} -->
				<div class="wp-block-fs-lms-feature-card fs-feature-card"><span class="fs-feature-card__icon has-accent-color has-accent-soft-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M7.5 9.5A2.5 2.5 0 1 0 7.5 4.5 2.5 2.5 0 0 0 7.5 9.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M3 18c0-2.5 2-4.5 4.5-4.5S12 15.5 12 18" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M14 6.7a2.5 2.5 0 0 1 0 4.6" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M17 18c0-2-1.2-3.7-3-4.3" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path></svg></span><h3 class="fs-feature-card__title">Занятия в группах до 8 человек</h3><p class="fs-feature-card__text">Подготовка к ЕГЭ в приятной атмосфере в центре Калининграда. Ты можешь заниматься вместе со своими друзьями или найти единомышленников на занятиях</p></div>
				<!-- /wp:fs-lms/feature-card -->

				<!-- wp:fs-lms/feature-card {"icon":"record","iconColor":"violet","iconBackground":"violet-soft","title":"Запись каждого занятия","text":"Вынужден пропустить занятие? Не переживай, ты можешь посмотреть его в записи и прорешать весь пройденный материал самостоятельно с поддержкой преподавателя"} -->
				<div class="wp-block-fs-lms-feature-card fs-feature-card"><span class="fs-feature-card__icon has-violet-color has-violet-soft-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M2.5 6h10v8h-10z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M12.5 9.2 17 6.5v7l-4.5-2.7z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path></svg></span><h3 class="fs-feature-card__title">Запись каждого занятия</h3><p class="fs-feature-card__text">Вынужден пропустить занятие? Не переживай, ты можешь посмотреть его в записи и прорешать весь пройденный материал самостоятельно с поддержкой преподавателя</p></div>
				<!-- /wp:fs-lms/feature-card -->

				<!-- wp:fs-lms/feature-card {"icon":"document","iconColor":"practice","iconBackground":"practice-soft","title":"Дополнительные материалы","text":"Тебе доступны и дополнительные материалы, которые помогут закрепить знания и вспомнить всё необходимое перед самим ЕГЭ по информатике: множество конспектов с теорией, шпаргалок и памяток"} -->
				<div class="wp-block-fs-lms-feature-card fs-feature-card"><span class="fs-feature-card__icon has-practice-color has-practice-soft-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 2.5h6.2L15.5 6.8V17.5H5V2.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path><path d="M11 3v4.3h4.3" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path></svg></span><h3 class="fs-feature-card__title">Дополнительные материалы</h3><p class="fs-feature-card__text">Тебе доступны и дополнительные материалы, которые помогут закрепить знания и вспомнить всё необходимое перед самим ЕГЭ по информатике: множество конспектов с теорией, шпаргалок и памяток</p></div>
				<!-- /wp:fs-lms/feature-card -->

				<!-- wp:fs-lms/feature-card {"icon":"spark","iconColor":"ok","iconBackground":"ok-soft","title":"Регулярные тренировки","text":"Тебя ждут регулярные пробники по информатике ЕГЭ с последующим разбором решений, а также система мотивации и контроля домашних работ для достижения наилучших результатов в подготовке!"} -->
				<div class="wp-block-fs-lms-feature-card fs-feature-card"><span class="fs-feature-card__icon has-ok-color has-ok-soft-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 3 12 7l4.5.6-3.3 3.2.8 4.5L10 13.2 6 15.5l.8-4.5L3.5 7.7 8 7z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"></path></svg></span><h3 class="fs-feature-card__title">Регулярные тренировки</h3><p class="fs-feature-card__text">Тебя ждут регулярные пробники по информатике ЕГЭ с последующим разбором решений, а также система мотивации и контроля домашних работ для достижения наилучших результатов в подготовке!</p></div>
				<!-- /wp:fs-lms/feature-card -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
