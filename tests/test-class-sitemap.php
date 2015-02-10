<?php

class WPSEO_News_Sitemap_Test extends WPSEO_News_UnitTestCase {

	private $instance;

	/**
	 * Setting up the instance of WPSEO_News_Admin_Page
	 */
	public function setUp() {
		parent::setUp();

		$this->instance = new WPSEO_News_Sitemap();
	}

	/**
	 * @covers WPSEO_News_Sitemap::add_to_index
	 */
	public function test_add_to_index() {

		$output = $this->instance->add_to_index( '' );

		$output_date     = new DateTime( get_lastpostdate( 'gmt' ), new DateTimeZone( new WPSEO_News_Sitemap_Timezone() ) );
		$expected_output = '<sitemap>' . "\n";
		$expected_output .= '<loc>' . home_url( 'news-sitemap.xml' ) . '</loc>' . "\n";
		$expected_output .= '<lastmod>' . htmlspecialchars( $output_date->format( 'c' ) ) . '</lastmod>' . "\n";
		$expected_output .= '</sitemap>' . "\n";

		$this->assertEquals( $expected_output, $output );

	}

	/**
	 * Check what happens if no posts are added
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_empty() {

		$output = $this->instance->build_sitemap();

		$expected_output = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
</urlset>';

		$this->assertEquals( $expected_output, $output );

	}

	/**
	 * Check what happens if there is one post added
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_NOT_empty() {
		$post_id = $this->factory->post->create(
			array(
				'post_title' => 'generate rss'
			)
		);

		$output = $this->instance->build_sitemap();

		// This is what we expect
		$expected_output = "<url>\n";
		$expected_output .= "\t<loc>" . get_permalink( $post_id ) . "</loc>\n";
		$expected_output .= "\t<news:news>\n";
		$expected_output .= "\t\t<news:publication>\n";
		$expected_output .= "\t\t\t<news:name><![CDATA[Test Blog]]></news:name>\n";
		$expected_output .= "\t\t\t<news:language>en</news:language>\n";
		$expected_output .= "\t\t</news:publication>\n";
		$expected_output .= "\t\t<news:publication_date>" . get_the_date( 'c', $post_id ) . "</news:publication_date>\n";
		$expected_output .= "\t\t<news:title><![CDATA[generate rss]]></news:title>\n";
		$expected_output .= "\t\t<news:keywords><![CDATA[]]></news:keywords>\n";
		$expected_output .= "\t</news:news>\n";
		$expected_output .= "</url>";

		// Check if the $output contains the $expected_output
		$this->assertContains( $expected_output, $output );
	}

	/**
	 * Check what happens if there is one post added
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_post_excluded() {
		// Create post
		$post_id = $this->factory->post->create(
			array(
				'post_title' => 'generate rss'
			)
		);

		// Set meta value to exclude
		update_post_meta( $post_id, '_yoast_wpseo_newssitemap-exclude', 'on' );

		$output = $this->instance->build_sitemap();

		// The expected output
		$expected_output = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
</urlset>';

		// Check if the $output contains the $expected_output
		$this->assertContains( $expected_output, $output );
	}


	/**
	 * Check what happens if there is one post added with keywords
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_WITH_keywords() {
		// Create post
		$post_id = $this->factory->post->create(
			array(
				'post_title' => 'generate rss'
			)
		);

		// Set meta value to exclude
		update_post_meta( $post_id, '_yoast_wpseo_newssitemap-keywords', 'test' );

		$output = $this->instance->build_sitemap();

		// The expected output
		$expected_output = "\t\t<news:keywords><![CDATA[test]]></news:keywords>\n";
		$expected_output .= "\t</news:news>\n";
		// Check if the $output contains the $expected_output

		$this->assertContains( $expected_output, $output );
	}

	/**
	 * Check what happens if there is one post added with only a single tag
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_WITH_tag() {
		// Create post
		$post_id = $this->factory->post->create(
			array(
				'post_title' => 'generate rss'
			)
		);

		// Set meta value to exclude
		$term = wp_insert_term( 'tag', 'post_tag');
		wp_set_post_terms( $post_id, array( $term['term_id'] ) );

		$output = $this->instance->build_sitemap();

		// The expected output
		$expected_output = "\t\t<news:keywords><![CDATA[tag]]></news:keywords>\n";
		$expected_output .= "\t</news:news>\n";
		// Check if the $output contains the $expected_output

		$this->assertContains( $expected_output, $output );
	}

	/**
	 * Check what happens if there is one post added with a image in its content
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_WITH_image() {

		$image   = home_url( 'tests/assets/yoast.png' );
		$post_id = $this->factory->post->create(
			array(
				'post_title'   => 'generate rss',
				'post_content' => '<img src="' . $image . '" />'
			)
		);

		$output = $this->instance->build_sitemap();

		$expected_output  = "\t<image:image>\n";
		$expected_output .= "\t\t<image:loc>" . $image . "</image:loc>\n";
		$expected_output .= "\t</image:image>\n";

		// Check if the $output contains the $expected_output
		$this->assertContains( $expected_output, $output );
	}

	/**
	 * Check what happens if there is one post added with a image in its content
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_WITHOUT_featured_image_restricted() {

		$image          = home_url( 'tests/assets/yoast.png' );
		$post_id = $this->factory->post->create(
			array(
				'post_title'   => 'featured image',
				'post_content' => '<img src="' . $image . '" />'
			)
		);

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

		// Check if the $output contains the $expected_output
		$this->assertContains( $expected_output, $output );
	}

	/**
	 * Check what happens if there is one post added with a image in its content
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_WITH_featured_image_restricted() {

		add_action('wpseo_news_options', array($this, 'restrict_featured_image'));

		$this->instance = new WPSEO_News_Sitemap();

		$image          = home_url( 'tests/assets/yoast.png' );
		$post_id = $this->factory->post->create(
			array(
				'post_title'   => 'featured image',
				'post_content' => '<img src="' . $image . '" />'
			)
		);

		$featured_image = home_url( 'tests/assets/yoast_featured.png' );
		$thumbnail_id   = $this->create_attachment( $featured_image, $post_id );

		update_post_meta( $post_id, '_thumbnail_id', $thumbnail_id );

		$output = $this->instance->build_sitemap();

		$expected_output  = "\t</news:news>\n";
		$expected_output .= "\t<image:image>\n";
		$expected_output .= "\t\t<image:loc>" . $featured_image . "</image:loc>\n";
		$expected_output .= "\t\t<image:title>attachment</image:title>\n";
		$expected_output .= "\t</image:image>\n";

		// Check if the $output contains the $expected_output
		$this->assertContains( $expected_output, $output );
	}


	public function restrict_featured_image( $options ) {
		$options['restrict_sitemap_featured_img'] = true;
		return $options;
	}


	private function create_attachment( $image, $post_id = 0 ) {
		return $this->factory->post->create(
			array(
				'post_title'     => 'attachment',
				'post_name'      => 'attachment',
				'guid'           => $image,
				'post_type'      => 'attachment',
				'post_mime_type' => 'image/png',
				'parent_id'      => $post_id
			)
		);
	}
}