<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    Dotdigital_WooCommerce
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

require 'class-dotdigital-woocommerce-bootstrapper.php';
use Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper;

/**
 * Uninstall plugin tables and notify EC
 */
function dd_woocommerce_uninstall() {
	global $wpdb;

	$email_marketing_table_name = $wpdb->prefix . Dotdigital_WooCommerce_Bootstrapper::EMAIL_MARKETING_TABLE_NAME;
	$subscribers_table_name     = $wpdb->prefix . Dotdigital_WooCommerce_Bootstrapper::SUBSCRIBERS_TABLE_NAME;

	$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $email_marketing_table_name" ); // phpcs:ignore WordPress.DB

	$wpdb->query( "DROP TABLE IF EXISTS $email_marketing_table_name" ); // phpcs:ignore WordPress.DB
	$wpdb->query( "DROP TABLE IF EXISTS $subscribers_table_name" ); // phpcs:ignore WordPress.DB

	wp_remote_post( Dotdigital_WooCommerce_Bootstrapper::$tracking_url . "/e/woocommerce/uninstall?pluginid=$plugin_id" );

	delete_option( 'dotdigital_for_woocommerce_version' );
	delete_option( 'dotdigital_for_woocommerce_settings_show_marketing_checkbox_at_checkout' );
	delete_option( 'dotdigital_for_woocommerce_settings_show_marketing_checkbox_at_register' );
	delete_option( 'dotdigital_for_woocommerce_settings_marketing_checkbox_text' );
	delete_option( 'dotdigital_for_woocommerce_settings_enable_site_and_roi_tracking' );
	delete_option( 'dotdigital_for_woocommerce_settings_region' );
}

dd_woocommerce_uninstall();
