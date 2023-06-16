<?php

namespace Yoast\WP\News\Tests;

use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use Mockery;
use WPSEO_News;
use WPSEO_Options;

/**
 * Test the WPSEO_News class.
 */
class News_Test extends TestCase {

	/**
	 * The instance.
	 *
	 * @var WPSEO_News
	 */
	protected $instance;

	/**
	 * Sets the instance.
	 */
	public function set_up() {
		parent::set_up();

		global $wp_version;
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- intended, to be able to test the constructor.
		$wp_version = YOAST_SEO_NEWS_WP_REQUIRED;

		$this->instance = new WPSEO_News();
	}

	/**
	 * Tests the retrieval of included post types.
	 *
	 * @covers WPSEO_News::__construct
	 * @covers WPSEO_News::set_hooks
	 *
	 * @runInSeparateProcess
	 */
	public function test_construct() {
		$this->assertNotFalse( Filters\has( 'plugin_action_links', [ $this->instance, 'plugin_links' ] ) );
		$this->assertNotFalse( Filters\has( 'wpseo_submenu_pages', [ $this->instance, 'add_submenu_pages' ] ) );
		$this->assertNotFalse( Actions\has( 'init', [ 'WPSEO_News_Option', 'register_option' ] ) );
		$this->assertNotFalse( Actions\has( 'init', [ 'WPSEO_News', 'read_options' ] ) );
		$this->assertNotFalse( Actions\has( 'admin_init', [ $this->instance, 'init_admin' ] ) );

		$this->assertNotFalse( Filters\has( 'wpseo_enable_tracking', '__return_true' ) );
		$this->assertNotFalse( Filters\has( 'wpseo_helpscout_beacon_settings', [ $this->instance, 'filter_helpscout_beacon' ] ) );

		$this->assertNotFalse( Filters\has( 'wpseo_frontend_presenters', [ $this->instance, 'add_frontend_presenter' ] ) );
	}

	/**
	 * Tests the retrieval of included post types.
	 *
	 * @covers WPSEO_News::get_included_post_types
	 *
	 * @runInSeparateProcess
	 */
	public function test_get_included_post_types() {
		$options = Mockery::mock( 'overload:' . WPSEO_Options::class );
		$options
			->shouldReceive( 'get' )
			->with( 'news_sitemap_include_post_types', [] )
			->once()
			->andReturn(
				[
					'post' => 'on',
				]
			);

		Functions\expect( 'get_post_types' )
			->andReturn( [ 'post', 'page' ] );

		$this->assertSame( [ 'post' ], WPSEO_News::get_included_post_types() );
	}

	/**
	 * Tests the retrieval of included post types with having no included post types set.
	 *
	 * @covers WPSEO_News::get_included_post_types
	 *
	 * @runInSeparateProcess
	 */
	public function test_get_included_post_types_with_no_set_post_types() {
		$options = Mockery::mock( 'overload:' . WPSEO_Options::class );
		$options
			->shouldReceive( 'get' )
			->with( 'news_sitemap_include_post_types', [] )
			->once()
			->andReturn( [] );

		Functions\expect( 'get_post_types' )
			->andReturn( [ 'page' ] );

		$this->assertSame( [ 'post' ], WPSEO_News::get_included_post_types() );
	}
}
