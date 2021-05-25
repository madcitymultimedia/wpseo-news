/* eslint-disable no-useless-concat */
/* eslint-disable no-useless-escape */
/* eslint-disable max-len */
// Custom task
// Rem defaultChangelogEntrys: "Other:\n* Includes every change in Yoast SEO core " + "VERSIONNUMBER" + ". See the [core changelog](https://wordpress.org/plugins/wordpress-seo/#developers).\n",

module.exports = {
	"wordpress-seo-premium": {
		options: {
			// Premium header:
			// ### 15.9: February 23rd, 2021
			readmeFile: "./README.md",
			releaseInChangelog: /[#] \d+\.\d+(\.\d+)?\: /g,
			matchChangelogHeader: /Changelog\n=========\n\n/ig,
			newHeadertemplate: "Changelog\n=========\n\n" + "### " + "VERSIONNUMBER" + ": " + "DATESTRING"  + "\n",
			matchCorrectLines: "### " + "VERSIONNUMBER" + "(.|\\n)*?(?=(### \\d+[\.\\d]+\: |$))",
			matchCorrectHeader: "### " + "VERSIONNUMBER" + "(.|\\n)*?\\n(?=(\\w\+?:\\n|### \\d+[\.\\d]+\: |$))",
			matchCleanedChangelog: "### " + "VERSIONNUMBER" + "(.|\\n)*$",
			replaceCleanedChangelog: "",
			defaultChangelogEntries: "",
			useANewLineAfterHeader: false,
			useEditDistanceCompare: true,
			commitChangelog: false,
		},
	},
};
