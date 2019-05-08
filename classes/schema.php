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
		$post_types = array_merge( WPSEO_News::get_included_post_types(), $post_types );

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
		$post = get_post();
		if ( in_array( $post->post_type, WPSEO_News::get_included_post_types(), true ) ) {
			$data['@type']           = 'NewsArticle';
			$data['copyrightYear']   = mysql2date( 'Y', $post->post_date_gmt, false );
			$data['copyrightHolder'] = array( '@id' => WPSEO_Utils::get_home_url() . WPSEO_Schema_IDs::ORGANIZATION_HASH );
		}

		return $data;
	}
}
