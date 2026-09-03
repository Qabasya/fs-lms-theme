<?php
/**
 * Title: Реквизиты организации — блок над юридическим аккордеоном
 * Slug: fs-lms-theme/about-header
 * Categories: fs-lms-sections
 * Keywords: о нас, реквизиты, лицензия, ИП, огрнип
 *
 * Фаза 15.0 (источник дизайна — `О нас - мокап.dc.html`, тот же Claude
 * Design проект Фазы 12, недоступен локально в этой сессии — см. заголовок
 * `patterns/subject-hero.php` про то же ограничение). Не переписывает
 * контент аккордеона (`about-accordion.php`, Фаза 10.2) — добавляется НАД
 * ним на странице `/about/` отдельным блоком: центрированное юридическое
 * название + реквизиты + абзац о лицензии + ссылка на PDF.
 *
 * Решение 2 Фазы 15: макет — колонка 900px, у `templates/page.html`
 * `contentSize:720px` — общий шаблон не трогаем, здесь своя группа с
 * `contentSize:900px`.
 * Решение 1: заголовок страницы (`wp:post-title` в `templates/page.html`)
 * меняется на юридическое название ИП вручную в админке (см. tasks.md
 * 15.0) — этот паттерн НЕ дублирует `<h1>`, начинается сразу с
 * реквизитов под заголовком страницы.
 * Решение 3: ссылка «Посмотреть лицензию →» — заглушка `href="#"`, файл
 * скана добавится позже отдельным шагом (подтверждено пользователем
 * 2026-09-02) — не блокирует реализацию.
 *
 * Реквизиты (адрес/телефон/почта) — те же, что в `footer-columns.php`
 * (единственный источник этих данных в теме); ИНН/ОГРНИП — тоже оттуда
 * (футер называет второй номер «ОГРН», хотя для ИП корректно «ОГРНИП» —
 * не отдельная ошибка этой фазы, тот же номер, только точная подпись).
 * Режим работы — не был нигде зафиксирован в репозитории, проставлен
 * ориентировочно (Пн–Пт, дневные часы работы школы) — сверить с реальным
 * при живой проверке.
 */
?>
<!-- wp:group {"layout":{"type":"constrained","contentSize":"900px"},"style":{"spacing":{"padding":{"top":"3rem","bottom":"2rem"}}}} -->
<div class="wp-block-group" style="padding-top:3rem;padding-bottom:2rem">
	<!-- wp:paragraph {"align":"center","fontSize":"sm"} -->
	<p class="has-text-align-center has-sm-font-size">ИНН 390407910400 · ОГРНИП 322390000000350</p>
	<!-- /wp:paragraph -->

	<!-- wp:separator {"className":"is-style-wide"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
	<!-- /wp:separator -->

	<!-- wp:html -->
	<div class="fs-about-requisites">
		<div class="fs-about-requisites__item"><span class="fs-about-requisites__label">Место нахождения</span><span>236006, г. Калининград, ул. Черняховского, д. 6, каб. 316</span></div>
		<div class="fs-about-requisites__item"><span class="fs-about-requisites__label">Режим работы</span><span>Пн–Пт: 10:00–20:00</span></div>
		<div class="fs-about-requisites__item"><span class="fs-about-requisites__label">Телефон</span><a href="tel:+79953264486">+7 995 326 44 86</a></div>
		<div class="fs-about-requisites__item"><span class="fs-about-requisites__label">Электронная почта</span><a href="mailto:info@future-step.ru">info@future-step.ru</a></div>
	</div>
	<!-- /wp:html -->

	<!-- wp:paragraph {"textColor":"text-secondary","fontSize":"sm"} -->
	<p class="has-text-secondary-color has-text-color has-sm-font-size">Образовательная деятельность осуществляется на основании лицензии № 11193 от 02.10.2025, выданной Министерством образования Калининградской области (регистрационный номер записи в реестре лицензий — 11193).</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"align":"center"} -->
	<p class="has-text-align-center"><a href="#">Посмотреть лицензию →</a></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
