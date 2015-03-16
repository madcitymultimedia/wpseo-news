<?php

class WPSEO_News_Sitemap {

	private $options;

	public function __construct() {
		$this->options = WPSEO_News::get_options();
	}


	/**
	 * Register the XML News sitemap with the main sitemap class.
	 */
	public function init() {
		if ( isset( $GLOBALS['wpseo_sitemaps'] ) ) {
			$GLOBALS['wpseo_sitemaps']->register_sitemap( 'news', array( $this, 'build' ) );
		}
	}

	/**
	 * Add the XML News Sitemap to the Sitemap Index.
	 *
	 * @param string $str String with Index sitemap content.
	 *
	 * @return string
	 */
	public function add_to_index( $str ) {

		$date = new DateTime( get_lastpostdate( 'gmt' ), new DateTimeZone( $this->wp_get_timezone_string() ) );

		/**
		 * Filter: 'wpseo_news_sitemap_url' - Allow filtering the news sitemap XML URL
		 *
		 * @api string $news_sitemap_xml The news sitemap XML URL
		 */
		$news_sitemap_xml = apply_filters( 'wpseo_news_sitemap_url', home_url( 'news-sitemap.xml' ) );

		$str .= '<sitemap>' . "\n";
		$str .= '<loc>' . $news_sitemap_xml . '</loc>' . "\n";
		$str .= '<lastmod>' . htmlspecialchars( $date->format( 'c' ) ) . '</lastmod>' . "\n";
		$str .= '</sitemap>' . "\n";

		return $str;
	}

	/**
	 * Build the sitemap and push it to the XML Sitemaps Class instance for display.
	 */
	public function build() {

		$post_types = $this->get_post_types();
		$items      = $this->get_items( $post_types );

		$output = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

		// Loop through items
		if ( ! empty( $items ) ) {

			$publication_name = ! empty( $this->options['name'] ) ? $this->options['name'] : get_bloginfo( 'name' );
			$publication_lang = $this->get_publication_lang();

			foreach ( $items as $item ) {
				$item->post_status = 'publish';

				if ( WPSEO_Meta::get_value( 'newssitemap-exclude', $item->ID ) == 'on' ) {
					continue;
				}

				if ( false != WPSEO_Meta::get_value( 'meta-robots', $item->ID ) && strpos( WPSEO_Meta::get_value( 'meta-robots', $item->ID ), 'noindex' ) !== false ) {
					continue;
				}

				if ( 'post' == $item->post_type ) {

					$cats    = get_the_terms( $item->ID, 'category' );
					$exclude = 0;

					foreach ( $cats as $cat ) {
						if ( isset( $this->options[ 'catexclude_' . $cat->slug ] ) ) {
							$exclude ++;
						}
					}

					if ( $exclude >= count( $cats ) ) {
						continue;
					}
				}

				$keywords         = new WPSEO_News_Meta_Keywords( $item->ID );
				$genre            = $this->get_item_genre( $item->ID );
				$stock_tickers    = $this->get_item_stock_tickers( $item->ID );
				$publication_date = $this->get_publication_date( $item );

				$output .= '<url>' . "\n";
				$output .= "\t<loc>" . get_permalink( $item ) . '</loc>' . "\n";
				$output .= "\t<news:news>\n";
				$output .= "\t\t<news:publication>" . "\n";
				$output .= "\t\t\t<news:name><![CDATA[" . htmlspecialchars( $publication_name ) . ']]></news:name>' . "\n";
				$output .= "\t\t\t<news:language>" . htmlspecialchars( $publication_lang ) . '</news:language>' . "\n";
				$output .= "\t\t</news:publication>\n";

				if ( ! empty( $genre ) ) {
					$output .= "\t\t<news:genres><![CDATA[" . htmlspecialchars( $genre ) . ']]></news:genres>' . "\n";
				}

				$output .= empty( $publication_date ) ? '' : "\t\t<news:publication_date>" . $publication_date . '</news:publication_date>' . "\n";
				$output .= "\t\t<news:title><![CDATA[" . htmlspecialchars( $item->post_title ) . ']]></news:title>' . "\n";

				if ( ! empty( $keywords ) ) {
					$output .= "\t\t<news:keywords><![CDATA[" . htmlspecialchars( $keywords ) . ']]></news:keywords>' . "\n";
				}

				if ( ! empty( $stock_tickers ) ) {
					$output .= "\t\t<news:stock_tickers><![CDATA[" . htmlspecialchars( $stock_tickers ) . ']]></news:stock_tickers>' . "\n";
				}

				$output .= "\t</news:news>\n";

				// Get images
				if ( $image_output = $this->get_item_image_output( $item ) ) {
					$output .= $image_output;
				}

				$output .= '</url>' . "\n";
			}
		}

		$output .= '</urlset>';
		$GLOBALS['wpseo_sitemaps']->set_stylesheet( '<?xml-stylesheet type="text/xsl" href="' . preg_replace( '/^http[s]?:/', '', plugin_dir_url( WPSEO_News::get_file() ) ) . 'assets/xml-news-sitemap.xsl"?>' );
		$GLOBALS['wpseo_sitemaps']->set_sitemap( $output );
	}

