<?php
/**
 * A Product category model.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.3.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/category
 */

namespace Dotdigital_WooCommerce\Includes\Category;

/**
 * Class Dotdigital_WooCommerce_Category
 */
class Dotdigital_WooCommerce_Category {

	/**
	 * Returns the product categories.
	 *
	 * @param int $product_id The current product id.
	 * @return string
	 */
	public function get_product_categories( $product_id ) {
		$categories = array();
		$wc_terms  = get_the_terms( $product_id, 'product_cat' );

		if ( ! $wc_terms ) {
			return '';
		}

		foreach ( $wc_terms as $wc_term ) {
			$categories[] = $wc_term->name;
		}

		return implode( ',', $categories );
	}
}
