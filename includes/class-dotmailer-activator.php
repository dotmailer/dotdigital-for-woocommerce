<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dotmailer
 * @subpackage Dotmailer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @subpackage Dotmailer/includes
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dotmailer_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$dotmailer_table_name = $wpdb->prefix . 'dotmailer';

		// @codingStandardsIgnoreStart
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $dotmailer_table_name ) ) !== $dotmailer_table_name ) {
			// @codingStandardsIgnoreEnd
			$charset_collate = $wpdb -> get_charset_collate();

			$sql = "CREATE TABLE $dotmailer_table_name (
          		PluginID VARCHAR(256) NOT NULL
     		) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		// @codingStandardsIgnoreStart
		$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $dotmailer_table_name" );

		if ( null === $plugin_id ) {
			$length = 128;
			$crypto_strong = true;

			$wpdb->insert( $dotmailer_table_name, array(
				'PluginID' => bin2hex( openssl_random_pseudo_bytes( $length, $crypto_strong ) ),
			));

			$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $dotmailer_table_name" );
			// @codingStandardsIgnoreEnd
		}

		wp_remote_post( "http://debug-tracking.dotmailer.internal/e/enable/woocommerce?pluginid=$plugin_id" );
	}
}
