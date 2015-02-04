<?php

class WPSEO_News_Admin_Page_Test extends WPSEO_News_UnitTestCase {

	private $instance;

	/**
	 * Setting up the instance of WPSEO_News_Admin_Page
	 */
	public function setUp() {
		parent::setUp();

		// Because is a global $wpseo_admin_pages we have to fill this one with an instance of WPSEO_Admin_Pages
		global $wpseo_admin_pages;

		if(empty($wpseo_admin_pages)) {
			$wpseo_admin_pages = new WPSEO_Admin_Pages();
		}

		$this->instance = new WPSEO_News_Admin_Page();
	}

	/**
	 * @covers WPSEO_News_Admin_Page::display
	 */
	public function test_display() {

		// Start buffering to get the output of display method
		ob_start();

		$this->instance->display();

		$output = ob_get_contents();
		ob_end_clean();

		// We expect this part in the generated HTML
		$expected_output = <<<EOT
<p>You will generally only need XML News sitemap when your website is included in Google News.</p><p>You can find your news sitemap here: <a target='_blank' class='button-secondary' href='http://example.org/news-sitemap.xml'>XML News sitemap</a></p><label class="textinput" for="name">Google News Publication Name:</label><input class="textinput" type="text" id="name" name="wpseo_news[name]" value=""/><br class="clear" /><label class="select" for="default_genre">Default Genre:</label><select class="select" name="wpseo_news[default_genre]" id="default_genre"><option value="none">None</option><option value="pressrelease">Press Release</option><option value="satire">Satire</option><option value="blog">Blog</option><option value="oped">Op-Ed</option><option value="opinion">Opinion</option><option value="usergenerated">User Generated</option></select><br class="clear"/><label class="textinput" for="default_keywords">Default Keywords:</label><input class="textinput" type="text" id="default_keywords" name="wpseo_news[default_keywords]" value=""/><br class="clear" /><p>It might be wise to add some of Google's suggested keywords to all of your posts, add them as a comma separated list. Find the list here: <a href="http://www.google.com/support/news_pub/bin/answer.py?answer=116037" rel="nofollow">http://www.google.com/support/news_pub/bin/answer.py?answer=116037</a></p><input class="checkbox double" type="checkbox" id="restrict_sitemap_featured_img" name="wpseo_news[restrict_sitemap_featured_img]" value="on"/><label for="restrict_sitemap_featured_img">Only use featured image for XML News sitemap, ignore images in post.</label><br class="clear" /><br><br><h2>Post Types to include in News Sitemap</h2><input class="checkbox double" type="checkbox" id="newssitemap_include_post" name="wpseo_news[newssitemap_include_post]" value="on"/><label for="newssitemap_include_post">Posts</label><br class="clear" /><input class="checkbox double" type="checkbox" id="newssitemap_include_page" name="wpseo_news[newssitemap_include_page]" value="on"/><label for="newssitemap_include_page">Pages</label><br class="clear" /><input class="checkbox double" type="checkbox" id="newssitemap_include_attachment" name="wpseo_news[newssitemap_include_attachment]" value="on"/><label for="newssitemap_include_attachment">Media</label><br class="clear" /><h2>Editors' Pick</h2><label class="select" for="ep_image_src">Editors' Pick Image:</label><input id="ep_image_src" type="text" size="36" name="wpseo_news[ep_image_src]" value="" /><input id="ep_image_src_button" class="wpseo_image_upload_button button" type="button" value="Upload Image" /><br class="clear"/><p>You can find your Editors' Pick RSS feed here: <a target='_blank' class='button-secondary' href='http://example.org/editors-pick.rss'>Editors' Pick RSS Feed</a></p><p>You can submit your Editors' Pick RSS feed here: <a class='button-secondary' href='https://support.google.com/news/publisher/contact/editors_picks' target='_blank'>Submit Editors' Pick RSS Feed</a></p>
EOT;

		// Check if the $output contains the $expected_output
		$this->assertContains( $expected_output, $output );

	}

}