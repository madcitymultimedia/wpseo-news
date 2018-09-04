<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News\XML_Sitemaps
 */

/**
 * Convert the sitemap dates to the correct timezone.
 */
class WPSEO_News_Sitemap_Timezone {

	/**
	 * Returns the timezone string for a site, even if it's set to a UTC offset.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->wp_get_timezone_string();
	}

	/**
	 * Returns the timezone string for a site, even if it's set to a UTC offset.
	 *
	 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
	 *
	 * @since 7.0 Changed the visibility of the method from private to public.
	 *
	 * @return string Valid PHP timezone string.
	 */
	public function wp_get_timezone_string() {

		// If site timezone string exists, return it.
		if ( $timezone = get_option( 'timezone_string' ) ) {
			return $timezone;
		}

		$utc_offset = get_option( 'gmt_offset', 0 );

		// Get UTC offset, if it isn't set then return UTC.
		if ( $utc_offset === 0 ) {
			return 'UTC';
		}

		// Format the UTC offset to a string readable by DateTimeZone.
		$offset_float         = abs( floatval( $utc_offset ) );
		$offset_int           = floor( $offset_float );
		$offset_minutes_float = ( ( $offset_float - $offset_int ) * 60 );
		$offset_minutes       = sprintf( '%02d' , $offset_minutes_float );
		$offset_hours         = sprintf( '%02d', $offset_int );

		if ( $utc_offset >= 0 ) {
			return '+' . $offset_hours . $offset_minutes;
		}

		return '-' . $offset_hours . $offset_minutes;
	}
}
