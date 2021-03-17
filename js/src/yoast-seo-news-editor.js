import domReady from "@wordpress/dom-ready";
import initializeEditorStore from "./initializers/initializeEditorStore";
import initializeTranslations from "./initializers/initializeTranslations";
import initializeEditorPage from "./initializers/initializeEditorPage";

domReady( () => {
	initializeTranslations();
	initializeEditorStore();
	initializeEditorPage();
} );
