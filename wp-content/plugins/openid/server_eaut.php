<?php
/**
 * All the code required for handling EAUT requests.  These functions should not be considered public, 
 * and may change without notice.
 */

add_action( 'parse_request', 'openid_parse_eaut_request');
add_filter( 'xrds_simple', 'openid_eaut_xrds_simple');

function openid_eaut_mapper($email) {
	$user = get_user_by_email($email);
	if ($user) $user = new WP_User($user->ID);

	if ($user && $user->has_cap('use_openid_provider')) {
		if ($user->user_login == get_option('openid_blog_owner')) {
			$openid = get_option('home');
		} elseif (get_usermeta($user->ID, 'openid_delegate')) {
			$openid = get_usermeta($user->ID, 'openid_delegate');
		} else {
			$openid = get_author_posts_url($user->ID);
		}

		wp_redirect($openid);
	} else {
		header('HTTP/1.0 500 Internal Server Error');
	}

	die;
}

/**
 * Parse the WordPress request.  If the query var 'eaut_mapper' is present, then 
 * handle the request accordingly.
 *
 * @param WP $wp WP instance for the current request
 */
function openid_parse_eaut_request($wp) {
	if (@$wp->query_vars['eaut'] == 'mapper' && $_REQUEST['email']) {
		openid_eaut_mapper($_REQUEST['email']);
	}
}

/**
 * Add EAUT Mapper server to XRDS document.
 */
function openid_eaut_xrds_simple($xrds) {
	if (get_option('openid_xrds_eaut')) {
		$xrds = xrds_add_simple_service($xrds, 'Email Address to URL Transformation Mapper', 
			'http://specs.eaut.org/1.0/mapping', openid_service_url('eaut', 'mapper'));
	}

	return $xrds;
}

?>
