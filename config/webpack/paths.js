/* global require, module */
const path = require( "path" );

const jsDistPath = path.resolve( "js", "dist" );
const jsSrcPath = path.resolve( "js", "src" );

// Output filename: Entry file (relative to jsSrcPath)
const entry = {
	"yoast-seo-news-plugin": "./yoast-seo-news-plugin.js",
};

module.exports = {
	entry,
	jsDist: jsDistPath,
	jsSrc: jsSrcPath,
};
