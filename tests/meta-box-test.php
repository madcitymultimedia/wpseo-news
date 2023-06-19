<?php

namespace Yoast\WP\News\Tests;

use WPSEO_News_Meta_Box;

/**
 * Test the WPSEO_News_Meta_Box class.
 *
 * @coversDefaultClass WPSEO_News_Meta_Box
 */
class Meta_Box_Test extends TestCase {

	/**
	 * The instance to test.
	 *
	 * @var WPSEO_News_Meta_Box
	 */
	protected $instance;

	/**
	 * Sets up the instance to test.
	 */
	public function set_up() {
		parent::set_up();
		$this->stubTranslationFunctions();
		$this->instance = new WPSEO_News_Meta_Box( '1.0.0' );
	}

	/**
	 * Data provider for the register_hooks test.
	 *
	 * @return array
	 */
	public static function data_provider_register_hooks() {
		return [
			'Load the editor script when on an edit post' =>
			[
				'page_now'              => 'post.php',
				'get'                   => null,
				'admin_enqueue_scripts' => 10,
				'elementor_action'      => false,
			],
			'Load the editor script when on an new post' =>
			[
				'page_now'              => 'post-new.php',
				'get'                   => null,
				'admin_enqueue_scripts' => 10,
				'elementor_action'      => false,
			],
			'Load the editor script when on an elementor edit page' =>
			[
				'page_now'              => 'post.php',
				'get'                   => [ 'action' => 'elementor' ],
				'admin_enqueue_scripts' => 10,
				'elementor_action'      => 10,
			],
			'Not post edit, new post or elementor post' =>
			[
				'page_now'              => 'admin.php',
				'get'                   => null,
				'admin_enqueue_scripts' => false,
				'elementor_action'      => false,
			],

		];
	}

	/**
	 * Tests register_hooks method.
	 *
	 * @covers ::register_hooks
	 *
	 * @dataProvider data_provider_register_hooks
	 *
	 * @param string     $page_now              The current page.
	 * @param array|null $get                   The $_GET array.
	 * @param bool       $admin_enqueue_scripts Whether the admin_enqueue_scripts hook should be called.
	 * @param bool       $elementor_action      Whether the elementor/editor/before_enqueue_scripts hook should be called.
	 */
	public function test_register_hooks( $page_now, $get, $admin_enqueue_scripts, $elementor_action ) {

		global $pagenow;
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, this is used only in tests.
		$pagenow = $page_now;
		$_GET    = $get;
		$this->instance->register_hooks();

		$this->assertNotFalse( \has_filter( 'add_extra_wpseo_meta_fields', [ $this->instance, 'add_meta_fields_to_wpseo_meta' ] ) );
		$this->assertNotFalse( \has_filter( 'wpseo_save_metaboxes', [ $this->instance, 'save' ] ) );
		$this->assertNotFalse( \has_filter( 'wpseo_content_meta_section_content', [ $this->instance, 'add_news_fields_to_the_content' ] ) );
		$this->assertNotFalse( \has_filter( 'wpseo_elementor_hidden_fields', [ $this->instance, 'add_news_fields_to_the_content' ] ) );
		$this->assertNotFalse( \has_filter( 'yoast_free_additional_metabox_sections', [ $this->instance, 'add_metabox_section' ] ) );

		$this->assertSame( $admin_enqueue_scripts, \has_action( 'admin_enqueue_scripts', [ $this->instance, 'enqueue_scripts' ] ) );
		$this->assertSame( $elementor_action, \has_action( 'elementor/editor/before_enqueue_scripts', [ $this->instance, 'enqueue_scripts' ] ) );
	}

	/**
	 * Tests the save method.
	 *
	 * @covers ::save
	 */
	public function test_save() {
		$result = $this->instance->save( [] );

		$expected = [
			'newssitemap-stocktickers' => [
				'name'        => 'newssitemap-stocktickers',
				'std'         => '',
				'type'        => 'hidden',
				'title'       => 'Stock Tickers',
				'description' => 'A comma-separated list of up to 5 stock tickers of the companies, mutual funds, or other financial entities that are the main subject of the article. Each ticker must be prefixed by the name of its stock exchange, and must match its entry in Google Finance. For example, "NASDAQ:AMAT" (but not "NASD:AMAT"), or "BOM:500325" (but not "BOM:RIL").',
			],
			'newssitemap-robots-index' =>
			[
				'type'          => 'hidden',
				'default_value' => '0',
				'std'           => '',
				'options'       => [ 'index', 'noindex' ],

				'title'         => 'Googlebot-News index',
				'description'   => 'Using noindex allows you to prevent articles from appearing in Google News.',
			],
		];

		$this->assertSame( $expected, $result );
	}
}
