<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes
 * @author     dotdigital <integrations@dotdigital.com>
 */

namespace Engagement_Cloud\Includes;

use Engagement_Cloud\Admin\Engagement_Cloud_Admin;
use Engagement_Cloud\Admin\Engagement_Cloud_Upgrader;
use Engagement_Cloud\Includes\Platforms\Engagement_Cloud_WooCommerce;
use Engagement_Cloud\Pub\Engagement_Cloud_Public;
use Engagement_Cloud\Includes\Engagement_Cloud_Rest_Api;
use Engagement_Cloud\Includes\Widgets\Engagement_Cloud_Widget;

/**
 * Class Engagement_Cloud
 */
class Engagement_Cloud {

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The path of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_path    The path of the plugin.
	 */
	protected $plugin_path;

	/**
	 * Engagement Cloud URL.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $webapp_url    Engagement Cloud URL.
	 */
	protected $webapp_url;

	/**
	 * Engagement Cloud tracking URL.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $tracking_url    Engagement Cloud tracking URL.
	 */
	protected $tracking_url;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Engagement_Cloud_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $plugin_path The path of the plugin.
	 * @param string $webapp_url Engagement Cloud URL.
	 * @param string $version The plugin version.
	 * @param string $tracking_url The tracking URL.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $plugin_path, $webapp_url, $version, $tracking_url ) {

		$this->plugin_name  = $plugin_name;
		$this->plugin_path  = $plugin_path;
		$this->webapp_url   = $webapp_url;
		$this->version      = $version;
		$this->tracking_url = $tracking_url;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_upgrade_hook();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_validation_hooks();
		$this->define_woocommerce_hooks();
		$this->initialise_rest_api();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Engagement_Cloud_Loader. Orchestrates the hooks of the plugin.
	 * - Engagement_Cloud_i18n. Defines internationalization functionality.
	 * - Engagement_Cloud_Admin. Defines all hooks for the admin area.
	 * - Engagement_Cloud_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new Engagement_Cloud_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Engagement_Cloud_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Engagement_Cloud_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Define an upgrade hook.
	 */
	private function define_upgrade_hook() {

		$plugin_upgrader = new Engagement_Cloud_Upgrader( $this->plugin_name, $this->version, $this->tracking_url );

		/**
		 * Check for an upgrade whenever admin is loaded.
		 * To be removed when updates can happen via plugin list.
		 */
		$this->loader->add_action( 'admin_init', $plugin_upgrader, 'upgrade_check' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Engagement_Cloud_Admin( $this->plugin_name, $this->version, $this->webapp_url );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
	}

	/**
	 * Register all of the hooks related to the verification of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_validation_hooks() {

		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			$plugin_validator = new Engagement_Cloud_Validator( $this->plugin_name, $this->plugin_path );

			$this->loader->add_action( 'admin_init', $plugin_validator, 'self_deactivate' );
			$this->loader->add_action( 'admin_menu', $plugin_validator, 'remove_admin_menu_page' );
			$this->loader->add_action( 'admin_notices', $plugin_validator, 'plugin_activation_failure_message' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Engagement_Cloud_Public( $this->plugin_name, $this->version );

		$this->loader->add_action( 'widgets_init', $plugin_public, 'ec_register_signup_widget' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_subscribe_to_newsletter', $plugin_public, 'subscribe_to_newsletter' );
		$this->loader->add_action( 'wp_ajax_nopriv_subscribe_to_newsletter', $plugin_public, 'subscribe_to_newsletter' );
		$this->loader->add_action( 'wp_ajax_update_cart', $plugin_public, 'update_cart' );
		$this->loader->add_action( 'wp_ajax_nopriv_update_cart', $plugin_public, 'update_cart' );

		$this->loader->add_action( 'wp_footer', $plugin_public, 'clean_cart_id' );
	}

	/**
	 * Register all of the hooks related to the WooCommerce plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_woocommerce_hooks() {

		$plugin_woocommerce = new Engagement_Cloud_WooCommerce();

		$this->loader->add_action( 'woocommerce_register_form', $plugin_woocommerce, 'engagement_cloud_render_register_marketing_checkbox', 5 );
		$this->loader->add_action( 'user_register', $plugin_woocommerce, 'engagement_cloud_handle_register_marketing_checkbox', 5 );

		$this->loader->add_action( 'woocommerce_after_checkout_billing_form', $plugin_woocommerce, 'engagement_cloud_render_checkout_marketing_checkbox', 5 );
		$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_woocommerce, 'engagement_cloud_handle_checkout_subscription', 5 );

		$this->loader->add_action( 'woocommerce_update_cart_action_cart_updated', $plugin_woocommerce, 'cart_updated', 5 );
		$this->loader->add_action( 'woocommerce_add_to_cart', $plugin_woocommerce, 'cart_updated', 5 );
		$this->loader->add_action( 'woocommerce_cart_item_removed', $plugin_woocommerce, 'cart_updated', 5 );
		$this->loader->add_action( 'woocommerce_cart_item_restored', $plugin_woocommerce, 'cart_updated', 5 );

		$this->loader->add_action( 'woocommerce_set_cart_cookies', $plugin_woocommerce, 'ec_cart_init', 5 );
	}

	/**
	 * Add custom API endpoints
	 */
	private function initialise_rest_api() {
		$service = new Engagement_Cloud_Rest_Api( $this->plugin_name );
		$this->loader->add_action( 'rest_api_init', $service, 'register_routes' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
}
