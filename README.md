Yoast News SEO for Yoast SEO
==========================
Requires at least: 5.4
Tested up to: 5.5
Stable tag: 12.6
Requires PHP: 5.6.20
Depends: Yoast SEO

Yoast News SEO module for the Yoast SEO plugin.

[![Code Climate](https://codeclimate.com/repos/54523c37e30ba0670f0016b8/badges/373c97133cba47d9822b/gpa.svg)](https://codeclimate.com/repos/54523c37e30ba0670f0016b8/feed)

This repository uses [the Yoast grunt tasks plugin](https://github.com/Yoast/plugin-grunt-tasks).

Installation
============

1. Go to Plugins -> Add New.
2. Click "Upload" right underneath "Install Plugins".
3. Upload the zip file that this readme was contained in.
4. Activate the plugin.
5. Go to SEO -> Extensions -> Licenses, enter your license key and Save.
6. Your license key will be validated.
7. You can now use Yoast News SEO. See also https://kb.yoast.com/kb/configuration-guide-for-news-seo/

Frequently Asked Questions
--------------------------

You can find the [Yoast News SEO FAQ](https://kb.yoast.com/kb/category/news-seo/) in our knowledge base.

Changelog
=========
### 12.6: August 18th, 2020
Enhancements:
* Adds 'Article' as `@type` to articles that are set to be included in the news sitemap. This results in a `@type` array with at least 'Article' and 'NewsArticle'.

Other:
* Enables tracking when activating the plugin. It can be disabled in the Yoast SEO configuration wizard.
* Sets the minimum supported WordPress version to 5.4.

### 12.5: July 21st, 2020
Other:
* Sets the minimum WordPress version to 5.3.

### 12.4.1: January 14th, 2020
Bugfixes:
* Fixes a bug where a large number of WordPress terms or post types would cause significant slowdowns and possible crashes on admin pages.

Other:
* Removes the code tags from the term and post type names on the News SEO settings page. 

### 12.4: January 7th, 2020
Bugfixes:
* Fixes a bug where the sitemap could be invalidated twice on multisite with MultilingualPress.

Other:
* Show dates in UTC+0 format everywhere.

### 12.3: November 27th, 2019
Enhancements:
* Replaces the HelpScout Beacon 1.0 on the News SEO admin page with the Beacon 2.0.

Bugfixes:
* Fixes a bug where the News sitemap would contain the SEO title instead of the post title.

Other:
* Sets the minimum required WordPress version to 5.2, the minimum PHP version to 5.6.20, and the minimum required Yoast SEO version to 12.6.1.

### 12.2: October 15th, 2019
Enhancements:
* Shows a floating `Save changes` button on the Yoast SEO News admin page when the normal button is not visible in the browser window.

Bugfixes:
* Fixes a bug where the copyrightHolder schema piece would not stitch on Windows because of a missing slash.

Other:
* Compatibility with Yoast SEO 12.2

### 12.1: September 17th, 2019
Other:
* Compatibility with Yoast SEO 12.1

### 12.0: September 3rd, 2019
Other:
* Compatibility with Yoast SEO 12.0

### 11.9: August 20th, 2019
Enhancements:
* This add-on now has its own tab in the Yoast SEO metabox, even if you have multiple Yoast SEO add-ons installed.

Bugfixes:
* Fixes a bug where the `@type` in the schema output would be `NewsArticle` instead of `Article` for articles which were excluded from the news sitemap.
* Fixes a bug where news article pages and custom post types included in the news sitemap would not receive an author in their schema output.

Other:
* Sets the minimum required Yoast SEO version to 11.9.

### 11.8: August 6th, 2019
Other:
* Compatibility with Yoast SEO 11.8

### 11.7: July 23rd, 2019
Other:
* Compatibility with Yoast SEO 11.7

### 11.6: July 7th, 2019
Other:
* Compatibility with Yoast SEO 11.6

### 11.5: June 25th, 2019
Other:
* Compatibility with Yoast SEO 11.5

### 11.4: June 12th, 2019
Other:
* Compatibility with Yoast SEO 11.4

### 11.3: May 28th, 2019
Other:
* Compatibility with Yoast SEO 11.3

### 11.2: May 15th, 2019
Other:
* Compatibility with Yoast SEO 11.2

### 11.1: April 30th, 2019
Other:
* Compatibility with Yoast SEO 11.1

### 11.0: April 16th, 2019
Enhancements:
* Schema: Adds Schema.org `NewsArticle` markup to all post types that you've selected as News.

Bugfixes:
* Fixed Sitemap image URLs to use href instead of guid to prevent relative URLs in the Sitemap. Props to [ChristophKnopf](https://github.com/ChristophKnopf)

Other:
* Sets the minimum required Yoast SEO version to 11.0.

### 10.1: April 2nd, 2019
Other:
* Sets the minimum required Yoast SEO version to 10.1.
* Removes the deprecated methods ( < 6.1 ).

### 10.0: March 12th, 2019
Bugfixes:
* Fixes a bug where the news sitemap would show a blank page due to an internal server error.

Other:
* Compatibility with Yoast SEO 10.0

### 9.7: February 26th, 2019
Enhancements:
* Changes the titles in the news sitemap to default to the SEO title, with a fallback to the post title. (props: @timnolte)

Bugfixes:
* Fixes a bug where it was possible the 48 hour selection range for posts in the news sitemap was slightly off. (props: @timnolte)

Other:
* Compatibility with Yoast SEO 9.7

### 9.6.1: February 12th, 2019
Other:
* Compatibility with Yoast SEO 9.6.1

### 9.6: February 12th, 2019
Other:
* Compatibility with Yoast SEO 9.6

### 9.5: January 22nd, 2019
Other:
* Compatibility with Yoast SEO 9.5

### 9.4: January 8th, 2019
Bugfixes:
* Fixes a bug where the last modified date for the sitemap index has been displayed with the wrong time zone.

Other:
* Compatibility with Yoast SEO 9.4

### 9.3: December 18th, 2018
Other:
* Compatibility with Yoast SEO 9.3

### 9.2: November 20th, 2018
Other:
* Compatibility with Yoast SEO 9.2

### 9.1: November 6th, 2018
Bugfixes:
* Fixes a bug where a PHP warning would be displayed when `php_uname` has been disabled for security reasons. 

Other:
* Compatibility with Yoast SEO 9.1

### 9.0: October 23rd, 2018
Enhancements:
* Allows term based exclusion in the news sitemap for all taxonomies connected to enabled post types.

Bugfixes:
* Fixes a bug where terms with `show_ui` set to false would be shown in the list of terms to be excluded from the news sitemap.

Other:
* Compatibility with Yoast SEO 9.0

### 8.4: October 9th, 2018
Bugfixes:
* Fixes a bug where the changelog would not show up when the plugin was updated.

Enhancements:
* Makes it possible to exclude posts with post type 'post' from the the news sitemap.

Other:
* Compatibility with Yoast SEO 8.4

### 8.3: September 25th, 2018
Bugfixes:
* Fixes a bug where the XML of the news sitemap would contain an incorrect publication time when the user would use a location-based timezone.
* Fixes a bug where posts with the post type of Post would always be included in the sitemap.

Other:
* Compatibility with Yoast SEO 8.3

### 8.2: September 11th, 2018
Other:
* Compatibility with Yoast SEO 8.2

### 8.1: August 28th, 2018
Other:
* Compatibility with Yoast SEO 8.1

### 8.0: August 14th, 2018
Other:
* Compatibility with Yoast SEO 8.0

### 7.9.1: August 7th, 2018
Other:
* Compatibility with Yoast SEO 7.9.1

### 7.9: July 24th, 2018
Bugfixes:
* Fixes a bug where news posts set to no-index would still appear in the XML News Sitemap.

Other:
* Compatibility with Yoast SEO 7.9

### 7.8: July 10th, 2018
Other:
* Removes functionality for original-source as Google has confirmed they're no longer using it.
* Compatibility with Yoast SEO 7.8

### 7.7: June 26th, 2018
* Compatibility with Yoast SEO 7.7

### 7.6: June 5th, 2018
Enhancements:
* Removes support for standout tag.
* Removes support for editor's pick RSS.

Bugfixes:
* Fixes a bug where the news image sitemap did not correctly handle protocol relative URLs.

Other:
* Compatibility with Yoast SEO 7.6

### 7.5: May 15th, 2018
* Compatibility with Yoast SEO 7.5

### 7.4: May 1st, 2018
* Compatibility with Yoast SEO 7.4

### 7.3: April 17th, 2018
* Compatibility with Yoast SEO 7.3

### 7.2: April 3rd, 2018
Enhancements:
* Removes the use of meta news keywords everywhere as Google no longer supports them.
* Renews compatibility with the [Plugin Dependencies](http://wordpress.org/plugins/plugin-dependencies/) plugin.

Bugfixes:
* Fixes a bug where the page link was missing on the plugin settings page.

Other:
* Compatibility with Yoast SEO 7.2
* Adds warnings for deprecated functions.
* Security hardening.

### 7.1: March 20th, 2018
* Compatibility with Yoast SEO 7.1

### 7.0: March 6th, 2018
* Requires Yoast SEO 7.0 or higher to be installed.
* Moves each sitemap class to its own file instead of having multiple classes in one single file.

### 6.3: February 13th, 2018
* Load the XSL stylesheet from a static file when home and site URL are the same.
* Compatibility with Yoast SEO 6.3

### 6.2.1: January 23rd, 2018
* Bump version to make sure it displays correctly in the plugin list.

### 6.2: January 23rd, 2018
* Compatibility with Yoast SEO 6.2

### 6.1: January 9th, 2018
* Compatibility with Yoast SEO 6.1

### 6.0: December 20th, 2017
Bugfixes:
* Fixes a problem where the sitemap table could not be sorted.

* Compatibility with Yoast SEO 6.0

### 5.9: December 5th, 2017
* Compatibility with Yoast SEO 5.9

### 5.8: November 15th, 2017
* Compatibility with Yoast SEO 5.8

### 5.7: October 24th, 2017
* Compatibility with Yoast SEO 5.7

### 5.6: October 10th, 2017
Enhancements:
* Changes the capability on which the submenu is registered to `wpseo_manage_options`

Bugfixes:
* Fixes a bug where the license check endpoint was using an incorrect URL

### 5.5: September 26th, 2017
* Updated the internationalization module to version 3.0.

### 5.4: September 5th, 2017
* Updated the internationalization module to version 2.0.

### 5.3: August 22nd, 2017
* Compatibility with Yoast SEO 5.3

### 5.2: August 8th, 2017
* Compatibility with Yoast SEO 5.2

### 5.1: July 25th, 2017
* Fixes a bug where the wrong timezone was used in the sitemap.

### 5.0: July 6th, 2017
* Adds post type name (attachment) after the label to clarify the post type.
* Fixes a fatal error when Piklist and Yoast News SEO are both active.
* Fixes a fatal error when Liveblog and Yoast News SEO are both active.
* Fixes a warning in the News sitemap when bbPress and Yoast News SEO are both active.

### 4.9: June 7th, 2017
* Fixes a bug where sticky posts show up in the editors' pick RSS feed when it was not marked for editors' pick.
* Fixes a bug where the date format was invalid in the editors' pick RSS feed.

### 4.8: May 23rd, 2017
* Compatibility with Yoast SEO 4.8

### 4.7: May 2nd, 2017
* Fixes a bug where there were a deprecated function was being called, resulting in a warning.

### 4.6: April 11th, 2017
* Compatibility with Yoast SEO 4.6

### 4.5: March 21st, 2017
* Compatibility with Yoast SEO 4.5

### 4.4: February 28th, 2017
* Compatibility with Yoast SEO 4.4

### 4.3: February 14th, 2017

* Enhancements
	* Only invalidate sitemap cache when an object in a Post Type that is marked as News is updated.

### 4.2.1: February 3rd, 2017

* Bugfixes
	* Fixes "Fatal error: Class 'yoast_i18n' not found". 

### 4.2: January 31st, 2017

* Bugfixes
	* Fixes a bug where a deprecated function was being called, resulting in a warning.

### 4.1: January 17th, 2017

* Bugfixes
    * Fixes W3C validation error on <link attribute 'original-source'.
    * Fixed broken link in news sitemap.

### 4.0: December 13th, 2016

* Compatibility with Yoast SEO 4.0

### 3.9: November 29th, 2016

* Bugfixes
    * Fixes a bug where the editor's pick checkbox was reset after inline editing a post.

* Updates translations.

### 3.8: November 8th, 2016

* Bugfixes
    * Fixes a bug where the editor's pick would be unset when quick editing. 
    * Fixes a bug where Google News would report an unexpected publication name in sitemaps.

 * Updates translations.

### 3.7: October 11th, 2016

* Updates translations.

### 3.6: September 27th, 2016

* Enhancements
    * [General accessibility improvements](https://github.com/Yoast/wpseo-news/pull/238)

### 3.5.1: September 16th, 2016

* Bugfixes
	* Fixes a bug where a page or post type with the slug news would be inaccessible.

### 3.5: September 7th, 2016

* Bugfixes
    * Fixes a bug where sitemaps would not be generated if a post with the same name existed.

### 3.4: July 19th, 2016

* Enhancements
	* Introduced noindex meta tag for the Google News bot.

* Bugfixes
	* Sitemaps need a real timezone instead of a UTC timezone because daylight saving time is not included in UTC.
	* Google News Genre global default could not be overridden by page setting.
	* The news keyword counting, for a maximum of 10 keywords, no longer counts empty keywords 

### 3.3: June 14th, 2016

* Enhancements
	* Adds the Yoast i18n module to the Yoast SEO News settings page, which informs users the plugin isn't available in their language and what they can do about it.

* Bugfixes
	* Fixes a bug where the support beacon for Yoast SEO News was added to all Yoast SEO settings pages.
	* Fixes a bug where updates were not working reliably when multiple paid Yoast plugins were active.

### 3.2: April 20th, 2016

* Bugfixes
	* Fixes a bug where the news sitemap would be shown in the index even if the sitemap itself was empty.
	* Fixes a bug where clicking the 'Update now' button on the plugin page didn't update correctly.

### 3.1: March 2nd, 2016

* Bugfixes
	* Fixes a bug where our license manager could sometimes not reach our licensing system due to problems with ssl.

* Enhancements
	* Makes sure users don't have to reactivate their license after updating or disabling/enabling the plugin.
	* Adds a support beacon on the News SEO settings page enabling users to ask for support from the WordPress backend.
	* Contains a few performance optimizations.

### 3.0: November 18th, 2015

* Synchronized plugin version with all other Yoast SEO plugins for WordPress.

* Bugfixes
	* Fixes a bug where the links in the sitemap would 'randomly' change from https to http or the other way around (in very rare circumstances).
	* Fixes the news metabox that was broken in combination with Yoast SEO 3.0.
	* Fixes deprecation warnings for filters that have been removed in Yoast SEO 3.0

### 2.2.5: June 10th, 2015

* Bugfixes
	* Fixes a bug where the news sitemap cache was not cleared when the News SEO settings were updated.
	* Added 1 new translation: en_AU.

### 2.2.4: April 29th, 2015

* Bugfixes
	* Fixes a bug where the news sitemap cache was not cleared when a news item was edited or added.
	* Fixes a bug where the stylesheets for the news sitemap were not included when the sitemap was served from cache.
	* Fixes a bug where specialchars were escaped in the news sitemap.

### 2.2.3: March 24th, 2015

* Bugfixes
	* Fixes a bug where an invalid argument error could be raised when visiting the sitemap.
	* Fixes a bug where the sitemap could contain wrongly formatted publication dates.
	* Fixes a bug where the `wpseo_news_sitemap_url` filter wasn't working properly.
	* Fixes a bug where the News sitemap genre wasn't saved on posts.
	* Fixes a bug where the sitemap didn't always contain the correct image url, props [Marcus Jaschen](https://github.com/mjaschen).
	* Fixes a bug with duplicate keywords in the news sitemap.
	* Fixes an issue where the Editor's pick RSS took very long to load, by only fetching the selected post types from the database.
	* Fixes a bug where the default keywords and the meta news keywords weren't added to the sitemap.
* Enhancements
	* Introduces a few string improvements.
	* Added 9 new languages: en_GB, es_ES, es_MX, fr_FR, he_IL, it_IT, nl_NL, ro_RO and tr_TR.

### 2.2.2: December 17th, 2014

* Bugfixes
  * The stocktickers didn't work properly, these are fixed
  * Setting correct HTTP header to be sure output is executed as a RSS-feed
* Enhancements
  * Hide the standout meta-tag automatically after seven days (you can still see standout was used in admin but it won't be displayed)
  * Show the total number of used standout meta-tags (for the last seven days) in the post-admin

### 2.2.1: November 11th, 2014

* Bugfixes
  * Fixed a bug where button to Editors' Pick RSS didn't work.
  * Fixed a bug where the wrong image url ended up in the <image:loc> in the Editor's Pick RSS
  * Fixed a bug where the wrong http header was set for the Editor's Pick RSS
* Enhancements
  *	Added translations for Polish

### 2.2: October 7th, 2014
* Bugfixes
  * Fixed a bug where button to Editors' Pick RSS didn't work.
	* Fixed bug where plugin would give a white screen of death in certain installations.
	* Improve using the right image for the news sitemap.

* Enhancements
	* Added `pubDate` to editors pick RSS feed.

### 2.1: July 9th, 2014
* Several performance optimizations for sitemap generation.
* Added button that links to news sitemap on admin page.
* Added an option to include only the featured image in the XML News sitemap.
* Introduced filter `wpseo_locale` for locale/language of the XML News sitemap.
* Introduced filter `wpseo_news_sitemap_url` to allow changing the XML News sitemap URL.

### 2.0.6: June 10th, 2014
* Removed the wptexturize filter from the_title and the_content in the Editors' Pick feed because corrupts our output.
* Added guid elements to item elements in the Editors' Pick feed.
* Added an atom:link element as recommended by the RSS Advisory Board to identifying a feed's URL within the feed.
* Added the WPSEO News fields to the WPSEO meta fields class to fix a bug where the post meta genre field isn't saved.

### 2.0.5: June 5th, 2014
* Fixed a publication_date timezone bug.

### 2.0.4: May 15th, 2014
* Bugfixes
  * Add CDATA tags to RSS feed text output.
  * Now using the same title for the Editors' Pick title as the channel title.

### 2.0.3: April 23rd, 2014
* Enhancement
  * Sitemaps now use creation dates instead of modified dates.

### 2.0.2: April 22nd, 2014
* Bugfixes
  * Fixed a bug with version_compare.

* Enhancement
  * Adds sanitize callback to register_settings.

### 2.0.1: April 22nd, 2014
* Bugfix
  * Changed EDD product name to 'News SEO'.

### 2.0: April 22nd, 2014
* Initial release
