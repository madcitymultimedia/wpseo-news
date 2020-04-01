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
	 * WPSEO_News_Head Constructor.
	 *
	 * @deprecated 12.5
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		_deprecated_function( __METHOD__, 'WPSEO News 12.5', Google_Bot_News_Presenter::class );
	}

	/**
	 * Display the optional sources link elements in the <code>&lt;head&gt;</code>.
	 *
	 * @deprecated 12.5
	 * @codeCoverageIgnore
	 */
	public function add_head_tags() {
		_deprecated_function( __METHOD__, 'WPSEO News 12.5' );
	}
}
