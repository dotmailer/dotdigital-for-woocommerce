<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.dotmailer.com/
 * @since      1.0.0
 *
 * @package    Dotmailer
 * @subpackage Dotmailer/admin/partials
 */

global $wpdb;
$dotmailer_table_name = $wpdb->prefix . 'dotmailerail_marketing';

// @codingStandardsIgnoreStart
$dotmailer_plugin_id = $wpdb->get_var( "SELECT PluginID FROM $dotmailer_table_name" );
// @codingStandardsIgnoreEnd

$dotmailer_store_name = get_bloginfo( 'name' );
$dotmailer_store_url = get_bloginfo( 'wpurl' );
$dotmailer_bridge_url = $dotmailer_store_url . '/bridge2cart/bridge.php';
$dotmailer_store_root = str_replace( '\\', '/', ABSPATH );

?>

<iframe id="dm4WcSettings" src="https://debug-webapp.dotmailer.internal/woocommerce/connect?
	storename=<?php echo rawurlencode( $dotmailer_store_name ); ?>&amp;
	storeurl=<?php echo rawurlencode( $dotmailer_store_url ); ?>&amp;
	bridgeurl=<?php echo rawurlencode( $dotmailer_bridge_url ); ?>&amp;
	storeroot=<?php echo rawurlencode( $dotmailer_store_root ); ?>&amp;
	pluginid=<?php echo rawurlencode( $dotmailer_plugin_id ); ?>"></iframe>
