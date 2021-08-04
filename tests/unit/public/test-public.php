<?php

namespace Engagement_Cloud\Tests\Unit\Pub;

use Brain\Monkey\Functions;
use Engagement_Cloud\Engagement_Cloud_Bootstrapper;
use Engagement_Cloud\Tests\Unit\Inc\PluginTestCase;
use Engagement_Cloud\Pub\Engagement_Cloud_Public;

class Engagement_Cloud_Public_Test extends PluginTestCase
{
    /**
     * @var string
     */
    private $plugin_dir;

    /**
     * @var Engagement_Cloud_Public
     */
    private $public_class;

    public function setUp()
    {
        $src = sprintf( '//r%s-t.trackedlink.net/_dmpt.js', Engagement_Cloud_Bootstrapper::DEFAULT_REGION );

        Functions\expect( 'get_option' )
            ->once()
            ->with( 'engagement_cloud_for_woocommerce_select_region', Engagement_Cloud_Bootstrapper::DEFAULT_REGION  )
            ->andReturn( Engagement_Cloud_Bootstrapper::DEFAULT_REGION );

        Functions\expect( 'plugin_dir_url' )
            ->with( '__FILE__'  )
            ->andReturn( $this->plugin_dir = 'plugin_dir' );

        Functions\expect( 'wp_enqueue_script' )
            ->once()
            ->with( 'engagement_cloud_public_js', $this->plugin_dir . 'js/engagement-cloud-public.js', array( 'jquery' ), null , true );

        Functions\expect( 'wp_enqueue_script' )
            ->once()
            ->with( 'tracking', $src , array(), null , true );

        Functions\expect( 'admin_url' )
            ->with( 'admin_url'  )
            ->andReturn( $admin_url = 'admin_url' );

        Functions\expect( 'wp_create_nonce' )
            ->with( 'subscribe_to_newsletter'  )
            ->andReturn( $nonce_value = 'nonce_value' );

        Functions\expect( 'get_option' )
            ->once()
            ->with( 'engagement_cloud_for_woocommerce_settings_enable_site_and_roi_tracking', Engagement_Cloud_Bootstrapper::DEFAULT_SITE_AND_ROI_TRACKING_ENABLED  )
            ->andReturn( true );

        $translation_array = array(
            'ajax_url' => $admin_url,
            'nonce'    => $nonce_value,
        );

        Functions\expect( 'wp_localize_script' )
            ->with( 'engagement_cloud_public_js', 'cpm_object', $translation_array  );

        $this->public_class = new Engagement_Cloud_Public('Plugin_name', null);


        parent::setUp();
    }

    public function test_that_tracking_script_is_loaded()
    {
        Functions\expect( 'is_checkout' )
            ->andReturn( false );

        Functions\expect( 'is_wc_endpoint_url' )
            ->andReturn( false );


        $this->public_class->enqueue_scripts();
    }

    public function test_that_roi_script_is_loaded_when_in_order_complete_page()
    {
        $_GET["key"] = "wc_order_key";

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

        $roi_mockery_class =  \Mockery::mock(  'overload:Engagement_Cloud\Includes\Tracking\Engagement_Cloud_Roi' );

        $roi_mockery_class->shouldReceive('get_order_data')
            ->with($order_id)
            ->andReturn($order_data = [
                'line_items' => array('Chaz Hoodie'),
                'total' => 15
            ]);

        Functions\expect( 'wp_localize_script' )
            ->with( 'roi_tracking_js', 'order_data', $order_data );

        $this->public_class->enqueue_scripts();
    }
}