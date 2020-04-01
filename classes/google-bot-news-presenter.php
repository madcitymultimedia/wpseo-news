<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News
 */

use Yoast\WP\SEO\Presentations\Indexable_Presentation;

/**
 * Represents the Googlebot-News tag presenter.
 */
class WPSEO_News_Google_Bot_News_Presenter {

	/**
	 * Renders the Googlebot-News noindex tag when applicable.
	 *
	 * @param Indexable_Presentation $presentation Presentation to use.
	 *
	 * @return string The rendered meta tag.
	 */
	public function present( Indexable_Presentation $presentation ) {
		if ( $presentation->model->object_type !== 'post' ) {
			return '';
		}

		/**
		 * Allow for running additional code before adding the News header tags.
		 *
		 * @deprecated 12.5.0 Use the {@see 'Yoast\WP\News\head'} action instead.
		 */
		do_action_deprecated( 'wpseo_news_head', [], 'YoastSEO News 12.5.0', 'Yoast\WP\News\head' );

		/**
		 * Allow for running additional code before adding the News header tags.
		 *
		 * @since 12.5.0
		 */
		do_action( 'Yoast\WP\News\head' );

		if ( $this->display_noindex( $presentation->source ) ) {
			return '<meta name="Googlebot-News" content="noindex" />' . PHP_EOL;
		}

		return '';
	}

	/**
	 * Shows the meta-tag with noindex when it has been decided to exclude the post from Google News.
	 *
	 * @see https://support.google.com/news/publisher/answer/93977?hl=en
	 *
	 * @param WP_Post|array $post The post object.
	 *
	 * @return bool True when noindex tag should be rendered.
	 */
	private function display_noindex( $post ) {
		/**
		 * Filter: 'wpseo_news_head_display_noindex' - Allow preventing of outputting noindex tag.
		 *
		 * @param object $post The post.
		 *
		 * @api        string $meta_robots The noindex tag.
		 *
		 * @deprecated 12.5.0. Use the {@see 'Yoast\WP\News\head_display_noindex'} filter instead.
		 */
		$display_noindex = apply_filters_deprecated(
			'wpseo_news_head_display_noindex',
			[ true, $post ],
			'YoastSEO News 12.5.0',
			'Yoast\WP\News\head_display_noindex'
		);

		/**
		 * Filter: 'Yoast\WP\News\head_display_noindex' - Allow preventing of outputting noindex tag.
		 *
		 * @param object $post The post.
		 *
		 * @api   string $meta_robots The noindex tag.
		 *
		 * @since 12.5.0
		 */
		$display_noindex = apply_filters( 'Yoast\WP\News\head_display_noindex', $display_noindex, $post );

		if ( empty( $display_noindex ) ) {
			return false;
		}

		$robots_index = WPSEO_Meta::get_value( 'newssitemap-robots-index', $post->ID );

		return ! empty( $robots_index );
	}
}
