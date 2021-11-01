<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

use Yoast\WP\SEO\Models\Indexable;

/**
 * Class WPSEO_News_Sitemap_Item_Test.
 */
class WPSEO_News_Sitemap_Item_Test extends WPSEO_News_UnitTestCase {

	/**
	 * Checks if the time output for the sitemap is correct when there is a post_date_gmt set.
	 *
	 * @covers WPSEO_News_Sitemap_Item::build_item
	 */
	public function test_build_item() {
		$test_indexable   = new Indexable();
		$test_indexable->object_id           = '123';
		$test_indexable->title               = 'Newest post';
		$test_indexable->permalink           = 'https://fake.url/slug';
		$test_indexable->object_published_at = '2021-10-29 13:21:13';
		$publication_tag  = "\t\t<news:publication>\n";
		$publication_tag .= "\t\t\t<news:name>Test News Site</news:name>\n";
		$publication_tag .= "\t\t\t<news:language>en_GB</news:language>\n";
		$publication_tag .= "\t\t</news:publication>\n";

		$instance = new WPSEO_News_Sitemap_Item( $test_indexable, $publication_tag );

		$expected_output = "<url>\n" .
			"\t<loc>https://fake.url/slug</loc>\n" .
			"\t<news:news>\n" .
			"\t\t<news:publication>\n" .
			"\t\t\t<news:name>Test News Site</news:name>\n" .
			"\t\t\t<news:language>en_GB</news:language>\n" .
			"\t\t</news:publication>\n" .
			"\t\t<news:publication_date>2021-10-29T13:21:13+00:00</news:publication_date>\n" .
			"\t\t<news:title><![CDATA[Newest Post]]></news:title>\n" .
			"\t</news:news>\n" .
			"</url>\n";

		$this->assertSame(
			$expected_output,
			$instance->__toString()
		);
	}
}

