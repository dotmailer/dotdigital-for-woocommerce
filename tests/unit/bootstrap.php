<?php
/**
 * The following snippets uses `PLUGIN` to prefix
 * the constants and class names. You should replace
 * it with something that matches your plugin name.
 */
// define test environment
define( 'EC_WOO_PHPUNIT', true );

// define fake ABSPATH
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', sys_get_temp_dir() . '/wordpress/' );
}
// define fake WPINC
if ( ! defined( 'WPINC' ) ) {
	define( 'WPINC', sys_get_temp_dir() . '/wordpress/wp-includes/' );
}
// define fake WPINC
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', sys_get_temp_dir() . '/wordpress/wp-content/' );
}
// define fake PLUGIN_ABSPATH
if ( ! defined( 'EC_WOO_ABSPATH' ) ) {
	define( 'EC_WOO_ABSPATH', sys_get_temp_dir() . '/wp-content/plugins/engagement-cloud-for-woocommerce/' );
}

require_once __DIR__ . '/../../vendor/autoload.php';

// Include the class for PluginTestCase
require_once __DIR__ . '/inc/PluginTestCase.php';

// Since our plugin files are loaded with composer, we should be good to go

//require_once __DIR__ . '/../../engagement-cloud.php';
