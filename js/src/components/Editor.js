import { createInterpolateElement, useEffect } from "@wordpress/element";
import { __, sprintf } from "@wordpress/i18n";
import { Checkbox, TextInput } from "@yoast/components";
import * as PropTypes from "prop-types";

const { location: { LocationConsumer } } = window.yoast.editorModules.components.contexts;
const { PersistentDismissableAlert } = window.yoast.editorModules.containers;

/**
 * The NewsEditor component.
 *
 * @param {Object} props The props.
 *
 * @returns {ReactElement} The NewsEditor element.
 */
export default function Editor( props ) {
	useEffect( () => {
		props.onLoad();
	}, [] );

	/* eslint-disable jsx-a11y/anchor-has-content */
	const alertContent = createInterpolateElement(
		/* Translators: %s expands to an opening anchor tag, %s expands to a closing anchor tag */
		sprintf( __( "We've made some changes to Yoast SEO News. %sLearn more about what has changed%s", "wordpress-seo-news" ), "<a>", "</a>" ),
		{
			a: <a
				href={ props.changesAlertLink }
				target="_blank"
				rel="noopener noreferrer"
			/>,
		},
	);
	/* eslint-enable jsx-a11y/anchor-has-content */

	/* Translators: %s expands to the name of the post type */
	const excludeLabel = sprintf( __( "Exclude this %s from Google News", "wordpress-seo-news" ), props.postTypeName );

	return (
		<LocationConsumer>
			{ location => {
				return (
					<div className="yoast">
						<PersistentDismissableAlert alertKey="news-editor-changes-alert" type="info">
							{ alertContent }
						</PersistentDismissableAlert>
						<Checkbox
							id={ `yoast-news-exclude-${ location }` }
							label={ excludeLabel }
							checked={ props.isExcluded }
							onChange={ props.toggleIsExcluded }
						/>
						<TextInput
							id={ `yoast-news-stock-tickers-${ location }` }
							label={ __( "Stock tickers", "wordpress-seo-news" ) }
							description={ __( "A comma-separated list of up to 5 stock tickers of the companies, mutual funds, or other financial entities that are the main subject of the article. Each ticker must be prefixed by the name of its stock exchange, and must match its entry in Google Finance. For example, \"NASDAQ:AMAT\" (but not \"NASD:AMAT\"), or \"BOM:500325\" (but not \"BOM:RIL\").", "wordpress-seo-news" ) }
							value={ props.stockTickers }
							onChange={ props.setStockTickers }
						/>
					</div>
				);
			} }
		</LocationConsumer>
	);
}

Editor.propTypes = {
	changesAlertLink: PropTypes.string.isRequired,
	isExcluded: PropTypes.bool.isRequired,
	postTypeName: PropTypes.string.isRequired,
	stockTickers: PropTypes.string.isRequired,
	onLoad: PropTypes.func.isRequired,
	toggleIsExcluded: PropTypes.func.isRequired,
	setStockTickers: PropTypes.func.isRequired,
};
