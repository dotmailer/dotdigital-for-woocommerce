<?php
/**
 * Handles CRUD operations for a cart_id property.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/cart
 */

namespace Dotdigital_WooCommerce\Includes\Cart;

/**
 * Class Dotdigital_WooCommerce_Cart
 */
class Dotdigital_WooCommerce_Cart {

	/**
	 * Getter.
	 *
	 * @return string|null
	 */
	public function get_cart_id() {
		if ( get_current_user_id() ) {
			return get_user_meta( get_current_user_id(), '_dd_persistent_cart_id_' . get_current_blog_id(), true );
		}

		if ( WC()->session ) {
			return WC()->session->get( 'cart_id', null );
		}

		return null;
	}

	/**
	 * Setter.
	 *
	 * @return void
	 */
	public function set_cart_id() {
		if ( is_null( WC()->session->cart ) ) {
			return;
		}

		$cart_id = uniqid();

		if ( get_current_user_id() ) {
			update_user_meta( get_current_user_id(), '_dd_persistent_cart_id_' . get_current_blog_id(), $cart_id );
		} else {
			WC()->session->set( 'cart_id', $cart_id );
		}
	}

	/**
	 * Delete cart id.
	 */
	public function delete_cart_id() {
		if ( get_current_user_id() ) {
			delete_user_meta( get_current_user_id(), '_dd_persistent_cart_id_' . get_current_blog_id() );
		} else {
			WC()->session->set( 'cart_id', null );
		}
	}
}
