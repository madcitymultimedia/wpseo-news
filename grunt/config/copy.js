module.exports = {
	artifactFiles: {
		files: [
			{
				expand: true,
				cwd: ".",
				src: [
					// Folders to copy.
					"vendor/**",
					"languages/**",
					// Files to copy.
					"/assets/xml-news-sitemap.xsl",
					"/assets/*.min.js",
					"*.php",
				],
				dest: "artifact",
			},
		],
	},
};
