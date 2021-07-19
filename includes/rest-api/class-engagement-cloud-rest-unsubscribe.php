<?php
/**
 * Unsubscribes an email address submitted via the REST API endpoint.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/rest-api
 */

namespace Engagement_Cloud\Includes\RestApi;

use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Subscriber;
use Engagement_Cloud\Includes\Engagement_Cloud_Rest_Api;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Engagement_Cloud_Rest_Unsubscribe
 */
class Engagement_Cloud_Rest_Unsubscribe extends Engagement_Cloud_Rest_Api {

	/**
	 * The Engagement_Cloud_Subscriber class.
	 *
	 * @var Engagement_Cloud_Subscriber
	 */
	private $subscriber;

	/**
	 * Engagement_Cloud_Rest_Unsubscribe constructor.
	 *
	 * @param string $plugin_name The name of the plugin.
	 */
	public function __construct( $plugin_name ) {
		parent::__construct( $plugin_name );
		$this->subscriber = new Engagement_Cloud_Subscriber();
	}

	/**
	 * Handle the unsubscribe.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response
	 */
	public function unsubscribe( WP_REST_Request $request ) {
		$params = $request->get_params();
		$this->subscriber->unsubscribe( $params['email'] );

		return $this->ec_rest_response( array( 'success' => true ) );
	}
}
