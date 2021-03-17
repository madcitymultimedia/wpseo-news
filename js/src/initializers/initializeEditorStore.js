import { registerStore, combineReducers } from "@wordpress/data";
import * as actions from "../redux/actions";
import reducers from "../redux/reducers";
import * as selectors from "../redux/selectors";

/**
 * Registers a redux store to WordPress.
 *
 * @returns {Object} The store.
 */
export default function initializeEditorStore() {
	return registerStore( "yoast-seo-news/editor", {
		reducer: combineReducers( reducers ),
		actions,
		selectors,
	} );
}
