/* global require, module */
const path = require( "path" );

const jsDistPath = path.resolve( "js", "dist" );
const jsSrcPath = path.resolve( "js", "src" );

// Output filename: Entry file (relative to jsSrcPath)
const entry = {
	"yoast-seo-news-editor": "./yoast-seo-news-editor.js",
	"yoast-seo-news-settings": "./yoast-seo-news-settings.js",
};

module.exports = {
	entry,
	jsDist: jsDistPath,
	jsSrc: jsSrcPath,
};
