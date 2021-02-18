const { camelCaseDash } = require( "@wordpress/dependency-extraction-webpack-plugin/lib/util" );

const externals = {
	lodash: "window.lodash",
	react: "React",
	"react-dom": "ReactDOM",
};

/**
 * WordPress dependencies.
 */
const wordpressPackages = [
	"@wordpress/data",
	"@wordpress/dom-ready",
	"@wordpress/element",
	"@wordpress/i18n",
];

const wordpressExternals = wordpressPackages.reduce( ( memo, packageName ) => {
	const name = camelCaseDash( packageName.replace( "@wordpress/", "" ) );

	memo[ packageName ] = `window.wp.${ name }`;
	return memo;
}, {} );

/**
 * Export the data.
 */
module.exports = {
	externals,
	wordpressExternals,
};
