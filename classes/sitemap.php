<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News\XML_Sitemaps
 */

/**
 * Handling the generation of the News Sitemap.
 */
class WPSEO_News_Sitemap {

	/**
	 * The date helper.
	 *
	 * @var WPSEO_Date_Helper
	 */
	protected $date;

	/**
	 * The sitemap basename.
	 *
	 * @var string
	 */
	private $basename;

	/**
	 * Constructor. Set options, basename and add actions.
	 */
	public function __construct() {
		$this->date = new WPSEO_Date_Helper();

		add_action( 'init', [ $this, 'init' ], 10 );

		add_action( 'save_post', [ $this, 'invalidate_sitemap' ] );

		add_action( 'wpseo_news_schedule_sitemap_clear', 'yoast_wpseo_news_clear_sitemap_cache' );
	}

	/**
	 * Add the XML News Sitemap to the Sitemap Index.
	 *
	 * @param string $str String with Index sitemap content.
	 *
	 * @return string
	 */
	public function add_to_index( $str ) {

		// Only add when we have items.
		$items = $this->get_items( 1 );
		if ( empty( $items ) ) {
			return $str;
		}

		$str .= '<sitemap>' . "\n";
		$str .= '<loc>' . self::get_sitemap_name() . '</loc>' . "\n";
		$str .= '<lastmod>' . htmlspecialchars( $this->date->format( get_lastpostdate( 'gmt' ) ), ENT_COMPAT, get_bloginfo( 'charset' ), false ) . '</lastmod>' . "\n";
		$str .= '</sitemap>' . "\n";

		return $str;
	}

	/**
	 * Register the XML News sitemap with the main sitemap class.
	 */
	public function init() {

		$this->basename = self::get_sitemap_name( false );

		// Setting stylesheet for cached sitemap.
		add_action( 'wpseo_sitemap_stylesheet_cache_' . $this->basename, [ $this, 'set_stylesheet_cache' ] );

		if ( isset( $GLOBALS['wpseo_sitemaps'] ) ) {
			add_filter( 'wpseo_sitemap_index', [ $this, 'add_to_index' ] );

			$this->yoast_wpseo_news_schedule_clear();

			// We might consider deprecating/removing this, because we are using a static xsl file.
			$GLOBALS['wpseo_sitemaps']->register_sitemap( $this->basename, [ $this, 'build' ] );
			if ( method_exists( $GLOBALS['wpseo_sitemaps'], 'register_xsl' ) ) {
				$xsl_rewrite_rule = sprintf( '^%s-sitemap.xsl$', $this->basename );

				$GLOBALS['wpseo_sitemaps']->register_xsl( $this->basename, [ $this, 'build_news_sitemap_xsl' ], $xsl_rewrite_rule );
			}
		}
	}

	/**
	 * Method to invalidate the sitemap.
	 *
	 * @param int $post_id Post ID to invalidate for.
	 */
	public function invalidate_sitemap( $post_id ) {
		// If this is just a revision, don't invalidate the sitemap cache yet.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Bail if this is a multisite installation and the site has been switched.
		if ( is_multisite() && ms_is_switched() ) {
			return;
		}

		// Only invalidate when we are in a News Post Type object.
		if ( ! in_array( get_post_type( $post_id ), WPSEO_News::get_included_post_types(), true ) ) {
			return;
		}

		WPSEO_Sitemaps_Cache::invalidate( $this->basename );
	}

	/**
	 * When sitemap is coming out of the cache there is no stylesheet. Normally it will take the default stylesheet.
	 *
	 * This method is called by a filter that will set the video stylesheet.
	 *
	 * @param object $target_object Target Object to set cache from.
	 *
	 * @return object
	 */
	public function set_stylesheet_cache( $target_object ) {
		$target_object->renderer->set_stylesheet( $this->get_stylesheet_line() );

		return $target_object;
	}

	/**
	 * Build the sitemap and push it to the XML Sitemaps Class instance for display.
	 */
	public function build() {
		$GLOBALS['wpseo_sitemaps']->set_sitemap( $this->build_sitemap() );
		$GLOBALS['wpseo_sitemaps']->renderer->set_stylesheet( $this->get_stylesheet_line() );
	}

	/**
	 * Building the XML for the sitemap.
	 *
	 * @return string
	 */
	public function build_sitemap() {
		$output = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

		$items = $this->get_items();

		// Loop through items.
		if ( ! empty( $items ) ) {
			$output .= $this->build_items( $items );
		}

		$output .= '</urlset>';

		return $output;
	}

	/**
	 * Outputs the XSL file.
	 */
	public function build_news_sitemap_xsl() {
		$protocol = 'HTTP/1.1';
		if ( filter_input( INPUT_SERVER, 'SERVER_PROTOCOL' ) !== '' ) {
			$protocol = sanitize_text_field( filter_input( INPUT_SERVER, 'SERVER_PROTOCOL' ) );
		}
		// Force a 200 header and replace other status codes.
		header( $protocol . ' 200 OK', true, 200 );
		// Set the right content / mime type.
		header( 'Content-Type: text/xml' );
		// Prevent the search engines from indexing the XML Sitemap.
		header( 'X-Robots-Tag: noindex, follow', true );
		// Make the browser cache this file properly.
		header( 'Pragma: public' );
		header( 'Cache-Control: maxage=' . YEAR_IN_SECONDS );
		header( 'Expires: ' . $this->date->format_timestamp( ( time() + YEAR_IN_SECONDS ), 'D, d M Y H:i:s' ) . ' GMT' );

		/*
		 * Using `readfile()` rather than `include` to prevent issues with XSL being interpreted as PHP
		 * on systems where the PHP ini directived `short_open_tags` is turned on.
		 * phpcs:disable WordPress.WP.AlternativeFunctions.file_system_read_readfile
		 */
		readfile( dirname( WPSEO_NEWS_FILE ) . '/assets/xml-news-sitemap.xsl' );
		// phpcs:enable

		die();
	}

