<?php
/**
 * @package News
 */

/**
 * Implements the helpscout beacon suggestions for wpseo news
 */
class WPSEO_News_Beacon_Setting implements Yoast_HelpScout_Beacon_Setting {
	/**
	 * {@inheritdoc}
	 */
	public function get_suggestions( $page ) {
		switch ( $page ) {
			case 'wpseo_news':
				return array();
				break;
		}

		return array();
	}

	/**
	 * Returns a product for a a certain admin page.
	 *
	 * @param string $page The current admin page we are on.
	 *
	 * @return Yoast_Product[] A product to use for sending data to helpscout
	 */
	public function get_products( $page ) {
		switch ( $page ) {
			case 'wpseo_news':
				return array( new WPSEO_News_Product() );
				break;
		}

		return array();
	}
}
