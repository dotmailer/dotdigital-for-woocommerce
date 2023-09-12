<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.0.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/admin
 */

namespace Dotdigital_WooCommerce\Admin;

use Dotdigital_WooCommerce\Includes\Client\Dotdigital_WooCommerce_Lists;
use Dotdigital_WooCommerce\Admin\Partials\Dotdigital_WooCommerce_Admin_Display;
use Dotdigital_WooCommerce\Admin\Settings\Dotdigital_WooCommerce_Api_Credentials_Handler;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/admin
 * @author     dotdigital <integrations@dotdigital.com>
 */
class Dotdigital_WooCommerce_Admin {

	const GENERAL_SECTION = 'dd_woo_settings_page_general_section';
	const SMS_MARKETING_SECTION = 'dd_woo_settings_page_sms_marketing_section';
	const TRACKING_SECTION = 'dd_woo_settings_page_tracking_section';
	const ABANDONED_CART_SECTION = 'dd_woo_settings_abandoned_cart_section';
	const API_USERNAME_FIELD = 'dotdigital_for_woocommerce_settings_dotdigital_api_username_field';
	const API_PASSWORD_FIELD = 'dotdigital_for_woocommerce_settings_dotdigital_api_password_field';

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
	 * Account login URL.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $webapp_url    dotdigital URL.
	 */
	private $webapp_url;

	/**
	 * Lists handler.
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var Dotdigital_WooCommerce_Lists
	 */
	private $lists;

	/**
	 * Settings handler.
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var Dotdigital_WooCommerce_Api_Credentials_Handler
	 */
	public $handler;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name    The name of this plugin.
	 * @param    string $version        The version of this plugin.
	 * @param    string $webapp_url     dotdigital URL.
	 */
	public function __construct( $plugin_name, $version, $webapp_url ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->webapp_url  = $webapp_url;
		$this->handler     = new Dotdigital_WooCommerce_Api_Credentials_Handler();
		$this->lists       = new Dotdigital_WooCommerce_Lists();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dotdigital_WooCommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dotdigital_WooCommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dotdigital-woocommerce-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dotdigital_WooCommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dotdigital_WooCommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dotdigital-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		* Add a settings page for this plugin to the Settings menu.
		*
		* NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		*
		*        Administration Menus: http://codex.wordpress.org/Administration_Menus
		*
		*/
		$admin_display = new Dotdigital_WooCommerce_Admin_Display( $this->plugin_name, $this->webapp_url );
		$icon_svg      = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMiAzMiI+PHBhdGggZD0iTTE2LDIuNzhBMTMuMjIsMTMuMjIsMCwxLDEsMi43OCwxNiwxMy4yMywxMy4yMywwLDAsMSwxNiwyLjc4TTE2LDBBMTYsMTYsMCwxLDAsMzIsMTYsMTYsMTYsMCwwLDAsMTYsMFoiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNMTYsOC4yOUE3Ljc0LDcuNzQsMCwxLDEsOC4yNiwxNiw3Ljc1LDcuNzUsMCwwLDEsMTYsOC4yOW0wLTIuNzhBMTAuNTIsMTAuNTIsMCwxLDAsMjYuNTIsMTYsMTAuNTIsMTAuNTIsMCwwLDAsMTYsNS41MVoiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNMTYsMTMuNzdBMi4yNiwyLjI2LDAsMSwxLDEzLjc1LDE2LDIuMjYsMi4yNiwwLDAsMSwxNiwxMy43N00xNiwxMWE1LDUsMCwxLDAsNSw1LDUsNSwwLDAsMC01LTVaIiBmaWxsPSIjZmZmIi8+PC9zdmc+';

		add_menu_page(
			'Dotdigital for WooCommerce settings',
			'Dotdigital for WooCommerce',
			'manage_options',
			$this->plugin_name,
			array( $admin_display, 'display_plugin_setup_page' ),
			'data:image/svg+xml;base64,' . $icon_svg,
			58
		);

		add_submenu_page(
			$this->plugin_name,
			'Connect',
			'Connect',
			'manage_options',
			$this->plugin_name
		);

		add_submenu_page(
			$this->plugin_name,
			__( 'Dotdigital for WooCommerce Settings' ),
			__( 'Settings' ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'display_plugin_settings_page' )
		);
	}

