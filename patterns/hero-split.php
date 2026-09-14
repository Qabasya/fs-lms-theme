<?php
/**
 * Title: Hero — заголовок, текст, кнопки, факты, код-карточка
 * Slug: fs-lms-theme/hero-split
 * Categories: fs-lms-sections
 * Keywords: hero, обложка, заголовок
 *
 * Фаза 11 (`refs/Главная v2 - мокап.dc.html`): вернули правую колонку
 * (карточка кода `main.py`, до этого была пустым `wp:image` без src —
 * см. tasks.md Фаза 3 про упрощение до плейсхолдера) и полосу из 3-х
 * фактов под кнопками. Код-карточка — `wp:html`: разовая бесповторная
 * визуальная деталь с подсветкой синтаксиса, не блок для редактуры через
 * инспектор (тот же приём, что у карты в footer-columns.php).
 */
?>
<!-- wp:group {"className":"fs-section-lead"} -->
<div class="wp-block-group fs-section-lead">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"3.5rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":1,"fontSize":"xxxl"} -->
			<h1 class="wp-block-heading has-xxxl-font-size">Подготовка ЕГЭ по информатике</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"lead"} -->
			<p class="has-text-secondary-color has-text-color has-lead-font-size">Персональная поддержка профессионального репетитора для успешного обучения.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"accent-2"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-accent-2-background-color has-background wp-element-button" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться на пробное</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"backgroundColor":"accent-2"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-accent-2-background-color has-background wp-element-button" href="#courses">О занятиях</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

			<!-- wp:html -->
			<div class="fs-hero-facts">
				<div><div class="fs-hero-facts__number">84</div><div class="fs-hero-facts__label">средний балл</div></div>
				<div><div class="fs-hero-facts__number">800 ₽</div><div class="fs-hero-facts__label">час занятий</div></div>
				<div><div class="fs-hero-facts__number">до 8</div><div class="fs-hero-facts__label">человек в группе</div></div>
			</div>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"27.0833rem"} -->
		<div class="wp-block-column" style="flex-basis:27.0833rem">
			<!-- wp:html -->
			<div class="fs-code-card">
				<div class="fs-code-card__bar">
					<span></span><span></span><span></span>
					<div class="fs-code-card__filename">main.py</div>
				</div>
				<div class="fs-code-card__body">
					<div class="fs-code-card__lines">1<br>2<br>3<br>4<br>5<br>6</div>
					<div class="fs-code-card__code"><span class="fs-code-kw">def</span> <span class="fs-code-fn">problem</span>():
  <span class="fs-code-fn2">print</span>(<span class="fs-code-str">"Хочу сдать ЕГЭ на сотку"</span>)

                    <div class="fs-code-card__code"><span class="fs-code-kw">def</span> <span class="fs-code-fn">solution</span>():
    action = <span class="fs-code-str">"Записаться на курс"</span></div>
				</div>
			</div>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
