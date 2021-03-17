Yoast News SEO for Yoast SEO
==========================
Requires at least: 5.6
Tested up to: 5.7
Stable tag: 12.7-beta1
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
### 12.7: April 6th, 2021
Enhancements:

* Adds News to our sidebar in the block editor as well as in our Elementor integration.
* Merges the `Exclude from News sitemap` and the `Google-bot news index` options into one.
* Removes the news genre from the News sitemap and settings.

Other:

* Sets the WordPress tested up to version to 5.7 and minimum supported WordPress version to 5.6.

Bugfixes:

* Fixes a bug where certain conditions (e.g. using a different admin language) would result in an endless loop.
* Fixes "mixed content" warnings on the News SEO options page.

### 12.6: August 18th, 2020
Enhancements:
* Adds 'Article' as `@type` to articles that are set to be included in the news sitemap. This results in a `@type` array with at least 'Article' and 'NewsArticle'.

Other:
* Enables tracking when activating the plugin. It can be disabled in the Yoast SEO configuration wizard.
* Sets the minimum supported WordPress version to 5.4.
