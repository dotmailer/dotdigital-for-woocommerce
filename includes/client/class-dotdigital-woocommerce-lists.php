<?php
/**
 * Implementation of address book requests.
 *
 * @link       https://developer.dotdigital.com/reference/get-address-books
 * @since      1.4.0
 *
 * @package    Dotdigital_WooCommerce
 * @subpackage Dotdigital_WooCommerce/Includes/Client
 */

namespace Dotdigital_WooCommerce\Includes\Client;

use Dotdigital\V2\Resources\AddressBooks;

/**
 * Class Dotdigital_WooCommerce_Lists
 */
class Dotdigital_WooCommerce_Lists {

	/**
	 * Dotdigital client.
	 *
	 * @var Dotdigital_WooCommerce_Client $dotdigital_client
	 * @access private.
	 */
	private $dotdigital_client;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->dotdigital_client = new Dotdigital_WooCommerce_Client();
	}

	/**
	 * Get lists.
	 *
	 * @return array
	 * @throws \Http\Client\Exception If request fails.
	 */
	public function get() {
		$formatted_lists = get_transient( 'dotdigital_woocommerce_api_lists' );
		if ( ! $formatted_lists ) {
			$formatted_lists = array();
			$formatted_lists[0] = __( '--Please Select--', 'dotdigital-for-woocommerce' );
			try {
				do {
					$lists = $this->dotdigital_client->get_client()->addressBooks->show( count( $formatted_lists ) - 1 );
					foreach ( $lists->getList() as $list ) {
						$formatted_lists[ $list->getId() ] = $list->getName();
					}
					$count_fetched_lists = count( $lists->getList() );
				} while ( AddressBooks::SELECT_LIMIT === $count_fetched_lists );
			} catch ( \Exception $exception ) {
				$formatted_lists[0] = $exception->getMessage();
				return $formatted_lists;
			}

			set_transient( 'dotdigital_woocommerce_api_lists', $formatted_lists, 600 );
		}
		return $formatted_lists;
	}
}
