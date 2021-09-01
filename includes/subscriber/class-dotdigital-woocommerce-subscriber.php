<?php
/**
 * A subscriber model.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.2.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/subscriber
 */

namespace Dotdigital_WooCommerce\Includes\Subscriber;

use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;

/**
 * Class Dotdigital_WooCommerce_Subscriber
 */
class Dotdigital_WooCommerce_Subscriber {

	const UNSUBSCRIBED = 0;
	const SUBSCRIBED   = 1;

	const SUBSCRIPTION_FAILED  = 0;
	const SUBSCRIPTION_SUCCESS = 1;

	/**
	 * This model's table name.
	 *
	 * @var string
	 */
	private $table_name;

	/**
	 * Dotdigital_WooCommerce_Subscriber constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . Dotdigital_WooCommerce_Config::SUBSCRIBERS_TABLE_NAME;
	}

	/**
	 * Mark an email address as unsubscribed.
	 *
	 * @param string $email An email address.
	 */
	public function unsubscribe( string $email ) {
		global $wpdb;

		$wpdb->update(
			$this->table_name,
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

		$matching_subscriber = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE email = %s", $subscriber_data['email'] ) // phpcs:ignore WordPress.DB
		);

		$subscriber_data['created_at'] = current_time( 'mysql' );
		$subscriber_data['updated_at'] = current_time( 'mysql' );

		try {
			if ( $matching_subscriber ) {
				unset( $subscriber_data['created_at'] );
				$this->update( $subscriber_data['email'], $subscriber_data );
			} else {
				$this->create( $subscriber_data );
			}
		} catch ( \Exception $e ) {
			return self::SUBSCRIPTION_FAILED;
		}

		return self::SUBSCRIPTION_SUCCESS;
	}

	/**
	 * Check if the supplied email address is subscribed.
	 *
	 * @param string $email An email address.
	 * @return bool
	 */
	public function is_subscribed( string $email ) {
		global $wpdb;

		$matching_subscriber = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE email = %s", $email ) // phpcs:ignore WordPress.DB
		);

		return (bool) $matching_subscriber && ( self::SUBSCRIBED === (int) $matching_subscriber->status );
	}

	/**
	 * Check if we have a matching row for the supplied user id,
	 * and that the status is 'subscribed'.
	 *
	 * @param int $id A user id.
	 * @return bool
	 */
	public function is_user_id_subscribed( int $id ) {
		if ( 0 === $id ) {
			return false;
		}

		global $wpdb;

		$matching_subscriber = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE user_id = %d", $id ) // phpcs:ignore WordPress.DB
		);

		return (bool) $matching_subscriber && ( self::SUBSCRIBED === (int) $matching_subscriber->status );
	}

	/**
	 * Create a subscriber.
	 * This method returns the auto-incremented ID of the created row,
	 * in common with WP object creation (posts, users etc.).
	 *
	 * @param array $data Array of data.
	 *
	 * @return int
	 */
	public function create( $data ) {
		global $wpdb;

		$insert = $wpdb->insert(
			$this->table_name,
			$data
		); // db call ok.

		return $wpdb->insert_id;
	}

	/**
	 * Update a subscriber.
	 *
	 * @param string $email Email address.
	 * @param array  $data Array of data.
	 */
	public function update( $email, $data ) {
		global $wpdb;

		$wpdb->update(
			$this->table_name,
			$data,
			array(
				'email' => $email,
			)
		); // db call ok.
	}

	/**
	 * Get by id (not user_id). Used by the unit test factory.
	 *
	 * @param int $id The row ID.
	 *
	 * @return array|object|void|null
	 */
	public function get_by_id( $id ) {
		global $wpdb;

		return $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE id = %s", $id ) // phpcs:ignore WordPress.DB
		);
	}

	/**
	 * Update by id (not user_id). Used by the unit test factory.
	 *
	 * @param int   $id The row ID.
	 * @param array $data Array of data.
	 */
	public function update_by_id( $id, $data ) {
		global $wpdb;

		$wpdb->update(
			$this->table_name,
			$data,
			array(
				'id' => $id,
			)
		); // db call ok.
	}

}
