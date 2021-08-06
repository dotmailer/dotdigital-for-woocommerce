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
use Engagement_Cloud\Includes\Cart\Engagement_Cloud_Cart;
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

		/**
		 * If we haven't got a cart_id, that means we've no user OR there are no items in the cart
		 */
		if ( empty( $cart->get_cart_id() ) ) {
			return false;
		}

		if ( get_option(
			'engagement_cloud_for_woocommerce_abandoned_cart_allow_non_subscribers',
			Engagement_Cloud_Bootstrapper::DEFAULT_ABANDONED_CART_ALLOW_NON_SUBSCRIBERS
		) ) {
			return true;
		}

		$subscriber = new Engagement_Cloud_Subscriber();
		$email      = WC()->customer->get_email();

		return $subscriber->is_subscribed( $email );
	}

	/**
	 * Get the appropriate class to collect data.
	 *
	 * @return Engagement_Cloud_Cart_Insight|Engagement_Cloud_Cart_Insight_Order_Complete
	 */
	public function get_data_provider() {
		if ( is_checkout() && is_wc_endpoint_url( 'order-received' ) ) {
			$order_id = absint( get_query_var( 'order-received' ) );
			return new Engagement_Cloud_Cart_Insight_Order_Complete( $order_id );
		}

		return new Engagement_Cloud_Cart_Insight();
	}
}
