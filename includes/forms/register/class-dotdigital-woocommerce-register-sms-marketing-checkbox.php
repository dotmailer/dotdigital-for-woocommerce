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
 * Class Dotdigital_WooCommerce_Checkout_Sms_Marketing_Checkbox
 */
class Dotdigital_WooCommerce_Register_Sms_Marketing_Checkbox {

	/**
	 * Renders the sms consent checkbox in checkout page.
	 */
	public function render() {
		$show_checkbox = get_option(
			Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION,
			Dotdigital_WooCommerce_Config::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT
		);

		if ( ! $show_checkbox ) {
			return;
		}

		woocommerce_form_field(
			Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_CHECKBOX_SMS_NAME,
			array(
				'type'  => 'checkbox',
				'label' => get_option(
					Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_SMS_TEXT,
					Dotdigital_WooCommerce_Config::DEFAULT_MARKETING_CHECKBOX_TEXT
				),
				'class' => array(
					DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_NAME . '-sms-checkbox',
				),
			),
			$_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_CHECKBOX_SMS_NAME ] ?? false // phpcs:ignore WordPress.Security
		);
	}

}
