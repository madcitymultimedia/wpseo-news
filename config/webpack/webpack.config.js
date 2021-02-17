const CaseSensitivePathsPlugin = require( "case-sensitive-paths-webpack-plugin" );
const path = require( "path" );
const { isString, mapValues } = require( "lodash" );
const webpack = require( "webpack" );
const { externals, wordpressExternals } = require( "./externals" );
const { flattenVersionForFile } = require( "../grunt/lib/version.js" );
const paths = require( "./paths" );

const root = path.join( __dirname, "../../" );
const mainEntry = mapValues( paths.entry, entry => {
	if ( ! isString( entry ) ) {
		return entry;
	}

	return "./" + path.join( "js/src/", entry );
} );

module.exports = function( env ) {
	if ( ! env ) {
		env = {};
	}
	if ( ! env.environment ) {
		env.environment = process.env.NODE_ENV || "production";
	}
	if ( ! env.pluginVersion ) {
		// eslint-disable-next-line global-require
		env.pluginVersion = require( root + "package.json" ).yoast.pluginVersion;
	}

	const pluginVersionSlug = flattenVersionForFile( env.pluginVersion );

	const plugins = [
		new webpack.DefinePlugin( {
			"process.env": {
				NODE_ENV: JSON.stringify( env.environment ),
			},
		} ),
		new CaseSensitivePathsPlugin(),
	];

	const base = {
		mode: env.environment,
		watch: env.watch,
		devtool: env.environment === "development" ? "source-map" : false,
		context: root,
		output: {
			path: paths.jsDist,
			filename: "[name]-" + pluginVersionSlug + ".js",
		},
		module: {
			rules: [
				{
					test: /.jsx?$/,
					exclude: /node_modules/,
					use: [
						{
							loader: "babel-loader",
						},
					],
				},
			],
		},
		externals: {
			...externals,
			...wordpressExternals,
		},
		optimization: {
			minimize: true,
		},
		plugins,
	};

	return [
		{
			...base,
			entry: {
				...mainEntry,
			},
		},
	];
};
