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
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes
 * @author     dotdigital <integrations@dotdigital.com>
 */

namespace Dotdigital_WooCommerce\Includes;

use Dotdigital_WooCommerce\Admin\Dotdigital_WooCommerce_Admin;
use Dotdigital_WooCommerce\Admin\Dotdigital_WooCommerce_Upgrader;
use Dotdigital_WooCommerce\Includes\Platforms\Dotdigital_WooCommerce as Dotdigital_Platform;
use Dotdigital_WooCommerce\Includes\Session\Dotdigital_WooCommerce_Session_Updater;
use Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Form_Handler;
use Dotdigital_WooCommerce\Pub\Dotdigital_WooCommerce_Public;


/**
 * Class Dotdigital_WooCommerce
 */
class Dotdigital_WooCommerce {

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
	 * Account login URL.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $webapp_url    dotdigital URL.
	 */
	protected $webapp_url;

	/**
	 * Tracking URL.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $tracking_url    dotdigital tracking URL.
	 */
	protected $tracking_url;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dotdigital_WooCommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * @param string $webapp_url dotdigital URL.
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
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dotdigital_WooCommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Dotdigital_WooCommerce_i18n. Defines internationalization functionality.
	 * - Dotdigital_WooCommerce_Admin. Defines all hooks for the admin area.
	 * - Dotdigital_WooCommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new Dotdigital_WooCommerce_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dotdigital_WooCommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dotdigital_WooCommerce_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Define an upgrade hook.
	 */
	private function define_upgrade_hook() {

		$plugin_upgrader = new Dotdigital_WooCommerce_Upgrader( $this->plugin_name, $this->version, $this->tracking_url );

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

		$plugin_admin = new Dotdigital_WooCommerce_Admin( $this->plugin_name, $this->version, $this->webapp_url );

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

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			return;
		}

		/**
		 * If Woo is network activated in multisite, that's OK.
		 */
		if ( is_multisite() ) {
			$plugins = get_site_option( 'active_sitewide_plugins' );
			if ( isset( $plugins['woocommerce/woocommerce.php'] ) ) {
				return;
			}
		}

		$plugin_validator = new Dotdigital_WooCommerce_Validator( $this->plugin_name, $this->plugin_path );

		$this->loader->add_action( 'admin_init', $plugin_validator, 'self_deactivate' );
		$this->loader->add_action( 'admin_menu', $plugin_validator, 'remove_admin_menu_page' );
		$this->loader->add_action( 'admin_notices', $plugin_validator, 'plugin_activation_failure_message' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dotdigital_WooCommerce_Public( $this->plugin_name, $this->version );

		$this->loader->add_action( 'widgets_init', $plugin_public, 'dd_register_signup_widget' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_update_cart', $plugin_public, 'update_cart' );
		$this->loader->add_action( 'wp_ajax_nopriv_update_cart', $plugin_public, 'update_cart' );

		$this->loader->add_action( 'wp_footer', $plugin_public, 'clean_cart_id' );

		$subscribe_form_handler = new Dotdigital_WooCommerce_Form_Handler();

		$this->loader->add_action( 'wp_ajax_subscribe_to_newsletter', $subscribe_form_handler, 'execute' );
		$this->loader->add_action( 'wp_ajax_nopriv_subscribe_to_newsletter', $subscribe_form_handler, 'execute' );

		$session_updater = new Dotdigital_WooCommerce_Session_Updater();

		$this->loader->add_action( 'wp_ajax_update_session', $session_updater, 'execute' );
		$this->loader->add_action( 'wp_ajax_nopriv_update_session', $session_updater, 'execute' );
	}

	/**
	 * Register all of the hooks related to the WooCommerce plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_woocommerce_hooks() {

		$plugin_woocommerce = new Dotdigital_Platform();

		$this->loader->add_action( 'woocommerce_register_form', $plugin_woocommerce, 'Dotdigital_WooCommerce_render_register_marketing_checkbox', 5 );
		$this->loader->add_action( 'user_register', $plugin_woocommerce, 'Dotdigital_WooCommerce_handle_register_marketing_checkbox', 5 );

		$this->loader->add_action( 'woocommerce_after_checkout_billing_form', $plugin_woocommerce, 'Dotdigital_WooCommerce_render_checkout_marketing_checkbox', 5 );
		$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_woocommerce, 'Dotdigital_WooCommerce_handle_checkout_subscription', 5 );

		$this->loader->add_action( 'woocommerce_set_cart_cookies', $plugin_woocommerce, 'dd_cart_init', 5 );

		$this->loader->add_action( 'woocommerce_before_single_product_summary', $plugin_woocommerce, 'last_browsed_products' );
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
