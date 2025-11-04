module.exports = {
	baseUrl: "http://localhost:3000",
	version: '1.0.0',
	pluginFile: 'ec-woocommerce.php',
	wpPath: '../../../',
	cypress: {
		env: {
			wpUsername: 'admin',
			wpPassword: 'password',
			wpAdminPath: '/wp-admin'
		}
	}
};
