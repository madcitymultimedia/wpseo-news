import domReady from "@wordpress/dom-ready";
import initializeTranslations from "./initializers/initializeTranslations";
import initializeSettingsPage from "./initializers/initializeSettingsPage";

domReady( () => {
	initializeTranslations();
	initializeSettingsPage();
} );
