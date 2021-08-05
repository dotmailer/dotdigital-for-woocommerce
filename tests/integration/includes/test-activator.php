<?php
/**
 * Class ActivatorTest
 *
 * @package Engagement_Cloud_For_Woocommerce
 */

namespace Engagement_Cloud\Tests\Integration\Includes;

use Engagement_Cloud\Includes\Engagement_Cloud_Activator;
use Engagement_Cloud\Engagement_Cloud_Bootstrapper;
use WP_UnitTestCase;

/**
 * Activator test.
 */
class ActivatorTest extends WP_UnitTestCase {

	/**
	 * @var Engagement_Cloud_Activator
	 */
	private $activator;

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
	}

	/**
	 * Test plugin activation.
	 *
	 * Notes:
	 * - This assumes the version option is not yet stored.
	 * - register_action_hook is called as normal from Engagement_Cloud_Bootstrapper.
	 */
	public function test_activate_runs_upgrade_check() {
		$plugin_abspath = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) .
		                   '/class-engagement-cloud-bootstrapper.php';

		do_action( 'activate_' . plugin_basename( $plugin_abspath ) );

		$this->assertEquals(get_option( 'engagement_cloud_for_woocommerce_version' ), $this->code_version);
	}

	/**
	 * Tear down after the test ends
	 */
	public function tearDown(): void {
		parent::tearDown();
	}
}
