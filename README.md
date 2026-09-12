# FS LMS Theme

Блочная (FSE) WordPress-тема для `fs-lms`: собственная дизайн-система,
кастомные Gutenberg-блоки и библиотека паттернов, из которых страницы
собираются вручную в редакторе (как в WoodMart, но на нативных блоках, без
Elementor). Не путать с `design-mockup/README.md` — там описан
Claude Design хэндофф, использованный как источник вёрстки/токенов, а не
сама тема.

Архитектура и статус по фазам — см. [`tasks.md`](tasks.md).

## Сборка

```bash
npm install
npm run build        # разработка: собирает assets/, пишет sourcemaps
npm run watch        # то же + пересобирает на каждое изменение src/
npm run build:prod   # прод: без sourcemaps (gulp build --production)
```

Сборка — gulp + webpack, по образцу плагина `fs-lms` (`gulpfile.js`), не
`@wordpress/scripts`. Результат пишется в `assets/` (гитигнорится, всегда
собирается заново из `src/`).

Линтеры:

```bash
npm run lint:js    # eslint src/js src/blocks
npm run lint:css   # stylelint src/scss src/blocks
```

## Структура папок

```
inc/                  PHP-модули bootstrap'а (functions.php их просто require'ит)
  Setup.php             theme supports, стиль блока «Карточка»
  Patterns.php          категории паттернов инсёртера
  Assets.php            шрифты + подключение собранных assets/css|js
  Blocks.php            регистрация кастомных блоков (glob по src/blocks/*/block.json)
  PluginRoutes.php      fs_lms_theme_url() — резолвер URL страниц плагина
  Forms.php             лид-формы (#hero-form/#signup): AJAX-приём, honeypot/
                        HMAC-таймер, rate-limit, Yandex SmartCaptcha, письмо
  WooCommerce.php       интеграция каталога с блоком «Товары»

src/
  scss/                theme.scss (фронт), editor.scss (редактор)
  js/                  theme.js (фронт-интерактив), forms.js (AJAX-отправка
                        лид-форм), carousels.js (Splide-карусели)
  blocks/              кастомные Gutenberg-блоки, см. src/blocks/README.md
  blocks/shared/       общие модули блоков (иконки, цвета, рейтинг, инициалы)

patterns/              PHP-паттерны — секции страниц (fs-lms-sections) и
                        шапка/футер (fs-lms-layout)
parts/                 template parts — тонкие обёртки вокруг паттернов шапки/футера
templates/              FSE-шаблоны (front-page, page, index, 404)
theme.json              дизайн-токены (источник — fs-lms plugin *_tokens.scss/_variables.scss)
```

## Как добавить новый блок

1. Создать `src/blocks/<name>/{block.json,index.js,edit.js,save.js,style.scss}`
   по образцу существующего блока (например `src/blocks/stat-tile/`).
2. `block.json`: `apiVersion: 3`, `name: "fs-lms/<name>"`,
   `category: "fs-lms-cards"`, `textdomain: "fs-lms-theme"`.
3. Ничего не прописывать в `gulpfile.js`/`inc/Blocks.php` — оба находят блок
   через `glob` по файловой структуре сами.
4. `npm run build`, затем зарегистрировать использование в паттерне
   (см. ниже) или вставить блок в редакторе напрямую.

Подробнее — [`src/blocks/README.md`](src/blocks/README.md).

## Блоки (`src/blocks/`)

8 кастомных Gutenberg-блоков, категория инсёртера `fs-lms-cards`. Каждый —
папка `src/blocks/<name>/`: `block.json` (атрибуты), `edit.js` (вид в
редакторе + инспектор), `save.js` (что сохраняется в разметку страницы),
`style.scss` (стили фронта+редактора). Чтобы поменять поля блока (что можно
редактировать в инспекторе справа) — правь `edit.js` и синхронно
`block.json`/`save.js`; чтобы поменять только внешний вид — правь
`style.scss` и пересобери (`npm run build`).

