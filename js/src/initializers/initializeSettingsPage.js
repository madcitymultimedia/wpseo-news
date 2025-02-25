import React from "react";
import { render } from "@wordpress/element";
import { __ } from "@wordpress/i18n";

const PersistentDismissableAlert = window.yoast.editorModules.containers.PersistentDismissableAlert;

const genreRemovalAlertElement = document.getElementById( "wpseo-news-genre-removal-alert" );

/**
 * Renders the React content on the Settings pages.
 *
 * @returns {void}
 */
export default function initializeSettingsPage() {
	render(
		<PersistentDismissableAlert
			store="yoast-seo/settings"
			type="info"
			alertKey="news-settings-genre-removal-alert"
		>
			{ /* eslint-disable-next-line max-len */ }
			{ __( "Google no longer supports 'Genres' for articles in Google News, therefore we decided to remove the 'Default genre' setting below.", "wordpress-seo-news" ) }
		</PersistentDismissableAlert>,
		genreRemovalAlertElement,
	);
}
