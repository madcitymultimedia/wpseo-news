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
	 * @param int|WP_Post|null $post Optional. Post ID or post object. Defaults to global $post.
	 *
	 * @return WP_Post|array|null Type corresponding to $output on success or null on failure.
	 */
	public function get_post( $post = null ) {
		return get_post( $post );
	}
}
