<?php

namespace Yoast\WP\News\Tests\Doubles;

use WPSEO_News_Option;

/**
 * Class Option_Double
 */
class Option_Double extends WPSEO_News_Option {

	/**
	 * Expose the constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @inheritDoc
	 */
	public function validate_option( $dirty, $clean, $old ) {
		return parent::validate_option( $dirty, $clean, $old );
	}
}
