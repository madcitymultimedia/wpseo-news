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
	 * Checks if the time output for the sitemap is correct when there is a post_date_gmt set.
	 *
	 * @covers WPSEO_News_Sitemap_Item::get_publication_date
	 * @covers WPSEO_News_Sitemap_Item::is_valid_datetime
	 * @covers WPSEO_News_Sitemap_Item::format_date_with_timezone
	 */
	public function test_get_publication_date_returning_correct_UTC_time() {
		$base_time = time();
		$timezone_format = DateTime::W3C;

		$test_post_date_gmt = self::factory()->post->create_and_get(
			array(
				'post_title'    => 'Newest post',
				'post_date'     => date( $timezone_format, $base_time ),
				'post_date_gmt' => date( $timezone_format, $base_time ),
				'post_type'     => 'post',
			)
		);

		$test_options = WPSEO_News::get_options();
		$instance     = new WPSEO_News_Sitemap_Item_Double( $test_post_date_gmt, $test_options );

		$original_post_UTC_time      = date( $timezone_format, $base_time );
		$get_publication_date_output = $instance->get_publication_date( $test_post_date_gmt );

		// Check if post_date_gmt is equal to output of get_publication_date().
		$this->assertEquals( $original_post_UTC_time, $get_publication_date_output );
	}

	/**
	 * Checks if the time output for the sitemap is correct when there is no post_date_gmt set.
	 *
	 * @covers WPSEO_News_Sitemap_Item::get_publication_date
	 * @covers WPSEO_News_Sitemap_Item::is_valid_datetime
	 * @covers WPSEO_News_Sitemap_Item::format_date_with_timezone
	 */
	public function test_get_publication_date_returning_correct_post_date_when_no_gmt_set() {
		$base_time = time();
		$timezone_format = DateTime::W3C;

		$test_post_date = self::factory()->post->create_and_get(
			array(
				'post_title'    => 'Newest post',
				'post_date'     => date( $timezone_format, $base_time ),
				'post_type'     => 'post',
			)
		);

		// Manually set post_date_gmt to an invalid string, because at creation WP forces a valid string.
		$test_post_date->post_date_gmt = 'This is an invalid string';

		$test_options = WPSEO_News::get_options();
		$instance     = new WPSEO_News_Sitemap_Item_Double( $test_post_date, $test_options );

		$original_post_date          = date( $timezone_format, $base_time );
		$get_publication_date_output = $instance->get_publication_date( $test_post_date );

		// Check if post_date is equal to output of get_publication_date().
		$this->assertEquals( $original_post_date, $get_publication_date_output );
	}

	/**
	 * Checks if the time output for the sitemap is an empty string when dates are
	 * invalid.
	 *
	 * @covers WPSEO_News_Sitemap_Item::get_publication_date
	 */
	public function test_get_publication_date_with_invalid_datetime() {
		$test_post_date_gmt = self::factory()->post->create_and_get(
			array(
				'post_title'    => 'Newest post',
				'post_type'     => 'post',
			)
		);

		$test_post_date_gmt->post_date     = -10;
		$test_post_date_gmt->post_date_gmt = -10;

		$test_options = WPSEO_News::get_options();
		$instance     = new WPSEO_News_Sitemap_Item_Double( $test_post_date_gmt, $test_options );

		$get_publication_date_output = $instance->get_publication_date( $test_post_date_gmt );

		// Check if post_date_gmt is equal to output of get_publication_date().
		$this->assertEquals( '', $get_publication_date_output );
	}

	/**
	 * Checks if the post title output for the sitemap defaults to the post title when no SEO title is present.
	 *
	 * @covers WPSEO_News_Sitemap_Item::get_item_title
	 */
	public function test_get_item_title_when_no_seo_title_set() {
		$test_seo_title = self::factory()->post->create_and_get(
			array(
				'post_title'    => 'Post without SEO title',
				'post_type'     => 'post',
			)
		);

		$test_options = WPSEO_News::get_options();
		$instance     = new WPSEO_News_Sitemap_Item_Double( $test_seo_title, $test_options );

		$title_output = $instance->get_item_title( $test_seo_title );

		// Check if correct post_title is returned.
		$this->assertEquals( 'Post without SEO title', $title_output );
	}

	/**
	 * Checks if the post title output for the sitemap is the SEO title when set.
	 *
	 * @covers WPSEO_News_Sitemap_Item::get_item_title
	 */
	public function test_get_item_title_when_seo_title_set() {
		$test_seo_title = self::factory()->post->create_and_get(
			array(
				'post_title'    => 'Post with SEO title',
				'post_type'     => 'post',
			)
		);
		// $key, $meta_value, $post_id )
		$test_options = WPSEO_News::get_options();

		WPSEO_Meta::set_value('title', 'SEO title of the post', $test_seo_title->ID );
		$instance     = new WPSEO_News_Sitemap_Item_Double( $test_seo_title, $test_options );

		$title_output = $instance->get_item_title( $test_seo_title );

		// Check if correct post_title is returned.
		$this->assertEquals( 'SEO title of the post', $title_output );
	}
}

