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

	/**
	 * Dotdigital table names
	 */
	const EMAIL_MARKETING_TABLE_NAME = 'dotmailer_email_marketing';
	const SUBSCRIBERS_TABLE_NAME     = 'dd_subscribers';


	const TRACKING_URL = 'https://t.trackedlink.net';

	/**
	 * Dotdigital settings default values
	 */
	const DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT = 1;
	const DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_REGISTER = 1;
	const DEFAULT_SITE_AND_ROI_TRACKING_ENABLED = 0;
	const DEFAULT_MARKETING_CHECKBOX_TEXT = 'Subscribe to our newsletter';
	const DEFAULT_REGION = 1;
	const DEFAULT_ABANDONED_CART_ALLOW_NON_SUBSCRIBERS = 1;

	/**
	 * Dotdigital API settings
	 */
	const WBT_PROFILE_ID_PATH = 'dotdigital_for_woocommerce_settings_web_behaviour_tracking_profile_id';
	const WBT_STATUS_PATH = 'dotdigital_for_woocommerce_settings_web_behaviour_tracking_enabled';
	const PROGRAM_ID_PATH = 'dotdigital_for_woocommerce_cart_insight_program_id';
	const CART_DELAY_PATH = 'dotdigital_for_woocommerce_cart_insight_cart_delay';
	const ALLOW_NON_SUBSCRIBERS_PATH = 'dotdigital_for_woocommerce_abandoned_cart_allow_non_subscribers';

	/**
	 * Dotdigital Settings
	 */
	const SHOW_MARKETING_CHECKBOX_CHECKOUT = 'dotdigital_for_woocommerce_settings_show_marketing_checkbox_at_checkout';
	const SHOW_MARKETING_CHECKBOX_REGISTER = 'dotdigital_for_woocommerce_settings_show_marketing_checkbox_at_register';
	const MARKETING_CHECKBOX_TEXT = 'dotdigital_for_woocommerce_settings_marketing_checkbox_text';
	const SITE_AND_ROI_TRACKING = 'dotdigital_for_woocommerce_settings_enable_site_and_roi_tracking';
	const REGION = 'dotdigital_for_woocommerce_settings_region';
	const PLUGIN_VERSION = 'dotdigital_for_woocommerce_version';
}
