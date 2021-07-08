<?php
/**
 * A subscriber model.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    EngagementCloud
 * @subpackage EngagementCloud/subscriber
 */

/**
 * Class Engagement_Cloud_Subscriber
 */
class Engagement_Cloud_Subscriber {

	const UNSUBSCRIBED = 0;
	const SUBSCRIBED   = 1;

	/**
	 * Mark an email address as unsubscribed.
	 *
	 * @param string $email An email address.
	 */
	public function unsubscribe( string $email ) {
		global $wpdb;

		$subscribers_table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME;

		$wpdb->update(
			$subscribers_table_name,
			array(
				'status' => self::UNSUBSCRIBED,
			),
			array(
				'email' => $email,
			)
		);
	}
}
