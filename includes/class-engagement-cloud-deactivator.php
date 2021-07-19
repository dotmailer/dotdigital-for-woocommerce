<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/includes
 */

namespace Engagement_Cloud\Includes;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes/includes
 * @author     dotdigital <integrations@dotdigital.com>
 */
class Engagement_Cloud_Deactivator {
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	private $plugin_name;

	/**
	 * Engagement Cloud tracking URL.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tracking_url    Engagement Cloud tracking URL.
	 */
	private $tracking_url;

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
	 * @param string $tracking_url Engagement Cloud tracking URL.
	 */
	public function __construct( $plugin_name, $tracking_url ) {

		$this->plugin_name  = $plugin_name;
		$this->tracking_url = $tracking_url;

	}

	/**
	 * Executed upon plugin deactivation.
	 *
	 * Executed upon plugin deactivation and posts to Engagament Cloud
	 * tracking site to notify that the plugin has been deactivated.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
		global $wpdb;
		$engagement_cloud_table_name = $wpdb->prefix . 'dotmailer_email_marketing';

		// @codingStandardsIgnoreStart
		$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $engagement_cloud_table_name" );
		// @codingStandardsIgnoreEnd
		wp_remote_post( "$this->tracking_url/e/woocommerce/disable?pluginid=$plugin_id" );
	}
}
