<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dm_Email_Marketing
 * @subpackage Dm_Email_Marketing/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Dm_Email_Marketing
 * @subpackage Dm_Email_Marketing/includes
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dm_Email_Marketing_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		$dotmailer_em_table_name = $wpdb->prefix . 'dotmailer_email_marketing';

		// @codingStandardsIgnoreStart
		$uid = $wpdb->get_var( "SELECT UID FROM $dotmailer_em_table_name" );
		// @codingStandardsIgnoreEnd
		wp_remote_post( "http://debug-tracking.dotmailer.internal/e/disable/woocommerce?uuid=$uid" );
	}
}
