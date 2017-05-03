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
$dotmailer_table_name = $wpdb->prefix . 'dotmailer';

// @codingStandardsIgnoreStart
$dotmailer_plugin_id = $wpdb->get_var( "SELECT PluginID FROM $dotmailer_table_name" );
// @codingStandardsIgnoreEnd

$dotmailer_store_name = get_bloginfo( 'name' );
$dotmailer_store_url = get_bloginfo( 'wpurl' );
$dotmailer_bridge_url = $dotmailer_store_url . '/bridge2cart/bridge.php';
$dotmailer_store_root = str_replace( '\\', '/', ABSPATH );

$dotmailer_query = http_build_query( array(
	'storename' => $dotmailer_store_name,
	'storeurl' => $dotmailer_store_url,
	'bridgeurl' => $dotmailer_bridge_url,
	'storeroot' => $dotmailer_store_root,
	'pluginid' => $dotmailer_plugin_id,
) );
?>

<iframe id="dotmailer-settings" src="https://debug-webapp.dotmailer.internal/woocommerce/connect?<?php echo esc_html( $dotmailer_query ) ?>"></iframe>
