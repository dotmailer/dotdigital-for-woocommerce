<?php
/**
 * Used for marketing consent on register.
 *
 * @since      1.4.0
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/forms/register
 * @author     dotdigital <integrations@dotdigital.com>
 */

namespace Dotdigital_WooCommerce\Includes\Forms\Register;

use Dotdigital\V3\Models\Contact;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Dotdigital_WooCommerce\Includes\Client\Dotdigital_WooCommerce_Contact;

/**
 * Class Dotdigital_WooCommerce_Checkout_Marketing_Checkbox
 */
class Dotdigital_WooCommerce_Register_Sms_Marketing_Phone {

	/**
	 * The contact client.
	 *
	 * @var Dotdigital_WooCommerce_Contact $contact_client
	 * @access private
	 */
	private $contact_client;

	/**
	 * Dotdigital_WooCommerce_Checkout_Sms_Marketing_Phone constructor.
	 */
	public function __construct() {
		$this->contact_client = new Dotdigital_WooCommerce_Contact();
	}

	/**
	 * Renders the  collapsible input for the sms consent capture
	 */
	public function render() {
		$show = get_option(
			Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION,
			Dotdigital_WooCommerce_Config::DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT
		);

		if ( ! $show ) {
			return;
		}

		woocommerce_form_field(
			Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME,
			array(
				'type'  => 'tel',
				'label' => '',
				'class' => array(
					'form-row-wide',
					DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_NAME . '-phone-input',
					'dd-phone-input',
				),
				'description' => get_option( Dotdigital_WooCommerce_Config::MARKETING_CONSENT_SMS_TEXT, '' ),
				'',
			),
			$_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME ] ?? '' // phpcs:ignore WordPress.Security
		);

		woocommerce_form_field(
			Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden',
			array(
				'type'  => 'hidden',
				'label' => '',
				'required' => true,
				'class' => array(
					DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_NAME . '-phone-input-hidden',
				),
				'validate' => array(
					'phone',
				),
			)
		);
	}

	/**
	 * Validates the marketing consent checkbox.
	 *
	 * @param string $username The username.
	 * @param string $email The email.
	 * @param array  $validation_errors The validation errors.
	 * @return void
	 */
	public function validate( $username, $email, $validation_errors ) {

		if ( ! empty( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_CHECKBOX_SMS_NAME ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$is_valid = filter_var(
				$_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ], // phpcs:ignore WordPress.Security
				FILTER_VALIDATE_BOOLEAN
			);

			if ( ! $is_valid ) {
				$validation_message = sprintf(
					'<strong>Marketing Consent</strong> %s',
					$_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ] // phpcs:ignore WordPress.Security
				);
				$validation_errors->add( 'validation', $validation_message );
			}
		}
	}

	/**
	 * Handles a subscription created via the WooCommerce registration.
	 *
	 * @param string $user_id User id.
	 * @return void
	 */
	public function handle_submit( $user_id ) {
		if ( empty( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME ] ) ) { // phpcs:ignore WordPress.Security
			return;
		}

		$phone = $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME ] ?? ''; // phpcs:ignore WordPress.Security
		$email = sanitize_text_field( wp_unslash( $_POST['email'] ?? '' ) ); // phpcs:ignore WordPress.Security

		$contact = new Contact(
			array(
				'matchIdentifier' => 'email',
				'identifiers' => array(
					'email' => $email,
					'mobileNumber' => $phone,
				),
			)
		);

		$consent_text = get_option( Dotdigital_WooCommerce_Config::MARKETING_CONSENT_SMS_TEXT, '' );
		if ( ! empty( $consent_text ) ) {
			$contact->setConsentRecords(
				array(
					array(
						'text' => $consent_text,
						'dateTimeConsented' => gmdate( 'Y-m-d\TH:i:s\Z', time() ),
						'url' => $_SERVER['HTTP_REFERER'] ?? '', // phpcs:ignore WordPress.Security
						'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? '', // phpcs:ignore WordPress.Security
						'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? '', // phpcs:ignore WordPress.Security
					),
				)
			);
		}

		$list = (int) get_option( Dotdigital_WooCommerce_Config::MARKETING_SMS_LISTS, '' );
		if ( ! empty( $list ) ) {
			$contact->setLists( array( $list ) );
		}

		$this->contact_client->create_or_update( $email, $contact );
	}
}
