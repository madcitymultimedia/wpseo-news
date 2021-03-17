import { compose } from "@wordpress/compose";
import { withDispatch, withSelect } from "@wordpress/data";
import Editor from "../components/Editor";

const newsEditorStore = "yoast-seo-news/editor";

export default compose( [
	withSelect( select => {
		const { getChangesAlertLink, getIsExcluded, getStockTickers } = select( newsEditorStore );
		const { getPostOrPageString } = select( "yoast-seo/editor" );

		return {
			changesAlertLink: getChangesAlertLink(),
			isExcluded: getIsExcluded(),
			postTypeName: getPostOrPageString(),
			stockTickers: getStockTickers(),
		};
	} ),

	withDispatch( dispatch => {
		const { loadEditorData, toggleIsExcluded, setStockTickers } = dispatch( newsEditorStore );

		return {
			onLoad: loadEditorData,
			toggleIsExcluded,
			setStockTickers,
		};
	} ),
] )( Editor );
