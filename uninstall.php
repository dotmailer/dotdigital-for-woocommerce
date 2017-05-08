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
 * @package    Dotmailer
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
require_once plugin_dir_path( __FILE__ ) . 'dotmailer.php';

global $wpdb;

$dotmailer_table_name = $wpdb->prefix . Dotmailer_Bootstrapper::$plugin_name;

// @codingStandardsIgnoreStart
$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $dotmailer_table_name" );
$wpdb->query( "DROP TABLE IF EXISTS $dotmailer_table_name" );
// @codingStandardsIgnoreEnd
wp_remote_post( Dotmailer_Bootstrapper::$tracking_site_url . "/e/woocommerce/uninstall?pluginid=$plugin_id" );
