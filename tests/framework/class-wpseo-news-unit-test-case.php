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
		$_POST[ $key ] = $_REQUEST[ $key ] = addslashes( $value );
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
		$this->assertEquals( $output, $string );
	}
}
