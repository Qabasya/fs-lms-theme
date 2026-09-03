/**
 * Семантические цвета для бейджей (fs-lms/course-card): один выбор в
 * инспекторе даёт пару «текст + мягкая подложка» из палитры theme.json,
 * а не два независимых пикера — так бейдж не собрать в нечитаемую пару
 * (например тёмный текст на тёмном фоне).
 *
 * `accent` — единственное исключение: его текстовый вариант на мягкой
 * подложке — `accent-700` (тот же приём, что уже используют существующие
 * паттерны — см. patterns/hero-split.php), у остальных семантических
 * цветов отдельного «700» токена в theme.json нет, поэтому текст = сам цвет.
 */

export const BADGE_COLORS = [
	{ name: 'Акцент', slug: 'accent' },
	{ name: 'Акцент 2', slug: 'accent-2' },
	{ name: 'Успех', slug: 'ok' },
	{ name: 'Ожидание', slug: 'wait' },
	{ name: 'Информация', slug: 'info' },
	{ name: 'Видео', slug: 'violet' },
	{ name: 'Практика', slug: 'practice' },
	{ name: 'Жёлтый', slug: 'subject-yellow-text' },
];

const TEXT_OVERRIDE = { accent: 'accent-700' };

/**
 * BugFix.4/6 (2026-09-03): `subject-yellow`/`subject-yellow-text` не
 * следуют конвенции `<slug>`/`<slug>-soft` остальной палитры (заведены
 * для пастельных подложек иконок направлений, см. `features-grid.php`,
 * не парой «насыщенный + `-soft`») — явный оверрайд подложки, чтобы
 * бейдж «Жёлтый» доставал пару из тех же двух токенов.
 */
const BG_OVERRIDE = { 'subject-yellow-text': 'subject-yellow' };

export function softSlug( slug ) {
	return BG_OVERRIDE[ slug ] || `${ slug }-soft`;
}

export function textSlug( slug ) {
	return TEXT_OVERRIDE[ slug ] || slug;
}
