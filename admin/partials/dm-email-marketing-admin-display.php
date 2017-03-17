<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dm_Email_Marketing
 * @subpackage Dm_Email_Marketing/admin/partials
 */

$store_name = get_bloginfo( 'name' );
$store_url = get_bloginfo( 'wpurl' );

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="rhino">
	<iframe src="https://debug-webapp.dotmailer.internal/woocommerce/connect?storename=<?php $store_name ?>&storeurl=<?php $store_url ?>"></iframe>
</div>

