<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News\XML_Sitemaps
 */

/**
 * Convert the sitemap dates to the correct timezone.
 *
 * @deprecated 12.4
 */
class WPSEO_News_Sitemap_Timezone {

	/**
	 * Returns the timezone string for a site, even if it's set to a UTC offset.
	 *
	 * @deprecated 12.4
	 * @codeCoverageIgnore
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
	 * @deprecated 12.4
	 * @codeCoverageIgnore
	 *
	 * @since 7.0 Changed the visibility of the method from private to public.
	 *
	 * @return string Valid PHP timezone string.
	 */
	public function wp_get_timezone_string() {
		_deprecated_function( __METHOD__, 'WPSEO News 12.4' );

		// If site timezone string exists, return it.
		$timezone = get_option( 'timezone_string' );
		if ( $timezone ) {
			return $timezone;
		}

		// Get UTC offset, if it isn't set then return UTC.
		$utc_offset = get_option( 'gmt_offset', 0 );
		if ( $utc_offset === 0 ) {
			return 'UTC';
		}

		// Format the UTC offset to a string readable by DateTimeZone.
		$offset_float         = abs( floatval( $utc_offset ) );
		$offset_int           = floor( $offset_float );
		$offset_minutes_float = ( ( $offset_float - $offset_int ) * 60 );
		$offset_minutes       = sprintf( '%02d', $offset_minutes_float );
		$offset_hours         = sprintf( '%02d', $offset_int );

		if ( $utc_offset >= 0 ) {
			return '+' . $offset_hours . $offset_minutes;
		}

		return '-' . $offset_hours . $offset_minutes;
	}
}
