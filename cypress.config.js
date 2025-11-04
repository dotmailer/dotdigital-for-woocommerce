const path = require('path');
const { defineConfig } = require('cypress');

module.exports = defineConfig({
	wordpressVersions: ['6.0.1'], // Add this line
	e2e: {
		baseUrl: 'http://localhost:3000',
		specPattern: 'cypress/**/*.spec.{js,jsx,ts,tsx}',
		supportFile: 'cypress/support/index.js',
		videosFolder: 'cypress/videos',
		screenshotsFolder: 'cypress/screenshots',
		fixturesFolder: 'cypress/fixtures',
		viewportWidth: 1280,
		viewportHeight: 720,
		defaultCommandTimeout: 10000,
		pageLoadTimeout: 30000,
		video: false
	},
	env: {
		wpUsername: 'admin',
		wpPassword: 'password',
		wpAdminPath: '/wp-admin',
		pluginsPath: path.resolve(__dirname),
		debug: false
	},
	wp: {
		version: ['6.0.1'],
		plugins: [
			path.resolve(__dirname)
		],
		port: 3000,
		seedsPath: 'cypress/seeds',
		adminUsername: 'admin'
	},
	watchForFileChanges: false,
	chromeWebSecurity: false,
	requestTimeout: 10000,
	responseTimeout: 30000,
	numTestsKeptInMemory: 5
});
