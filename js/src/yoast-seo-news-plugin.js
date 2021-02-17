import domReady from "@wordpress/dom-ready";
import initializeEditorStore from "./initializers/initializeEditorStore";

jQuery( document ).ready( function() {
	initializeEditorStore();
} );

domReady( () => {
	initializeEditorStore();
} );
