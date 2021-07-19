<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/admin
 */

namespace Engagement_Cloud\Admin;

use Engagement_Cloud\Admin\Partials\Engagement_Cloud_Admin_Display;
use Engagement_Cloud\Engagement_Cloud_Bootstrapper;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/admin
 * @author     dotdigital <integrations@dotdigital.com>
 */
class Engagement_Cloud_Admin {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
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
	 * Engagement Cloud URL
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $webapp_url    Engagement Cloud URL.
	 */
	private $webapp_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name    The name of this plugin.
	 * @param    string $version        The version of this plugin.
	 * @param    string $webapp_url     Engagement Cloud URL.
	 */
	public function __construct( $plugin_name, $version, $webapp_url ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->webapp_url  = $webapp_url;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/engagement-cloud-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/engagement-cloud-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		* Add a settings page for this plugin to the Settings menu.
		*
		* NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		*
		*        Administration Menus: http://codex.wordpress.org/Administration_Menus
		*
		*/
		require_once 'partials/class-engagement-cloud-admin-display.php';
		$admin_display = new Engagement_Cloud_Admin_Display( $this->plugin_name, $this->webapp_url );
		$icon_svg      = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMiAzMiI+PHBhdGggZD0iTTE2LDIuNzhBMTMuMjIsMTMuMjIsMCwxLDEsMi43OCwxNiwxMy4yMywxMy4yMywwLDAsMSwxNiwyLjc4TTE2LDBBMTYsMTYsMCwxLDAsMzIsMTYsMTYsMTYsMCwwLDAsMTYsMFoiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNMTYsOC4yOUE3Ljc0LDcuNzQsMCwxLDEsOC4yNiwxNiw3Ljc1LDcuNzUsMCwwLDEsMTYsOC4yOW0wLTIuNzhBMTAuNTIsMTAuNTIsMCwxLDAsMjYuNTIsMTYsMTAuNTIsMTAuNTIsMCwwLDAsMTYsNS41MVoiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNMTYsMTMuNzdBMi4yNiwyLjI2LDAsMSwxLDEzLjc1LDE2LDIuMjYsMi4yNiwwLDAsMSwxNiwxMy43N00xNiwxMWE1LDUsMCwxLDAsNSw1LDUsNSwwLDAsMC01LTVaIiBmaWxsPSIjZmZmIi8+PC9zdmc+';

		add_menu_page(
			'dotdigital Engagement Cloud',
			'Engagement Cloud',
			'manage_options',
			$this->plugin_name,
			array( $admin_display, 'display_plugin_setup_page' ),
			'data:image/svg+xml;base64,' . $icon_svg,
			55.5
		);

		add_submenu_page(
			$this->plugin_name,
			'Connect',
			'Connect',
			'manage_options',
			$this->plugin_name
		);

		add_submenu_page(
			$this->plugin_name,
			'dotdigital Engagement Cloud Settings',
			'Settings',
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'display_plugin_settings_page' )
		);
	}

	/**
	 * Display the plugin settings page.
	 *
	 * @since    1.2.0
	 */
	public function display_plugin_settings_page() {
		require_once 'partials/engagement-cloud-admin-settings-display.php';
	}

	/**
	 * Register settings: define the containing section and the fields to go in it.
	 *
	 * @since    1.2.0
	 */
	public function register_settings() {
		/**
		 * Add settings section.
		 */
		add_settings_section(
			'ec_woo_settings_page_general_section',
			'Marketing subscription',
			null,
			$this->plugin_name . '-settings'
		);

		/**
		 * Add settings field [show checkbox at checkout].
		 */
		add_settings_field(
			'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_checkout',
			'Show marketing checkbox at checkout',
			array( $this, 'settings_page_render_checkbox' ),
			$this->plugin_name . '-settings',
			'ec_woo_settings_page_general_section',
			array(
				'id'            => 'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_checkout',
				'name'          => 'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_checkout',
				'default_value' => Engagement_Cloud_Bootstrapper::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT,
			)
		);

		/**
		 * Add settings field [show checkbox at register].
		 */
		add_settings_field(
			'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_register',
			'Show marketing checkbox at register',
			array( $this, 'settings_page_render_checkbox' ),
			$this->plugin_name . '-settings',
			'ec_woo_settings_page_general_section',
			array(
				'id'            => 'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_register',
				'name'          => 'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_register',
				'default_value' => Engagement_Cloud_Bootstrapper::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_REGISTER,
			)
		);

		register_setting(
			$this->plugin_name . '-settings',
			'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_checkout'
		);

		register_setting(
			$this->plugin_name . '-settings',
			'engagement_cloud_for_woocommerce_settings_show_marketing_checkbox_at_register'
		);

		/**
		 * Add settings field [checkbox text].
		 */
		add_settings_field(
			'engagement_cloud_for_woocommerce_settings_marketing_checkbox_text',
			'Marketing checkbox text',
			array( $this, 'settings_page_render_text_input' ),
			$this->plugin_name . '-settings',
			'ec_woo_settings_page_general_section',
			array(
				'id'            => 'engagement_cloud_for_woocommerce_settings_marketing_checkbox_text',
				'name'          => 'engagement_cloud_for_woocommerce_settings_marketing_checkbox_text',
				'default_value' => Engagement_Cloud_Bootstrapper::DEFAULT_MARKETING_CHECKBOX_TEXT,
			)
		);

		register_setting(
			$this->plugin_name . '-settings',
			'engagement_cloud_for_woocommerce_settings_marketing_checkbox_text'
		);
	}

	/**
	 * A template for a checkbox field.
	 *
	 * @param array $args An array of arguments.
	 */
	public function settings_page_render_checkbox( $args ) {
		$value = get_option( $args['id'], $args['default_value'] );
		echo '<input type="checkbox" id="' . $args['id'] . '" name="' . $args['name'] . '" value="1"' . checked( 1, $value, false ) . '/>'; // phpcs:ignore WordPress.Security
	}

	/**
	 * A template for a text input field.
	 *
	 * @param array $args An array of arguments.
	 */
	public function settings_page_render_text_input( $args ) {
		$value = get_option( $args['id'], $args['default_value'] );
		echo '<input type="text" id="' . $args['id'] . '" name="' . $args['name'] . '" value="' . esc_attr( $value ) . '" size="40" />'; // phpcs:ignore WordPress.Security
	}
}
