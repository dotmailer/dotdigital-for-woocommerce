<?php
namespace Dotdigital_WooCommerce\Tests\Unit\Inc;

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;

/**
 * An abstraction over WP_Mock to do things fast
 * It also uses the snapshot trait
 */
class PluginTestCase extends TestCase {
	use MockeryPHPUnitIntegration;

	/**
	 * Setup which calls \WP_Mock setup
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$_POST = array();
		$_GET = array();
		Monkey\setUp();

		Monkey\Functions\when( '__' )
			->returnArg( 1 );
		Monkey\Functions\when( '_e' )
			->returnArg( 1 );
		Monkey\Functions\when( '_n' )
			->returnArg( 1 );
		Monkey\Functions\when( 'plugin_dir_path' )
			->returnArg( 1 );
		Monkey\Functions\when( 'register_activation_hook' )
			->returnArg( 1 );
		Monkey\Functions\when( 'register_deactivation_hook' )
			->returnArg( 1 );
		Monkey\Functions\when( 'plugin_basename' )
			->returnArg( 1 );
		Monkey\Functions\when( 'update_option' )
			->returnArg( 1 );
		Monkey\Functions\when( 'get_site_option' )
			->returnArg( 1 );
		Monkey\Functions\when( 'apply_filters' )
			->justReturn( array() );
		Monkey\Functions\when( 'is_multisite' )
			->justReturn( 1 );
        Monkey\Functions\when( 'is_admin' )
            ->justReturn( 0 );
    }

	/**
	 * Teardown which calls \WP_Mock tearDown
	 *
	 * @return void
	 */
	public function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}
}
