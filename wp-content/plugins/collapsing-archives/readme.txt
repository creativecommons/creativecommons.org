=== Collapsing Archives ===
Contributors: robfelty
Donate link: http://blog.robfelty.com/wordpress-plugins
Tags: archives, sidebar, widget, navigation, menu, posts, collapsing, collapsible
Requires at least: 2.8
Tested up to: 3.0.4
Stable tag: 1.3.2

This plugin uses Javascript to dynamically expand or collaps the set of
months for each year and posts for each month in the archive listing.

== Description ==

This is a relatively simple plugin that uses Javascript to
make the Archive links in the sidebar collapsable by year and/or month.

= What's new? =

*  1.3.2 (2011.01.03)
    * Fixed display bug when only one month in year
*  1.3.1 (2010.06.22)
    * Fixed bug where months would not expand for current year when "expand
      current year" was set to no
*  1.3 (2010.06.18) 
    * Storing post information in javascript array to reduce number of DOM
      elements (and improve page loading speed)
    * Added option to select post date before or after title

 
See the CHANGELOG for more information

== Installation ==

IMPORTANT!
Please deactivate before upgrading, then re-activate the plugin. 

= MANUAL INSTALLATION =

Unpack the contents to wp-content/plugins/ so that the files are in a
collapsing-archives directory. Now enable the plugin. To use the plugin,
change the following here appropriate (most likely sidebar.php):

Change From:

    <ul>
     `<?php wp_get_archives(); ?>`
    </ul>

To something of the following:
`
    <?php
     if( function_exists('collapsArch') ) {
      collapsArch();
     } else {
      echo "<ul>\n";
      wp_get_archives();
      echo "</ul>\n";
     }
    ?>
`
You can specify options for collapsArch. See options section.


= WIDGET INSTALLATION =

For those who have widget capabilities, (default in Wordpress 2.3+), installation is easier. 

Unzip contents to wp-content/plugins/ so that the files are in a
collapsing-archives directory.  You must enable the Collapsing Archives
plugin,  then simply go the Presentation > Widgets section and add the
Collapsing Archives Widget.

== Frequently Asked Questions ==

=  How do I change the style of the collapsing archives lists? =

  The collapsing archives plugin uses several ids and classes which can be
styled with CSS. These can be changed from the settings page. You may have to
rename some of the id statements. For example, if your sidebar is called
"myawesomesidebar", you would rewrite the line 

  #sidebar li.collapsArch {list-style-type:none}
  to
  #myawesomesidebar li.collapsArch {list-style-type:none}

If you are using the plugin manually (i.e. inserting code into your theme),
you may want to replace #sidebar with #collapsArchList

= There seems to be a newline between the collapsing/expanding symbol and the
category name. How do I fix this? =

If your theme has some css that says something like

#sidebar li a {display:block}

that is the problem. 
You probably want to add a float:left to the .sym class
   
== Screenshots ==

1. Collapsing archives with default theme
2. available options

== Demo ==

I use this plugin in my blog at http://blog.robfelty.com


== OPTIONS AND CONFIGURATIONS ==

`$defaults=array(
  'noTitle' => '',
  'inExcludeCat' => 'exclude',
  'inExcludeCats' => '',
  'inExcludeYear' => 'exclude',
  'inExcludeYears' => '',
  'sort' => 'DESC',
  'showPages' => false, 
  'linkToArch' => true,
  'showYearCount' => true,
  'expandCurrentYear' => true,
  'expandMonths' => true,
  'expandYears' => true,
  'expandCurrentMonth' => true,
  'showMonthCount' => true,
  'showPostTitle' => true,
  'expand' => '0',
  'showPostDate' => false,
  'postDateFormat' => 'm/d',
  'postDateAppenc' => 'after',
  'animate' => 0,
  'postTitleLength' => '',
  'debug' => '0',
  );
`

* noTitle
    * If your posts don't have title, specify a string to show in place of the
      title
* inExcludeCat
    * Whether to include or exclude certain categories 
        * 'exclude' (default) 
        * 'include'
* inExcludeCats
    * The categories which should be included or excluded
* inExcludeYear
    * Whether to include or exclude certain years 
        * 'exclude' (default) 
        * 'include'
* inExcludeYears
    * The years which should be included or excluded
* showPages
    * Whether or not to include pages as well as posts. Default if false
* showYearCount
    *  When true, the number of posts in the year will be shown in parentheses 
* showMonthCount
    *  When true, the number of posts in the month will be shown in parentheses 
* linkToArch
    * 1 (true), clicking on a the month or year will link to the archive (default)
    * 0 (false), clicking on a month or year expands and collapses 
* sort
    * Whether posts should be sorted in chronological  or reverse
      chronological order. Possible values:
        * 'DESC' reverse chronological order (default)
        * 'ASC' chronological order
* expand
    * The symbols to be used to mark expanding and collapsing. Possible values:
        * '0' Triangles (default)
        * '1' + -
        * '2' [+] [-]
        * '3' images (you can upload your own if you wish)
        * '4' custom symbols
