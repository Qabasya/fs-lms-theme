/**
 * Gulp configuration for FS LMS Theme
 *
 * Та же схема, что и в плагине fs-lms (см. fs-lms/gulpfile.js): gulp гоняет
 * SCSS через dart-sass+postcss, JS — через webpack-stream (ES-модули,
 * Babel). Дополнительно к плагину — сборка кастомных Gutenberg-блоков
 * (src/blocks/<name>/{index.js,style.scss,editor.scss}), у которых число и
 * состав меняется по ходу разработки, поэтому точки входа блоков собираются
 * динамически через glob, а не прописываются руками.
 */

const path = require('node:path');
const { PassThrough } = require('node:stream');
const { globSync } = require('glob');

const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const sourcemaps = require('gulp-sourcemaps');
const notify = require('gulp-notify');
const plumber = require('gulp-plumber');
const rename = require('gulp-rename');

const webpack = require('webpack-stream');
const webpackCompiler = require('webpack');
const named = require('vinyl-named');

/**
 * Прод-режим — `npm run build:prod` (`gulp build --production`, см.
 * package.json). Финальная сборка без sourcemaps (Фаза 8 QA); обычный
 * `npm run build`/`npm run watch` для разработки sourcemaps пишет как раньше.
 */
const isProduction = process.argv.includes('--production');

/**
 * ПУТИ
 */
const paths = {
	scss: {
		theme: './src/scss/theme.scss',
		editor: './src/scss/editor.scss',
		watch: './src/scss/**/*.scss',
	},
	js: {
		theme: './src/js/theme.js',
		watch: './src/js/**/*.js',
	},
	blocks: {
		root: './src/blocks',
		editIndex: './src/blocks/*/index.js',
		style: './src/blocks/*/style.scss',
		editorStyle: './src/blocks/*/editor.scss',
		watchJs: './src/blocks/**/*.js',
		watchScss: './src/blocks/**/*.scss',
	},
	output: {
		css: './assets/css/',
		js: './assets/js/',
		maps: './maps/',
	},
};

/**
 * Внешние зависимости кастомных блоков.
 *
 * WordPress уже грузит React/`wp.*` глобалами на странице редактора —
 * бандлить их вторично не нужно (и нельзя: два экземпляра React в одном
 * контексте ломают редактор). Ключ — имя npm-пакета/import specifier,
 * значение — глобальная переменная, под которой его отдаёт WP.
 */
const wpExternals = {
	react: 'React',
	'react-dom': 'ReactDOM',
	'@wordpress/blocks': 'wp.blocks',
	'@wordpress/block-editor': 'wp.blockEditor',
	'@wordpress/components': 'wp.components',
	'@wordpress/element': 'wp.element',
	'@wordpress/i18n': 'wp.i18n',
	'@wordpress/data': 'wp.data',
};

/**
 * Babel-правило для JS (обычный фронт-скрипт: ES6+ модули, без JSX)
 */
const babelRuleJs = {
	test: /\.js$/,
	type: 'javascript/auto',
	exclude: /node_modules/,
	use: {
		loader: 'babel-loader',
		options: {
			presets: [ [ '@babel/preset-env', { modules: false } ] ],
			sourceType: 'module',
		},
	},
};

/**
 * Babel-правило для блоков (JSX в edit.js)
 */
const babelRuleBlocks = {
	test: /\.js$/,
	type: 'javascript/auto',
	exclude: /node_modules/,
	use: {
		loader: 'babel-loader',
		options: {
			presets: [
				[ '@babel/preset-env', { modules: false } ],
				// classic pragma → wp.element.createElement (не react/jsx-runtime,
				// которого нет среди externals): каждый JSX-файл блока должен
				// импортировать { createElement, Fragment } из '@wordpress/element'.
				[ '@babel/preset-react', { pragma: 'createElement', pragmaFrag: 'Fragment' } ],
			],
			sourceType: 'module',
		},
	},
};

