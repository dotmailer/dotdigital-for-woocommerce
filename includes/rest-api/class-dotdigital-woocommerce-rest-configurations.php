<?php
/**
 * Initialize the EC configurations via the REST API endpoint.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/rest-api
 */

namespace Dotdigital_WooCommerce\Includes\RestApi;

use WP_REST_Request;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Rest_Api;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;

/**
 * Class Dotdigital_WooCommerce_Rest_Configurations
 */
class Dotdigital_WooCommerce_Rest_Configurations extends Dotdigital_WooCommerce_Rest_Api {

	/**
	 * Dotdigital_WooCommerce_Rest_Configurations constructor.
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
		update_option(
			Dotdigital_WooCommerce_Config::WBT_STATUS_PATH,
			$request->get_param( self::WBT_STATUS_PARAM )
		);

		update_option(
			Dotdigital_WooCommerce_Config::WBT_PROFILE_ID_PATH,
			$request->get_param( self::WBT_PROFILE_ID_PARAM )
		);

		update_option(
			Dotdigital_WooCommerce_Config::PROGRAM_ID_PATH,
			$request->get_param( self::PROGRAM_ID_PARAM )
		);

		update_option(
			Dotdigital_WooCommerce_Config::CART_DELAY_PATH,
			$request->get_param( self::CART_DELAY_PARAM )
		);

		update_option(
			Dotdigital_WooCommerce_Config::ALLOW_NON_SUBSCRIBERS_PATH,
			$request->get_param( self::ALLOW_NON_SUBSCRIBERS_PARAM )
		);

		return $this->dd_rest_response( array( 'success' => true ) );
	}
}
