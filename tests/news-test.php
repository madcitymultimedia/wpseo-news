<?php

namespace Yoast\WP\News\Tests;

use WPSEO_News;
use function Brain\Monkey\Functions\expect;

/**
 * Test the WPSEO_News class.
 */
class News_Test extends TestCase {

	/**
	 * Tests the retrieval of included post types.
	 *
	 * @covers WPSEO_News::get_included_post_types
	 *
	 * @runInSeparateProcess
	 */
	public function test_get_included_post_types() {
		$options = \Mockery::mock( 'overload:\WPSEO_Options' );
		$options
			->shouldReceive( 'get' )
			->with( 'news_sitemap_include_post_types', [] )
			->once()
			->andReturn(
				[
					'post' => 'on',
				]
			);

		expect( 'get_post_types' )
			->andReturn( [ 'post', 'page' ] );

		$this->assertEquals( [ 'post' ], WPSEO_News::get_included_post_types() );
	}

	/**
	 * Tests the retrieval of included post types with having no included post types set.
	 *
	 * @covers WPSEO_News::get_included_post_types
	 *
	 * @runInSeparateProcess
	 */
	public function test_get_included_post_types_with_no_set_post_types() {
		$options = \Mockery::mock( 'overload:\WPSEO_Options' );
		$options
			->shouldReceive( 'get' )
			->with( 'news_sitemap_include_post_types', [] )
			->once()
			->andReturn( [] );

		expect( 'get_post_types' )
			->andReturn( [ 'page' ] );

		$this->assertEquals( [ 'post' ], WPSEO_News::get_included_post_types() );
	}

	/**
	 * Determines if the post is excluded in through a term that is excluded.
	 *
	 * @param int    $post_id   The ID of the post.
	 * @param string $post_type The type of the post.
	 *
	 * @return bool True if the post is excluded.
	 */
	public static function is_excluded_through_terms( $post_id, $post_type ) {
		$terms          = self::get_terms_for_post( $post_id, $post_type );
		$excluded_terms = (array) WPSEO_Options::get( 'news_sitemap_exclude_terms', array() );
		foreach ( $terms as $term ) {
			$option_key = $term->taxonomy . '_' . $term->slug . '_for_' . $post_type;
			if ( array_key_exists( $option_key, $excluded_terms ) && $excluded_terms[ $option_key ] === 'on' ) {
				return true;
			}
		}

		return false;
	}
}