* customExpand
    * If you have selected '4' for the expand option, this character will be
      used to mark expandable link categories
* customCollapse
    * If you have selected '4' for the expand option, this character will be
      used to mark collapsible link categories
 
* expandYears
    * 1 (true): Years collapse and expand to show months (default)
    * 0 (false): Only links to yearly archives are shown
* expandMonths
    * 1 (true): Months collapse and expand to show posts (default)
    * 0 (false): Only links to yearly and monthly archives are shown
* expandCurrentMonth
    * When true, the current month will be expanded by default
* expandCurrentYear
    * When true, the current year will be expanded by default
* showPostTitle
    * 1 (true): The title of each post is shown (default)
* showPostDate
    * 1 (true): Show the date of each post 
* postDateFormat
    * The format in which the date should be shown (default: 'm/d')
* postDateAppend
    * after: The post date comes after the title (default)
    * before: The post date comes before the title 
* postTitleLength
    * Truncate post titles to this number of characters (default: 0 = don't
      truncate)
* animate
    * When set to true, collapsing and expanding will be animated
* number
    * If using manually with more than one instance on a page, you can give
      unique ids to each instance with this option. For example, if you had
      one instance with number 1 and another with number 2, the ul for March
      2004 for number 1 would have an id of 'collapsArch-2004-3:1', while the
      id for number 2 would be 'collapsArch-2004-3:2'
* debug
    * When set to true, extra debugging information will be displayed in the
      underlying code of your page (but not visible from the browser). Use
      this option if you are having problems

= Examples =

`collapsArch('animate=1&sort=ASC&expand=3,inExcludeCats=general,uncategorized')`
This will produce a list with:
* animation on
* shown in chronological order
* using images to mark collapsing and expanding
* exclude posts from  the categories general and uncategorized

== CAVEAT ==

This plugin relies on Javascript, but does degrade
gracefully if it is not present/enabled to show all of the
archive links as usual.

== CHANGELOG ==

=  1.3.2 (2011.01.03) =
* Fixed display bug when only one month in year

=  1.3.1 (2010.06.22) =
* Fixed bug where months would not expand for current year when "expand
  current year" was set to no (thanks to beardedgit for debugging help)

=  1.3 (2010.06.18) =
* Storing post information in javascript array to reduce number of DOM
  elements (and improve page loading speed)
* Added option to select post date before or after title

=  1.2.2 (2010.01.28) =
* Restricting settings page to authorized users
* Fixed expandYears option. Now when you show posts, but not months, the
  year expands to show posts. 
* Updated javascript to fix cookie bug
* Switched from scriptaculous to jquery. Now no longer conflicts with
  plugins which use mootools (for example featured content gallery)

=  1.2.1 (2009.06.22) =
* Can now use manually in WP 2.7-
* Updated Spanish localization (thanks to Karin Sequen)
* Fixed problems with page load and cookies

=  1.2.beta (2009.06.07) =
* Changed hide and show classed to collapse and expand to avoid CSS class
  conflicts

=  1.2.alpha (2009.05.02) =
* Widgets work with 2.8 API
* Can specify options directly in manual usage

=  1.1.4 (2009/04/22)  =
* Fixed html validation error when using manual version
* Spanish localization (thanks to Karin Sequen)

=  1.1.3 (2009/04/17) =
* Fixed bug with unicode codes showing up instead of triangles

=  1.1.2 (2009/03/28) =
* Span all on one line so it doesn't mess up exec-php (thanks GeekLad)
* fixed some minor issues to get page to be valid xhtml
* no longer requires footer
* updated javascript file
* added option for custom expanding and collapsing symbols

=  1.1 (2009/03/07) =
* fixed bug with truncating titles
* cleaned up code a bit
* fixed query for excluding categories
* fixed including only certain categories
* added option for "no title" - suggested by Brad Parker
* reduced number of queries by using get_permalink without id
* Improved internationalization
* fixed settings panel

=  1.0.5 (2009/01/21) =
* changed query
* when using truncated titles, title attribute has full title
* using html ellipsis in truncate titles
* Got rid of comma before post date
* fixed some issues with settings page
* updated FAQ

=  1.0.4 (2009/01/15) =
* fixed debug option
* style is set in database if the style column is not already there

=  1.0.3 (2009/01/09) =
* don't put an expand icon for years if "show months" is not selected
* add self class to post for additional styling
* fixed :before style info to restore default style
* fixed post title truncating

