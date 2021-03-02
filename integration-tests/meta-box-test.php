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
			->setConstructorArgs( [ '1260' ] )
			->setMethods(
				[
					'is_post_type_supported',
					'get_meta_boxes',
					'do_meta_box',
				]
			)
			->getMock();

		$stub->method( 'is_post_type_supported' )->willReturn( true );

		$sections = $stub->add_metabox_section( [] );

		$this->assertCount( 1, $sections );

		$this->assertSame( 'news', $sections[0]['name'] );
		$this->assertSame( '<span class="dashicons dashicons-admin-plugins"></span>News', $sections[0]['link_content'] );
		$this->assertSame( '<div id="wpseo-news-metabox-root" class="wpseo-meta-section-content"></div>', $sections[0]['content'] );
	}

	/**
	 * Tests whether the function doesn't add the metabox section if the post type is not supported.
	 *
	 * @covers ::add_metabox_section
	 */
	public function test_add_metabox_section_unsupported_posttype() {
		$stub = $this
			->getMockBuilder( 'WPSEO_News_Meta_Box_Double' )
			->setConstructorArgs( [ '1260' ] )
			->setMethods( [ 'is_post_type_supported' ] )
			->getMock();

		$stub->method( 'is_post_type_supported' )->willReturn( false );

		$sections = $stub->add_metabox_section( [] );

		$this->assertCount( 0, $sections );
	}
}
