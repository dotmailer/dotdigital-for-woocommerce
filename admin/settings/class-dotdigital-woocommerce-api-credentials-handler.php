<?php
/**
 * Sanitization handlers for admin settings.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.4.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/admin/settings
 */

namespace Dotdigital_WooCommerce\Admin\Settings;

use Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WooCommerce\Includes\client\Dotdigital_WooCommerce_Account_Info;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Encryptor;
use Dotdigital_WooCommerce\Includes\Exceptions\Dotdigital_WooCommerce_Password_Validation_Exception;
use Dotdigital_WooCommerce\Includes\Exceptions\Dotdigital_WooCommerce_Username_Validation_Exception;
use Dotdigital_WooCommerce\Includes\Exceptions\Dotdigital_WooCommerce_Validation_Exception;
use Dotdigital_WooCommerce\Includes\Exceptions\Password_Validation_Exception;
use Dotdigital_WooCommerce\Includes\Exceptions\Username_Validation_Exception;
use Http\Client\Exception;

/**
 * Class Dotdigital_WooCommerce_Admin_Settings_Handler
 */
class Dotdigital_WooCommerce_Api_Credentials_Handler {

	/**
	 * The plugin name.
	 *
	 * @var     string  $plugin_name
	 * @access  private
	 */
	private $plugin_name;

	/**
	 * The encryptor.
	 *
	 * @var     Dotdigital_WooCommerce_Encryptor
	 * @access  private
	 */
	private $encryptor;

	/**
	 * The account info.
	 *
	 * @var     Dotdigital_WooCommerce_Account_Info
	 * @access  private
	 */
	private $account_info;

	/**
	 * If the credentials have been sanitized.
	 *
	 * @var     bool
	 * @access  private
	 */
	private $has_sanitized;

	/**
	 * Dotdigital_WooCommerce_Admin_Settings_Handler constructor.
	 *
	 * @param   string $plugin_name The plugin name.
	 */
	public function __construct( string $plugin_name ) {
		$this->plugin_name = $plugin_name;
		$this->encryptor = new Dotdigital_WooCommerce_Encryptor();
		$this->account_info = new Dotdigital_WooCommerce_Account_Info( $this->plugin_name );
	}

	/**
	 * Sanitizes the API credentials.
	 *
	 * @param array $credentials The API credentials.
	 * @return  array|false|mixed|null
	 */
	public function sanitize_api_credentials( array $credentials ) {

		if ( $this->has_sanitized ) {
			return $credentials;
		}

		$old_credentials = get_option( Dotdigital_WooCommerce_Config::API_CREDENTIALS_PATH );
		$username_diff = $this->diff_option( $old_credentials, $credentials, 'username' );
		$password_diff = $this->diff_option( $old_credentials, $credentials, 'password' );

		if ( ! $username_diff && ! $password_diff ) {
			return $old_credentials;
		}

		$encrypted_credentials = array(
			'username' => trim( $credentials['username'] ),
			'password' => ( ! $password_diff ) ? $credentials['password'] : $this->encryptor->encrypt( $credentials['password'] ),
		);

		try {
			$this->account_info->validate_credentials( $encrypted_credentials );
		} catch ( ResponseValidationException $e ) {
			$this->account_info->display_notice( $e->getMessage(), 'error' );
			$encrypted_credentials = array();
		} catch ( Dotdigital_WooCommerce_Password_Validation_Exception $e ) {
			$this->account_info->display_notice( $e->getMessage(), 'error' );
			$encrypted_credentials['password'] = '';
		} catch ( Dotdigital_WooCommerce_Username_Validation_Exception $e ) {
			$this->account_info->display_notice( $e->getMessage(), 'error' );
			$encrypted_credentials['username'] = '';
		} catch ( Dotdigital_WooCommerce_Validation_Exception $e ) {
			$encrypted_credentials = array();
		} finally {
			$this->has_sanitized = true;
		}
		return $encrypted_credentials;
	}

	/**
	 * Checks if credentials are different.
	 *
	 * @param   array|bool $old_value The old value.
	 * @param   array|bool $value    The new value.
	 * @param   string     $key     The key.
	 * @return  bool
	 */
	private function diff_option( $old_value, $value, string $key ):bool {

		if ( ! is_array( $old_value ) || ! is_array( $value ) ) {
			return true;
		}

		if ( ! isset( $old_value[ $key ] ) ) {
			return true;
		}

		if ( ! isset( $value[ $key ] ) ) {
			return false;
		}

		if ( $old_value[ $key ] !== $value[ $key ] ) {
			return true;
		}

		return false;
	}

}
