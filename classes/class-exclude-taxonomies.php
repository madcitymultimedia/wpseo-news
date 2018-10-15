<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News
 */

/**
 * Class representing the excludable taxonomies for a certain post type.
 */
class WPSEO_News_Excludable_Taxonomies {
	/**
	 * The post type.
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Setting properties.
	 *
	 * @param string $post_type The post type.
	 */
	public function __construct( $post_type ) {
		$this->post_type = $post_type;
	}

	/**
	 * Gets a list of taxonomies of which posts with terms of this taxonomy can be excluded from the sitemap.
	 *
	 * @return array Taxonomies of which posts with terms of this taxonomy can be excluded from the sitemap.
	 */
	public function get() {
		$taxonomies = get_object_taxonomies( $this->post_type, 'objects' );

		return array_filter( $taxonomies, array( $this, 'filter_taxonomies' ) );
	}

	/**
	 * Filter to check whether a taxonomy is shows in the WordPress ui.
	 *
	 * @param WP_Taxonomy $taxonomy The taxonomy to filter.
	 *
	 * @return bool Whether or not the taxonomy is hidden in the WordPress ui.
	 */
	protected function filter_taxonomies( WP_Taxonomy $taxonomy ) {
		return $taxonomy->show_ui === true;
	}
}