/**
 * Webpack: обычный фронт-бандл темы (без внешних wp.* зависимостей —
 * theme.js не завязан на React/редактор).
 */
const webpackConfig = {
	mode: 'production',
	module: { rules: [ babelRuleJs ] },
	resolve: { extensions: [ '.js', '.json' ] },
	output: { filename: '[name].min.js' },
	devtool: isProduction ? false : 'source-map',
};

/**
 * Webpack: конфиг для кастомных блоков — JSX + externals на wp.*.
 */
const webpackBlocksConfig = {
	mode: 'production',
	module: { rules: [ babelRuleBlocks ] },
	resolve: { extensions: [ '.js', '.json' ] },
	externals: wpExternals,
	devtool: isProduction ? false : 'source-map',
};

const errorHandler = function ( err ) {
	notify.onError( {
		title: 'Gulp error in ' + err.plugin,
		message: err.toString(),
	} )( err );
	this.emit( 'end' );
};

/**
 * Watch-режим: watcher должен пережить опечатку в SCSS/JS, иначе после
 * первой же ошибки перестают собираться все бандлы до ручного перезапуска
 * (см. тот же приём в fs-lms/gulpfile.js).
 */
let watching = false;

function guard() {
	return watching ? plumber( { errorHandler } ) : new PassThrough( { objectMode: true } );
}

/**
 * Прод-режим не пишет sourcemaps вообще (не просто пустой шаг — иначе
 * cssnano всё равно вставит `/*# sourceMappingURL` комментарий в файл).
 */
function maybeSourcemapsInit() {
	return isProduction ? new PassThrough( { objectMode: true } ) : sourcemaps.init();
}

function maybeSourcemapsWrite() {
	return isProduction ? new PassThrough( { objectMode: true } ) : sourcemaps.write( paths.output.maps );
}

/**
 * ОБРАБОТКА CSS — фронт темы
 */
function stylesTheme() {
	return gulp.src( paths.scss.theme )
		.pipe( guard() )
		.pipe( maybeSourcemapsInit() )
		.pipe( sass() )
		.pipe( postcss( [ autoprefixer(), cssnano() ] ) )
		.pipe( rename( 'theme.min.css' ) )
		.pipe( maybeSourcemapsWrite() )
		.pipe( gulp.dest( paths.output.css ) );
}

/**
 * ОБРАБОТКА CSS — стили внутри редактора (editor-styles-wrapper)
 */
function stylesEditor() {
	return gulp.src( paths.scss.editor )
		.pipe( guard() )
		.pipe( maybeSourcemapsInit() )
		.pipe( sass() )
		.pipe( postcss( [ autoprefixer(), cssnano() ] ) )
		.pipe( rename( 'editor.min.css' ) )
		.pipe( maybeSourcemapsWrite() )
		.pipe( gulp.dest( paths.output.css ) );
}

/**
 * ОБРАБОТКА CSS — стили блоков (style.scss → фронт+редактор, editor.scss →
 * только редактор). allowEmpty: пока в src/blocks/ нет ни одного блока
 * (Фаза 1), таск не должен падать на пустом glob.
 */
function stylesBlocksFront() {
	return gulp.src( paths.blocks.style, { base: paths.blocks.root, allowEmpty: true } )
		.pipe( guard() )
		.pipe( maybeSourcemapsInit() )
		.pipe( sass() )
		.pipe( postcss( [ autoprefixer(), cssnano() ] ) )
		.pipe( rename( ( file ) => {
			const blockName = file.dirname; // gulp.src base — src/blocks, значит dirname == имя папки блока
			file.dirname = '.';
			file.basename = blockName + '.min';
		} ) )
		.pipe( maybeSourcemapsWrite() )
		.pipe( gulp.dest( paths.output.css + 'blocks/' ) );
}

