# BugFix
1. расскажи, как мне менять фото fs-alumni-card__media fs-placeholder-tile (хочу это не в коде делать, а на сайте уже, через медиа файлы). Пока везде используй изображение img/alumni.png
2. Проверь эту карусель fs-alumni. Там должно зацикленно передвигать по одной карточке. На каком-то моменте происходит прыжок через несколько карточек сейчас 
3. И у меня снова при редактировании страницы на самом Wordpress редактор не может определить блоки и выдаёт "Этот блок имеет неожидаемое или неверное содержимое.". Почини это везде. Проверь синтаксис, как тут пишут https://wordpress.com/ru/support/wordpress-editor/block-error-unexpected-or-invalid-content/
4. Посмотри из плагина (/Users/daniil/FS-LMS/wordpress/wp-content/plugins/fs-lms) ограничения на поля с именем (кирилица, кол-во букв), давай и сюда добавим в формы, чтобы писали только на кириллице
5. Почему у нас нет разделения по паттернам блока "Как устроены занятия"? как мне поменять текст у других направлений?
6. Давай на главную последней секцией добавим частозадаваемые вопросы (faq) аккордеоном как на странице "О нас". Вот макет (на остальные блоки не смотри, только на последний): Use the claude_design MCP (https://api.anthropic.com/v1/design/mcp, auth via /design-login) to import this project:
   https://claude.ai/design/p/76381180-848a-43eb-be89-7071cd1c5f9a?file=%D0%93%D0%BB%D0%B0%D0%B2%D0%BD%D0%B0%D1%8F+v4+-+%D1%81%D0%B1%D0%BE%D1%80%D0%BA%D0%B0.dc.html

Focus on these files (the whole project is readable):
- `Главная v4 - сборка.dc.html`

Also read these files the selection imports:
- `support.js`

Implement: `Главная v4 - сборка.dc.html`
7. Добавь плавную анимацию открытия аккордеона (всем аккордеонам)