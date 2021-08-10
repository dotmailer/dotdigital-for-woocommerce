<?php
/**
 * A customer model.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/customer
 */

namespace Engagement_Cloud\Includes\Customer;

use Engagement_Cloud\Engagement_Cloud_Bootstrapper;

/**
 * Class Engagement_Cloud_Customer
 */
class Engagement_Cloud_Customer {

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
