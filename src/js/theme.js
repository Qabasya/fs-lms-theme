/**
 * FS LMS Theme — фронт-интерактив (мобильное меню, FAQ-аккордеон и т.п.).
 *
 * Мобильное меню и FAQ-аккордеон обошлись без JS (нативный `core/navigation`
 * overlay и `<details>` соответственно, Фаза 3/4) — первый реальный модуль
 * появился только в Фазе 10.5 (quick view каталога WooCommerce). Карусели
 * на Splide — Фаза 12.0/12.3/12.4 (главная v4, выпускники/вузы). Лид-формы
 * (`#hero-form`/`#signup`) — Фаза 14.
 */

import { initQuickView } from './quick-view.js';
import { initCarousels } from './carousels.js';
import { initForms } from './forms.js';

initQuickView();
initCarousels();
initForms();
