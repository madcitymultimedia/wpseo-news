// https://github.com/markoheijnen/grunt-glotpress
/* eslint-disable camelcase */
module.exports = function() {
	const base = {
		url: "<%= pkg.plugin.glotpress %>",
		domainPath: "<%= paths.languages %>",
		file_format: "%domainPath%/%textdomain%-%wp_locale%",
		slug: "<%= pkg.plugin.slug %>",
		textdomain: "<%= pkg.plugin.textdomain %>",
		formats: [],
		filter: {
			translation_sets: false,
			minimum_percentage: 50,
			waiting_strings: false,
		},
	};

	return {
		plugin: {
			options: {
				...base,
				formats: [ "mo" ],
				file_format: `${ base.file_format }.%format%`,
			},
		},
		"plugin-json": {
			options: {
				...base,
				file_format: `${ base.file_format }.json`,
				formats: [ "jed1x" ],
				batchSize: 5,
			},
		},
	};
};
/* eslint-enable camelcase */
