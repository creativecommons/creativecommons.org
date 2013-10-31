=== OpenID ===
Contributors: willnorris, factoryjoe
Tags: openid, authentication, login, comments
Requires at least: 2.8
Tested up to: 2.8.5
Stable tag: 3.3.4

Allows WordPress to provide and consumer OpenIDs for authentication of users and comments.

== Description ==

OpenID is an [open standard][] that allows users to authenticate to websites
without having to create a new password.  This plugin allows users to login to
their local WordPress account using an OpenID, as well as enabling commenters
to leave authenticated comments with OpenID.  The plugin also includes an OpenID
provider, enabling users to login to OpenID-enabled sites using their
own personal WordPress account. [XRDS-Simple][] is required for the OpenID
Provider and some features of the OpenID Consumer.

Developer documention, which includes all of the public methods and hooks for
integrating with and extending the plugin, can be found [here][dev-doc].

[open standard]: http://openid.net/
[XRDS-Simple]: http://wordpress.org/extend/plugins/xrds-simple/
[dev-doc]: http://wiki.diso-project.org/wordpress-openid-api

== Installation ==

This plugin follows the [standard WordPress installation method][]:

1. Upload the `openid` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the plugin through the 'OpenID' section of the 'Options' menu

[standard WordPress installation method]: http://codex.wordpress.org/Managing_Plugins#Installing_Plugins


== Frequently Asked Questions ==

= Why do I get blank screens when I activate the plugin? =

In some cases the plugin may have problems if not enough memory has been
allocated to PHP.  Try ensuring that the PHP memory\_limit is at least 8MB
(limits of 64MB are not uncommon).

= Why don't `https` OpenIDs work? =

SSL certificate problems creep up when working with some OpenID providers
(namely MyOpenID).  This is typically due to an outdated CA cert bundle being
used by libcurl.  An explanation of the problem and a couple of solutions 
can be found [here][libcurl].

[libcurl]: http://lists.openidenabled.com/pipermail/dev/2007-August/000784.html

= Why do I get the error "Invalid openid.mode '<No mode set>'"? =

There are actually a couple of reasons that can cause this, but it seems one of
the more common causes is a conflict with certain mod_security rules.  See 
[this blog post][ioni2] for instructions on how to resolve this issue.

[ioni2]: http://ioni2.com/2009/wordpress-openid-login-failed-invalid-openid-mode-no-mode-set-solved-for-both-wordpress-and-drupal/


= How do I use SSL for OpenID transactions? =

First, be aware that this only works in WordPress 2.6 and up.  Make sure you've
turned on SSL in WordPress by [defining either of the following][wp-ssl]
globals as "true" in your `wp-config.php` file:

 - FORCE\_SSL\_LOGIN
 - FORCE\_SSL\_ADMIN

Then, also define the following global as "true" in your `wp-config.php` file:

 - OPENID\_SSL

Be aware that you will almost certainly have trouble with this if you are not
using a certificate purchased from a well-known certificate authority.

[wp-ssl]: http://codex.wordpress.org/Administration_Over_SSL

= How do I get help if I have a problem? =

Please direct support questions to the "Plugins and Hacks" section of the
[WordPress.org Support Forum][].  Just make sure and include the tag 'openid'
so that I'll see your post.  Additionally, you can file a bug
report at <http://code.google.com/p/diso/issues/list>.  

[WordPress.org Support Forum]: http://wordpress.org/support/


== Screenshots ==

1. Commentors can use their OpenID when leaving a comment
2. Users can login with their OpenID in place of a traditional username and password
3. Users authorized to use the OpenID Provider can delegate to a different provider
4. Users can add additional OpenIDs which they can use to login to WordPress
5. Users authorized to use the OpenID Provider can monitor which sites they've logged in to


== Changelog ==

