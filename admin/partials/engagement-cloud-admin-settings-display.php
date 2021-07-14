<?php
/**
 * HTML output for the settings.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/admin/partials
 */

?>
<div class="wrap">
	<div id="icon-engagement-cloud" class="icon32"></div>
	<h2>dotdigital Engagement Cloud Settings</h2>
	<?php settings_errors(); ?>
	<form method="POST" action="options.php">
		<?php
		settings_fields( $this->plugin_name . '-settings' );
		do_settings_sections( $this->plugin_name . '-settings' );
		?>
		<?php submit_button(); ?>
	</form>
</div>
