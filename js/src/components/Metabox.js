import NewsEditorContainer from "../containers/EditorContainer";

const { location: { LocationProvider } } = window.yoast.editorModules.components.contexts;

/**
 * Wraps the NewsEditorContainer in required Metabox components.
 *
 * @returns {ReactElement} The NewsMetabox.
 */
export default function Metabox() {
	return (
		<LocationProvider value="metabox">
			<NewsEditorContainer />
		</LocationProvider>
	);
}
