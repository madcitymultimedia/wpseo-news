<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News
 */

use Yoast\WP\SEO\Presenters\Admin\Meta_Fields_Presenter;

/**
 * Represents the Yoast SEO: News metabox.
 */
class WPSEO_News_Meta_Box extends WPSEO_Metabox {

	/**
	 * Holds the flattened version to use with enqueueing the scripts.
	 *
	 * @var string
	 */
	protected $script_version;

	/**
	 * Constructs WPSEO_News_Meta_Box.
	 *
	 * @param string $script_version The version to use for the script.
	 *
	 * @noinspection PhpMissingParentConstructorInspection The parent constructor only has unwanted side-effects.
	 */
	public function __construct( $script_version ) {
		$this->script_version = $script_version;
	}

	/**
	 * Registers the hooks.
	 */
	public function register_hooks() {
		global $pagenow;

		// Register the fields as meta.
		add_filter( 'add_extra_wpseo_meta_fields', [ $this, 'add_meta_fields_to_wpseo_meta' ] );

		// Register the fields for saving.
		add_filter( 'wpseo_save_metaboxes', [ $this, 'save' ], 10, 1 );

		// Render the fields alongside other hidden inputs.
		add_filter( 'wpseo_content_meta_section_content', [ $this, 'add_news_fields_to_the_content' ] );
		add_filter( 'wpseo_elementor_hidden_fields', [ $this, 'add_news_fields_to_the_content' ] );

		// Register the meta box tab.
		add_filter( 'yoast_free_additional_metabox_sections', [ $this, 'add_metabox_section' ] );
		}
	}

	/**
	 * The metaboxes to display and save for the tab.
	 *
	 * @param string $post_type The post type to get metaboxes for. Unused in this implementation.
	 *
	 * @return array[] Multi-level array with information on each metabox to display.
	 */
	public function get_meta_boxes( $post_type = 'post' ) {
		return [
			'newssitemap-exclude'      => [
				'name'  => 'newssitemap-exclude',
				'type'  => 'checkbox',
				'std'   => 'on',
				'title' => __( 'News Sitemap', 'wordpress-seo-news' ),
				'expl'  => __( 'Exclude from News Sitemap', 'wordpress-seo-news' ),
			],
			'newssitemap-stocktickers' => [
				'name'        => 'newssitemap-stocktickers',
				'std'         => '',
				'type'        => 'text',
				'title'       => __( 'Stock Tickers', 'wordpress-seo-news' ),
				'description' => __( 'A comma-separated list of up to 5 stock tickers of the companies, mutual funds, or other financial entities that are the main subject of the article. Each ticker must be prefixed by the name of its stock exchange, and must match its entry in Google Finance. For example, "NASDAQ:AMAT" (but not "NASD:AMAT"), or "BOM:500325" (but not "BOM:RIL").', 'wordpress-seo-news' ),
			],
			'newssitemap-robots-index' => [
				'type'          => 'radio',
				'default_value' => '0', // The default value will be 'index'; See the list of options.
				'std'           => '',
				'options'       => [
					'0' => 'index',
					'1' => 'noindex',
				],
				'title'         => __( 'Googlebot-News index', 'wordpress-seo-news' ),
				'description'   => __( 'Using noindex allows you to prevent articles from appearing in Google News.', 'wordpress-seo-news' ),
			],
		];
	}

	/**
	 * Add the meta boxes to meta box array so they get saved.
	 *
	 * @param array $meta_boxes The metaboxes to save.
	 *
	 * @return array
	 */
	public function save( $meta_boxes ) {
		// When action is inline-save there is nothing to save for seo news.
		if ( filter_input( INPUT_POST, 'action' ) !== 'inline-save' ) {
			$meta_boxes = array_merge( $meta_boxes, $this->get_meta_boxes() );
		}

		return $meta_boxes;
	}

	/**
	 * Add WordPress SEO meta fields to WPSEO meta class.
	 *
	 * @param array $meta_fields The meta fields to extend.
	 *
	 * @return array
	 */
	public function add_meta_fields_to_wpseo_meta( $meta_fields ) {
		$meta_fields['news'] = $this->get_meta_boxes();

		return $meta_fields;
	}

	/**
	 * Adds a news section to the metabox sections array.
	 *
	 * @param array $sections The sections to add to.
	 *
	 * @return array
	 */
	public function add_metabox_section( $sections ) {
		if ( ! $this->is_post_type_supported() ) {
			return $sections;
		}

		$content = '';
		foreach ( $this->get_meta_boxes() as $meta_key => $meta_box ) {
			$content .= $this->do_meta_box( $meta_box, $meta_key );
		}

		$sections[] = [
			'name'         => 'news',
			'link_content' => '<span class="dashicons dashicons-admin-plugins"></span>' . esc_html__( 'Google News', 'wordpress-seo-news' ),
			'content'      => '<div class="wpseo-meta-section-content">' . $content . '</div>',
		];

		return $sections;
	}

	/**
	 * Adds the News meta fields to the content.
	 *
	 * @param string $content The content.
	 *
	 * @return string The content with the rendered News meta fields.
	 */
	public function add_news_fields_to_the_content( $content ) {
		return $content . new Meta_Fields_Presenter( $this->get_metabox_post(), 'news' );
	}

	/**
	 * Check if current post_type is supported.
	 *
	 * @return bool
	 */
	protected function is_post_type_supported() {
		static $is_supported;

		if ( $is_supported === null ) {
			// Default is false.
			$is_supported = false;

			$post = $this->get_metabox_post();

			if ( is_a( $post, 'WP_Post' ) ) {
				// Get supported post types.
				$post_types = WPSEO_News::get_included_post_types();

				// Display content if post type is supported.
				if ( ! empty( $post_types ) && in_array( $post->post_type, $post_types, true ) ) {
					$is_supported = true;
				}
			}
		}

		return $is_supported;
	}

	/* ********************* DEPRECATED METHODS ********************* */

	/**
	 * Only add the tab header and content actions when the post is supported.
	 *
	 * @deprecated 12.7
	 * @codeCoverageIgnore
	 */
	public function add_tab_hooks() {
		_deprecated_function( __METHOD__, 'WPSEO News 12.7' );

		if ( $this->is_post_type_supported() ) {
			add_filter( 'yoast_free_additional_metabox_sections', [ $this, 'add_metabox_section' ] );
		}
	}

	/**
	 * The tab header.
	 *
	 * @deprecated 11.9
	 * @codeCoverageIgnore
	 */
	public function header() {
		_deprecated_function( __METHOD__, '11.9' );
	}

	/**
	 * The tab content.
	 *
	 * @deprecated 11.9
	 * @codeCoverageIgnore
	 */
	public function content() {
		_deprecated_function( __METHOD__, '11.9' );
	}
}
