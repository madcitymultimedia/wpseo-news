<?php
/**
 * PHPUnit bootstrap file
 *
 * @package WPSEO/WooCommerce/Tests
 */

define( 'WPSEO_INDEXABLES', true );
define( 'WPSEO_NEWS_VERSION', '12.6' );
define( 'WPSEO_VERSION', '17.6' );
define( 'YOAST_SEO_NEWS_WP_REQUIRED', '6.1' );

if ( ! defined( 'WPSEO_PATH' ) ) {
	define( 'WPSEO_PATH', dirname( dirname( __DIR__ ) ) . '/' );
}

if ( ! defined( 'WPSEO_FILE' ) ) {
	define( 'WPSEO_FILE', WPSEO_PATH . 'wp-seo.php' );
}

if ( file_exists( dirname( __DIR__ ) . '/vendor/autoload.php' ) === false ) {
	echo PHP_EOL, 'ERROR: Run `composer install` to generate the autoload files before running the unit tests.', PHP_EOL;
	exit( 1 );
}

require_once __DIR__ . '/../vendor/yoast/wp-test-utils/src/BrainMonkey/bootstrap.php';
require_once __DIR__ . '/../vendor/autoload.php';
