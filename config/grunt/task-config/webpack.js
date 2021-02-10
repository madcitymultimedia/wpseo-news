/* global require */
const webpackConfig = require( "../../webpack/webpack.config" );

module.exports = ( grunt ) => {
	return {
		watch: () => webpackConfig( { environment: "development", watch: true } ),
		buildDev: () => webpackConfig( { environment: "development" } ),
		buildProd: () => webpackConfig( { environment: "production" } ),
	};
};
