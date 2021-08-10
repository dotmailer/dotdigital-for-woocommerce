<?php
/**
 * Provides the correct class for collating cart insight data, dependent on order state.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/cart
 */

namespace Engagement_Cloud\Includes\Cart;

use Engagement_Cloud\Engagement_Cloud_Bootstrapper;
use Engagement_Cloud\Includes\Customer\Engagement_Cloud_Customer;
use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Subscriber;

/**
 * Class Engagement_Cloud_Cart_Insight_Handler
 */
class Engagement_Cloud_Cart_Insight_Handler {

	/**
	 * Determine whether we should gather data and enqueue the script.
	 *
	 * @return bool
	 */
	public function can_send_cart_insight() {
		$cart = new Engagement_Cloud_Cart();
		$abandoned_cart_program_id = get_option( Engagement_Cloud_Bootstrapper::PROGRAM_ID_PATH, null );

		/**
		 * If we haven't got a cart_id, that means we've no user OR there are no items in the cart.
		 * If we haven't got an abandoned cart program_id specified, that means we don't need to track abandoned cart.
		 */
		if ( empty( $cart->get_cart_id() || ! $abandoned_cart_program_id ) ) {
			return false;
		}

		$subscriber = new Engagement_Cloud_Subscriber();
		$customer = new Engagement_Cloud_Customer();

		if ( empty( $customer->get_customer_email() ) ) {
			return true;
		}

		if ( get_option(
			Engagement_Cloud_Bootstrapper::ALLOW_NON_SUBSCRIBERS_PATH,
			Engagement_Cloud_Bootstrapper::DEFAULT_ABANDONED_CART_ALLOW_NON_SUBSCRIBERS
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
	public function get_data() {
		$cart_insight_data = array();

		if ( $this->can_send_cart_insight() ) {
			$cart_insight_data = $this->get_data_provider()->get_payload();
		}

		return $cart_insight_data;
	}

	/**
	 * Get the appropriate class to collect data.
	 *
	 * @return Engagement_Cloud_Cart_Insight|Engagement_Cloud_Cart_Insight_Order_Complete
	 */
	private function get_data_provider() {
		if ( is_checkout() && is_wc_endpoint_url( 'order-received' ) ) {
			$order_id = absint( get_query_var( 'order-received' ) );
			return new Engagement_Cloud_Cart_Insight_Order_Complete( $order_id );
		}

		return new Engagement_Cloud_Cart_Insight();
	}
}
