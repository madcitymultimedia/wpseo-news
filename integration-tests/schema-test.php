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
			->setMethods( [ 'get_post', 'is_post_excluded' ] )
			->getMock();

		$this->default_mock
			->method( 'get_post' )
			->will(
				$this->returnValue(
					self::factory()->post->create_and_get(
						[
							'post_title'    => 'Newest post',
							'post_date'     => $date_string,
							'post_date_gmt' => $date_string,
							'post_type'     => 'post',
						]
					)
				)
			);
	}

	/**
	 * Tests whether the article post types includes `post` by default.
	 *
	 * @covers WPSEO_News_Schema::article_post_types
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_article_post_types() {
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$actual = $this->default_mock->article_post_types( [] );

		$this->assertEquals( [ 'post' ], $actual );
	}

	/**
	 * Tests whether the article post types does not add when the post is excluded through a term.
	 *
	 * @covers WPSEO_News_Schema::article_post_types
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_article_post_types_with_excluded_term() {
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->default_mock
			->expects( $this->once() )
			->method( 'is_post_excluded' )
			->willReturn( $this->returnValue( true ) );

		$actual = $this->default_mock->article_post_types( [] );

		$this->assertEquals( [], $actual );
	}

	/**
	 * Tests whether the @type in the schema is correctly changed to NewsArticle.
	 *
	 * @covers WPSEO_News_Schema::change_article
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_change_article() {
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$expected = [
			'@type'           => 'NewsArticle',
			'copyrightYear'   => $this->date->format( 'Y' ),
			'copyrightHolder' => [
				'@id' => 'http://example.org/#organization',
			],
		];
		$actual   = $this->default_mock->change_article( [] );

		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Tests whether the schema output is generated correctly if one of the terms that is
	 * attached to a post is excluded from the news sitemap.
	 *
	 * @covers WPSEO_News_Schema::change_article
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_change_article_with_an_excluded_term() {
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->default_mock
			->expects( $this->once() )
			->method( 'is_post_excluded' )
			->willReturn( $this->returnValue( true ) );

		/*
		 * Note there is no `@type` expected here. This is because we do not __override__ it.
		 * Yoast SEO is setting the default of `Article` in the output of the actual page.
		 */
		$expected = [
			'copyrightYear'   => $this->date->format( 'Y' ),
			'copyrightHolder' => [
				'@id' => 'http://example.org/#organization',
			],
		];
		$actual   = $this->default_mock->change_article( [] );

		$this->assertEquals( $expected, $actual );
	}
}
