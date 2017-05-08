<?php
/**
 * Used for WooCommerce hooks.
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dotmailer
 * @subpackage Dotmailer/includes/woocommerce
 */

/**
 * Used for WooCommerce hooks.
 *
 * This class defines all code necessary to use WooCommerce hooks.
 *
 * @since      1.0.0
 * @package    Dotmailer
 * @subpackage Dotmailer/includes
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dotmailer_WooCommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dotmailer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	private $checkbox_name = 'dotmailer_marketing_checkbox';

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dotmailer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	private $checkbox_label = 'Subscribe to our newsletter';

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dotmailer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	private $meta_key = '_wc_subscribed_to_newsletter';

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	function dotmailer_render_checkout_marketing_checkbox() {
		if ( is_user_logged_in( ) ) {
			woocommerce_form_field( $this->checkbox_name, array(
				'type'          => 'checkbox',
				'class'         => array( $this->checkbox_name ),
				'label'         => __( $this->checkbox_label ),
			), get_user_meta( get_current_user_id(), $this->meta_key, true ) );
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
			if ( isset( $_POST[ $this->checkbox_name ] ) ) {
				$accepts_marketing = 1;
			}
			update_user_meta( get_current_user_id( ), $this->meta_key, $accepts_marketing );
		}
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	function dotmailer_render_register_marketing_checkbox() {
		woocommerce_form_field( $this->checkbox_name, array(
			'type'          => 'checkbox',
			'class'         => array( $this->checkbox_name ),
			'label'         => __( $this->checkbox_name ),
		) );
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
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
