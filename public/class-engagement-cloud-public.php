<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/public
 */

namespace Engagement_Cloud\Pub;

use Engagement_Cloud\Includes\Cart\Engagement_Cloud_Cart;
use Engagement_Cloud\Includes\Cart\Engagement_Cloud_Cart_Insight_Handler;
use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Subscriber;
use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Form_Handler;
use Engagement_Cloud\Includes\Widgets\Engagement_Cloud_Widget;
use Engagement_Cloud\Includes\Tracking\Engagement_Cloud_Roi;
use Engagement_Cloud\Engagement_Cloud_Bootstrapper;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/public
 * @author     dotdigital <integrations@dotigital.com>
 */
class Engagement_Cloud_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param string $plugin_name   The name of the plugin.
	 * @param string $version       The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Engagement_Cloud_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Engagement_Cloud_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/engagement-cloud-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Engagement_Cloud_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Engagement_Cloud_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		/**
		 * Main storefront JS
		 */
		wp_enqueue_script( 'engagement_cloud_public_js', plugin_dir_url( __FILE__ ) . 'js/engagement-cloud-public.js', array( 'jquery' ), $this->version, true );

		$this->ajax_form_scripts();
		$this->add_tracking_and_roi_script();
		$this->setup_scripts_for_cart_insight();
	}

	/**
	 * Subscribe to newsletter action
	 *
	 * @since 1.2.0
	 */
	public function subscribe_to_newsletter() {
		$subscriber   = new Engagement_Cloud_Subscriber();
		$form_handler = new Engagement_Cloud_Form_Handler( $subscriber );
		$form_handler->subscribe();
	}

	/**
	 * Registration of signup form widget
	 *
	 * @since 1.2.0
	 */
	public function ec_register_signup_widget() {
		register_widget( new Engagement_Cloud_Widget() );
	}

	/**
	 * Initialize ajax_url and nonce params in form ajax request
	 *
	 * @since 1.2.0
	 */
	public function ajax_form_scripts() {
		$props = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'subscribe_to_newsletter' ),
		);
		wp_localize_script( 'engagement_cloud_public_js', 'ec_ajax_handler', $props );
	}


	/**
	 * Purges the cart_id meta for the current user.
	 */
	public function clean_cart_id() {
		if ( is_checkout() && is_wc_endpoint_url( 'order-received' ) ) {
			( new Engagement_Cloud_Cart() )->delete_cart_id();
		}
	}

	/**
	 * Triggered by Woo AJAX events added_to_cart and removed_from_cart.
	 */
	public function update_cart() {
		$cart_insight_handler = new Engagement_Cloud_Cart_Insight_Handler();
		wp_send_json(
			array(
				'data' => $cart_insight_handler->get_data(),
			)
		);
	}

	/**
	 * Adds tracking and roi script in the footer.
	 *
	 * @since 1.2.0
	 */
	private function add_tracking_and_roi_script() {
		if ( ! get_option(
			'engagement_cloud_for_woocommerce_settings_enable_site_and_roi_tracking',
			Engagement_Cloud_Bootstrapper::DEFAULT_SITE_AND_ROI_TRACKING_ENABLED
		) ) {
			return;
		}

		$region = get_option(
			'engagement_cloud_for_woocommerce_settings_region',
			Engagement_Cloud_Bootstrapper::DEFAULT_REGION
		);

		$src = sprintf( '//r%s-t.trackedlink.net/_dmpt.js', $region );
		wp_enqueue_script( 'tracking', $src, array(), $this->version, true );

		if ( is_checkout() && is_wc_endpoint_url( 'order-received' ) ) {
			$order_id = absint( get_query_var( 'order-received' ) );
			$this->trigger_roi_script( $order_id );
		}
	}

	/**
	 * Triggers the roi script with the order details.
	 *
	 * @param int $order_id  Used to fetch the order details.
	 *
	 * @since 1.2.0
	 */
	private function trigger_roi_script( $order_id ) {
		$roi_data_provider = new Engagement_Cloud_Roi();

		if ( $order_id ) {
			$order_data = $roi_data_provider->get_order_data( $order_id );
			wp_enqueue_script( 'roi_tracking_js', plugin_dir_url( __FILE__ ) . 'js/roi-tracking.js', array(), $this->version, true );
			wp_localize_script( 'roi_tracking_js', 'order_data', $order_data );
		}
	}

	/**
	 * Set up scripts for Web Behaviour Tracking and Cart Insight.
	 */
	private function setup_scripts_for_cart_insight() {

		$wbt_profile_id = get_option(
			'engagement_cloud_for_woocommerce_settings_web_behaviour_tracking_profile_id'
		);

		if ( ! $wbt_profile_id ) {
			return;
		}

		wp_enqueue_script( 'wbt', plugin_dir_url( __FILE__ ) . 'js/tracking/web-behaviour-tracking.js', array(), $this->version, true );
		wp_localize_script(
			'wbt',
			'wbt_data',
			array(
				'profile_id' => $wbt_profile_id,
			)
		);

		$cart_insight_handler = new Engagement_Cloud_Cart_Insight_Handler();

		wp_enqueue_script( 'cart_insight', plugin_dir_url( __FILE__ ) . 'js/tracking/cart-insight.js', array( 'jquery' ), $this->version, true );
		wp_localize_script(
			'cart_insight',
			'cart_insight',
			array(
				'data'     => $cart_insight_handler->get_data(),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}
}
