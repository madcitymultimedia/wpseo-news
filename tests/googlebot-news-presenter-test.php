<?php

namespace Yoast\WP\News\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery;
use Mockery\MockInterface;
use WPSEO_News_Googlebot_News_Presenter;

/**
 * Test the WPSEO_News class.
 *
 * @coversDefaultClass WPSEO_News_Googlebot_News_Presenter
 *
 * @runTestsInSeparateProcesses
 */
class Googlebot_News_Presenter_Test extends TestCase {

	/**
	 * Represents the instance to test.
	 *
	 * @var WPSEO_News_Googlebot_News_Presenter
	 */
	protected $instance;

	/**
	 * Represents the presentation.
	 *
	 * @var MockInterface
	 */
	protected $presentation;

	/**
	 * Represents the model (indexable).
	 *
	 * @var MockInterface
	 */
	protected $model;

	/**
	 * Represents the source.
	 *
	 * @var MockInterface
	 */
	protected $source;

	/**
	 * Sets the instance.
	 */
	public function set_up() {
		parent::set_up();

		$this->instance     = new WPSEO_News_Googlebot_News_Presenter();
		$this->presentation = Mockery::mock();
		$this->model        = Mockery::mock();
		$this->source       = Mockery::mock();

		$this->presentation->model    = $this->model;
		$this->presentation->source   = $this->source;
		$this->instance->presentation = $this->presentation;
	}

	/**
	 * Tests with noindex output for a non post object.
	 *
	 * @covers WPSEO_News_Googlebot_News_Presenter::present
	 */
	public function test_present_for_non_post() {
		$this->model->object_type = 'term';

		$this->assertSame( '', $this->instance->get() );
	}

	/**
	 * Tests with noindex output enabled by filter but disabled by meta value.
	 *
	 * @covers WPSEO_News_Googlebot_News_Presenter::present
	 * @covers WPSEO_News_Googlebot_News_Presenter::display_noindex
	 */
	public function test_present_with_noindexing_enabled_by_filter() {
		$this->model->object_type = 'post';
		$this->source->ID         = 1337;

		$meta = Mockery::mock( 'overload:WPSEO_Meta' );
		$meta
			->expects( 'get_value' )
			->with( 'newssitemap-robots-index', 1337 )
			->andReturnFalse();

		Monkey\Actions\expectDone( 'Yoast\WP\News\head' );

		Monkey\Filters\expectApplied( 'Yoast\WP\News\head_display_noindex' )
			->andReturn( true );

		$this->assertSame( '', $this->instance->get() );
	}

	/**
	 * Tests with noindex output disabled by filter.
	 *
	 * @covers WPSEO_News_Googlebot_News_Presenter::present
	 * @covers WPSEO_News_Googlebot_News_Presenter::display_noindex
	 */
	public function test_present_with_noindexing_disabled_by_filter() {
		$this->model->object_type = 'post';
		$this->source->ID         = 1337;

		Monkey\Actions\expectDone( 'Yoast\WP\News\head' );

		Monkey\Filters\expectApplied( 'Yoast\WP\News\head_display_noindex' )
			->andReturn( false );

		$this->assertSame( '', $this->instance->get() );
	}

	/**
	 * Tests the noindex output being enabled by meta value.
	 *
	 * @covers WPSEO_News_Googlebot_News_Presenter::present
	 * @covers WPSEO_News_Googlebot_News_Presenter::display_noindex
	 */
	public function test_present_with_noindexing_enabled_by_meta() {
		$this->model->object_type = 'post';
		$this->source->ID         = 1337;

		$this->stubEscapeFunctions();

		$meta = Mockery::mock( 'overload:WPSEO_Meta' );
		$meta
			->expects( 'get_value' )
			->with( 'newssitemap-robots-index', 1337 )
			->andReturnTrue();
		Monkey\Actions\expectDone( 'Yoast\WP\News\head' );

		Monkey\Filters\expectApplied( 'Yoast\WP\News\head_display_noindex' )
			->andReturn( true );

		Functions\expect( 'is_admin_bar_showing' )
			->andReturn( false );

		$this->assertSame( '<meta name="Googlebot-News" content="noindex" />', $this->instance->present() );
	}
}
