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
 * Plugin Name:          Dotdigital for WooCommerce
 * Description:          Connect your WooCommerce store to dotdigital and put customer, subscriber, product and order data at your fingertips.
 * Version:              2.2.0
 * Requires PHP:         7.4
 * Requires at least:    5.7
 * Author:               dotdigital
 * Author URI:           https://www.dotdigital.com/
 * License:              MIT
 * License URI:          https://opensource.org/licenses/MIT
 * Text Domain:          dotdigital-for-woocommerce
 * Domain Path:          /languages
 * WC requires at least: 6.0.0
 * WC tested up to:      10.0.0
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';

use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Deactivator;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Activator;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;

define( 'DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_NAME', 'dotdigital-for-woocommerce' );
define( 'DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_VERSION', '2.2.0' );
define( 'DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_DIR_PATH', __DIR__ );
define( 'DOTDIGITAL_FOR_WOOCOMMERCE_PUBLIC_MEDIA_PATH', plugin_dir_url( __FILE__ ) . 'public/image/' );
define( 'DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_ICON', 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyBpZD0iQ2FwYV8xIiBkYXRhLW5hbWU9IkNhcGEgMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNjgyIDY4MiI+CiAgPGRlZnM+CiAgICA8c3R5bGU+CiAgICAgIC5jbHMtMSB7CiAgICAgICAgZmlsbDogI2ZmZjsKICAgICAgfQogICAgPC9zdHlsZT4KICA8L2RlZnM+CiAgPHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMjAwLjk0LDI2NS43OWMzNS44MiwwLDY0Ljg2LTI5LjA0LDY0Ljg2LTY0Ljg2cy0yOS4wNC02NC44Ni02NC44Ni02NC44NiwyOS4wNC02NC44Niw2NC44NiwyOS4wNCw2NC44Niw2NC44Niw2NC44NloiLz4KICA8cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik01MzUuOTIsMjY2LjE5Yy0xMC44NywwLTI3Ljc4LDE2Ljg4LTQzLjI2LDQxLjQ0LTEuMTcsMS44NS0zLjIyLDMuMDMtNS40MywzLjAzLTQuMzksMC03LjQ4LTQuMzYtNi4wMi04LjUyLDIxLjkyLTYxLjk3LDIzLjI5LTEwNy41LjgxLTEwNy41LTE4LjgsMC00OS4zOCwzMS45NC03OC4zMyw3OC43NS0xLjMsMi4xMS0zLjYxLDMuNDItNi4wOCwzLjQyLTQuODUsMC04LjI5LTQuNzUtNi44LTkuMzQsMjQuODItNzYuODMsMjUuNDctMTMxLjM4LTIuMjQtMTMxLjM4LTM1Ljk0LDAtMTA1LjkxLDkxLjc2LTE1Ni4zLDIwNC45Mi01MC4zOSwxMTMuMTYtNjIuMTMsMjA0LjkzLTI2LjE4LDIwNC45MywyOS40LDAsODEuNTgtNjEuNDQsMTI3LjE4LTE0NS43OSwxLjA3LTEuOTgsMy4xMi0zLjIyLDUuNC0zLjIyLDQuMDcsMCw2Ljk5LDMuOSw1Ljg5LDcuODEtMTMuODksNDguODktMTIuMiw4Mi42NSw3LjEyLDgyLjY1LDIyLjAyLDAsNjAuMTEtNDMuNzgsOTIuOTYtMTAzLjg5LDEuNDMtMi42Myw0LjIzLTQuMzMsNy4yMi00LjMzLDUuMDQsMCw4Ljg1LDQuNDksOC4xMyw5LjQ2LTIuMzEsMTYuNTkuNjUsMjcuMTksOS4yNywyNy4xOSwxNS4yOSwwLDQyLjYxLTMzLjUsNjEuMDItNzQuODEsMTguNDEtNDEuMzEsMjAuOTEtNzQuODEsNS41OS03NC44MWguMDNaIi8+Cjwvc3ZnPgo=' );

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

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	public static $plugin_name = DOTDIGITAL_FOR_WOOCOMMERCE_PLUGIN_NAME;

	/**
	 * Account login URL.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $webapp_url    dotdigital URL.
	 * @deprecated Use the constant WEBAPP_URL instead.
	 * @see Dotdigital_WooCommerce_Config::WEBAPP_URL
	 */
	public static $webapp_url = 'https://login.dotdigital.com';

	/**
	 * Tracking URL.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $tracking_url    dotdigital tracking URL.
	 * @deprecated Use the constant TRACKING_URL instead.
	 * @see Dotdigital_WooCommerce_Config::TRACKING_URL
	 */
	public static $tracking_url = 'https://t.trackedlink.net';

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-dotdigital-woocommerce-activator.php
	 */
	public static function activate_dotdigital_woocommerce() {
		( new Dotdigital_WooCommerce_Activator( self::$plugin_name, self::get_tracking_url(), self::get_version() ) )->activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-dotdigital-woocommerce-deactivator.php
	 */
	public static function deactivate_dotdigital_woocommerce() {
		( new Dotdigital_WooCommerce_Deactivator( self::$plugin_name, self::get_tracking_url() ) )->deactivate();
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
		( new Dotdigital_WooCommerce( self::$plugin_name, plugin_basename( __FILE__ ), self::get_webapp_url(), self::get_version(), self::get_tracking_url() ) )->run();
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

	/**
	 * Get the webapp URL.
	 *
	 * Webapp URL can be overridden by setting DOTDIGITAL_WEBAPP_URL in wp-config.php.
	 *
	 * @return string
	 */
	private static function get_webapp_url() {
		if ( defined( 'DOTDIGITAL_WEBAPP_URL' ) ) {
			return constant( 'DOTDIGITAL_WEBAPP_URL' );
		}
		return Dotdigital_WooCommerce_Config::WEBAPP_URL;
	}

	/**
	 * Get the tracking URL.
	 *
	 * Tracking URL can be overridden by setting DOTDIGITAL_TRACKING_URL in wp-config.php.
	 *
	 * @return string
	 */
	private static function get_tracking_url() {
		if ( defined( 'DOTDIGITAL_TRACKING_URL' ) ) {
			return constant( 'DOTDIGITAL_TRACKING_URL' );
		}
		return Dotdigital_WooCommerce_Config::TRACKING_URL;
	}
}

register_activation_hook( __FILE__, array( 'Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper', 'activate_dotdigital_woocommerce' ) );
register_deactivation_hook( __FILE__, array( 'Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper', 'deactivate_dotdigital_woocommerce' ) );

Dotdigital_WooCommerce_Bootstrapper::run_dotdigital_woocommerce();
