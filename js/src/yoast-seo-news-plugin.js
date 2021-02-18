import domReady from "@wordpress/dom-ready";
import initializeEditorStore from "./initializers/initializeEditorStore";

domReady( () => {
	initializeEditorStore();
} );
