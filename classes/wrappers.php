<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News\Admin
 */

/**
 * Represents wrappers for the form methods.
 */
class WPSEO_News_Wrappers {

	/**
	 * Fallback for admin_header.
	 *
	 * @deprecated 12.4
	 *
	 * @param bool   $form             Whether or not the form start tag should be included.
	 * @param string $option_long_name Group name of the option.
	 * @param string $option           The short name of the option to use for the current page.
	 * @param bool   $contains_files   Whether the form should allow for file uploads.
	 */
	public static function admin_header( $form = true, $option_long_name = 'yoast_wpseo_options', $option = 'wpseo', $contains_files = false ) {
		_deprecated_function( __METHOD__, 'WPSEO News 12.4' );
	}

	/**
	 * Fallback for admin_footer.
	 *
	 * @deprecated 12.4
	 *
	 * @param bool $submit       Whether or not a submit button and form end tag should be shown.
	 * @param bool $show_sidebar Whether or not to show the banner sidebar - used by premium plugins to disable it.
	 */
	public static function admin_footer( $submit = true, $show_sidebar = true ) {
		_deprecated_function( __METHOD__, 'WPSEO News 12.4' );
	}

	/**
	 * Fallback for the textinput method.
	 *
	 * @deprecated 12.4
	 *
	 * @param string $var   The variable within the option to create the text input field for.
	 * @param string $label The label to show for the variable.
	 * @param string $option The option to use.
	 */
	public static function textinput( $var, $label, $option = '' ) {
		_deprecated_function( __METHOD__, 'WPSEO News 12.4' );
	}

	/**
	 * Wrapper for select method.
	 *
	 * @deprecated 12.4
	 *
	 * @param string $var    The variable within the option to create the select for.
	 * @param string $label  The label to show for the variable.
	 * @param array  $values The select options to choose from.
	 * @param string $option The option to use.
	 *
	 * @return mixed
	 */
	public static function select( $var, $label, $values, $option = '' ) {
		_deprecated_function( __METHOD__, 'WPSEO News 12.4' );
	}

	/**
	 * Wrapper for checkbox method.
	 *
	 * @deprecated 12.4
	 *
	 * @param string $var        The variable within the option to create the checkbox for.
	 * @param string $label      The label to show for the variable.
	 * @param bool   $label_left Whether the label should be left (true) or right (false).
	 * @param string $option     The option to use.
	 */
	public static function checkbox( $var, $label, $label_left = false, $option = '' ) {
		_deprecated_function( __METHOD__, 'WPSEO News 12.4' );
	}
}
