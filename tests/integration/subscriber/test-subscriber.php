<?php
/**
 * Class SubscriberTest
 *
 * @package Engagement_Cloud_For_Woocommerce
 */

namespace Engagement_Cloud\Tests\Integration\Subscriber;

use Engagement_Cloud\Tests\Integration\Subscriber\Engagement_Cloud_UnitTest_Factory;
use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Subscriber;
use Engagement_Cloud\Admin\Engagement_Cloud_Upgrader;

/**
 * Migrator test.
 */
class SubscriberTest extends \WP_UnitTestCase {

	/**
	 * @var Engagement_Cloud_Subscriber
	 */
	private $subscriber;

    /**
     * @var Engagement_Cloud_Upgrader
     */
    private $upgrader;

	/**
	 *
	 */
	public function setUp(): void {
		parent::setUp();

        $this->factory = new Engagement_Cloud_UnitTest_Factory;
        $this->subscriber = new Engagement_Cloud_Subscriber();

        // Upgrader is required so we can install the subscriber table
        $this->upgrader = new Engagement_Cloud_Upgrader(
            'test-plugin',
            '1.2.0',
            'https://chaz-tracking-link.net'
        );

        $this->install_tables();
	}

    /**
     *
     */
    public function test_email_is_subscribed() {
	    $this->factory->subscriber->create_many(5);

        $subscriber = $this->factory->subscriber->get_object_by_id( 1 );

        $this->assertTrue(
            $this->subscriber->is_subscribed( $subscriber->email )
        );
    }

    /**
     *
     */
    public function test_user_id_is_subscribed() {
        $subscriber = $this->factory->subscriber->create_and_get();

        $this->assertTrue(
            $this->subscriber->is_user_id_subscribed( $subscriber->user_id )
        );
    }

    /**
     *
     */
    public function test_subscriber_update() {
        $id = $this->factory->subscriber->create();
        $this->factory->subscriber->update_object( $id, [
            'email' => 'subscriber@emailsim.io'
        ]);
        $subscriber = $this->factory->subscriber->get_object_by_id( $id );

        $this->assertEquals( 'subscriber@emailsim.io', $subscriber->email );
    }

    /**
     *
     */
    public function test_unsubscribe() {
        $subscriber = $this->factory->subscriber->create_and_get();
        $this->subscriber->unsubscribe( $subscriber->email );

        $this->assertFalse(
            $this->subscriber->is_subscribed( $subscriber->email )
        );
    }

	/**
	 * Tear down after the test ends
	 */
	public function tearDown(): void
	{
		parent::tearDown();
	}

    /**
     * Install required tables
     */
    private function install_tables() {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $this->upgrader->create_subscriber_table();
    }
}
