/* global wpseoNewsScriptData */
import { render } from "@wordpress/element";
import { addAction } from "@wordpress/hooks";
import { registerPlugin } from "@wordpress/plugins";
import Metabox from "../components/Metabox";
import Sidebar from "../components/Sidebar";

/**
 * Initializes the metabox.
 */
function initializeMetabox() {
	const element = document.getElementById( "wpseo-news-metabox-root" );

	if ( element ) {
		render( <Metabox />, element );
	}
}

/**
 * Initializes the sidebar.
 */
function initializeSidebar() {
	if ( wpseoNewsScriptData.isBlockEditor ) {
		registerPlugin( "yoast-seo-news", { render: Sidebar } );
	}
}

/**
 * Initializes the sidebar in Elementor.
 */
function initializeElementorSidebar() {
	/**
	 * @returns {ReactElement} The Sidebar for Elementor.
	 */
	const ElementorSidebar = () => <Sidebar fillName="YoastElementor" />;

	addAction( "yoast.elementor.loaded", "yoast/yoast-news-seo/load-news-in-elementor", () => {
		window.YoastSEO._registerReactComponent( "yoast-seo-news", ElementorSidebar );
	} );
}

/**
 * Initializes the content on the editor page.
 */
export default function initializeEditorPage() {
	initializeMetabox();
	initializeSidebar();
	initializeElementorSidebar();
}
