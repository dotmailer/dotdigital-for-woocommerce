<?php
/**
 * Provides the correct class for collating cart insight data, dependent on order state.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/cart
 */

namespace Dotdigital_WooCommerce\Includes\Cart;

use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Dotdigital_WooCommerce\Includes\Customer\Dotdigital_WooCommerce_Customer;
use Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Subscriber;

/**
 * Class Dotdigital_WooCommerce_Cart_Insight_Handler
 */
class Dotdigital_WooCommerce_Cart_Insight_Handler {

	/**
	 * Determine whether we should gather data and enqueue the script.
	 *
	 * @return bool
	 */
	public function can_send_cart_insight() {
		$cart = new Dotdigital_WooCommerce_Cart();

		/**
		 * If we haven't got a cart_id, that means we've no user OR there are no items in the cart.
		 */
		if ( empty( $cart->get_cart_id() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get cart insight data.
	 *
	 * @return array
	 */
	public function get_data(): array {
		if ( $this->can_send_cart_insight() ) {
			try {
				return $this->get_data_provider()->get_payload();
			} catch ( \Exception $e ) {
				return array();
			}
		}

		return array();
	}

	/**
	 * Get the appropriate class to collect data.
	 *
	 * @return Dotdigital_WooCommerce_Cart_Insight|Dotdigital_WooCommerce_Cart_Insight_Order_Complete
	 */
	private function get_data_provider() {
		if ( is_checkout() && is_wc_endpoint_url( 'order-received' ) ) {
			$order_id = absint( get_query_var( 'order-received' ) );
			return new Dotdigital_WooCommerce_Cart_Insight_Order_Complete( $order_id );
		}

		$cart_insight = new Dotdigital_WooCommerce_Cart_Insight();

		if ( is_checkout() || $this->is_ajax_from_checkout() ) {
			$cart_insight->set_cart_phase( 'ORDER_CHECKOUT' );
		}

		return $cart_insight;
	}

	/**
	 * Check if current AJAX request originated from checkout page.
	 *
	 * @return bool
	 */
	private function is_ajax_from_checkout() {
		if ( ! wp_doing_ajax() ) {
			return false;
		}

		$referer = isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';

		if ( empty( $referer ) ) {
			return false;
		}

		$checkout_url       = wc_get_checkout_url();
		$checkout_url_parts = parse_url( $checkout_url );
		$referer_parts      = parse_url( $referer );

		if ( isset( $checkout_url_parts['path'] ) && isset( $referer_parts['path'] ) ) {
			return strpos( $referer_parts['path'], $checkout_url_parts['path'] ) !== false;
		}

		return false;
	}
}
