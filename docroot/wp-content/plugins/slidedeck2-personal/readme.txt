=== SlideDeck 2 Personal for WordPress ===
Contributors: dtelepathy, kynatro, jamie3d, dtrenkner, oriontimbers, nielsfogt, bkenyon, barefootceo, dtlabs
Donate link: http://www.slidedeck.com/
Tags: Slider, dynamic, responsive, image gallery, dtelepathy, digital telepathy, digital-telepathy, iPad, jquery, media, photo, pictures, plugin, posts, Search Engine Optimized, seo, skinnable, slide, slide show, slider, slideshow, theme, touch support, video, widget, Flickr, Instagram, 500px, RSS, Pinterest, Google+, Twitter, YouTube, Vimeo, Dailymotion, Picasa, Dribbble
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv3

Create responsive content sliders on your WordPress blogging platform. Manage SlideDeck content and insert them into templates and posts.

== Description ==

= Responsive Content Slider by SlideDeck 2 =
= Easily create responsive content sliders for your WordPress site without code. Use images & text, plus YouTube, Flickr, Pinterest & more =
SlideDeck 2 for WordPress is a responsive slider plugin that lets you easily create content sliders out of almost any content. Connect to a variety of Content Sources like YouTube, Flickr, Twitter, WordPress posts and Pinterest to create gorgeous, dynamic sliders in a few clicks - no coding is required.

**Requirements:** WordPress 3.3+, PHP5 and higher

**Important Links:**

