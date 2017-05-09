<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dotmailer
 * @subpackage Dotmailer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @subpackage Dotmailer/includes
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dotmailer_Activator {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	private $plugin_name;

	/**
	 * The URL of the dotmailer's tracking site.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tracking_site_url    The URL of the dotmailer's tracking site.
	 */
	private $tracking_site_url;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $tracking_site_url The URL of the dotmailer's tracking site.
	 */
	public function __construct( $plugin_name, $tracking_site_url ) {

		$this->plugin_name = $plugin_name;
		$this->tracking_site_url = $tracking_site_url;

	}

	/**
	 * Executed upon plugin activation.
	 *
	 * Executed upon plugin activation and posts to dotmailer's
	 * tracking site to notify that the plugin has been activated.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		global $wpdb;
		$dotmailer_table_name = $wpdb->prefix . $this->plugin_name;

		// @codingStandardsIgnoreStart
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $dotmailer_table_name ) ) !== $dotmailer_table_name ) {
			// @codingStandardsIgnoreEnd
			$charset_collate = $wpdb -> get_charset_collate();

			$sql = "CREATE TABLE $dotmailer_table_name (
          		PluginID VARCHAR(256) NOT NULL
     		) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		// @codingStandardsIgnoreStart
		$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $dotmailer_table_name" );

		if ( null === $plugin_id ) {
			$length = 128;
			$crypto_strong = true;

			$wpdb->insert( $dotmailer_table_name, array(
				'PluginID' => bin2hex( openssl_random_pseudo_bytes( $length, $crypto_strong ) ),
			));

			$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $dotmailer_table_name" );
			// @codingStandardsIgnoreEnd
		}

		wp_remote_post( "$this->tracking_site_url/e/woocommerce/enable?pluginid=$plugin_id" );
	}
}
