<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News
 */

if ( ! class_exists( 'WPSEO_News_Product', false ) && class_exists( 'Yoast_Product' ) ) {

	/**
	 * Class WPSEO_News_Product.
	 */
	class WPSEO_News_Product extends Yoast_Product {

		/**
		 * Constructor for the product.
		 */
		public function __construct() {
			$file = plugin_basename( WPSEO_NEWS_FILE );
			$slug = dirname( $file );

			parent::__construct(
				'https://my.yoast.com/edd-sl-api',
				'News SEO',
				$slug,
				WPSEO_News::VERSION,
				WPSEO_Shortlinker::get( 'https://yoa.st/4fg' ),
				'admin.php?page=wpseo_licenses#top#licenses',
				'wordpress-seo-news',
				'Yoast',
				$file
			);
		}
	}
}
