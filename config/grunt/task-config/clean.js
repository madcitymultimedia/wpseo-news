// See https://github.com/gruntjs/grunt-contrib-clean for details.
module.exports = {
	"language-files": [
		"<%= paths.languages %>*",
		"!<%= paths.languages %>index.php",
	],
	"after-build-language-files": [
		"<%= paths.languages %>*",
		"!<%= paths.languages %>index.php",
		// Keep the plugin pot file for GitHub.
		"!<%= paths.languages %><%= files.pot.plugin %>",
		// Keep the MO files for the zip.
		"!<%= paths.languages %>*.mo",
	],
	"after-po-download": [
		"<%= paths.languages %><%= pkg.plugin.textdomain %>-*-{formal,informal,ao90}.{po,json}",
	],
	"po-files": [
		"<%= paths.languages %>*.po",
		"<%= paths.languages %>*.pot",
	],
	"build-assets-js": [
		"<%= paths.jsDist %>",
	],
	artifact: [
		"<%= files.artifact %>",
	],
	"composer-artifact": [
		"<%= files.artifactComposer %>",
	],
	"composer-files": [
		"<%= files.artifactComposer %>/vendor",
	],
};
