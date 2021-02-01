/* global require, process */
const path = require( "path" );
const loadGruntConfig = require( "load-grunt-config" );
const timeGrunt = require( "time-grunt" );
const { flattenVersionForFile } = require( "./config/grunt/lib/version.js" );

module.exports = function( grunt ) {
	timeGrunt( grunt );

	const pkg = grunt.file.readJSON( "package.json" );
	const pluginVersion = pkg.yoast.pluginVersion;
	const developmentBuild = ! [ "release", "artifact" ].includes( process.argv[ 2 ] );

	// Define project configuration.
	const project = {
		developmentBuild,
		pluginVersion,
		pluginSlug: "wpseo-news",
		pluginMainFile: "wpseo-news.php",
		pluginVersionConstant: "WPSEO_NEWS_VERSION",
		paths: {
			/**
			 * Gets the config path.
			 *
			 * @returns {string} Config path.
			 */
			get config() {
				return this.grunt + "task-config/";
			},
			grunt: "config/grunt/",
			jsSrc: "js/src/",
			jsDist: "js/dist/",
			languages: "languages/",
			logs: "logs/",
			vendor: "vendor/",
			vendorYoast: "vendor/yoast/",
		},
		files: {
			/**
			 * Gets the config path glob.
			 *
			 * @returns {string} Config path glob.
			 */
			get config() {
				return project.paths.config + "*.js";
			},
			grunt: "Gruntfile.js",
			artifact: "artifact",
			js: [
				"<%= paths.jsSrc %>**/*.js",
			],
			php: [
				"*.php",
				"classes/**/*.php",
			],
			phptests: "tests/**/*.php",
			pot: {
				plugin: "<%= pkg.plugin.textdomain %>.pot",
				php: "<%= pkg.plugin.textdomain %>php.pot",
				js: "<%= pkg.plugin.textdomain %>js.pot",
				makepot: "gettext.pot",
			},
		},
		pkg: grunt.file.readJSON( "package.json" ),
	};

	project.pluginVersionSlug = flattenVersionForFile( pluginVersion );

	/* eslint-disable camelcase */
	// Load Grunt configurations and tasks.
	loadGruntConfig( grunt, {
		configPath: path.join( process.cwd(), "node_modules/@yoast/grunt-plugin-tasks/config/" ),
		overridePath: path.join( process.cwd(), project.paths.config ),
		data: project,
		jitGrunt: {
			staticMappings: {
				addtextdomain: "grunt-wp-i18n",
				makepot: "grunt-wp-i18n",
				glotpress_download: "grunt-glotpress",
				"update-version": "./node_modules/@yoast/grunt-plugin-tasks/tasks/update-version.js",
				"set-version": "./node_modules/@yoast/grunt-plugin-tasks/tasks/set-version.js",
			},
		},
	} );
	/* eslint-enable camelcase */
};
