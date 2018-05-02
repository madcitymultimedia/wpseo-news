/* global require, process */
const { flattenVersionForFile } = require( "./grunt/modules/version.js" );

module.exports = function( grunt ) {
	"use strict";

	require( "time-grunt" )( grunt );

	const pkg = grunt.file.readJSON( "package.json" );
	const pluginVersion = pkg.yoast.pluginVersion;

	// Define project configuration
	var project = {
		pluginVersion: pluginVersion,
		paths: {
			get config() {
				return this.grunt + "config/";
			},
			grunt: "grunt/",
			js: "assets/",
			languages: "languages/",
			logs: "logs/"
		},
		files: {
			js: [
				"assets/**/*.js",
				"!assets/**/*.min.js"
			],
			php: [
				"*.php",
				"classes/**/*.php"
			],
			phptests: "tests/**/*.php",
			get config() {
				return project.paths.config + "*.js";
			},
			get changelog() {
				return project.paths.theme + "changelog.txt";
			},
			grunt: "Gruntfile.js"
		},
		pkg: grunt.file.readJSON( "package.json" )
	};

	project.pluginVersionSlug = flattenVersionForFile( pluginVersion );

	// Load Grunt configurations and tasks
	require( "load-grunt-config" )(grunt, {
		configPath: require( "path" ).join( process.cwd(), project.paths.config ),
		data: project,
		jitGrunt: {
			staticMappings: {
				addtextdomain: "grunt-wp-i18n",
				makepot: "grunt-wp-i18n",
				glotpress_download: "grunt-glotpress",
				"update-version": "grunt-yoast-tasks",
				"set-version": "grunt-yoast-tasks",
			},
		},
	});
};
