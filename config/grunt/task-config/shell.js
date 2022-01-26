// See https://github.com/sindresorhus/grunt-shell
module.exports = function( grunt ) {
	return {
		"composer-install": {
			command: "composer install --no-interaction",
		},

		"php-lint": {
			command: "composer lint",
		},
		phpcs: {
			command: "composer check-cs",
		},

		"makepot-wordpress-seo-news": {
			command: [
				// Ensure the minimum makepot file exists, even when there are no JS translations.
				"echo 'msgid \"\"\nmsgstr \"Content-Type: text/plain; charset=utf-8\"\n' > <%= files.pot.makepot %>",
				// Creates `gettext.pot` file with the translations from the JS code.
				"yarn i18n-wordpress-seo-news",
			].join( "&&" ),
		},
		"combine-pot-files": {
			fromFiles: [
				"<%= paths.languages %><%= files.pot.php %>",
				"<%= paths.languages %><%= files.pot.js %>",
			],
			toFile: "<%= paths.languages %><%= files.pot.plugin %>",
			/**
			 * Gets the command that combines js.pot and php.pot files into a single .pot file.
			 *
			 * @returns {string} The command that combines js.pot and php.pot files into a single .pot file.
			 */
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
