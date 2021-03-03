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
	"@wordpress/components",
	"@wordpress/compose",
	"@wordpress/data",
	"@wordpress/dom-ready",
	"@wordpress/element",
	'@wordpress/hooks',
	"@wordpress/i18n",
	"@wordpress/plugins",
];

const wordpressExternals = wordpressPackages.reduce( ( memo, packageName ) => {
	const name = camelCaseDash( packageName.replace( "@wordpress/", "" ) );

	memo[ packageName ] = `window.wp.${ name }`;
	return memo;
}, {} );

const yoastExternals = {
	"@yoast/components": "window.yoast.componentsNew",
};

/**
 * Export the data.
 */
module.exports = {
	externals,
	wordpressExternals,
	yoastExternals,
};
