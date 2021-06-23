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
	 * @param    string $plugin_name 	The name of this plugin.
	 * @param    string $version    	The version of this plugin.
	 * @param    string $webapp_url    	Engagement Cloud URL.
	 */
	public function __construct( $plugin_name, $version, $webapp_url ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->webapp_url = $webapp_url;

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
		require_once( 'partials/engagement-cloud-admin-display.php' );
		$admin_display = new Engagement_Cloud_Admin_Display( $this->plugin_name, $this->webapp_url );

		add_menu_page(
			'dotdigital Engagement Cloud',
			'Engagement Cloud',
			'manage_options',
			$this->plugin_name,
			array( $admin_display, 'display_plugin_setup_page' ),
			plugins_url('../assets/DD-roundel-16x16.png',__FILE__),
			55.5
		);
	}
}