| Блок | Что показывает | Где редактировать |
|---|---|---|
| `fs-lms/course-card` | Обложка, бейдж класса, название, описание, цена, кнопка «Записаться» — карточка курса | `src/blocks/course-card/` |
| `fs-lms/teacher-card` | Фото, имя, роль/предмет, короткое био, ссылка на профиль | `src/blocks/teacher-card/` |
| `fs-lms/testimonial-card` | Аватар/инициалы, имя, роль, текст отзыва, рейтинг (0–5 звёзд) | `src/blocks/testimonial-card/` |
| `fs-lms/feature-card` | Иконка (выбор из 6 встроенных SVG), заголовок, текст — карточка преимущества | `src/blocks/feature-card/` |
| `fs-lms/stat-tile` | Крупное число, подпись, пояснение — плитка статистики | `src/blocks/stat-tile/` |
| `fs-lms/faq-item` | Вопрос/ответ на нативном `<details>/<summary>` — аккордеон без JS | `src/blocks/faq-item/` |
| `fs-lms/cta-banner` | Заголовок, текст, 1–2 кнопки, вариант фона solid/soft | `src/blocks/cta-banner/` |
| `fs-lms/alumni-card` | Фото, баллы, имя, короткий отзыв выпускника — слайд карусели «Наши выпускники» | `src/blocks/alumni-card/` |

Общие для блоков модули — `src/blocks/shared/` (иконки, цветовые пары
бейджей, инициалы из имени, рендер звёзд рейтинга, пикер картинки).

Чтобы поменять внешний вид готовой карточки/плитки на конкретной странице —
не обязательно лезть в код блока: открыть страницу в редакторе, кликнуть по
блоку и поправить поля в инспекторе справа (текст, картинка, цвет — всё,
что вынесено в атрибуты `block.json`) — правки блока в коде (`edit.js`/
`save.js`/`style.scss`) нужны только когда меняется сам набор полей блока
или его вёрстка/стили во всех местах использования разом.

## Как добавить новый паттерн

1. Новый файл `patterns/<slug>.php` с докблоком:
   ```php
   <?php
   /**
    * Title: Человекочитаемое название
    * Slug: fs-lms-theme/<slug>
    * Categories: fs-lms-sections
    * Keywords: слово, для, поиска, в, инсёртере
    */
   ?>
   <!-- сериализованная разметка блоков -->
   ```
2. WordPress регистрирует паттерн автоматически по докблоку — ничего
   регистрировать в PHP не нужно.
3. Категории паттернов (`fs-lms-sections` — секции страниц, `fs-lms-layout` —
   шапка/футер) заведены в `inc/Patterns.php`.
4. Если паттерн ссылается на функциональную страницу плагина (запись на
   занятие, вход, профиль) — использовать `fs_lms_theme_url( 'apply' | 'sign-in' | 'profile' )`
   вместо хардкод-URL (см. `inc/PluginRoutes.php`).

## Паттерны (`patterns/`)

Каждая секция сайта — отдельный PHP-файл, который исполняется как обычный
PHP (можно `<?php if/echo ?>` внутри — паттерны не статичные текстовые
файлы, WordPress подключает их через `ob_start()+include`) и выводит
сериализованную блочную разметку. Категория `fs-lms-layout` — шапка/футер
(template parts), `fs-lms-sections` — секции страниц, вставляются через
`wp:pattern` в шаблоне или руками из инсёртера в контенте страницы. Чтобы
поменять текст/цвета/расположение внутри готовой секции — редактируй файл
паттерна напрямую (или открой соответствующий блок в Site Editor и
отредактируй мышкой — оба пути меняют один и тот же результат, разница
только в том, где хранится правка: в файле темы или в базе).

