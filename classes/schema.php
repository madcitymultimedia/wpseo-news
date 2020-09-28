<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News
 */

use Yoast\WP\SEO\Config\Schema_IDs;

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

		add_filter( 'wpseo_schema_article_post_types', [ $this, 'article_post_types' ] );
		add_filter( 'wpseo_schema_article_type', [ $this, 'add_news_article_type' ] );
		add_filter( 'wpseo_schema_article', [ $this, 'add_copyright_information' ] );
	}

	/**
	 * Make all News post types output Article schema.
	 *
	 * @param array $post_types Supported post types.
	 *
	 * @return array Supported post types.
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
	 * Adds the NewsArticle type.
	 *
	 * @param array|string $type Schema Article type.
	 *
	 * @return array Schema Article type.
	 */
	public function add_news_article_type( $type ) {
		$post = $this->get_post();

		if ( ! $this->is_post_type_included( $post ) ) {
			return $type;
		}
		if ( $this->is_post_excluded( $post ) ) {
			return $type;
		}

		// Make sure that we are dealing with an array of types.
		if ( ! is_array( $type ) ) {
			$type = [ $type ];
		}

		/*
		 * Replace `None` with `Article` if included.
		 * This is to keep it consistent with post types that already include an Article.
		 */
		$type = array_map(
			static function ( $value ) {
				return ( $value === 'None' ) ? 'Article' : $value;
			},
			$type
		);

		$type[] = 'NewsArticle';

		return $type;
	}

	/**
	 * Adds copyright information.
	 *
	 * @param array $data Schema Article data.
	 *
	 * @return array Schema Article data.
	 */
	public function add_copyright_information( $data ) {
		$post = $this->get_post();
		if ( $this->is_post_type_included( $post ) ) {
			$data['copyrightYear']   = $this->date->format( $post->post_date_gmt, 'Y' );
			$data['copyrightHolder'] = [ '@id' => trailingslashit( WPSEO_Utils::get_home_url() ) . Schema_IDs::ORGANIZATION_HASH ];
		}

		return $data;
	}

	/**
	 * Checks if the given post should be included or not, based on the type.
	 *
	 * @param WP_Post|null $post The post to check for.
	 *
	 * @return bool True if the post should be included based on post type.
	 */
	protected function is_post_type_included( $post ) {
		return $post !== null && in_array( $post->post_type, WPSEO_News::get_included_post_types(), true );
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
