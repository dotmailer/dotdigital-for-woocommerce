/// <reference types="cypress" />
const wpCypressPlugin = require('@bigbite/wp-cypress/lib/cypress-plugin');

module.exports = (on, config) => {
  // Configure WordPress settings
  config.baseUrl = process.env.CYPRESS_BASE_URL || 'http://localhost:8080';
  config.env = {
    ...config.env,
    wpUsername: process.env.CYPRESS_WP_USER || 'admin',
    wpPassword: process.env.CYPRESS_WP_PASSWORD || 'password'
  };

  // Initialize wp-cypress plugin
  return wpCypressPlugin(on, config);
};
