<?php

namespace Dotdigital_WooCommerce\Tests\Unit\Includes\Subscriber;

use Brain\Monkey\Functions;
use Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Form_Handler;
use Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Subscriber;
use Dotdigital_WooCommerce\Tests\Unit\Inc\PluginTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TestDotdigitalFormHandler extends PluginTestCase
{
    /**
     * @var Dotdigital_WooCommerce_Form_Handler
     */
    private $dd_form_handler;

    /**
     * @var Dotdigital_WooCommerce_Subscriber|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $dd_subscriber_mock;

    public function setUp(): void {
        parent::setUp();

        $this->dd_subscriber_mock = \Mockery::mock(
        	'overload:Dotdigital_WooCommerce\Includes\Subscriber\Dotdigital_WooCommerce_Subscriber'
        );
        $this->dd_form_handler = new Dotdigital_WooCommerce_Form_Handler();
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

	    $this->dd_subscriber_mock
		    ->shouldReceive('create_or_update')
		    ->with($subscriber_data)
		    ->andReturn(1);

	    Functions\expect( 'wp_send_json' )
            ->with( array( 'success' => 1 ) )
            ->andReturn($this->returnCallback(function () {
                exit;
            }));

        $this->dd_form_handler->execute();
	    /**
	     * Dummy assertion. For some reason without this PHPUnit thinks this test is risky.
	     */
	    $this->assertTrue(true);
    }

    public function test_if_nonce_value_is_invalid_user_not_subscribes()
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

        $this->dd_form_handler->execute();
	    /**
	     * Dummy assertion. For some reason without this PHPUnit thinks this test is risky.
	     */
	    $this->assertTrue(true);
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

        $this->dd_subscriber_mock->shouldNotReceive('create_or_update');
        $this->dd_form_handler->execute();
    }
}
