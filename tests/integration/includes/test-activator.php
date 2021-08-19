<?php
/**
 * Class ActivatorTest
 *
 * @package Dotdigital_WooCommerce
 */

namespace Dotdigital_WooCommerce\Tests\Integration\Includes;

use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Activator;
use Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper;
use WP_UnitTestCase;

/**
 * Activator test.
 */
class ActivatorTest extends WP_UnitTestCase {

	/**
	 * @var Dotdigital_WooCommerce_Activator
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

		$this->code_version = Dotdigital_WooCommerce_Bootstrapper::get_version();
	}

	/**
	 * Test plugin activation.
	 *
	 * Notes:
	 * - This assumes the version option is not yet stored.
	 * - register_action_hook is called as normal from Dotdigital_WooCommerce_Bootstrapper.
	 */
	public function test_activate_runs_upgrade_check() {
		$plugin_abspath = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) .
		                   '/class-dotdigital-woocommerce-bootstrapper.php';

		do_action( 'activate_' . plugin_basename( $plugin_abspath ) );

		$this->assertEquals(get_option( 'dotdigital_for_woocommerce_version' ), $this->code_version);
	}

	/**
	 * Tear down after the test ends
	 */
	public function tearDown(): void {
		parent::tearDown();
	}
}
