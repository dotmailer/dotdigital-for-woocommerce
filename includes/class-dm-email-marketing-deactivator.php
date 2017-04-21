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

		$dotmailer_em_store_url = get_bloginfo( 'wpurl' );
		$dotmailer_em_config_path = ABSPATH . '/bridge2cart/config.php';

		if ( is_file( $dotmailer_em_config_path ) && is_readable( $dotmailer_em_config_path ) ) {

			require $dotmailer_em_config_path;

			wp_remote_post( "http://debug-tracking.dotmailer.internal/e/disable/woocommerce?storeurl=$dotmailer_em_store_url&storekey=" . M1_TOKEN );
		}
	}
}
