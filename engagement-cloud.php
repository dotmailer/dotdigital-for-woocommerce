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
 * @package    EngagementCloud
 *
 * @wordpress-plugin
 * Plugin Name:       dotdigital Engagement Cloud for WooCommerce
 * Description:       Engagement Cloud Integration for WooCommerce ecommerce platform.
 * Version:           1.2.0
 * Author:            dotdigital
 * Author URI:        https://www.dotdigital.com/
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       dotdigital_engagement_cloud
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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'EC_FOR_WOOCOMMERCE_PLUGIN_VERSION', '1.2.0' );

/**
 * Used to bootstrap the Engagement Cloud plugin.
 *
 * This class defines all code necessary to register and run the plugin.
 *
 * @since      1.0.0
 * @package    EngagementCloud
 * @author     dotdigital <integrations@dotdigital.com>
 */
class Engagement_Cloud_Bootstrapper {

	const EMAIL_MARKETING_TABLE_NAME = 'dotmailer_email_marketing';
	const SUBSCRIBERS_TABLE_NAME     = 'ec_subscribers';

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	public static $plugin_name = 'dotdigital_engagement_cloud';

	/**
	 * Engagement Cloud URL.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $webapp_url    Engagement Cloud URL.
	 */
	public static $webapp_url = 'https://login.dotdigital.com';

	/**
	 * Engagement Cloud callback URL.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $callback_url    Engagement Cloud callback URL.
	 */
	public static $callback_url = 'https://t.trackedlink.net';

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-engagement-cloud-activator.php
	 */
	public static function activate_engagement_cloud() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-engagement-cloud-activator.php';
		( new Engagement_Cloud_Activator( self::$plugin_name, self::$callback_url, self::get_version() ) )->activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-engagement-cloud-deactivator.php
	 */
	public static function deactivate_engagement_cloud() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-engagement-cloud-deactivator.php';
		( new Engagement_Cloud_Deactivator( self::$plugin_name, self::$callback_url ) )->deactivate();
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
	public static function run_engagement_cloud() {
		/**
		 * The core plugin class that is used to define internationalization,
		 * admin-specific hooks, and public-facing site hooks.
		 */
		require plugin_dir_path( __FILE__ ) . 'includes/class-engagement-cloud.php';

		( new Engagement_Cloud( self::$plugin_name, plugin_basename( __FILE__ ), self::$webapp_url, self::get_version() ) )->run();
	}

	/**
	 * Fetch the plugin version.
	 *
	 * @return string
	 */
	private static function get_version() {
		if ( defined( 'EC_FOR_WOOCOMMERCE_PLUGIN_VERSION' ) ) {
			return EC_FOR_WOOCOMMERCE_PLUGIN_VERSION;
		} else {
			return '1.0.0';
		}
	}
}

register_activation_hook( __FILE__, array( 'Engagement_Cloud_Bootstrapper', 'activate_engagement_cloud' ) );
register_deactivation_hook( __FILE__, array( 'Engagement_Cloud_Bootstrapper', 'deactivate_engagement_cloud' ) );

Engagement_Cloud_Bootstrapper::run_engagement_cloud();
