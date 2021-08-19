<?php

namespace Dotdigital_WooCommerce\Tests\Unit\Includes\Subscriber;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Dotdigital_WooCommerce\Tests\Unit\Inc\PluginTestCase;
use Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Subscriber;
use Dotdigital_WooCommerce\Dotdigital_WooCommerce_Bootstrapper;

class Dotdigital_WooCommerce_Subscriber_Test extends PluginTestCase {

    public function setUp() {
        parent::setUp();

        Monkey\Functions\when( 'get_option' )
            ->justReturn( 1 );
    }

	public function test_that_a_subscriber_unsubscribes() {
		global $wpdb;
		$subscriber_class = new Dotdigital_WooCommerce_Subscriber();

		$email        = 'chaz@emailsim.io';
		$table        = Dotdigital_WooCommerce_Bootstrapper::SUBSCRIBERS_TABLE_NAME;
		$wpdb         = \Mockery::mock( 'wpdb' );
		$wpdb->prefix = 'chaz_';
		$wpdb->shouldReceive( 'update' )
			->once()
			->with( $wpdb->prefix . $table, array( 'status' => 0 ), array( 'email' => $email ) );

		$subscriber_class->unsubscribe( $email );
	}
}
