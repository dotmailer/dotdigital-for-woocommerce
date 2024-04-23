<?php
/**
 * Collates cart insight data after an order is received.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/cart
 */

namespace Dotdigital_WooCommerce\Includes\Cart;

use Dotdigital_WooCommerce\Includes\Cart\Dotdigital_WooCommerce_Cart;
use Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Subscriber;

/**
 * Class Dotdigital_WooCommerce_Cart_Insight_Order_Complete
 */
class Dotdigital_WooCommerce_Cart_Insight_Order_Complete extends Dotdigital_WooCommerce_Cart_Insight {

	/**
	 * Order object.
	 *
	 * @var \WC_Order
	 */
	private $order;

	/**
	 * Dotdigital_WooCommerce_Cart_Insight_Order_Complete constructor.
	 *
	 * @param int $order_id The order ID.
	 */
	public function __construct( int $order_id ) {
		$this->order = wc_get_order( $order_id );
	}

	/**
	 * Set the cart_phase.
	 *
	 * @return string
	 */
	protected function get_cart_phase() {
		return 'ORDER_COMPLETE';
	}

	/**
	 * Get order subtotal.
	 *
	 * @return float
	 */
	protected function get_subtotal() {
		return $this->order->get_subtotal();
	}

	/**
	 * Get order shipping.
	 *
	 * @return float|string
	 */
	protected function get_shipping() {
		return $this->order->get_shipping_total( '' );
	}

	/**
	 * Get order discount amount.
	 *
	 * @return float|string
	 */
	protected function get_discount_amount() {
		return $this->order->get_discount_total( '' );
	}

	/**
	 * Get order tax amount.
	 *
	 * @return float
	 */
	protected function get_tax_amount() {
		return $this->order->get_total_tax( '' );
	}

	/**
	 * Get order grand total.
	 *
	 * @return float
	 */
	protected function get_grand_total() {
		return $this->order->get_total( '' );
	}

	/**
	 * Get order line items.
	 *
	 * @return array
	 */
	protected function get_line_items() {
		return $this->order->get_data()['line_items'];
	}
}
