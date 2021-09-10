<?php
/**
 * Last browsed products provider
 *
 * @link       https://www.dotdigital.com/
 * @since      1.3.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/tracking
 */

namespace Dotdigital_WooCommerce\Includes\Tracking;

use Dotdigital_WooCommerce\Includes\Category\Dotdigital_WooCommerce_Category;

/**
 * Class Dotdigital_WooCommerce_Last_Browsed_Products
 */
class Dotdigital_WooCommerce_Last_Browsed_Products {

	/**
	 * Returns the last browsed product data.
	 *
	 * @param int $product_id The current product id.
	 * @return array
	 */
	public function get_last_product( $product_id ) {
		$product = wc_get_product( $product_id );
		$dotdigital_woocommerce_category_helper = new Dotdigital_WooCommerce_Category();

		try {
			return array(
				'product_name' => $product->get_name(),
				'product_sku' => $product->get_sku(),
				'product_price' => round( $product->get_regular_price(), 2 ),
				'product_url' => get_permalink( $product->get_id() ),
				'product_image_path' => wp_get_attachment_image_url( $product->get_image_id() ),
				'product_status' => $product->get_stock_status(),
				'product_categories' => $dotdigital_woocommerce_category_helper->get_product_categories( $product_id ),
				'product_description' => $product->get_description(),
				'product_currency' => get_woocommerce_currency(),
				'product_specialPrice' => $this->get_special_price( $product ),
			);
		} catch ( \Exception $e ) {
			return array();
		}
	}

	/**
	 * Returns the special price of the product.
	 *
	 * @param \WC_Product $product Product object.
	 * @return float
	 */
	private function get_special_price( $product ) {
		return ( $product->get_regular_price() === $product->get_sale_price() ) ? 0 : round( $product->get_sale_price(), 2 );
	}
}
