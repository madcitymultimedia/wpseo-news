<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WPSEO_News_Meta_Box.
 *
 * @coversDefaultClass WPSEO_News_Meta_Box
 */
class WPSEO_News_Meta_Box_Test extends WPSEO_News_UnitTestCase {

	/**
	 * Tests whether the function adds the news metabox section.
	 *
	 * @covers ::add_metabox_section
	 */
	public function test_add_metabox_section() {
		$stub = $this
			->getMockBuilder( 'WPSEO_News_Meta_Box_Double' )
			->setMethods(
				array(
					'is_post_type_supported',
					'get_meta_boxes',
					'do_meta_box',
				)
			)
			->getMock();

		$stub->method( 'is_post_type_supported' )->willReturn( true );
		$stub->method( 'get_meta_boxes' )->willReturn( array( 'metakey' => 'metabox' ) );
		$stub->method( 'do_meta_box' )->willReturn( '[content]' );

		$sections = $stub->add_metabox_section( array() );

		$this->assertEquals( count( $sections ), 1 );

		$this->assertEquals( $sections[0]['name'], 'news' );
		$this->assertEquals( $sections[0]['link_content'], '<span class="dashicons dashicons-admin-plugins"></span>Google News' );
		$this->assertEquals( $sections[0]['content'], '[content]' );
	}

	/**
	 * Tests whether the function doesn't add the metabox section if the post type is not supported.
	 *
	 * @covers ::add_metabox_section
	 */
	public function test_add_metabox_section_unsupported_posttype() {
		$stub = $this
			->getMockBuilder( 'WPSEO_News_Meta_Box_Double' )
			->setMethods( array( 'is_post_type_supported' ) )
			->getMock();

		$stub->method( 'is_post_type_supported' )->willReturn( false );

		$sections = $stub->add_metabox_section( array() );

		$this->assertEquals( count( $sections ), 0 );
	}
}
