<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

use Yoast\WPTestUtils\WPIntegration\TestCase;

/**
 * TestCase base class for convenience methods.
 */
abstract class WPSEO_News_UnitTestCase extends TestCase {

	/**
	 * Call protected/private method of a class.
	 *
	 * @param object $object      Instantiated object that we will run method on.
	 * @param string $method_name Method name to call.
	 * @param array  $parameters  Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invoke_method( &$object, $method_name, array $parameters = [] ) {
		$reflection = new ReflectionClass( get_class( $object ) );
		$method     = $reflection->getMethod( $method_name );

		$method->setAccessible( true );

		return $method->invokeArgs( $object, $parameters );
	}
}
