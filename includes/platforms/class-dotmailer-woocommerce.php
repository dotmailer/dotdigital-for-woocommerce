<?php
/**
 * Used for WooCommerce hooks.
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dotmailer
 * @subpackage Dotmailer/includes/platforms
 */

/**
 * Used for WooCommerce hooks.
 *
 * This class defines all code necessary to use WooCommerce hooks.
 *
 * @since      1.0.0
 * @package    Dotmailer
 * @subpackage Dotmailer/includes/platforms
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dotmailer_WooCommerce {

	/**
	 * Used to identify the checkbox.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $checkbox_name    Used to identify the checkbox.
	 */
	private $checkbox_name = 'dotmailer_marketing_checkbox';

	/**
	 * Text for the checkbox's label.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $checkbox_label    Text for the checkbox's label.
	 */
	private $checkbox_label = 'Subscribe to our newsletter';

	/**
	 * Key used to identify the value in the meta table.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $meta_key    Key used to identify the value in the meta table.
	 */
	private $meta_key = '_wc_subscribed_to_newsletter';

	/**
	 * Renders the checkbox in checkout page.
	 *
	 * @since    1.0.0
	 */
	function dotmailer_render_checkout_marketing_checkbox() {
		if ( is_user_logged_in( ) ) {
			woocommerce_form_field( $this->checkbox_name, array(
				'type'          => 'checkbox',
				'label'         => __( $this->checkbox_label ),
			), get_user_meta( get_current_user_id(), $this->meta_key, true ) );
		}
	}

	/**
	 * Handles the checkoutbox in checkout page.
	 *
	 * @since    1.0.0
	 */
	function dotmailer_handle_checkout_marketing_checkbox() {
		if ( is_user_logged_in() ) {
			$accepts_marketing = 0;
			if ( isset( $_POST[ $this->checkbox_name ] ) ) {
				$accepts_marketing = 1;
			}
			update_user_meta( get_current_user_id( ), $this->meta_key, $accepts_marketing );
		}
	}

	/**
	 * Renders the checkoutbox in registration page.
	 *
	 * @since    1.0.0
	 */
	function dotmailer_render_register_marketing_checkbox() {
		woocommerce_form_field( $this->checkbox_name, array(
			'type'          => 'checkbox',
			'label'         => __( $this->checkbox_label ),
		) );
	}

	/**
	 * Handles the checkoutbox in registration page.
	 *
	 * @since    1.0.0
	 *
	 * @param string $user_id The ID of new registree.
	 */
	function dotmailer_handle_register_marketing_checkbox( $user_id ) {
		$accepts_marketing = 0;
		if ( isset( $_POST[ $this->checkbox_name ] ) ) {
			$accepts_marketing = 1;
		}
		update_user_meta( $user_id, $this->meta_key, $accepts_marketing );
	}
}
