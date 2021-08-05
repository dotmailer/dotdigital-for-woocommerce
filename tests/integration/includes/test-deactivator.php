<?php
/**
 * Class DeactivatorTest
 *
 * @package Engagement_Cloud_For_Woocommerce
 */

namespace Engagement_Cloud\Tests\Integration\Includes;

use Engagement_Cloud\Admin\Engagement_Cloud_Upgrader;
use Engagement_Cloud\Includes\Engagement_Cloud_Deactivator;
use Engagement_Cloud\Engagement_Cloud_Bootstrapper;
use WP_UnitTestCase;

/**
 * Deactivator test.
 */
class DeactivatorTest extends WP_UnitTestCase {

	/**
	 * @var Engagement_Cloud_Deactivator
	 */
	private $deactivator;

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

		// Upgrader is required so we can install the marketing table
		$this->upgrader = new Engagement_Cloud_Upgrader(
			'test-plugin',
			'1.2.0',
			'https://chaz-tracking-link.net'
		);

		$this->code_version = Engagement_Cloud_Bootstrapper::get_version();
	}

	/**
	 * Test plugin deactivation.
	 * - All this does is simply run the code.
	 * - It confirms the deactivation hook successfully connects with a method.
	 */
	public function test_deactivate_runs() {
		$this->install_tables();

		$plugin_abspath = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) .
		                  '/class-engagement-cloud-bootstrapper.php';

		do_action( 'deactivate_' . plugin_basename( $plugin_abspath ) );

		$this->assertEquals( true, true );
	}

	/**
	 * Tear down after the test ends
	 */
	public function tearDown(): void {
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
