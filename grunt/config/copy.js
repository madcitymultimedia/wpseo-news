module.exports = {
	artifactFiles: {
		files: [
			{
				expand: true,
				cwd: ".",
				src: [
					// folders to copy
					"classes/**",
					"vendor/**",
					"assets/**",
					"languages/**",
					// files to copy
					"wpseo-news.php"
				],
				dest: "artifact",
			},
		],
	},
}