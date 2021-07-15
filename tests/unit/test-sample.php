<?php

namespace EngagementCloud\Test;

use EngagementCloud\Engagement_Cloud_Bootstrapper;
use \Engagement_Cloud_Upgrader;
use \Brain\Monkey\Functions;

class SomeClassTest extends \PluginTestCase {

	public function test_upgrade_from_no_stored_version() {
		global $wpdb;
		$upgrader = new Engagement_Cloud_Upgrader(
			'test-plugin',
			'1.0.0',
			'https://chaz-tracking-link.net'
		);

		Functions\expect( 'get_option' )
			->once()
			->with( 'engagement_cloud_for_woocommerce_version' );

		Functions\expect( 'current_user_can' )
			->once()
			->with( 'update_plugins' )
			->andReturn( true );

		//$bootstrapper = \Mockery::mock('Engagement_Cloud_Bootstrapper', 'Engagement_Cloud_Bootstrapper_Stub');
		\Mockery::getConfiguration()->setConstantsMap([
			'Engagement_Cloud_Bootstrapper' => [
				'EMAIL_MARKETING_TABLE_NAME' => 'email_table',
				'SUBSCRIBERS_TABLE_NAME' => 'subscriber_table',
			]
		]);
		$bootstrap_mock = \Mockery::mock('Engagement_Cloud_Bootstrapper');
		var_dump($bootstrap_mock::SUBSCRIBERS_TABLE_NAME);

		$wpdb         = \Mockery::mock( 'wpdb' );
		$wpdb->prefix = 'chaz_';
		$wpdb->shouldReceive( 'prepare' )
			->once();
		$wpdb->shouldReceive( 'get_var' )
		     ->once();
		$wpdb->shouldReceive( 'get_charset_collate' )
		     ->once();

		$upgrader->upgrade_check();

		// arrange
//		$_SERVER['REQUEST_METHOD'] = 'POST';
//		$_POST = [ 'foo' => '\\\'asas' ];
//		// We expect wp_unslash to be called during bootstrap
//		Functions\expect( 'wp_unslash' )
//			->once()
//			->with( $_POST )
//			->andReturnFirstArg();
//		// We expect plugins_url to be called
//		Functions\expect( 'plugins_url' )
//			->once()
//			->with( '/dist/', EC_WOO_ABSPATH )
//			->andReturn( 'https://eform.test/foo/dist/' );
//		// Fire
//		$stub = $this->getMockForAbstractClass( Engagement_Cloud_Upgrader::class );
//		$stub_class = get_class( $stub );
//		// $base = new \EFormStub\StubAdminBase();
//		// We expect admin_menu action to have been added when calling register
//		$this->assertTrue( has_action( 'admin_menu', "{$stub_class}->admin_menu()" ) );
//		// Assert
//		$this->assertEquals( $_POST, $stub->get_post() );
	}
}

class Engagement_Cloud_Bootstrapper_Stub
{
	const EMAIL_MARKETING_TABLE_NAME = 'email_table';
	const SUBSCRIBERS_TABLE_NAME = 'subscriber_table';
}
