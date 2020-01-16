<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WPSEO_News_Test.
 */
class WPSEO_News_Test extends WPSEO_News_UnitTestCase {

	/**
	 * Tests the check dependencies function.
	 *
	 * @dataProvider check_dependencies_data
	 *
	 * @covers WPSEO_News::check_dependencies
	 *
	 * @param bool   $expected              The expected value.
	 * @param string $wordpress_seo_version The WordPress SEO version to check.
	 * @param string $wordpress_version     The WordPress version to check.
	 * @param string $message               Message given by PHPUnit after assertion.
	 */
	public function test_check_dependencies( $expected, $wordpress_seo_version, $wordpress_version, $message ) {
		$class_instance = $this
			->getMockBuilder( 'WPSEO_News_Double' )
			->disableOriginalConstructor()
			->setMethods( [ 'get_wordpress_seo_version' ] )
			->getMock();

		$class_instance
			->expects( $this->any() )
			->method( 'get_wordpress_seo_version' )
			->will( $this->returnValue( $wordpress_seo_version ) );


		$this->assertSame( $expected, $class_instance->check_dependencies( $wordpress_version ), $message );
	}

	/**
	 * Data provider for the check dependencies test.
	 *
	 * [0]: Expected
	 * [1]: WordPress SEO Version
	 * [2]: WordPress Version
	 * [3]: Message for PHPUnit.
	 *
	 * @return array[]
	 */
	public function check_dependencies_data() {
		return [
			[ false, '12.7', '3.0', 'WordPress is below the minimal required version.' ],
			[ false, '12.7', '5.1', 'WordPress is below the minimal required version.' ],
			[ false, false, '5.3', 'WordPress SEO is not installed.' ],
			[ false, '8.1', '5.3', 'WordPress SEO is below the minimal required version.' ],
			[ true, '12.6.1-RC1', '5.2', 'WordPress (5.2) and WordPress SEO have the minimal required versions.' ],
			[ true, '12.7', '5.3', 'WordPress (5.3) and WordPress SEO have the minimal required versions.' ],
		];
	}
}
