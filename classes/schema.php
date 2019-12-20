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
	 * The date helper.
	 *
	 * @var WPSEO_Date_Helper
	 */
	protected $date;

	/**
	 * WPSEO_News_Schema Constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		$this->date = new WPSEO_Date_Helper();

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
		$post = $this->get_post();
		// Alter the article post types only when the news article is not excluded.
		if ( $post !== null && ! $this->is_post_excluded( $post ) ) {
			$post_types = array_unique( array_merge( WPSEO_News::get_included_post_types(), $post_types ) );
		}

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
		$post = $this->get_post();
		if ( $post !== null && in_array( $post->post_type, WPSEO_News::get_included_post_types(), true ) ) {
			// Change the `@type` to `NewsArticle` only when the news article is not excluded.
			if ( ! $this->is_post_excluded( $post ) ) {
				$data['@type'] = 'NewsArticle';
			}

			$data['copyrightYear']   = $this->date->format( $post->post_date_gmt, 'Y' );
			$data['copyrightHolder'] = array( '@id' => trailingslashit( WPSEO_Utils::get_home_url() ) . WPSEO_Schema_IDs::ORGANIZATION_HASH );
		}

		return $data;
	}

	/**
	 * Checks if the given post should be excluded or not.
	 *
	 * @codeCoverageIgnore It just wraps logic.
	 *
	 * @param WP_Post $post The post to check for.
	 *
	 * @return bool True if the post should be excluded.
	 */
	protected function is_post_excluded( $post ) {
		return (
			WPSEO_News::is_excluded_through_sitemap( $post->ID )
			|| WPSEO_News::is_excluded_through_terms( $post->ID, $post->post_type )
		);
	}

	/**
	 * Retrieves post data given a post ID or post object.
	 *
	 * This function exists to be able to mock the get_post call and should
	 * no longer be needed when moving the tests suite over to BrainMonkey.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param int|WP_Post|null $post Optional. Post ID or post object.
	 *
	 * @return WP_Post|null The post object or null if it cannot be found.
	 */
	protected function get_post( $post = null ) {
		return get_post( $post );
	}
}
