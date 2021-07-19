<?php

namespace Engagement_Cloud\Tests\Unit;

use Brain\Monkey;
use Engagement_Cloud\Tests\Unit\Inc\PluginTestCase;
use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Subscriber;
use Engagement_Cloud\Engagement_Cloud_Bootstrapper;

class Engagement_Cloud_Subscriber_Test extends PluginTestCase {

	public function setUp() {
		parent::setUp();

		Monkey\Functions\when( 'get_option' )
			->justReturn( 1 );
	}
	public function test_that_a_subscriber_unsubscribes() {
		global $wpdb;
		$subscriber_class = new Engagement_Cloud_Subscriber();

		$email        = 'chaz@emailsim.io';
		$table        = Engagement_Cloud_Bootstrapper::SUBSCRIBERS_TABLE_NAME;
		$wpdb         = \Mockery::mock( 'wpdb' );
		$wpdb->prefix = 'chaz_';
		$wpdb->shouldReceive( 'update' )
			->once()
			->with( $wpdb->prefix . $table, array( 'status' => 0 ), array( 'email' => $email ) );

		$subscriber_class->unsubscribe( $email );
	}
}
