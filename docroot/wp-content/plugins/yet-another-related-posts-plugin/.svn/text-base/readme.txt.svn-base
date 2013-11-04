=== Yet Another Related Posts Plugin ===
Contributors: mitchoyoshitaka
Author: mitcho (Michael Yoshitaka Erlewine)
Author URI: http://mitcho.com/
Plugin URI: http://yarpp.org/
Donate link: http://tinyurl.com/donatetomitcho
Tags: related, posts, post, pages, page, RSS, feed, feeds
Requires at least: 3.3
Tested up to: 3.5
Stable tag: 4.0.2
License: GPLv2 or later

Display a list of related entries on your site and feeds based on a unique algorithm. Now with thumbnail support built-in!

== Description ==

Yet Another Related Posts Plugin (YARPP) gives you a list of posts and/or pages related to the current entry, introducing the reader to other relevant content on your site.

1. **Thumbnails**: a beautiful new thumbnail display, for themes which use post thumbnails (featured images) **New in YARPP 4!**
2. **Related posts, pages, and custom post types**: [Learn about CPT support](http://wordpress.org/extend/plugins/yet-another-related-posts-plugin/other_notes/).
3. **Templating**: The [YARPP templating system](http://mitcho.com/blog/projects/yarpp-3-templates/) gives you advanced control of how your results are displayed.
4. **An advanced and versatile algorithm**: Using a customizable algorithm considering post titles, content, tags, categories, and custom taxonomies, YARPP finds related content from across your site. [Learn more](http://wordpress.tv/2011/01/29/michael-%E2%80%9Cmitcho%E2%80%9D-erlewine-the-yet-another-related-posts-plugin-algorithm-explained/).
5. **Caching**: YARPP is performant, caching related posts data as your site is visited.
6. **Related posts in feeds**: Display related posts in RSS feeds with custom display options.

This plugin requires PHP 5, MySQL 4.1, and WordPress 3.3 or greater.

See [other plugins by mitcho](http://profiles.wordpress.org/users/mitchoyoshitaka/).

= A note on support (June 2010) =

I have begun instituting a stricter policy of not responding to support inquiries via email, instead directing senders to the appropriate WordPress.org forum, [here](http://wordpress.org/support/plugin/yet-another-related-posts-plugin).

I try to respond to inquiries on the forums on a regular basis and hope to build a community of users who can learn from each other's questions and experiences and can support one another. I ask for your understanding on this matter.

= Testimonials =

<blockquote>
"One of my favorite [plugin]s I just activated on my blog is called Yet Another Related Posts Plugin... I've been blogging seven or eight years now so I have a lot of archives, and it actually surprises me sometimes when I blog about something and I visit the permalink to see I've written about it before... and it also increases the traffic on your blog because when they come in just to one entry, they'll see this other stuff going on."
</blockquote>

&mdash; [Matt Mullenweg](http://ma.tt), WordPress creator

<blockquote>
"The first one I ended up trying was Yet Another Related Posts Plugin (YARPP), and mitcho has really done a fantastic job on it:

<ul>
<li>It’s trivial to install.</li>
<li>You don’t have to edit your WordPress template.</li>
<li>The relevance is good: the suggested posts are related, and you can tweak thresholds and how things are computed if you want."</li>
</ul>
</blockquote>

&mdash; [Matt Cutts](http://www.mattcutts.com/blog/wordpress-plugin-related-posts/), head of Webspam, Google

<blockquote>
"One way of getting search engines to get to your older content a bit easier, thus increasing your WordPress SEO capabilites a LOT, is by using a related posts plugin. These plugins search through your posts database to find posts with the same subject, and add links to these posts. There are quite a few related posts plugins but I tend to stick with the Yet Another Related Posts Plugin..."
</blockquote>

&mdash; [Yoast (Joost de Valk)](http://yoast.com/articles/wordpress-seo/)

== Installation ==

= Auto display on your website =

1. Copy the folder `yet-another-related-posts-plugin` into the directory `wp-content/plugins/` and activate the plugin.
2. (optionally) copy the sample template files inside the `yarpp-templates` folder into your active theme.
3. Go to the "Related Posts (YARPP)" settings page to customize YARPP.

= Auto display in your feeds =

Make sure the "display related posts in feeds" option is turned on if you would like to show related posts in your RSS and Atom feeds. The "display related posts in feeds" option can be used regardless of whether you auto display them on your website (and vice versa).

= Widget =

Related posts can also be displayed as a widget. Go to the Design > Widgets options page and add the Related Posts widget. The widget will only be displayed on single entry (permalink) pages. The widget can be used even if the "auto display" option is turned off.

= Custom display through templates =

YARPP allows the advanced user with knowledge of PHP to customize the display of related posts using a templating mechanism. More information is available [in this tutorial](http://mitcho.com/blog/projects/yarpp-3-templates/).

== Frequently Asked Questions ==

If your question isn't here, ask your own question at [the WordPress.org forums](http://wordpress.org/support/plugin/yet-another-related-posts-plugin). *Please do not email with questions.*

= How can I move the related posts display? =

If you do not want to show the Related Posts display in its default position (right below the post content), first go to YARPP options and turn off the "automatically display" options in the "website" section. If you would like to instead display it in your sidebar and you have a widget-aware theme, YARPP provides a Related Posts widget which you can add under "Appearance" > "Widgets".

If you would like to add the Related Posts display elsewhere, edit your relevant theme file (most likely something like `single.php`) and add the PHP code `related_posts();` within [The Loop](http://codex.wordpress.org/The_Loop) where you want to display the related posts.

= I'm using the Thumbnails display in YARPP 4. How can I change the thumbnail size? =

The thumbnail size can be specified programmatically by adding `add_image_size( 'yarpp-thumbnail', $width, $height, true );` to your theme's `functions.php` file with appropriate width and height variables. In the future I may add some UI to the settings to also set this... feedback is requested on whether this is a good idea.

Each time you change YARPP's thumbnail dimensions like this, you will probably want to have WordPress regenerate appropriate sized thumbnails for all of your images. I highly recommend the [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) plugin for this purpose. See also the next question.

= I'm using the Thumbnails display in YARPP 4. Why aren't the right size thumbnails being served? =

By default if an appropriately sized thumbnail is not available in WordPress, a larger image will be served and will be made to fit in the thumbnail space via CSS. Sometimes this means images will be scaled down in a weird way, so it is not ideal... what you really want is for YARPP to serve appropriately-sized thumbnails.

There are two options for doing so:

* First, you can use the [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) plugin to generate all these thumbnail-sized images in a batch process. This puts you in control of when this resizing process happens on your server (which is good because it can be processor-intensive). New images which are uploaded to WordPress should automatically get the appropriate thumbnail generated when the image is uploaded.

* Second, you can turn on a feature in YARPP to auto-generate appropriate size thumbnails on the fly, if they have not yet been created. Doing this type of processing on the fly does not scale well, so this feature is turned off by default. But if you run a smaller site with less traffic, it may work for you. Simply add `define('YARPP_GENERATE_THUMBNAILS', true);` to your theme's `functions.php` file.

= How can I use the custom template feature? =

YARPP's [custom templates feature](http://mitcho.com/blog/projects/yarpp-3-templates/) allows you to uber-customize the related posts display using the same coding conventions and [Template Tags](http://codex.wordpress.org/Template_Tags) as in WordPress themes. Custom templates must be in your *active theme's main directory* in order to be recognized by YARPP. If your theme did not ship with YARPP templates, move the files in the `yarpp-templates` directory which ships with YARPP into your active theme's main directory. Be sure to move the *files* (which must be named `yarpp-template-`...`.php`) to your theme, not the entire directory.

= Does YARPP slow down my blog/server? =

The YARPP calculation of related content does make a little impact, yes. However, YARPP caches all of its results, so any post's results need only be calculated once. YARPP's queries have been significantly optimized in version 3.5.

If you are running a large site and need to throttle YARPP's computation, try the official [YARPP Experiments](http://wordpress.org/extend/plugins/yarpp-experiments/) plugin which adds this throttling functionality. If you are looking for a hosting provider whose databases will not balk under YARPP, I personally have had great success with [MediaTemple](http://www.mediatemple.net/#a_aid=4ed59d7ac5dae).

= Many pages list "no related posts". =

Most likely you have "no related posts" right now as the default "match threshold" is too high. Here's what I recommend to find an appropriate match threshold: lower your match threshold in the YARPP options to something very low, like 1. (If you don't see the match threshold, you may need to display the "Relatedness" options via the "Screen Options" tab at the top.) Most likely the really low threshold will pull up many posts that aren't actually related (false positives), so look at some of your posts' related posts and their match scores. This will help you find an appropriate threshold. You want it lower than what you have now, but high enough so it doesn't have many false positives.

= Are there any plugins that are incompatible with YARPP? =

* [DISQUS](https://wordpress.org/extend/plugins/disqus-comment-system/): go to the DISQUS plugin advanced settings and turn on the "Check this if you have a problem with comment counts not showing on permalinks".
* [SEO_Pager plugin](http://wordpress.org/support/topic/267966): turn off the automatic display option in SEO Pager and instead add the code manually.
* [Pagebar 2](http://www.elektroelch.de/hacks/wp/pagebar/);
* [WP Contact Form III plugin and Contact Form Plugin](http://wordpress.org/support/topic/392605);
* Other related posts plugins, obviously, may also be incompatible.

Please submit similar bugs by starting a new thread on [the WordPress.org forums](http://wordpress.org/support/plugin/yet-another-related-posts-plugin). I check the forums regularly and will try to release a quick bugfix.

= Does YARPP work with full-width characters or languages that don't use spaces between words? =

YARPP works fine with full-width (double-byte) characters, assuming your WordPress database is set up with Unicode support. 99% of the time, if you're able to write blog posts with full-width characters and they're displayed correctly, YARPP will work on your blog.

However, YARPP does have difficulty with languages that don't place spaces between words (Chinese, Japanese, etc.). For these languages, the "consider body" and "consider titles" options in the "Relatedness options" may not be very helpful. Using only tags and categories may work better for these languages.

= Things are weird after I upgraded. =

1. Visit the "Related Posts (YARPP)" settings page to verify your settings.
2. Disactivate YARPP, replace the YARPP files on the server with a fresh copy of the new version, and then reactivate it.
3. Install the official [YARPP Experiments](http://wordpress.org/extend/plugins/yarpp-experiments/) plugin to flush the cache.

= Can I clear my cache? Can I build up the cache manually? =

The official [YARPP Experiments](http://wordpress.org/extend/plugins/yarpp-experiments/) plugin adds manual cache controls, letting you flush the cache and build it up manually.

== Developing with YARPP ==

= Custom post types =

To make YARPP support your custom post type, the attribute `yarpp_support` must be set to true on the custom post type when it is registered. It will then be available on options on the YARPP settings page.

If you would like to programmatically control which post types are considered in an automatically-displayed related posts display, use the `yarpp_map_post_types` filter.

= Custom displays =

Developers can call YARPP's powerful relatedness algorithm from anywhere in their own code. Some examples and more details are in [my slides from my WordCamp Birmingham talk](http://www.slideshare.net/mitcho/relate-all-the-things).

	yarpp_related(array(
		// Pool options: these determine the "pool" of entities which are considered
		'post_type' => array('post', 'page', ...),
		'show_pass_post' => false, // show password-protected posts
		'past_only' => false, // show only posts which were published before the reference post
		'exclude' => array(), // a list of term_taxonomy_ids. entities with any of these terms will be excluded from consideration.
		'recent' => false, // to limit to entries published recently, set to something like '15 day', '20 week', or '12 month'.
		
		// Relatedness options: these determine how "relatedness" is computed
		// Weights are used to construct the "match score" between candidates and the reference post
		'weight' => array(
			'body' => 1,
			'title' => 2, // larger weights mean this criteria will be weighted more heavily
			'tax' => array(
				'post_tag' => 1,
				... // put any taxonomies you want to consider here with their weights
			)
		),
		// Specify taxonomies and a number here to require that a certain number be shared:
		'require_tax' => array(
			'post_tag' => 1 // for example, this requires all results to have at least one 'post_tag' in common.
		),
		// The threshold which must be met by the "match score"
		'threshold' => 5,
	
		// Display options:
		'template' => , // either the name of a file in your active theme or the boolean false to use the builtin template
		'limit' => 5, // maximum number of results
		'order' => 'score DESC'
	),
	$reference_ID, // second argument: (optional) the post ID. If not included, it will use the current post.
	true); // third argument: (optional) true to echo the HTML block; false to return it

Options which are not specified will default to those specified in the YARPP settings page. Additionally, if you are using the builtin template rather than specifying a custom template file in `template`, the following arguments can be used to override the various parts of the builtin template: `before_title`, `after_title`, `before_post`, `after_post`, `before_related`, `after_related`, `no_results`, `excerpt_length`.

If you need to use related entries programmatically or to know whether they exist, you can use the functions `yarpp_get_related($args, $reference_ID)` and `yarpp_related_exist($args, $reference_ID)`. `yarpp_get_related` returns an array of `post` objects, just like the WordPress function `get_posts`. `yarpp_related_exist` returns a boolean for whether any such related entries exist. For each function, `$args` takes the same arguments as those shown for `yarpp_related` above, except for the various display and template options.

Note that custom YARPP queries using the functions mentioned here are *not* cached in the built-in YARPP caching system. Thus, if you notice any performance hits, you may need to write your own code to cache the results.

= Custom taxonomy support =

Any taxonomy, including custom taxonomies, may be specified in the `weight` or `require_tax` arguments in a custom display as above. `term_taxonomy_id`s specified in the `exclude` argument may be of any taxonomy.

If you would like to choose custom taxonomies to choose in the YARPP settings UI, either to exclude certain terms or to consider them in the relatedness formula via the UI, the taxonomy must (a) have either the `show_ui` or `yarpp_support` attribute set to true and (b) must apply to either the post types `post` or `page` or both.

== Localizations ==

YARPP is currently localized in the following languages:

* Egyptian Arabic (`ar_EG`) by Bishoy Antoun of [cdmazika.com](http://www.cdmazika.com).
* Standard Arabic (`ar`) by [led](http://led24.de)
* Belarussian (`by_BY`) by [Fat Cow](http://www.fatcow.com)
* Bulgarian (`bg_BG`) by [Flash Gallery](http://www.flashgallery.org)
* Simplified Chinese (`zh_CN`) by Jor Wang of [jorwang.com](http://jorwang.com)
* Traditional Chinese (Taiwan, `zh_TW`) by [Pseric](http://www.freegroup.org/)
* Croatian (`hr`) by [GoCroatia.com](http://gocroatia.com)
* Czech (`cs_CZ`) by [Zdenek Hejl](http://www.zdenek-hejl.com)
* Dutch (`nl_NL`) by Sybrand van der Werf
* Farsi/Persian (`fa_IR`) by [Moshen Derakhshan](http://webdesigner.downloadkar.com/)
* French (`fr_FR`) by Lionel Chollet
* Georgian (`ge_KA`) by Kasia Ciszewski of [Find My Hosting](www.findmyhosting.com)
* German (`de_DE`) by Michael Kalina of [3th.be](http://3th.be) and Nils Armgart of [movie-blog.de.ms](http://www.movie-blog.de.ms)
* Cypriot Greek (`el_CY`) by Aristidis Tonikidis of [akouseto.gr](http://www.akouseto.gr)
* Greek (`el_EL`) by Aristidis Tonikidis of [akouseto.gr](http://www.akouseto.gr)
* Hebrew (`he_IL`) by Mickey Zelansky of [simpleidea.us](http://simpleidea.us) and [Hadas Kotek](http://web.mit.edu/hkotek/www)
* Hindi (`hi_IN`) by [Outshine Solutions](http://outshinesolutions.com/)
* Italian (`it_IT`) by Gianni Diurno of [gidibao.net](http://gidibao.net)
* Irish (`gb_IR`) by [Ray Gren](http://letsbefamous.com)
* Bahasa Indonesia (`id_ID`) by [Hendry Lee](http://hendrylee.com/) of [Kelayang](http://kelayang.com/)
* Japanese (`ja`) by myself (yarpp at mitcho dot com)
* Kazakh (`kk_KZ`) by [DachaDecor](http://DachaDecor.ru)
* Korean (`ko_KR`) by [Jong-In Kim](http://incommunity.codex.kr)
* Latvian (`lv_LV`) by [Mike](http://antsar.info)
* Lithuanian (`lt_LT`) by [Karolis Vyčius](http://vycius.co.cc) and [Mantas Malcius](http://mantas.malcius.lt)
* Norwegian (`nb_NO`) by [Tom Arne Sundtjønn](http://www.datanerden.no)
* Polish (`pl_PL`) by [Perfecta](http://perfecta.pro/wp-pl/)
* (European) Portuguese (`pt_PT`) by Stefan Mueller of [fernstadium-net](http://www.fernstudium-net.de)
* Brazilian Portuguese (`pt_BR`) by Rafael Fischmann of [macmagazine.br](http://macmagazine.com.br/)
* Romanian (`ro_RO`) by [Uhren Shop](http://uhrenstore.de/)
* Russian (`ru_RU`) by Marat Latypov of [blogocms.ru](http://blogocms.ru)
* Serbian (`sr_RS`) by [Zarko Zivkovic](http://www.zarkozivkovic.com/) 
* Slovak (`sk_SK`) by [Forex](http://www.eforex.sk/)
* Spanish (`es_ES`) by Rene of [WordPress Webshop](http://wpwebshop.com)
* Swedish (`sv_SE`) by Max Elander
* Turkish (`tr_TR`) by [Nurullah](http://www.ndemir.com) and [Barış Ünver](http://beyn.org/)
* Vietnamese (`vi_VN`) by Vu Nguyen of [Rubik Integration](http://rubikintegration.com/)
* Ukrainian (`uk_UA`) by [Onore](http://Onore.kiev.ua) (Alexander Musevich)
* Uzbek (`uz_UZ`) by Ali Safarov of [comfi.com](http://www.comfi.com/)

<!--We already have localizers lined up for the following languages:
* Danish
* Catalan
* Thai
* Bhasa Indonesian
-->

If you are a bilingual speaker of English and another language and an avid user of YARPP, I would love to talk to you about localizing YARPP! Localizing YARPP can be pretty easy using [the Codestyling Localization plugin](http://www.code-styling.de/english/development/wordpress-plugin-codestyling-localization-en). Please [contact me](mailto:yarpp@mitcho.com) *first* before translating to make sure noone else is working on your language. Thanks!

== Changelog ==

= 4.0.2 =
* [Bugfix](http://wordpress.org/support/topic/yarpp-doesnt-update-suggestions-with-older-posts): cache should be cleared when the "show only previous posts?" option is changed
* [Bugfix](http://wordpress.org/support/topic/no-default-image-showing?replies=4): In the thumbnail display, sometimes the default image was not displayed, even though no post thumbnail was available.
* Localization updates
	* Updated Polish, Japanese, Hebrew localizations
	* Better right-to-left layout support

= 4.0.1 =
* Improvements to thumbnail handling
	* See new FAQ entry for practical details
	* Thumbnail size can be specified programmatically (see FAQ)
	* YARPP now registers its thumbnail size properly as `yarpp-thumbnail`
	* Fixed a typo and simplified an item in the dynamic `styles-thumbnails.php` styles
	* Code to generate thumbnails of appropriate size on the fly has been added, but is turned off by default for performance reasons (see FAQ)
* Bugfix: a class of `yarpp-related-` with a stray hyphen was sometimes being produced. Now fixed so it produces `yarpp-related`.
* [Bugfix](http://wordpress.org/support/topic/bug-in-sql-function-in-yarpp_cache): `term_relationships` table was being joined when unnecessary
* [Bugfix](http://wordpress.org/support/topic/no-option-to-add-widget-title-in-theme-using-hybrid-core-framework): widget options would not display if custom templates were not available
* Bugfix: some transients expired too soon if object caching was used
* The `yarpp_map_post_types` filter now also applies to feeds and takes an extra argument to know whether the context is `website` or `rss`.

= 4.0 =
* New thumbnail template option!
	* No PHP required—just visit the settings page
	* Edit your theme's CSS file to modify the styling
* Auto display settings changes:
	* Easily choose which post types you want related posts to display on
	* Added an "also display in archives" option
* [Bugfix](https://wordpress.org/support/topic/related-posts-disappearing-cache-issue): uses of `related_posts_exist()` and `get_related()` without explicit reference ID parameter would incorrectly return no related posts.
* Changes to the output HTML:
	* All YARPP output is now wrapped in a `div` with class `yarpp-related`, `yarpp-related-widget`, or `yarpp-related-rss` as appropriate ([by request](https://wordpress.org/support/topic/adding-a-main-div-to-default-template)). If there are no results, a `yarpp-related-none` class is added.
	* The "related posts brought to you by YARPP" text is only added if there were results.
* Refinements to settings UI:
	* A new design for the template chooser
	* Example code display is now hidden by default; turn them back on from the "screen options" tab.
	* A new "copy templates" button allows one-button installation of bundled templates into the current theme, if filesystem permissions make it possible.
	* Header information in YARPP custom templates are now displayed to users in the settings UI. Available fields are `Template Name`, `Description`, `Author`, `Author URI`, in the same format as plugin and theme file headers. See bundled templates for examples.
* Code cleanup:
	* Settings screen UI have been rewritten to use `div`s rather than `table`s!
	* Inline help in settings screen now use WordPress pointers
	* Removed keyword cache table, as it does not ctually improve performance much and the overhead of an additional table is not worth it.
* Default option changes:
	* Default result count is now 4
	* Default match threshold is now 4
	* Default for "before related entries" heading uses `h3` instead of `p`
* Added `yarpp_map_post_types` filter to programmatically specify what post types should be looked at for automatic displays
* Added option to send YARPP setting and usage information back to YARPP (off by default). This information will be used to make more informed decisions about future YARPP development. More info available in the settings.

= 3.5.6 =
* Typo fix for postmeta cache
* Added Traditional Chinese (Taiwan, `zh_TW`) localization by [Pseric](http://www.freegroup.org/)

= 3.5.5 =
* Quick bugfix for how admin screen code was loaded in in WordPress < 3.3.

= 3.5.4 =
* New Help tab, which displays help text from the readme.
* Retina icons! Now served faster, in sprite form.
* Added Croatian (`hr`) localization by [gocroatia.com](http://gocroatia.com)
* Cleanup:
	* Bugfix: stopwords would not be loaded if WPLANG is defined but blank.
	* Added new `stats` method to `YARPP_Cache_*` objects.
	* Load meta boxes on `screen_option` hook. Improves performance on admin pages.
	* Changed default option of "show only previous posts" to `false` and removed FAQ text, as it no longer improves performance much.

= 3.5.3 =
* [Bugfix](https://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-no-related-posts-7): Fixed a common cause of "no related posts"!
* Better post revision handling
* [Bugfix](https://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-database-errors-upon-activation): setup wasn't automatic for network activations.
* Code cleanup:
	* [Bugfix](https://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-latin1-instead-of-utf-8): tables should be created using WordPress charset settings
	* YARPP_Cache_*::update methods are now protected
	* Simplified some post status transition handling
	* Ensure that `delete_post` hook receives relevant post ID information
	* Various functions now refer to the `enforce` method which will activate if it's a new install, or else upgrade if necessary. (Part of the fix for the network activation above.)

= 3.5.2 =
* [Bugfix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-bug-found-with-solution): fix an unfortunate typo which caused "no related posts" on many environments with non-MyISAM tables
* Fixed a bug where related posts would not be recomputed on post update, on environments using the `table` YARPP cache method and a persistent object caching system, like W3 Total Cache or memcached
* [Bugfix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-351-error-call-to-a-member-function): reference to `get_post_types()` failed in ajax display
* Fixed a bug where some RSS display options were not being obeyed
* Fixed a bug where the "automatic display" was being displayed on some custom post types without any control.
* Localizations:
	* Added Czech (`cs_CZ`) localization by [Zdenek Hejl](http://www.zdenek-hejl.com)
	* Added Serbian (`sr_RS`) by [Zarko Zivkovic](http://www.zarkozivkovic.com/)
	* Fixed bug in Dutch localization
* Clarified readme to require WordPress 3.1
* Code cleanup:
	* PHP 5.3+: replaced an instance of `ereg_replace`
	* Removed warning on settings save
	* Sometimes [a warning]((http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-warning-invalid-argument-supplied-for-foreach)) was printed on upgrade from YARPP < 3.4.4
	* Fixed [PHP warning](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-php-warning) when no taxonomies are considered
	* No longer using `clear_pre` function which has been deprecated since WordPress 3.4.

= 3.5.1 =
* [Bugfix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-catchable-fatal-error-object-of-class-stdclass-could-not-be-converted-to-string): change `$yarpp->get_post_types()` to return array of names by default
* Ensure that all supported post types are used when "display results from all post types" is set
= 3.5 =
* New public YARPP query API, which supports custom post types
	* Documentation in the "other notes" section of the readme
	* Changed format of `weight`, `template`, `recent` parameters in options and in optional args
* Further main query optimization:
	* What's cooler than joining four tables? Joining two.
	* Exclude now simply uses `term_taxonomy_id`s instead of `term_id`s
* Bugfix: "related posts" preview metabox was not always working properly
* Changes to the `related_*()` and `yarpp_related()` function signatures.
* Added "consider with extra weight" to taxonomy criteria as well
* Code cleanup:
	* Don't clear the cache when it's already empty
	* `protect` the `sql` method as it shouldn't be `public`
	* Further use of utility functions from 3.1 like `wp_list_pluck()`
	* New constant, `YARPP_EXTRA_WEIGHT` to define the "extra weight." By default, it's 3.
* Localizations:
	* Added Slovak (`sk_SK`) localization by [Forex](http://www.eforex.sk/)
	* Added Romanian (`ro_RO`) localization by [Uhren Shop](http://uhrenstore.de/)
	* Updated `it_IT`, `ko_KR`, `fr_FR`, `sv_SE`, `ja` localizations

= 3.4.3 =
* Bugfix: keywords were not getting cleared on post update, meaning new posts (which start blank) were not getting useful title + body keyword matches. This often resulted in "no related posts" for new posts.
* Postmeta cache: make sure to clear keyword cache on flush too
* Make welcome pointer more robust
* More custom post type support infrastructure
* Updated Turkish localization by [Barış Ünver](http://beyn.org/).

= 3.4.2 =
* [Bugfix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-not-working-version-341-and-custom-template): 3.4 and 3.4.1 assumed existence of `wp_posts` table.
* Fix typo in `yarpp-template-random.php` example template file
* Improve compatibility with DB Cache Reloaded plugin which doesn't properly implement `set_charset` method.

= 3.4.1 =
* [Bugfix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-34-images-problem-using-template): restore `global $post` access to custom templates
* [Bugfix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-some-clarification-assistance) for missing `join_filter` on bypass cache
* Bugfixes to query changes:
	* [Bugfix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-34-images-problem-using-template/page/2?replies=36#post-2498791): Shared taxonomy terms were not counted correctly
	* [Bugfix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-use-2-times-related_posts-in-the-singlephp-longer-works): exclusion was not working
* [Bugfix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-34-images-problem-using-template/page/2?replies=36#post-2498791): "disallow" terms were not being displayed for custom taxonomies.
* Add defaults for the `post_type` arg
* Strengthen default post ID values for `related_*` functions
* Added nonce to cache flushing. If you would like to manually flush the cache now, you must use the [YARPP Experiments](http://wordpress.org/extend/plugins/yarpp-experiments/) plugin.
* Updated `sv_SE`, `ko_KR`, `fr_FR` localizations
= 3.4 =
* Major optimizations to the main related posts query, in particular with regard to taxonomy lookups
	* Performance improvements on pages with "no related posts"
* Now can consider custom taxonomies (of posts and pages), in addition to tags and cateogories! Custom taxonomies can also be used to exclude certain content from The Pool.
* Add welcome message, inviting users to check out the settings page
* [Bug fix](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-version-333-breaks-templates-in-widget): Custom templates could not be used in widget display
* Significant code cleanup
	* Move many internal functions into a global object `$yarpp` of class `YARPP`; references to the global `$yarpp_cache` should now be to global `$yarpp->cache`
	* Created the "bypass" cache engine which is used when custom arguments are specified.
		* Switch to bypass cache for demos
	* Now only clears cache on post update, and only computes results for actual posts, not revisions (thanks to Andrei Mikhaylov)
	* Removed the many different options entries, replacing them with a single `yarpp` option (except `yarpp_version`)
	* Fixed issues with display options field data escaping and slashing once and for all
	* Streamlined keyword storage in `YARPP_Cache_Postmeta`
	* Create `YARPP_Cache` abstract class
	* Updated minor bug for computing how many results should be cached
	* Adding some filters: yarpp_settings_save, yarpp_blacklist, yarpp_blackmethods, yarpp_keywords_overused_words, yarpp_title_keywords, yarpp_body_keywords, yarpp_extract_keywords
	* New systematic use of YARPP_ constants to communicate cache status
	* Use `get_terms` to load terms
* Get lazy and embrace asynchronicity:
	* Implement lazy/infinite scrolling for the "disallow tags" and "disallow categories," so the YARPP settings screen doesn't lock up the browser for sites which have a crazy number or tags or categories
	* Don't compute related posts for the metabox on the edit screen; display them via ajax instead
	* Only clear cache on post save, not recompute
* Added `yarpp_get_related()` function can be used similar to `get_posts()`
* Support for [YARPP Experiments](http://wordpress.org/extend/plugins/yarpp-experiments/).
* Fix formatting of the Related Posts meta box
* Localizations
	* Updated `it_IT` localization
	* Added Portuguese stopwords by Leandro Coelho ([Logística Descomplicada](http://www.logisticadescomplicada.com))
= 3.3.3 =
* [Bug fix](http://wordpress.org/support/topic/no-related-posts-1): a fix for keyword computation for pages; should improve results on pages. May require flushing of cache: see FAQ for instructions.
* Init YARPP on the `init` action, [for compatibility with WPML](https://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-load-sequence-yarpp-starts-before-the-wordpress-init-completes)
* Updated Polish, Italian, and Japanese localizations; added Dutch stopwords by Paul Kessels
* Code cleanup:
	* Minor speedup to unnecessarily slow i18n code
	* Fixed fatal error in postmeta keyword caching code
	* Fewer `glob`s
	* [Bug fix](http://wordpress.org/support/topic/the-problem-when-publish-a-post): ignore empty `blog_charset`s
= 3.3.2 =
* [Bugfix](http://wordpress.org/support/topic/missing-translate-strings): removed an unlocalized string
* Bugfix for users of WordPress 3.0.x.
= 3.3.1 =
* Quick bugfix to [relatedness options panel bug](http://wordpress.org/support/topic/relatedness-options-for-titles-and-bodies-cant-be-changed)
= 3.3 =
* Pretty major rewrite to the options page for extensibility and screen options support
	* By default, the options screen now only show the display options. "The Pool" and "Relatedness" options can be shown in the screen options tab in the top right corner of the screen.
	* Removed the "reset options" button, because it wasn't actually doing anything.
* Rebuilt the new version notice to actually have a link which triggers the WordPress plugin updater, at least for new full versions
* Changed default "relatedness" settings to not consider categories, to improve performance
* Added [BlogGlue](http://blogglue.com) partnership module
* Localizations
	* Quick fix to Czech word list file name
	* Updated Italian localization (`it_IT`)
	* Added Hungarian (`hu_HU`) by [daSSad](http://dassad.com)
	* Added Kazakh (`kk_KZ`) by [DachaDecor](http://DachaDecor.ru)
	* Added Irish (`gb_IR`) by [Ray Gren](http://letsbefamous.com)
= 3.2.2 =
* Now [ignores soft hyphens](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-french-overused-words) in keyword construction
* Minor fix for "cross-relate posts and pages" option and more accurate `related_*()` results across post types
* Localization updates:
	* Updated `de_DE` German localization files
	* Fixed an encoding issue in the `pt_PT` Portuguese localization files
	* Added `es_ES` Spanish localization by Rene of [WordPress Webshop](http://wpwebshop.com)
	* Added `ge_KA` Georgian by Kasia Ciszewski of [Find My Hosting](www.findmyhosting.com)
	* Added Czech (`cs_CZ`) overused words list [by berniecz](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-french-overused-words)
= 3.2.1 =
* Bugfix: [Duplicate results shown for some users](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-yarpp-post-duplicate-related-articles)
* Bugfix: [With PHP4, the "related posts" would simply show the current post](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-yarpp-showing-same-post)
	* This was due to an issue with [object references in PHP4](http://www.obdev.at/developers/articles/00002.html). What a pain!
	* A big thanks to Brendon Held of [inMotion Graphics](http://www.imgwebdesign.com) for being incredibly patient and letting me try out different diagnostics on his server.
* Better handling of [`post_status` transitions](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-changed-post-to-draft-still-showing-up-as-related-to-other-posts).
* Bugfix: [the widget was not working on pages](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-showing-yarp-widget-in-pages-and-subpages)
* Added overused words list for French, thanks to [saymonz](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-french-overused-words)
* Minor code cleanup:
	* Fixed [a bug in `yarpp_related_exists()`](http://wordpress.org/support/topic/plugin-yet-another-related-posts-plugin-fatal-error-call-to-undefined-method-yarpp_cache_tablesstart_yarpp_time)
	* Removed legacy code for gracefully upgrading from YARPP versions < 1.5 and working with WordPress versions < 2.8.
	* Cleanup of `yarpp_upgrade_check()` calling
	* Cleanup of `yarpp_version_json()`, including caching and minor security fix
	* Eliminated a couple globals
	* Cleaned up some edge case causes for "unexpected output" on plugin activation
	* Removed WP Help Center badge, as they are closing
= 3.2 =
* Better caching performance:
  * Previously, the cache would never actually build up properly. This is now fixed. Thanks to Artefact for pointing this out.
  * The appropriate caches are cleared after posts are deleted ([#1245](http://plugins.trac.wordpress.org/ticket/1245)).
  * Caching is no longer performed while batch-importing posts.
* A new object-based abstraction for the caching system. YARPP by default uses custom database tables (same behavior as 3.1.x), but you now have an option to use the `postmeta` table instead. To use `postmeta` caching, add `define('YARPP_CACHE_TYPE', 'postmeta');` to your `wp-config.php` file.<!--YARPP no longer uses custom tables! Both custom tables (`yarpp_related_cache` and `yarpp_keywords_cache`) are automatically removed if you have them. WordPress Post Meta is used instead for caching.-->
* Localizations:
	* added Bulgarian (`bg_BG`) by [Flash Gallery](http://www.flashgallery.org);
	* added Farsi/Persian (`fa_IR`) by [Moshen Derakhshan](http://webdesigner.downloadkar.com/);
	* added Bahasa Indonesia (`id_ID`) by [Hendry Lee](http://hendrylee.com/) of [Kelayang](http://kelayang.com/)
	* added Norwegian (`nb_NO`) by [Tom Arne Sundtjønn](www.datanerden.no);
	* added Portuguese (`pt_PT`) by [Stefan Mueller](http://www.fernstudium-net.de).
	* updated Lithuanian (`lt_LT`) by [Mantas Malcius](http://mantas.malcius.lt/)
* Added [WordPress HelpCenter](http://wphelpcenter.com/) widget for quick access to professional support.
* Some code cleanup (bug [#1246](http://plugins.trac.wordpress.org/ticket/1246))
* No longer supporting WordPress versions before 3.0, not because I suddenly started using something that requires 3.0, but in order to simplify testing.
= 3.1.9 =
* Added Standard Arabic localization (`ar`) by [led](http://led24.de)
* The Related Posts Widget now can also use custom templates. ([#1143](http://plugins.trac.wordpress.org/ticket/1143))
* Fixed a [conflict with the Magazine Premium theme](http://wordpress.org/support/topic/419174)
* Fixes a WordPress warning of "unexpected output" on plugin installation.
* Fixes a PHP warning message regarding `array_key`.
* Fixed a strict WordPress warning about capabilities.
* Bugfix: widget now obeys cross-relate posts and pages option
* For WPMU + Multisite users, reverted 3.1.8's `get_site_option`s to `get_option`s, so that individual site options can be maintained.
= 3.1.8 =
* Added Turkish localization (`tr_TR`)
* Bugfix: related pages and "cross-relate posts and pages" functionality is now working again.
* Some bare minimum changes for Multisite (WPMU) support.
* Reimplemented the old "show only previous posts" option. May improve performance for sites with frequent new posts, as there is then no longer a need to recompute the previous posts' related posts set, as it cannot include the new post anyway.
* Minor bugfix to threshold limiting.
* Minor fix which may help reduce [`strip_tags()` errors](http://wordpress.org/support/topic/353588).
* Updated FAQ.
* Code cleanup.
= 3.1.7 =
* Added Egyptian Arabic localization (`ar_EG`)
* Changed default option for automatic display of related posts in feeds to OFF. May improve performance for new users who use the default settings.
* "Use template" options are now disabled when templates are not found. Other minor tweaks to options screen.
* 3.1.7 has been lightly tested with WP 3.0. Multisite (WPMU) compatibility has not been tested yet.
= 3.1.6 =
* Added Latvian localization (`lv_LV`)
* Added a template which displays post thumbnails; requires WordPress 2.9 and a theme which has post thumbnail support
= 3.1.5 =
* Quick bugfix to new widget template (removed extra quote).
= 3.1.4 =
* Improved widget code
* Localization improvements - descriptions can now be localized
* [Compatibility with PageBar](http://wordpress.org/support/topic/346714) - thanks to Latz for the patch!
* Bugfix: [`related_posts_exist` was giving incorrect values](http://wordpress.org/support/topic/362347)
* Bugfix: [SQL error for setups with blank DB_CHARSET](http://wordpress.org/support/topic/358757)
= 3.1.3 =
* Performance improvements:
  * Turning off cache expiration, made possible by smarter caching system of 3.1 - should improve caching database performance over time.
  * [updated primary key for cache](http://wordpress.org/support/topic/345070) by Pinoy.ca - improves client-side pageload times.
* Code cleanup
  * Rewrote `include` and `require` paths
* Bugfix: localizations were not working with WordPress 2.9 ([a CodeStyling Localizations bug](http://wordpress.org/support/topic/343389))
* Bugfix: [redundant entries for "unrelatedness" were being inserted](http://wordpress.org/support/topic/344859)
* Bugfix: [`yarpp_clear_cache` bug on empty input](http://wordpress.org/support/topic/343001)
* Version checking code no longer uses Snoopy.
* New localization: Hindi by [Outshine Solutions](http://outshinesolutions.com/)
= 3.1.2 =
* Bugfix: [saving posts would sometimes timeout](http://wordpress.org/support/topic/343001)
= 3.1.1 =
* [Possible fix for the "no related posts" issue](http://wordpress.org/support/topic/284209/page/2) by [vkovalcik](http://wordpress.org/support/profile/5032111)
* Bugfix: [slight optimization to keyword function](http://wordpress.org/support/topic/284209/page/2) by [vkovalcik](http://wordpress.org/support/profile/5032111)
* Bugfix: [regex issue with br-stripping](http://wordpress.org/support/topic/323823)
= 3.1 =
* New snazzy options screen
* Smarter, less confusing caching
  * No more manual caching option—"on the fly" caching is always on now.
* Bugfix: [fixed related pages functionality](http://wordpress.org/support/topic/273008)
* Bugfix: [an issue with options saving](http://wordpress.org/support/topic/312637)
* Bugfix: [a slash escaping bug](http://wordpress.org/support/topic/315560)
* Minor fixes:
  * Fixed `yarpp_settings_link` dependency when disabled.
  * Breaks (&lt;br /&gt;) are now stripped out of titles.
  * Added plugin incompatibility info for Pagebar.
  * Faster post saving.
= 3.0.13 =
* Quick immediate bugfix to 3.0.12
= 3.0.12 =
* Yet another DISQUS note... sigh.
* Changed [default markup](http://wordpress.org/support/topic/307890) to be make the output validate better.
* Reformatted the version log in readme.txt
* Added a Settings link to the plugins page
* Some initial WPML support:
  * Tweaked a SQL query so that it was WPML compatible
  * Added YARPP template to be used with WPML
* Added Hebrew localization
= 3.0.11 =
* Quick fix for `compare_version` code.
= 3.0.10 =
* Added Ukrainian localization
* Incorporated a quick update for the widget display [thanks to doodlebee](http://wordpress.org/support/topic/281575).
* Now properly uses `compare_version` in lieu of old hacky versioning.
= 3.0.9 =
* Added Uzbek, Greek, Cypriot Greek, and Vietnamese localizations
* Further bugfixes for the [pagination issue](http://wordpress.org/support/topic/267350)
= 3.0.8 =
* Bugfix: [a pagination issue](http://wordpress.org/support/topic/267350) (may not be completely fixed yet)
* Bugfix: a quick bugfix for widgets, thanks to Chris Northwood
* Added Korean and Lithuanian localizations
* Bugfix: [when ad-hoc caching was off, the cached status would always say "0% cached" ](http://wordpress.org/support/topic/286395)
* Bugfix: enabled Polish and Italian stopwords and [fixed encoding of Italian stopwords](http://wordpress.org/support/topic/288808).
* Bugfix: `is_single` and other such flags are now set properly within the related posts Loop (as a result, now [compatible with WP Greet Box](http://wordpress.org/support/topic/288230))
* Confirmed compatibility with 2.8.2
* Bugfix: [the Related Posts metabox now respects the Screen Options](http://wordpress.org/support/topic/289290)
= 3.0.7 =
* Bugfix: additional bugfix for widgets.
* Reinstating excerpt length by number of words (was switched to letters in 3.0.6 without accompanying documentation)
* Localizations:
  * Updated Italian
  * Added Belarussian by [Fat Cow](http://www.fatcow.com)
* Confirmed compatibility with 2.8.1
= 3.0.6 =
* Bugfix: [updated excerpting to use `wp_html_excerpt`](http://wordpress.org/support/topic/268934) (for WP 2.5+)
* Bugfix: [fixed broken widget display](http://wordpress.org/support/topic/276031)
* Added Russian (`ru_RU`) localization by Marat Latypov
* Confirmed 2.8 compatibility
* Added note on [incompatibility with SEO Pager plugin](http://wordpress.org/support/topic/267966)
= 3.0.5 =
* Added link to manual SQL setup information [by request](http://wordpress.org/support/topic/266752)
* Added Portuguese localization
* Updated info on "on the fly" caching - it is *strongly recommended* for larger blogs.
* Updated "incomplete cache" warning message so it is only displayed when the "on the fly" option is off.
= 3.0.4 =
* A fix to the version checking in the options page - now uses Snoopy
* Adding Dutch localization
= 3.0.3 =
* Reinstated the 3.0.1 bugfix for includes
* Bugfix: Fixed encoding issue in keyword caching algorithm
* Bugfix: One SQL query assumed `wp_` prefix on tables
* Added Polish localization
* Added note on DISQUS in readme
* Making some extra strings localizable
* Bugfix: [a problem with the Italian localization](http://wordpress.org/support/topic/265952)
= 3.0.2 =
* Bugfix: [Templating wasn't working with child templates.](http://wordpress.org/support/topic/265515)
* Bugfix: In some situations, [SQL errors were printed in the AJAX preview displays](http://wordpress.org/support/topic/265728).
= 3.0.1 =
* Bugfix: In some situations before YARPP options were updated, an `include` PHP error was displayed.
= 3.0 =
* Major new release!
* Caching for better SQL performance
* A new [templating feature](http://mitcho.com/blog/projects/yarpp-3-templates/) for custom related posts displays
* Cleaned up options page
* New and updated localizations
= 2.1.6 =
* Versioning bugfix - same as 2.1.5
= 2.1.5 =
* Bugfix: In certain scenarios, [related posts would be displayed in RSS feeds even when that option was off](http://wordpress.org/support/topic/216145)
* Bugfix: The `related_*()` functions were missing the `echo` parameter
* Some localization bugfixes
* Localizations:
	* Japanese (`ja`) by myself ([mitcho (Michael Yoshitaka Erlewine)](http://mitcho.com))
= 2.1.4 =
* Bugfix: [Settings' sumbmit button took you to PayPal](http://wordpress.org/support/topic/214090)
* Bugfix: Fixed [keyword algorithm for users without `mbstring`](http://wordpress.org/support/topic/216420)
* Bugfix: `title` attributes were not properly escaped
* Bugfix: [keywords did not filter tags](http://wordpress.org/support/topic/218211). (This bugfix may vastly improve "relatedness" on some blogs.)
* Localizations:
	* Simplified Chinese (`zh_CN`) by Jor Wang (mail at jorwang dot com) of [jorwang.com](http://jorwang.com)
	* German (`de_DE`) by Michael Kalina of [3th.be](http://3th.be)
* The "show excerpt" option now shows the first `n` words of the excerpt, rather than the content ([by request](http://wordpress.org/support/topic/212577))
* Added an `echo` parameter to the `related_*()` functions, with default value of `true`. If `false`, the function will simply return the output.
* Added support for the [AllWebMenus Pro](http://wordpress.org/extend/plugins/allwebmenus-wordpress-menu-plugin/) plugin
* Further internationalization:
	* the donate button! ^^
	* overused words lists ([by request](http://wordpress.org/support/topic/159359))), with a German word list.
= 2.1.3 =
* Bugfix: Turned off [the experimental caching](http://wordpress.org/support/topic/216194#post-894440) which shouldn't have been on in this release...
* Bugfix: an issue with the [keywords algorithm for non-ASCII characters](http://wordpress.org/support/topic/216078)
= 2.1.2 =
* Bugfix: MyISAM override handling bug
= 2.1.1 =
* Bugfix: keywords with forward slashes (\) could make the main SQL query ill-formed.
* Bugfix: Added an override option for the [false MyISAM warnings](http://wordpress.org/support/topic/211043).
* Preparing for localization! (See note at the bottom of the FAQ.)
* Adding a debug mode--just try adding `&yarpp_debug=1` to your URL's and look at the HTML source.
= 2.1 - The RSS edition! =
* RSS feed support!: the option to automagically show related posts in RSS feeds and to customize their display, [by popular request](http://wordpress.org/support/topic/151766).
* A link to [the Yet Another Related Posts Plugin RSS feed](http://wordpress.org/support/topic/208469).
* Further optimization of the main SQL query in cases where not all of the match criteria (title, body, tags, categories) are chosen.
* A new format for pushing arguments to the `related_posts()` functions.
* Bugfix: [compatibility](http://wordpress.org/support/topic/207286) with the [dzoneZ-Et](http://wordpress.org/extend/plugins/dzonez-et/) and [reddZ-Et](http://wordpress.org/extend/plugins/reddz-et/) plugins.
* Bugfix: `related_*_exist()` functions produced invalid queries
* A warning for `wp_posts` with non-MyISAM engines and semi-compatibility with non-MyISAM setups.
* Bugfix: [a better notice for users of Wordpress < 2.5](http://www.mattcutts.com/blog/wordpress-plugin-related-posts/#comment-131194) regarding the "compare tags" and "compare categories" features.
= 2.0.6 =
* A quick emergency bugfix (In one instance, assumed existence of `wp_posts`)
= 2.0.5 =
* Further optimized algorithm - should be faster on most systems. Good bye [subqueries](http://dev.mysql.com/doc/refman/5.0/en/unnamed-views.html)!
* Bugfix: restored MySQL 4.0 support
* Bugfix: [widgets required the "auto display" option](http://wordpress.org/support/topic/190454)
* Bugfix: sometimes default values were not set properly on (re)activation
* Bugfix: [quotes in HTML tag options would get escaped](http://wordpress.org/support/topic/199139)
* Bugfix: `user_level` was being checked in a deprecated manner
* A helpful little tooltip for the admin-only threshold display
= 2.0.4 - what 2.0 should have been =
* Bugfix: new fulltext query for MySQL 5 compatibility
* Bugfix: updated `apply_filters` to work with WP 2.6
= 2.0.3 =
* Bugfix: [2.0.2 accidentally required some tags or categories to be disabled](http://wordpress.org/support/topic/188745)
= 2.0.2 =
* Versioning bugfix (rerelease of 2.0.1)
= 2.0.1 =
* Bugfix: [`admin_menu` instead of `admin_head`](http://konstruktors.com/blog/wordpress/277-fixing-postpost-and-ozh-absolute-comments-plugins/)
* Bugfix: [a variable scope issue](http://wordpress.org/support/topic/188550) crucial for 2.0 upgrading
= 2.0 =
* New algorithm which considers tags and categories, by frequent request
* Order by score, date, or title, [by request](http://wordpress.org/support/topic/158459)
* Excluding certain tags or categories, [by request](http://wordpress.org/support/topic/161263)
* Sample output displayed in the options screen
* Bugfix: [an excerpt length bug](http://wordpress.org/support/topic/155034?replies=5)
* Bugfix: now compatible with the following plugins:
	- diggZEt
	- WP-Syntax
	- Viper's Video Quicktags
	- WP-CodeBox
	- WP shortcodes
= 1.5.1 =
* Bugfix: standardized directory references to `yet-another-related-posts-plugin`
= 1.5 =
* Simple installation: automatic display of a basic related posts install
* code and variable cleanup
* FAQ in the documentation
= 1.1 =
* Related pages support!
* Also, uses `apply_filters` to apply whatever content text transformation you use (Wikipedia link, Markdown, etc.) before computing similarity.
= 1.0.1 =
* Bugfix: 1.0 assumed you had Markdown installed
= 1.0 =
* Initial upload

== Upgrade Notice ==
= 3.3 =
Some YARPP options are now hidden by default. You can show them again from the Screen Options tab.

= 3.2.2 =
Requires PHP 5.
