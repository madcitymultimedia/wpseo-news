<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News\XML_Sitemaps
 */

/**
 * The News Sitemap entry.
 */
class WPSEO_News_Sitemap_Item {

	/**
	 * The date helper.
	 *
	 * @var WPSEO_Date_Helper
	 */
	protected $date;

	/**
	 * The output which will be returned.
	 *
	 * @var string
	 */
	private $output = '';

	/**
	 * The current item.
	 *
	 * @var object
	 */
	private $item;

	/**
	 * Setting properties and build the item.
	 *
	 * @param object $item    The post.
	 * @param null   $options Deprecated. The options.
	 */
	public function __construct( $item, $options = null ) {
		if ( $options !== null ) {
			_deprecated_argument( __METHOD__, 'WPSEO News: 12.4', 'The options argument is deprecated' );
		}

		$this->item = $item;
		$this->date = new WPSEO_Date_Helper();

		// Check if item should be skipped.
		if ( ! $this->skip_build_item() ) {
			$this->build_item();
		}
	}

	/**
	 * Return the output, because the object is converted to a string.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->output;
	}

	/**
	 * Determines if the item has to be skipped or not.
	 *
	 * @return bool True if the item has to be skipped.
	 */
	private function skip_build_item() {
		if ( WPSEO_News::is_excluded_through_sitemap( $this->item->ID ) ) {
			return true;
		}

		$item_noindex = WPSEO_Meta::get_value( 'meta-robots-noindex', $this->item->ID );

		if ( $item_noindex === '1' ) {
			return true;
		}

		if ( $item_noindex === '0' && WPSEO_Options::get( 'noindex-' . $this->item->post_type ) === true ) {
			return true;
		}

		// Check the specific WordPress SEO News no-index value.
		if ( WPSEO_Meta::get_value( 'newssitemap-robots-index', $this->item->ID ) === '1' ) {
			return true;
		}

		if ( WPSEO_News::is_excluded_through_terms( $this->item->ID, $this->item->post_type ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Building each sitemap item.
	 */
	private function build_item() {
		$this->item->post_status = 'publish';

		$this->output .= '<url>' . "\n";
		$this->output .= "\t<loc>" . get_permalink( $this->item ) . '</loc>' . "\n";

		// Building the news_tag.
		$this->build_news_tag();

		// Getting the images for this item.
		$this->get_item_images();

		$this->output .= '</url>' . "\n";
	}

	/**
	 * Building the news tag.
	 */
	private function build_news_tag() {

		$title         = $this->get_item_title( $this->item );
		$genre         = $this->get_item_genre();
		$stock_tickers = $this->get_item_stock_tickers( $this->item->ID );

		$this->output .= "\t<news:news>\n";

		// Build the publication tag.
		$this->build_publication_tag();

		if ( ! empty( $genre ) ) {
			$this->output .= "\t\t<news:genres><![CDATA[" . $genre . ']]></news:genres>' . "\n";
		}

		$this->output .= "\t\t<news:publication_date>" . $this->get_publication_date( $this->item ) . '</news:publication_date>' . "\n";
		$this->output .= "\t\t<news:title><![CDATA[" . $title . ']]></news:title>' . "\n";

		if ( ! empty( $stock_tickers ) ) {
			$this->output .= "\t\t<news:stock_tickers><![CDATA[" . $stock_tickers . ']]></news:stock_tickers>' . "\n";
		}

		$this->output .= "\t</news:news>\n";
	}

	/**
	 * Builds the publication tag.
	 */
	private function build_publication_tag() {
		$publication_name = WPSEO_Options::get( 'news_sitemap_name', get_bloginfo( 'name' ) );
		$publication_lang = $this->get_publication_lang();

		$this->output .= "\t\t<news:publication>\n";
		$this->output .= "\t\t\t<news:name>" . $publication_name . '</news:name>' . "\n";
		$this->output .= "\t\t\t<news:language>" . htmlspecialchars( $publication_lang, ENT_COMPAT, get_bloginfo( 'charset' ), false ) . '</news:language>' . "\n";
		$this->output .= "\t\t</news:publication>\n";
	}

	/**
	 * Gets the SEO title of the item, with a fallback to the item title.
	 *
	 * @param WP_Post $item The post object.
	 *
	 * @return string The formatted title or, if no formatted title can be created, the post_title.
	 */
	protected function get_item_title( $item = null ) {
		// Exit early if the item is null.
		if ( $item === null ) {
			return '';
		}

		return $item->post_title;
	}

	/**
	 * Getting the genre for given $item_id.
	 *
	 * @return string
	 */
	private function get_item_genre() {
		$genre = WPSEO_Meta::get_value( 'newssitemap-genre', $this->item->ID );
		if ( is_array( $genre ) ) {
			$genre = implode( ',', $genre );
		}

		$default_genre = WPSEO_Options::get( 'news_sitemap_default_genre' );
		if ( $genre === '' && $default_genre ) {
			$genre = is_array( $default_genre ) ? implode( ',', $default_genre ) : $default_genre;
		}

		$genre = trim( preg_replace( '/^none,?/', '', $genre ) );

		return $genre;
	}

	/**
	 * Getting the publication language.
	 *
	 * @return string
	 */
	private function get_publication_lang() {
		$locale = apply_filters( 'wpseo_locale', get_locale() );

		// Fallback to 'en', if the length of the locale is less than 2 characters.
		if ( strlen( $locale ) < 2 ) {
			$locale = 'en';
		}

		$publication_lang = substr( $locale, 0, 2 );

		return $publication_lang;
	}

	/**
	 * Parses the $item argument into an xml format.
	 *
	 * @param WP_Post $item Object to get data from.
	 *
	 * @return string
	 */
	protected function get_publication_date( $item ) {
		if ( $this->date->is_valid_datetime( $item->post_date_gmt ) ) {
			return $this->date->format( $item->post_date_gmt );
		}

		return '';
	}

	/**
	 * Getting the stock_tickers for given $item_id.
	 *
	 * @param int $item_id Item to get ticker from.
	 *
	 * @return string
	 */
	private function get_item_stock_tickers( $item_id ) {
		$stock_tickers = explode( ',', trim( WPSEO_Meta::get_value( 'newssitemap-stocktickers', $item_id ) ) );
		$stock_tickers = trim( implode( ', ', $stock_tickers ), ', ' );

		return $stock_tickers;
	}

	/**
	 * Getting the images for current item.
	 */
	private function get_item_images() {
		$this->output .= new WPSEO_News_Sitemap_Images( $this->item );
	}
}
