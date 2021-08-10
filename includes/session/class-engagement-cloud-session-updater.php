<?php
/**
 * Handles updates to the current Woo session.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/session
 */

namespace Engagement_Cloud\Includes\Session;

use Engagement_Cloud\Includes\Cart\Engagement_Cloud_Cart_Insight_Handler;

/**
 * Class Engagement_Cloud_Session_Updater
 */
class Engagement_Cloud_Session_Updater {

	/**
	 * The controller entry point.
	 */
	public function execute() {
		$nonce_value = isset( $_POST['nonce'] ) ? wp_unslash( $_POST['nonce'] ) : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( ! wp_verify_nonce( $nonce_value, 'email_capture' ) ) {
			return;
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : false;

		if ( ! $email || get_current_user_id() ) {
			return;
		}

		$this->update_session( $email );

		$cart_insight_handler = new Engagement_Cloud_Cart_Insight_Handler();
		wp_send_json(
			array(
				'data' => $cart_insight_data = $cart_insight_handler->get_data(),
			)
		);
	}

	/**
	 * Update the current session with a guest_email.
	 *
	 * @param string $email An email address.
	 */
	private function update_session( $email ) {
		if ( empty( WC()->session->cart ) ) {
			return;
		}

		WC()->session->set( 'guest_email', $email );
	}
}
