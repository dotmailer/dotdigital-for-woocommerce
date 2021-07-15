<?php
/**
 * Handles plugin upgrades.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/admin
 */

/**
 * Class Engagement_Cloud_Upgrader
 */
class Engagement_Cloud_Upgrader {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Engagement Cloud tracking URL.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $tracking_url The URL.
	 */
	private $tracking_url;

	/**
	 * The stored option containing the last set / previous version of this plugin.
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      string    $stored_version    The stored version of this plugin.
	 */
	private $stored_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @param string $tracking_url The tracking URL.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version, $tracking_url ) {

		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->tracking_url = $tracking_url;

	}

	/**
	 * Check if we need to upgrade the database.
	 */
	public function upgrade_check() {
		$this->stored_version = get_option( 'engagement_cloud_for_woocommerce_version' );
		if ( version_compare( $this->stored_version, $this->version, '>=' ) ) {
			return;
		}

		$this->upgrade();
		$this->notify();
		$this->set_plugin_version();
	}

	/**
	 * Run a series of version-specific upgrade scripts.
	 */
	private function upgrade() {
		if ( current_user_can( 'update_plugins' ) ) {
			$this->upgrade_one_zero_zero();
			$this->upgrade_one_two_zero();
		}
	}

	/**
	 * Notify Engagement Cloud of the upgrade.
	 */
	private function notify() {
		$service      = new Engagement_Cloud_Rest_Api( $this->plugin_name );
		$callback_url = $service->get_rest_callback_url();
		$plugin_id    = $this->generate_and_store_plugin_id();

		$this->send_plugin_id_and_callback_url_to_ec( $callback_url, $plugin_id );
	}

	/**
	 * Generates and stores a 128 character plugin_id to serve as an auth token for EC.
	 *
	 * @return string|null
	 */
	private function generate_and_store_plugin_id() {
		global $wpdb;
		$table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::EMAIL_MARKETING_TABLE_NAME;
		$plugin_id  = $wpdb->get_var( "SELECT PluginID FROM $table_name" ); // phpcs:ignore WordPress.DB

		if ( null === $plugin_id ) {
			$length        = 128;
			$crypto_strong = true;

			$wpdb->insert(
				$table_name,
				array(
					'PluginID' => bin2hex( openssl_random_pseudo_bytes( $length, $crypto_strong ) ),
				)
			); // db call ok.

			$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $table_name" ); // phpcs:ignore WordPress.DB
		}

		return $plugin_id;
	}

	/**
	 * Stores the plugin ID in the table and posts to Engagement Cloud
	 * tracking site to notify that the plugin has been activated.
	 *
	 * @param string $callback_url A callback URL for EC requests.
	 * @param string $plugin_id The plugin ID.
	 */
	private function send_plugin_id_and_callback_url_to_ec( $callback_url, $plugin_id ) {
		$data = array(
			'callback_url' => $callback_url,
			'pluginid'     => $plugin_id,
		);
		wp_remote_post( "$this->tracking_url/e/woocommerce/enable?" . http_build_query( $data ) );
	}

	/**
	 * Upgrade 1.0.0.
	 */
	private function upgrade_one_zero_zero() {
		if ( version_compare( $this->stored_version, '1.0.0', '<' ) ) {
			$this->create_email_marketing_table();
		}
	}
	/**
	 * Upgrade 1.2.0.
	 */
	private function upgrade_one_two_zero() {
		if ( version_compare( $this->stored_version, '1.2.0', '<' ) ) {
			$this->create_subscriber_table();

			require_once( 'class-engagement-cloud-migrator.php' );
			$migrator = new Engagement_Cloud_Migrator();
			$migrator->migrate_users_to_subscriber_table();
		}
	}

	/**
	 * Creates the dotmailer_email_marketing table.
	 */
	public function create_email_marketing_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::EMAIL_MARKETING_TABLE_NAME;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            PluginID VARCHAR(256) NOT NULL
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Create the ec_subscribers table.
	 */
	public function create_subscriber_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
            `id` int(10) NOT NULL AUTO_INCREMENT,
            `user_id` int(10) NOT NULL default 0,
            `email` varchar(255) NOT NULL default '',
            `status` smallint(5),
            `first_name` varchar(255),
            `last_name` varchar(255),
            `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
            `updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY (`id`)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Set the plugin version after install or upgrade
	 */
	private function set_plugin_version() {
		 update_option( 'engagement_cloud_for_woocommerce_version', $this->version );
	}
}
