<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * TestCase base class for convenience methods.
 */
class WPSEO_News_UnitTestCase extends WP_UnitTestCase {

	/**
	 * Set up an HTTP post request.
	 *
	 * @param string $key   Array key.
	 * @param mixed  $value Value.
	 */
	protected function set_post( $key, $value ) {
		$_REQUEST[ $key ] = addslashes( $value );
		$_POST[ $key ]    = $_REQUEST[ $key ];
	}

	/**
	 * Unset an HTTP post request.
	 *
	 * @param string $key Array key.
	 */
	protected function unset_post( $key ) {
		unset( $_POST[ $key ], $_REQUEST[ $key ] );
	}

	/**
	 * Fake a request to the WP front page.
	 */
	protected function go_to_home() {
		$this->go_to( home_url( '/' ) );
	}

	/**
	 * Test expected output.
	 *
	 * @param string $string Expected output string.
	 */
	protected function expectOutput( $string ) {
		$output = ob_get_contents();
		ob_clean();
		$this->assertSame( $output, $string );
	}

	/**
	 * Call protected/private method of a class.
	 *
	 * @param object $object      Instantiated object that we will run method on.
	 * @param string $method_name Method name to call.
	 * @param array  $parameters  Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invoke_method( &$object, $method_name, array $parameters = array() ) {
		$reflection = new ReflectionClass( get_class( $object ) );
		$method     = $reflection->getMethod( $method_name );

		$method->setAccessible( true ); // PHP 5.3.2.

		return $method->invokeArgs( $object, $parameters );
	}
}
