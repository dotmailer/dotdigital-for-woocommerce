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

namespace Engagement_Cloud\Includes\Subscriber;

use Engagement_Cloud\Engagement_Cloud_Bootstrapper;

/**
 * Class Engagement_Cloud_Subscriber
 */
class Engagement_Cloud_Subscriber {

	const UNSUBSCRIBED = 0;
	const SUBSCRIBED   = 1;

	const SUBSCRIPTION_FAILED  = 0;
	const SUBSCRIPTION_SUCCESS = 1;

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

	/**
	 * Creates a new subscriber or updates the existing one with a new status if already exists.
	 *
	 * @param array $subscriber_data  Data to be stored.
	 * @return int
	 */
	public function create_or_update( $subscriber_data ) {
		global $wpdb;
		$table_name = $wpdb->prefix . Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME;

		$matching_subscriber = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$table_name} WHERE email = %s", $subscriber_data['email'] ) // phpcs:ignore WordPress.DB
		);

		$subscriber_data['created_at'] = current_time( 'mysql' );
		$subscriber_data['updated_at'] = current_time( 'mysql' );

		try {
			if ( $matching_subscriber ) {
				unset( $subscriber_data['created_at'] );
				$wpdb->update(
					$table_name,
					$subscriber_data,
					array(
						'email' => $subscriber_data['email'],
					)
				); // db call ok.
			} else {
				$wpdb->insert(
					$table_name,
					$subscriber_data
				); // db call ok.
			}
		} catch ( \Exception $e ) {
			return self::SUBSCRIPTION_FAILED;
		}

		return self::SUBSCRIPTION_SUCCESS;
	}
}
