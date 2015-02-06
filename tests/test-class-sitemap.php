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
	 * Check what happens if there is one post added with a image in its content
	 *
	 * @covers WPSEO_News_Sitemap::build_sitemap
	 */
	public function test_sitemap_WITH_image() {

		$image   = home_url('tests/assets/yoast.png');
		$post_id = $this->factory->post->create(
			array(
				'post_title' => 'generate rss',
				'post_content' => '<img src="' . $image . '" />'
			)
		);

		$output = $this->instance->build_sitemap();

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
		$expected_output .= "\t<image:image>\n";
		$expected_output .= "\t\t<image:loc>" . $image . "</image:loc>\n";
		$expected_output .= "\t</image:image>\n";
		$expected_output .= "</url>";

		// Check if the $output contains the $expected_output
		$this->assertContains( $expected_output, $output );
	}

}