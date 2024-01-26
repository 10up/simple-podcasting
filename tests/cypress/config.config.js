const { defineConfig } = require('cypress');

module.exports = defineConfig({
	fixturesFolder: __dirname + '/fixtures',
	screenshotsFolder: __dirname + '/screenshots',
	videosFolder: __dirname + '/videos',
	downloadsFolder: __dirname + '/downloads',
	video: true,
	reporter: 'mochawesome',
	reporterOptions: {
		mochaFile: 'mochawesome-[name]',
		reportDir: __dirname + '/reports',
		overwrite: false,
		html: false,
		json: true,
	},
	chromeWebSecurity: false,
	env: {
		HAS_BLOCK_EDITOR: true,
	},
	e2e: {
		// We've imported your old cypress plugins here.
		// You may want to clean this up later by importing these.
		setupNodeEvents(on, config) {
			return require(__dirname + '/plugins/index.js')(on, config);
		},
		supportFile: __dirname + '/support/index.js',
		specPattern: [
			'tests/cypress/integration/admin.test.js',
			'tests/cypress/integration/onboarding.test.js',
			'tests/cypress/integration/taxonomy.test.js',
			'tests/cypress/integration/block.test.js',
			'tests/cypress/integration/podcast-setting-panel.test.js',
		],
	},
});
