<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes
 */

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
	 * @access   public
	 * @var      string    $webapp_url    Engagement Cloud URL.
	 */
	protected $webapp_url;

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
	 * @since    1.0.0
	 *
	 * @param string $plugin_name 	The name of the plugin.
	 * @param string $plugin_path   The path of the plugin.
	 * @param string $webapp_url   	Engagement Cloud URL.
	 */
	public function __construct( $plugin_name, $plugin_path, $webapp_url ) {

		$this->version = '1.0.0';

		$this->plugin_name = $plugin_name;
		$this->plugin_path = $plugin_path;
		$this->webapp_url = $webapp_url;

		$this->load_dependencies();
		$this->set_locale();
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

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-engagement-cloud-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-engagement-cloud-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-engagement-cloud-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-engagement-cloud-public.php';

		/**
		 * The class responsible for defining all actions that occur during plugin's requirement validation.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-engagement-cloud-validator.php';

		/**
		 * The class responsible for defining all actions that occur in woocommerce related
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/platforms/class-engagement-cloud-woocommerce.php';

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

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
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

		$this->loader->add_action( 'woocommerce_checkout_after_customer_details', $plugin_woocommerce, 'engagement_cloud_render_checkout_marketing_checkbox', 5 );
		$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_woocommerce, 'engagement_cloud_handle_checkout_marketing_checkbox', 5 );
		
		$this->loader->add_action( 'woocommerce_update_cart_action_cart_updated', $plugin_woocommerce, 'api2cart_cart_updated', 5 );
		$this->loader->add_action( 'woocommerce_add_to_cart', $plugin_woocommerce, 'api2cart_cart_updated', 5 );
		$this->loader->add_action( 'woocommerce_cart_item_removed', $plugin_woocommerce, 'api2cart_cart_updated', 5 );
		$this->loader->add_action( 'woocommerce_cart_item_restored', $plugin_woocommerce, 'api2cart_cart_updated', 5 );
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
