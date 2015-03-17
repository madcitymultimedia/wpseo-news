<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class WPSEO_News_Admin_Page {

	/**
	 * Display admin page
	 */
	public function display() {
		// Load options
		$options = WPSEO_News::get_options();

		// Admin header
		WPSEO_News_Wrappers::admin_header( true, 'yoast_wpseo_news_options', 'wpseo_news' );

		// This filter is documented in class-sitemap.php
		$news_sitemap_xml = apply_filters( 'wpseo_news_sitemap_url', home_url( 'news-sitemap.xml' ) );

		// Introducten
		echo '<p>' . __( 'You will generally only need XML News sitemap when your website is included in Google News.', 'wordpress-seo-news' ) . '</p>';
		echo '<p>' . sprintf( __( 'You can find your news sitemap here: %1$sXML News sitemap%2$s', 'wordpress-seo-news' ), "<a target='_blank' class='button-secondary' href='" . $news_sitemap_xml . "'>", '</a>' ) . '</p>';

		// Google News Publication Name
		echo WPSEO_News_Wrappers::textinput( 'name', __( 'Google News Publication Name', 'wordpress-seo-news' ) );

		// Default Genre
		echo WPSEO_News_Wrappers::select( 'default_genre', __( 'Default Genre', 'wordpress-seo-news' ),
			WPSEO_News::list_genres()
		);

		// Default keywords
		echo WPSEO_News_Wrappers::textinput( 'default_keywords', __( 'Default Keywords', 'wordpress-seo-news' ) );
		echo '<p>' . __( 'It might be wise to add some of Google\'s suggested keywords to all of your posts, add them as a comma separated list. Find the list here:', 'wordpress-seo-news' ) . ' ' . make_clickable( 'http://www.google.com/support/news_pub/bin/answer.py?answer=116037' ) . '</p>';

		echo WPSEO_News_Wrappers::checkbox( 'restrict_sitemap_featured_img', __( 'Only use featured image for XML News sitemap, ignore images in post.', 'wordpress-seo-news' ), false );

		echo '<br><br>';

		// Post Types to include in News Sitemap
		echo '<h2>' . __( 'Post Types to include in News Sitemap and Editors&#39; Pick RSS', 'wordpress-seo-news' ) . '</h2>';
		foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $posttype ) {
			echo WPSEO_News_Wrappers::checkbox( 'newssitemap_include_' . $posttype->name, $posttype->labels->name, false );
		}

		// Post categories to exclude
		if ( isset( $options['newssitemap_include_post'] ) ) {
			echo '<h2>' . __( 'Post categories to exclude', 'wordpress-seo-news' ) . '</h2>';
			foreach ( get_categories() as $cat ) {
				echo WPSEO_News_Wrappers::checkbox( 'catexclude_' . $cat->slug, $cat->name . ' (' . $cat->count . ' posts)', false );
			}
		}

		// Post Types to include in News Sitemap
		echo '<h2>' . __( "Editors' Pick", 'wordpress-seo-news' ) . '</h2>';

		$esc_form_key = 'ep_image_src';
		$option       = WPSEO_News::get_options();
		$meta_value   = $option[ $esc_form_key ];

		echo '<label class="select" for="' . $esc_form_key . '">' . __( "Editors' Pick Image", 'wordpress-seo-news' ) . ':</label>';
		echo '<input id="' . $esc_form_key . '" type="text" size="36" name="wpseo_news[' . $esc_form_key . ']" value="' . esc_attr( $meta_value ) . '" />';
		echo '<input id="' . $esc_form_key . '_button" class="wpseo_image_upload_button button" type="button" value="' . __( 'Upload Image', 'wordpress-seo-news' ) . '" />';
		echo '<br class="clear"/>';

		echo '<p>' . sprintf( __( 'You can find your Editors\' Pick RSS feed here: %1$sEditors\' Pick RSS Feed%2$s', 'wordpress-seo-news' ), "<a target='_blank' class='button-secondary' href='" . home_url( 'editors-pick.rss' ) . "'>", '</a>' ) . '</p>';
		echo '<p>' . sprintf( __( 'You can submit your Editors\' Pick RSS feed here: %1$sSubmit Editors\' Pick RSS Feed%2$s', 'wordpress-seo-news' ), "<a class='button-secondary' href='https://support.google.com/news/publisher/contact/editors_picks' target='_blank'>", '</a>' ) . '</p>';

		// Admin footer
		WPSEO_News_Wrappers::admin_footer( true, false );
	}

}

class WPSEO_News_Wrappers {

	/**
	 * Fallback for admin_header
	 *
	 * @param bool   $form
	 * @param string $option_long_name
	 * @param string $option
	 * @param bool   $contains_files
	 *
	 * @return mixed
	 */
	public static function admin_header( $form = true, $option_long_name = 'yoast_wpseo_options', $option = 'wpseo', $contains_files = false ) {

		if ( method_exists( 'Yoast_Form', 'admin_header' ) ) {
			Yoast_Form::get_instance()->admin_header( $form, $option, $contains_files, $option_long_name );

			return;
		}

		return self::admin_pages()->admin_header( true, 'yoast_wpseo_news_options', 'wpseo_news' );
	}

	/**
	 * Fallback for admin_footer
	 *
	 * @param bool $submit
	 * @param bool $show_sidebar
	 *
	 * @return mixed
	 */
	public static function admin_footer( $submit = true, $show_sidebar = true ) {

		if ( method_exists( 'Yoast_Form', 'admin_footer' ) ) {
			Yoast_Form::get_instance()->admin_footer( $submit, $show_sidebar );

			return;
		}

		return self::admin_pages()->admin_footer( $submit, $show_sidebar );
	}

	/**
	 * Fallback for the textinput method
	 *
	 * @param string $var
	 * @param string $label
	 * @param string $option
	 *
	 * @return mixed
	 */
	public static function textinput($var, $label, $option = '') {
		if ( method_exists( 'Yoast_Form', 'textinput' ) ) {
			if ( $option !== '' ) {
				Yoast_Form::get_instance()->set_option( $option );
			}

			Yoast_Form::get_instance()->textinput( $var, $label );

			return;
		}

		return self::admin_pages()->textinput( $var, $label, $option );
	}

	/**
	 * Wrapper for select method.
	 *
	 * @param string $var
	 * @param string $label
	 * @param array  $values
	 * @param string $option
	 *
	 * @return mixed
	 */
	public static function select( $var, $label, $values, $option = '' ) {
		if ( method_exists( 'Yoast_Form', 'select' ) ) {
			if ( $option !== '' ) {
				Yoast_Form::get_instance()->set_option( $option );
			}

			Yoast_Form::get_instance()->select( $var, $label, $values );
			return;
		}

		return self::admin_pages()->select( $var, $label, $option );
	}

	/**
	 * Wrapper for checkbox method
	 *
	 * @param        $var
	 * @param        $label
	 * @param bool   $label_left
	 * @param string $option
	 *
	 * @return mixed
	 */
	public static function checkbox( $var, $label, $label_left = false, $option = '' ) {
		if ( method_exists( 'Yoast_Form', 'checkbox' ) ) {
			if ( $option !== '' ) {
				Yoast_Form::get_instance()->set_option( $option );
			}

			Yoast_Form::get_instance()->checkbox( $var, $label, $label_left );
			return;
		}

		return self::admin_pages()->checkbox( $var, $label, $label_left, $option );
	}

	/**
	 * Returns the wpseo_admin pages global variable
	 *
	 * @return mixed
	 */
	private static function admin_pages() {
		global $wpseo_admin_pages;

		return $wpseo_admin_pages;
	}

}
