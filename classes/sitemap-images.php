<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News\XML_Sitemaps
 */

use Yoast\WP\SEO\Models\Indexable;

/**
 * Handle images used in News.
 */
class WPSEO_News_Sitemap_Images {

	/**
	 * The current item.
	 *
	 * @var Indexable
	 */
	private $item;

	/**
	 * The output that will be returned.
	 *
	 * @var string
	 */
	private $output = '';

	/**
	 * Storage for the images.
	 *
	 * @var array
	 */
	private $images;

	/**
	 * Setting properties and build the item.
	 *
	 * @param Indexable $item    News post Indexable.
	 * @param null      $options Deprecated. The options.
	 */
	public function __construct( $item, $options = null ) {
		if ( $options !== null ) {
			_deprecated_argument( __METHOD__, 'WPSEO News: 12.4', 'The options argument is deprecated' );
		}

		$this->item = $item;

		$this->parse_item_images();
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
	 * Parsing the images from the item.
	 */
	private function parse_item_images() {
		$encoding = get_bloginfo( 'charset' );

		$this->get_item_images();

		if ( ! empty( $this->images ) ) {
			foreach ( $this->images as $image ) {
				$this->output .= "\t<image:image>\n";
				$this->output .= "\t\t<image:loc>" . htmlspecialchars( $image->url, ENT_COMPAT, $encoding, false ) . "</image:loc>\n";
				// Ideally we'd want to output the image's alt here.
				$this->output .= "\t</image:image>\n";
			}
		}
	}

	/**
	 * Getting the images for the given $item.
	 */
	private function get_item_images() {
		$links_repository = YoastSEO()->classes->get( 'Yoast\WP\SEO\Repositories\SEO_Links_Repository' );
		$this->images = $links_repository->query()
			->select( 'url' )
			->where( 'indexable_id', $this->item->id )
			->where( 'type', 'image-in' )
			->find_many();

		if ( has_post_thumbnail( $this->item->object_id ) ) {
			$featured_image = (object) [
				'url' => get_the_post_thumbnail_url( $this->item->object_id )
			];
			$this->images[] = $featured_image;
		}
	}
}
