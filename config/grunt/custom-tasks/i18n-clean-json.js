module.exports = function( grunt ) {
	/**
	 * Filters Jed JSON to only the strings specified in the filter.
	 *
	 * @param {string}   file            The file path to filter.
	 * @param {string[]} filter          The strings to keep.
	 * @param {string}   textdomain      The textdomain used in the files.
	 * @param {string}   [newTextdomain] The desired textdomain in the output
	 *                                   files.
	 *
	 * @returns {void}
	 */
	function filterJedJSON( file, filter, textdomain, newTextdomain = "" ) {
		if ( newTextdomain === "" ) {
			newTextdomain = textdomain;
		}

		var content = grunt.file.read( file );
		var json = JSON.parse( content );

		var strings = json.locale_data[ textdomain ];

		Object.keys( strings ).forEach( function( key ) {
			if ( -1 === filter.indexOf( key ) ) {
				delete( strings[ key ] );
			}
		} );

		delete json.locale_data[ textdomain ];
		strings[ "" ].domain = newTextdomain;
		json.locale_data[ newTextdomain ] = strings;

		// This property is added by GlotPress which is unnecessary.
		if ( json.hasOwnProperty( "translation-revision-date" ) ) {
			delete json[ "translation-revision-date" ];
		}

		// This property is added by GlotPress which is unnecessary.
		if ( json.hasOwnProperty( "generator" ) ) {
			delete json.generator;
		}

		json.domain = newTextdomain;

		content = JSON.stringify( json );

		grunt.file.write( file, content );
	}

	/**
	 * Cleans all strings in a Jed JSON files that are not relevant.
	 *
	 * @param {string} relevantStringsJSON   A path to the file that contains
	 *                                       the relevant string as a Jed JSON.
	 * @param {string} filesToFilter         A file glob with all files that
	 *                                       need to be cleaned.
	 * @param {string} textdomain            The textdomain used in the files.
	 * @param {string} wpGlotPressTextdomain The textdomain that GlotPress puts
	 *                                       in te downloaded files.
	 * @param {string} [newTextdomain]       The desired textdomain in the
	 *                                       output files.
	 *
	 * @returns {void}
	 */
	function cleanJSON( relevantStringsJSON, filesToFilter, textdomain, wpGlotPressTextdomain, newTextdomain = "" ) {
		let jsStrings = grunt.file.read( relevantStringsJSON );
		const filter = [];

		jsStrings = JSON.parse( jsStrings );
		jsStrings = jsStrings.locale_data;
		jsStrings = jsStrings[ textdomain ];
		for ( let jsString in jsStrings ) {
			filter.push( jsString );
		}

		const jsonFiles = grunt.file.expand( filesToFilter );
		jsonFiles.forEach( function( file ) {
			filterJedJSON( file, filter, wpGlotPressTextdomain, newTextdomain );
		} );
	}

	/**
	 * Cleans JSON files to remove strings we don't want don't need in a particular file.
	 *
	 * @returns {void}
	 */
	function cleanJSONFiles() {
		const languagePath = grunt.config.get( "paths.languages" );
		const textdomain = grunt.config.get( "pkg.plugin.textdomain" );

		cleanJSON( `${languagePath}${textdomain}js.json`, `${languagePath}${textdomain}js-*.json`, textdomain, "messages", textdomain );
	}

	grunt.registerTask( "i18n-clean-json", "Cleans JSON files", cleanJSONFiles );
};
