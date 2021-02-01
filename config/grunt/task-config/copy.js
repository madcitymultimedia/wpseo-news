module.exports = function( grunt ) {
	return {
		artifact: {
			files: [
				{
					expand: true,
					cwd: ".",
					src: [
						// Folders to copy.
						"<%= paths.vendor %>**",
						"!<%= paths.vendor %>bin",
						"!<%= paths.vendor %>composer/installed.json",
						"!<%= paths.vendor %>xrstf/composer-php52/composer.json",
						"!<%= paths.vendor %>xrstf/composer-php52/README.md",
						"!<%= paths.vendorYoast %>i18n-module/composer.json",
						"!<%= paths.vendorYoast %>i18n-module/README.md",
						"!<%= paths.vendorYoast %>i18n-module/LICENSE",
						"!<%= paths.vendorYoast %>wp-helpscout/composer.json",
						"classes/**",
						// Files to copy.
						"wpseo-news.php",
						"**/index.php",
						"!node_modules/**/index.php",
						"assets/xml-news-sitemap.xsl",
						"<%= paths.jsDist %>*.js",
						"README.md",
						"license.txt",
						"<%= paths.languages %>*.mo",
					],
					dest: "artifact",
				},
			],
		},

		"makepot-wordpress-seo-news": {
			src: "<%= files.pot.makepot %>",
			dest: "<%= paths.languages %><%= files.pot.js %>",
		},
	};
};
