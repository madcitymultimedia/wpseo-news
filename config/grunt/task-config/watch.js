// See https://github.com/gruntjs/grunt-contrib-watch for details.
module.exports = {
	options: {
		livereload: false,
	},
	grunt: {
		options: {
			reload: true,
		},
		files: [
			"<%= files.grunt %>",
			"<%= files.config %>",
		],
		tasks: [
			"eslint:grunt",
		],
	},
	php: {
		files: [
			"<%= files.php %>",
		],
		tasks: [
			"check:php",
		],
	},
	js: {
		files: [
			"<%= files.js %>",
		],
		tasks: [
			"eslint:plugin",
			"build:js",
		],
	},
};