| Паттерн | Секция | Файл |
|---|---|---|
| `header-nav` | Шапка: топбар, лого, меню, соцсети, «Записаться», корзина | `patterns/header-nav.php` |
| `footer-columns` | Футер: контакты+адрес, карта Яндекса, юр. реквизиты, копирайт | `patterns/footer-columns.php` |
| `hero` | Первый экран главной: 4 направления + карточка формы записи (`#hero-form`) + 3 факта — в `front-page.html` по умолчанию | `patterns/hero.php` |
| `hero-split` | Более старый вариант первого экрана (заголовок, лид, кнопки, 3 факта, код-карточка `main.py`) — не в шаблоне, оставлен в библиотеке про запас | `patterns/hero-split.php` |
| `alumni-carousel` | «Наши выпускники» — Splide-карусель отзывов из `fs-lms/alumni-card` | `patterns/alumni-carousel.php` |
| `alumni-strip` | Ряд логотипов вузов-партнёров | `patterns/alumni-strip.php` |
| `features-grid` | «Сделаем вместе» — текст слева + сетка 2×2 карточек-преимуществ | `patterns/features-grid.php` |
| `courses-grid` | «Курсы и направления» — 3 карточки курсов с ценой | `patterns/courses-grid.php` |
| `intensive-split` | «Интенсивная подготовка» — фото, чек-лист, плашка цены | `patterns/intensive-split.php` |
| `stats-row` | Библиотечный паттерн — 3 плитки статистики (цена/средний балл/лучший балл), не в шаблоне по умолчанию | `patterns/stats-row.php` |
| `blog-grid` | Библиотечный паттерн — «Как проходят занятия», 3 последних поста блога (реальный `WP_Query`, секция скрывается, если постов < 3), не в шаблоне по умолчанию | `patterns/blog-grid.php` |
| `contact-section` | CTA + форма заявки на пробное занятие (`#signup`), с полем «Направление» — используется на главной | `patterns/contact-section.php` |
| `about-header` | Реквизиты организации над юридическим аккордеоном (используется на `/about/`) | `patterns/about-header.php` |
| `about-accordion` | Юридический аккордеон организации (используется на `/about/`, см. ниже) | `patterns/about-accordion.php` |
| `subject-hero` | Первый экран страницы направления: инфобокс + мини-форма записи (`#hero-form`) — общий шаблон для ЕГЭ/ОГЭ/Python/Робототехники, тексты правятся вручную при вставке | `patterns/subject-hero.php` |
| `subject-contact` | Та же форма записи, что `contact-section`, но без поля «Направление» — для страницы направления, где предмет уже задан контекстом | `patterns/subject-contact.php` |
| `subject-more` | «Хочешь больше?» — 2 карточки-ссылки на учебник/тренажёр предмета (URL через `fs_lms_theme_subject_url()`) | `patterns/subject-more.php` |
| `faq` | Библиотечный паттерн — FAQ-аккордеон (`fs-lms/faq-item`), не привязан к конкретной странице | `patterns/faq.php` |
| `testimonials` | Библиотечный паттерн — сетка карточек отзывов | `patterns/testimonials.php` |
| `cta-banner` | Библиотечный паттерн — обёртка вокруг блока `fs-lms/cta-banner` | `patterns/cta-banner.php` |

«Библиотечные» — не встроены ни в один шаблон по умолчанию, вставляются
вручную из инсёртера на любой странице, где нужны.

Формы (`hero`/`subject-hero` → `#hero-form`, `contact-section`/
`subject-contact` → `#signup`) — не статичная вёрстка, а рабочие формы с
реальной отправкой на почту; как их настраивать — раздел «Формы и
Yandex SmartCaptcha» ниже.

## Формы и Yandex SmartCaptcha

В теме две рабочие лид-формы «оставьте контакты, перезвоним» —
`#hero-form` (паттерны `hero.php`/`subject-hero.php`) и `#signup`
(паттерны `contact-section.php`/`subject-contact.php`). Это **не** тот же
поток, что заявка на зачисление плагина `fs-lms` (OTP, шифрование
персональных данных, личный кабинет) — своя лёгкая логика в теме,
`inc/Forms.php` + `src/js/forms.js`, без зависимости от классов плагина.

### Как это работает

