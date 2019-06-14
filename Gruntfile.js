/* global require, process */
var path = require( "path" );
var loadGruntConfig = require( "load-grunt-config" );
var timeGrunt = require( "time-grunt" );

const { flattenVersionForFile } = require( "./grunt/modules/version.js" );

module.exports = function( grunt ) {
	"use strict";

	timeGrunt( grunt );

	const pkg = grunt.file.readJSON( "package.json" );
	const pluginVersion = pkg.yoast.pluginVersion;

	// Define project configuration
	var project = {
		pluginVersion: pluginVersion,
		pluginSlug: "wpseo-news",
		pluginMainFile: "wpseo-news.php",
		pluginVersionConstant: "WPSEO_NEWS_VERSION",
		paths: {
			get config() {
				return this.grunt + "config/";
			},
			grunt: "grunt/",
			js: "assets/",
			languages: "languages/",
			logs: "logs/",
			vendor: "vendor/"
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
	loadGruntConfig(grunt, {
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
	});
};
