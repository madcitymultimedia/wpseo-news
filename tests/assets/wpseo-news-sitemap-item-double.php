<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WPSEO_News_Sitemap_Item.
 */
class WPSEO_News_Sitemap_Item_Double extends WPSEO_News_Sitemap_Item {

	/**
	 * @inheritdoc
	 */
	public function get_publication_date( $item ) {
		return parent::get_publication_date( $item );
	}

	/**
	 * @inheritdoc
	 */
	public function get_item_title( $item = null ) {
		return parent::get_item_title( $item );
	}
}
