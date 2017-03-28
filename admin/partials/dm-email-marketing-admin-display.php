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

<iframe id="dm4WcSettings" src="https://debug-webapp.dotmailer.internal/woocommerce/connect?storename=<?php echo rawurlencode( $store_name ); ?>&amp;storeurl=<?php echo rawurlencode( $store_url ); ?>"></iframe>
