<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WPSEO_News_Sitemap_Images_Test.
 *
 * @group news_sitemap_images
 */
class WPSEO_News_Sitemap_Images_Test extends WPSEO_News_UnitTestCase {

	/**
	 * Instance of the WPSEO_News_Sitemap_Images class.
	 *
	 * @var \WPSEO_News_Sitemap_Images
	 */
	protected $instance;

	/**
	 * Setting up the instance of WPSEO_News_Sitemap_Images.
	 */
	public function setUp() {
		parent::setUp();

		// Create a post and retrieve options so the new object can actually be created.
		// Neither is actually used for the current unit tests.
		$post_id = $this->factory->post->create();
		$post    = get_post( $post_id );
		$options = WPSEO_News::get_options();

		$this->instance = new WPSEO_News_Sitemap_Images( $post, $options );
	}

	/**
	 * Clean up after all tests in this class have run.
	 */
	public function tearDown() {
		$this->instance = null;

		parent::tearDown();
	}

	/**
	 * Tests parsing of the image source attribute value.
	 *
	 * @covers WPSEO_News_Sitemap_Images::parse_image_source
	 *
	 * @dataProvider provider_parse_image_source
	 *
	 * @requires PHP 5.3.2
	 *
	 * @param string $src      HTML image tag.
	 * @param string $expected Expected result.
	 */
	public function test_parse_image_source( $src, $expected ) {
		$url = $this->invoke_method( $this->instance, 'parse_image_source', array( $src ) );
		$this->assertSame( $expected, $url );
	}

	/**
	 * Data provider.
	 *
	 * @see WPSEO_News_Sitemap_Images_Test::parse_image_source()
	 */
	public function provider_parse_image_source() {
		return array(
			// HTTP.
			array(
				'http://example.org/wp-content/uploads/2018/01/image1.jpg',
				'http://example.org/wp-content/uploads/2018/01/image1.jpg',
			),
			// HTTPS.
			array(
				'https://example.org/wp-content/uploads/2018/01/image1.jpg',
				'https://example.org/wp-content/uploads/2018/01/image1.jpg',
			),
			// Relative URL.
			array(
				'/wp-content/uploads/2018/01/image1.jpg',
				'http://example.org/wp-content/uploads/2018/01/image1.jpg',
			),
			array(
				'wp-content/uploads/2018/01/image1.jpg',
				null,
			),
			// Protocol relative URL.
			array(
				'//example.org/wp-content/uploads/2018/01/image1.jpg',
				'//example.org/wp-content/uploads/2018/01/image1.jpg',
			),
		);
	}
}
