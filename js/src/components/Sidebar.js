import React from "react";
import { Fill } from "@wordpress/components";
import NewsEditorContainer from "../containers/EditorContainer";
import PropTypes from "prop-types";

const { SidebarItem, SidebarCollapsible } = window.yoast.editorModules.components;

/**
 * Wraps the NewsEditorContainer in required Sidebar components.
 *
 * @returns {ReactElement} The VideoSidebar
 */
export default function Sidebar( { fillName = "YoastSidebar" } ) {
	return (
		<Fill name={ fillName }>
			<SidebarItem key="news" renderPriority={ 33 }>
				<SidebarCollapsible title="News">
					<NewsEditorContainer />
				</SidebarCollapsible>
			</SidebarItem>
		</Fill>
	);
}

Sidebar.propTypes = {
	// Using defaultProps in functional components is deprecated. The defaults are specified in the function arguments.
	// eslint-disable-next-line react/require-default-props
	fillName: PropTypes.string,
};
