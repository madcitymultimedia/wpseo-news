// https://github.com/rockykitamura/grunt-po2json
module.exports = {
	js: {
		options: {
			format: "jed1.x",
			domain: "<%= pkg.plugin.textdomain %>",
		},
		src: [
			"<%= paths.languages %><%= files.pot.js %>",
		],
		dest: "languages",
	},
};
