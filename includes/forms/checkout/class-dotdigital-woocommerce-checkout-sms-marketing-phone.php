<?php
/**
 * Used for marketing consent on checkout.
 *
 * @since      1.4.0
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/forms/checkout
 * @author     dotdigital <integrations@dotdigital.com>
 */

namespace Dotdigital_WooCommerce\Includes\Forms\Checkout;

use DOMDocument;
use DOMXPath;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;

/**
 * Class Dotdigital_WooCommerce_Checkout_Sms_Marketing_Phone
 */
class Dotdigital_WooCommerce_Checkout_Sms_Marketing_Phone {

	/**
	 * Renders the  collapsable input for the sms consent capture
	 */
	public function render() {
		$show = get_option(
			Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_CHECKOUT,
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
	 * Modifies the input content by removing the description class.
	 *
	 * @param string $field HTML Woocommerce field.
	 * @param string $key The key of the field.
	 * @param array  $args The arguments of the field.
	 * @param mixed  $value The value of the field.
	 * @return false|mixed|string
	 */
	public function modify_input_content( $field, $key, $args, $value ) {
		if ( Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME === $key ) {
			$document = new DOMDocument();
			$document->loadHTML( $field );
			$document
				->getElementById( Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '-description' )
				->setAttribute( 'class', 'dd-phone-input-description' );

			$field = $document->saveHTML();
		}
		return $field;
	}

	/**
	 * Validates the marketing consent checkbox.
	 *
	 * @param array $fields The fields to validate.
	 * @param array $errors The errors to add to.
	 * @return void
	 */
	public function validate( $fields, $errors ) {
		if ( ! empty( wp_strip_all_tags( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME ] ) ) // phpcs:ignore WordPress.Security
			&& ! empty( wp_strip_all_tags( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ] ) ) ) {  // phpcs:ignore WordPress.Security
			$is_valid = filter_var(
				wp_strip_all_tags( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ] ), // phpcs:ignore WordPress.Security
				FILTER_VALIDATE_BOOLEAN
			);

			if ( ! $is_valid ) {
				$validation_message = sprintf(
					'<strong>Marketing Consent</strong> %s',
					wp_strip_all_tags( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ] )// phpcs:ignore WordPress.Security
				);
				$errors->add( 'validation', $validation_message );
			}
		}
	}

	/**
	 * Handles a subscription created via the WooCommerce checkout.
	 *
	 * @todo Implement this.
	 * @param int $order_id The processed order ID.
	 *
	 * @since    1.0.0
	 */
	public function handle_submit( $order_id ) {}
}
