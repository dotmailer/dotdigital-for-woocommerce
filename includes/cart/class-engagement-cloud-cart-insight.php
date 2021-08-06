<?php
/**
 * Collates cart insight data.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/cart
 */

namespace Engagement_Cloud\Includes\Cart;

use Engagement_Cloud\Includes\Cart\Engagement_Cloud_Cart;

/**
 * Class Engagement_Cloud_Cart_Insight
 */
class Engagement_Cloud_Cart_Insight {

	/**
	 * Return an array of data to be consumed by cart-insight.js
	 *
	 * @return array
	 */
	public function get_payload() {

		if ( is_null( WC()->cart ) ) {
			return array();
		}

		$ec_cart = new Engagement_Cloud_Cart();

		$data = array(
			'customer_email'  => WC()->customer->get_email(),
			'program_id'      => $this->get_program_id(),
			'cart_delay'      => $this->get_cart_delay(),
			'cart_id'         => $ec_cart->get_cart_id(),
			'cart_phase'      => $this->get_cart_phase(),
			'currency'        => get_woocommerce_currency(),
			'subtotal'        => round( $this->get_subtotal(), 2 ),
			'shipping'        => round( $this->get_shipping(), 2 ),
			'discount_amount' => round( $this->get_discount_amount(), 2 ),
			'tax_amount'      => round( $this->get_tax_amount(), 2 ),
			'grand_total'     => round( $this->get_grand_total(), 2 ),
			'cart_url'        => wc_get_cart_url(),
		);

		$line_items = array();
		foreach ( $this->get_line_items() as $cart_item_key => $cart_item ) {
			$product = wc_get_product( $cart_item['product_id'] );

			$line_item_data = array(
				'sku'         => $product->get_sku(),
				'name'        => $product->get_name(),
				'description' => $product->get_short_description(),
				'category'    => $this->get_category_string( $product->get_category_ids() ),
				'quantity'    => $cart_item['quantity'],
				'total_price' => round( $cart_item['line_total'], 2 ),
				'image_url'   => $this->get_product_image_url( $product ),
				'product_url' => get_permalink( $product->get_id() ),
			);

			if ( 'variable' === $product->get_type() ) {
				$product = wc_get_product( $cart_item['variation_id'] );
			}

			$line_item_data['unit_price'] = round( $product->get_regular_price(), 2 );
			$line_item_data['sale_price'] = round( $product->get_sale_price(), 2 );

			$line_items[] = $line_item_data;
		}

		$data['line_items'] = $line_items;

		return $data;
	}

	/**
	 * Get the stored program_id option.
	 *
	 * @return int
	 */
	private function get_program_id() {
		return (int) get_option( 'engagement_cloud_for_woocommerce_cart_insight_program_id', 0 );
	}

	/**
	 * Get the stored cart_delay option.
	 *
	 * @return int
	 */
	private function get_cart_delay() {
		return (int) get_option( 'engagement_cloud_for_woocommerce_cart_insight_cart_delay', 0 );
	}

	/**
	 * Set the cart_phase.
	 *
	 * @return string
	 */
	protected function get_cart_phase() {
		return 'CUSTOMER_LOGIN';
	}

	/**
	 * Get cart subtotal.
	 *
	 * @return float
	 */
	protected function get_subtotal() {
		return WC()->cart->get_subtotal();
	}

	/**
	 * Get cart shipping.
	 *
	 * @return float
	 */
	protected function get_shipping() {
		return WC()->cart->get_shipping_total();
	}

	/**
	 * Get cart discount amount.
	 *
	 * @return float
	 */
	protected function get_discount_amount() {
		return WC()->cart->get_discount_total();
	}

	/**
	 * Get cart tax amount.
	 *
	 * @return float
	 */
	protected function get_tax_amount() {
		return WC()->cart->get_total_tax();
	}

	/**
	 * Get cart grand total.
	 *
	 * @return float
	 */
	protected function get_grand_total() {
		return WC()->cart->get_total( '' );
	}

	/**
	 * Get cart line items.
	 *
	 * @return array
	 */
	protected function get_line_items() {
		return WC()->cart->get_cart_contents();
	}

	/**
	 * Assemble a comma-separated string of category values.
	 *
	 * @param array $category_ids Array of ids.
	 * @return string
	 */
	private function get_category_string( array $category_ids ) {
		$category_names = array();
		foreach ( $category_ids as $category_id ) {
			$term = get_term_by( 'id', $category_id, 'product_cat' );
			if ( $term ) {
				$category_names[] = $term->name;
			}
		}
		return implode( ', ', $category_names );
	}

	/**
	 * Fetch a url for the product's image attachment, falling back to the Woo placeholder.
	 *
	 * @param WC_Product $product The product object.
	 * @return string
	 */
	private function get_product_image_url( $product ) {
		$attachment_url = wp_get_attachment_url( $product->get_image_id() );
		return $attachment_url ? $attachment_url : wc_placeholder_img_src();
	}
}