1. Каждая форма — обычный `<form data-fs-form>` в `wp:html` внутри
   паттерна. `src/js/forms.js` (`initForms()`, вызывается из
   `src/js/theme.js`) перехватывает `submit`, шлёт `fetch` на
   `admin-ajax.php` (`action=fs_theme_submit_form`) и без перезагрузки
   страницы показывает ответ в `.fs-form-message` внутри формы.
2. Обработчик на сервере — `fs_lms_theme_handle_form_submit()` в
   `inc/Forms.php`. Проверяет по порядку: nonce (`check_ajax_referer`) →
   honeypot + HMAC-таймер (защита от ботов) → rate-limit по IP
   (`FS_LMS_THEME_FORM_RATE_LIMIT` — 5 сабмитов за
   `FS_LMS_THEME_FORM_RATE_WINDOW`, 10 минут) → капча Yandex SmartCaptcha
   (только если настроена, см. ниже) → валидация имени/телефона → письмо
   через `wp_mail()` на `FS_LMS_THEME_FORM_RECIPIENT` (сейчас
   `info@future-step.ru`, константа в `inc/Forms.php`).
3. Honeypot — скрытое поле `fs_company` (класс `.fs-form-honeypot`,
   `tabindex="-1" aria-hidden="true"`) — бот его заполняет, человек не
   видит и не трогает. HMAC-таймер — скрытое поле `fs_form_token`
   (`{timestamp}.{hmac}`, подписано `AUTH_KEY` или константой
   `FS_LMS_THEME_FORM_SALT`, если задана в `wp-config.php`) — отсекает
   сабмит раньше `FS_LMS_THEME_FORM_MIN_FILL_SECONDS` (3 сек, слишком
   быстро для человека) и токены старше часа.

### Как подключить капчу и куда вписывать ключи Яндекса

Капча — **опциональна**: если ключи не заданы, формы работают на одном
honeypot+таймере+rate-limit, ничего не ломается на голой установке.

