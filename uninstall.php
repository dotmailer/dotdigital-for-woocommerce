<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    EngagementCloud
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'engagement-cloud.php';

global $wpdb;

$engagement_cloud_table_name = $wpdb->prefix . "dotmailer_email_marketing";

// @codingStandardsIgnoreStart
$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $engagement_cloud_table_name" );
$wpdb->query( "DROP TABLE IF EXISTS $engagement_cloud_table_name" );
// @codingStandardsIgnoreEnd
wp_remote_post( Engagement_Cloud_Bootstrapper::$callback_url . "/e/woocommerce/uninstall?pluginid=$plugin_id" );
