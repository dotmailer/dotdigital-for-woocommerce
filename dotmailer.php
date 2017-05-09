<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.dotmailer.com/
 * @since             1.0.0
 * @package           Dotmailer
 *
 * @wordpress-plugin
 * Plugin Name:       dotmailer Email Marketing
 * Description:       dotmailer Integration for WordPress ecommerce platforms.
 * Version:           1.0.0
 * Author:            dotmailer
 * Author URI:        https://www.dotmailer.com/
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       dotmailer_email_marketing
 * Domain Path:       /languages
 *
 * Copyright 2017 dotDigital Group PLC
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

/**
 * Used to bootstrap the dotmailer plugin.
 *
 * This class defines all code necessary to register and run the plugin.
 *
 * @since      1.0.0
 * @package    Dotmailer
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dotmailer_Bootstrapper {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	public static $plugin_name = 'dotmailer_email_marketing';

	/**
	 * The URL of dotmailer's web app.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $webapp_url    The URL of dotmailer's web app.
	 */
	public static $webapp_url = 'https://debug-webapp.dotmailer.internal';

	/**
	 * The URL of dotmailer tracking site.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $tracking_site_url    The URL of dotmailer's tracking site.
	 */
	public static $tracking_site_url = 'http://debug-tracking.dotmailer.internal';

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-dotmailer-activator.php
	 */
	public static function activate_dotmailer() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-dotmailer-activator.php';
		( new Dotmailer_Activator( self::$plugin_name, self::$tracking_site_url ) )->activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-dotmailer-deactivator.php
	 */
	public static function deactivate_dotmailer() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-dotmailer-deactivator.php';
		( new Dotmailer_Deactivator( self::$plugin_name, self::$tracking_site_url ) )->deactivate();
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
	public static function run_dotmailer() {
		/**
		 * The core plugin class that is used to define internationalization,
		 * admin-specific hooks, and public-facing site hooks.
		 */
		require plugin_dir_path( __FILE__ ) . 'includes/class-dotmailer.php';

		( new Dotmailer( self::$plugin_name, plugin_basename( __FILE__ ), self::$webapp_url ) )->run();
	}
}

register_activation_hook( __FILE__, array( 'Dotmailer_Bootstrapper', 'activate_dotmailer' ) );
register_deactivation_hook( __FILE__, array( 'Dotmailer_Bootstrapper', 'deactivate_dotmailer' ) );

Dotmailer_Bootstrapper::run_dotmailer();
