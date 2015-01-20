<?php

class WPSEO_News_Sitemap_Editors_Pick {

	/**
	 * Store the editors pick
	 *
	 * @var array
	 */
	private $items;

	/**
	 * Construct the Class-Sitemap-Editors-Pick rss feed generator. We set the WP Seo options and we
	 * find the editors pick items and store them in the $items var
	 */
	public function __construct() {
		$this->prepare_items();
	}

	/**
	 * Prepare RSS feed data
	 */
	private function prepare_items() {
		$this->items = array();

		// Remove the wptexturize filter
		remove_filter( 'the_title', 'wptexturize' );
		remove_filter( 'the_content', 'wptexturize' );

		// EP Query
		$ep_query = new WP_Query(
			array(
				'post_type'   => WPSEO_News::get_included_post_types(),
				'post_status' => 'publish',
				'meta_query'  => array(
					array(
						'key'   => '_yoast_wpseo_newssitemap-editors-pick',
						'value' => 'on',
					),
				),
				'order'       => 'DESC',
				'orderby'     => 'date',
			)
		);

		// The Loop
		if ( $ep_query->have_posts() ) {
			while ( $ep_query->have_posts() ) {
				$ep_query->the_post();
				$this->items[] = array(
					'title'        => get_the_title(),
					'link'         => get_permalink(),
					'description'  => get_the_excerpt(),
					'creator'      => get_the_author_meta( 'display_name' ),
					'published_on' => get_the_date( DATE_RFC822 ),
				);
			}
		}

		/* Restore original Post Data */
		wp_reset_postdata();
	}

	/**
	 * Generate the Editors' Pick URL
	 */
	public function generate_rss() {

		$options = WPSEO_News::get_options();

		// Show output as XML
		header( 'Content-Type: application/rss+xml; charset=' . get_bloginfo( 'charset' ) );

		echo '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '" ?>' . PHP_EOL;
		echo '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">' . PHP_EOL;
		echo '<channel>' . PHP_EOL;

		// Atom channel elements
		echo '<atom:link href="' . get_site_url() . '/editors-pick.rss" rel="self" type="application/rss+xml" />' . PHP_EOL;

		// Display the main channel tags
		echo '<link>' . get_site_url() . '</link>' . PHP_EOL;
		echo '<description>' . get_bloginfo( 'description' ) . '</description>' . PHP_EOL;
		echo '<title>' . get_bloginfo( 'name' ) . '</title>' . PHP_EOL;

		// Display the image tag if an image is set
		if ( isset( $options['ep_image_src'] ) && $options['ep_image_src'] != '' ) {
			echo '<image>' . PHP_EOL;
			echo '<url>' . $options['ep_image_src'] . '</url>' . PHP_EOL;
			echo '<title>' . get_bloginfo( 'name' ) . '</title>' . PHP_EOL;
			echo '<link>' . get_site_url() . '</link>' . PHP_EOL;
			echo '</image>' . PHP_EOL;
		}

		// Display the items
		if ( count( $this->items ) > 0 ) {
			foreach ( $this->items as $item ) {
				$this->show_item( $item );
			}
		}


		echo '</channel>' . PHP_EOL;
		echo '</rss>' . PHP_EOL;

	}

	/**
	 * Showing item as XML
	 *
	 * @param array $item
	 */
	private function show_item( $item ) {
		echo '<item>' . PHP_EOL;
		echo '<title><![CDATA[' . $item['title'] . ']]></title>' . PHP_EOL;
		echo '<guid isPermaLink="true">' . $item['link'] . '</guid>' . PHP_EOL;
		echo '<link>' . $item['link'] . '</link>' . PHP_EOL;
		echo '<description><![CDATA[' . $item['description'] . ']]></description>' . PHP_EOL;
		echo '<dc:creator><![CDATA[' . $item['creator'] . ']]></dc:creator>' . PHP_EOL;
		echo '<pubDate>' . $item['published_on'] . '</pubDate>' . PHP_EOL;
		echo '</item>' . PHP_EOL;
	}

}