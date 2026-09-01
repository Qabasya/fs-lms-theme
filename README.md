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

src/
  scss/                theme.scss (фронт), editor.scss (редактор)
  js/                  theme.js (фронт-интерактив)
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

## Как собрать новую страницу

Страницы в теме не верстаются шаблоном под каждый случай — редактор собирает
их в Site Editor из готовых паттернов (`patterns/`), как в WoodMart, но на
нативных блоках.

1. **Страницы → Добавить новую**, шаблон — `templates/page.html` (заголовок +
   контент, `contentSize: 720px`) подходит для большинства текстовых страниц.
   Если нужна полноширинная секционная вёрстка (как на главной) — завести
   отдельный FSE-шаблон в `templates/` по образцу `front-page.html`
   (`wp:template-part` шапка → `wp:pattern` секции → `wp:template-part`
   футер).
2. В контенте вставлять паттерны из инсёртера (категория `fs-lms-sections`) —
   например `faq` для блока частых вопросов/аккордеона (чистый CSS-аккордеон
   на `fs-lms/faq-item`, без JS), `cta-banner`, `testimonials` и т.д. Если
   готового паттерна под нужную секцию нет — см. «Как добавить новый
   паттерн» выше.
3. Текст/картинки редактируются прямо в блоках через инспектор — паттерны
   Фазы 5 статичны (без `WP_Query`/REST к данным плагина, см. `tasks.md`),
   контент вписывается вручную.
4. Если на странице нужна ссылка на функциональную страницу плагина
   (запись на занятие, вход, профиль) — использовать
   `fs_lms_theme_url( 'apply' | 'sign-in' | 'profile' )`, не хардкод-URL.
5. Добавить пункт меню на новую страницу в `patterns/header-nav.php` (там
   есть заглушки `#` под пункты без реальной страницы) или подключить через
   `wp:navigation-link` в редакторе.
6. Каталог товаров WooCommerce в теме пока не собирался — WooCommerce нигде
   не подключён (нет `add_theme_support('woocommerce')`, нет шаблонов
   `woocommerce/`). Для такой страницы сначала добавить эту поддержку в
   `inc/Setup.php`, затем собирать блоком «Товары» (`Products`/`All Products
   Block`) из WooCommerce — он сам тянет реальные `WC_Product` без
   кастомного кода.

## Где искать токены

Единственный источник дизайн-токенов — `fs-lms` plugin
`src/scss/frontend/_variables.scss` + `src/scss/shared/_tokens.scss`.
`theme.json` — их синхронизированное зеркало (цвета, шрифты, spacing,
radius, тени, брейкпоинт сеток `960px` = `$bp-tablet` плагина). При правке
токенов держать это соответствие как инвариант — см. чек-лист в `tasks.md`,
Фаза 0.
