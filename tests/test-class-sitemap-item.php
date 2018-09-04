<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WPSEO_News_Sitemap_Item_Test.
 */
class WPSEO_News_Sitemap_Item_Test extends WPSEO_News_UnitTestCase {

	/**
	 * Instance of the WPSEO_News_Sitemap_Item class.
	 *
	 * @var \WPSEO_News_Sitemap_Item
	 */
	private $instance;

	/**
	 * Checks if the time output for the sitemap is correct when there is a post_date_gmt set.
	 *
	 * @covers WPSEO_News_Sitemap_Item::get_publication_date
	 */
	public function test_get_publication_date_returning_correct_UTC_time() {
		$base_time = time();
		$timezone_format = DateTime::W3C;

		$test_post_date_gmt = $this->factory->post->create_and_get(
			array(
				'post_title'    => 'Newest post',
				'post_date'     => date( $timezone_format, $base_time ),
				'post_date_gmt' => date( $timezone_format, $base_time )
			)
		);

		$test_options = WPSEO_News::get_options();
		$this->instance = new WPSEO_News_Sitemap_Item_Double($test_post_date_gmt, $test_options);

		$original_post_UTC_time      = date( $timezone_format, $base_time );
		$get_publication_date_output = $this->instance->get_publication_date( $test_post_date_gmt );

		// Check if post_date_gmt is equal to output of get_publication_date().
		$this->assertEquals( $original_post_UTC_time, $get_publication_date_output );
	}

	/**
	 * Checks if the time output for the sitemap is correct when there is no post_date_gmt set.
	 *
	 * @covers WPSEO_News_Sitemap_Item::get_publication_date
	 */
	public function test_get_publication_date_returning_correct_post_date_when_no_gmt_set() {
		$base_time = time();
		$timezone_format = DateTime::W3C;

		$test_post_date = $this->factory->post->create_and_get(
			array(
				'post_title'    => 'Newest post',
				'post_date'     => date( $timezone_format, $base_time ),
			)
		);

		// Manually set post_date_gmt to an invalid string, because at creation WP forces a valid string.
		$test_post_date->post_date_gmt = "This is an invalid string";

		$test_options = WPSEO_News::get_options();
		$this->instance = new WPSEO_News_Sitemap_Item_Double($test_post_date, $test_options);

		$original_post_date          = date( $timezone_format, $base_time );
		$get_publication_date_output = $this->instance->get_publication_date( $test_post_date );

		// Check if post_date is equal to output of get_publication_date().
		$this->assertEquals( $original_post_date, $get_publication_date_output );
	}
}

