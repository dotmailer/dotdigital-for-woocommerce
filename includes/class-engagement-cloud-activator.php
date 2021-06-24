<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @subpackage EngagementCloud/includes
 * @author     dotdigital <integrations@dotdigital.com>
 */
class Engagement_Cloud_Activator {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	private $plugin_name;

	/**
	 * Engagement Cloud callback URL.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $callback_url    Engagement Cloud callback URL.
	 */
	private $callback_url;

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
	 * @param string $callback_url The URL of the Engagement Cloud tracking site.
	 */
	public function __construct( $plugin_name, $callback_url ) {

		$this->plugin_name  = $plugin_name;
		$this->callback_url = $callback_url;

	}

	/**
	 * Executed upon plugin activation.
	 *
	 * Executed upon plugin activation and posts to Engagement Cloud
	 * tracking site to notify that the plugin has been activated.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		global $wpdb;
		$engagement_cloud_table_name = $wpdb->prefix . 'dotmailer_email_marketing';

		// @codingStandardsIgnoreStart
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $engagement_cloud_table_name ) ) !== $engagement_cloud_table_name ) {
			// @codingStandardsIgnoreEnd
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $engagement_cloud_table_name (
          		PluginID VARCHAR(256) NOT NULL
     		) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}

		// @codingStandardsIgnoreStart
		$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $engagement_cloud_table_name" );

		if ( null === $plugin_id ) {
			$length = 128;
			$crypto_strong = true;

			$wpdb->insert( $engagement_cloud_table_name, array(
				'PluginID' => bin2hex( openssl_random_pseudo_bytes( $length, $crypto_strong ) ),
			));

			$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $engagement_cloud_table_name" );
			// @codingStandardsIgnoreEnd
		}

		wp_remote_post( "$this->callback_url/e/woocommerce/enable?pluginid=$plugin_id" );
	}
}
