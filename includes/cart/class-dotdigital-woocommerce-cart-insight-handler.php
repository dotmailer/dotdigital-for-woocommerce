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
		$ac_enabled = get_option( Dotdigital_WooCommerce_Config::AC_STATUS_PATH );
		$abandoned_cart_program_id = get_option( Dotdigital_WooCommerce_Config::PROGRAM_ID_PATH, null );

		/**
		 * If we haven't got a cart_id, that means we've no user OR there are no items in the cart.
		 * If AC is not enabled, we exit.
		 * If we haven't got an AC program_id specified, that means we don't need to track abandoned carts.
		 */
		if ( empty( $cart->get_cart_id() ) || ! $ac_enabled || ! $abandoned_cart_program_id ) {
			return false;
		}

		$subscriber = new Dotdigital_WooCommerce_Subscriber();
		$customer = new Dotdigital_WooCommerce_Customer();

		if ( empty( $customer->get_customer_email() ) ) {
			return true;
		}

		if ( get_option(
			Dotdigital_WooCommerce_Config::ALLOW_NON_SUBSCRIBERS_PATH,
			Dotdigital_WooCommerce_Config::DEFAULT_ABANDONED_CART_ALLOW_NON_SUBSCRIBERS
		) ) {
			return true;
		}

		return $subscriber->is_subscribed( $customer->get_customer_email() );
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

		return new Dotdigital_WooCommerce_Cart_Insight();
	}
}
