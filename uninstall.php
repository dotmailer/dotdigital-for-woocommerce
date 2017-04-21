<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dm_Email_Marketing
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$dotmailer_em_store_url = get_bloginfo( 'wpurl' );
$dotmailer_em_config_path = ABSPATH . '/bridge2cart/config.php';

if ( is_file( $dotmailer_em_config_path ) && is_readable( $dotmailer_em_config_path ) ) {

	require $dotmailer_em_config_path;

	wp_remote_post( "http://debug-tracking.dotmailer.internal/e/uninstall/woocommerce?storeurl=$dotmailer_em_store_url&storekey=" . M1_TOKEN );
}
