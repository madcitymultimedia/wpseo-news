<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News
 */

/**
 * Makes the require Schema changes.
 */
class WPSEO_News_Schema {
	/**
	 * WPSEO_News_Head Constructor.
	 */
	public function __construct() {
		$this->post_types = WPSEO_News::get_included_post_types();
		add_filter( 'wpseo_schema_article_post_types', array( $this, 'article_post_types' ) );
		add_filter( 'wpseo_schema_article', array( $this, 'change_article' ) );
	}

	/**
	 * Make all News post types output Article schema.
	 *
	 * @param array $post_types Supported post types.
	 *
	 * @return array $post_types Supported post types.
	 */
	public function article_post_types( $post_types ) {
		$post_types = array_merge( $this->post_types, $post_types );

		return $post_types;
	}

	/**
	 * Change Article to NewsArticle.
	 *
	 * @param array $data Schema Article data.
	 *
	 * @return array $data Schema Article data.
	 */
	public function change_article( $data ) {
		if ( in_array( get_post_type(), $this->post_types ) ) {
			$data['@type'] = 'NewsArticle';
		}

		return $data;
	}
}
