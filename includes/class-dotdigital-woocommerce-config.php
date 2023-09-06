<?php
/**
 * The config provider class.
 *
 * This class provides the default config values and paths.
 *
 * @since      1.2.0
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes
 * @author     dotdigital <integrations@dotdigital.com>
 */

namespace Dotdigital_WooCommerce\Includes;

/**
 * Class Dotdigital_WooCommerce_Config
 */
class Dotdigital_WooCommerce_Config {

	const TRACKING_URL = 'https://t.trackedlink.net';

	const API_ENDPOINT = 'https://r1-api.dotdigital.com';

	/**
	 * Table names
	 */
	const EMAIL_MARKETING_TABLE_NAME = 'dotmailer_email_marketing';
	const SUBSCRIBERS_TABLE_NAME     = 'dd_subscribers';

	/**
	 * Admin settings - default values
	 */
	const DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT = 1;
	const DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_REGISTER = 1;
	const DEFAULT_SITE_AND_ROI_TRACKING_ENABLED = 0;
	const DEFAULT_MARKETING_CHECKBOX_TEXT = 'Subscribe to our newsletter';
	const DEFAULT_REGION = 1;
	const DEFAULT_ABANDONED_CART_ALLOW_NON_SUBSCRIBERS = 1;
	const DEFAULT_ABANDONED_CART_STATUS = 0;

	/**
	 * Admin settings - paths
	 */
	const PLUGIN_VERSION = 'dotdigital_for_woocommerce_version';
	const MARKETING_CHECKBOX_TEXT = 'dotdigital_for_woocommerce_settings_marketing_checkbox_text';
	const SHOW_MARKETING_CHECKBOX_CHECKOUT = 'dotdigital_for_woocommerce_settings_show_marketing_checkbox_at_checkout';
	const SHOW_MARKETING_CHECKBOX_REGISTER = 'dotdigital_for_woocommerce_settings_show_marketing_checkbox_at_register';
	const MARKETING_EMAIL_LISTS = 'dotdigital_for_woocommerce_settings_marketing_email_lists';
	const SITE_AND_ROI_TRACKING = 'dotdigital_for_woocommerce_settings_enable_site_and_roi_tracking';
	const REGION = 'dotdigital_for_woocommerce_settings_region';
	const WBT_PROFILE_ID_PATH = 'dotdigital_for_woocommerce_settings_web_behaviour_tracking_profile_id';
	const AC_STATUS_PATH = 'dotdigital_for_woocommerce_settings_abandoned_cart_enabled';
	const PROGRAM_ID_PATH = 'dotdigital_for_woocommerce_cart_insight_program_id';
	const CART_DELAY_PATH = 'dotdigital_for_woocommerce_cart_insight_cart_delay';
	const ALLOW_NON_SUBSCRIBERS_PATH = 'dotdigital_for_woocommerce_abandoned_cart_allow_non_subscribers';
	const API_CREDENTIALS_PATH = 'dotdigital_for_woocommerce_settings_api_credentials';

	/**
	 * Form Field constants
	 */
	const FORM_FIELD_MARKETING_CHECKBOX_NAME = 'dotdigital_woocommerce_marketing_checkbox';
	const FORM_FIELD_MARKETING_CHECKBOX_SMS_NAME = 'dotdigital_woocommerce_marketing_checkbox_sms';
	const FORM_FIELD_MARKETING_INPUT_PHONE_NAME = 'dotdigital_woocommerce_marketing_phone_sms';

	/**
	 * Sms option marketing constants
	 */
	const SHOW_SMS_MARKETING_CHECKBOX_CHECKOUT = 'dotdigital_for_woocommerce_settings_show_sms_marketing_checkbox_at_checkout';
	const SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION = 'dotdigital_for_woocommerce_settings_show_sms_marketing_checkbox_at_user_registration';
	const MARKETING_CHECKBOX_SMS_TEXT = 'dotdigital_for_woocommerce_settings_marketing_sms_checkbox_text';
	const MARKETING_CONSENT_SMS_TEXT = 'dotdigital_for_woocommerce_settings_marketing_sms_consent_text';
	const MARKETING_SMS_LISTS = 'dotdigital_for_woocommerce_settings_marketing_sms_lists';
}
