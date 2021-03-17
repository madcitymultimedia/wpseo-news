import { get } from "lodash";
import EditorFields from "../../helpers/fields/EditorFields";
import { INITIAL_STATE } from "../reducers/editor";

export const LOAD_EDITOR_DATA = "LOAD_EDITOR_DATA";
export const TOGGLE_IS_EXCLUDED = "TOGGLE_IS_EXCLUDED";
export const SET_STOCK_TICKERS = "SET_STOCK_TICKERS";

/**
 * Creates an action to load the News editor data.
 *
 * @returns {Object} A LOAD_EDITOR_DATA action.
 */
export function loadEditorData() {
	return {
		type: LOAD_EDITOR_DATA,
		changesAlertLink: get( window, "wpseoNewsScriptData.newsChangesAlertLink", INITIAL_STATE.changesAlertLink ),
		isExcluded: EditorFields.isExcluded,
		stockTickers: EditorFields.stockTickers,
	};
}

/**
 * Creates an action to toggle is excluded.
 *
 * @returns {Object} A TOGGLE_IS_EXCLUDED action.
 */
export function toggleIsExcluded() {
	EditorFields.isExcluded = ! EditorFields.isExcluded;
	return {
		type: TOGGLE_IS_EXCLUDED,
	};
}

/**
 * Creates an action to set the stock tickers.
 *
 * @param {string} stockTickers The stockTickers to set.
 *
 * @returns {Object} A SET_STOCK_TICKERS action.
 */
export function setStockTickers( stockTickers ) {
	EditorFields.stockTickers = stockTickers;
	return {
		type: SET_STOCK_TICKERS,
		stockTickers,
	};
}
