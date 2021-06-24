<?php
/**
 * Used for WooCommerce hooks.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/platforms
 */

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
	 * Key used to identify the value in the meta table.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $meta_key    Key used to identify the value in the meta table.
	 */
	private $meta_key = '_wc_subscribed_to_newsletter';

	/**
	 * Renders the checkbox in checkout page.
	 *
	 * @since    1.0.0
	 */
	public function engagement_cloud_render_checkout_marketing_checkbox() {
		woocommerce_form_field(
			$this->checkbox_name,
			array(
				'type'  => 'checkbox',
				'label' => __( 'Subscribe to our newsletter' ),
			),
			get_user_meta( get_current_user_id(), $this->meta_key, true )
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
		$nonce_value = isset( $_POST['woocommerce-process-checkout-nonce'] ) ? wp_unslash( $_POST['woocommerce-process-checkout-nonce'] ) : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( isset( $nonce_value ) && wp_verify_nonce( $nonce_value, 'woocommerce-process_checkout' ) ) {
			$accepts_marketing = 0;

			if ( isset( $_POST[ $this->checkbox_name ] ) ) {
				$accepts_marketing = 1;
				$order             = wc_get_order( $order_id );
				global $wpdb;

				$wpdb->insert(
					$wpdb->prefix . Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME,
					array(
						'user_id'    => $order->get_customer_id(),
						'email'      => $order->get_billing_email(),
						'status'     => 1,
						'first_name' => $order->get_billing_first_name(),
						'last_name'  => $order->get_billing_last_name(),
						'created_at' => current_time( 'mysql' ),
					)
				); // db call ok.
			}

			update_user_meta( get_current_user_id(), $this->meta_key, $accepts_marketing );
		}
	}

	/**
	 * Renders the checkoutbox in registration page.
	 *
	 * @since    1.0.0
	 */
	public function engagement_cloud_render_register_marketing_checkbox() {
		woocommerce_form_field(
			$this->checkbox_name,
			array(
				'type'  => 'checkbox',
				'label' => __( 'Subscribe to our newsletter' ),
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
			$accepts_marketing = 0;
			if ( isset( $_POST[ $this->checkbox_name ] ) ) {
				$accepts_marketing = 1;
				$email             = isset( $_POST['email'] ) ?
					sanitize_text_field( wp_unslash( $_POST['email'] ) ) :
					'';
				global $wpdb;

				$wpdb->insert(
					$wpdb->prefix . Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME,
					array(
						'user_id'    => $user_id,
						'email'      => $email,
						'status'     => 1,
						'first_name' => '',
						'last_name'  => '',
						'created_at' => current_time( 'mysql' ),
					)
				); // db call ok.
			}

			update_user_meta( $user_id, $this->meta_key, $accepts_marketing );
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
