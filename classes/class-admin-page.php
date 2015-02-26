<?php

class WPSEO_News_Admin_Page {

	/**
	 * Holder for object for admin_pages
	 * @var
	 */
	private $wpseo_admin_pages;

	/**
	 * Display admin page
	 */
	public function display() {
		// Setting the object for admin pages
		$this->wpseo_admin_pages = $this->get_wpseo_admin_pages();

		// Admin header
		$this->wpseo_admin_pages->admin_header( true, 'yoast_wpseo_news_options', 'wpseo_news' );

		// This filter is documented in class-sitemap.php
		$news_sitemap_xml = apply_filters( 'wpseo_news_sitemap_url', home_url( 'news-sitemap.xml' ) );

		// Introducten
		echo '<p>' . __( 'You will generally only need XML News sitemap when your website is included in Google News.', 'wordpress-seo-news' ) . '</p>';
		echo '<p>' . sprintf( __( 'You can find your news sitemap here: %1$sXML News sitemap%2$s', 'wordpress-seo-news' ), "<a target='_blank' class='button-secondary' href='" . $news_sitemap_xml . "'>", '</a>' ) . '</p>';

		// Google News Publication Name
		echo $this->wpseo_admin_pages->textinput( 'name', __( 'Google News Publication Name', 'wordpress-seo-news' ) );

		// Default Genre
		echo $this->wpseo_admin_pages->select( 'default_genre', __( 'Default Genre', 'wordpress-seo-news' ), WPSEO_News::list_genres() );

		// Default keywords
		$this->default_keywords();

		// Post Types to include in News Sitemap
		$this->include_post_types();

		// Post categories to exclude
		$this->excluded_post_categories( );

		// Editors' Pick
		$this->editors_pick();

		// Admin footer
		$this->wpseo_admin_pages->admin_footer( true, false );
	}

	/**
	 * Getting the object for the seo admin pages
	 *
	 * @return object
	 */
	private function get_wpseo_admin_pages() {
		if ( class_exists( 'Yoast_Form' ) ) {
			return Yoast_Form::get_instance();
		}

		global $wpseo_admin_pages;
		return $wpseo_admin_pages;
	}

	/**
	 * Generate HTML for the keywords which will be defaulted
	 */
	private function default_keywords() {
		echo $this->wpseo_admin_pages->textinput( 'default_keywords', __( 'Default Keywords', 'wordpress-seo-news' ) );
		echo '<p>' . __( 'It might be wise to add some of Google\'s suggested keywords to all of your posts, add them as a comma separated list. Find the list here:', 'wordpress-seo-news' ) . ' ' . make_clickable( 'http://www.google.com/support/news_pub/bin/answer.py?answer=116037' ) . '</p>';

		echo $this->wpseo_admin_pages->checkbox( 'restrict_sitemap_featured_img', __( 'Only use featured image for XML News sitemap, ignore images in post.', 'wordpress-seo-news' ), false );

		echo '<br><br>';
	}

	/**
	 * Generate HTML for the post types which should be included in the sitemap
	 */
	private function include_post_types() {
		echo '<h2>' . __( 'Post Types to include in News Sitemap', 'wordpress-seo-news' ) . '</h2>';
		foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $post_type ) {
			echo $this->wpseo_admin_pages->checkbox( 'newssitemap_include_' . $post_type->name, $post_type->labels->name, false );
		}
	}

	/**
	 * Generate HTML for excluding post categories
	 */
	private function excluded_post_categories( ) {
		$options = WPSEO_News::get_options();
		if ( isset( $options['newssitemap_include_post'] ) ) {
			echo '<h2>' . __( 'Post categories to exclude', 'wordpress-seo-news' ) . '</h2>';
			foreach ( get_categories() as $cat ) {
				echo $this->wpseo_admin_pages->checkbox( 'catexclude_' . $cat->slug, $cat->name . ' (' . $cat->count . ' posts)', false );
			}
		}
	}

	/**
	 * Part with HTML for editors pick
	 */
	private function editors_pick() {
		echo '<h2>' . __( "Editors' Pick", 'wordpress-seo-news' ) . '</h2>';

		$esc_form_key = 'ep_image_src';
		$options      = WPSEO_News::get_options();

		echo '<label class="select" for="' . $esc_form_key . '">' . __( "Editors' Pick Image", 'wordpress-seo-news' ) . ':</label>';
		echo '<input id="' . $esc_form_key . '" type="text" size="36" name="wpseo_news[' . $esc_form_key . ']" value="' . esc_attr( $options[ $esc_form_key ] ) . '" />';
		echo '<input id="' . $esc_form_key . '_button" class="wpseo_image_upload_button button" type="button" value="' . __( 'Upload Image', 'wordpress-seo-news' ) . '" />';
		echo '<br class="clear"/>';

		echo '<p>' . sprintf( __( 'You can find your Editors\' Pick RSS feed here: %1$sEditors\' Pick RSS Feed%2$s', 'wordpress-seo-news' ), "<a target='_blank' class='button-secondary' href='" . home_url( 'editors-pick.rss' ) . "'>", '</a>' ) . '</p>';
		echo '<p>' . sprintf( __( 'You can submit your Editors\' Pick RSS feed here: %1$sSubmit Editors\' Pick RSS Feed%2$s', 'wordpress-seo-news' ), "<a class='button-secondary' href='https://support.google.com/news/publisher/contact/editors_picks' target='_blank'>", '</a>' ) . '</p>';
	}

}