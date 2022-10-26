const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
    ...defaultConfig,
	output: {
		...defaultConfig.output,
		path: path.resolve( process.cwd(), 'dist' ),
	},
    entry: {
        'blocks': path.resolve( process.cwd(), 'assets/js', 'blocks.js' ),
        'podcasting-edit-post': path.resolve( process.cwd(), 'assets/js', 'podcasting-edit-post.js' ),
        'podcasting-edit-term': path.resolve( process.cwd(), 'assets/js', 'podcasting-edit-term.js' ),
        'podcasting-onboarding': path.resolve( process.cwd(), 'assets/js', 'onboarding.js' ),
    }
};