	/**
	 * Display the plugin settings page.
	 *
	 * @since    1.2.0
	 */
	public function display_plugin_settings_page() {
		require_once 'partials/dotdigital-woocommerce-admin-settings-display.php';
	}

	/**
	 * Register settings: define the containing section and the fields to go in it.
	 *
	 * @since    1.2.0
	 */
	public function register_settings() {
		$this->register_settings_for_marketing_subscription();
		$this->register_settings_for_sms_marketing_subscription();
		$this->register_settings_for_tracking();
		$this->register_abandoned_cart_settings();
	}

	/**
	 * A template for a checkbox field.
	 *
	 * @param array $args An array of arguments.
	 */
	public function settings_page_render_checkbox( $args ) {
		$value = get_option( $args['id'], $args['default_value'] );
		echo '<input type="checkbox" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" value="1"' . checked( 1, $value, false ) . ( isset( $args['disabled'] ) && $args['disabled'] ? 'disabled' : '' ) . '/>';
	}

	/**
	 * A template for a text input field.
	 *
	 * @param array $args An array of arguments.
	 */
	public function settings_page_render_text_input( $args ) {
		$value = get_option( $args['id'], $args['default_value'] );
		echo '<input type="text" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $value ) . '" size="40"' . ( isset( $args['disabled'] ) && $args['disabled'] ? 'disabled' : '' ) . '/>';
	}

	/**
	 * A template for a textarea input field.
	 *
	 * @param array $args An array of arguments.
	 */
	public function settings_page_render_textarea_input( $args ) {
		$value = get_option( $args['id'], $args['default_value'] );
		echo '<textarea id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" rows="4" cols="40">' . esc_attr( $value ) . '</textarea>';
	}

	/**
	 * A template for a text input field.
	 *
	 * @param array $args An array of arguments.
	 */
	public function settings_page_render_username_input( $args ) {
		$value = get_option( Dotdigital_WooCommerce_Config::API_CREDENTIALS_PATH, $args['default_value'] );
		echo '<input type="text" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( Dotdigital_WooCommerce_Config::API_CREDENTIALS_PATH ) . '[username] " value="' . esc_attr( $value['username'] ?? null ) . '" size="40"' . ( isset( $args['disabled'] ) && $args['disabled'] ? 'disabled' : '' ) . '/>';
	}

	/**
	 * A template for a text input field.
	 *
	 * @param array $args An array of arguments.
	 */
	public function settings_page_render_password_input( $args ) {
		$value = get_option( Dotdigital_WooCommerce_Config::API_CREDENTIALS_PATH, $args['default_value'] );
		echo '<input type="password" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( Dotdigital_WooCommerce_Config::API_CREDENTIALS_PATH ) . '[password] " value="' . esc_attr( $value['password'] ?? null ) . '" size="40"' . ( isset( $args['disabled'] ) && $args['disabled'] ? 'disabled' : '' ) . '/>';
	}

	/**
	 * A template for a numeric input field.
	 *
	 * @param array $args An array of arguments.
	 */
	public function settings_page_render_numeric_input( $args ) {
		$value = get_option( $args['id'], $args['default_value'] );
		echo '<input type="number" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $value ) . '" min="0"' . ( isset( $args['disabled'] ) && $args['disabled'] ? 'disabled' : '' ) . '/>';
	}

