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

use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Subscriber;

/**
 * Class Dotdigital_WooCommerce_Checkout_Marketing_Checkbox
 */
class Dotdigital_WooCommerce_Checkout_Marketing_Checkbox {

	/**
	 * Renders the checkbox in checkout page.
	 * For guests, the value defaults to false.
	 * For customers, the value reflects the current subscriber status.
	 */
	public function render() {
		$show_checkbox = get_option(
			Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_CHECKOUT,
			Dotdigital_WooCommerce_Config::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT
		);

		if ( ! $show_checkbox ) {
			return;
		}

		$subscriber = new Dotdigital_WooCommerce_Subscriber();

		woocommerce_form_field(
			Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_CHECKBOX_NAME,
			array(
				'type'  => 'checkbox',
				'label' => get_option(
					Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_TEXT,
					Dotdigital_WooCommerce_Config::DEFAULT_MARKETING_CHECKBOX_TEXT
				),

			),
			$subscriber->is_user_id_subscribed( get_current_user_id() )
		);
	}

	/**
	 * Handles a subscription created via the WooCommerce checkout.
	 *
	 * @param int $order_id The processed order ID.
	 */
	public function handle_submit( $order_id ) {
		$accepts_marketing = 0;
		if ( isset( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_CHECKBOX_NAME ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$accepts_marketing = 1;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		// For guest orders with no subscription, we can exit here.
		if ( ! $accepts_marketing && ! $order->get_customer_id() ) {
			return;
		}

		$data = array(
			'user_id'    => $order->get_customer_id(),
			'email'      => $order->get_billing_email(),
			'status'     => $accepts_marketing,
			'first_name' => $order->get_billing_first_name(),
			'last_name'  => $order->get_billing_last_name(),
		);

		$subscriber = new Dotdigital_WooCommerce_Subscriber();
		$subscriber->create_or_update( $data );
	}
}
