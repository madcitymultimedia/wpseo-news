import { Fill } from "@wordpress/components";
import NewsEditorContainer from "../containers/EditorContainer";

const { SidebarItem, SidebarCollapsible } = window.yoast.editorModules.components;

/**
 * Wraps the NewsEditorContainer in required Sidebar components.
 *
 * @returns {ReactElement} The VideoSidebar
 */
export default function Sidebar( { fillName = "YoastSidebar" } ) {
	return (
		<Fill name={ fillName }>
			<SidebarItem renderPriority={ 33 }>
				<SidebarCollapsible title="News">
					<NewsEditorContainer />
				</SidebarCollapsible>
			</SidebarItem>
		</Fill>
	);
}
