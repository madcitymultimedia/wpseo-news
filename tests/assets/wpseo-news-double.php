<?php
/**
 * @package WPSEO\Tests
 */

/**
 * Class WPSEO_News_Test
 */
class WPSEO_News_Double extends WPSEO_News {
	/**
	 * @inheritdoc
	 */
	public function check_dependencies( $wp_version ) {
		return parent::check_dependencies( $wp_version );
	}
}
