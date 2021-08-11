module.exports = {
	...require( '@wordpress/scripts/config/jest-e2e.config' ),
	setupFilesAfterEnv: [
		'@wordpress/jest-console',
		'expect-puppeteer',
		'puppeteer-testing-library/extend-expect',
	],
	testTimeout: 120000,
};
