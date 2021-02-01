// See https://github.com/sindresorhus/grunt-shell
module.exports = function( grunt ) {
	return {
		"composer-install": {
			command: "composer install",
		},

		"php-lint": {
			command: "composer lint",
		},
		phpcs: {
			command: "composer check-cs",
		},

		"makepot-wordpress-seo-news": {
			// Creates `gettext.pot` file with the translations from the JS code.
			command: "yarn i18n-wordpress-seo-news",
		},
		"combine-pot-files": {
			fromFiles: [
				"<%= paths.languages %><%= files.pot.php %>",
				"<%= paths.languages %><%= files.pot.js %>",
			],
			toFile: "<%= paths.languages %><%= files.pot.plugin %>",
			command: function() {
				const files = grunt.config.get( "shell.combine-pot-files.fromFiles" );
				const toFile = grunt.config.get( "shell.combine-pot-files.toFile" );

				return "msgcat" +
					" --use-first" +
					" " + files.join( " " ) +
					" > " + toFile;
			},
		},
	};
};
