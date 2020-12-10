<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WPSEO_News_Sitemap_Test.
 */
class WPSEO_News_Sitemap_Test extends WPSEO_News_UnitTestCase {

	/**
	 * Instance of the WPSEO_News_Sitemap class.
	 *
	 * @var WPSEO_News_Sitemap
	 */
	private $instance;

	/**
	 * Setting up the instance of WPSEO_News_Sitemap.
	 */
	public function setUp() {
		parent::setUp();

		$this->instance = new WPSEO_News_Sitemap();
	}

	/**
	 * Verifies that the news sitemap is not added to the sitemap index when there are no news items.
	 *
	 * @covers WPSEO_News_Sitemap::add_to_index
	 */
	public function test_add_to_index_no_items() {
		$input  = '';
		$output = $this->instance->add_to_index( $input );
		$this->assertSame( $input, $output );
	}

	/**
	 * Verifies that the news sitemap is correctly added to the sitemap index when there are news items.
	 *
	 * Prior to PHP 5.5.10, timezone offsets were not supported by `DateTimeZone` causing the test to fail.
	 *
	 * @requires PHP 5.5.10
	 *
	 * @covers WPSEO_News_Sitemap::add_to_index
	 */
	public function test_add_to_index() {

		/**
		 * We need an item to be present to get output.
		 */
		$this->factory->post->create(
			[
				'post_title' => 'generate rss',
			]
		);

		$output = $this->instance->add_to_index( '' );

		$output_date      = new DateTime( get_lastpostdate( 'gmt' ) );
		$expected_output  = '<sitemap>' . "\n";
		$expected_output .= '<loc>' . home_url( 'news-sitemap.xml' ) . '</loc>' . "\n";
		$expected_output .= '<lastmod>' . htmlspecialchars( $output_date->format( 'c' ), ENT_COMPAT, 'UTF-8', false ) . '</lastmod>' . "\n";
		$expected_output .= '</sitemap>' . "\n";

		$this->assertSame( $expected_output, $output );
	}

	/**
	 * Checks what happens if no posts are added.
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_empty() {

		$output = $this->instance->build_sitemap();

		$expected_output = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
</urlset>';

		$this->assertSame( $expected_output, $output );
	}

	/**
	 * Checks what happens if there is one post added.
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_NOT_empty() {
		WPSEO_Options::set( 'news_sitemap_name', 'Test Blog' );

		$post_id = $this->factory->post->create( [ 'post_title' => 'generate rss' ] );

		$output = $this->instance->build_sitemap();

		// This is what we expect.
		$expected_output  = "<url>\n";
		$expected_output .= "\t<loc>" . get_permalink( $post_id ) . "</loc>\n";
		$expected_output .= "\t<news:news>\n";
		$expected_output .= "\t\t<news:publication>\n";
		$expected_output .= "\t\t\t<news:name>Test Blog</news:name>\n";
		$expected_output .= "\t\t\t<news:language>en</news:language>\n";
		$expected_output .= "\t\t</news:publication>\n";
		$expected_output .= "\t\t<news:publication_date>" . get_the_date( DATE_ATOM, $post_id ) . "</news:publication_date>\n";
		$expected_output .= "\t\t<news:title><![CDATA[generate rss]]></news:title>\n";
		$expected_output .= "\t</news:news>\n";
		$expected_output .= '</url>';

		// Check if the $output contains the $expected_output.
		$this->assertStringContainsString( $expected_output, $output );
	}

	/**
	 * Checks what happens if there is one post added.
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_post_excluded() {
		// Create post.
		$post_id = $this->factory->post->create();

		// Set meta value to exclude.
		update_post_meta( $post_id, '_yoast_wpseo_newssitemap-exclude', 'on' );

		$output = $this->instance->build_sitemap();

		// The expected output.
		$expected_output = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
</urlset>';

		// Check if the $output is the same as the $expected_output.
		$this->assertSame( $expected_output, $output );
	}

	/**
	 * Checks what happens if there is one post added with a image in its content.
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_WITH_image() {

		$image        = home_url( 'tests/assets/yoast.png' );
		$post_details = [
			'post_title'   => 'with images',
			'post_content' => '<img src="' . $image . '" />',
		];
		$this->factory->post->create( $post_details );

		$output = $this->instance->build_sitemap();

		$expected_output  = "\t<image:image>\n";
		$expected_output .= "\t\t<image:loc>" . $image . "</image:loc>\n";
		$expected_output .= "\t</image:image>\n";

		// Check if the $output contains the $expected_output.
		$this->assertStringContainsString( $expected_output, $output );
	}

	/**
	 * Checks what happens if there is one post added with a image in its content.
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_WITHOUT_featured_image_restricted() {

		$image        = home_url( 'tests/assets/yoast.png' );
		$post_details = [
			'post_title'   => 'featured image',
			'post_content' => '<img src="' . $image . '" />',
		];
		$post_id      = $this->factory->post->create( $post_details );

		$featured_image = home_url( 'tests/assets/yoast_featured.png' );
		$thumbnail_id   = $this->create_attachment( $featured_image, $post_id );

		update_post_meta( $post_id, '_thumbnail_id', $thumbnail_id );

		$output = $this->instance->build_sitemap();

		$expected_output  = "\t</news:news>\n";
		$expected_output .= "\t<image:image>\n";
		$expected_output .= "\t\t<image:loc>" . $image . "</image:loc>\n";
		$expected_output .= "\t</image:image>\n";
		$expected_output .= "\t<image:image>\n";
		$expected_output .= "\t\t<image:loc>" . $featured_image . "</image:loc>\n";
		$expected_output .= "\t\t<image:title>attachment</image:title>\n";
		$expected_output .= "\t</image:image>\n";

		// Check if the $output contains the $expected_output.
		$this->assertStringContainsString( $expected_output, $output );
	}

	/**
	 * Checks that the sitemap uses the default name of news when no news post type is present.
	 *
	 * @covers WPSEO_News_Sitemap::news_sitemap_basename
	 */
	public function test_sitemap_default_name() {
		$this->assertSame( 'news', WPSEO_News_Sitemap::news_sitemap_basename() );
	}

