/**
 * FS LMS Theme — фронт-интерактив (мобильное меню, FAQ-аккордеон и т.п.).
 *
 * Мобильное меню и FAQ-аккордеон обошлись без JS (нативный `core/navigation`
 * overlay и `<details>` соответственно, Фаза 3/4). Карусели на Splide —
 * Фаза 12.0/12.3/12.4 (главная v4, выпускники/вузы). Лид-формы
 * (`#hero-form`/`#signup`) — Фаза 14. Фильтр по классу на «Курсы» — Фаза
 * 16.5. Quick view каталога WooCommerce (Фаза 10.5) убран в Фазе 16.2 —
 * нового макета магазина эта функция не предусматривает.
 */

import { initCarousels } from './carousels.js';
import { initForms } from './forms.js';
import { initCourseFilter } from './course-filter.js';
import { initHeaderScroll } from './header-scroll.js';
import { initCardLinks } from './card-links.js';
import { initCouponAccordion } from './coupon-accordion.js';

initCarousels();
initForms();
initCourseFilter();
initHeaderScroll();
initCardLinks();
initCouponAccordion();
