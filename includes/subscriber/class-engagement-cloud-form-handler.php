<?php
/**
 * A Form Handler.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/subscriber
 */

namespace Engagement_Cloud\Includes\Subscriber;

use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Subscriber;

/**
 * Class Engagement_Cloud_Form_Handler
 */
class Engagement_Cloud_Form_Handler {

	/**
	 * Subscribes user to newsletter via newsletter form widget.
	 * Adds a guest email address to a cart session for abandoned cart.
	 */
	public function execute() {
		$nonce_value = isset( $_POST['nonce'] ) ? wp_unslash( $_POST['nonce'] ) : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( wp_verify_nonce( $nonce_value, 'subscribe_to_newsletter' ) ) {
			$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : false;

			if ( ! $email ) {
				wp_send_json(
					array(
						'success' => 0,
						'message' => 'Invalid email address',
					)
				);
				return;
			}

			$subscriber_data = array(
				'email'      => $email,
				'status'     => 1,
			);

			$subscriber = new Engagement_Cloud_Subscriber();
			$subscribed = $subscriber->create_or_update( $subscriber_data );

			if ( $subscribed ) {
				wp_send_json( array( 'success' => 1 ) );
				return;
			}
		}
		wp_send_json(
			array(
				'success' => 0,
				'message' => 'There was an error during your subscription. Please try again.',
			)
		);
	}
}

