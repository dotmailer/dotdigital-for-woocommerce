<?php
/**
 * Collates cart insight data.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/cart
 */

namespace Dotdigital_WooCommerce\Includes\Cart;

use Dotdigital_WooCommerce\Includes\Customer\Dotdigital_WooCommerce_Customer;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Dotdigital_WooCommerce\Includes\Category\Dotdigital_WooCommerce_Category;

/**
 * Class Dotdigital_WooCommerce_Cart_Insight
 */
class Dotdigital_WooCommerce_Cart_Insight {

	/**
	 * Return an array of data to be consumed by cart-insight.js
	 *
	 * @return array
	 */
	public function get_payload() {

		if ( is_null( WC()->cart ) ) {
			return array();
		}

		$dd_cart = new Dotdigital_WooCommerce_Cart();
		$customer = new Dotdigital_WooCommerce_Customer();

		$data = array(
			'customer_email'  => $customer->get_customer_email(),
			'program_id'      => $this->get_program_id(),
			'cart_delay'      => $this->get_cart_delay(),
			'cart_id'         => $dd_cart->get_cart_id(),
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
		$dotdigital_woocommerce_category_helper = new Dotdigital_WooCommerce_Category();

		foreach ( $this->get_line_items() as $cart_item_key => $cart_item ) {
			$product = wc_get_product( $cart_item['product_id'] );

			$line_item_data = array(
				'sku'         => $product->get_sku(),
				'name'        => $product->get_name(),
				'description' => $product->get_short_description(),
				'category'    => $dotdigital_woocommerce_category_helper->get_product_categories( $product->get_id() ),
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
		return (int) get_option( Dotdigital_WooCommerce_Config::PROGRAM_ID_PATH, 0 );
	}

	/**
	 * Get the stored cart_delay option.
	 *
	 * @return int
	 */
	private function get_cart_delay() {
		return (int) get_option( Dotdigital_WooCommerce_Config::CART_DELAY_PATH, 0 );
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