Project maintined on github at
[diso/wordpress-openid](https://github.com/diso/wordpress-openid).

= version 3.3.4 (Nov 16, 2012) =
 - update to latest OpenID library (includes lots of bug fixes, particularly with PHP 5.3). Full changelog [on github](https://github.com/openid/php-openid).
 - various bug fixes. Full changelog [on github](https://github.com/diso/wordpress-openid).

= version 3.3.3 (Aug 24, 2010) =
 - add/update danish, japanese, and spanish translations
 - update to latest version of php-openid library
 - fix a few PHP and WordPress warnings and notices

= version 3.3.2 (Nov 06, 2009) =
 - add localizations for czech, danish, french, spanish, and vietnamese.  Some are more up to date than others.  More are welcome, see http://code.google.com/p/diso/issues/detail?id=26
 - remove stylesheet for recent comments widget, since it breaks the style for OpenID comments
 - various fixes with administration panels

= version 3.3.1 (Sep 28, 2009) =
 - tiny bug in get_user_openids causing it to always return empty array

= version 3.3 (Sep 28, 2009) =
 - minimum required version has been bumped to WordPress 2.8
 - fix support for WordPress MU
 - new, less obtrusive UI for comment form.  Should also work with all themes in some form (with or without js).
 - many administrative options have been moved to their respective locations on core WordPress Settings pages
 - drop support for experimental EAUT and IDIB protocols
 - drop support for installing the plugin in mu-plugins folder
 - always include 'index.php' on OpenID endpoint URLs.  Without that, some deployments were having problems.
 - fix bug relating to trackbacks and pingbacks
 - fix bug (#121) relating to unregistered options (props: tom.tdw for the patch)
 - lots of minor bug fixes

= version 3.2.3 (Jul 20, 2009) =
 - fix XSS vulnerability. (props: Stuart Metcalfe)

= version 3.2.2 (Mar 19, 2009) =
 - fix problems when using non-index.php permalinks with non-apache web servers
 - ensure that show\_on\_front option is not empty
 - function name typo (props: gunemalli)
 - fix deprecated pass-by-reference call in php-openid library (props: jschuur)
 - fix UI bug on registration form with IE browsers (props: oledole)
 - UI tweaks to better match WP 2.7
 - update a few strings for localization and POT file

= version 3.2.1 (Feb 13, 2009) =
 - patch php-openid library to fix XRDS handling (thanks Mike Jones for helping find this)
 - add default values for some openid vars -- necessary for OP-initiated login
 - fix bug with OpenID server where OpenID request was sometimes lost
 - add filter for openid\_trust\_root

= version 3.2 (Jan 20, 2009) =
 - add uninstall hook for WordPress 2.7 -- this will remove all traces of the plugin from the database
 - UI fixes for WordPress 2.7
 - add settings link to plugins page
 - silence XML parsing errors with PHP4
 - ensure wp\_scripts is set
 - ensure openid comment processing occurs after akismet
 - add ellipses to truncated OpenIDs (fixes #94)
 - fix bug where Yahoo! OpenIDs weren't matching profile URL (fixes #98)
 - don't return empty SREG values
 - Add support for consuming Attribute Exchange
 - use a single return\_to URL for all OpenID actions
 - cleaner OpenID service URLs when permalinks configured to do so (all path, no query string)
 - fixed issue where OpenID Server would sometimes break depending on a users permalink structure (fixed #101)
 - fixed issue where OpenID consumer would sometimes break if mod\_encoding was enabled in Apache (used for WebDAV) (fixed #96)
 - don't redirect when performing discovery on OpenID trust root

= version 3.1.4 (Nov 04, 2008) =
 - allow OP extensions to include XRDS Types in login service
 - run OpenID comment processor after Akismet, and skip if Akismet marks comment as spam

= version 3.1.3 (Oct 27, 2008) =
 - fix error message if /dev/urandom is not readable

= version 3.1.2 (Oct 26, 2008) =
 - ensure source of randomness is set properly
 - prevent duplicate cleanup\_openid cron jobs
 - prevent SQL errors on activation
 - suppress verbose error logging with XML parsing

= version 3.1.1 (Oct 20, 2008) =
 - fix bug with OpenID Provider XRDS code that prevents ability to login to some sites (like plaxo.com)

= version 3.1 (Oct 19, 2008) =
 - added hidden constant to set custom comments post page (OPENID\_COMMENTS\_POST\_PAGE)
 - additional option to skip name and email check for OpenID comments
 - use preferred username (from SREG) if possible when creating new account
 - truncate long URLs when used as display\_name for comments
 - numerous bug fixes, including bug with registration form

= version 3.0 (Oct 02, 2008) =
 - includes OpenID Provider
 - supports OpenID delegation
 - add experimental support for Email Address to URL Transformation
 - many new hooks for extension and integration
 - major code refactoring

= version 2.2.2 (Aug 06, 2008) =
 - fix bug with "unauthorized return\_to URL" (only known problem with [openid.pl][])
 - fix bug with comments containing non-latin characters
 - respect CUSTOM\_USER\_META\_TABLE constant if present (also added CUSTOM\_OPENID\_IDENTITY\_TABLE constant)
 - add experimental support for Identity in the Browser

= version 2.2.1 (Jul 25, 2008) =
 - fixed EAUT handling code
 - fixed bug that broke comments containing double quotes (")

= version 2.2.0 (Jul 23, 2008) =
 - use POST replay for comments (fixes compatibility with other comment plugins)
 - only build openid object when needed (much better memory usage)
 - support for Email Address to URL Transformation (see eaut.org)
 - fixed bug when using suhosin (hardened php)
 - use hooks for gathering user data (more extensible)
 - fixed openid spoofing vulnerability (http://plugins.trac.wordpress.org/ticket/702)
 - lots code refactoring and UI cleanup

= version 2.1.9 (May 20, 2008) =
 - fix javascript loading issues
 - fix various bugs when creating new account with OpenID
 - fix error message, and add new warning prompt when removing last OpenID for account

= version 2.1.8 (Apr 02, 2008) =
 - fix UI issue with wp-login.php page in WP2.5
 - fix bug printing supported curl protocols (http://wordpress.org/support/topic/159062)
 - fix jquery bug while adding category in  WP2.5  (http://wordpress.org/support/topic/164305)

= version 2.1.7 (Mar 21, 2008) =
 - remove php5 dependency bug... AGAIN!
 - also remove some other custom changes to php-openid I forgot were in there.  This may actually re-introduce some edge-case
   bugs, but I'd rather expose them so that we can get the appropriate patches pushed upstream if they really are necessary.

= version 2.1.6 (Mar 20, 2008) =
 - update php-openid library to latest.  Now properly supports Yahoo's OpenID provider.

= version 2.1.5 (Mar 20, 2008) =
 - add support for wordpress v2.5

= version 2.1.4 (Feb 13, 2008) =
 - fix php5 dependency bug
 - improve jQuery code to reduce problems with other js libraries

= version 2.1.3 (Feb 06, 2008) =
 - address security bug mentioned [here](http://www.gnucitizen.org/blog/hijacking-openid-enabled-accounts).  Props: Sam Alexander

= version 2.1.2 =
 - minor typo in profile data code

= version 2.1.1 =
 - minor bug where profile data is being overwritten

= version 2.1 =
 - added FAQ items for plugin updater and adding an OpenID field to a comment form
 - better tracking of which users have OpenIDs linked to their local WP account
 - better automatic username generation
 - fixed bug where non-OpenID websites had problems (bug [729])
 - upgrade to version 2.0 of JanRain OpenID library
 - admin option to rebuild tables

= version 2.0 =
 - simplified admin interface by using reasonable defaults.  Default behaviors include:
  - "unobtrusive mode"
  - always add openid to wp-login.php
  - always use WP option 'home' for the trust root
 - new features
  - hook for trust engine, with very simple implementation included
  - supports OpenID 2.0 (draft 12) as well as OpenID 1.1 and SReg 1.0
 - normal collection of bug fixes

= version 1.0.1 =
 - added wordpress.org style readme.txt
 
= version 1.0 (also known as r13) =

Full SVN logs are available at <http://dev.wp-plugins.org/log/openid/>.

[729]: http://dev.wp-plugins.org/ticket/729
[openid.pl]: http://openid.pl/

The original OpenID plugin for WordPress was a collaborative effort between Alan Castonguay and Hans Granqvist.

Will Norris forked the plugin and has since become the maintainer.

[Alan Castonguay]: http://verselogic.net/
[Hans Granqvist]: http://commented.org/
[Will Norris]: http://willnorris.com/
