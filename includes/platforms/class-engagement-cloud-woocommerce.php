<?php
/**
 * Used for WooCommerce hooks.
 *
 * This class defines all code necessary to use WooCommerce hooks.
 *
 * @since      1.0.0
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/platforms
 * @author     dotdigital <integrations@dotdigital.com>
 */

/**
 * Class Engagement_Cloud_WooCommerce
 */
class Engagement_Cloud_WooCommerce {

	/**
	 * Used to identify the checkbox.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $checkbox_name    Used to identify the checkbox.
	 */
	private $checkbox_name = 'engagement_cloud_checkbox';

	/**
	 * Renders the checkbox in checkout page.
	 * For guests, the value defaults to false.
	 * For customers, the value reflects the current subscriber status.
	 *
	 * @since    1.0.0
	 */
	public function engagement_cloud_render_checkout_marketing_checkbox() {
		if ( ! get_option(
			'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_checkout',
			Engagement_Cloud_Bootstrapper::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT
		) ) {
			return;
		}

		$current_user_has_subscription = false;

		if ( get_current_user_id() ) {
			global $wpdb;
			$table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME;

			$matching_subscriber = $wpdb->get_row(
				$wpdb->prepare( "SELECT * FROM {$table_name} WHERE user_id = %d", get_current_user_id() ) // phpcs:ignore WordPress.DB
			);
			if ( $matching_subscriber ) {
				$current_user_has_subscription = ( Engagement_Cloud_Subscriber::SUBSCRIBED === (int) $matching_subscriber->status );
			}
		}

		woocommerce_form_field(
			$this->checkbox_name,
			array(
				'type'  => 'checkbox',
				'label' => get_option(
					'engagement_cloud_for_woocommerce_settings_marketing_checkbox_text',
					Engagement_Cloud_Bootstrapper::DEFAULT_MARKETING_CHECKBOX_TEXT
				),
			),
			$current_user_has_subscription
		);
	}

	/**
	 * Handles a subscription created via the Woocommerce checkout.
	 *
	 * @param int $order_id The processed order ID.
	 *
	 * @since    1.0.0
	 */
	public function engagement_cloud_handle_checkout_subscription( $order_id ) {
		$accepts_marketing = 0;
		if ( isset( $_POST[ $this->checkbox_name ] ) ) { // phpcs:ignore WordPress.Security
			$accepts_marketing = 1;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		// For guest orders with no subscription, we can exit here.
		if ( ! $accepts_marketing && ! $order->get_customer_id() ) {
			return;
		}

		$data = array(
			'user_id'    => $order->get_customer_id(),
			'email'      => $order->get_billing_email(),
			'status'     => $accepts_marketing,
			'first_name' => $order->get_billing_first_name(),
			'last_name'  => $order->get_billing_last_name(),
		);

		$subscriber = new Engagement_Cloud_Subscriber();
		$subscriber->create_or_update( $data );
	}

	/**
	 * Renders the checkoutbox in registration page.
	 *
	 * @since    1.0.0
	 */
	public function engagement_cloud_render_register_marketing_checkbox() {

		if ( ! get_option(
			'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_register',
			Engagement_Cloud_Bootstrapper::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT
		) ) {
			return;
		}

		woocommerce_form_field(
			$this->checkbox_name,
			array(
				'type'  => 'checkbox',
				'label' => get_option(
					'engagement_cloud_for_woocommerce_settings_marketing_checkbox_text',
					Engagement_Cloud_Bootstrapper::DEFAULT_MARKETING_CHECKBOX_TEXT
				),
			)
		);
	}

	/**
	 * Handles the checkoutbox in registration page.
	 *
	 * @since    1.0.0
	 *
	 * @param string $user_id The ID of new registree.
	 */
	public function engagement_cloud_handle_register_marketing_checkbox( $user_id ) {
		$nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? wp_unslash( $_POST['woocommerce-register-nonce'] ) : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( isset( $nonce_value, $_POST['email'] ) && wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
			if ( isset( $_POST[ $this->checkbox_name ] ) ) {
				$email = isset( $_POST['email'] ) ?
					sanitize_text_field( wp_unslash( $_POST['email'] ) ) :
					'';

				$data = array(
					'user_id'    => $user_id,
					'email'      => $email,
					'status'     => 1,
				);

				$subscriber = new Engagement_Cloud_Subscriber();
				$subscriber->create_or_update( $data );
			}
		}
	}

	/**
	 * Updates the modified cart date.
	 *
	 * @since    1.1.0
	 */
	public function cart_updated() {
		$woocommerce = WooCommerce::instance();
		$user_id     = get_current_user_id() ? get_current_user_id() : $woocommerce->session->get_customer_id();
		$blog_id     = get_current_blog_id();
		$items_count = count( $woocommerce->cart->get_cart_contents() );

		if ( preg_match( '/^[a-f0-9]{32}$/', $user_id ) !== 1 ) {
			$update_time = time();
			$updated_key = '_a2c_wh_cart_' . $blog_id . '_updated_gmt';
			$created_key = '_a2c_wh_cart_' . $blog_id . '_created_gmt';

			update_user_meta( $user_id, $updated_key, $update_time );

			if ( get_user_meta( $user_id, $created_key, true ) === '' ) {
				update_user_meta( $user_id, $created_key, $update_time );
			} elseif ( 0 === $items_count ) {
				delete_user_meta( $user_id, $created_key );
			}
		}
	}
}
