<?php

namespace Dotdigital_WooCommerce\Tests\Unit\Pub;

use Brain\Monkey\Functions;
use Dotdigital_WooCommerce\Includes\Dotdigital_WooCommerce_Config;
use Dotdigital_WooCommerce\Tests\Unit\Inc\PluginTestCase;
use Dotdigital_WooCommerce\Pub\Dotdigital_WooCommerce_Public;

class Dotdigital_WooCommerce_Public_Test extends PluginTestCase
{
    /**
     * @var string
     */
    private $plugin_dir;

    /**
     * @var Dotdigital_WooCommerce_Public
     */
    private $public_class;

    public function setUp(): void
    {
        Functions\expect( 'get_option' )
            ->once()
            ->with( 'dotdigital_for_woocommerce_settings_region', Dotdigital_WooCommerce_Config::DEFAULT_REGION  )
            ->andReturn( Dotdigital_WooCommerce_Config::DEFAULT_REGION );

        Functions\expect( 'plugin_dir_url' )
            ->with( '__FILE__'  )
            ->andReturn( $this->plugin_dir = 'plugin_dir' );

        Functions\expect( 'get_option' )
            ->once()
            ->with( 'dotdigital_for_woocommerce_settings_enable_site_and_roi_tracking', Dotdigital_WooCommerce_Config::DEFAULT_SITE_AND_ROI_TRACKING_ENABLED  )
            ->andReturn( true );

        $this->public_class = new Dotdigital_WooCommerce_Public('Plugin_name', null);

        parent::setUp();
    }

    public function test_that_tracking_script_is_loaded()
    {
	    $src = sprintf( '//r%s-t.trackedlink.net/_dmpt.js', Dotdigital_WooCommerce_Config::DEFAULT_REGION );

        Functions\expect( 'is_checkout' )
            ->andReturn( false );

        Functions\expect( 'is_wc_endpoint_url' )
            ->andReturn( false );

	    Functions\expect( 'wp_enqueue_script' )
		    ->once()
		    ->with( 'tracking', $src , array(), null , true );

        $this->public_class->add_tracking_and_roi_script();
    }

    public function test_that_roi_script_is_loaded_when_in_order_complete_page()
    {
	    $src = sprintf( '//r%s-t.trackedlink.net/_dmpt.js', Dotdigital_WooCommerce_Config::DEFAULT_REGION );
        $_GET["key"] = "wc_order_key";

	    Functions\expect( 'wp_enqueue_script' )
		    ->once()
		    ->with( 'tracking', $src , array(), null , true );

        Functions\expect( 'is_checkout' )
            ->andReturn( true );

        Functions\expect( 'is_wc_endpoint_url' )
            ->andReturn( true );

        Functions\expect( 'wp_enqueue_script' )
            ->with( 'roi_tracking_js', $this->plugin_dir . 'js/roi-tracking.js', array(), null , true );

        Functions\expect( 'get_query_var' )
            ->with('order-received')
            ->andReturn( $order_id = 1 );

        Functions\expect( 'absint' )
            ->with($order_id)
            ->andReturn( $order_id  );

        $roi_mockery_class =  \Mockery::mock(  'overload:Dotdigital_WooCommerce\Includes\Tracking\Dotdigital_WooCommerce_Roi' );

        $roi_mockery_class->shouldReceive('get_order_data')
            ->with($order_id)
            ->andReturn($order_data = [
                'line_items' => array('Chaz Hoodie'),
                'total' => 15
            ]);

        Functions\expect( 'wp_localize_script' )
            ->with( 'roi_tracking_js', 'order_data', $order_data );

        $this->public_class->add_tracking_and_roi_script();
    }

	public function test_scripts_are_enqueued()
	{
		$woocommerce_product = \Mockery::mock( \WC_Product::class );

		Functions\expect( 'wc_get_product' )
			->once()
			->andReturn( $woocommerce_product );

		Functions\expect( 'wp_enqueue_script' )
			->once()
			->with( 'dotdigital_woocommerce_public_js', $this->plugin_dir . 'js/dotdigital-woocommerce-public.js', array( 'jquery' ), null , true );

		Functions\expect( 'admin_url' )
			->with( 'admin_url'  )
			->andReturn( $admin_url = 'admin_url' );

		Functions\expect( 'wp_create_nonce' )
			->with( 'subscribe_to_newsletter'  )
			->andReturn( $nonce_value = 'nonce_value' );

		$props = array(
			'ajax_url' => $admin_url,
			'nonce'    => $nonce_value,
		);

		Functions\expect( 'wp_localize_script' )
			->with( 'dotdigital_woocommerce_public_js', 'dd_ajax_handler', $props );

		Functions\expect( 'is_checkout' )
			->andReturn( false );

		Functions\expect( 'is_account_page' )
			->andReturn( true );

		Functions\expect( 'is_product' )
			->andReturn( false );

		$this->public_class->enqueue_scripts();
	}

}
