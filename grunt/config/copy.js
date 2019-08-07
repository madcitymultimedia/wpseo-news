module.exports = {
	artifact: {
		files: [
			{
				expand: true,
				cwd: ".",
				src: [
					// Folders to copy.
					"vendor/**",
					"!vendor/bin",
					"!vendor/composer/installed.json",
					"!vendor/xrstf/composer-php52/composer.json",
					"!vendor/xrstf/composer-php52/README.md",
					"!vendor/yoast/i18n-module/composer.json",
					"!vendor/yoast/i18n-module/README.md",
					"!vendor/yoast/i18n-module/LICENSE",
					"!vendor/yoast/wp-helpscout/composer.json",
					"languages/*.mo",
					"classes/**",
					// Files to copy.
					"wpseo-news.php",
					"assets/xml-news-sitemap.xsl",
					"assets/*.min.js",
					"css/dist/*.min.css",
					"README.md",
					"license.txt",
				],
				dest: "artifact",
			},
		],
	},
};
