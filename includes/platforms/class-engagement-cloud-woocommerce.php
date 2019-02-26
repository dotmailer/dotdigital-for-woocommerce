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
	 * Text for the checkbox's label.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $checkbox_label    Text for the checkbox's label.
	 */
	private $checkbox_label = 'Subscribe to our newsletter';

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
	function engagement_cloud_render_checkout_marketing_checkbox() {
		if ( is_user_logged_in( ) ) {
			woocommerce_form_field( $this->checkbox_name, array(
				'type'          => 'checkbox',
				'label'         => __( $this->checkbox_label ),
			), get_user_meta( get_current_user_id(), $this->meta_key, true ) );
		}
	}

	/**
	 * Handles the checkoutbox in checkout page.
	 *
	 * @since    1.0.0
	 */
	function engagement_cloud_handle_checkout_marketing_checkbox() {
		if ( is_user_logged_in() ) {
			$accepts_marketing = 0;
			if ( isset( $_POST[ $this->checkbox_name ] ) ) {
				$accepts_marketing = 1;
			}
			update_user_meta( get_current_user_id( ), $this->meta_key, $accepts_marketing );
		}
	}

	/**
	 * Renders the checkoutbox in registration page.
	 *
	 * @since    1.0.0
	 */
	function engagement_cloud_render_register_marketing_checkbox() {
		woocommerce_form_field( $this->checkbox_name, array(
			'type'          => 'checkbox',
			'label'         => __( $this->checkbox_label ),
		) );
	}

	/**
	 * Handles the checkoutbox in registration page.
	 *
	 * @since    1.0.0
	 *
	 * @param string $user_id The ID of new registree.
	 */
	function engagement_cloud_handle_register_marketing_checkbox( $user_id ) {
		$accepts_marketing = 0;
		if ( isset( $_POST[ $this->checkbox_name ] ) ) {
			$accepts_marketing = 1;
		}
		update_user_meta( $user_id, $this->meta_key, $accepts_marketing );
	}
	
	/**
	 * Updates the modified cart date so Api2Cart can handle.
	 *
	 * @since    1.1.0
	 */
	function api2cart_cart_updated() {
	    $woocommerce = WooCommerce::instance();
	    $user_id = get_current_user_id() ?: $woocommerce->session->get_customer_id();
	    $blog_id = get_current_blog_id();
	    $itemsCount = count($woocommerce->cart->get_cart_contents());
	    
	    if (preg_match('/^[a-f0-9]{32}$/', $user_id) !== 1) {
	        $updateTime = time();
	        $updatedKey = '_a2c_wh_cart_' . $blog_id . '_updated_gmt';
	        $createdKey = '_a2c_wh_cart_' . $blog_id . '_created_gmt';
	        
	        update_user_meta($user_id, $updatedKey, $updateTime);
	        
	        if(get_user_meta($user_id, $createdKey, true) === '') {
	            update_user_meta($user_id, $createdKey, $updateTime);
	        }
	        elseif ($itemsCount === 0) {
	            delete_user_meta($user_id, $createdKey);
	        }
	    }
	}
}