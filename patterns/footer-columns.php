<?php
/**
 * Title: Футер — тёмная тема, контакты, карта, юридическая информация
 * Slug: fs-lms-theme/footer-columns
 * Categories: fs-lms-layout
 * Block Types: core/template-part/footer
 * Keywords: футер, подвал, footer, карта, реквизиты
 *
 * Фаза 12.9 (`Главная v4 - сборка.dc.html`): концептуальная смена на тёмную
 * тему (`#2b2f36`/`#3a3f47`) — осознанное решение макета v4, большое
 * визуальное расхождение со светлой остальной темой, не баг. Сетка —
 * `300px 1fr 300px` (обе текстовые колонки фиксированной ширины, карта в
 * резиновой средней колонке, отцентрована). Без соцсетей (YouTube/VK/
 * Telegram в макете v4 есть, сознательно не переносятся — Фаза 12 решение
 * 7). Тот же паттерн переиспользуется на страницах направлений (Фаза 13,
 * решение 4).
 *
 * BugFix.14 (2026-09-03): логотип переключён на отдельный файл
 * `img/logo-footer.png` (было — общий с шапкой `images/logo.png`,
 * решение 2 Фазы 12, разворот этого решения по прямому указанию
 * пользователя); ширина/высота пересчитаны под реальное соотношение
 * сторон файла (2800×816), не растянуты произвольно.
 */
?>
<!-- wp:group {"tagName":"div","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"var:preset|spacing|xxxl","bottom":"0"}}},"textColor":"white"} -->
<div class="wp-block-group has-white-color has-text-color" style="margin-top:0;padding-top:var(--wp--preset--spacing--xxxl);padding-bottom:0;background:#2b2f36">
	<!-- wp:group {"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:html -->
		<div class="fs-footer-grid">
			<div class="fs-footer-grid__col">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fs-footer-logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'img/logo-footer.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="172" height="50" style="display:block;width:172px;height:50px;object-fit:contain;" /></a>
				<p class="fs-footer-grid__lead">Школа программирования и робототехники в Калининграде</p>
				<div class="fs-footer-grid__contacts">
					<div>236006, г. Калининград, ул. Черняховского, д. 6, каб. 316</div>
					<a href="tel:+79953264486">Телефон: +7 995 326 44 86</a>
					<a href="mailto:info@future-step.ru">Почта: info@future-step.ru</a>
				</div>
			</div>

			<div class="fs-footer-grid__map">
				<iframe class="fs-aspect-16-9" src="https://yandex.ru/map-widget/v1/?indoorLevel=1&amp;ll=20.503606%2C54.718401&amp;oid=187566652967&amp;ol=biz&amp;z=16.53" width="100%" loading="lazy" style="border:0;border-radius:var(--wp--custom--radius--md)" title="<?php echo esc_attr__( 'Карта — где мы находимся', 'fs-lms-theme' ); ?>"></iframe>
			</div>

			<div class="fs-footer-grid__col">
				<div class="fs-footer-grid__title">Информация</div>
				<div class="fs-footer-grid__lead">ИП Иванов Борис Олегович<br>ИНН: 390407910400<br>ОГРН: 322390000000350</div>
				<div class="fs-footer-grid__contacts">
					<a href="<?php echo esc_url( home_url( '/public-offer/' ) ); ?>">Публичная оферта</a>
					<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Политика конфиденциальности</a>
				</div>
			</div>
		</div>

		<div class="fs-footer-bottom">© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. Репетитор ЕГЭ по информатике. Россия, Калининград.</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
