<?php

declare( strict_types=1 );

use Isolated\Symfony\Component\Finder\Finder;

/**
 * To modify consult the PHP-Scoper template:
 * https://github.com/humbug/php-scoper/blob/main/src/scoper.inc.php.tpl
 */
return [
	'finders' => [
		Finder::create()
			->files()
			->ignoreVCS( true )
			->notName( '/.*\\.md|.*\\.dist|.*\\.json|.*\\.lock|scoper\\.inc\\.php|.*\\.phar/' )
			->exclude( [
				'bin',
				'docs',
				'node_modules',
				'cypress',
				'tests'
			] )
			->in( __DIR__ ),
		Finder::create()->append( [
			'composer.json',
		] ),
	],

	'patchers' => [],

	'exclude-namespaces' => [
		'Dotdigital_WooCommerce',
		'Automattic'
	],
	'exclude-classes'    => [
		'WP_Widget'
	],
];
