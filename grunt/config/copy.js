module.exports = {
	artifactFiles: {
		files: [
			{
				expand: true,
				cwd: ".",
				src: [
					// folders to copy
					"vendor/**",
					"languages/**",
					// files to copy
					"/assets/xml-news-sitemap.xsl",
					"/assets/admin-page.min.js",
					"/assets/post-edit.min.js",
					"wpseo-news.php"
				],
				dest: "artifact",
			},
		],
	},
}