	/**
	 * Clear the sitemap and sitemap index every hour to make sure the sitemap is hidden or shown when it needs to be.
	 *
	 * @return void
	 */
	private function yoast_wpseo_news_schedule_clear() {
		$schedule = wp_get_schedule( 'wpseo_news_schedule_sitemap_clear' );

		if ( empty( $schedule ) ) {
			wp_schedule_event( time(), 'hourly', 'wpseo_news_schedule_sitemap_clear' );
		}
	}

	/**
	 * Getter for stylesheet URL.
	 *
	 * @return string Stylesheet URL.
	 */
	private function get_stylesheet_line() {
		return "\n" . '<?xml-stylesheet type="text/xsl" href="' . esc_url( $this->get_xsl_url() ) . '"?>';
	}

	/**
	 * Getting all the items for the sitemap.
	 *
	 * @param int $limit The limit for the query, default is 1000 items.
	 *
	 * @return array|object|null
	 */
	private function get_items( $limit = 1000 ) {
		global $wpdb;

		// Get supported post types.
		$post_types = WPSEO_News::get_included_post_types();

		if ( empty( $post_types ) ) {
			return [];
		}

		$replacements   = $post_types;
		$replacements[] = max( 1, min( 1000, $limit ) );

		// Get posts for the last two days only, credit to Alex Moss for this code.
		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID, post_content, post_name, post_author, post_parent, post_date_gmt, post_date, post_title, post_type
				FROM {$wpdb->posts}
				WHERE post_status='publish'
					AND post_date_gmt >= NOW() - INTERVAL 48 HOUR
					AND post_type IN (" . implode( ',', array_fill( 0, count( $post_types ), '%s' ) ) . ')
				ORDER BY post_date_gmt DESC
				LIMIT 0, %d
				',
				$replacements
			)
		);

		return $items;
	}

	/**
	 * Loop through all $items and build each one of it.
	 *
	 * @param array $items Items to convert to sitemap output.
	 *
	 * @return string
	 */
	private function build_items( $items ) {
		$output = '';
		foreach ( $items as $item ) {
			$output .= new WPSEO_News_Sitemap_Item( $item );
		}

		return $output;
	}

	/**
	 * Getting the name for the sitemap, if $full_path is true, it will return the full path.
	 *
	 * @param bool $full_path Generate a full path.
	 *
	 * @return string
	 */
	public static function get_sitemap_name( $full_path = true ) {
		/**
		 * Allows for filtering the News sitemap name.
		 *
		 * @deprecated 12.5.0. Use the {@see 'Yoast\WP\News\sitemap_name'} filter instead.
		 *
		 * @param string $sitemap_name First portion of the news sitemap "file" name.
		 */
		$sitemap_name = apply_filters_deprecated(
			'wpseo_news_sitemap_name',
			[ self::news_sitemap_basename() ],
			'YoastSEO News 12.5.0',
			'Yoast\WP\News\sitemap_name'
		);

		/**
		 * Allows for filtering the News sitemap name.
		 *
		 * @since 12.5.0
		 *
		 * @param string $sitemap_name First portion of the news sitemap "file" name.
		 */
		$sitemap_name = apply_filters( 'Yoast\WP\News\sitemap_name', $sitemap_name );

		// When $full_path is true, it will generate a full path.
		if ( $full_path ) {
			return WPSEO_Sitemaps_Router::get_base_url( $sitemap_name . '-sitemap.xml' );
		}

		return $sitemap_name;
	}

	/**
	 * Returns the basename of the news-sitemap, the first portion of the name of the sitemap "file".
	 *
	 * Defaults to news, but it's possible to override it by using the YOAST_NEWS_SITEMAP_BASENAME constant.
	 *
	 * @since 3.1
	 *
	 * @return string Basename for the news sitemap.
	 */
	public static function news_sitemap_basename() {
		$basename = 'news';

		if ( post_type_exists( 'news' ) ) {
			$basename = 'yoast-news';
		}

		if ( defined( 'YOAST_NEWS_SITEMAP_BASENAME' ) ) {
			$basename = YOAST_NEWS_SITEMAP_BASENAME;
		}

		return $basename;
	}

	/**
	 * Retrieves the XSL URL that should be used in the current environment.
	 *
	 * When home_url and site_url are not the same, the home_url should be used.
	 * This is because the XSL needs to be served from the same domain, protocol and port
	 * as the XML file that is loading it.
	 *
	 * @return string The XSL URL that needs to be used.
	 */
	protected function get_xsl_url() {
		if ( home_url() !== site_url() ) {
			return home_url( $this->basename . '-sitemap.xsl' );
		}

		return plugin_dir_url( WPSEO_NEWS_FILE ) . 'assets/xml-news-sitemap.xsl';
	}
}
