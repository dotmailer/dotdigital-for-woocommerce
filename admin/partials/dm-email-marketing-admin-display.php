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

$dotmailer_em_store_name = get_bloginfo( 'name' );
$dotmailer_em_store_url = get_bloginfo( 'wpurl' );
$dotmailer_em_bridge_url = $dotmailer_em_store_url . '/bridge2cart/bridge.php';
$dotmailer_em_store_root = ABSPATH;

?>

<iframe id="dm4WcSettings" src="https://debug-webapp.dotmailer.internal/woocommerce/connect?
	storename=<?php echo rawurlencode( $dotmailer_em_store_name ); ?>&amp;
	storeurl=<?php echo rawurlencode( $dotmailer_em_store_url ); ?>&amp;
	bridgeurl=<?php echo rawurlencode( $dotmailer_em_bridge_url ); ?>&amp;
	storeroot=<?php echo rawurlencode( $dotmailer_em_store_root ); ?>"></iframe>
