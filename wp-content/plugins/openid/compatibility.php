<?php
/**
 * Implement a few of the functions available only in recent versions of 
 * WordPress.  I'd much rather reimplement these functions here, and keep the 
 * rest of the plugin code clean.
 */

/* since 2.6 */
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

/* since 2.6 */
if (!function_exists('site_url')):
function site_url($path = '', $scheme = null) {
	$url =  get_option('siteurl');
	if ( !empty($path) && is_string($path) && strpos($path, '..') === false ) {
		$url .= '/' . ltrim($path, '/');
	}
	return $url;
}
endif;


/* since 2.6 */
if (!function_exists('admin_url')):
function admin_url($path = '') {
	$url = site_url('wp-admin/', 'admin');
	if ( !empty($path) && is_string($path) && strpos($path, '..') === false ) {
		$url .= ltrim($path, '/');
	}
	return $url;
}
endif;

/* since 2.6 */
if (!function_exists('plugins_url')):
function plugins_url($path = '') {
	$url = WP_PLUGIN_URL;
	if ( !empty($path) && is_string($path) && strpos($path, '..') === false ) {
		$url .= '/' . ltrim($path, '/');
	}
	return $url;
}
endif;

/* since 2.5 */
if (!function_exists('is_front_page')):
function is_front_page() {
	return is_home();
}
endif;

/* since 2.3 - copied from $wpdb->prepare */
if (!function_exists('wpdb_prepare')):
function wpdb_prepare($args=null) {
	global $wpdb;

	if (is_null($args)) return;
	$args = func_get_args();

	if (method_exists($wpdb, 'prepare')) {
		return call_user_func_array(array($wpdb,'prepare'), $args);
	} else {
		$query = array_shift($args);
		$query = str_replace("'%s'", '%s', $query); // in case someone mistakenly already singlequoted it
		$query = str_replace('"%s"', '%s', $query); // doublequote unquoting
		$query = str_replace('%s', "'%s'", $query); // quote the strings
		array_walk($args, create_function('&$s', '$s = addslashes($s);'));
		return @vsprintf($query, $args);
	}
}
endif;

?>
