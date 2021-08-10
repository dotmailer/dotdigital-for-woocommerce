<?php
/**
 * Initialize the EC configurations via the REST API endpoint.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/rest-api
 */

namespace Engagement_Cloud\Includes\RestApi;

use WP_REST_Request;
use Engagement_Cloud\Includes\Engagement_Cloud_Rest_Api;
use Engagement_Cloud\Engagement_Cloud_Bootstrapper;

/**
 * Class Engagement_Cloud_Rest_Configurations
 */
class Engagement_Cloud_Rest_Configurations extends Engagement_Cloud_Rest_Api {

	/**
	 * Engagement_Cloud_Rest_Configurations constructor.
	 *
	 * @param string $plugin_name The name of the plugin.
	 */
	public function __construct( $plugin_name ) {
		parent::__construct( $plugin_name );
	}

	/**
	 * Sets options for plugin configuration.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return \WP_REST_Response
	 */
	public function set_configurations( WP_REST_Request $request ) {
		update_site_option(
			Engagement_Cloud_Bootstrapper::WBT_STATUS_PATH,
			$request->get_param( self::WBT_STATUS_PARAM )
		);

		update_site_option(
			Engagement_Cloud_Bootstrapper::WBT_PROFILE_ID_PATH,
			$request->get_param( self::WBT_PROFILE_ID_PARAM )
		);

		update_site_option(
			Engagement_Cloud_Bootstrapper::PROGRAM_ID_PATH,
			$request->get_param( self::PROGRAM_ID_PARAM )
		);

		update_site_option(
			Engagement_Cloud_Bootstrapper::CART_DELAY_PATH,
			$request->get_param( self::CART_DELAY_PARAM )
		);

		update_site_option(
			Engagement_Cloud_Bootstrapper::ALLOW_NON_SUBSCRIBERS_PATH,
			$request->get_param( self::ALLOW_NON_SUBSCRIBERS_PARAM )
		);

		return $this->ec_rest_response( array( 'success' => true ) );
	}
}
