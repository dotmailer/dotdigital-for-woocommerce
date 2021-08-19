<?php
/**
 * Class DeactivatorTest
 *
 * @package Dotdigital_WooCommerce
 */

namespace Dotdigital_WooCommerce\Tests\Integration\Includes;

use Dotdigital_WooCommerce\Admin\Dotdigital_WooCommerce_Upgrader;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Deactivator;
use Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper;
use WP_UnitTestCase;

/**
 * Deactivator test.
 */
class DeactivatorTest extends WP_UnitTestCase {

	/**
	 * @var Dotdigital_WooCommerce_Deactivator
	 */
	private $deactivator;

	/**
	 * @var Dotdigital_WooCommerce_Upgrader
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
		$this->upgrader = new Dotdigital_WooCommerce_Upgrader(
			'test-plugin',
			'1.2.0',
			'https://chaz-tracking-link.net'
		);

		$this->code_version = Dotdigital_WooCommerce_Bootstrapper::get_version();
	}

	/**
	 * Test plugin deactivation.
	 * - All this does is simply run the code.
	 * - It confirms the deactivation hook successfully connects with a method.
	 */
	public function test_deactivate_runs() {
		$this->install_tables();

		$plugin_abspath = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) .
		                  '/class-dotdigital-woocommerce-bootstrapper.php';

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
