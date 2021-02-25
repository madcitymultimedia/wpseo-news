import { get } from "lodash";
import { INITIAL_STATE } from "../reducers/editor";

/**
 * Selects the changes alert link.
 *
 * @param {Object} state The state.
 *
 * @returns {string} The changes alert link.
 */
export function getChangesAlertLink( state ) {
	return get( state, "editor.changesAlertLink", INITIAL_STATE.changesAlertLink );
}

/**
 * Selects the is excluded.
 *
 * @param {Object} state The state.
 *
 * @returns {boolean} The is excluded.
 */
export function getIsExcluded( state ) {
	return get( state, "editor.isExcluded", INITIAL_STATE.isExcluded );
}

/**
 * Selects the stock tickers.
 *
 * @param {Object} state The state.
 *
 * @returns {string} The stock tickers.
 */
export function getStockTickers( state ) {
	return get( state, "editor.stockTickers", INITIAL_STATE.stockTickers );
}
