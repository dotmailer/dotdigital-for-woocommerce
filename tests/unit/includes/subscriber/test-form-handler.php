<?php

namespace Engagement_Cloud\Tests\Unit\Includes\Subscriber;

use Brain\Monkey\Functions;
use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Form_Handler;
use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Subscriber;
use Engagement_Cloud\Tests\Unit\Inc\PluginTestCase;

class TestEngagementCloudFormHandler extends PluginTestCase
{
    /**
     * @var Engagement_Cloud_Form_Handler
     */
    private $ec_form_handler;

    /**
     * @var Engagement_Cloud_Subscriber|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $ec_subscriber_mock;

    public function setUp() {
        parent::setUp();

        $this->ec_subscriber_mock = \Mockery::mock( Engagement_Cloud_Subscriber::class );
        $this->ec_form_handler = new Engagement_Cloud_Form_Handler($this->ec_subscriber_mock);
    }

    public function test_if_email_is_correct_and_nonce_value_is_correct_that_user_subscribes()
    {
        $_POST = array('nonce' => 'correct_value', 'email' => 'chaz@emailsim.io');

        Functions\expect( 'wp_unslash' )
            ->with( $_POST['nonce'] )
            ->andReturn( $_POST['nonce'] );

        Functions\expect( 'wp_verify_nonce' )
            ->with( $_POST['nonce'] , 'subscribe_to_newsletter')
            ->andReturn( true );

        Functions\expect( 'wp_unslash' )
            ->with( $_POST['email'] )
            ->andReturn( $_POST['email'] );

        Functions\expect( 'sanitize_email' )
            ->with( $_POST['email'] )
            ->andReturn( $_POST['email'] );

        $subscriber_data = array(
            'email'      => $_POST['email'],
            'status'     => 1,
        );

        $this->ec_subscriber_mock
            ->shouldReceive('create_or_update')
            ->with($subscriber_data)
            ->andReturn(Engagement_Cloud_Subscriber::SUBSCRIPTION_SUCCESS);

        Functions\expect( 'wp_send_json' )
            ->with( array( 'success' => 1 ) )
            ->andReturn($this->returnCallback(function () {
                exit;
            }));

        $this->ec_form_handler->subscribe();
    }

    public function test_if_nonce_value_is_invalid_user_not_subscibes()
    {
        $_POST = array('nonce' => 'invalid_value', 'email' => 'chaz@emailsim.io');

        Functions\expect( 'wp_unslash' )
            ->with( $_POST['nonce'] )
            ->andReturn( $_POST['nonce'] );

        Functions\expect( 'wp_verify_nonce' )
            ->with( $_POST['nonce'] , 'subscribe_to_newsletter')
            ->andReturn( false );

        Functions\expect( 'wp_send_json' )
            ->with( array(
                'success' => 0,
                'message' => 'There was an error during your subscription. Please try again',
            ) )
            ->andReturn($this->returnCallback(function () {
                exit;
            }));

        $this->ec_form_handler->subscribe();
    }

    public function test_if_email_is_invalid_user_not_subscribes()
    {
        $_POST = array('nonce' => 'correct_value', 'email' => 'invalid_email');

        Functions\expect( 'wp_unslash' )
            ->with( $_POST['nonce'] )
            ->andReturn( $_POST['nonce'] );

        Functions\expect( 'wp_verify_nonce' )
            ->with( $_POST['nonce'] , 'subscribe_to_newsletter')
            ->andReturn( true );

        Functions\expect( 'wp_unslash' )
            ->with( $_POST['email'] )
            ->andReturn( $_POST['email'] );

        Functions\expect( 'sanitize_email' )
            ->with( $_POST['email'] )
            ->andReturn( false );

        Functions\expect( 'wp_send_json' )
            ->with( array(
                'success' => 0,
                'message' => 'Invalid email address',
            ) )
            ->andReturn($this->returnCallback(function () {
                exit;
            }));

        $this->ec_subscriber_mock->shouldNotReceive('create_or_update');
        $this->ec_form_handler->subscribe();
    }
}