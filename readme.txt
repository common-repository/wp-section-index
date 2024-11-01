=== WP Section Index ===
Contributors: mattyza
Donate link: 
Tags: table of contents, section index, widget, links, on-page navigation, wp section index
Requires at least: 2.9
Tested up to: 3.1-beta 2
Stable tag: 1.1.1

Create a table of contents in a widget for the current page or blog post, using headings from the content.

== Description ==

WP Section Index is aimed at users who write long blog posts or pages and divide them up using headings.

The plugin retrieves the headings from the content of the Page or blog Post being viewed, adds anchors to them and creates a neat list (in a widget) that allows the user to easily navigate the content, finding either the content for which they were specifically looking, or finding the content which they would find most useful. This list is also aimed at providing the user with a list of headings, explaining what the Page or blog Post is about.

If you enjoy writing long blog posts, but fear that your users won't read through the entire post due to it's length, fear no more. WP Section Index will make reading those long blog posts that much easier for your users.

== Installation ==

Installing WP Section Index is really easily. Simply follow the steps below:

1. Upload the `wp-section-index` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Setup the Section Index via the 'Settings' menu in WordPress (there's a "Section Index" menu)
1. Load the 'Section Index' widget into one of your theme's widgetised areas (preferably one that is viewable on both Pages and blog Posts)

When the plugin is activated, the setup process is clearly outlined via a series of administrator notices displayed near the top of the WordPress administration area.

== Frequently Asked Questions ==

= So, how does this plugin work anyway? =

The Section Index plugin is used in conjunction with a widget, displaying the section index for the page currently viewed by the user. The plugin looks through your content and creates easy-to-navigate-to sections within the content. This is ideal for long Pages or blog Posts.

= The section index isn't displaying. What gives? =

If the widget is not visible on the Page or Post, the section index will not display. By the same token, if the widget is enabled and there are no sections for the Page or Post in question (or the section index has been disabled for that Page or Post) the widget displaying the section index will not display.

= What's this about a "Back to top" element ID? =

This is the ID of an element (a `div` tag, for example) in your theme to which you would like the user to jump when a "Back to top" link is clicked. It is recommended that this element be somewhere near the top of the screen, usually a `header` or `top` element. The ID can be found by viewing the source of the page and looking for a tag with the `id=""` attribute. The value inside the quotation marks is what is inputted in the `"Back to top" element ID` field.

== Screenshots ==

1. The administrator notices displayed when WP Section Index is activated. These prompt the setup of WP Section Index, as well as the insertion of the Section Index widget.
2. The Section Index settings screen, where all the options for WP Section Index are setup.
3. The widgets screen, depicting the Section Index widget (required to display the Section Index list).

== Changelog ==

= 1.1.1 =
* Fixed bug with section index not displaying on non-paginated Pages and Posts, when "display all anchors" is active.

= 1.1.0 =
* Added functionality to toggle the display of all section index anchors in the widget when on a paginated Post or Page.
* Added support for removing the "back to top ID", such that the page jumps to the top instead of to a specific element ID.

= 1.0.0 =
* Adjusted versioning structure to work as "Major Release"."New Feature"."Bug Fix". Starting at 1.0.0.
* Moved documentation on settings screen into the contextual help tab. Added links to support forum and documentation.
* Added "noindex" and "nofollow" to the section index links, to prevent them from impacting on SEO.
* Optimised plugin for translation and the future creation of a POT language file.
* Added a first pass at a filter to add support for additional post types.
* Added activation hook for keeping track of the version of WP Section Index that is in use.

= 0.0.0.8 =
* Added the `wpsi_backtotop_text` filter to the text in the "Back to top" hyperlinks.

= 0.0.0.7 =
* Added more robust support for detecting XHTML heading tags with nested markup and attributes when detecting headings for processing.
* Added support for paginated pages and posts, to only show headings from the paged section being viewed.

= 0.0.0.6 =
* Added priorities to action hooks for compability with plugins that filter the_content(). Compatibility testing with WordPress 3.0.

= 0.0.0.5 =
* Alpha release.

== Upgrade Notice ==

= 1.1.1 =
* Bugfix: Section indexes now display on non-paginated Pages and Posts when "display all anchors" is active.

= 1.1.0 =
* New feature release. Support for displaying anchors for the entire Post or Page within a paginated post (can be turned on or off).
* The "back to top ID" is no longer a required field.

= 1.0.0 =
* Major version upgrade, including search engine optimisation enhancements and a start on support for custom post types.

= 0.0.0.8 =
* Added the `wpsi_backtotop_text` filter to the text in the "Back to top" hyperlinks.

= 0.0.0.7 =
* Added more robust support for detecting XHTML heading tags with nested markup and attributes when detecting headings for processing.
* Added support for paginated pages and posts, to only show headings from the paged section being viewed.

= 0.0.0.6 =
* Added priorities to action hooks for compability with plugins that filter the_content(). Compatibility testing with WordPress 3.0.

= 0.0.0.5 =
Alpha release.