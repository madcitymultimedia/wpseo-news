<?php

class WPSEO_News_Sitemap_Editors_Pick_Test extends WPSEO_News_UnitTestCase {

	private $instance;

	private $post_id;

	/**
	 * Setting up the instance of WPSEO_News_Admin_Page
	 */
	public function setUp() {
		parent::setUp();

		$this->post_id = $this->factory->post->create(
			array(
				'post_title' => 'generate rss'
			)
		);
		// Set post as editors pick
		add_post_meta($this->post_id, '_yoast_wpseo_newssitemap-editors-pick', 'on');

		$this->instance = new WPSEO_News_Sitemap_Editors_Pick();
	}

	/**
	 * @covers WPSEO_News_Sitemap_Editors_Pick::generate_rss
	 */
	public function test_generate_rss() {
		// The date in XML format
		$date_in_rss = get_the_date( DATE_RFC822, $this->post_id );

		// Start buffering to get the output of display method
		ob_start();

		$this->instance->generate_rss( false );

		$output = ob_get_contents();
		ob_clean();

		// We expect this part in the generated HTML
		$expected_output = <<<EOT
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="http://example.org/editors-pick.rss" rel="self" type="application/rss+xml" />
<link>http://example.org</link>
<description>Just another WordPress site</description>
<title>Test Blog</title>
<item>
<title><![CDATA[generate rss]]></title>
<guid isPermaLink="true">http://example.org/?p={$this->post_id}</guid>
<link>http://example.org/?p={$this->post_id}</link>
<description><![CDATA[Post excerpt 1]]></description>
<dc:creator><![CDATA[]]></dc:creator>
<pubDate>{$date_in_rss}</pubDate>
</item>
</channel>
</rss>
EOT;

		// Check if the $output contains the $expected_output
		$this->assertContains( $expected_output, $output );
	}

}