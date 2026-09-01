# Кастомные блоки FS LMS Theme

Каждый блок — своя папка `src/blocks/<block-name>/`:

```
src/blocks/<block-name>/
├── block.json     # apiVersion 3, name: "fs-lms/<block-name>", textdomain: fs-lms-theme
├── index.js       # registerBlockType — точка входа для webpack
├── edit.js        # компонент редактора (JSX)
├── save.js        # статичный save (контент хранится в разметке поста)
├── style.scss     # стили: фронт + редактор
└── editor.scss    # стили только для редактора (по необходимости)
```

Точки входа собираются автоматически (`gulpfile.js` сканирует
`src/blocks/*/index.js` и `*/style.scss` через glob) — просто создать папку
по этой структуре, ничего не прописывать руками в `gulpfile.js`.

Сборка: `src/blocks/<name>/index.js` → `assets/js/blocks/<name>.min.js`,
`style.scss` → `assets/css/blocks/<name>.min.css`,
`editor.scss` → `assets/css/blocks/<name>-editor.min.css`.

Регистрация в PHP (`inc/Blocks.php`, Фаза 4) — по той же файловой структуре:
один `register_block_type()` на каждую папку с `block.json`, без хардкод-списка.

Список блоков и их назначение — см. `tasks.md`, Фаза 4.
