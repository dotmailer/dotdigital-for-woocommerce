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
use Dotdigital_WooCommerce\Includes\Image\Dotdigital_WooCommerce_Image;

/**
 * Class Dotdigital_WooCommerce_Cart_Insight
 */
class Dotdigital_WooCommerce_Cart_Insight {

	/**
	 * Return an array of data to be consumed by cart-insight.js
	 *
	 * @return array
	 * @throws \Exception If any line item cannot be matched to a product.
	 */
	public function get_payload() {

		if ( is_null( WC()->cart ) ) {
			return array();
		}

		$dd_cart = new Dotdigital_WooCommerce_Cart();
		$customer = new Dotdigital_WooCommerce_Customer();
		$dotdigital_woocommerce_category_helper = new Dotdigital_WooCommerce_Category();
		$image_finder = new Dotdigital_WooCommerce_Image();

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

		foreach ( $this->get_line_items() as $cart_item ) {
			$product = wc_get_product( $cart_item['product_id'] );
			if ( ! $product ) {
				/* translators: placeholder = the product id */
				throw new \Exception( sprintf( __( 'Product id %s not found, invalid payload for cart insight.', 'dotdigital-woocommerce' ), $cart_item['product_id'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			}

			$line_item_data = array(
				'sku'         => $product->get_sku(),
				'name'        => $product->get_name(),
				'description' => $product->get_short_description(),
				'category'    => $dotdigital_woocommerce_category_helper->get_product_categories( $product->get_id() ),
				'quantity'    => $cart_item['quantity'],
				'total_price' => round( $cart_item['line_total'], 2 ),
				'image_url'   => $image_finder->get_product_image_url( $product ),
				'product_url' => get_permalink( $product->get_id() ),
			);

			if ( 'variable' === $product->get_type() ) {
				$product = wc_get_product( $cart_item['variation_id'] );
				if ( ! $product ) {
					/* translators: placeholder = the product variation id */
					throw new \Exception( sprintf( __( 'Product id %s not found for variation, invalid payload for cart insight.', 'dotdigital-woocommerce' ), $cart_item['variation_id'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
			}

			$line_item_data['unit_price'] = round( (float) $product->get_regular_price(), 2 );
			$line_item_data['sale_price'] = round( $this->get_product_sale_price( $product ), 2 );

			$line_items[] = apply_filters( 'dotdigital_modify_line_item_data', $line_item_data, $cart_item );
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
	 * Get product sale price.
	 *
	 * @param \WC_Product $product The product object.
	 *
	 * @return float
	 */
	private function get_product_sale_price( $product ) {
		return (float) ( $product->get_sale_price() ? $product->get_sale_price() : $product->get_regular_price() );
	}
}
