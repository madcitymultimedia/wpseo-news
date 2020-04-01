<?php

namespace Yoast\WP\News\Tests;

use Brain\Monkey;
use WPSEO_News_Google_Bot_News_Presenter;
use Yoast\WP\News\Tests\TestCase;
use Mockery;

/**
 * Test the WPSEO_News class.
 *
 * @coversDefaultClass WPSEO_News_Google_Bot_News_Presenter
 */
class WPSEO_News_Google_Bot_News_Presenter_Test extends TestCase {

	/**
	 * Represents the instance to test.
	 *
	 * @var WPSEO_News_Google_Bot_News_Presenter
	 */
	protected $instance;

	/**
	 * Represents the presentation.
	 *
	 * @var Mockery\MockInterface
	 */
	protected $presentation;

	/**
	 * Represents the model (indexable).
	 *
	 * @var Mockery\MockInterface
	 */
	protected $model;

	/**
	 * Represents the source.
	 *
	 * @var Mockery\MockInterface
	 */
	protected $source;

	/**
	 * @inheritDoc
	 */
	public function setUp() {
		parent::setUp();

		$this->instance = new WPSEO_News_Google_Bot_News_Presenter();
		$this->presentation = Mockery::mock();
		$this->model        = Mockery::mock();
		$this->source       = Mockery::mock();

		$this->presentation->model  = $this->model;
		$this->presentation->source = $this->source;
	}

	/**
	 * Tests with noindex output for a non post object.
	 * @covers WPSEO_News_Google_Bot_News_Presenter::present
	 */
	public function test_present_for_non_post() {
		$this->model->object_type = 'term';

		$this->assertSame( '', $this->instance->present( $this->presentation ) );
	}

	/**
	 * Tests with noindex output enabled by filter but disabled by meta value.
	 *
	 * @covers WPSEO_News_Google_Bot_News_Presenter::present
	 * @covers WPSEO_News_Google_Bot_News_Presenter::display_noindex
	 */
	public function test_present_with_noindexing_enabled_by_filter() {
		$this->model->object_type = 'post';
		$this->source->ID         = 1337;

		$meta = Mockery::mock( 'overload:WPSEO_Meta' );
		$meta
			->expects( 'get_value' )
			->with( 'newssitemap-robots-index', 1337 )
			->andReturnFalse();

		Monkey\Functions\expect( 'do_action_deprecated' )
			->once()
			->with(
				'wpseo_news_head',
				[],
				'YoastSEO News 12.5.0',
				'Yoast\WP\News\head'
			);

		Monkey\Actions\expectDone( 'Yoast\WP\News\head' );

		Monkey\Functions\expect( 'apply_filters_deprecated' )
			->once()
			->with(
				'wpseo_news_head_display_noindex',
				[
					true,
					$this->source
				],
				'YoastSEO News 12.5.0',
				'Yoast\WP\News\head_display_noindex'
		);

		Monkey\Filters\expectApplied( 'Yoast\WP\News\head_display_noindex' )
			->andReturn( true );

		$this->assertSame( '', $this->instance->present( $this->presentation ) );
	}

	/**
	 * Tests with noindex output disabled by filter.
	 *
	 * @covers WPSEO_News_Google_Bot_News_Presenter::present
	 * @covers WPSEO_News_Google_Bot_News_Presenter::display_noindex
	 */
	public function test_present_with_noindexing_disabled_by_filter() {
		$this->model->object_type = 'post';
		$this->source->ID         = 1337;

		Monkey\Functions\expect( 'do_action_deprecated' )
			->once()
			->with(
				'wpseo_news_head',
				[],
				'YoastSEO News 12.5.0',
				'Yoast\WP\News\head'
			);

		Monkey\Actions\expectDone( 'Yoast\WP\News\head' );

		Monkey\Functions\expect( 'apply_filters_deprecated' )
			->once()
			->with(
				'wpseo_news_head_display_noindex',
				[
					true,
					$this->source
				],
				'YoastSEO News 12.5.0',
				'Yoast\WP\News\head_display_noindex'
		);

		Monkey\Filters\expectApplied( 'Yoast\WP\News\head_display_noindex' )
			->andReturn( false );

		$this->assertSame( '', $this->instance->present( $this->presentation ) );
	}

	/**
	 * Tests the noindex output being enabled by meta value.
	 *
	 * @covers WPSEO_News_Google_Bot_News_Presenter::present
	 * @covers WPSEO_News_Google_Bot_News_Presenter::display_noindex
	 */
	public function test_present_with_noindexing_enabled_by_meta() {
		$this->model->object_type = 'post';
		$this->source->ID         = 1337;

		$meta = Mockery::mock( 'overload:WPSEO_Meta' );
		$meta
			->expects( 'get_value' )
			->with( 'newssitemap-robots-index', 1337 )
			->andReturnTrue();

		Monkey\Functions\expect( 'do_action_deprecated' )
			->once()
			->with(
				'wpseo_news_head',
				[],
				'YoastSEO News 12.5.0',
				'Yoast\WP\News\head'
			);

		Monkey\Actions\expectDone( 'Yoast\WP\News\head' );

		Monkey\Functions\expect( 'apply_filters_deprecated' )
			->once()
			->with(
				'wpseo_news_head_display_noindex',
				[
					true,
					$this->source
				],
				'YoastSEO News 12.5.0',
				'Yoast\WP\News\head_display_noindex'
		);

		Monkey\Filters\expectApplied( 'Yoast\WP\News\head_display_noindex' )
			->andReturn( true );

		$this->assertSame( '<meta name="Googlebot-News" content="noindex" />' . PHP_EOL, $this->instance->present( $this->presentation ) );
	}
}
