<?php

namespace Yoast\WP\News\Tests;

use WPSEO_News;
use Yoast\WP\News\Tests\TestCase;
use Mockery;

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
		$options = Mockery::mock( 'overload:\WPSEO_Options' );
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
		$options = Mockery::mock( 'overload:\WPSEO_Options' );
		$options
			->shouldReceive( 'get' )
			->with( 'news_sitemap_include_post_types', [] )
			->once()
			->andReturn( [] );

		expect( 'get_post_types' )
			->andReturn( [ 'page' ] );

		$this->assertEquals( [ 'post' ], WPSEO_News::get_included_post_types() );
	}
}
