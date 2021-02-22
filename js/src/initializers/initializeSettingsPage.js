import { render } from "@wordpress/element";
const PersistentDismissableAlert = window.yoast.editorModules.containers.PersistentDismissableAlert;

const genreRemovalAlertElement = document.getElementById( "wpseo-news-genre-removal-alert" );

/**
 * Renders the React content on the Settings pages.
 *
 * @returns {Object} React Element.
 */
export default function initializeSettingsPage() {
	render(
		<PersistentDismissableAlert
			store="yoast-seo/settings"
			type="info"
			alertKey="news-settings-genre-removal-alert"
		>
			Google no longer supports 'Genres' for articles in Google News, therefore we decided to remove the 'Default genre' setting below.
		</PersistentDismissableAlert>,
		genreRemovalAlertElement
	);
}
