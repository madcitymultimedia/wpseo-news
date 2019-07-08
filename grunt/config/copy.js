module.exports = {
	artifact: {
		files: [
			{
				expand: true,
				cwd: ".",
				src: [
					// Folders to copy.
					"vendor/**",
					"languages/**",
					"classes/**",
					// Files to copy.
					"wpseo-news.php",
					"assets/xml-news-sitemap.xsl",
					"assets/*.min.js",
					"README.md",
					"license.txt",
				],
				dest: "artifact",
			},
		],
	},
};
