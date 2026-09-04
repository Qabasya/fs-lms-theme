# BugFix
1. На страницах предмета поменять блоки "Открыть учебник" и "Открыть тренажер" в соответствии с дизайном: Use the claude_design MCP (https://api.anthropic.com/v1/design/mcp, auth via /design-login) to import this project:
   https://claude.ai/design/p/76381180-848a-43eb-be89-7071cd1c5f9a?file=%D0%95%D0%93%D0%AD+%D0%B8%D0%BD%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%82%D0%B8%D0%BA%D0%B0+-+%D0%BC%D0%BE%D0%BA%D0%B0%D0%BF.dc.html

Focus on these files (the whole project is readable):
- `ЕГЭ информатика - мокап.dc.html`

Also read these files the selection imports:
- `support.js`

Implement: `ЕГЭ информатика - мокап.dc.html`

2. Создать все 4 предмета и проверить доступность перехода к ним через страницу курсов. И новые цвета направлений: ОГЭ - violet, Робототехника - yellow. Учти это в бейджах и прочих моментах, связанных с направлениями
3. Сделать плавный скрол к якорям. При скролле к форме записи добавить автофокус на поле ввода имени (ФИО)
4. На мобильном устройстве карточки должны отображаться по 1 штуке на всю ширину. К примеру: вблоках fs-feature-card (1 на всю ширину на мобильном устройстве). Проверить адаптив шапки сайта
5. Заменить названия вузов на изображения. Временно везде поставить images/main_univer_5.png
6. Добавить возможность менять контент (текст и фото) блока "Как устроены занятия" на каждой странице направления
7. Поправить стили всех страниц woocommerce (скриншоты неправильного дизайна в папке images)
Привести к дизайну:
- корзина 
Use the claude_design MCP (https://api.anthropic.com/v1/design/mcp, auth via /design-login) to import this project:
  https://claude.ai/design/p/76381180-848a-43eb-be89-7071cd1c5f9a?file=%D0%9A%D0%BE%D1%80%D0%B7%D0%B8%D0%BD%D0%B0+-+%D0%BC%D0%BE%D0%BA%D0%B0%D0%BF.dc.html

Focus on these files (the whole project is readable):
- `Корзина - мокап.dc.html`

Also read these files the selection imports:
- `support.js`

Implement: `Корзина - мокап.dc.html`

- оформление заказа (ДОБАВИТЬ поля для фамилии имени отчетсва родителя, ребёнка, почты, убрать поле примечание к заказу)
  Use the claude_design MCP (https://api.anthropic.com/v1/design/mcp, auth via /design-login) to import this project:
  https://claude.ai/design/p/76381180-848a-43eb-be89-7071cd1c5f9a?file=%D0%9E%D1%84%D0%BE%D1%80%D0%BC%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5+%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0+-+%D0%BC%D0%BE%D0%BA%D0%B0%D0%BF.dc.html

Focus on these files (the whole project is readable):
- `Оформление заказа - мокап.dc.html`

Also read these files the selection imports:
- `support.js`

Implement: `Оформление заказа - мокап.dc.html`
8. Сделать липкую шапку: при пролистывании вниз она скрывается, при пролистывании вверх - появляется
9. Кнопка "Записаться" в шапке: если на странице нет формы записи, то кнопка ведёт на главную страницу якорем к форме. Если есть форма на текущей странице, то просто якорь к форме.
10. Убрать выпадающее меню Учебник и Тренажёр. Оставить их ссылками. Сделать еще 2 страницы: Учебник (/articles/) и Тренажёр (/tasks/). Вот дизайны (хедер и футер - наши!, только основной контент возьми):
    Use the claude_design MCP (https://api.anthropic.com/v1/design/mcp, auth via /design-login) to import this project:
    https://claude.ai/design/p/76381180-848a-43eb-be89-7071cd1c5f9a?file=%D0%A3%D1%87%D0%B5%D0%B1%D0%BD%D0%B8%D0%BA+-+%D0%BC%D0%BE%D0%BA%D0%B0%D0%BF.dc.html

Focus on these files (the whole project is readable):
- `Учебник - мокап.dc.html`

Also read these files the selection imports:
- `support.js`

Implement: `Учебник - мокап.dc.html`

Use the claude_design MCP (https://api.anthropic.com/v1/design/mcp, auth via /design-login) to import this project:
https://claude.ai/design/p/76381180-848a-43eb-be89-7071cd1c5f9a?file=%D0%A2%D1%80%D0%B5%D0%BD%D0%B0%D0%B6%D1%91%D1%80+-+%D0%BC%D0%BE%D0%BA%D0%B0%D0%BF.dc.html

Focus on these files (the whole project is readable):
- `Тренажёр - мокап.dc.html`

Also read these files the selection imports:
- `support.js`

Implement: `Тренажёр - мокап.dc.html`

11. В шапке убрать кнопку и пункт меню «Личный кабинет» (дублировали друг друга); кнопки CTA должны быть на одном уровне со ссылками навигации, а не переноситься на вторую строку
12. В блоках fs-hero-stats и fs-price-plaque выровнять текст по вертикали по центру
13. В футере весь текст сделать белым — адрес отображался тёмным (наследовал базовый цвет темы вместо белого)
14. У карточек каталога направлений (`/courses/`, `fs-course-catalog-card`) высота отличалась между рядами сетки (карточка ЕГЭ не совпадала по высоте с остальными) — выровнять
15. Кнопки «Все направления» должны вести на `/courses/`, а не на якорь `#dirs`
16. WooCommerce: убрать рейтинг и вкладку «Отзывы» на странице товара (как уже убран рейтинг в каталоге — отзывы никто не ведёт). Ссылки в хлебных крошках перекрасить в акцентный синий (сейчас серые — цвет из CSS самого плагина). Отступ слева у формы `cart` — баг не подтверждён, нужен скриншот/уточнение, что именно сдвинуто
17. Унифицировать вертикальные отступы между всеми секциями/блоками на всех страницах сайта (было вразнобой — 4.5rem/5.5rem/токены xxxl/xxxxl) — вынести величину в общие CSS-классы (`.fs-section`/`.fs-section-lead`/`.fs-main`), а не задавать инлайн-стилем в каждом паттерне отдельно
18. Заменить иконку корзины в шапке на иконку личного кабинета (человек), ссылка на `/profile/`
19. Добавить hover (потемнение до `accent-2-600`) всем кнопкам с оранжевым фоном (`accent-2`), как у `fs-course-card__button` — раньше не срабатывал из-за `!important` в `.has-accent-2-background-color`

---
1. Со страниц направлений убрать название предмета (h1 wp-block-post-title has-xxxl-font-size)