1. Получить пару ключей Yandex SmartCaptcha в
   [Yandex Cloud](https://cloud.yandex.ru/services/smartcaptcha) —
   **Client-side (site) key** и **Server-side (secret) key** для домена
   сайта.
2. В админке WordPress: **Настройки → Формы** (пункт меню добавляет
   `inc/SmartCaptcha.php`) — вписать оба ключа в поля **Site key** и
   **Server key**, «Сохранить изменения».
3. Дальше ничего делать не нужно — как только оба поля заполнены:
   - `fs_lms_theme_captcha_configured()` начинает возвращать `true`;
   - паттерны форм выводят пустой контейнер `<div data-fs-captcha>`
     (`fs_lms_theme_captcha_slot_html()`);
   - на первом фокусе в форме `src/js/captcha.js` вставляет скрипт
     `https://smartcaptcha.yandexcloud.net/captcha.js` и рендерит **невидимый**
     виджет (`invisible: true`): задание Яндекс показывает только при
     подозрении на бота. Значок Яндекса в углу экрана скрыт
     (`hideShield: true`). Условия Яндекса разрешают это, только если
     посетитель уведомлён об обработке данных SmartCaptcha иначе, — такое
     уведомление со ссылкой на условия Яндекса размещается на сайте вручную,
     тема его не выводит. Через `wp_enqueue_script` скрипт **не** подключается
     специально: оптимизаторы (WP Rocket) минифицируют его и отдают со
     своего домена, а `captcha.js` строит адрес iframe от своего `src` —
     вместо капчи открывается 404-страница сайта;
   - `fs_lms_theme_handle_form_submit()` проверяет `smart-token` через
     `POST https://smartcaptcha.yandexcloud.net/validate` с server key
     (таймаут 5 сек, **fail-open** — если API Яндекса недоступен/ошибся,
     сабмит не блокируется, чтобы сторонний сервис не клал форму);
   - если у посетителя капча не загрузилась (блокировщик, сеть), форма
     уходит без токена и принимается под общим на сайт лимитом
     `FS_LMS_THEME_FORM_NO_CAPTCHA_LIMIT` (5 заявок в час), в письме
     появляется пометка об этом. Неверный токен — отказ.
4. Метка `fs_form_token` и nonce берутся заново на первом фокусе в форме
   (`action=fs_theme_form_token`), поэтому кэш страниц формам не мешает.
5. Ключи темы **не совпадают и не переиспользуют** ключи капчи плагина
   `fs-lms` (`SmartCaptchaSettingsController`) — это два независимых
   инстанса SmartCaptcha (можно оформить один сайт на два ключа в Yandex
   Cloud, или использовать разные пары), хранятся в отдельных опциях
   (`fs_lms_theme_captcha_site_key`/`fs_lms_theme_captcha_server_key`).

### Как поменять получателя писем, лимиты или добавить поле в форму

- Email получателя заявок — константа `FS_LMS_THEME_FORM_RECIPIENT` в
  `inc/Forms.php`, тема письма — `FS_LMS_THEME_FORM_MAIL_SUBJECT`.
- Письмо: имя, телефон, IP и «Форма:» — ссылка на страницу, на главной ещё
  «Форма 1» (hero) / «Форма 2» (signup), `FS_LMS_THEME_FRONT_PAGE_FORMS`.
  Ниже технический блок: время прихода заявки, исход капчи (пройдена с
  заданием или без / не загрузилась / API Яндекса не ответил / выключена),
  время заполнения формы, устройство и User-Agent браузера.
- Лимиты rate-limit/таймера — константы `FS_LMS_THEME_FORM_RATE_LIMIT`,
  `FS_LMS_THEME_FORM_RATE_WINDOW`, `FS_LMS_THEME_FORM_MIN_FILL_SECONDS`,
  `FS_LMS_THEME_FORM_MAX_TOKEN_AGE` в начале `inc/Forms.php`.
- Новое поле в форме — добавить `<input>`/`<select>` с нужным `name` в
  разметке паттерна (`hero.php`/`subject-hero.php`/`contact-section.php`/
  `subject-contact.php`) **и** прочитать это поле (`sanitize_text_field`
  + добавить строку в `$lines`) в `fs_lms_theme_handle_form_submit()` —
  без второго шага значение просто не попадёт в письмо, сервер игнорирует
  неизвестные поля `$_POST`.
- Обязательные служебные поля, которые должны быть в разметке любой формы
  темы: `form_id` (скрытое, различает формы главной в письме),
  `fs_form_token` (`<?php echo esc_attr( fs_lms_theme_form_timestamp_token() ); ?>`),
  honeypot (`name="<?php echo esc_attr( fs_lms_theme_honeypot_field() ); ?>"`,
  класс `.fs-form-honeypot`), контейнер `.fs-form-message` для ответа JS и
  сам `<form data-fs-form>` — без них `src/js/forms.js` не найдёт форму
  или сервер отклонит сабмит как «не человек».

## Как собрать свою страницу (на примере `/about/`)

Страницы не верстаются шаблоном под каждый случай — редактор собирает их в
Site Editor/редакторе страницы из готовых паттернов, как в WoodMart, но на
нативных блоках. Разберём на уже готовом примере — юридической странице
`/about/` (Фаза 10.2, `tasks.md`):

1. **Страницы → Добавить новую** в админке WordPress, заголовок «О нас»
   (или как нужно — заголовок и URL независимы).
2. **Постоянная ссылка** — вручную выставить slug `about`, чтобы адрес
   получился `/about/` (по умолчанию WordPress сделает slug из заголовка).
3. **Шаблон страницы** справа в настройках — `Page` (наш `templates/page.html`:
   заголовок + `wp:post-content`, `contentSize: 720px`). Для полноширинной
   секционной вёрстки, как на главной, нужен отдельный FSE-шаблон в
   `templates/` по образцу `front-page.html`.
4. В содержимом страницы открыть инсёртер блоков (значок `+`) → вкладка
   «Паттерны» → категория «fs-lms-sections» → найти и вставить
   **«Сведения об образовательной организации»** (`fs-lms-theme/about-accordion`).
   Паттерн уже содержит весь юридический текст (перенесён с прод-сайта,
   `patterns/about-accordion.php`) — ничего дополнительно вписывать не
   нужно, аккордеон рабочий сразу (чистый `<details>`, без JS).
5. Если странице не хватает готового паттерна под нужную секцию — см. «Как
   добавить новый паттерн» выше, либо просто собрать её из обычных
   core-блоков и/или кастомных `fs-lms/*` прямо в редакторе.
6. Если на странице нужна ссылка на функциональную страницу плагина
   (запись на занятие, вход, профиль) — использовать
   `fs_lms_theme_url( 'apply' | 'sign-in' | 'profile' )` в паттерне, не
   хардкод-URL.
7. Добавить пункт меню на новую страницу в `patterns/header-nav.php` —
   найти нужный `wp:navigation-link` (у «О нас» уже стоит
   `home_url('/about/')`, у остальных — заглушка `#`) и подставить
   `home_url('/<slug>/')` или прямую ссылку.
8. **Опубликовать**. Каталог товаров WooCommerce собирается похожим
   образом, но через блок «Товары» (`Products`/`All Products Block`) — он
   сам тянет реальные `WC_Product`, подробности и живая логика каталога —
   `inc/WooCommerce.php` и `tasks.md`, Фаза 10.

Похожим образом собирается страница направления (ЕГЭ/ОГЭ/Python/
Робототехника) — из трёх паттернов подряд: `subject-hero` (инфобокс +
`#hero-form`) → `subject-more` (ссылки на учебник/тренажёр) →
`subject-contact` (`#signup` без поля «Направление»). У всех трёх, кроме
`subject-more`, контент общий шаблон под ЕГЭ по умолчанию — при вставке на
страницу другого предмета текст (бейдж/`<h1>`/описание) и переменная
`$subject_key` в начале `subject-more.php` правятся вручную прямо в файле
паттерна (см. докблок `subject-more.php` и `tasks.md`, Фаза 13).

## Где искать токены

Единственный источник дизайн-токенов — `fs-lms` plugin
`src/scss/frontend/_variables.scss` + `src/scss/shared/_tokens.scss`.
`theme.json` — их синхронизированное зеркало (цвета, шрифты, spacing,
radius, тени, брейкпоинт сеток `960px` = `$bp-tablet` плагина). При правке
токенов держать это соответствие как инвариант — см. чек-лист в `tasks.md`,
Фаза 0.

## Как сделать релиз

Сборка релиза — на GitHub Actions (`.github/workflows/release.yml`), по
образцу того же workflow в плагине `fs-lms`. Запускается пушем тега вида
`vX.Y.Z`; сам процесс (lint → сверка версии → `npm run build:prod` → сборка
ZIP по `.distignore` → публикация GitHub Release) полностью в CI, руками
собирать ZIP не нужно.

1. Поднять версию в `style.css` (шапка темы, поле `Version:`) — тег должен
   **точно** совпасть с этим значением, иначе workflow упадёт на шаге
   `Verify version matches tag`.

   ```bash
   git add style.css
   git commit -m "Version 1.1.0"
   ```

2. Запушить коммит и создать тег с версией (с префиксом `v`):

   ```bash
   git push
   git tag v1.1.0
   git push origin v1.1.0
   ```

3. Дальше всё сама делает GitHub Actions: lint (`npm run lint:js`/`lint:css`),
   сверка версии из тега со `style.css`, прод-сборка ассетов, упаковка
   `fs-lms-theme-1.1.0.zip` (всё, что не в `.distignore` — то есть без
   `src/`, `node_modules/`, `refs/`, дев-конфигов) и публикация как GitHub
   Release с этим ZIP-архивом. Прогресс — во вкладке **Actions** репозитория.
4. Если релиз упал на гейте (lint/сборка/несовпадение версии) — тег уже
   запушен, но релиза не будет, пока ошибку не поправят. Поправить код,
   закоммитить, и либо перевыпустить тег заново (`git tag -d vX.Y.Z && git push origin :vX.Y.Z`,
   затем повторить шаг 2), либо взять следующий номер версии.