function stylesBlocksEditor() {
	return gulp.src( paths.blocks.editorStyle, { base: paths.blocks.root, allowEmpty: true } )
		.pipe( guard() )
		.pipe( maybeSourcemapsInit() )
		.pipe( sass() )
		.pipe( postcss( [ autoprefixer(), cssnano() ] ) )
		.pipe( rename( ( file ) => {
			const blockName = file.dirname;
			file.dirname = '.';
			file.basename = blockName + '-editor.min';
		} ) )
		.pipe( maybeSourcemapsWrite() )
		.pipe( gulp.dest( paths.output.css + 'blocks/' ) );
}

/**
 * ОБРАБОТКА JS — фронт темы (theme.js: мобильное меню, FAQ-аккордеон и т.п.)
 */
function scriptsTheme() {
	return gulp.src( paths.js.theme )
		.pipe( guard() )
		.pipe( named() )
		.pipe( webpack( webpackConfig ) )
		.pipe( maybeSourcemapsWrite() )
		.pipe( gulp.dest( paths.output.js ) )
		.pipe( notify( { message: 'Theme JS processed!', onLast: true } ) );
}

/**
 * ОБРАБОТКА JS — кастомные блоки.
 *
 * Каждая папка src/blocks/<name>/index.js — отдельная точка входа →
 * assets/js/blocks/<name>.min.js. Список папок собирается через glob при
 * каждом запуске таска, руками ничего не прописываем: добавил папку блока —
 * она подхватилась.
 */
function findBlockEntries() {
	const entries = {};
	for ( const file of globSync( paths.blocks.editIndex ) ) {
		const blockName = path.basename( path.dirname( file ) );
		entries[ blockName ] = path.resolve( file );
	}
	return entries;
}

function scriptsBlocks( done ) {
	const entry = findBlockEntries();

	if ( Object.keys( entry ).length === 0 ) {
		console.log( 'scripts:blocks — в src/blocks/ пока нет ни одного блока, пропускаю.' );
		done();
		return;
	}

	webpackCompiler( {
		...webpackBlocksConfig,
		entry,
		output: {
			path: path.resolve( __dirname, paths.output.js, 'blocks' ),
			filename: '[name].min.js',
		},
	}, ( err, stats ) => {
		if ( err ) {
			errorHandler.call( { emit: () => {} }, err );
			done( watching ? undefined : err );
			return;
		}
		if ( stats.hasErrors() ) {
			const err2 = new Error( stats.toString( { errorDetails: true } ) );
			err2.plugin = 'webpack (blocks)';
			errorHandler.call( { emit: () => {} }, err2 );
			done( watching ? undefined : err2 );
			return;
		}
		console.log( stats.toString( { colors: true, chunks: false } ) );
		done();
	} );
}

/**
 * WATCHER
 */
function watchFiles() {
	watching = true;
	gulp.watch( paths.scss.watch, gulp.parallel( stylesTheme, stylesEditor ) );
	gulp.watch( [ paths.blocks.watchScss ], gulp.parallel( stylesBlocksFront, stylesBlocksEditor ) );
	gulp.watch( paths.js.watch, scriptsTheme );
	gulp.watch( [ paths.blocks.watchJs ], scriptsBlocks );
	console.log( 'Gulp is watching and building fs-lms-theme assets...' );
}

const build = gulp.parallel( stylesTheme, stylesEditor, stylesBlocksFront, stylesBlocksEditor, scriptsTheme, scriptsBlocks );

exports[ 'styles:theme' ] = stylesTheme;
exports[ 'styles:editor' ] = stylesEditor;
exports[ 'styles:blocks' ] = gulp.parallel( stylesBlocksFront, stylesBlocksEditor );
exports[ 'scripts:theme' ] = scriptsTheme;
exports[ 'scripts:blocks' ] = scriptsBlocks;
exports.build = build;
exports.watch = watchFiles;
exports.default = gulp.series( build, watchFiles );
