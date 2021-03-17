/* global yoast */
const { setTextdomainL10n } = yoast.editorModules.helpers.i18n;

/**
 * Initializes the News translations.
 */
export default function initializeTranslations() {
	setTextdomainL10n( "wordpress-seo-news", "wpseoNewsJSL10n" );
}
