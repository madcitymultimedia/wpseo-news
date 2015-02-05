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

		$output = $this->instance->add_to_index('');

		$output_date     = new DateTime( get_lastpostdate( 'gmt' ), new DateTimeZone( new WPSEO_News_Sitemap_Timezone() ) );
		$expected_output = '<sitemap>' . "\n";
		$expected_output .= '<loc>' . home_url( 'news-sitemap.xml' ) . '</loc>' . "\n";
		$expected_output .= '<lastmod>' . htmlspecialchars( $output_date->format( 'c' ) ) . '</lastmod>' . "\n";
		$expected_output .= '</sitemap>' . "\n";

		$this->assertEquals($expected_output, $output);

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

		$this->assertEquals($expected_output, $output);

	}

}