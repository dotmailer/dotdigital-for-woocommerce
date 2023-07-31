<?php
/**
 * Implementation of contact resource requests.
 *
 * @link       https://www.dotdigital.com/
 * @since      1.4.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/includes/client
 */

namespace Dotdigital_WooCommerce\Includes\Client;

use Dotdigital\Exception\ResponseValidationException;
use Dotdigital\V3\Client;
use Dotdigital\V3\Models\Contact;
use Dotdigital\V3\Models\ContactCollection;

/**
 * Class Dotdigital_WooCommerce_Contact
 */
class Dotdigital_WooCommerce_Contact {
	/**
	 * The client.
	 *
	 * @var Client $client
	 * @access private
	 */
	private $dotdigital_client;

	/**
	 * Dotdigital_WooCommerce_Contact constructor.
	 */
	public function __construct() {
		$this->dotdigital_client = new Dotdigital_WooCommerce_Client( Client::class );
	}

	/**
	 * Create or update the contact in Dotdigital
	 *
	 * We use the import method to create or update the contact in Dotdigital as
	 * the create method will throw an error if the contact already exists.
	 *
	 * @param Contact $contact The contact to create or update.
	 * @return void
	 * @throws \Http\Client\Exception If the API call fails.
	 */
	public function create_or_update( Contact $contact ) {
		try {
			$collection = new ContactCollection();
			$collection->add( $contact );
			$this->dotdigital_client
				->get_client()
				->contacts
				->import( $collection );
		} catch ( ResponseValidationException $exception ) {
			error_log( $exception->getMessage() );
		}
	}
}
