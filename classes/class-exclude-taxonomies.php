<?php

class WPSEO_Exclude_Taxonomies {
	/**
	 * Gets a list of taxonomies of which posts with terms of this taxonomy can be excluded from the sitemap.
	 *
	 * @param string $post_type The post type.
	 *
	 * @return array Taxonomies of which posts with terms of this taxonomy can be excluded from the sitemap.
	 */
	public static function getExcludableTaxonomies( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		return array_filter( $taxonomies, 'WPSEO_Exclude_Taxonomies::filter_taxonomies_show_ui' );
	}

	/**
	 * Filter to check whether a taxonomy is shows in the WordPress ui.
	 *
	 * @param WP_Taxonomy $taxonomy The taxonomy to filter.
	 *
	 * @return bool Whether or not the taxonomy is hidden in the WordPress ui.
	 */
	public static function filter_taxonomies_show_ui( WP_Taxonomy $taxonomy ) {
		return $taxonomy->show_ui === true;
	}
}
