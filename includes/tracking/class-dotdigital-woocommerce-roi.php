<?php
/**
 * Roi data provider.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/tracking
 */

namespace Dotdigital_WooCommerce\Includes\Tracking;

/**
 * Class Dotdigital_WooCommerce_Roi
 */
class Dotdigital_WooCommerce_Roi {

	/**
	 * Returns the order data to be send in EC via ROI script.
	 *
	 * @param int $order_id  Used to fetch the order details.
	 * @return array|void
	 *
	 * @since 1.2.0
	 */
	public function get_order_data( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return;
		}
		$line_items = array();

		foreach ( $order->get_data()['line_items'] as $item ) {
			$line_items[] = $item->get_name();
		}

		return array(
			'line_items' => $line_items,
			'total' => $order->get_total(),
		);
	}
}
