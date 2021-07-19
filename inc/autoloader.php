<?php
/**
 * The autoloader file.
 *
 * This file is used to autoload all classes.
 *
 * @since      1.2.0
 * @package    EngagementCloud
 * @subpackage EngagementCloud
 * @author     dotdigital <integrations@dotdigital.com>
 */

/**
 * Autoload require
 */
require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(
	function( $class ) {
		if ( false !== strpos( $class, 'Engagement_Cloud' ) ) {
			if ( defined( 'PLUGIN_DIR_PATH' ) ) {
				$root = PLUGIN_DIR_PATH . '/'; // parent directory.
			} else {
				$root = __DIR__ . '/../';
			}

			$split = explode( '\\', $class );
			if ( 'Engagement_Cloud_Bootstrapper' !== $split[0] ) {
				unset( $split[0] );
				foreach ( $split as $key => $item ) {
					if ( false === strpos( $item, 'Engagement_Cloud' ) ) {
						if ( 'Pub' === $item ) {
							$split[ $key ] = 'public';
						} elseif ( 'RestApi' === $item ) {
							$split[ $key ] = 'rest-api';
						} else {
							$split[ $key ] = strtolower( $item );
						}
					} else {
						$split[ $key ] = 'class-' . str_replace( '_', '-', strtolower( $item ) ) . '.php';
					}
				}

				$file = implode( '/', $split );
			} else {
				$file = 'class-' . str_replace( '_', '-', strtolower( $split[0] ) ) . '.php';
			}

			if ( is_readable( $root . $file ) ) {
				require_once $root . $file;
			}
		}
	}
);