	/**
	 * A template for a dropdown field.
	 *
	 * @param array $args An array of arguments.
	 */
	public function settings_page_render_dropdown( $args ) {
		$selected_region = get_option( $args['name'], $args['default_value'] );
		?>
			<select id='<?php echo sanitize_key( $args['id'] ); ?>' name='<?php echo sanitize_key( $args['name'] ); ?>'
					<?php echo ( isset( $args['disabled'] ) && $args['disabled'] ? 'disabled' : '' ); ?>>
		<?php
		foreach ( $args['items'] as $value => $label ) {
			?>
			<option value='<?php echo sanitize_key( $value ); ?>' <?php selected( $selected_region, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php
		}
		echo '</select>';
	}

	/**
	 * Email section subtitle.
	 *
	 * @param array $arg arguments.
	 * @return void
	 */
	public function email_section_subtitle( $arg ) {
		echo '<p>' . esc_html( __( 'Choose how you gather consent for your email marketing subscribers.' ) ) . '</p>';
	}

	/**
	 * SMS section subtitle.
	 *
	 * @param array $arg arguments.
	 * @return void
	 */
	public function sms_section_subtitle( $arg ) {
		echo '<p>' . esc_html( __( 'Choose how you gather consent for your SMS marketing subscribers.' ) ) . '</p>';
		echo '<h2>' . esc_html( __( 'Dotdigital API' ) ) . '</h2>';
		echo '<p>' . esc_html( __( 'SMS marketing for WooCommerce uses the Dotdigital API.' ) ) . '</p>';
	}

	/**
	 * Email marketing lists custom field.
	 *
	 * @param array $arg arguments.
	 * @return void
	 */
	public function email_marketing_lists( $arg ) {
		echo '<p>' . esc_html( __( 'In Dotdigital, go to Connect > WooCommerce ' ) ) . '<a href="https://support.dotdigital.com/hc/en-gb/articles/6575059852818" target="_blank">' . esc_html( __( 'Learn more' ) ) . '</a></p>';
	}

	/**
	 * Register settings for marketing subscription.
	 *
	 * @since 1.2.0
	 */
	private function register_settings_for_marketing_subscription() {

		/**
		 * Add settings section for marketing subscription.
		 */
		add_settings_section(
			self::GENERAL_SECTION,
			__( 'Email marketing consent' ),
			array( $this, 'email_section_subtitle' ),
			$this->plugin_name . '-settings'
		);

		/**
		 * Add settings field [show checkbox at checkout].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_CHECKOUT,
			__( 'Show checkbox at checkout' ),
			array( $this, 'settings_page_render_checkbox' ),
			$this->plugin_name . '-settings',
			self::GENERAL_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_CHECKOUT,
				'name'          => Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_CHECKOUT,
				'default_value' => '',
			)
		);

		/**
		 * Add settings field [show checkbox at user registration].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_REGISTER,
			__( 'Show checkbox at user registration' ),
			array( $this, 'settings_page_render_checkbox' ),
			$this->plugin_name . '-settings',
			self::GENERAL_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_REGISTER,
				'name'          => Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_REGISTER,
				'default_value' => '',
			)
		);

		/**
		 * Add settings field [email checkbox text].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_TEXT,
			__( 'Checkbox text' ),
			array( $this, 'settings_page_render_text_input' ),
			$this->plugin_name . '-settings',
			self::GENERAL_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_TEXT,
				'name'          => Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_TEXT,
				'default_value' => '',
			)
		);

		/**
		 * Add settings field [email checkbox text].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::MARKETING_EMAIL_LISTS,
			__( 'Add email subscribers to' ),
			array( $this, 'email_marketing_lists' ),
			$this->plugin_name . '-settings',
			self::GENERAL_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::MARKETING_EMAIL_LISTS,
				'name'          => Dotdigital_WooCommerce_Config::MARKETING_EMAIL_LISTS,
				'default_value' => '',
			)
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_TEXT
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_CHECKOUT
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::SHOW_MARKETING_CHECKBOX_REGISTER
		);
	}

	/**
	 * Register settings for sms marketing subscription.
	 *
	 * @return void
	 * @throws \Http\Client\Exception If request fails.
	 */
	private function register_settings_for_sms_marketing_subscription() {
		/**
		 * Add settings section for sms marketing subscription.
		 */
		add_settings_section(
			self::SMS_MARKETING_SECTION,
			__( 'SMS Marketing subscription' ),
			array( $this, 'sms_section_subtitle' ),
			$this->plugin_name . '-settings'
		);

		/**
		 * Add settings field [username].
		 */
		add_settings_field(
			self::API_USERNAME_FIELD,
			__( 'Username' ),
			array( $this, 'settings_page_render_username_input' ),
			$this->plugin_name . '-settings',
			self::SMS_MARKETING_SECTION,
			array(
				'id'            => self::API_USERNAME_FIELD,
				'name'          => self::API_USERNAME_FIELD,
				'default_value' => '',
			)
		);

		/**
		 * Add settings field [password].
		 */
		add_settings_field(
			self::API_PASSWORD_FIELD,
			__( 'Password' ),
			array( $this, 'settings_page_render_password_input' ),
			$this->plugin_name . '-settings',
			self::SMS_MARKETING_SECTION,
			array(
				'id'            => self::API_PASSWORD_FIELD,
				'name'          => self::API_PASSWORD_FIELD,
				'default_value' => '',
			)
		);

		/**
		 * Add settings field [show sms marketing checkbox at checkout].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_CHECKOUT,
			__( 'Show checkbox at checkout' ),
			array( $this, 'settings_page_render_checkbox' ),
			$this->plugin_name . '-settings',
			self::SMS_MARKETING_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_CHECKOUT,
				'name'          => Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_CHECKOUT,
				'default_value' => 0,
			)
		);

		/**
		 * Add settings field [show sms marketing checkbox at user registration].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION,
			__( 'Show checkbox at user registration' ),
			array( $this, 'settings_page_render_checkbox' ),
			$this->plugin_name . '-settings',
			self::SMS_MARKETING_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION,
				'name'          => Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION,
				'default_value' => 0,
			)
		);

		/**
		 * Add settings field [SMS checkbox text].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_SMS_TEXT,
			__( 'Checkbox text' ),
			array( $this, 'settings_page_render_text_input' ),
			$this->plugin_name . '-settings',
			self::SMS_MARKETING_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_SMS_TEXT,
				'name'          => Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_SMS_TEXT,
				'default_value' => '',
			)
		);

		/**
		 * Add settings field [SMS consent text].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::MARKETING_CONSENT_SMS_TEXT,
			__( 'Consent text' ),
			array( $this, 'settings_page_render_textarea_input' ),
			$this->plugin_name . '-settings',
			self::SMS_MARKETING_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::MARKETING_CONSENT_SMS_TEXT,
				'name'          => Dotdigital_WooCommerce_Config::MARKETING_CONSENT_SMS_TEXT,
				'default_value' => '',
			)
		);

		/**
		 * Add settings field [Select Region].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::MARKETING_SMS_LISTS,
			__( 'Add SMS subscribers to' ),
			array( $this, 'settings_page_render_dropdown' ),
			$this->plugin_name . '-settings',
			self::SMS_MARKETING_SECTION,
			array(
				'id' => Dotdigital_WooCommerce_Config::MARKETING_SMS_LISTS,
				'name' => Dotdigital_WooCommerce_Config::MARKETING_SMS_LISTS,
				'default_value' => 0,
				'items' => $this->lists->get(),
			)
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::API_CREDENTIALS_PATH,
			array( $this->handler, 'sanitize_api_credentials' )
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_CHECKOUT
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::SHOW_SMS_MARKETING_CHECKBOX_USER_REGISTRATION
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::MARKETING_CHECKBOX_SMS_TEXT
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::MARKETING_CONSENT_SMS_TEXT
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::MARKETING_SMS_LISTS
		);
	}

	/**
	 * Register settings for tracking section.
	 *
	 * @since 1.2.0
	 */
	private function register_settings_for_tracking() {

		/**
		 * Add settings section for tracking.
		 */
		add_settings_section(
			self::TRACKING_SECTION,
			__( 'Tracking' ),
			null,
			$this->plugin_name . '-settings'
		);

		/**
		 * Add settings field [enable site and roi tracking].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::SITE_AND_ROI_TRACKING,
			__( 'Enable site and ROI tracking' ),
			array( $this, 'settings_page_render_checkbox' ),
			$this->plugin_name . '-settings',
			self::TRACKING_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::SITE_AND_ROI_TRACKING,
				'name'          => Dotdigital_WooCommerce_Config::SITE_AND_ROI_TRACKING,
				'default_value' => Dotdigital_WooCommerce_Config::DEFAULT_SITE_AND_ROI_TRACKING_ENABLED,
			)
		);

		/**
		 * Add settings field [Select Region].
		 */
		add_settings_field(
			'selected_region',
			__( 'Select region' ),
			array( $this, 'settings_page_render_dropdown' ),
			$this->plugin_name . '-settings',
			self::TRACKING_SECTION,
			array(
				'id' => Dotdigital_WooCommerce_Config::REGION,
				'name' => Dotdigital_WooCommerce_Config::REGION,
				'default_value' => Dotdigital_WooCommerce_Config::DEFAULT_REGION,
				'items' => array(
					'1' => 'Region 1',
					'2' => 'Region 2',
					'3' => 'Region 3',
				),
			)
		);

		/**
		 * Add settings field [Profile Id].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::WBT_PROFILE_ID_PATH,
			__( 'Web behavior tracking profile ID' ),
			array( $this, 'settings_page_render_text_input' ),
			$this->plugin_name . '-settings',
			self::TRACKING_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::WBT_PROFILE_ID_PATH,
				'name'          => Dotdigital_WooCommerce_Config::WBT_PROFILE_ID_PATH,
				'default_value' => '',
			)
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::REGION
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::SITE_AND_ROI_TRACKING
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::WBT_PROFILE_ID_PATH
		);
	}

	/**
	 * Register settings for abandoned carts configurations section.
	 *
	 * @since 1.3.0
	 */
	private function register_abandoned_cart_settings() {
		/**
		  * Add settings section for abandoned cart configurations.
		  */
		add_settings_section(
			self::ABANDONED_CART_SECTION,
			__( 'Abandoned carts' ),
			function () {
				echo '<p>' .
				esc_html__( 'A web behaviour tracking profile ID is required to modify these settings.', 'dotdigital-woocommerce' ) .
				'</p>';
			},
			$this->plugin_name . '-settings'
		);

		/**
		 * Add settings field [enable abandoned cart].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::AC_STATUS_PATH,
			__( 'Enable abandoned cart' ),
			array( $this, 'settings_page_render_checkbox' ),
			$this->plugin_name . '-settings',
			self::ABANDONED_CART_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::AC_STATUS_PATH,
				'name'          => Dotdigital_WooCommerce_Config::AC_STATUS_PATH,
				'default_value' => '',
				'disabled' => $this->is_disabled_field(),
			)
		);

		/**
		 * Add settings field [AC program Id].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::PROGRAM_ID_PATH,
			__( 'Abandoned cart program ID' ),
			array( $this, 'settings_page_render_text_input' ),
			$this->plugin_name . '-settings',
			self::ABANDONED_CART_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::PROGRAM_ID_PATH,
				'name'          => Dotdigital_WooCommerce_Config::PROGRAM_ID_PATH,
				'default_value' => '',
				'disabled' => $this->is_disabled_field(),
			)
		);

		/**
		 * Add settings field [Cart delay].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::CART_DELAY_PATH,
			__( 'Allow abandoned cart delay (minutes)' ),
			array( $this, 'settings_page_render_numeric_input' ),
			$this->plugin_name . '-settings',
			self::ABANDONED_CART_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::CART_DELAY_PATH,
				'name'          => Dotdigital_WooCommerce_Config::CART_DELAY_PATH,
				'default_value' => '',
				'disabled' => $this->is_disabled_field(),
			)
		);

		/**
		 * Add settings field [Allow AC for non subscribers].
		 */
		add_settings_field(
			Dotdigital_WooCommerce_Config::ALLOW_NON_SUBSCRIBERS_PATH,
			__( 'Allow abandoned cart for non-subscribed contacts' ),
			array( $this, 'settings_page_render_checkbox' ),
			$this->plugin_name . '-settings',
			self::ABANDONED_CART_SECTION,
			array(
				'id'            => Dotdigital_WooCommerce_Config::ALLOW_NON_SUBSCRIBERS_PATH,
				'name'          => Dotdigital_WooCommerce_Config::ALLOW_NON_SUBSCRIBERS_PATH,
				'default_value' => '',
				'disabled' => $this->is_disabled_field(),
			)
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::PROGRAM_ID_PATH
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::CART_DELAY_PATH
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::AC_STATUS_PATH
		);

		register_setting(
			$this->plugin_name . '-settings',
			Dotdigital_WooCommerce_Config::ALLOW_NON_SUBSCRIBERS_PATH
		);
	}

	/**
	 * Checks if field has disabled status.
	 *
	 * @since 1.3.0
	 * @return bool
	 */
	private function is_disabled_field() {
		return ! get_option( Dotdigital_WooCommerce_Config::WBT_PROFILE_ID_PATH );
	}
}
