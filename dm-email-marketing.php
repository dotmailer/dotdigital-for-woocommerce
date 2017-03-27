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
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dm-email-marketing
 * Domain Path:       /languages
 */

$plugin_name = 'dm-email-marketing';

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

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
 */
function run_dm_email_marketing( $plugin_name ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin = new Dm_Email_Marketing( $plugin_name );
	$plugin->run();

	if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
		validate_dm_email_marketing( $plugin_name );
	}
}

/**
 * Validates if one of the supported ecommerce platform plugins are active.
 *
 * @since    1.0.0
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
			global $plugin_name;
			remove_menu_page( $plugin_name );
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
				<p><?php esc_html_e( 'dotmailer has been deactivated as no supported ecommerce platform has been found.' ); ?></p>
			</div>
		<?php
		}
	}
}

run_dm_email_marketing( $plugin_name );
