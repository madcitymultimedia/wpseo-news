const prefix = "yoast_wpseo_newssitemap-";

/**
 * This class is responsible for handling the interaction with the hidden fields for News SEO.
 */
export default class EditorFields {
	/**
	 * Getter for the isExcludedElement.
	 *
	 * @returns {HTMLElement} The isExcludedElement.
	 */
	static get isExcludedElement() {
		/*
		 * The `exclude` and `robots-index` options merged into the latter.
		 * The interface is still saying `Excluded`.
		 * That is why this is called Excluded but gets the value from `robots-index`.
		 */
		return document.getElementById( this.prefixElementId( "robots-index" ) );
	}

	/**
	 * Getter for the stockTickersElement.
	 *
	 * @returns {HTMLElement} The stockTickersElement.
	 */
	static get stockTickersElement() {
		return document.getElementById( this.prefixElementId( "stocktickers" ) );
	}

	/**
	 * Getter for the isExcluded setting.
	 *
	 * @returns {Boolean} The isExcluded setting.
	 */
	static get isExcluded() {
		return EditorFields.isExcludedElement && EditorFields.isExcludedElement.value === "1" || false;
	}

	/**
	 * Setter for the isExcluded setting.
	 *
	 * @param {boolean} value The value to set.
	 *
	 * @returns {void}
	 */
	static set isExcluded( value ) {
		EditorFields.isExcludedElement.value = value ? "1" : "0";
	}

	/**
	 * Getter for the stockTickers.
	 *
	 * @returns {string} The stockTickers.
	 */
	static get stockTickers() {
		return EditorFields.stockTickersElement && EditorFields.stockTickersElement.value;
	}

	/**
	 * Setter for the stockTickers.
	 *
	 * @param {string} value The value to set.
	 *
	 * @returns {void}
	 */
	static set stockTickers( value ) {
		EditorFields.stockTickersElement.value = value;
	}

	/**
	 * Prefixes the passed id with the prefix.
	 *
	 * @param {string} partialId The id to prefix.
	 *
	 * @returns {string} The prefixed id.
	 */
	static prefixElementId( partialId ) {
		return prefix + partialId;
	}
}
