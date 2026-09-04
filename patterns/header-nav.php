<?php
/**
 * Title: Шапка — лого, меню, кнопка записи
 * Slug: fs-lms-theme/header-nav
 * Categories: fs-lms-layout
 * Block Types: core/template-part/header
 * Keywords: шапка, хедер, header, меню, навигация
 *
 * По мокапу v4 («Главная v4 - сборка.dc.html», Фаза 12): тонкая инфо-полоса +
 * основная навигация (лого, меню, CTA). Ссылка «Записаться» — якорь
 * `#hero-form` на форму первого экрана (Фаза 12, решение 5) — раньше вела на
 * страницу заявки плагина через `fs_lms_theme_url('apply')`, эта функция
 * остаётся для других страниц сайта. «Учебник»/«Тренажёр» остаются
 * заглушкой `#` — под них ещё нет страниц темы.
 *
 * Фаза 16.5: «Курсы» — на новую страницу-каталог направлений `/courses/`
 * (`home_url('/courses/')`, тот же приём, что «О нас» → `/about/`), было —
 * на страницу магазина WooCommerce через `fs_lms_theme_shop_url()` (Фаза
 * 10.3) — подтверждено пользователем 2026-09-03: «Курсы» — это каталог
 * направлений (ЕГЭ/ОГЭ/Python/Робототехника), не витрина товаров магазина.
 * `fs_lms_theme_shop_url()` остаётся в `inc/WooCommerce.php` для витрины
 * магазина (Фаза 16.2), просто эта конкретная ссылка её больше не
 * использует.
 *
 * Соцсети (YouTube/VK/Telegram) в макете v4 нет (Фаза 12, решение 7) — в
 * шапке их и не было с самого начала (Фаза 3), убирать нечего.
 *
 * BugFix (2026-09-03): «Главная» была захардкожена с `className:
 * current-menu-item` безусловно — на `/courses/` (и на любой другой
 * странице) подсвечивалась «Главная», а не текущий раздел. `wp:navigation-link`
 * со статичным `url` (не ссылкой на реальный ID поста через
 * `"kind":"post-type"`) не получает подсветку от ядра автоматически —
 * ниже вычисляем активный пункт вручную через `is_front_page()`/`is_page()`
 * и подставляем `className` в JSON-атрибуты блока (та же техника, что
 * `esc_url()` в `url` этих же блоков).
 *
 * BugFix.14 (2026-09-03): логотип переключён на отдельный файл
 * `img/logo-header.png` (было — общий с футером `images/logo.png`,
 * решение 2 Фазы 12, разворот по прямому указанию пользователя); ширина
 * пересчитана под реальное соотношение сторон файла (2800×816), высота
 * не менялась (64px).
 *
 * BugFix (2026-09-04): кнопка «Личный кабинет» убрана из CTA-группы (была
 * дублем одноимённого пункта меню) — по прямому указанию пользователя.
 * Внешний ряд (лого + меню + CTA) переносился на вторую строку на десктопе
 * из-за `flexWrap:wrap` по умолчанию у layout-типа `flex` — добавлен класс
 * `fs-header-row` с `flex-wrap:nowrap` (`theme.scss`), чтобы кнопки всегда
 * оставались на одном уровне со ссылками навигации.
 *
 * BugFix (2026-09-04): иконка корзины WooCommerce заменена на иконку
 * личного кабинета (ссылка на `/profile/`, `fs_lms_theme_url('profile')`)
 * — по прямому указанию пользователя. `wc_get_cart_url()`/проверка
 * `function_exists('wc_get_cart_url')` больше не нужны — ссылка на профиль
 * не зависит от активности WooCommerce.
 *
 * Задача 9 (tasks.md, 2026-09-04): «Записаться» больше не жёстко
 * `#hero-form` — `fs_lms_theme_signup_button_url()` (`inc/Forms.php`)
 * отдаёт якорь формы на текущей странице либо, если её нет, якорь формы
 * главной страницы.
 *
 * Слоган в инфо-полосе — абзац, а не `<h1>`: заголовок первого уровня на
 * странице должен быть один (и это заголовок самой страницы), иначе
 * поисковик видит на каждой странице сайта один и тот же h1.
 */
