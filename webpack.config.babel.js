import path from 'path';
import webpack from 'webpack';
import glob from 'glob';

const DIST_PATH = path.resolve('./dist/js');

const config = {
	cache: true,
	entry: {
		'podcasting-edit-post': './assets/js/podcasting-edit-post.js',
		'podcasting-edit-term': './assets/js/podcasting-edit-term.js',
		blocks: './assets/js/blocks.js'
	},
	output: {
		path: DIST_PATH,
		filename: '[name].min.js'
	},
	resolve: {
		modules: ['node_modules']
	},
	devtool: 'source-map',
	module: {
		rules: [
			{
				test: /\.js$/,
				enforce: 'pre',
				loader: 'eslint-loader',
				query: {
					configFile: './.eslintrc'
				}
			},
			{
				test: /\.js$/,
				exclude: /(node_modules|bower_components)/,
				use: [
					{
						loader: 'babel-loader',
						options: {
							babelrc: true
						}
					}
				]
			}
		]
	},
	mode: 'production',
	plugins: [
		new webpack.NoEmitOnErrorsPlugin()
	],
	externals: {
		jquery: 'jQuery',
		underscores: '_',
		window: 'window',
		wp: 'wp'
	},
	stats: {
		colors: true
	}

};

module.exports = config;
