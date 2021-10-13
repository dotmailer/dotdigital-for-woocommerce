<?php
/**
 * HTML output for the settings.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/admin/partials
 */

?>
<div class="wrap">
	<div id="icon-dotdigital" class="icon32"></div>
	<h2>Dotdigital for WooCommerce Settings</h2>
	<?php settings_errors(); ?>
	<form method="POST" action="options.php">
		<?php
		settings_fields( $this->plugin_name . '-settings' );
		do_settings_sections( $this->plugin_name . '-settings' );
		?>
		<?php submit_button(); ?>
	</form>
</div>
