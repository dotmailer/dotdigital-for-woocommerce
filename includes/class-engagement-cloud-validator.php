<?php
/**
 * Fired during plugin validation
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/includes
 */

/**
 * Fired during plugin validation.
 *
 * This class defines all code necessary to run during the plugin's validation.
 *
 * @since      1.0.0
 * @subpackage EngagementCloud/includes
 * @author     dotdigital <integrations@dotdigital.com>
 */
class Engagement_Cloud_Validator {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The path of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_path    The path of this plugin.
	 */
	private $plugin_path;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param   string $plugin_name     The name of the plugin.
	 * @param   string $plugin_path     The path of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_path ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_path = $plugin_path;
	}

	/**
	 * Deactivates the plugin.
	 *
	 * Deactivates this plugin if the environment doesn't meet requirements.
	 *
	 * @since    1.0.0
	 */
	public function self_deactivate() {
		deactivate_plugins( $this->plugin_path );
	}

	/**
	 * Removes the Engagement Cloud admin page from WordPress.
	 *
	 * Removes the Engagement Cloud admin page from WordPress upon deactivation.
	 *
	 * @since    1.0.0
	 */
	public function remove_admin_menu_page() {
		remove_menu_page( $this->plugin_name );
	}

	/**
	 * Displays activation failure/deactivation message.
	 *
	 * Displays activation failure/deactivation message if the environment doesn't meet requirements.
	 *
	 * @since    1.0.0
	 */
	public function plugin_activation_failure_message() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php esc_html_e( 'Engagement Cloud plugin will remain deactivated until an ecommerce plugin is installed and activated.', 'dotdigital-engagement-cloud' ); ?></p>
		</div>
		<?php
	}
}
