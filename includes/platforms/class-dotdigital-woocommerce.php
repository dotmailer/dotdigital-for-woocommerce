<?php
/**
 * Used for WooCommerce hooks.
 *
 * This class defines all code necessary to use WooCommerce hooks.
 *
 * @since      1.0.0
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/platforms
 * @author     dotdigital <integrations@dotdigital.com>
 */

namespace Dotdigital_WooCommerce\Includes\Platforms;

use Dotdigital_WooCommerce\Includes\Tracking\Dotdigital_WooCommerce_Last_Browsed_Products;
use WooCommerce;
use Dotdigital_WooCommerce\Includes\Cart\Dotdigital_WooCommerce_Cart;
use Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Subscriber;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;

/**
 * Class Dotdigital_WooCommerce_WooCommerce
 */
class Dotdigital_WooCommerce {

	/**
	 * Used to identify the checkbox.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $checkbox_name    Used to identify the checkbox.
	 */
	private $checkbox_name = 'dotdigital_woocommerce_checkbox';

	/**
	 * Renders the checkbox in checkout page.
	 * For guests, the value defaults to false.
	 * For customers, the value reflects the current subscriber status.
	 *
	 * @since    1.0.0
	 */
	public function dotdigital_woocommerce_render_checkout_marketing_checkbox() {
		$show_checkbox = get_option(
			Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_CHECKOUT,
			Dotdigital_WooCommerce_Config::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT
		);

		if ( ! $show_checkbox ) {
			return;
		}

		$subscriber = new Dotdigital_WooCommerce_Subscriber();

		woocommerce_form_field(
			$this->checkbox_name,
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
	 *
	 * @since    1.0.0
	 */
	public function dotdigital_woocommerce_handle_checkout_subscription( $order_id ) {
		$accepts_marketing = 0;
		if ( isset( $_POST[ $this->checkbox_name ] ) ) { // phpcs:ignore WordPress.Security
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

	/**
	 * Renders the checkoutbox in registration page.
	 *
	 * @since    1.0.0
	 */
	public function dotdigital_woocommerce_render_register_marketing_checkbox() {

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
	 * @since    1.0.0
	 *
	 * @param string $user_id The ID of new registree.
	 */
	public function dotdigital_woocommerce_handle_register_marketing_checkbox( $user_id ) {
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

	/**
	 * Set the cart id.
	 */
	public function dd_cart_init() {
		if ( is_admin() ) {
			return;
		}

		$cart = new Dotdigital_WooCommerce_Cart();

		if ( ! $cart->get_cart_id() ) {
			$cart->set_cart_id();
		}
	}

	/**
	 * Initializes wbt script with last browsed product data.
	 */
	public function last_browsed_products() {
		global $product;

		if ( ! $product->get_id() ) {
			return;
		}

		$last_browsed_products_handler = new Dotdigital_WooCommerce_Last_Browsed_Products();

		wp_localize_script(
			'wbt',
			'product_data',
			array(
				'data' => $last_browsed_products_handler->get_last_product( $product->get_id() ),
			)
		);
	}
}
