<?php
/**
 * Handles data migrations.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/admin
 */

namespace Engagement_Cloud\Admin;

use Engagement_Cloud\Engagement_Cloud_Bootstrapper;

/**
 * Class Engagement_Cloud_Upgrader
 */
class Engagement_Cloud_Migrator {

	/**
	 * Prior to 1.2.0, subscribers were determined by a user meta key.
	 *
	 * @deprecated 1.2.0 Meta key value.
	 */
	const SUBSCRIBER_META_KEY = '_wc_subscribed_to_newsletter';

	/**
	 * Fetch existing subscribed users to dd_subscribers table
	 *
	 * @return bool|int
	 */
	public function migrate_users_to_subscriber_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME;

		// Do not overwrite existing.
		$result = $wpdb->get_results( "SELECT id from {$table_name} LIMIT 1" ); // phpcs:ignore WordPress.DB
		if ( count( $result ) !== 0 ) {
			return;
		}

		$sql = "SELECT ID, user_email FROM {$wpdb->prefix}users
            	INNER JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}usermeta.user_id = {$wpdb->prefix}users.ID 
            	WHERE {$wpdb->prefix}usermeta.meta_key = '" . self::SUBSCRIBER_META_KEY . "'
            	AND {$wpdb->prefix}usermeta.meta_value = 1
        ";

		$date = current_time( 'mysql' );
		$wpdb->query( "INSERT INTO {$table_name} (user_id, email) {$sql}" ); // phpcs:ignore WordPress.DB
		$count = $wpdb->query( "UPDATE {$table_name} set status=1, created_at='{$date}', updated_at='{$date}'" ); // phpcs:ignore WordPress.DB

		return $count;
	}
}
