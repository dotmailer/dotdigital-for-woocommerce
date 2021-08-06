<?php
/**
 * Handles CRUD operations for a cart_id property.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/cart
 */

namespace Engagement_Cloud\Includes\Cart;

/**
 * Class Engagement_Cloud_Cart
 */
class Engagement_Cloud_Cart {

	/**
	 * Getter.
	 *
	 * @return string
	 */
	public function get_cart_id() {
		return get_current_user_id() ?
			get_user_meta( get_current_user_id(), '_ec_persistent_cart_id_' . get_current_blog_id(), true ) :
			WC()->session->get( 'cart_id' );
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
			update_user_meta( get_current_user_id(), '_ec_persistent_cart_id_' . get_current_blog_id(), $cart_id );
		} else {
			WC()->session->set( 'cart_id', $cart_id );
		}
	}

	/**
	 * Delete cart id.
	 */
	public function delete_cart_id() {
		if ( get_current_user_id() ) {
			delete_user_meta( get_current_user_id(), '_ec_persistent_cart_id_' . get_current_blog_id() );
		} else {
			WC()->session->set( 'cart_id', null );
		}
	}
}
