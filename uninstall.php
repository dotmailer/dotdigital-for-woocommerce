<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    EngagementCloud
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

require_once plugin_dir_path( __FILE__ ) . 'engagement-cloud.php';

/**
 * Uninstall plugin tables and notify EC
 */
function ec_woocommerce_uninstall() {
	global $wpdb;

	$email_marketing_table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::EMAIL_MARKETING_TABLE_NAME;
	$subscribers_table_name     = $wpdb->prefix . Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME;

	$plugin_id = $wpdb->prepare( 'SELECT PluginID FROM %s', $email_marketing_table_name );

	$wpdb->prepare( 'DROP TABLE IF EXISTS %s', $email_marketing_table_name );
	$wpdb->prepare( 'DROP TABLE IF EXISTS %s', $subscribers_table_name );

	wp_remote_post( Engagement_Cloud_Bootstrapper::$callback_url . "/e/woocommerce/uninstall?pluginid=$plugin_id" );
}

ec_woocommerce_uninstall();
