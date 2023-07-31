<?php
/**
 * Used for marketing consent on checkout.
 *
 * @since      1.4.0
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/forms/checkout
 * @author     dotdigital <integrations@dotdigital.com>
 */

namespace Dotdigital_WooCommerce\Includes\Forms\Checkout;

use Dotdigital\V3\Models\Contact;
use DOMDocument;
use Dotdigital_WooCommerce\Includes\Client\Dotdigital_WooCommerce_Contact;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Http\Client\Exception;

/**
 * Class Dotdigital_WooCommerce_Checkout_Sms_Marketing_Phone
 */
class Dotdigital_WooCommerce_Checkout_Sms_Marketing_Phone {

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
	 * Renders the  collapsable input for the sms consent capture
	 */
	public function render() {
		$show = get_option(
			Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_CHECKOUT,
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
	 * Modifies the input content by removing the description class.
	 *
	 * @param string $field HTML Woocommerce field.
	 * @param string $key The key of the field.
	 * @param array  $args The arguments of the field.
	 * @param mixed  $value The value of the field.
	 * @return false|mixed|string
	 */
	public function modify_input_content( $field, $key, $args, $value ) {
		if ( Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME === $key ) {
			$document = new DOMDocument();
			$document->loadHTML( $field );
			if ( ! empty( $document->getElementById( Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '-description' ) ) ) {
				$document
					->getElementById( Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '-description' )
					->setAttribute( 'class', 'dd-phone-input-description' );
			}
			$field = $document->saveHTML();
		}
		return $field;
	}

	/**
	 * Validates the marketing consent checkbox.
	 *
	 * @param array $fields The fields to validate.
	 * @param array $errors The errors to add to.
	 * @return void
	 */
	public function validate( $fields, $errors ) {
		if ( ! empty( wp_strip_all_tags( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME ] ) ) // phpcs:ignore WordPress.Security
			&& ! empty( wp_strip_all_tags( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ] ) ) ) {  // phpcs:ignore WordPress.Security
			$is_valid = filter_var(
				wp_strip_all_tags( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ] ), // phpcs:ignore WordPress.Security
				FILTER_VALIDATE_BOOLEAN
			);

			if ( ! $is_valid ) {
				$validation_message = sprintf(
					'<strong>Marketing Consent</strong> %s',
					wp_strip_all_tags( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME . '_hidden' ] )// phpcs:ignore WordPress.Security
				);
				$errors->add( 'validation', $validation_message );
			}
		}
	}

	/**
	 * Handles contact creation via the WooCommerce checkout.
	 *
	 * @param int       $order_id The processed order ID.
	 * @param array     $posted_data The posted data.
	 * @param \WC_Order $order The order object.
	 *
	 * @return void
	 */
	public function handle_submit( int $order_id, array $posted_data, \WC_Order $order ) {
		if ( empty( $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME ] ) ) { // phpcs:ignore WordPress.Security
			return;
		}

		$phone = $_POST[ Dotdigital_WooCommerce_Config::FORM_FIELD_MARKETING_INPUT_PHONE_NAME ] ?? ''; // phpcs:ignore WordPress.Security

		$contact = new Contact(
			array(
				'matchIdentifier' => 'email',
				'identifiers' => array(
					'email' => $order->get_billing_email(),
					'mobileNumber' => $phone,
				),
				'channelProperties' => array(
					'email' => array(
						'emailType' => 'Html',
					),
				),
				'dataFields' => array(
					'firstName' => $order->get_billing_first_name(),
					'lastName' => $order->get_billing_last_name(),
				),
			)
		);

		$consent_text = get_option( Dotdigital_WooCommerce_Config::MARKETING_CONSENT_SMS_TEXT, '' );
		if ( ! empty( $consent_text ) ) {
			$contact->setConsentRecords(
				array(
					'text' => $consent_text,
					'dateTimeConsented' => gmdate( 'Y-m-d\TH:i:s\Z', time() ),
					'url' => $_SERVER['HTTP_REFERER'] ?? '', // phpcs:ignore WordPress.Security
				'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? '', // phpcs:ignore WordPress.Security
				'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? '', // phpcs:ignore WordPress.Security
				)
			);
		}

		$list = get_option( Dotdigital_WooCommerce_Config::MARKETING_SMS_LISTS, '' );
		if ( ! empty( $list ) ) {
			$contact->setLists( array( $list ) );
		}

		$this->contact_client->create_or_update( $contact );
	}
}
