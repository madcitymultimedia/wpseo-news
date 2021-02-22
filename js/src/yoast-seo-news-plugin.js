import domReady from "@wordpress/dom-ready";
import initializeEditorStore from "./initializers/initializeEditorStore";
import initializeTranslations from "./initializers/initializeTranslations";
import initializeSettingsPage from "./initializers/initializeSettingsPage";

domReady( () => {
	initializeTranslations();
	initializeEditorStore();
	initializeSettingsPage();
} );
