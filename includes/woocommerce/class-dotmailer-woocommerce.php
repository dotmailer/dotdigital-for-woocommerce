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
	function dotmailer_render_checkout_marketing_checkbox( $checkout ) {
		if ( is_user_logged_in( ) ) {
			echo '<div id="dotmailer_checkout_marketing_field">';

			woocommerce_form_field( 'dotmailer_marketing_checkbox', array(
				'type'          => 'checkbox',
				'class'         => array( 'dotmailer-marketing-checkbox' ),
				'label'         => __( 'Subscribe to our newsletter' ),
			), get_user_meta( get_current_user_id(), '_wc_subscribed_to_newsletter', true ) );

			echo '</div>';
		}
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	function dotmailer_handle_checkout_marketing_checkbox() {
		if ( is_user_logged_in() ) {
			$accepts_marketing = 0;
			if ( isset( $_POST['dotmailer_marketing_checkbox'] ) ) {
				$accepts_marketing = 1;
			}
			update_user_meta( get_current_user_id( ), '_wc_subscribed_to_newsletter', $accepts_marketing );
		}
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 *
	 * @param string $checkout The $checkout object.
	 */
	function dotmailer_render_register_marketing_checkbox( $checkout ) {
		echo '<div id="dotmailer_checkout_marketing_field">';

		woocommerce_form_field( 'dotmailer_marketing_checkbox', array(
			'type'          => 'checkbox',
			'class'         => array( 'dotmailer-marketing-checkbox' ),
			'label'         => __( 'Subscribe to our newsletter' ),
		), get_user_meta( get_current_user_id(), '_wc_subscribed_to_newsletter', true ) );

		echo '</div>';
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 *
	 * @param string $username The ID of new registree.
	 */
	function dotmailer_handle_register_marketing_checkbox( $user_id ) {
		$accepts_marketing = 0;
		if ( isset( $_POST['dotmailer_marketing_checkbox'] ) ) {
			$accepts_marketing = 1;
		}
		update_user_meta( get_user_by( 'ID', $user_id )->ID, '_wc_subscribed_to_newsletter', $accepts_marketing );
	}
}
