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

namespace Dotdigital_WooCommerce\Includes\client;

use Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Encryptor;
use Dotdigital\V2\Client;
use Dotdigital_WooCommerce\Includes\Exceptions\Dotdigital_WooCommerce_Password_Validation_Exception;
use Dotdigital_WooCommerce\Includes\Exceptions\Dotdigital_WooCommerce_Username_Validation_Exception;
use Dotdigital_WooCommerce\Includes\Exceptions\Dotdigital_WooCommerce_Validation_Exception;
use Http\Client\Exception;

/**
 * Class Dotdigital_WooCommerce_Account_info
 */
class Dotdigital_WooCommerce_Account_Info {

	/**
	 * The plugin name.
	 *
	 * @var string $plugin_name
	 * @access private
	 */
	private $plugin_name;

	/**
	 * The client.
	 *
	 * @var Client $client
	 * @access private
	 */
	private $client;

	/**
	 * The encryptor.
	 *
	 * @var Dotdigital_WooCommerce_Encryptor $encryptor
	 * @access private
	 */
	private $encryptor;

	/**
	 * The credentials.
	 *
	 * @var array $credentials
	 * @access private
	 */
	private $credentials;

	/**
	 * Dotdigital_WooCommerce_Admin_Settings_Handler constructor.
	 *
	 * @param   string $plugin_name The plugin name.
	 */
	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;
		$this->encryptor = new Dotdigital_WooCommerce_Encryptor();
		$this->client = new Client();
		$this->credentials = get_option( Dotdigital_WooCommerce_Config::API_CREDENTIALS_PATH ) ?? array();
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
			$this->credentials = $credentials;
		}

		if ( empty( $this->get_api_user() ) && empty( $this->get_api_password() ) ) {
			throw new Dotdigital_WooCommerce_Validation_Exception( '', 200 );
		}

		if ( empty( $this->get_api_user() ) && ! empty( $this->get_api_password() ) ) {
			throw new Dotdigital_WooCommerce_Username_Validation_Exception( 'Please enter a valid API username', 422 );
		}

		if ( empty( $this->get_api_password() ) && ! empty( $this->get_api_user() ) ) {
			throw new Dotdigital_WooCommerce_Password_Validation_Exception( 'Please enter a valid API password', 422 );
		}

		$this->client::setApiUser( $this->get_api_user() );
		$this->client::setApiPassword( $this->get_api_password() );
		$this->client::setApiEndpoint( $this->get_api_endpoint() );

		try {
			$response = $this->client->accountInfo->show();
			$account_properties = $response->getProperties();
			$api_endpoint_index = array_search( 'ApiEndpoint', array_column( $account_properties, 'name' ) );
			$this->store_api_endpoint( $account_properties[ $api_endpoint_index ]['value'] );
			$this->display_notice(
				sprintf(
					'Your credentials are valid, connected to %s using account (%s)',
					$this->get_api_endpoint(),
					$this->get_api_user()
				),
				'success'
			);
		} catch ( \Exception $exception ) {
			$this->store_api_endpoint( null );
			throw new ResponseValidationException( $exception->getMessage(), 422 );
		}
	}

	/**
	 * Get the API endpoint from the passed credentials array or the database
	 *
	 * @return string
	 */
	public function get_api_endpoint(): string {
		$host = get_option( "{$this->plugin_name}_api_endpoint" );
		if ( ! empty( $host ) ) {
			return $host;
		}
		return Dotdigital_WooCommerce_Config::API_ENDPOINT;
	}

	/**
	 * Get the API user from the passed credentials array or the database
	 *
	 * @return string|null
	 */
	private function get_api_user() {
		if ( ! empty( $this->credentials ) && ! empty( $this->credentials['username'] ) ) {
			return $this->credentials['username'];
		}

		return null;
	}

	/**
	 * Get the API password from the passed credentials array or the database
	 *
	 * @return bool|string|null
	 */
	private function get_api_password() {
		if ( ! empty( $this->credentials ) && ! empty( $this->credentials['password'] ) ) {
			return $this->encryptor->decrypt( $this->credentials['password'] );
		}

		return null;
	}

	/**
	 * Store the API endpoint in the database
	 *
	 * @param string|null $api_endpoint The API endpoint.
	 * @return void
	 */
	public function store_api_endpoint( $api_endpoint ) {
		update_option( "{$this->plugin_name}_api_endpoint", $api_endpoint );
	}
}
