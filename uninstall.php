<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dotmailer
 */

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
wp_remote_post( Dotmailer_Bootstrapper::$callback_url . "/e/woocommerce/uninstall?pluginid=$plugin_id" );
