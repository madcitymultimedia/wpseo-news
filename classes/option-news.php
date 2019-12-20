<?php
/**
 * WPSEO plugin file.
 *
 * @package WPSEO\Internals\Options
 */

/**
 * Class representing the wpseo_news options.
 */
class WPSEO_Option_News extends WPSEO_Option {

	/**
	 * The option name.
	 *
	 * @var string
	 */
	protected $option_name = 'wpseo_news';

	/**
	 * The defaults.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'news_sitemap_name'          => '',
		'news_sitemap_default_genre' => '',
		'news_version'               => '0',
	);

	/**
	 * Used for "caching" during pageload.
	 *
	 * @var array
	 */
	protected $enriched_defaults;

	/**
	 * Registers the option to the WPSEO Options framework.
	 */
	public static function register_option() {
		WPSEO_Options::register_option( self::get_instance() );
	}

	/**
	 * Get the singleton instance of this class.
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Add dynamically created default options based on available post types and taxonomies.
	 *
	 * @return void
	 */
	public function enrich_defaults() {
		$enriched_defaults = $this->enriched_defaults;
		if ( $enriched_defaults !== null ) {
			$this->defaults += $enriched_defaults;
			return;
		}

		$enriched_defaults = [];

		/*
		 * Retrieve all the relevant post type and taxonomy arrays.
		 *
		 * WPSEO_Post_Type::get_accessible_post_types() should *not* be used here.
		 * These are the defaults and can be prepared for any public post type.
		 */
		$post_types = get_post_types( [ 'public' => true ], 'objects' );

		if ( $post_types ) {
			foreach ( $post_types as $post_type ) {
				$enriched_defaults[ 'news_sitemap_include_post_type_' . $post_type->name ] = '';

				$excludable_taxonomies = new WPSEO_News_Excludable_Taxonomies( $post_type->name );

				foreach ( $excludable_taxonomies->get_terms() as $data ) {
					$terms = $data['terms'];

					foreach ( $terms as $term ) {
						$enriched_defaults[ 'news_sitemap_exclude_term_' . $term->taxonomy . '_' . $term->slug . '_for_' . $post_type->name ] = '';
					}
				}
			}
		}

		$this->enriched_defaults = $enriched_defaults;
		$this->defaults         += $enriched_defaults;
	}

	/**
	 * All concrete classes must contain a validate_option() method which validates all
	 * values within the option.
	 *
	 * @param array $dirty New value for the option.
	 * @param array $clean Clean value for the option, normally the defaults.
	 * @param array $old   Old value of the option.
	 *
	 * @return $clean The cleaned an validated option.
	 */
	protected function validate_option( $dirty, $clean, $old ) {

		foreach ( $clean as $key => $value ) {
			switch ( $key ) {
				case 'news-version':
					$clean[ $key ] = WPSEO_NEWS_VERSION;
				break;
				case 'news_sitemap_name':
					if ( isset( $dirty[ $key ] ) && $dirty[ $key ] !== '' ) {
						$clean[ $key ] = WPSEO_Utils::sanitize_text_field( $dirty[ $key ] );
					}
					break;
				case 'news_sitemap_default_genre' :
					if ( isset( $dirty[ $key ] ) && is_array( $dirty[ $key ] ) ) {
						$clean[ $key ] = array_map( [ 'WPSEO_Utils', 'sanitize_text_field' ], $dirty[ $key ] );
					}

					if ( isset( $dirty[ $key ] ) &&  is_string( $dirty[ $key ] ) ) {
						$clean[ $key ] = WPSEO_Utils::sanitize_text_field( $dirty[ $key ] );
					}

					break;

				default :
					if ( stripos( $key, 'news_sitemap_include_post_type_' ) === 0 || stripos( $key, 'news_sitemap_exclude_term_' ) === 0  ) {
						if ( ! empty( $dirty[ $key ] ) ) {
							$clean[ $key ] = 'on';
						}
						else {
							unset ( $clean[ $key ] );
						}
					}

					break;
			}
		}

		return $clean;
	}
}
