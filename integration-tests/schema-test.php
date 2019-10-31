<?php
/**
 * Yoast SEO: News plugin test file.
 *
 * @package WPSEO_News\Tests
 */

/**
 * Class WPSEO_News_Schema_Test.
 */
class WPSEO_News_Schema_Test extends WPSEO_News_UnitTestCase {

	/**
	 * Holds the instance of the class being tested.
	 *
	 * @var WPSEO_News_Schema_Double
	 */
	private $default_mock;

	/**
	 * Holds the current date.
	 *
	 * @var DateTime
	 */
	private $date;

	/**
	 * Setting up the instance of WPSEO_News_Schema.
	 */
	public function setUp() {
		parent::setUp();

		$this->date  = date_create();
		$date_string = $this->date->format( DateTime::W3C );

		$this->default_mock = $this
			->getMockBuilder( 'WPSEO_News_Schema_Double' )
			->setMethods( array( 'get_post' ) )
			->getMock();

		$this->default_mock
			->method( 'get_post' )
			->will(
				$this->returnValue(
					self::factory()->post->create_and_get(
						array(
							'post_title'    => 'Newest post',
							'post_date'     => $date_string,
							'post_date_gmt' => $date_string,
							'post_type'     => 'post',
						)
					)
				)
			);
	}

	/**
	 * Tests whether the article post types includes `post` by default.
	 *
	 * @covers WPSEO_News_Schema::article_post_types
	 * @covers WPSEO_News_Schema::is_post_excluded
	 * @covers WPSEO_News::is_excluded_through_sitemap
	 * @covers WPSEO_News::is_excluded_through_terms
	 * @covers WPSEO_News::get_terms_for_post
	 */
	public function test_article_post_types() {
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$actual = $this->default_mock->article_post_types( array() );

		$this->assertEquals( array( 'post' ), $actual );
	}

	/**
	 * Tests whether the article post types does not add when the post is excluded through a term.
	 *
	 * @covers WPSEO_News_Schema::article_post_types
	 * @covers WPSEO_News_Schema::is_post_excluded
	 * @covers WPSEO_News::is_excluded_through_sitemap
	 * @covers WPSEO_News::is_excluded_through_terms
	 * @covers WPSEO_News::get_terms_for_post
	 */
	public function test_article_post_types_with_excluded_term() {
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		// Add the term exclusion in the options.
		add_filter( 'wpseo_news_options', array( $this, 'filter_options_exclude_uncategorized' ) );

		$actual = $this->default_mock->article_post_types( array() );

		$this->assertEquals( array(), $actual );
	}

	/**
	 * Tests whether the @type in the schema is correctly changed to NewsArticle.
	 *
	 * @covers WPSEO_News_Schema::change_article
	 * @covers WPSEO_News_Schema::is_post_excluded
	 * @covers WPSEO_News::is_excluded_through_sitemap
	 * @covers WPSEO_News::is_excluded_through_terms
	 * @covers WPSEO_News::get_terms_for_post
	 */
	public function test_change_article() {
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$expected = array(
			'@type'           => 'NewsArticle',
			'copyrightYear'   => $this->date->format( 'Y' ),
			'copyrightHolder' => array(
				'@id' => 'http://example.org/#organization',
			),
		);
		$actual   = $this->default_mock->change_article( array() );

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Tests whether the schema output is generated correctly if one of the terms that is
	 * attached to a post is excluded from the news sitemap.
	 *
	 * @covers WPSEO_News_Schema::change_article
	 * @covers WPSEO_News_Schema::is_post_excluded
	 * @covers WPSEO_News::is_excluded_through_sitemap
	 * @covers WPSEO_News::is_excluded_through_terms
	 * @covers WPSEO_News::get_terms_for_post
	 */
	public function test_change_article_with_an_excluded_term() {
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		// Add the term exclusion in the options.
		add_filter( 'wpseo_news_options', array( $this, 'filter_options_exclude_uncategorized' ) );

		/*
		 * Note there is no `@type` expected here. This is because we do not __override__ it.
		 * Yoast SEO is setting the default of `Article` in the output of the actual page.
		 */
		$expected = array(
			'copyrightYear'   => $this->date->format( 'Y' ),
			'copyrightHolder' => array(
				'@id' => 'http://example.org/#organization',
			),
		);
		$actual   = $this->default_mock->change_article( array() );

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Adds the options to exclude uncategorized posts. This is a filter function.
	 *
	 * @param array $options The options that get passed through the filter.
	 *
	 * @return array $options
	 */
	public function filter_options_exclude_uncategorized( $options ) {
		$options['term_exclude_category_uncategorized_for_post'] = true;
		return $options;
	}
}
