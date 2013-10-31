<?php
/*
Plugin Name: Yet Another Related Posts Plugin
Plugin URI: http://yarpp.org/
Description: Returns a list of related entries based on a unique algorithm for display on your blog and RSS feeds. Now with Now with thumbnail support built-in!
Version: 4.0.2
Author: mitcho (Michael Yoshitaka Erlewine)
Author URI: http://mitcho.com/
Donate link: http://tinyurl.com/donatetomitcho
*/

define('YARPP_VERSION', '4.0.2');
define('YARPP_DIR', dirname(__FILE__));
define('YARPP_NO_RELATED', ':(');
define('YARPP_RELATED', ':)');
define('YARPP_NOT_CACHED', ':/');
define('YARPP_DONT_RUN', 'X(');

require_once(YARPP_DIR.'/class-core.php');
require_once(YARPP_DIR.'/related-functions.php');
require_once(YARPP_DIR.'/template-functions.php');
require_once(YARPP_DIR.'/class-widget.php');

if ( !defined('WP_CONTENT_URL') )
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

// New in 3.2: load YARPP cache engine
// By default, this is tables, which uses custom db tables.
// Use postmeta instead and avoid custom tables by adding the following to wp-config:
//   define('YARPP_CACHE_TYPE', 'postmeta');
if (!defined('YARPP_CACHE_TYPE'))
	define('YARPP_CACHE_TYPE', 'tables');
	
// New in 3.5: YARPP extra weight multiplier
if ( !defined('YARPP_EXTRA_WEIGHT') )
	define( 'YARPP_EXTRA_WEIGHT', 3 );

// new in 3.3.3: init yarpp on init
add_action( 'init', 'yarpp_init' );
function yarpp_init() {
	global $yarpp;
	$yarpp = new YARPP;
}

function yarpp_set_option($options, $value = null) {
	global $yarpp;
	$yarpp->set_option($options, $value);
}

function yarpp_get_option($option = null) {
	global $yarpp;
	return $yarpp->get_option($option);
}

function yarpp_plugin_activate() {
	update_option( 'yarpp_activated', true );
}
add_action( 'activate_' . plugin_basename(__FILE__), 'yarpp_plugin_activate' );
