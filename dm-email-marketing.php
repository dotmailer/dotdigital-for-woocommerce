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
 * @package           Dm_Email_Marketing
 *
 * @wordpress-plugin
 * Plugin Name:       dotmailer Email Marketing
 * Description:       dotmailer Integration for WordPress ecommerce platforms
 * Version:           1.0.0
 * Author:            dotmailer
 * Author URI:        https://www.dotmailer.com/
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       dm-email-marketing
 * Domain Path:       /languages
 *
 * dotmailer Email Marketing for WordPress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * dotmailer Email Marketing for WordPress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with dotmailer Email Marketing for WordPress. If not, see https://www.gnu.org/licenses/gpl-3.0.txt.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$dotmailer_plugin_name = 'dm-email-marketing';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dm-email-marketing-activator.php
 */
function activate_dm_email_marketing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dm-email-marketing-activator.php';
	Dm_Email_Marketing_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dm-email-marketing-deactivator.php
 */
function deactivate_dm_email_marketing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dm-email-marketing-deactivator.php';
	Dm_Email_Marketing_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dm_email_marketing' );
register_deactivation_hook( __FILE__, 'deactivate_dm_email_marketing' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dm-email-marketing.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 *
 * @param string $plugin_name The name of the plugin.
 */
function run_dm_email_marketing( $plugin_name ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin = new Dm_Email_Marketing( $plugin_name );
	$plugin->run();

	if ( is_admin() && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
		validate_dm_email_marketing( $plugin_name );
	}
}

/**
 * Validates if one of the supported ecommerce platform plugins are active.
 *
 * @since   1.0.0
 *
 * @param string $plugin_name The name of the plugin.
 */
function validate_dm_email_marketing( $plugin_name ) {
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		add_action( 'admin_init', 'self_deactivate' );
		add_action( 'admin_menu', 'remove_admin_menu_page' );
		add_action( 'admin_notices', 'plugin_activation_failure_message' );

		/**
	 	 * Short Description. (use period)
	 	 *
	 	 * Long Description.
	 	 *
	 	 * @since    1.0.0
	 	 */
		function self_deactivate() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
		/**
	 	 * Short Description. (use period)
	 	 *
	 	 * Long Description.
	 	 *
	 	 * @since    1.0.0
	 	 */
		function remove_admin_menu_page() {
			global $dotmailer_plugin_name;
			remove_menu_page( $dotmailer_plugin_name );
		}
		/**
	 	 * Short Description. (use period)
	 	 *
		 * Long Description.
	 	 *
	 	 * @since    1.0.0
	 	 */
		function plugin_activation_failure_message() {
		?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'dotmailer plugin will remain deactivated until an ecommerce plugin is installed and activated.', 'dm-email-marketing' ); ?></p>
			</div>
		<?php
		}
	}
}

run_dm_email_marketing( $dotmailer_plugin_name );
