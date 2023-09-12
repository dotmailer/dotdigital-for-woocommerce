<?php
/**
 * Sanitization handlers for admin settings.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.4.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/client
 */

namespace Dotdigital_WooCommerce\Includes\Client;

use Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper;
use Dotdigital_WooCommerce\Includes\Exceptions\Dotdigital_WooCommerce_Password_Validation_Exception;
use Dotdigital_WooCommerce\Includes\Exceptions\Dotdigital_WooCommerce_Username_Validation_Exception;
use Dotdigital_WooCommerce\Includes\Exceptions\Dotdigital_WooCommerce_Validation_Exception;

/**
 * Class Dotdigital_WooCommerce_Account_info
 */
class Dotdigital_WooCommerce_Account_Info {

	/**
	 * Plugin name.
	 *
	 * @var string $plugin_name
	 * @access private
	 */
	private $plugin_name;

	/**
	 * Dotdigital client.
	 *
	 * @var Dotdigital_WooCommerce_Client $dotdigital_client
	 * @access private.
	 */
	private $dotdigital_client;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->plugin_name = Dotdigital_WooCommerce_Bootstrapper::$plugin_name;
		$this->dotdigital_client = new Dotdigital_WooCommerce_Client();
	}

	/**
	 * Trigger settings_error hook with the passed message and type
	 *
	 * @param string $message The message to display.
	 * @param string $type   The type of message.
	 *
	 * @return void
	 */
	public function display_notice( string $message, string $type ) {
		add_settings_error(
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings',
			$message,
			$type
		);
	}

	/**
	 * Validate the credentials and display a notice
	 *
	 * @param mixed|null $credentials The credentials.
	 *
	 * @return void
	 * @throws Dotdigital_WooCommerce_Password_Validation_Exception Password validation exception.
	 * @throws Dotdigital_WooCommerce_Username_Validation_Exception Username validation exception.
	 * @throws Dotdigital_WooCommerce_Validation_Exception Validation exception.
	 * @throws ResponseValidationException Response validation exception.
	 */
	public function validate_credentials( $credentials = null ) {

		if ( ! is_null( $credentials ) ) {
			$this->dotdigital_client->set_credentials( $credentials );
		}

		if ( empty( $this->dotdigital_client->get_api_user() ) && empty( $this->dotdigital_client->get_api_password() ) ) {
			throw new Dotdigital_WooCommerce_Validation_Exception( '', 200 );
		}

		if ( empty( $this->dotdigital_client->get_api_user() ) && ! empty( $this->dotdigital_client->get_api_password() ) ) {
			throw new Dotdigital_WooCommerce_Username_Validation_Exception( 'Please enter a valid API username', 422 );
		}

		if ( empty( $this->dotdigital_client->get_api_password() ) && ! empty( $this->dotdigital_client->get_api_user() ) ) {
			throw new Dotdigital_WooCommerce_Password_Validation_Exception( 'Please enter a valid API password', 422 );
		}

		try {
			$response = $this->dotdigital_client->get_client()->accountInfo->show();
			$account_properties = $response->getProperties();
			$api_endpoint_index = array_search( 'ApiEndpoint', array_column( $account_properties, 'name' ) );
			$this->dotdigital_client->store_api_endpoint( $account_properties[ $api_endpoint_index ]['value'] );
			$this->display_notice(
				sprintf(
					'Your credentials are valid, connected to %s using account (%s)',
					$this->dotdigital_client->get_api_endpoint(),
					$this->dotdigital_client->get_api_user()
				),
				'success'
			);
		} catch ( ResponseValidationException $exception ) {
			$this->dotdigital_client->store_api_endpoint( null );
			throw $exception;
		}
	}
}
