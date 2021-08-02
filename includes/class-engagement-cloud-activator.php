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

namespace Engagement_Cloud\Includes;

use Engagement_Cloud\Admin\Engagement_Cloud_Upgrader;

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
	 * Engagement Cloud tracking URL.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tracking_url    Engagement Cloud tracking URL.
	 */
	private $tracking_url;

	/**
	 * Plugin version
	 *
	 * @since 1.1.0
	 * @access private
	 * @var string
	 */
	private $version;

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
	 * @param string $tracking_url The URL of the Engagement Cloud tracking site.
	 * @param string $version Plugin Version.
	 */
	public function __construct( $plugin_name, $tracking_url, $version ) {

		$this->plugin_name  = $plugin_name;
		$this->tracking_url = $tracking_url;
		$this->version      = $version;

	}

	/**
	 * Executed upon plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		$plugin_upgrader = new Engagement_Cloud_Upgrader( $this->plugin_name, $this->version, $this->tracking_url );
		$upgrade = $plugin_upgrader->upgrade_check();
		if ( ! $upgrade ) {
			$plugin_upgrader->notify();
		}
	}
}
