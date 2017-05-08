<?php
/**
 * Fired during plugin validation
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dotmailer
 * @subpackage Dotmailer/includes
 */

/**
 * Fired during plugin validation.
 *
 * This class defines all code necessary to run during the plugin's validation.
 *
 * @since      1.0.0
 * @subpackage Dotmailer/includes
 * @author     dotmailer <integrations@dotmailer.com>
 */
class Dotmailer_Validator {

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
	 * @param   string $plugin_name 	The name of the plugin.
	 * @param   string $plugin_path 	The path of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_path ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_path = $plugin_path;
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	function self_deactivate() {
		deactivate_plugins( $this->plugin_path );
	}
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	function remove_admin_menu_page() {
		remove_menu_page( $this->plugin_name );
	}
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	function plugin_activation_failure_message() {
	?>
		<div class="notice notice-error is-dismissible">
			<p><?php esc_html_e( 'dotmailer plugin will remain deactivated until an ecommerce plugin is installed and activated.', 'dotmailer-email-marketing' ); ?></p>
		</div>
	<?php
	}
}
