<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dotmailer
 * @subpackage Dotmailer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Dotmailer
 * @subpackage Dotmailer/includes
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dotmailer_WooCommerce {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 *
	 * @param string $checkout The $checkout object.
	 */
	function dotmailer_render_accepts_marketing_input( $checkout ) {
		if ( is_user_logged_in( ) ) {
			// TODO: add checked attribute if get_the_author_meta( '_wc_subscribed_to_newsletter', get_current_user_id( ) ) === 'true'.
			echo '<p><label><input type="checkbox" name="dotmailer__accepts_marketing" />I accept marketing</label></p>';
		}
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 *
	 * @param type $order_id The id of the current order.
	 */
	function dotmailer_handle_accepts_marketing_input( $order_id ) {
		if ( is_user_logged_in() ) {
			$accepts_marketing = 'false';
			if ( ! empty( $_POST['dotmailer_accepts_marketing'] ) ) {
				$accepts_marketing = 'true';
			}
			// can swap out update_user_attribute but that is only available in WordPress VIP.
			update_user_meta( get_current_user_id( ), '_wc_subscribed_to_newsletter', $accepts_marketing );
		}
	}
}