$fs_nav_id  = function_exists( 'fs_lms_theme_navigation_id' ) ? fs_lms_theme_navigation_id() : 0;
$fs_cta_url = function_exists( 'fs_lms_theme_signup_button_url' ) ? fs_lms_theme_signup_button_url() : '#hero-form';
?>
<!-- wp:group {"tagName":"div","backgroundColor":"surface-2","style":{"spacing":{"padding":{"top":"var:preset|spacing|xs","bottom":"var:preset|spacing|xs"}},"border":{"bottom":{"color":"var:preset|color|border-light","width":"1px"}}}} -->
<div class="wp-block-group has-surface-2-background-color has-background" style="border-bottom-color:var(--wp--preset--color--border-light);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--xs);padding-bottom:var(--wp--preset--spacing--xs)">
	<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
		<!-- wp:group {"className":"fs-topbar","layout":{"type":"flex","justifyContent":"space-between"}} -->
		<div class="wp-block-group fs-topbar">
			<!-- wp:paragraph {"className":"fs-topbar__slogan","textColor":"muted-2","fontSize":"xxs"} -->
			<p class="fs-topbar__slogan has-muted-2-color has-text-color has-xxs-font-size">ЕГЭ, ОГЭ, программирование и робототехника в Калининграде</p>
			<!-- /wp:paragraph -->
			<!-- wp:group {"layout":{"type":"flex"},"style":{"spacing":{"blockGap":"var:preset|spacing|xl","margin":{"left":"auto"}}}} -->
			<div class="wp-block-group" style="margin-left:auto">
				<!-- wp:paragraph {"textColor":"muted","fontSize":"xxs"} -->
				<p class="has-muted-color has-text-color has-xxs-font-size"><a href="tel:+79953264486">+7 995 326 44 86</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"muted","fontSize":"xxs"} -->
				<p class="has-muted-color has-text-color has-xxs-font-size"><a href="mailto:info@future-step.ru">info@future-step.ru</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"div","backgroundColor":"white","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"var:preset|spacing|md","bottom":"var:preset|spacing|md"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}}}} -->
<div class="wp-block-group has-white-background-color has-background" style="margin-top:0;border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--lg)">
	<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"left":"var:preset|spacing|xxl","right":"var:preset|spacing|xxl"}}}} -->
	<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--xxl);padding-left:var(--wp--preset--spacing--xxl)">
		<!-- wp:group {"className":"fs-header-row","layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"center"}} -->
		<div class="wp-block-group fs-header-row">
			<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|md"}}} -->
			<div class="wp-block-group">
				<!-- wp:image {"width":"220px","className":"fs-header-logo","linkDestination":"custom"} -->
				<figure class="wp-block-image fs-header-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'img/logo-header.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" style="width:220px"/></a></figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:group -->

			<?php if ( $fs_nav_id > 0 ) : ?>
			<!-- wp:navigation {"ref":<?php echo (int) $fs_nav_id; ?>,"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"center"},"style":{"typography":{"fontSize":"sm"}}} /-->
			<?php else : ?>
			<!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"center"},"style":{"typography":{"fontSize":"sm"}}} -->
			<!-- wp:home-link {"label":"Главная"} /-->

			<!-- wp:navigation-link {"label":"О нас","url":"<?php echo esc_url( home_url( '/about/' ) ); ?>"} /-->

			<!-- wp:navigation-link {"label":"Курсы","url":"<?php echo esc_url( home_url( '/courses/' ) ); ?>"} /-->
			<!-- /wp:navigation -->
			<?php endif; ?>

			<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|lg"}}} -->
			<div class="wp-block-group">

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"backgroundColor":"accent-2"} -->
					<div class="wp-block-button"><a class="wp-block-button__link has-accent-2-background-color has-background wp-element-button" href="<?php echo esc_url( $fs_cta_url ); ?>">Записаться</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

				<!-- wp:html -->
				<a class="fs-header-account" href="<?php echo esc_url( fs_lms_theme_url( 'profile' ) ); ?>" aria-label="<?php echo esc_attr__( 'Личный кабинет', 'fs-lms-theme' ); ?>"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/><path d="M4 20c0-4.4 3.6-8 8-8s8 3.6 8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></a>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
