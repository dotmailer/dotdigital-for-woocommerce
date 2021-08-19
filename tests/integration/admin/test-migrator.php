<?php
/**
 * Class MigratorTest
 *
 * @package Dotdigital_WooCommerce
 */

namespace Dotdigital_WooCommerce\Tests\Integration\Admin;

use Dotdigital_WooCommerce\Admin\Dotdigital_WooCommerce_Migrator;
use Dotdigital_WooCommerce\Admin\Dotdigital_WooCommerce_Upgrader;

/**
 * Migrator test.
 */
class MigratorTest extends \WP_UnitTestCase {

	/**
	 * @var Dotdigital_WooCommerce_Upgrader
	 */
	private $upgrader;

	/**
	 * @var Dotdigital_WooCommerce_Migrator
	 */
	private $migrator;

	/**
	 *
	 */
	public function setUp(): void {
		parent::setUp();

		// Upgrader is required so we can install the subscriber table
		$this->upgrader = new Dotdigital_WooCommerce_Upgrader(
			'test-plugin',
			'1.2.0',
			'https://chaz-tracking-link.net'
		);

		require_once plugin_dir_path( dirname( __FILE__ ) ) . '../../admin/class-dotdigital-woocommerce-migrator.php';
		$this->migrator = new Dotdigital_WooCommerce_Migrator();
	}

	/**
	 * Test that users with the old meta key are migrated to the new table.
	 */
	public function test_subscriber_meta_key_migration() {
		$user_count = 5;

		$this->install_tables();

		$user_ids = $this->factory()->user->create_many($user_count);
		foreach ( $user_ids as $user_id ) {
			add_user_meta( $user_id, Dotdigital_WooCommerce_Migrator::SUBSCRIBER_META_KEY, 1 );
		}

		$migrated = $this->migrator->migrate_users_to_subscriber_table();

		$this->assertEquals( $migrated, $user_count );
	}

	/**
	 * Tear down after the test ends
	 */
	public function tearDown(): void
	{
		parent::tearDown();
	}

	/**
	 * Install required tables
	 */
	private function install_tables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$this->upgrader->create_subscriber_table();
	}
}
