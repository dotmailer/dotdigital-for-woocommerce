<?php
/**
 * Handles plugin upgrades.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/admin
 */

namespace Dotdigital_WooCommerce\Admin;

use Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Rest_Api;
use Dotdigital_WooCommerce\Admin\Dotdigital_WooCommerce_Migrator;

/**
 * Class Dotdigital_WooCommerce_Upgrader
 */
class Dotdigital_WooCommerce_Upgrader {

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
	 * The dotdigital tracking URL.
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
	 *
	 * @return bool If we did upgrade, return true.
	 */
	public function upgrade_check() {
		$this->stored_version = get_option( 'dotdigital_for_woocommerce_version' );
		if ( version_compare( $this->stored_version, $this->version, '>=' ) ) {
			return false;
		}

		$this->upgrade();
		$this->notify();
		$this->set_plugin_version();

		return true;
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
	 * Notify dotdigital of the upgrade.
	 */
	public function notify() {
		$service      = new Dotdigital_WooCommerce_Rest_Api( $this->plugin_name );

		$data = array(
			'callback_url' => $service->get_rest_callback_url(),
			'pluginid'     => $this->generate_and_store_plugin_id(),
			'version' => $this->version,
		);
		wp_remote_post( "$this->tracking_url/e/woocommerce/enable?" . http_build_query( $data ) );
	}

	/**
	 * Generates and stores a 128 character plugin_id to serve as an auth token for EC.
	 *
	 * @return string|null
	 */
	private function generate_and_store_plugin_id() {
		global $wpdb;
		$table_name = $wpdb->prefix . Dotdigital_WooCommerce_Bootstrapper::EMAIL_MARKETING_TABLE_NAME;
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

			$migrator = new Dotdigital_WooCommerce_Migrator();
			$migrator->migrate_users_to_subscriber_table();
		}
	}

	/**
	 * Creates the dotmailer_email_marketing table.
	 */
	public function create_email_marketing_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . Dotdigital_WooCommerce_Bootstrapper::EMAIL_MARKETING_TABLE_NAME;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            PluginID VARCHAR(256) NOT NULL
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Create the dd_subscribers table.
	 */
	public function create_subscriber_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . Dotdigital_WooCommerce_Bootstrapper::SUBSCRIBERS_TABLE_NAME;
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
	 * Fetch existing subscribed users to dd_subscribers table
	 *
	 * @since 1.0.0
	 * @package dotdigital
	 */
	private function migrate_users_to_subscriber_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . Dotdigital_WooCommerce_Bootstrapper::SUBSCRIBERS_TABLE_NAME;

		// Do not overwrite existing.
		$result = $wpdb->get_results( "SELECT id from {$table_name} LIMIT 1" ); // phpcs:ignore WordPress.DB
		if ( count( $result ) !== 0 ) {
			return;
		}

		$sql = "SELECT ID, user_email FROM {$wpdb->prefix}users
            	INNER JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}usermeta.user_id = {$wpdb->prefix}users.ID 
            	WHERE {$wpdb->prefix}usermeta.meta_key = '_wc_subscribed_to_newsletter'
            	AND {$wpdb->prefix}usermeta.meta_value = 1
        ";

		$date = current_time( 'mysql' );
		$wpdb->query( "INSERT INTO {$table_name} (user_id, email) {$sql}" ); // phpcs:ignore WordPress.DB
		$wpdb->query( "UPDATE {$table_name} set status=1, created_at='{$date}', updated_at='{$date}'" ); // phpcs:ignore WordPress.DB
	}

	/**
	 * Set the plugin version after install or upgrade
	 */
	private function set_plugin_version() {
		 update_option( 'dotdigital_for_woocommerce_version', $this->version );
	}
}
