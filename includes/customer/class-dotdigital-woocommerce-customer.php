<?php
/**
 * A customer model.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/customer
 */

namespace Dotdigital_WooCommerce\Includes\Customer;

use Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper;

/**
 * Class Dotdigital_WooCommerce_Customer
 */
class Dotdigital_WooCommerce_Customer {

	/**
	 * Get the email address of the logged in customer, or the previously-identified guest.
	 *
	 * @return string
	 */
	public function get_customer_email() {
		$email = ( get_current_user_id() ) ?
			WC()->customer->get_email() :
			WC()->session->get( 'guest_email' );

		return (string) $email;
	}
}
