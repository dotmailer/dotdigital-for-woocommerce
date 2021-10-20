<?php
/**
 * A Product image helper.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.3.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/image
 */

namespace Dotdigital_WooCommerce\Includes\Image;

/**
 * Class Dotdigital_WooCommerce_Image
 */
class Dotdigital_WooCommerce_Image {

	/**
	 * Fetch a url for the product's image attachment, falling back to the Woo placeholder.
	 * Note this will return the full original size if an attachment is found.
	 *
	 * @param \WC_Product $product The product object.
	 * @return string
	 */
	public function get_product_image_url( $product ) {
		$attachment_url = wp_get_attachment_image_url( $product->get_image_id(), '' );
		return $attachment_url ? $attachment_url : wc_placeholder_img_src();
	}
}
