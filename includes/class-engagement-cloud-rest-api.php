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

use Engagement_Cloud\Includes\RestApi\Engagement_Cloud_Rest_Configurations;
use Engagement_Cloud\Includes\RestApi\Engagement_Cloud_Rest_Unsubscribe;
use Engagement_Cloud\Engagement_Cloud_Bootstrapper;
use WP_REST_Response;
use WP_REST_Controller;
use WP_Error;

/**
 * Class Engagement_Cloud_Rest_Api
 */
class Engagement_Cloud_Rest_Api {

	const EMAIL_PARAM = 'email';
	const WBT_STATUS_PARAM = 'wbt_enabled';
	const WBT_PROFILE_ID_PARAM = 'wbt_profile_id';
	const PROGRAM_ID_PARAM = 'program_id';
	const CART_DELAY_PARAM = 'cart_delay';
	const ALLOW_NON_SUBSCRIBERS_PARAM = 'allow_non_subscribers';
	const PLUGIN_ID_PARAM = 'plugin_id';

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
	protected $namespace;

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
		$this->namespace   = 'wc-' . $plugin_name . '/' . $this->api_version;
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
					self::EMAIL_PARAM     => array(
						'required' => true,
					),
					self::PLUGIN_ID_PARAM => array(
						'required'          => true,
						'validate_callback' => array( $this, 'validate_plugin_id' ),
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/configure-store-settings',
			array(
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => array( new Engagement_Cloud_Rest_Configurations( $this->plugin_name ), 'set_configurations' ),
				'permission_callback' => array( $this, 'configure_settings_permissions_check' ),
				'args' => array(
					self::WBT_STATUS_PARAM     => array(
						'type' => 'boolean',
					),
					self::WBT_PROFILE_ID_PARAM     => array(
						'type' => 'string',
					),
					self::PROGRAM_ID_PARAM     => array(
						'type' => 'number',
					),
					self::CART_DELAY_PARAM     => array(
						'type' => 'number',
					),
					self::ALLOW_NON_SUBSCRIBERS_PARAM     => array(
						'type' => 'boolean',
					),
					self::PLUGIN_ID_PARAM => array(
						'required'          => true,
						'validate_callback' => array( $this, 'validate_plugin_id' ),
					),
				),
			)
		);
	}

	/**
	 * Check if current user perform the action.
	 *
	 * @return bool|WP_Error
	 */
	public function configure_settings_permissions_check() {
		if ( ! ( current_user_can( 'manage_options' ) ) ) {
			return new WP_Error(
				'permissions_error',
				__( 'Cannot update settings.', 'ec' ),
				array( 'status' => 400 )
			);
		}

		return true;
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