	/**
	 * Checks that the sitemap name uses the fallback name for the sitemap when a post type of News exists.
	 *
	 * @covers WPSEO_News_Sitemap::news_sitemap_basename
	 */
	public function test_sitemap_name_on_post_type() {
		register_post_type( 'news' );

		$this->assertSame( 'yoast-news', WPSEO_News_Sitemap::news_sitemap_basename() );
	}

	/**
	 * Checks that the sitemap name uses the YOAST_NEWS_SITEMAP_BASENAME constant value.
	 *
	 * @covers WPSEO_News_Sitemap::news_sitemap_basename
	 */
	public function test_sitemap_name_on_constant() {
		define( 'YOAST_NEWS_SITEMAP_BASENAME', 'unit-test-news' );

		$this->assertSame( 'unit-test-news', WPSEO_News_Sitemap::news_sitemap_basename() );
	}

	/**
	 * Checks that expired posts don't get included in the sitemap.
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_only_showing_recent_items() {
		$base_time = time();
		$this->factory->post->create(
			[
				'post_title'    => 'Newest post',
				'post_date'     => gmdate( 'Y-m-d H:i:s', $base_time ),
				'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $base_time ),
			]
		);

		$two_days_ago = strtotime( '-48 hours' );

		$this->factory->post->create(
			[
				'post_title'    => 'New-ish post',
				'post_date'     => gmdate( 'Y-m-d H:i:s', $two_days_ago ),
				'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $two_days_ago ),
			]
		);

		$two_days_ago_one_minute = strtotime( '-48 hours -1 minute' );

		$this->factory->post->create(
			[
				'post_title'    => 'Too old Post',
				'post_date'     => gmdate( 'Y-m-d H:i:s', $two_days_ago_one_minute ),
				'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $two_days_ago_one_minute ),
			]
		);

		$output = $this->instance->build_sitemap();

		// Check if the $output contains the $expected_output.
		$this->assertStringContainsString( "\t\t<news:title><![CDATA[Newest post]]></news:title>\n", $output );
		$this->assertStringContainsString( "\t\t<news:title><![CDATA[New-ish post]]></news:title>\n", $output );

		$this->assertStringNotContainsString( "\t\t<news:title><![CDATA[Too old Post]]></news:title>\n", $output );
	}

	/**
	 * Test helper. Create an attachment to test with.
	 *
	 * @param string $image   URL to the image.
	 * @param int    $post_id ID of the post to attach the image to.
	 *
	 * @return int Attachment post ID.
	 */
	private function create_attachment( $image, $post_id = 0 ) {
		return $this->factory->post->create(
			[
				'post_title'     => 'attachment',
				'post_name'      => 'attachment',
				'guid'           => $image,
				'post_type'      => 'attachment',
				'post_mime_type' => 'image/png',
				'parent_id'      => $post_id,
			]
		);
	}
}
