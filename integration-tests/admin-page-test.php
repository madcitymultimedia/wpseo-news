<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WPSEO_News_Admin_Page_Test.
 */
class WPSEO_News_Admin_Page_Test extends WPSEO_News_UnitTestCase {

	/**
	 * Instance of the WPSEO_News_Admin_Page class.
	 *
	 * @var WPSEO_News_Admin_Page
	 */
	private $instance;

	/**
	 * Original value of the $plugin_page global.
	 *
	 * @var string|null
	 */
	private $original_plugin_page;

	/**
	 * Setting up the instance of WPSEO_News_Admin_Page.
	 */
	public function set_up() {
		parent::set_up();

		// Because is a global $wpseo_admin_pages we have to fill this one with an instance of WPSEO_Admin_Pages.
		// Similar for the WP native $plugin_page global.
		global $wpseo_admin_pages, $plugin_page;

		if ( empty( $wpseo_admin_pages ) ) {
			$wpseo_admin_pages = new WPSEO_Admin_Pages();
		}

		// The $plugin_page variable needs to be set, the value is actually not really relevant for our purposes.
		$this->original_plugin_page = $plugin_page;
		$plugin_page                = 'wpseo_news';

		$this->instance = new WPSEO_News_Admin_Page();
	}

	/**
	 * Clean up after each test.
	 */
	public function tear_down() {
		// Reset the value of $plugin_page.
		global $plugin_page;
		$plugin_page = $this->original_plugin_page;
		unset( $this->original_plugin_page );

		parent::tear_down();
	}

	/**
	 * Tests whether the admin page is generated correctly.
	 *
	 * @covers WPSEO_News_Admin_Page::display
	 */
	public function test_display() {

		// We expect this part in the generated HTML.
		$expected_output = <<<'EOT'
<p>You will generally only need a News Sitemap when your website is included in Google News.</p><p><a target="_blank" href="http://example.org/news-sitemap.xml">View your News Sitemap</a>.</p>
EOT;

		$this->expectOutputContains( $expected_output );

		$this->instance->display();
	}
}
