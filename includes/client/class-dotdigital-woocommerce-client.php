<?php
/**
 * Initializes the client.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.4.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/client
 */

namespace Dotdigital_WooCommerce\Includes\Client;

use Dotdigital\V2\Client;
use Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Encryptor;

/**
 * Class Dotdigital_WooCommerce_Lists
 */
class Dotdigital_WooCommerce_Client {

	/**
	 * The plugin name.
	 *
	 * @var string $plugin_name
	 * @access private
	 */
	protected $plugin_name;

	/**
	 * The client.
	 *
	 * @var Client $client
	 * @access private
	 */
	protected $client;

	/**
	 * The encryptor.
	 *
	 * @var Dotdigital_WooCommerce_Encryptor $encryptor
	 * @access private
	 */
	protected $encryptor;

	/**
	 * The credentials.
	 *
	 * @var array $credentials
	 * @access private
	 */
	protected $credentials;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->plugin_name = Dotdigital_WooCommerce_Bootstrapper::$plugin_name;
		$this->encryptor = new Dotdigital_WooCommerce_Encryptor();
		$this->client = new Client();
		$this->credentials = get_option( Dotdigital_WooCommerce_Config::API_CREDENTIALS_PATH ) ?? array();
		$this->setup_client();
	}

	/**
	 * Get client.
	 *
	 * @return Client
	 */
	public function get_client(): Client {
		return $this->client;
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
	public function get_api_user() {
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
	public function get_api_password() {
		if ( ! empty( $this->credentials ) && ! empty( $this->credentials['password'] ) ) {
			return $this->encryptor->decrypt( $this->credentials['password'] );
		}

		return null;
	}

	/**
	 * Set credentials.
	 *
	 * @param array $credentials dotdigital creds.
	 */
	public function set_credentials( array $credentials ) {
		$this->credentials = $credentials;
		$this->setup_client();
	}

	/**
	 * Set up client.
	 *
	 * @return void
	 */
	private function setup_client() {
		$this->client::setApiUser( (string) $this->get_api_user() );
		$this->client::setApiPassword( (string) $this->get_api_password() );
		$this->client::setApiEndpoint( (string) $this->get_api_endpoint() );
	}
}