=  1.0.2 (2009/01/07) =
* added javascript version
* not loading unnecessary code for admin pages (fixes interference with
  akismet stats page
* fixed settings page for manual usage
* fixed sort order option

=  1.0.1 (2009/01/06) =
* Finally fixed disappearing widget problem when trying to add to sidebar
* Added debugging option to show the query used and the output
* Moved style option to options page

=  1.0 (2008.12.08) =
* Integrating javascript with other collapsing plugins
* Non-widget version now works out of the box (defaults added to database
  upon activation)
* style can now be set with an option
* inline javascript moved to bottom for faster page loading

=  0.9.6 (2008.12.02) =
* Minor bug fix with missing end tag when years expand to months, but
  months do not expand to posts

=  0.9.5 (2008.12.01) =
* fixed javascript bug for IE7

=  0.9.4 (2008.11.21) =
* Improved handling of options for non-widget version
* Uses cookies to keep track of expanded and collapsed years/months
* tested with 2.7 beta3

=  0.9.3 (2008.11.04) =
* Now can once again use as a widget or non-widget

=  0.9.2 (2008.11.01) =
* Fixed truncating of title

=  0.9.1 (2008.10.28) =
* added collapsArchMonth class for when posts are not shown
* added img directory
* calling it stable

=  0.9.alpha (2008.10.23)  =
* Can now use more than one widget
* Added option to animate collapsing and expanding
* Added option to use images as collapsing symbols
* Added option to have the year and month activate collapsing instead of
  linking to the yearly/monthly archive

=  0.8.9 (2008.06.04) =
* added option for different expand and collapse symbols (triangles, +/-)

=  0.8.8 (2008.05.27) =
* added some more FAQ about stylesheets
* added option to only include certain years

=  0.8.7 =
* fixed sparse year problem (extra tags would get inserted after a year
  with only one post which was in January) -- thanks to [aishdas]
  (http://wordpress.org/support/profile/444678) for pointing this out

=  0.8.6 =
* fixed bug which had wrong markup when months were turned off

=  0.8.5 =
* fixed bug (introduced in 0.8.2) that made the widget not show up after an
  upgrade

=  0.8.4 =
* title of archives now shows up correctly using before_title and
  after_title

=  0.8.3  =
* fixed bug introduced in version 0.8.2 trying to exclude categories. Would
  break if no categories were being excluded

=  0.8.2 =
* Added option to exclude posts that belong to certain categories. So far
  this is only working for posts that belong to a single category
* Added option to change title in widget, and can now set all options from
  the widget page
* Now is condensed into one plugin

=  0.8.1 =
* Changed htmlentities to htmlspecialchars in formatting title text. Now
  this should not mess up accented characters, but should escape quotes
* Using unicode codes in css file for double quote character

=  0.8  =
* Verified to work with wordpress 2.5
* Now has custom styling option through the collapsArch.css stylesheet
* updated screenshots
* (Hopefully) fixed multi-language support for titles (put htmlentitites
  back in)
* moved javascript into collapsArch.php and got rid of separate file

=  0.7.8  =
* Got rid of htmlentities in post titles. Should display better now

=  0.7.7 =
* Now links should work with all sorts of permalink structures. Thanks to
  Krysthora http://krysthora.free.fr/ for finding this bug

=  0.7.6 =
* fixed some more markup issues to make it valid xhtml

=  0.7.5 =
* fixed bug when turning off "month links should expand to show posts" 
  option

=  0.7.4 =
* fixed broken links

=  0.7.3 =
* posts now have the class "collapsCatPost" and can be styled with CSS.
  Some styling has been added in collapsCat.php
* removed list icons in front of triangles

=  0.7.2 =
* Added option to link to index.php, root, or archives.php

=  0.7.1 =
* Fixed comment count feature in post links
* Fixed display of date in post links
* Fixed automatic loading of options into database

=  0.7: =
		* Complete rewrite of database code to reduce the number of queries from
		  2 * #months + 1 to 1 single query

=  0.6.2:  =
* Added collapsing class to <li>s with triangles for CSS styling
* Added style information to make triangles bigger and give a pointer
  cursor over them
* Added title tags to triangles to indicate functionality

=  0.6.1: =
* Bug fix - fixed the previous year triangle pointing in the wrong 
  direction
* Changed default options to reflect how I use it on my website

=  0.6:  =
* Changed name from Fancy Archives to Collapsing Archives
* Changed author from Andrew Rader to Robert Felty
* Added option to link to archives.php
* Added option to list in chronological or reverse chronological order
* Added triangles which mark the collapsing and expanding features
  That is, clicking on the triangle collapses or expands, while clicking
  on a month or year links to the archives for the said month or year
* Changed behavior from starting all expanded and then collapsing on page
  load to the opposite
* Removed the rel='hide' and rel='show' tags, because they are not xhtml
  1.0 compliant. Now uses the CSS classes instead

---------------------------------------------------------------------------
Fancy Archives Changelog
=  0.5: =
* Added option to display Page entries with Posts inside the month links
* Cleaned up the list generation code

=  0.4: =
* Added option: Trim post titles to a set size
* Added option: Optionally show ellipsis if a post title was shrunk
* Fix: Added fix for when page's content-type is "application/xhtml+xml"

=  0.3: =
* Huge rewrite: cleaned up javascript - one function does all the work,
  javascript no longer visible in page source
* Added options: month links are optional, set current year/month to be
  expanded by default
* Links now link to 'javascript;' instead of '#'

=  0.2.5: =
* Fixed an issue with displaying comment counts in < WP2.0, fixed by using
  WP's internal comment counting function (Thanks Will)

=  0.2: =
* Massive update, now has a dedicated options page (no more passing
  options to function)
* Month links can expand to show individual posts

=  0.1: =
	* Initial Release
