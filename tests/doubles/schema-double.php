<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WPSEO_News_Schema.
 */
class WPSEO_News_Schema_Double extends WPSEO_News_Schema {

	/**
	 * Retrieves post data given a post ID or post object.
	 *
	 * @param int|WP_Post|null $post Optional. Post ID or post object.
	 *
	 * @return WP_Post|null The post object or null if it cannot be found.
	 */
	public function get_post( $post = null ) {
		return parent::get_post( $post );
	}
}
