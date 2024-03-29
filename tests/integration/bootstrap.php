<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package Dotdigital_WooCommerce
 */

if ( PHP_MAJOR_VERSION >= 8 ) {
	echo "The scaffolded tests cannot currently be run on PHP 8.0+. See https://github.com/wp-cli/scaffold-command/issues/285" . PHP_EOL;
	exit( 1 );
}

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( "{$_tests_dir}/includes/functions.php" ) ) {
	echo "Could not find {$_tests_dir}/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL;
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once "{$_tests_dir}/includes/functions.php";

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( dirname( __FILE__ ) ) ) . '/class-dotdigital-woocommerce-bootstrapper.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Require Yoast PHPUnit Polyfills library
require dirname( dirname( dirname( __FILE__ ) ) ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';

// Start up the WP testing environment.
require "{$_tests_dir}/includes/bootstrap.php";
