<?php

namespace Dotdigital_WooCommerce\Tests\Unit\Admin\Platforms;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Dotdigital_WooCommerce\Tests\Unit\Inc\PluginTestCase;
use Dotdigital_WooCommerce\Includes\Platforms\Dotdigital_WooCommerce;

class Dotdigital_WooCommerce_Test extends PluginTestCase {

    public function setUp(): void {
        parent::setUp();

        Monkey\Functions\when( 'get_option' )
            ->justReturn( 1 );
    }

	public function test_dotdigital_handle_checkout_subscription_that_updates_the_record_if_subscriber_match() {
		$dd_woocommerce_class = new Dotdigital_WooCommerce();

		global $wpdb;
		$wpdb         = \Mockery::mock( 'wpdb' );
		$wpdb->prefix = 'chaz_';

		$order_id = 1;
		$_POST    = array( 'dotdigital_woocommerce_checkbox' => true );

		$wc_order_mock = \Mockery::mock( 'WC_Order' );

		Functions\expect( 'wc_get_order' )
			->once()
			->with( $order_id )
			->andReturn( $wc_order_mock );

		$wc_order_mock->shouldReceive( 'get_customer_id' )->andReturn( '1' );
		$wc_order_mock->shouldReceive( 'get_billing_email' )->andReturn( 'chaz@emailsim.io' );
		$wc_order_mock->shouldReceive( 'get_billing_first_name' )->andReturn( 'Chaz' );
		$wc_order_mock->shouldReceive( 'get_billing_last_name' )->andReturn( 'Kangaroo' );

		Functions\expect( 'current_time' )
			->with( 'mysql' )
			->andReturn( '2021-07-05 10:42:24' );

		$wpdb->shouldReceive( 'prepare' )
			->andReturn( $sql = 'SQL row' );

		$wpdb->shouldReceive( 'get_row' )
			->with( $sql )
			->andReturn( true );

		$wpdb->shouldReceive( 'update' )->once();
		$wpdb->shouldNotReceive( 'insert' );

		$dd_woocommerce_class->dotdigital_woocommerce_handle_checkout_subscription( $order_id );
	}

	public function test_dotdigital_handle_checkout_subscription_that_inserts_new_record_if_subscriber_not_match() {
		$dd_woocommerce_class = new Dotdigital_WooCommerce();

		global $wpdb;
		$wpdb         = \Mockery::mock( 'wpdb' );
		$wpdb->prefix = 'chaz_';

		$order_id = 1;
		$_POST    = array( 'dotdigital_woocommerce_checkbox' => true );

		$wc_order_mock = \Mockery::mock( 'WC_Order' );

		Functions\expect( 'wc_get_order' )
			->once()
			->with( $order_id )
			->andReturn( $wc_order_mock );

		$wc_order_mock->shouldReceive( 'get_customer_id' )->andReturn( '1' );
		$wc_order_mock->shouldReceive( 'get_billing_email' )->andReturn( 'chaz@emailsim.io' );
		$wc_order_mock->shouldReceive( 'get_billing_first_name' )->andReturn( 'Chaz' );
		$wc_order_mock->shouldReceive( 'get_billing_last_name' )->andReturn( 'Kangaroo' );

		Functions\expect( 'current_time' )
			->with( 'mysql' )
			->andReturn( '2021-07-05 10:42:24' );

		$wpdb->shouldReceive( 'prepare' )
			->andReturn( $sql = 'SQL row' );

		$wpdb->shouldReceive( 'get_row' )
			->with( $sql )
			->andReturn( false );

		$wpdb->shouldNotReceive( 'update' );
		$wpdb->shouldReceive( 'insert' )->once();

		$dd_woocommerce_class->dotdigital_woocommerce_handle_checkout_subscription( $order_id );
	}
}
