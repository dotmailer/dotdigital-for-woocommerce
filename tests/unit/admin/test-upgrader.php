<?php

namespace Engagement_Cloud\Tests\Unit\Admin;

use Engagement_Cloud\Tests\Unit\Inc\PluginTestCase;
use Brain\Monkey;
use Engagement_Cloud\Admin\Engagement_Cloud_Upgrader;

/**
 * Upgrader unit test.
 * This tests the scenario where code_version and stored_version are the same.
 * We can't test if code_version is lower than stored_version with unit tests,
 * because doing so calls methods containing require_once.
 * We'll test that scenario with integration tests.
 */
class UpgraderTest extends PluginTestCase {

	/**
	 * @var Engagement_Cloud_Upgrader
	 */
	private $upgrader;

	/**
	 * @var string
	 */
	private $code_version;

	/**
	 * Test upgrade check if versions match.
	 */
	public function test_upgrade_check_if_versions_match() {
		$code_version = '1.2.0';
		$stored_version = $code_version;

		$upgrader = new Engagement_Cloud_Upgrader(
			'test-plugin',
			$code_version,
			'https://chaz-tracking-link.net'
		);

		Monkey\Functions\expect( 'get_option' )
			->once()
			->with( 'engagement_cloud_for_woocommerce_version' )
			->andReturn( $stored_version );

		global $wpdb;
		$wpdb = \Mockery::mock( 'wpdb' );
		$wpdb->prefix = 'chaz_';
		$wpdb->shouldReceive( array('prepare', 'get_var', 'get_charset_collate') )
			->never();

		Monkey\Functions\expect( 'wp_remote_post' )
			->never();

		$upgrader->upgrade_check();
	}
}
