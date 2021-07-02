<?php

namespace Yoast\WP\News\Tests;

use Brain\Monkey;
use Mockery;
use Yoast\WPTestUtils\BrainMonkey\YoastTestCase;

/**
 * TestCase base class.
 */
abstract class TestCase extends YoastTestCase {

	/**
	 * Test setup.
	 */
	protected function set_up() {
		parent::set_up();

		Monkey\Functions\stubs(
			[
				'is_admin'       => false,
			]
		);

		Monkey\Functions\expect( 'get_option' )
			->zeroOrMoreTimes()
			->with( Mockery::anyOf( 'wpseo', 'wpseo_titles', 'wpseo_taxonomy_meta', 'wpseo_social', 'wpseo_ms' ) )
			->andReturn( [] );

		Monkey\Functions\expect( 'get_site_option' )
			->zeroOrMoreTimes()
			->with( Mockery::anyOf( 'wpseo', 'wpseo_titles', 'wpseo_taxonomy_meta', 'wpseo_social', 'wpseo_ms' ) )
			->andReturn( [] );
	}

    /**
     * Stub the WP native escaping functions.
     *
     * The stubs created by this function return the original input string unchanged.
     *
     * Alternative to the BrainMonkey `Monkey\Functions\stubEscapeFunctions()` function
     * which does apply some form of escaping to the input.
     *
     * @return void
     */
    public function stubEscapeFunctions() {
        Monkey\Functions\stubs(
            [
                'esc_js',
                'esc_sql',
                'esc_attr',
                'esc_html',
                'esc_textarea',
                'esc_url',
                'esc_url_raw',
                'esc_xml',
            ]
        );
    }
}
