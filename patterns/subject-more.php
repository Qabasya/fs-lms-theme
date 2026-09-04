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
 */

$subject_key = 'inf_ege';
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:0;padding-bottom:5.5rem">
	<!-- wp:heading {"textAlign":"center","fontSize":"xxl"} -->
	<h2 class="wp-block-heading has-text-align-center has-xxl-font-size">Хочешь больше?</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textAlign":"center","textColor":"text-secondary","fontSize":"md"} -->
	<p class="has-text-align-center has-text-secondary-color has-text-color has-md-font-size">Учебник с теорией и тренажёр с заданиями доступны каждому ученику направления.</p>
	<!-- /wp:paragraph -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"1.75rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"is-style-card fs-subject-more-card fs-subject-more-card--articles"} -->
			<div class="wp-block-group is-style-card fs-subject-more-card fs-subject-more-card--articles">
				<!-- wp:paragraph {"className":"fs-subject-more-card__title"} -->
				<p class="fs-subject-more-card__title"><a href="<?php echo esc_url( fs_lms_theme_subject_url( $subject_key, 'articles' ) ); ?>">Открыть учебник</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__text"} -->
				<p class="fs-subject-more-card__text">Конспекты, шпаргалки и памятки по каждой теме направления.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"is-style-card fs-subject-more-card fs-subject-more-card--trainer"} -->
			<div class="wp-block-group is-style-card fs-subject-more-card fs-subject-more-card--trainer">
				<!-- wp:paragraph {"className":"fs-subject-more-card__title"} -->
				<p class="fs-subject-more-card__title"><a href="<?php echo esc_url( fs_lms_theme_subject_url( $subject_key, 'trainer' ) ); ?>">Открыть тренажёр</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"fs-subject-more-card__text"} -->
				<p class="fs-subject-more-card__text">Задания по темам направления с проверкой и разбором ошибок.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
