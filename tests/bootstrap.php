<?php

// disable xdebug backtrace
if ( function_exists( 'xdebug_disable' ) ) {
	xdebug_disable();
}

echo 'Welcome to the Wordpress SEO News test suite' . PHP_EOL;
echo 'Version: 1.0' . PHP_EOL . PHP_EOL;

$GLOBALS['wp_tests_options'] = array(
	'active_plugins' => array( 'wordpress-seo/wp-seo.php', 'wpseo-news/wpseo-news.php' ),
);

if( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
	require getenv( 'WP_DEVELOP_DIR' ) . 'tests/phpunit/includes/bootstrap.php';
} else {
	require '../../../../tests/phpunit/includes/bootstrap.php';
}

// include unit test base class
require_once dirname( __FILE__ ) . '/framework/class-wpseo-news-unit-test-case.php';