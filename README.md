[![Coverage Status](https://coveralls.io/repos/github/Yoast/wpseo-news/badge.svg?branch=trunk)](https://coveralls.io/github/Yoast/wpseo-news?branch=trunk)

Yoast News SEO for Yoast SEO
==========================
Requires at least: 6.1
Tested up to: 6.2
Stable tag: 13.1
Requires PHP: 7.2.5
Depends: Yoast SEO

Yoast News SEO module for the Yoast SEO plugin.

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

## 20.11

Release date: 2023-07-11

#### Enhancements

* Adds a `wpseo_news_sitemap_content` filter to append custom content to the XML sitemap. Props to @wccoder.

#### Bugfixes

* Fixes a bug where a warning would be thrown on activation.
* Fixes a bug where using the `&` character in the publication name would break the XML sitemap.

#### Other

* Drops compatibility with PHP 5.6, 7.0 and 7.1.
* Sets minimum WordPress version to 5.9 and tested up to 6.0.
* Sets minimum WordPress version to 6.0.
* Sets the minimum supported WordPress version to 6.1.
* Sets the tested up to WordPress version to 6.2.
* Sets the WordPress tested up to version to 5.9.

## 13.1

Release date: 2021-11-16

#### Enhancements

* Huge performance improvement: moves the XML News sitemap to be based on our Indexables architecture.
* Removes images from the XML News sitemap as they serve no purpose here and this further improves performance.

#### Other

* Excludes attachments and non-indexed post types from the possible post types to include in the News Sitemap.

### Earlier versions
For the changelog of earlier versions, please refer to [the changelog on yoast.com](https://yoa.st/news-seo-changelog).
