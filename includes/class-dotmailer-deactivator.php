<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dotmailer
 * @subpackage Dotmailer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Dotmailer
 * @subpackage Dotmailer/includes
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dotmailer_Deactivator {
	/**
	 * The name of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The name of the plugin.
	 */
	private $plugin_name;

	/**
	 * The URL of the dotmailer tracking site.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tracking_site_url    The URL of the dotmailer tracking site.
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
	 * @param string $tracking_site_url The URL of the dotmailer tracking site.
	 */
	public function __construct( $plugin_name, $tracking_site_url ) {

		$this->plugin_name = $plugin_name;
		$this->tracking_site_url = $tracking_site_url;

	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
		global $wpdb;
		$dotmailer_table_name = $wpdb->prefix . $this->plugin_name;

		// @codingStandardsIgnoreStart
		$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $dotmailer_table_name" );
		// @codingStandardsIgnoreEnd
		wp_remote_post( "$this->tracking_site_url/e/woocommerce/disable?pluginid=$plugin_id" );
	}
}
