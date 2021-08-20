<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 * @package    Dotdigital_WooCommerce
 *
 * @wordpress-plugin
 * Plugin Name:       dotdigital for WooCommerce
 * Description:       dotdigital Integration for WooCommerce ecommerce platform.
 * Version:           1.2.0
 * Author:            dotdigital
 * Author URI:        https://www.dotdigital.com/
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       dotdigital-woocommerce
 * Domain Path:       /languages
 *
 * MIT License
 *
 * Copyright (c) 2017 dotDigital Group PLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Dotdigital_WooCommerce;

use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Deactivator;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Activator;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_VERSION', '1.2.0' );
define( 'PLUGIN_DIR_PATH', __DIR__ );

require_once 'inc/autoloader.php';

/**
 * Used to bootstrap the dotdigital plugin.
 *
 * This class defines all code necessary to register and run the plugin.
 *
 * @since      1.0.0
 * @package    Dotdigital_WooCommerce
 * @author     dotdigital <integrations@dotdigital.com>
 */
class Dotdigital_WooCommerce_Bootstrapper {

	const EMAIL_MARKETING_TABLE_NAME = 'dotmailer_email_marketing';
	const SUBSCRIBERS_TABLE_NAME     = 'dd_subscribers';

	const DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_CHECKOUT = 1;
	const DEFAULT_MARKETING_CHECKBOX_DISPLAY_AT_REGISTER = 1;
	const DEFAULT_SITE_AND_ROI_TRACKING_ENABLED = 0;
	const DEFAULT_MARKETING_CHECKBOX_TEXT = 'Subscribe to our newsletter';
	const DEFAULT_REGION = 1;

	const DEFAULT_ABANDONED_CART_ALLOW_NON_SUBSCRIBERS = 1;

	const WBT_PROFILE_ID_PATH = 'dotdigital_for_woocommerce_settings_web_behaviour_tracking_profile_id';
	const WBT_STATUS_PATH = 'dotdigital_for_woocommerce_settings_web_behaviour_tracking_enabled';
	const PROGRAM_ID_PATH = 'dotdigital_for_woocommerce_cart_insight_program_id';
	const CART_DELAY_PATH = 'dotdigital_for_woocommerce_cart_insight_cart_delay';
	const ALLOW_NON_SUBSCRIBERS_PATH = 'dotdigital_for_woocommerce_abandoned_cart_allow_non_subscribers';

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	public static $plugin_name = 'dotdigital-for-woocommerce';

	/**
	 * Account login URL.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $webapp_url    dotdigital URL.
	 */
	public static $webapp_url = 'https://login.dotdigital.com';

	/**
	 * Tracking URL.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $tracking_url    dotdigital tracking URL.
	 */
	public static $tracking_url = 'https://t.trackedlink.net';

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-dotdigital-woocommerce-activator.php
	 */
	public static function activate_dotdigital_woocommerce() {
		( new Dotdigital_WooCommerce_Activator( self::$plugin_name, self::$tracking_url, self::get_version() ) )->activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-dotdigital-woocommerce-deactivator.php
	 */
	public static function deactivate_dotdigital_woocommerce() {
		( new Dotdigital_WooCommerce_Deactivator( self::$plugin_name, self::$tracking_url ) )->deactivate();
	}

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	public static function run_dotdigital_woocommerce() {
		/**
		 * The core plugin class that is used to define internationalization,
		 * admin-specific hooks, and public-facing site hooks.
		 */
		( new Dotdigital_WooCommerce( self::$plugin_name, plugin_basename( __FILE__ ), self::$webapp_url, self::get_version(), self::$tracking_url ) )->run();
	}

	/**
	 * Fetch the plugin version.
	 *
	 * @return string
	 */
	public static function get_version() {
		if ( defined( 'DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_VERSION' ) ) {
			return DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_VERSION;
		} else {
			return '1.0.0';
		}
	}
}

register_activation_hook( __FILE__, array( 'Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper', 'activate_dotdigital_woocommerce' ) );
register_deactivation_hook( __FILE__, array( 'Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper', 'deactivate_dotdigital_woocommerce' ) );

Dotdigital_WooCommerce_Bootstrapper::run_dotdigital_woocommerce();
