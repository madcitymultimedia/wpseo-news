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
