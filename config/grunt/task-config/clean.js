// See https://github.com/gruntjs/grunt-contrib-clean for details.
module.exports = {
	"language-files": [
		"<%= paths.languages %>*",
		"!<%= paths.languages %>index.php",
		"<%= files.pot.makepot %>",
	],
	"after-build-language-files": [
		// Remove the @wordpress/babel-plugin-makepot output.
		"<%= files.pot.makepot %>",
		// Remove the contents of the language folder, with some exceptions.
		"<%= paths.languages %>*",
		// Keep the "Nothing to see here" index.php file.
		"!<%= paths.languages %>index.php",
		// Keep the plugin pot file for GitHub.
		"!<%= paths.languages %><%= files.pot.plugin %>",
		// Keep the MO files for the zip.
		"!<%= paths.languages %>*.mo",
		// Keep the JavaScript JSON files for the zip.
		"!<%= paths.languages %><%= pkg.plugin.textdomain %>js.json",
		"!<%= paths.languages %><%= pkg.plugin.textdomain %>js-*.json",
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
