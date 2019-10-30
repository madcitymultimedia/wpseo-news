<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News
 */

/**
 * Represents the frontend head.
 */
class WPSEO_News_Head {

	/**
	 * Holder for post-data.
	 *
	 * @var object
	 */
	private $post;

	/**
	 * WPSEO_News_Head Constructor.
	 */
	public function __construct() {
		/**
		 * Allow for running additional code before adding the News header tags.
		 *
		 * @deprecated 12.5.0 Use the {@see 'Yoast\WP\News\head'} action instead.
		 */
		do_action_deprecated( 'wpseo_news_head', array(), 'YoastSEO News 12.5.0', 'Yoast\WP\News\head' );

		/**
		 * Allow for running additional code before adding the News header tags.
		 *
		 * @since 12.5.0
		 */
		do_action( 'Yoast\WP\News\head' );

		add_action( 'wpseo_head', array( $this, 'add_head_tags' ) );
	}

	/**
	 * Display the optional sources link elements in the <code>&lt;head&gt;</code>.
	 */
	public function add_head_tags() {
		if ( is_singular() ) {
			global $post;

			$this->post = $post;

			$this->display_noindex();
		}
	}

	/**
	 * Shows the meta-tag with noindex when it has been decided to exclude the post from Google News.
	 *
	 * @see https://support.google.com/news/publisher/answer/93977?hl=en
	 */
	private function display_noindex() {
		/**
		 * Filter: 'wpseo_news_head_display_noindex' - Allow preventing of outputting noindex tag.
		 *
		 * @api string $meta_robots The noindex tag.
		 *
		 * @param object $post The post.
		 */
		if ( apply_filters( 'wpseo_news_head_display_noindex', true, $this->post ) ) {
			$robots_index = WPSEO_Meta::get_value( 'newssitemap-robots-index', $this->post->ID );
			if ( ! empty( $robots_index ) ) {
				echo '<meta name="Googlebot-News" content="noindex" />' . "\n";
			}
		}
	}
}
