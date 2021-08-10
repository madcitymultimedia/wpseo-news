Yoast News SEO for Yoast SEO
==========================
Requires at least: 5.6
Tested up to: 5.8
Stable tag: 12.9
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

### 12.9: August 10th, 2021
Bugfixes:
* Fixes a bug where a duplicate News section would be shown in the Yoast sidebar in the post editor when Yoast SEO Premium is active in combination with WP 5.8.

### 12.8: July 13th, 2021
Enhancements:
* Adds key/value pairs of all News SEO meta tags to our REST API.
* Improves the performance of the news sitemap. Props to [archon810](https://github.com/archon810).

Bugfixes:
* Fixes a bug where a database query including duplicate column names would fail on certain database systems.

Other:
* Adds a filter to override the decision to omit an item from the news sitemap. Props to [joneslloyd](https://github.com/joneslloyd).
* Sets the minimum WordPress version to 5.6.

### Earlier versions
For the changelog of earlier versions, please refer to [the changelog on yoast.com](https://yoa.st/news-seo-changelog).
