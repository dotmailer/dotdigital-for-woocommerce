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

use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Subscriber;
use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Form_Handler;
use Engagement_Cloud\Includes\Widgets\Engagement_Cloud_Widget;

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

		wp_enqueue_script( 'engagement_cloud_public_js', plugin_dir_url( __FILE__ ) . 'js/engagement-cloud-public.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Initialize ajax_url and nonce params in form ajax request
	 *
	 * @since 1.2.0
	 */
	public function ajax_form_scripts() {
		$translation_array = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'subscribe_to_newsletter' ),
		);
		wp_localize_script( 'engagement_cloud_public_js', 'cpm_object', $translation_array );
	}

	/**
	 * Subscribe to newsletter action
	 *
	 * @since 1.2.0
	 */
	public function subscribe_to_newsletter() {
		$subscriber  = new Engagement_Cloud_Subscriber();
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
}
