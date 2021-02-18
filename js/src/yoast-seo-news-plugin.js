import domReady from "@wordpress/dom-ready";
import initializeEditorStore from "./initializers/initializeEditorStore";
import initializeTranslations from "./initializers/initializeTranslations";

domReady( () => {
	initializeTranslations();
	initializeEditorStore();
} );
