<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News\XML_Sitemaps
 */

use Yoast\WP\SEO\Models\Indexable;

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
	 * @var Indexable
	 */
	private $item;

	/**
	 * Setting properties and build the item.
	 *
	 * @param Indexable $item    The post.
	 * @param null      $options Deprecated. The options.
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
		$skip_build_item = false;

		if ( WPSEO_News::is_excluded_through_sitemap( $this->item->object_id ) ) {
			$skip_build_item = true;
		}

		if ( WPSEO_News::is_excluded_through_terms( $this->item->ID, $this->item->object_sub_type ) ) {
			$skip_build_item = true;
		}

		/**
		 * Filter: 'Yoast\WP\News\skip_build_item' - Allow override of decision to skip adding this item to the news sitemap.
		 *
		 * @param bool   $skip_build_item Whether this item should be built for the sitemap.
		 * @param string $item_id         ID of the current item to be skipped or not.
		 *
		 * @since 12.8.0
		 */
		$skip_build_item = apply_filters( 'Yoast\WP\News\skip_build_item', $skip_build_item, $this->item->object_id );

		return is_bool( $skip_build_item ) && $skip_build_item;
	}

	/**
	 * Building each sitemap item.
	 */
	private function build_item() {
		$this->output .= '<url>' . "\n";
		$this->output .= "\t<loc>" . $this->item->permalink . '</loc>' . "\n";

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

		$stock_tickers = $this->get_item_stock_tickers( $this->item->object_id );

		$this->output .= "\t<news:news>\n";

		// Build the publication tag.
		$this->build_publication_tag();

		$this->output .= "\t\t<news:publication_date>" . $this->date->format( $this->item->object_published_at ) . '</news:publication_date>' . "\n";
		$this->output .= "\t\t<news:title><![CDATA[" . $this->item->title . ']]></news:title>' . "\n";

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
	 * Getting the publication language.
	 *
	 * @return string Publication language.
	 */
	private function get_publication_lang() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WPSEO hook.
		$locale = apply_filters( 'wpseo_locale', get_locale() );

		// Fallback to 'en', if the length of the locale is less than 2 characters.
		if ( strlen( $locale ) < 2 ) {
			$locale = 'en';
		}

		return substr( $locale, 0, 2 );
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
