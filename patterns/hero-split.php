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
<!-- wp:group {"style":{"spacing":{"padding":{"top":"4.5rem","bottom":"5.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:4.5rem;padding-bottom:5.5rem">
	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"3.5rem"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"backgroundColor":"accent-soft","textColor":"accent-700","fontSize":"xs","style":{"spacing":{"padding":{"top":"var:preset|spacing|xs","bottom":"var:preset|spacing|xs","left":"var:preset|spacing|md","right":"var:preset|spacing|md"}}}} -->
			<p class="has-accent-700-color has-accent-soft-background-color has-text-color has-background has-xs-font-size" style="padding-top:var(--wp--preset--spacing--xs);padding-right:var(--wp--preset--spacing--md);padding-bottom:var(--wp--preset--spacing--xs);padding-left:var(--wp--preset--spacing--md)">КАЛИНИНГРАД · ГРУППЫ ДО 8 ЧЕЛОВЕК</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1,"fontSize":"xxxl"} -->
			<h1 class="wp-block-heading has-xxxl-font-size">Подготовка ЕГЭ по информатике</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"lead"} -->
			<p class="has-text-secondary-color has-text-color has-lead-font-size">Персональная поддержка профессионального репетитора для успешного обучения.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"accent"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button" href="<?php echo esc_url( fs_lms_theme_url( 'apply' ) ); ?>">Записаться на пробное</a></div>
				<!-- /wp:button -->

				<!-- wp:button {"backgroundColor":"white","textColor":"text-secondary","className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-secondary-color has-white-background-color has-text-color has-background wp-element-button" href="#courses">О занятиях</a></div>
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

		<!-- wp:column {"width":"520px"} -->
		<div class="wp-block-column" style="flex-basis:520px">
			<!-- wp:html -->
			<div class="fs-code-card">
				<div class="fs-code-card__bar">
					<span></span><span></span><span></span>
					<div class="fs-code-card__filename">main.py</div>
				</div>
				<div class="fs-code-card__body">
					<div class="fs-code-card__lines">1<br>2<br>3<br>4<br>5<br>6</div>
					<div class="fs-code-card__code"><span class="fs-code-kw">def</span> <span class="fs-code-fn">print_hi</span>():
  <span class="fs-code-comment"># И это чистая правда</span>
  <span class="fs-code-fn2">print</span>(<span class="fs-code-str">"Я сдам ЕГЭ на сотку"</span>)

<span class="fs-code-kw">if</span> __name__ == <span class="fs-code-str">"__Future_Step__"</span>:
  input = <span class="fs-code-str">"Записаться на курс"</span></div>
				</div>
			</div>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
