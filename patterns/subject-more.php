<?php
/**
 * Title: Хочешь больше? — ссылки на учебник и тренажёр предмета
 * Slug: fs-lms-theme/subject-more
 * Categories: fs-lms-sections
 * Keywords: направление, предмет, учебник, тренажёр
 *
 * Фаза 13.0: единственная живая связь страницы направления с плагином
 * (решение 2 Фазы 13) — 2 карточки-ссылки на разделы предмета, которые
 * заводит и наполняет плагин (`Inc\Enums\Wp\SubjectPageType::Articles` /
 * `::Trainer`). URL строит `fs_lms_theme_subject_url()` (`inc/PluginRoutes.php`)
 * — если плагин активен, слаг берётся из его же enum, иначе — тот же
 * `/{$subjectKey}/{articles|trainer}/`-паттерн локально.
 *
 * `$subject_key` ниже — дефолт под ЕГЭ по информатике (Фаза 13.1); при
 * вставке паттерна на страницы других направлений (13.2–13.4) редактор
 * меняет 4 места: `$subject_key` в PHP-переменной этого файла НЕ
 * параметризуется per-страничным образом (паттерны темы — статичный
 * стартовый контент, не шаблон с параметрами, см. «Принципы архитектуры»
 * в начале tasks.md) — поэтому при вставке на страницу другого предмета
 * это единственный паттерн темы, где после вставки в редакторе нужно
 * поправить 2 href вручную (или продублировать файл на 4 варианта — не
 * стали, т.к. отличаются только 2 ссылки, а не вся секция).
 *
 * BugFix.1 (2026-09-04, задача 1 tasks.md): карточки перерисованы по
 * мокапу «ЕГЭ информатика - мокап.dc.html» (DesignSync) — заголовок секции
 * слева (был центрирован), без вводного абзаца под ним; карточка — иконка
 * в кружке (цвет направления: синий у «Учебника», оранжевый у
 * «Тренажёра») + заголовок + текст + кнопка. Кликабельна только кнопка
 * (было — вся карточка через stretched-link на заголовке), заголовок
 * карточки — просто текст «Учебник»/«Тренажёр» (было — сама ссылка
 * «Открыть учебник»/«Открыть тренажёр», этот текст переехал на кнопку).
 */

$subject_key = 'inf_ege';
?>
<!-- wp:group {"className":"fs-section"} -->
<div class="wp-block-group fs-section">
	<!-- wp:heading {"fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-xxl-font-size">Хочешь больше?</h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"1.25rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-subject-more-card fs-subject-more-card--articles"} -->
			<div class="wp-block-group fs-subject-more-card fs-subject-more-card--articles">
				<!-- wp:html -->
				<span class="fs-subject-more-card__icon has-accent-color has-accent-soft-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 5.5C8.6 4.4 6.8 4 4.5 4v10c2.3 0 4.1.4 5.5 1.5 1.4-1.1 3.2-1.5 5.5-1.5V4c-2.3 0-4.1.4-5.5 1.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"></path><path d="M10 5.5v10" stroke="currentColor" stroke-width="1.6"></path></svg></span>
				<!-- /wp:html -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__title"} -->
				<p class="fs-subject-more-card__title">Учебник</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__text"} -->
				<p class="fs-subject-more-card__text">Собрали для тебя статьи по каждой теме в ЕГЭ: теория, разбор формата задания и приёмы, которые экономят время на экзамене.</p>
				<!-- /wp:paragraph -->

				<!-- wp:html -->
				<a class="fs-subject-more-card__button" href="<?php echo esc_url( fs_lms_theme_subject_url( $subject_key, 'articles' ) ); ?>">Открыть учебник</a>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"fs-subject-more-card fs-subject-more-card--trainer"} -->
			<div class="wp-block-group fs-subject-more-card fs-subject-more-card--trainer">
				<!-- wp:html -->
				<span class="fs-subject-more-card__icon has-accent-2-color has-accent-2-soft-background-color has-text-color has-background"><svg width="19" height="19" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3" y="3" width="14" height="14" rx="2.5" stroke="currentColor" stroke-width="1.6"></rect><path d="m6.5 9.5 2 2 5-5M7 14h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
				<!-- /wp:html -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__title"} -->
				<p class="fs-subject-more-card__title">Тренажёр</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__text"} -->
				<p class="fs-subject-more-card__text">Сборник заданий по каждой теме ЕГЭ с подробным решением: тренируйся по одному номеру или собирай вариант целиком.</p>
				<!-- /wp:paragraph -->

				<!-- wp:html -->
				<a class="fs-subject-more-card__button" href="<?php echo esc_url( fs_lms_theme_subject_url( $subject_key, 'trainer' ) ); ?>">Открыть тренажёр</a>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
