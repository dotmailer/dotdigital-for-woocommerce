<?php

namespace Engagement_Cloud\Tests\Integration\Subscriber;

use Engagement_Cloud\Includes\Subscriber\Engagement_Cloud_Subscriber;

class Engagement_Cloud_UnitTest_Factory extends \WP_UnitTest_Factory {

    /**
     * @var WP_UnitTest_Factory_For_Subscriber
     */
    public $subscriber;

    function __construct() {
        parent::__construct();

        $this->subscriber = new Engagement_Cloud_UnitTest_Factory_For_Subscriber( $this );
    }
}

class Engagement_Cloud_UnitTest_Factory_For_Subscriber extends \WP_UnitTest_Factory_For_Thing {

	public function __construct( $factory = null ) {
	    parent::__construct( $factory );
        $this->default_generation_definitions = array(
            'user_id' => new \WP_UnitTest_Generator_Sequence( '%s' ),
            'email' => new \WP_UnitTest_Generator_Sequence( 'chaz%s@emailsim.io' ),
            'first_name' => 'Chaz',
            'last_name' => 'Kangaroo',
            'status' => 1,
        );

        $this->subscriber = new Engagement_Cloud_Subscriber();
	}

    public function create_object( $args ) {
	    return $this->subscriber->create( $args );
	}

    public function update_object( $id, $fields ) {
        return $this->subscriber->update_by_id( $id, $fields );
	}

    public function get_object_by_id( $id ) {
	    return $this->subscriber->get_by_id( $id );
    }
}
