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
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$this->store_plugin_id_and_notify();
		$this->create_subscriber_table();
	}

	/**
	 *
	 */
	private function store_plugin_id_and_notify() {
		global $wpdb;
		$engagement_cloud_table_name = $wpdb->prefix . 'dotmailer_email_marketing';

		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $engagement_cloud_table_name ) ) !== $engagement_cloud_table_name ) {
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $engagement_cloud_table_name (
          		PluginID VARCHAR(256) NOT NULL
     		) $charset_collate;";

			dbDelta( $sql );
		}

		$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $engagement_cloud_table_name" );

		if ( null === $plugin_id ) {
			$length        = 128;
			$crypto_strong = true;

			$wpdb->insert(
				$engagement_cloud_table_name,
				array(
					'PluginID' => bin2hex( openssl_random_pseudo_bytes( $length, $crypto_strong ) ),
				)
			);

			$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $engagement_cloud_table_name" );
		}

		wp_remote_post( "$this->callback_url/e/woocommerce/enable?pluginid=$plugin_id" );
	}

	/**
	 *
	 */
	private function create_subscriber_table() {
		global $wpdb;
		$engagement_cloud_table_name = $wpdb->prefix . 'ec_subscribers';

		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $engagement_cloud_table_name ) ) !== $engagement_cloud_table_name ) {
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE {$engagement_cloud_table_name} (
	            `id` int(10) NOT NULL AUTO_INCREMENT,
	            `user_id` int(10),
	            `email` varchar(255) NOT NULL default '',
	            `status` smallint(5),
	            `first_name` varchar(255),
	            `last_name` varchar(255),
	            `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	            PRIMARY KEY (`id`)
	        ) $charset_collate;";

			dbDelta( $sql );
		}
	}
}
