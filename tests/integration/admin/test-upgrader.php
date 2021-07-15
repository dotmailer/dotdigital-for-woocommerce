<?php
/**
 * Class UpgraderTest
 *
 * @package Engagement_Cloud_For_Woocommerce
 */

/**
 * Upgrader test.
 */
class UpgraderTest extends WP_UnitTestCase {

	/**
	 * @var Engagement_Cloud_Upgrader
	 */
	private $upgrader;

	/**
	 * @var string
	 */
	private $code_version;

	/**
	 *
	 */
	public function setUp(): void {
		parent::setUp();

		wp_set_current_user( 1 );

		$this->code_version = Engagement_Cloud_Bootstrapper::get_version();
		$this->upgrader = new Engagement_Cloud_Upgrader(
			'test-plugin',
			$this->code_version,
			'https://chaz-tracking-link.net'
		);
	}

	/**
	 * Test upgrade from blank state.
	 */
	public function test_upgrade_from_no_stored_version() {
		$stored_version = null;

		$this->upgrader->upgrade_check();

		$this->assertEquals(get_option( 'engagement_cloud_for_woocommerce_version' ), $this->code_version);
	}

	/**
	 * Test upgrade from 1.0.0.
	 */
	public function test_upgrade_from_version_one_zero_zero() {
		$stored_version = '1.0.0';
		update_option( 'engagement_cloud_for_woocommerce_version', $stored_version );
		$this->install_tables();

		$this->upgrader->upgrade_check();

		$this->assertEquals(get_option( 'engagement_cloud_for_woocommerce_version' ), $this->code_version);
	}

	/**
	 * Test upgrade check if versions match.
	 */
	public function test_upgrade_check_if_versions_match() {
		$stored_version = $this->code_version;
		update_option( 'engagement_cloud_for_woocommerce_version', $stored_version );

		$this->upgrader->upgrade_check();

		$this->assertEquals(get_option( 'engagement_cloud_for_woocommerce_version' ), $this->code_version);
	}

	/**
	 * Tear down after the test ends
	 */
	public function tearDown(): void
	{
		delete_option( 'engagement_cloud_for_woocommerce_version' );
		parent::tearDown();
	}

	/**
	 * Install required tables
	 */
	private function install_tables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$this->upgrader->create_email_marketing_table();
	}
}
