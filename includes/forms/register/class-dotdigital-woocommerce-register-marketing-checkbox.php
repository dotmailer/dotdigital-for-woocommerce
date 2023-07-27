<?php
/**
 * Used for marketing consent on register.
 *
 * @since      1.4.0
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/forms/register
 * @author     dotdigital <integrations@dotdigital.com>
 */

namespace Dotdigital_WooCommerce\Includes\Forms\Register;

use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Subscriber;

/**
 * Class Dotdigital_WooCommerce_Register_Marketing_Checkbox
 */
class Dotdigital_WooCommerce_Register_Marketing_Checkbox {

	/**
	 * Used to identify the checkbox.
	 *
	 * @access   private
	 * @var      string    $checkbox_name    Used to identify the checkbox.
	 */
	private $checkbox_name = 'dotdigital_woocommerce_checkbox';

	/**
	 * Renders the checkbox in registration page.
	 */
	public function render() {

		$show_checkbox = get_option(
			Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_REGISTER,
			Dotdigital_WooCommerce_Config::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_REGISTER
		);

		if ( ! $show_checkbox ) {
			return;
		}

		woocommerce_form_field(
			$this->checkbox_name,
			array(
				'type'  => 'checkbox',
				'label' => get_option(
					Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_TEXT,
					Dotdigital_WooCommerce_Config::DEFAULT_MARKETING_CHECKBOX_TEXT
				),
			)
		);
	}

	/**
	 * Handles the checkoutbox in registration page.
	 *
	 * @param string $user_id The ID of new registree.
	 */
	public function handle_submit( $user_id ) {
		$nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? wp_unslash( $_POST['woocommerce-register-nonce'] ) : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( isset( $nonce_value, $_POST['email'] ) && wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
			if ( isset( $_POST[ $this->checkbox_name ] ) ) {
				$email = isset( $_POST['email'] ) ?
					sanitize_email( wp_unslash( $_POST['email'] ) ) :
					'';

				$data = array(
					'user_id'    => $user_id,
					'email'      => $email,
					'status'     => 1,
				);

				$subscriber = new Dotdigital_WooCommerce_Subscriber();
				$subscriber->create_or_update( $data );
			}
		}
	}
}
