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

		$this->date = date_create();

		$this->default_mock = $this
			->getMockBuilder( 'WPSEO_News_Schema_Double' )
			->setMethods( [ 'get_post', 'is_post_excluded' ] )
			->getMock();
	}

	/**
	 * Tests whether the article post types includes `post` by default.
	 *
	 * @covers WPSEO_News_Schema::article_post_types
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_article_post_types() {
		$this->set_post_mock();
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$actual = $this->default_mock->article_post_types( [] );

		$this->assertSame( [ 'post' ], $actual );
	}

	/**
	 * Tests whether the article post types does not add when the post is excluded through a term.
	 *
	 * @covers WPSEO_News_Schema::article_post_types
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_article_post_types_with_excluded_term() {
		$this->set_post_mock();
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->default_mock
			->expects( $this->once() )
			->method( 'is_post_excluded' )
			->willReturn( true );

		$actual = $this->default_mock->article_post_types( [] );

		$this->assertSame( [], $actual );
	}

	/**
	 * Tests the copyright information.
	 *
	 * @covers WPSEO_News_Schema::add_copyright_information
	 * @covers WPSEO_News_Schema::is_post_type_included
	 */
	public function test_add_copyright_information() {
		$this->set_post_mock();
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->assertSame(
			[
				'copyrightYear'   => $this->date->format( 'Y' ),
				'copyrightHolder' => [
					'@id' => 'http://example.org/#organization',
				],
			],
			$this->default_mock->add_copyright_information( [] )
		);
	}

	/**
	 * Tests no copyright information is added when the post type is not included.
	 *
	 * @covers WPSEO_News_Schema::add_copyright_information
	 * @covers WPSEO_News_Schema::is_post_type_included
	 */
	public function test_add_copyright_information_post_type_not_included() {
		$this->set_post_mock( 'other_post_type' );
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->assertSame(
			[],
			$this->default_mock->add_copyright_information( [] )
		);
	}

	/**
	 * Tests the news article type.
	 *
	 * @covers WPSEO_News_Schema::add_news_article_type
	 * @covers WPSEO_News_Schema::is_post_type_included
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_add_news_article_type() {
		$this->set_post_mock();
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->default_mock
			->expects( $this->once() )
			->method( 'is_post_excluded' )
			->willReturn( false );

		$this->assertSame(
			[ 'Article', 'NewsArticle' ],
			$this->default_mock->add_news_article_type( [ 'Article' ] )
		);
	}

	/**
	 * Tests that news article type changes None to Article.
	 *
	 * @covers WPSEO_News_Schema::add_news_article_type
	 * @covers WPSEO_News_Schema::is_post_type_included
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_add_news_article_type_change_none() {
		$this->set_post_mock();
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->default_mock
			->expects( $this->once() )
			->method( 'is_post_excluded' )
			->willReturn( false );

		$this->assertSame(
			[ 'Article', 'NewsArticle' ],
			$this->default_mock->add_news_article_type( [ 'None' ] )
		);
	}

	/**
	 * Tests that news article type handling type as string.
	 *
	 * @covers WPSEO_News_Schema::add_news_article_type
	 * @covers WPSEO_News_Schema::is_post_type_included
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_add_news_article_type_handle_string() {
		$this->set_post_mock();
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->default_mock
			->expects( $this->once() )
			->method( 'is_post_excluded' )
			->willReturn( false );

		$this->assertSame(
			[ 'Article', 'NewsArticle' ],
			$this->default_mock->add_news_article_type( 'None' )
		);
	}

	/**
	 * Tests that news article type is not changed when the post type is not included.
	 *
	 * @covers WPSEO_News_Schema::add_news_article_type
	 * @covers WPSEO_News_Schema::is_post_type_included
	 */
	public function test_add_news_article_type_post_type_not_included() {
		$this->set_post_mock( 'other_post_type' );
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->assertSame(
			'None',
			$this->default_mock->add_news_article_type( 'None' )
		);
	}

	/**
	 * Tests that news article type is not changed when the post is excluded.
	 *
	 * @covers WPSEO_News_Schema::add_news_article_type
	 * @covers WPSEO_News_Schema::is_post_type_included
	 * @covers WPSEO_News_Schema::is_post_excluded
	 */
	public function test_add_news_article_type_post_excluded() {
		$this->set_post_mock();
		$this->default_mock
			->expects( $this->once() )
			->method( 'get_post' );

		$this->default_mock
			->expects( $this->once() )
			->method( 'is_post_excluded' )
			->willReturn( true );

		$this->assertSame(
			'None',
			$this->default_mock->add_news_article_type( 'None' )
		);
	}

	/**
	 * Mocks `get_post` for ease of testing.
	 *
	 * @param string $post_type The post type for the mocked post.
	 */
	protected function set_post_mock( $post_type = 'post' ) {
		$date_string = $this->date->format( DateTime::W3C );

		$this->default_mock
			->method( 'get_post' )
			->will(
				$this->returnValue(
					self::factory()->post->create_and_get(
						[
							'post_title'    => 'Newest post',
							'post_date'     => $date_string,
							'post_date_gmt' => $date_string,
							'post_type'     => $post_type,
						]
					)
				)
			);
	}
}
