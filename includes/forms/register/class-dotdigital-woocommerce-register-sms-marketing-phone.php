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

/**
 * Class Dotdigital_WooCommerce_Checkout_Marketing_Checkbox
 */
class Dotdigital_WooCommerce_Register_Sms_Marketing_Phone {

	/**
	 * Renders the  collapsible input for the sms consent capture
	 */
	public function render() {
		$show = get_option(
			Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION,
			Dotdigital_WooCommerce_Config::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT
		);

		if ( ! $show ) {
			return;
		}

		woocommerce_form_field(
			Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME,
			array(
				'type'  => 'tel',
				'label' => '',
				'class' => array(
					'form-row-wide',
					DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_NAME . '-phone-input',
					'dd-phone-input',
				),
				'description' => get_option( Dotdigital_WooCommerce_Config::MARKETING_CONSENT_SMS_TEXT, '' ),
				'',
			),
			$_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME ] ?? '' // phpcs:ignore WordPress.Security
		);

		woocommerce_form_field(
			Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden',
			array(
				'type'  => 'hidden',
				'label' => '',
				'required' => true,
				'class' => array(
					DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_NAME . '-phone-input-hidden',
				),
				'validate' => array(
					'phone',
				),
			)
		);
	}

	/**
	 * Validates the marketing consent checkbox.
	 *
	 * @param string $username The username.
	 * @param string $email The email.
	 * @param array  $validation_errors The validation errors.
	 * @return void
	 */
	public function validate( $username, $email, $validation_errors ) {

		if ( ! empty( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_CHECKBOX_SMS_NAME ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$is_valid = filter_var(
				$_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ], // phpcs:ignore WordPress.Security
				FILTER_VALIDATE_BOOLEAN
			);

			if ( ! $is_valid ) {
				$validation_message = sprintf(
					'<strong>Marketing Consent</strong> %s',
					$_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ] // phpcs:ignore WordPress.Security
				);
				$validation_errors->add( 'validation', $validation_message );
			}
		}
	}

	/**
	 * Handles a subscription created via the WooCommerce checkout.
	 *
	 * @todo Implement this.
	 */
	public function handle_submit() {}
}