* [More Details](http://www.slidedeck.com/)
* [Knowledge Base](https://dtelepathy.zendesk.com/categories/20031167-slidedeck-2)
* [Support](http://dtelepathy.zendesk.com/)

== Installation ==

1. Upload the `slidedeck2-personal` folder and all its contents to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a new SlideDeck from the “SlideDeck » Manage” menu in the control panel sidebar
4. Insert a SlideDeck in your post or page by clicking on the "Embed a SlideDeck" button above the rich text editor in the post/page view.

You can also place a SlideDeck in your template or theme via the PHP command “do_shortcode()”. Just pass the ID of the SlideDeck you want to render. For example:
`<?php echo do_shortcode( "[SlideDeck2 id=1644]" ); ?>`

Where 1644 is the SlideDeck's ID. You can also see this code snippet when you save a new SlideDeck for the first time.


== Changelog ==
= 2.1.20130325 =
* Reporter Lens: Fixed an issue where multiple decks on one page would not navigate correctly.
* Added h1, h2, h3, h4, h5, h6, pre, address tags to allowed tag list in Image custom slide type.
* Security improvements
* Renamed language files for proper inclusion
* Modified the way that custom taxonomies were looked up for the WordPress Posts Source
* Modified our caching methods to better work with Object Caching setups
* Added a new Advanced Option for aggressive cache flushing
* Updated image source handling for Dribble, Flickr, Google+/Google+ Images, Instagram - will use current protocol i.e. http/https
* Fixed some typos in plugin interfaces
* Updated font additions to remove protocol from source URL

= 2.1.20130228 =
* Fixing an issue with the O-Town and Fashion Lens JavaScript files
* Lenses can now have no variations and the variation dropdown is correctly hidden

= 2.1.20130219 =
* Updating the version of plupload in anticipation of jQuery 1.9
* Removing all calls to jQuery.browser or $.browser in anticipation of jQuery 1.9
* Updated custom slide CSS for images to fix an issue where the sd2-node-caption was showing up even though there was no content
* Attempted a fix for the fullscreen images flashing issue.
* Addressing an issue where editing a lens could result in a broken page

= 2.1.20130116 =
* Extracted a Regex for filtering images from feeds/sources, and added a filter for it `slidedeck_image_blacklist`
* Google Plus Image (Picasa) source was updated to support the new resolutions specified in the API. See this: http://goo.gl/pjkKP for more info (search for `imgmax`)
* Made an accommodation for a rare case where many WordPress custom taxonomies could make the WordPress Posts Source's taxonomy selector unusable
* Updated the button style of the `Insert SlideDeck` button to match the new WordPress 3.5 and higher look
* Tweaked the FancyForms dropdown styles so the z-indexing is no longer an issue
* Adjusted the following lenses for a mobile/responsive bug: fashion, half-moon, o-town, reporter, tool-kit, twitter.
* Fixed a warning message related to the $expansion_factor variable

= 2.1.20121212 =
* Updated references in activation routine to fix free to paid upgrade identification if free plugin was still activated
* Help us make SlideDeck 2 better! Added optional anonymous usage statistics opt-in.
* Improved the asset load order of SlideDecks
* Fixed an issue with video IDs containing double underscores and how they were handled
* Fixed an issue in the core JavaScript library that affected transparent decks and the fade transition (eg: Reporter)
* Added content/description pull for Flickr feed source
* Addressed an issue where upgrading from SlideDeck 2.0.x to SlideDeck 2.1.x would fail to import certain decks
* Fixed various edge-case bugs related to adding images to a custom deck; also made the UI a bit cleaner
* Updated namespace for fancy-form JavaScript and CSS assets to reduce possible namespace conflicts
* Fixed a few notices/errors when deleting a SlideDeck embedded in the theme
* Fixed an issue with Firefox versions 20 and higher
* Avatars are no longer loaded in the HTML if the `Show Author Avatar` option is set to `Off`
* Modified AJAX change lens method in SlideDeck interface to prevent regression bug

= 2.1.20121115 =
* Updated pathing for Ress js asset loading for better consitency across the board
* Adding a filter for the shortcode attributes.
* Adjusting the way that a proportional RESS raito is calculated (Should help with height on some RESS decks).
* Fixing a typo in the 'Overlays Always Open' tooltip.
* Adding Flickr Group support to the Flickr source (recent only, no tags)
* Adding image size choice to the WordPress Posts source. You can now choose auto or a specific registered size.
* Adding image size choice to the Custom Images source. You can now choose auto or a specific registered size.
* For custom slides, the erroneous addition of a date class was removed using a filter.
* Addressing an issue where the manage screen may fail and dislay this error: `Cannot use object of type WP_Error as array`
* Added Georgia to the default list of fonts.

= 2.1.20121102 =
* Fixed an issue where using the shortcode in your theme could cause SlideDeck to not render.

= 2.1.20121101 =
* Optimized the way that scripts and styles are loaded when using RESS.
* Moved RESS scripts into their own file, echoing them instead of including them.
* Fixed an issue where the "Add to SlideDeck" button would disappear after searching in the media upload modal (Custom Image Slides).
* Fixed an issue with Tool Kit lens that was preventing Custom Content video slide type colors from inheriting "Light" variation styles.
* Fixed a bug with JavaScript library that was preventing the "active" class from being applied to the active slide in transitions other than "Slide" or "Stack"
* Added an IE8 compatible background-color definition for the main navigation arrows
* Fixed a PHP warning in the widget.php file
* RSS feed source can now support multiple feeds
* Corrected constant definition of Custom Content slide types so that it is properly excluded from the WordPress Posts source list

= 2.1.20121017 =
* Fixed an issue where the responsive SlideDecks would fail in IE10
* Fixed an issue where the classic lens would not render correctly in IE 10
* SlideDeck now renders correctly in Chrome for iOS
* Loading of SlideDeck lenses no longer triggers a fatal error if the lens class was not unique
* Fixed an issue where playing a video slide (on mobile) woud not pause the SlideDeck autoPlay setting

= 2.1.20121010 =
* Fixed an issue where a warning was being thrown due to a lack of a `$found_lens_path`
* Updated URL pathing constant to use plugins_url() for better WordPress Network compatibility
* Updating the slidedeck_dimensions() filter for PHP 5.4 compatibility
* Added Title, Before, and After text to the SlideDeck WordPress Widget
* Corrected the default RSS feed
* Tool Kit Lens: Fixed an issue where the excerpt block could show up even if there was no excerpt.
* Custom Content SlideDecks with the image slide type are now available in the Personal license! You will still need Professional for Textonly and Video slide types.
* Proto Lens: Added some non rgba() CSS rules for IE8.
* Image bulk upload is back with a vengance! We've added the ability to bulk upload images to a Custom Content SlideDeck.
* Added some missing semicolons from the 'JavaScript Constants' that SlideDeck 2 outputs

= 2.1.20120919 =
* Updated the http_build_query() method that outputs the dimensions for iframe and RESS decks, added the ampersand as the separator. Some users were having the html encoded '&amp;' being output which was breaking the deck dimensions.
* Namespaced the SlideDeck SimpleModal JavaScript library further to avoid namespace conflicts with plugins like Shortcode Exec PHP and Ajax Event Calendar
* Fixed bug with IE 8 background image processing that was preventing images beyond the first slide from working in vertical SlideDecks.
* Fixed a display issue where the SlideDeck previews on the manage page where sometimes rendered at the wrong height.
* Fixed an issue where SlideDecks using the iframe=1 shortcode option had a larger than necessary height.
* Block Title Lens: Fixed an issue where the play video button was not clickable if the excerpt was turned off.
* Block Title Lens: Fixed a problem with content being hidden when the play video button was clicked.
* Reporter Lens: Including multiple Reporter decks on the page no longer creates JavaScript errors and broken decks.
* Reporter Lens: Fixed an issue where the video thumbnail was erroneously linking to the video permalink when this was not expected.
* For custom SlideDecks in an iFrame (or using RESS), links in the caption area no longer open within the iFrame itself.
* Updated language files to include phrases used in lens templates such as "Read More".
* Addressed an issue where the next video that was automatically played was not always the next slide that was navigated to (custom decks).
* Fixed an issue where the video cover was not always hidden when automatically advnacing to the next slide.

= 2.1.20120827 =
* Adding RESS (REsponsive Server-Side) options to the shortcode ress=1 and proportional=no
* Adding RESS (REsponsive Server-Side) options to the widget options
* Shortcode now accepts start=# where # is the starting slide (needed for RESS)
* Adding a fix that prevents a JavaScript lockup if the O-Town lens has thumbnails and is less than 110px tall
* Adding an optimization (speedup) to the iFrame rendering methods (echo scripts instead of linking them)
* Adding a check to some of the sources that may prevent some warnings from being shown.

= 2.1.20120823 =
* SlideDeck will now intelligently only load assets for SlideDecks for non-iframe embedded SlideDecks.
* Added an option that will turn off loading of SlideDeck base assets (common JavaScript/CSS files) on every page. When this option is turned on, SlideDeck will always load the base assets even if it doesn't detect a SlideDeck being loaded in the posts on the page (useful for template embedded SlideDecks). When this option is turned off SlideDeck will intelligently load assets only when it detects a SlideDeck embedded in a post on the page.
* Optimized caching namespacing and cache busting techniques to prevent Object Caching problems with persistent caching plugins like W3 Total Cache, WP Super Cache, Quick Cache and the like.
* Modified slidedeck_get_font filter to be more hookable to allow users to manually add their own fonts to the fonts list.
* Fixed bug that was preventing the insert SlideDeck modal from working in WordPress installations that were installed in a sub-folder.
* Added no-cache headers to AJAX calls on the SlideDeck preview to prevent cached responses from coming back
* Added "slidedeck_iframe_header" and "slidedeck_iframe_footer" actions to the IFRAME render template. This action receives two parameters: $slidedeck (the SlideDeck object Array) and $preview (a boolean of whether or not this is a preview render). Hook into these actions to add additional header and footer content to your IFRAME rendered SlideDecks.
* Fixed bug with the Tool Kit lens that prevented the Dark/Light variations from working on Custom Content SlideDecks with image slides.

= 2.1.20120807 =
* Fixed a bug in the WordPress Post Content Source that was excluding search of the post content area for imagery when the user chose to use the excerpt instead of the post content for the slide copy.
* Created new ability to choose how an image is scaled in a slide (no scaling, scale to fit, scale and crop)
* Fixed bug with IE that was preventing links from being clickable in slides with certain lenses
* Fixed bug with the way title .trim() is handled in the Block-Title and Fashion Lenses
* Changed the way video slides are handled (on iOS) for better compatibility and better playback starting
* Nav arrows are now hidden while a video is playing (on iOS) as they are unclickable anyway, we have a better solution for this in the works, but it will be added in a future release as it is going to take time to get a fully working solution for this problem implemented
* Fixed bug with O-Town lens JavaScript that was preventing proper completion of lens render in some cases
* Moving the slidedeck2 options to its own option key in the database (resolves a conflict with SlideDeck 1)
* Fixed a toggle issue with the Twitter content source flyout
* Fixed a play button positioning bug with custom SlideDecks and the Tool Kit lens when the navigation was set to display on the left or right of the SlideDeck

= 2.1.20120724 =
* Added logic to make more of the lenses cleanly copyable
* Twitter source now specifies small as a starting size
* If the default lens for a source is not available, the default SlideDeck lens is used instead
* Added indexType option for 'Classic' spined lens, sets SlideDeck JavaScript Index option to approptiate setting
* Added default lens hideSpines option set to true for all non Classic lenses
* Returned the "Inside Slide Area" Navigation Style option to the Tool Kit lens for Custom SlideDecks
* Fixed a bug where a taxonomy name with a dash in it eg: `my-custom-categories` would cause the WordPress posts taxonomy chooser (in SlideDeck) to fail

= 2.1.20120717 =
* Improved how we were affecting the force of target _blank on links when loading SlideDecks in an iframe
* Fixed bug that was casuing some remote content sources cached responses to load incorrectly, causing a "no content" response when loading the SlideDeck
* Added actual slide dimensions indicator in SlideDeck preview area
* Updated IFRAME preview mode to properly identify if it is in a preview or being rendered as part of an iframe=1 shortcode render
* Modified SlideDeck JavaScript core references to include the ?noping parameter when being rendered as part of the admin control panel
* Added filters for higher customization of slide spines

= 2.1.20120711 =
* SlideDecks are no longer rendered in feeds (Even Feedburner!). They are shown as a link instead
* Updated Twitter icons to reflect logo update
* Made a minor line-height adjustment to the Block Title lens for custom content types
* Rearranged some localization code
* Added an option to define a `SLIDEDECK_LICENSE_KEY` constant with your license key in your wp-config file
* Added a GMT override to the "time ago" calculations for the display of dates in lenses
* Fixed an issue where the last used Google+ API key was not being pre-populated
* Fixed a bug where the Google Plus "fullImage" property was sometimes returning a page URL instead of an image URL
* SlideDecks being rendered in iFrames now respect the 'open in same window' option

= 2.1.20120705 =
* Added min-width value to Flickr source flyout to fix rendering display in Webkit browsers (Safari/Chrome)
* Appended to the sort logic. If the deck only has one source, we don't sort by date. If it has more than one source, we sort by date
* Added an additional sort option to the WordPress Posts content source. You can now sort by menu_order
* Fixed an issue with the YouTube content source where videos in a playlist did not have the correct date ordering under some circumstances
* Added Pinterest icon to the sources image on the manage page
* Added a "jQuery Hook" (custom event) for the re-filling of the deck options area

= 2.1.20120702 =
* Fixed some logic bugs with uploading lenses
* Improved deletion logic for user-uploaded lenses to work properly with server configurations where the owner of the lens folder is the FTP user and not the web server user
* Moved strip_shortcodes() command to take place after an image has been searched for instead of at initial setup of slide nodes for dynamic post content source
* Fixed a bug that was returning a bad URL for first image in content
* Improved the "first image in gallery" logic to properly retrieve the first listed image in a gallery that has not yet been intentionally sorted by the user
* Fixed a regression bug with post thumbnail support in the posts dynamic content source that was preventing SlideDecks from working properly with themes that don't have thumbnail support
* Fixed an issue where jQuery Masonry was not being enqueued
* Adjusted the YouTube API call so no related videos are shown at the end of playback
* Added category selection to the 500px content source
* Added photos source selection to the 500px content source (My Photos, Photos I've favorited, Photos from my friends)
* Fixed the `slidedeck_lens_selection_before_lenses` and `slidedeck_lens_selection_after_lenses` hooks so they no longer throw notices

= 2.1.20120626 =
* Improved many of the Custom SlideDeck lens layouts for consistency and general appearance improvement
* Implemented some accommdations for the upcoming release of SlideDeck 2 Lite
* Added Pinterest content source 

= 2.1.20120618 =
* Fixed link coloring for light variation display of author links in Tool-kit lens to be more visible
* Fixed duplicate SlideDeck bug that was preventing all sources from being copied
* Swapped Dynamic and Custom content source blocks on manage page
* Improved interaction for duplicating SlideDecks to show an indicator while duplication is taking place
* Fixed positioning of overlay with Tool-Kit lens and "Custom SlideDecks"
* Adapted Instagram access token retrieval to work with new multi-source SlideDecks
* Fixed bug in slidedeck_after_save event that was improperly passing only on source as a string instead of an array of slugs
* Fixed bug with tooltips in flyouts for dynamic content sources that was causing tooltips to fail after a source was added or deleted
* Added "Overlays Always Open" option to allow overlays to be un-closable when visible
* Added ability to toggle iframe=1 as a default option on all future SlideDeck shortcode embeds
* Fixed some miscelaneous bugs with the Posts source JavaScript to properly handle taxonomy filtering
* Fixed display bug with O-Town lens and video custom slide types when using the caption layout 

= 2.1.20120614 =
* Updated SlideDeck jQuery core to 1.3.8 with a fix to properly handle animation speed
* Set custom SlideDecks to hide start setting since it doesn't really apply in this case as the user can order the slides any way they wish
* Added super-script, sub-script, clean formatting and paste special buttons to WYSIWYG editors for custom slides
* Made no thumbnail thumbnails for Tool-kit lens inherit the slide title's font

= 2.1.20120611 =
* Added slide type identification to identify the type of media on that slide (textonly|image|video|html) for mixed media SlideDecks
* Abstracted video meta query methods to the parent SlideDeck class to be available to all content sources
* Modified Google+ content source to show videos on those slides that contain video embeds
* Added author information to YouTube video source
* Added author meta data to Tool-kit video template
* Made Tool-kit video template excerpt area class consistent with regular template for proper visibility control and consistent style
* Created new multi-source Dynamic SlideDeck type to create SlideDecks with multiple external content sources
* Restructured entire content source model to allow content sources to be more modularly attached
* Created new "Custom Deck" SlideDeck type. You can now create a SlideDeck slide by slide, customize individual slide layouts and use custom content sources like: Images, Video, Text Only and full HTML
* Tool-Kit lens: Addressed some issues with style inheritance on the navigation arrows for the lens that were causing some display issues
* Setting related videos in YouTube to not display
* Fixed issue with WordPress post content source that was causing titles to trim off an already texturized version of the title instead of trimming before texturizing
* Fixed SlideDeck duplication bug where some options were not being duplicated
* Improved full slide linking. If the slide does not have a permalink, the full slide link will not output for that slide.

= 2.0.20120518 =
* Tool-Kit lens: Addressed an issue where a hanging thumbnail nav, on the bottom, on a large deck, would overlap
* Tool-Kit lens: Overlays should now be positioned better for certain deck configurations
* Tool-Kit lens: Thumbnail navigation arrows should no longer appear if there's only one page of thumbnails, on a vertical deck

= 2.0.20120517 =
* Fixed the ToolKit arrows bug where on hover, in some instances, certain arrow styles were being overwritten causing the buttons to look broken
* Fixed the configure source title output to remove redundancy in some cases
* Made it so that when "Preferred Image Source" is set to "First in Gallery" it gets the first image in the gallery based off menu order
* The back cover link target now inherits what the deck is set to: Open links in... "New Window/Tab" or "Same Window"
* Fixed an overflow issue with the Tool-Kit lens and covers
* Changed the default tweet text to be smarter about SlideDeck titles.
* Updated the Tool-Kit lens to allow for vertical decks (navigation on left or right)
* Several Tool-Kit lens changes and updates (for better display of overlays, etc)

= 2.0.20120508 =
* Updated the way we retrieve YouTube user playlists to accommodate for API update

= 2.0.20120507 =
* Removed deprecated output of SlideDeckLens JavaScript object declaration
* Updated FancyForm library to automatically update selected option on .change() event of the originating SELECT element
* Re-enabling the excerpt controls for Google Plus (for image descriptions)
* Made it possible to copy the Tool-Kit lens with no additional file (slug) editing needed
* Several tweaks to the design of the Tool-Kit lens, nothing major, but many small adjustments
* Added an option to Tool-Kit for no border/frame at all (and changed the option name to: Border/Frame)
* Reduced the confilct between "Navigation Position" and "Caption Position" - They move out of each other's way now
* Made the Tool-Kit navigation arrows more "bright and shiny"... cap'n
* Replaced 'user_nicename' with 'display_name' to respect the 'Display name publicly as' option
* Addressed issue where O-Town lens was not allowing the user to hide the excerpt area

= 2.0.20120501 =
* Changed Instagram access token and Google+ API key fields to password fields to improve privacy
* Added slidedeck_manage_sidebar_bottom action to make it easier to hook-into adding manage page sidebar content
* Added filter to URL for sidebar SlideDeck on manage page
* Made access to the Lens Management and Advanced Options sections require `manage_options` user capability
* Updated first save and get code dialog box to reflect post/page creation user capabilities
* Fixed an issue with the tool-kit lens where flash videos would not be visible when using the `thick frame` option
* Added a button to the overview screen that allows you to instantly duplicate an existing SlideDeck

= 2.0.20120418 =
* Modified URL pathing to be more accurate for custom plugin directory locations
* Removed license key render restiction
* Converted plugin license to GPL v3

= 2.0.20120406 =
* Updated TinyMCE integration so it wouldn't conflict when running side-by-side with SlideDeck 1 installations
* Made accommodation to exclude password protected posts from WordPress Post Deck types
* Added cache-busting uniqueid parameter to the iframe URLs in order to prevent cached query returns for overly-cached server configurations
* Fixed bug with Youtube players that makes accommodation for Youtube videos that contain underscores in their IDs
* Converted license key field on Advanced Options page to a password field
* Added ability to link the entire slide to the slide title's link - great for image only SlideDecks!
* Added missing semi-colon to end of slidedeck-public.js file to help with minification inclusion on caching plugins
* Updated preview, admin and public JavaScript asset files to reflect SlideDeck plugin version instead of individual file versions for simplicity and better update cache proofing
* Added version numbers to iframe JavaScript and CSS file sources to cache bust

= 2.0.20120327 =
* Improved menu positioning to be less conflicting with some themes like Thesis
* Improved validation of license key routine to provide better fallbacks for certain server configurations
* Improved how JavaScript files are loaded in preview and IFrame mode SlideDecks to prevent problems with sub-folder embedded SlideDecks 
* Added Google map images and custom titles for Google Plus Posts check-ins.
* Added an option to the WordPress Posts type that allows overriding the content with the excerpt.
* Addressed another issue where post_thumbnail support was not being checked for.

= 2.0.20120323 =
* Addressed an issue where post_thumbnail support was not being checked for
* Added new "slidedeck_after_get" filter for post-processing SlideDeck array when loading single SlideDecks in the SlideDeck::get() method
* Added some logic that tries to retrieve the right sized image from your WordPress posts source
* Removed the "Featured" selection from the WordPress Posts content source
* Replaced all JavaScript calls to jQuery.on() with jQuery.bind() or jQuery.delegate()
* Fixed issue with Internet Explorer 8 and the content source flyouts

= 2.0.20120322 =
* Made accommodation for systems that do not have the mbstring PHP module enabled
* Attempted to fix a license key issue for servers with connection issues

= 2.0.20120319 =
* Addressing an issue with vertical SlideDecks and video

= 2.0.20120316 =
* Gold release

= 2.0.0beta3 =
* Third beta release
* Fixed issue with image retrieval for GPlus posts
* Design craft implementations
* Various bug fixes
* Some IE 7/8 improvements

= 2.0.0beta2 =
* Second beta release
* Lots of design polish
* Options groups table organization for usability and consistency
* New cover designs
* Tons of bug fixes
* SlideDeck 2 now runs side-by-side with SlideDeck 1

= 2.0.0beta1 =
* Initial beta release.

== Upgrade Notice ==
= 2.1.20130325 =
Bug fixes and better upgrade messaging

= 2.1.20130228 =
Fixing an issue with the O-Town and Fashion Lens JavaScript files

= 2.1.20130219 =
Preparing for jQuery 1.9, misc bug fixes

= 2.1.20130116 =
Collection of miscellaneous bug fixes

= 2.1.20121212 =
Many bug fixes, and lens CSS loading optimizations

= 2.1.20121115 =
Bug fixes, adds the ability to purchase lenses

= 2.1.20121102 =
Critical hotfix for decks that are inserted directly into the theme

= 2.1.20121101 =
Bug fixes for IE8. Asset loading optimizations

= 2.1.20121017 =
Bug fixes, IE10 issued resolved, Chrome iOS issues resolved

= 2.1.20121010 =
Bug fixes, bulk image upload, Custom Content Image slides for Personal

= 2.1.20120919 =
Bug fixes for IE, some lenses, RESS responsive and IFRAME conflicts, cross-plugin conflicts

= 2.1.20120701 =
Update to fix a few bugs and improve some interactions.

= 2.1.20120626 =
SlideDeck 2.1! Now with custom slide editing, tiered add-on support, full HTML slides (with Developer license). Don't forget to install the add-ons to get all your features!

= 2.0.20120508 =
Hotfix: fixed bug with YouTube user playlist retrieval

= 2.0.20120327 =
Improved IFrame loading and added ability to override WordPress content with excerpt content, compatibility fixes 

= 2.0.20120323 =
Compatibility for WordPress themes without featured thumbnail support and new filter addition

= 2.0.20120322 =
Compatibility bug fixes for certain PHP configurations

= 2.0.20120319 =
Addressing an issue with vertical SlideDecks and video

= 2.0.20120316 =
Gold release

= 2.0.0beta3 =
Third beta release

= 2.0.0beta2 =
Second beta release

= 2.0.0beta1 =
Initial private beta release