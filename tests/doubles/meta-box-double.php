<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WWPSEO_News_Meta_Box_Double.
 */
class WPSEO_News_Meta_Box_Double extends WPSEO_News_Meta_Box {

	/**
	 * Check if current post_type is supported.
	 *
	 * @return bool
	 */
	public function is_post_type_supported() {
		return parent::is_post_type_supported();
	}
}