	/**
	 * Returns the timezone string for a site, even if it's set to a UTC offset
	 *
	 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
	 *
	 * @return string valid PHP timezone string
	 */
	private function wp_get_timezone_string() {

		// if site timezone string exists, return it
		if ( $timezone = get_option( 'timezone_string' ) ) {
			return $timezone;
		}

		// get UTC offset, if it isn't set then return UTC
		if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
			return 'UTC';
		}

		// adjust UTC offset from hours to seconds
		$utc_offset *= 3600;

		// attempt to guess the timezone string from the UTC offset
		$timezone = timezone_name_from_abbr( '', $utc_offset );

		// last try, guess timezone string manually
		if ( false === $timezone ) {

			$is_dst = date( 'I' );

			foreach ( timezone_abbreviations_list() as $abbr ) {
				foreach ( $abbr as $city ) {
					if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset ) {
						return $city['timezone_id'];
					}
				}
			}
		}

		// fallback to UTC
		return 'UTC';
	}

	/**
	 * Getting the publication language
	 *
	 * @return string
	 */
	private function get_publication_lang() {
		$locale = apply_filters( 'wpseo_locale', get_locale() );

		// fallback to 'en', if the length of the locale is less than 2 characters
		if ( strlen( $locale ) < 2 ) {
			$locale = 'en';
		}

		$publication_lang = substr( $locale, 0, 2 );

		return $publication_lang;
	}

	/**
	 * Parses the inputted date into xml format
	 *
	 * @param string $item
	 *
	 * @return string
	 */
	private function get_publication_date( $item ) {

		static $timezone_string;

		if ( $timezone_string == null ) {
			// Get the timezone string
			$timezone_string = $this->wp_get_timezone_string();
		}

		if ( $this->is_valid_datetime( $item->post_date_gmt ) ) {
			// Create a DateTime object date in the correct timezone
			return $this->format_date_from_timzone( $item->post_date_gmt, $timezone_string );
		}
		elseif ( $this->is_valid_datetime( $item->post_modified_gmt ) ) {
			// Fallback 1: post_modified_gmt
			return $this->format_date_from_timzone( $item->post_modified_gmt, $timezone_string );
		}
		elseif ( $this->is_valid_datetime( $item->post_modified ) ) {
			// Fallback 2: post_modified
			return $this->format_date_from_timzone( $item->post_modified, $timezone_string );
		}

		return '';
	}

	/**
	 *
	 *
	 * @param $datetime_string
	 * @param $timezone
	 *
	 * @return string
	 */
	private function format_date_from_timzone( $datetime_string, $timezone ) {
		$datetime = new DateTime( $datetime_string, new DateTimeZone( $timezone ) );

		return $datetime->format( 'c' );
	}

	/**
	 * Get attachment
	 *
	 * @param $attachment_id
	 *
	 * @return array
	 */
	private function get_attachment( $attachment_id ) {
		// Get attachment
		$attachment = get_post( $attachment_id );

		// Check if we've found an attachment
		if ( null == $attachment ) {
			return array();
		}

		// Return props
		return array(
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href'        => get_permalink( $attachment->ID ),
			'src'         => wp_get_attachment_url( $attachment->ID ),
			'title'       => $attachment->post_title,
		);
	}

	/**
	 * Getting the post_types which will be displayed in the sitemap
	 *
	 * @return array|string
	 */
	private function get_post_types() {

		// Get supported post types
		$post_types = WPSEO_News::get_included_post_types();

		if ( count( $post_types ) > 0 ) {
			$post_types = "'" . implode( "','", $post_types ) . "'";
		}

		return $post_types;
	}

	/**
	 * Getting all the items for the sitemap
	 *
	 * @param array $post_types
	 *
	 * @return mixed
	 */
	private function get_items( $post_types ) {
		global $wpdb;

		// Get posts for the last two days only, credit to Alex Moss for this code.
		$items = $wpdb->get_results( "SELECT ID, post_content, post_name, post_author, post_parent, post_date_gmt, post_date, post_date_gmt, post_title, post_type
									FROM $wpdb->posts
									WHERE post_status='publish'
									AND (DATEDIFF(CURDATE(), post_date_gmt)<=2)
									AND post_type IN ($post_types)
									ORDER BY post_date_gmt DESC
									LIMIT 0, 1000" );

		return $items;
	}

	/**
	 * Getting the genre for given $item_id
	 *
	 * @param integer $item_id
	 *
	 * @return string
	 */
	private function get_item_genre( $item_id ) {
		$genre = WPSEO_Meta::get_value( 'newssitemap-genre', $item_id );
		if ( is_array( $genre ) ) {
			$genre = implode( ',', $genre );
		}

		if ( $genre == '' && isset( $this->options['default_genre'] ) && $this->options['default_genre'] != '' ) {
			$genre = $this->options['default_genre'];
		}
		$genre = trim( preg_replace( '/^none,?/', '', $genre ) );

		return $genre;
	}

	/**
	 * Getting the stock_tickers for given $item_id
	 *
	 * @param integer $item_id
	 *
	 * @return string
	 */
	private function get_item_stock_tickers( $item_id ) {
		$stock_tickers = explode( ',', trim( WPSEO_Meta::get_value( 'newssitemap-stocktickers', $item_id ) ) );

		$stock_tickers = trim( implode( ', ', $stock_tickers ), ', ' );

		return $stock_tickers;
	}

	/**
	 * Getting the image output for given $item
	 *
	 * @param object $item
	 *
	 * @return string
	 */
	private function get_item_image_output( $item ) {

		$images = $this->get_item_images( $item );

		if ( isset( $images ) && count( $images ) > 0 ) {
			$output = '';
			foreach ( $images as $src => $img ) {
				$output .= $this->parse_item_image( $src, $img, $item );
			}

			return $output;
		}
	}

	/**
	 * Getting the images for the given $item
	 *
	 * @param object $item
	 *
	 * @return array
	 */
	private function get_item_images( $item ) {

		$images = array();

		if ( ( ! isset( $this->options['restrict_sitemap_featured_img'] ) || ! $this->options['restrict_sitemap_featured_img'] ) && preg_match_all( '/<img [^>]+>/', $item->post_content, $matches ) ) {
			foreach ( $matches[0] as $img ) {
				if ( preg_match( '/src=("|\')([^"|\']+)("|\')/', $img, $match ) ) {
					if ( $src = $this->parse_image_source( $match[2] ) ) {
						$images[ $src ] = $this->parse_image( $img );
					} else {
						continue;
					}
				}
			}
		}

		// Also check if the featured image value is set.
		$post_thumbnail_id = get_post_thumbnail_id( $item->ID );
		if ( '' != $post_thumbnail_id ) {
			$images = $this->get_item_featured_image( $post_thumbnail_id, $images );
		}

		return $images;
	}

	/**
	 * Parsing the image source
	 *
	 * @param string $src
	 *
	 * @return string|void
	 */
	private function parse_image_source( $src ) {

		static $home_url;

		if ( $home_url == null ) {
			$home_url = home_url();
		}

		if ( strpos( $src, 'http' ) !== 0 ) {

			if ( $src[0] != '/' ) {
				return;
			}

			$src = $home_url . $src;
		}

		if ( $src != esc_url( $src ) ) {
			return;
		}

		if ( isset( $url['images'][ $src ] ) ) {
			return;
		}

		return $src;
	}

	/**
	 * Setting title and alt for image and returns them in an array
	 *
	 * @param string $img
	 *
	 * @return array
	 */
	private function parse_image( $img ) {
		$image = array();
		if ( preg_match( '/title=("|\')([^"\']+)("|\')/', $img, $match ) ) {
			$image['title'] = str_replace( array( '-', '_' ), ' ', $match[2] );
		}

		if ( preg_match( '/alt=("|\')([^"\']+)("|\')/', $img, $match ) ) {
			$image['alt'] = str_replace( array( '-', '_' ), ' ', $match[2] );
		}

		return $image;
	}

	/**
	 * Getting the featured image
	 *
	 * @param integer $post_thumbnail_id
	 * @param array   $images
	 *
	 * @return array
	 */
	private function get_item_featured_image( $post_thumbnail_id, $images ) {

		$attachment = $this->get_attachment( $post_thumbnail_id );

		if ( count( $attachment ) > 0 ) {
			$image = array();

			if ( '' != $attachment['title'] ) {
				$image['title'] = $attachment['title'];
			}

			if ( '' != $attachment['alt'] ) {
				$image['alt'] = $attachment['alt'];
			}

			if ( '' != $attachment['src'] ) {
				$images[ $attachment['src'] ] = $image;
			} elseif ( '' != $attachment['href'] ) {
				$images[ $attachment['href'] ] = $image;
			}
		}

		return $images;
	}

	/**
	 * Parse the XML for given image
	 *
	 * @param string $src
	 * @param string $img
	 * @param object $item
	 *
	 * @return string
	 */
	private function parse_item_image( $src, $img, $item ) {

		$output = '';

		/**
		 * Filter: 'wpseo_xml_sitemap_img_src' - Allow changing of sitemap image src
		 *
		 * @api string $src The image source
		 *
		 * @param object $item The post item
		 */
		$src = apply_filters( 'wpseo_xml_sitemap_img_src', $src, $item );

		$output .= "\t\t<image:image>\n";
		$output .= "\t\t\t<image:loc>" . htmlspecialchars( $src ) . "</image:loc>\n";

		if ( isset( $img['title'] ) ) {
			$output .= "\t\t\t<image:title>" . htmlspecialchars( $img['title'] ) . "</image:title>\n";
		}

		if ( isset( $img['alt'] ) ) {
			$output .= "\t\t\t<image:caption>" . htmlspecialchars( $img['alt'] ) . "</image:caption>\n";
		}

		$output .= "\t\t</image:image>\n";

		return $output;
	}

	/**
	 * Wrapper function to check if we have a valid datetime (Uses a new util in WPSEO)
	 *
	 * @param string $datetime
	 *
	 * @return bool
	 */
	private function is_valid_datetime( $datetime ) {
		if ( method_exists( 'WPSEO_Utils', 'is_valid_datetime' ) ) {
			return WPSEO_Utils::is_valid_datetime( $datetime );
		}
		return true;
	}

}
