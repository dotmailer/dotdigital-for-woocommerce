<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/public
 */

namespace Dotdigital_WooCommerce\Pub;

use Dotdigital_WooCommerce\Includes\Cart\Dotdigital_WooCommerce_Cart;
use Dotdigital_WooCommerce\Includes\Cart\Dotdigital_WooCommerce_Cart_Insight_Handler;
use Dotdigital_WooCommerce\Includes\Tracking\Dotdigital_WooCommerce_Last_Browsed_Products;
use Dotdigital_WooCommerce\Includes\Widgets\Dotdigital_WooCommerce_Widget;
use Dotdigital_WooCommerce\Includes\Tracking\Dotdigital_WooCommerce_Roi;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/public
 * @author     dotdigital <integrations@dotigital.com>
 */
class Dotdigital_WooCommerce_Public {

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
		 * defined in Dotdigital_WooCommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dotdigital_WooCommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dotdigital-woocommerce-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-public-intl', plugin_dir_url( __FILE__ ) . 'css/dotdigital-woocommerce-public-intl.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( is_admin() ) {
			return;
		}

		/**
		 * Main storefront JS
		 */
		wp_enqueue_script( 'dotdigital_woocommerce_public_js', plugin_dir_url( __FILE__ ) . 'js/dotdigital-woocommerce-public.js', array( 'jquery' ), $this->version, true );

		$this->ajax_form_scripts();
		$this->add_tracking_and_roi_script();
		$this->setup_scripts_for_cart_insight();
		$this->add_intl_phone_validation_script();
		$this->add_last_browsed_products_script_data();
	}

	/**
	 * Registration of signup form widget
	 *
	 * @since 1.2.0
	 */
	public function dd_register_signup_widget() {
		register_widget( new Dotdigital_WooCommerce_Widget() );
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
		wp_localize_script( 'dotdigital_woocommerce_public_js', 'dd_ajax_handler', $props );
	}

	/**
	 * Purges the cart_id meta for the current user.
	 */
	public function clean_cart_id() {
		if ( is_checkout() && is_wc_endpoint_url( 'order-received' ) ) {
			( new Dotdigital_WooCommerce_Cart() )->delete_cart_id();
		}
	}

	/**
	 * Triggered by Woo AJAX events added_to_cart and removed_from_cart.
	 */
	public function update_cart() {
		$cart_insight_handler = new Dotdigital_WooCommerce_Cart_Insight_Handler();
		wp_send_json(
			array(
				'data' => $cart_insight_handler->get_data(),
			)
		);
	}

	/**
	 * Set the cart id.
	 */
	public function dd_cart_init() {
		if ( is_admin() ) {
			return;
		}

		$cart = new Dotdigital_WooCommerce_Cart();

		if ( ! $cart->get_cart_id() ) {
			$cart->set_cart_id();
		}
	}

	/**
	 * Adds tracking and roi script in the footer.
	 *
	 * @since 1.2.0
	 */
	public function add_tracking_and_roi_script() {
		if ( ! get_option(
			Dotdigital_WooCommerce_Config::SITE_AND_ROI_TRACKING,
			Dotdigital_WooCommerce_Config::DEFAULT_SITE_AND_ROI_TRACKING_ENABLED
		) ) {
			return;
		}

		$region = get_option(
			'dotdigital_for_woocommerce_settings_region',
			Dotdigital_WooCommerce_Config::DEFAULT_REGION
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
		$roi_data_provider = new Dotdigital_WooCommerce_Roi();

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
			Dotdigital_WooCommerce_Config::WBT_PROFILE_ID_PATH
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

		$cart_insight_handler = new Dotdigital_WooCommerce_Cart_Insight_Handler();

		wp_enqueue_script( 'cart_insight', plugin_dir_url( __FILE__ ) . 'js/tracking/cart-insight.js', array( 'jquery' ), $this->version, true );
		wp_localize_script(
			'cart_insight',
			'cart_insight',
			array(
				'data'     => $cart_insight_handler->get_data(),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'email_capture' ),
			)
		);
	}

	/**
	 * Localize and enqueue scripts for Sms phone number validation
	 */
	private function add_intl_phone_validation_script() {

		if (
			! ( is_checkout() && get_option( Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_CHECKOUT ) ) &&
			! ( is_account_page() && get_option( Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION ) )
		) {
			return;
		}

		wp_localize_script(
			'dotdigital_woocommerce_public_js',
			'dotdigital_intl',
			array(
				'plugin_name' => $this->plugin_name,
				'allowed_countries' => WC()->countries->get_allowed_countries(),
				'default_country' => WC()->countries->get_base_country(),
				'utils_script' => plugin_dir_url( __FILE__ ) . 'js/dotdigital-woocommerce-public-intl-utils.min.js',
				'error_messages' => apply_filters(
					'dotdigital_woocommerce_intl_error_map',
					array(
						'invalid_phone' => __( 'Invalid phone number', 'dotdigital-for-woocommerce' ),
						'invalid_country_code' => __( 'Invalid country code', 'dotdigital-for-woocommerce' ),
						'invalid_to_short' => __( 'Invalid phone number, too short', 'dotdigital-for-woocommerce' ),
						'invalid_too_long' => __( 'Invalid phone number, too long', 'dotdigital-for-woocommerce' ),
					)
				),
			)
		);
		wp_enqueue_script(
			$this->plugin_name . '-public-intl',
			plugin_dir_url( __FILE__ ) . 'js/dotdigital-woocommerce-public-intl.min.js',
			array( 'jquery', 'wc-blocks-checkout' ),
			$this->version,
			true
		);
	}

	/**
	 * Initializes wbt script with last browsed product data.
	 */
	private function add_last_browsed_products_script_data() {
		$product = wc_get_product();
		if ( ! is_product() || ! $product ) {
			return;
		}

		$last_browsed_products_handler = new Dotdigital_WooCommerce_Last_Browsed_Products();

		wp_localize_script(
			'wbt',
			'product_data',
			array(
				'data' => $last_browsed_products_handler->get_last_product( $product->get_id() ),
			)
		);
	}
}
