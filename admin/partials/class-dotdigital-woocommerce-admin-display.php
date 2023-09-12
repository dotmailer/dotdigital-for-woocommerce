<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes
 */

namespace Dotdigital_WooCommerce\Admin\Partials;

use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/admin/partials
 */
class Dotdigital_WooCommerce_Admin_Display {

	/**
	 * The name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name   The name of this plugin.
	 */
	private $plugin_name;

	/**
	 * Account login URL.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $webapp_url    dotdigital URL.
	 */
	private $webapp_url;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 *
	 * @param string $plugin_name   The name of this plugin.
	 * @param string $webapp_url    dotdigital URL.
	 */
	public function __construct( $plugin_name, $webapp_url ) {

		$this->plugin_name = $plugin_name;
		$this->webapp_url  = $webapp_url;
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {

		$store_name = $this->get_store_name();
		$store_url  = get_bloginfo( 'wpurl' );
		$bridge_url = $store_url . '/bridge2cart/bridge.php';
		$store_root = '\\' === DIRECTORY_SEPARATOR ?
			'/' . str_replace( '\\', '/', ABSPATH ) :
			ABSPATH;

		global $wpdb;
		$table_name = $wpdb->prefix . Dotdigital_WooCommerce_Config::EMAIL_MARKETING_TABLE_NAME;

		// @codingStandardsIgnoreStart
		$plugin_id = $wpdb->get_var( "SELECT PluginID FROM $table_name" );
		$plugin_version = get_option( Dotdigital_WooCommerce_Config::PLUGIN_VERSION );
		// @codingStandardsIgnoreEnd

		$connection_query = http_build_query(
			array(
				'storename' => $store_name,
				'storeurl'  => $store_url,
				'bridgeurl' => $bridge_url,
				'storeroot' => $store_root,
				'pluginid'  => $plugin_id,
				'pluginversion' => $plugin_version,
			)
		);

		echo '<iframe id="dotdigital-for-woocommerce-settings" src="' . esc_html( $this->webapp_url ) . '/woocommerce/connect?' . esc_html( $connection_query ) . '"></iframe>';
	}

	/**
	 * Gets store name from db and converts it's HTML entities to their corresponding characters
	 *
	 * @since    1.1.1
	 */
	private function get_store_name() {
		return html_entity_decode( get_bloginfo( 'name' ), ENT_QUOTES, $this->get_charset() );
	}

	/**
	 * Gets charset from db / if empty defaults to UTF-8
	 *
	 * @since    1.1.1
	 */
	private function get_charset() {
		$charset = get_bloginfo( 'charset' );
		return empty( $charset ) ? 'UTF-8' : $charset;
	}
}
