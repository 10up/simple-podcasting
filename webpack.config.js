const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const CopyPlugin = require('copy-webpack-plugin');
const path = require('path');

module.exports = {
	...defaultConfig,
	output: {
		...defaultConfig.output,
		path: path.resolve(process.cwd(), 'dist'),
	},
	entry: {
		blocks: path.resolve(process.cwd(), 'assets/js', 'blocks.js'),
		'podcasting-edit-post': path.resolve(
			process.cwd(),
			'assets/js',
			'podcasting-edit-post.js'
		),
		'podcasting-edit-term': path.resolve(
			process.cwd(),
			'assets/js',
			'podcasting-edit-term.js'
		),
		'podcast-platforms-block': path.resolve(
			process.cwd(),
			'assets/js/blocks/podcast-platforms',
			'index.js'
		),
		'podcasting-onboarding': path.resolve(
			process.cwd(),
			'assets/js',
			'onboarding.js'
		),
		'podcasting-transcript': path.resolve(
			process.cwd(),
			'includes/blocks/podcast-transcript',
			'index.js'
		),
		'create-podcast-show': path.resolve(
			process.cwd(),
			'assets/js',
			'create-podcast-show.js'
		),
		'latest-episode': path.resolve(
			process.cwd(),
			'assets/js/blocks/latest-episode',
			'index.js'
		),
	},
	plugins: [
		...defaultConfig.plugins,
		new CopyPlugin({
			patterns: [
				{ from: 'assets/images/*', to: './' },
				{ from: 'assets/images/icons', to: './images/icons' },
			],
		}),
	],
};
