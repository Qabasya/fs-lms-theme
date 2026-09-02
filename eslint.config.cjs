const js = require( '@eslint/js' );
const wp = require( '@wordpress/eslint-plugin' );

module.exports = [
	{
		ignores: [ 'node_modules/**', 'build/**', 'assets/**', '**/*.min.js' ],
	},

	{
		files: [ 'src/js/**/*.js' ],

		...js.configs.recommended,

		languageOptions: {
			sourceType: 'module',
		},

		rules: {
			'no-console': 'off',
			'no-alert': 'error',
		},
	},

	{
		files: [ 'src/blocks/**/*.js' ],

		...js.configs.recommended,

		plugins: {
			'@wordpress': wp,
		},

		languageOptions: {
			sourceType: 'module',
			parserOptions: {
				ecmaFeatures: { jsx: true },
			},
		},

		rules: {
			...wp.configs.recommended.rules,
			'no-console': 'off',
		},
	},
];
