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
			<!-- wp:html -->
			<a href="<?php echo esc_url( fs_lms_theme_subject_url( $subject_key, 'articles' ) ); ?>" class="wp-block-group is-style-card fs-subject-more-card">
				<span class="fs-subject-more-card__icon"><svg width="22" height="22" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 2.5h6.2L15.5 6.8V17.5H5V2.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/><path d="M11 3v4.3h4.3" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/></svg></span>
				<span class="fs-subject-more-card__title">Открыть учебник</span>
				<span class="fs-subject-more-card__text">Конспекты, шпаргалки и памятки по каждой теме направления.</span>
			</a>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:html -->
			<a href="<?php echo esc_url( fs_lms_theme_subject_url( $subject_key, 'trainer' ) ); ?>" class="wp-block-group is-style-card fs-subject-more-card">
				<span class="fs-subject-more-card__icon"><svg width="22" height="22" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 3 12 7l4.5.6-3.3 3.2.8 4.5L10 13.2 6 15.5l.8-4.5L3.5 7.7 8 7z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/></svg></span>
				<span class="fs-subject-more-card__title">Открыть тренажёр</span>
				<span class="fs-subject-more-card__text">Задания по темам направления с проверкой и разбором ошибок.</span>
			</a>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
