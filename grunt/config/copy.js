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
					"/assets/*.min.js",
					"*.php"
				],
				dest: "artifact",
			},
		],
	},
}