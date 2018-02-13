module.exports = {
	artifactFiles: {
		options: {
			archive: "artifact.zip",
		},
		files: [
			{
				expand: true,
				cwd: "artifact/",
				src: ["**"],
				dest: "./",
			},
		],
	},
}