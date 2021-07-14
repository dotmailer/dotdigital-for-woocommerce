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

require_once plugin_dir_path( __FILE__ ) . 'class-engagement-cloud-bootstrapper.php';

/**
 * Uninstall plugin tables and notify EC
 */
function ec_woocommerce_uninstall() {
	global $wpdb;

	$email_marketing_table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::EMAIL_MARKETING_TABLE_NAME;
	$subscribers_table_name     = $wpdb->prefix . Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME;

	$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $email_marketing_table_name" ); // phpcs:ignore WordPress.DB

	$wpdb->query( "DROP TABLE IF EXISTS $email_marketing_table_name" ); // phpcs:ignore WordPress.DB
	$wpdb->query( "DROP TABLE IF EXISTS $subscribers_table_name" ); // phpcs:ignore WordPress.DB

	wp_remote_post( Engagement_Cloud_Bootstrapper::$tracking_url . "/e/woocommerce/uninstall?pluginid=$plugin_id" );

	delete_option( 'engagement_cloud_for_woocommerce_version' );
}

ec_woocommerce_uninstall();
