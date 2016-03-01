<?php

/**
 * Class WPSEO_News_Product
 */
class WPSEO_News_Product extends Yoast_Product {

	public function __construct() {
		parent::__construct(
			'http://yoast.com/edd-sl-api',
			'News SEO',
			plugin_basename( WPSEO_News::get_file() ),
			WPSEO_News::VERSION,
			'https://yoast.com/wordpress/plugins/news-seo/',
			'admin.php?page=wpseo_licenses#top#licenses',
			'wordpress-seo-news',
			'Yoast'
		);
	}

}
