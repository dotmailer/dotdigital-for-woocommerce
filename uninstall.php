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

require 'includes/class-dotdigital-woocommerce-config.php';
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;

/**
 * Uninstall plugin tables and notify EC
 */
function dd_woocommerce_uninstall() {
	global $wpdb;

	$email_marketing_table_name = $wpdb->prefix . Dotdigital_WooCommerce_Config::EMAIL_MARKETING_TABLE_NAME;
	$subscribers_table_name     = $wpdb->prefix . Dotdigital_WooCommerce_Config::SUBSCRIBERS_TABLE_NAME;

	$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $email_marketing_table_name" ); // phpcs:ignore WordPress.DB

	$wpdb->query( "DROP TABLE IF EXISTS $email_marketing_table_name" ); // phpcs:ignore WordPress.DB
	$wpdb->query( "DROP TABLE IF EXISTS $subscribers_table_name" ); // phpcs:ignore WordPress.DB

	wp_remote_post( Dotdigital_WooCommerce_Config::TRACKING_URL . "/e/woocommerce/uninstall?pluginid=$plugin_id" );

	delete_option( Dotdigital_WooCommerce_Config::PLUGIN_VERSION );
	delete_option( Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_TEXT );
	delete_option( Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_SMS_TEXT );
	delete_option( Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_CHECKOUT );
	delete_option( Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_REGISTER );
	delete_option( Dotdigital_WooCommerce_Config::SITE_AND_ROI_TRACKING );
	delete_option( Dotdigital_WooCommerce_Config::REGION );
	delete_option( Dotdigital_WooCommerce_Config::WBT_PROFILE_ID_PATH );
	delete_option( Dotdigital_WooCommerce_Config::AC_STATUS_PATH );
	delete_option( Dotdigital_WooCommerce_Config::PROGRAM_ID_PATH );
	delete_option( Dotdigital_WooCommerce_Config::CART_DELAY_PATH );
	delete_option( Dotdigital_WooCommerce_Config::ALLOW_NON_SUBSCRIBERS_PATH );
	delete_option( Dotdigital_WooCommerce_Config::API_CREDENTIALS_PATH );
	delete_option( Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_CHECKOUT );
	delete_option( Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION );
	delete_option( Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_SMS_TEXT );
	delete_option( Dotdigital_WooCommerce_Config::MARKETING_CONSENT_SMS_TEXT );
	delete_option( Dotdigital_WooCommerce_Config::MARKETING_SMS_LISTS );
}

if ( ! is_multisite() ) {
	dd_woocommerce_uninstall();
} else {
	global $wpdb;
	try {
		foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ) as $dotdigital_woocommerce_blog_id ) {
			switch_to_blog( $dotdigital_woocommerce_blog_id );
			dd_woocommerce_uninstall();
		}
		restore_current_blog();
        // @codingStandardsIgnoreStart
    } catch ( \Exception $e ) {
	}
    // @codingStandardsIgnoreEnd
}
