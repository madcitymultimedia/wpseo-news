<?php
/**
 * @package WPSEO\Tests
 */

/**
 * Class WPSEO_News_Test
 */
class WPSEO_News_Test extends WPSEO_News_UnitTestCase {

	/**
	 * Tests the situation where WordPress version is below the minimal required version.
	 *
	 * @covers WPSEO_News::check_dependencies()
	 */
	public function test_with_required_depencendies_with_older_wordpress_version() {
		$class_instance = new WPSEO_News_Double();

		$this->assertFalse( $class_instance->check_dependencies( 3.0 ) );
	}

	/**
	 * * Tests the situation where WordPress SEO version isn't installed.
	 * @covers WPSEO_News::check_dependencies()
	 */
	public function test_with_required_depencendies_with_no_wordpress_seo_version() {
		$class_instance = $this->getMockBuilder( 'WPSEO_News_Double' )
			->setMethods( array( 'get_wordpress_seo_version' ) )
			->getMock();

		$class_instance
			->expects( $this->once() )
			->method( 'get_wordpress_seo_version' )
			->will( $this->returnValue( false ) );

		$this->assertFalse( $class_instance->check_dependencies( '5.0' ) );
	}

	/**
	 * Tests the situation where WordPress SEO version is below the minimal required version.
	 *
	 * @covers WPSEO_News::check_dependencies()
	 */
	public function test_with_required_depencendies_with_old_wordpress_seo_version() {
		$class_instance = $this->getMockBuilder( 'WPSEO_News_Double' )
			->setMethods( array( 'get_wordpress_seo_version' ) )
			->getMock();

		$class_instance
			->expects( $this->once() )
			->method( 'get_wordpress_seo_version' )
			->will( $this->returnValue( '6.9' ) );

		$this->assertFalse( $class_instance->check_dependencies( '5.0' ) );
	}

	/**
	 * Tests the situation where WordPress and WordPress SEO have the minimal required versions.
	 *
	 * @covers WPSEO_News::check_dependencies()
	 */
	public function test_with_all_required_depencendies_ok() {
		$class_instance = $this->getMockBuilder( 'WPSEO_News_Double' )
			->setMethods( array( 'get_wordpress_seo_version' ) )
			->getMock();

		$class_instance
			->expects( $this->once() )
			->method( 'get_wordpress_seo_version' )
			->will( $this->returnValue( '7.0' ) );

		$this->assertTrue( $class_instance->check_dependencies( '5.0' ) );
	}


}