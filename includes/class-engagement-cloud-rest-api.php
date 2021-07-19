<?php
/**
 * Defines and handles REST API endpoints.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes
 */

namespace Engagement_Cloud\Includes;

use Engagement_Cloud\Includes\RestApi\Engagement_Cloud_Rest_Unsubscribe;
use Engagement_Cloud\Engagement_Cloud_Bootstrapper;
use WP_REST_Response;

/**
 * Class Engagement_Cloud_Rest_Api
 */
class Engagement_Cloud_Rest_Api {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	private $namespace;

	/**
	 * API version.
	 *
	 * @var string
	 */
	private $api_version = 'v1';

	/**
	 * Engagement_Cloud_Rest_Api constructor.
	 *
	 * @param string $plugin_name The name of the plugin.
	 */
	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;
		$this->namespace   = $plugin_name . '/' . $this->api_version;
	}

	/**
	 * Register routes.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/unsubscribe',
			array(
				'methods'  => 'GET',
				'callback' => array( new Engagement_Cloud_Rest_Unsubscribe( $this->plugin_name ), 'unsubscribe' ),
				'permission_callback' => '__return_true',
				'args'     => array(
					'email'     => array(
						'required' => true,
					),
					'plugin_id' => array(
						'required'          => true,
						'validate_callback' => array( $this, 'validate_plugin_id' ),
					),
				),
			)
		);
	}

	/**
	 * Validates the plugin_id param against the stored value in wp_dotmailer_email_marketing.
	 *
	 * @param string $plugin_id The plugin_id.
	 *
	 * @return bool
	 */
	public function validate_plugin_id( $plugin_id ) {
		global $wpdb;
		$email_marketing_table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::EMAIL_MARKETING_TABLE_NAME;

		return $plugin_id === $wpdb->get_var( "SELECT PluginID FROM $email_marketing_table_name" ); // phpcs:ignore WordPress.DB
	}

	/**
	 * Fetch the root REST callback url for the current blog.
	 *
	 * @return string
	 */
	public function get_rest_callback_url() {
		return esc_url( get_rest_url( null, '/' ) . $this->namespace );
	}

	/**
	 * Return a formatted JSON response.
	 *
	 * @param array $data Array of response data.
	 * @param int   $status Response status code.
	 * @return WP_REST_Response
	 */
	protected function ec_rest_response( $data, $status = 200 ) {
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		$response = new WP_REST_Response( $data );
		$response->set_status( $status );
		return $response;
	}

}
