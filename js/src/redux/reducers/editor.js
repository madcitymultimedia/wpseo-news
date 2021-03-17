import { LOAD_EDITOR_DATA, SET_STOCK_TICKERS, TOGGLE_IS_EXCLUDED } from "../actions";

export const INITIAL_STATE = {
	isLoaded: false,
	changesAlertLink: "https://yoa.st/news-changes",
	isExcluded: false,
	stockTickers: "",
};

/**
 * A reducer for the News editor reducer.
 *
 * @param {Object} state  The current state of the object.
 * @param {Object} action The current action received.
 *
 * @returns {Object} The state.
 */
export default function editorReducer( state = INITIAL_STATE, action ) {
	switch ( action.type ) {
		case LOAD_EDITOR_DATA:
			return {
				...state,
				changesAlertLink: action.changesAlertLink,
				isExcluded: action.isExcluded,
				stockTickers: action.stockTickers,
			};
		case TOGGLE_IS_EXCLUDED:
			return {
				...state,
				isExcluded: ! state.isExcluded,
			};
		case SET_STOCK_TICKERS:
			return {
				...state,
				stockTickers: action.stockTickers,
			};
		default:
			return state;
	}
}
