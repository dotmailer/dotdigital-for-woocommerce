<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dm_Email_Marketing
 * @subpackage Dm_Email_Marketing/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @subpackage Dm_Email_Marketing/includes
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dm_Email_Marketing_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$url = 'https://192.168.100.254/ecommerce/register-wp-plugin';
		$cart_id;

		if ( class_exists( 'WooCommerce' ) ) {
			$cart_id = 'Woocommerce';
		} else {
			die();
		}

		echo 'Cart name: $cart_id';

		//$response = wp_remote_post( $url );

	}
}
