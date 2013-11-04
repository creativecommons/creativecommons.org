<?php
/*
Plugin Name: CC Permalink Mapper
Description: Maps old-style CC permalinks to the new-style ones, handles some redirects to canonical page names, and causes permalinks to be generated a la CC.
Version: 0.1
Author: Nathan Kinkade
Author URI: http://creativecommons.org
*/

/**
 * In this array go the mappings that need to happen when wordpress
 * goes to create permalinks.  The first item is a regular expression
 * that will be used to check if the permalink need rewriting, and
 * the second argument is what to replace the pattern with
 * The default is something like: /<category>/<year>/<month>/<day>/<post_id>/,
 * but we write that to something like /weblog/entry/<post_id>.  With
 * the array setup below it is possible to specify various rewrites, just
 * add them as needed.
 */
$cc_pl_rewrites = array(
	array("\/weblog\/\d{4}\/\d{2}\/\d{2}\/", "/weblog/entry/"),
	array("\/weblog\/cclearn\/\d{4}\/\d{2}\/\d{2}\/", "/weblog/entry/"),
	array("\/press-releases\/\d{4}\/\d{2}\/\d{2}\/", "/press-releases/entry/"),
	array("\/commoners\/\d{4}\/\d{2}\/\d{2}\/", "/weblog/entry/"),
	array("\/(\d{4})", "/weblog/\\1"),
	array("\/(\d{4}\/\d{2})", "/weblog/\\1")
);

# Since we go changing it around, this variable will simply keep track
# of what the original REQUEST_URI was.
$cc_orginal_request_uri = $_SERVER['REQUEST_URI'];

add_action("init", "cc_mangle_request");
add_filter("wp_footer", "cc_rewrite_request_uri_notify");
add_filter("post_link", "cc_rewrite_permalink");
add_filter("year_link", "cc_rewrite_permalink");
add_filter("month_link", "cc_rewrite_permalink");
add_filter("rewrite_rules_array", "cc_bare_category_rewrite_rules");


/**
 * Sometimes Wordpress decides what page to display based on both the query
 * string AND the REQUEST_URI.  mod_rewrite does not alter the REQUEST_URI, and
 * therefore mod_rewrite by itself is not always enough to coerce Wordpress into
 * doing what we want.  In these case, mod_rewrite will add a request variable
 * '&roflcopter' to rewritten request.  This is our signal that some REQUEST_URI
 * munging needs to happen.
 */
function cc_mangle_request() {
	if ( isset($_GET['roflcopter']) ) {
		# If 'p' is set then that means that we should already have a valid
		# post id and that we just need to alter the internal REQUEST_URI 
		# variable.  If 'post_name' is set, then this was an old-style permalink
		# that was using post names instead of IDs, so we've got to find the post
		# ID based on the name and then we'll just redirect the user to the
		# right URL.
		if ( isset($_GET['p']) ) {
			$_SERVER['REQUEST_URI'] = "/index.php?" . rtrim($_SERVER['QUERY_STRING'], "&roflcopter");
		} elseif ( isset($_GET['post_name']) ) {
			$post_id = cc_get_post_by_name($_GET['post_name']);
			if ( ! $post_id ) {
				# If there isn't actually a post with the title specified, then
				# just set $post_id to something descriptive that will be sure
				# to invoke a 404 error.  The value is arbitrary, so long as it's
				# not a real post id.
				$post_id = "404-not-found";
			}
			# 'category' was added by the mod_rewrite rule that got us here.
			header("Location: http://{$_SERVER['SERVER_NAME']}/{$_GET['category']}/entry/$post_id");
			exit;
		}
	}
}

/**
 * This is to force Wordpress to create permalinks like we want instead 
 * of the way it wants to.  It will want to write them as:
 * /<category>/<year>/<month>/<day>/<post id>/, but we want:
 * /<category>/entry/<post id>
 */
function cc_rewrite_permalink($link) {

	global $cc_pl_rewrites;

	foreach ( $cc_pl_rewrites as $cc_pl_rewrite ) {
		if ( preg_match("/{$cc_pl_rewrite[0]}/", $link) ) {
			$rewritten_link = preg_replace("/{$cc_pl_rewrite[0]}/", $cc_pl_rewrite[1], $link);
			return $rewritten_link;
		}
	}

	# If nothing was changed, then just return the original link	
	return $link;

}


/**
 * Drop a comment into the footer region of the page notifying
 * whoever about the change in REQUEST_URI
 */
function cc_rewrite_request_uri_notify() {

	global $cc_orginal_request_uri;

	if ( isset($_GET['roflcopter']) ) {
		echo "<!-- CC Permalink Mapper was here: $cc_orginal_request_uri -> {$_SERVER['REQUEST_URI']} -->\n";
	}

	return true;

}

/**
 * The old permalink structure for press-releases was:
 * /press-releases/<year>/<month>/<somereallylongandcrazytitlelikethis>
 * We still need to honor that old style, but the user needs to be redirected
 * to the 'canonical' format of /press-releases/entry/<postid>.  So we have
 * to lookup the post's ID based on it's name when we find one of these
 * old-style permalinks
 */
function cc_get_post_by_name($post_name) {

	global $wpdb;

	$sql = sprintf("
		SELECT ID FROM wp_posts
		WHERE post_name = '%s'
		",
		$post_name
	);

	$post_id = (int) $wpdb->get_var($sql);

	if ( $post_id ) {
		return $post_id;
	} else {
		return false;
	}

}

/**
 * WordPress doesn't technically allow bare category slugs i.e., URLs of the
 * form /catname.  Technically all category archive pages should be preceeded
 * by a category base, which is by default /category/catname.  However, on the
 * CC blog we've been using bare category slugs for years, and this has worked
 * just fine up until WP 3.4.  In 3.4 bare category archives work like
 * /catname, but pagination does not work.  This adds a few rewrite rules to WP
 * to force pagination to work.
 */
function cc_bare_category_rewrite_rules($rules) {

	$cc_rules = array();

	# We unset this existing rule so that we can later re-append it to the
	# list of rewrite rules, else it will match before the more specific
	# ones do.
	unset($rules['(.+?)/?$']);

	# Handles pagination for URLs like /catname/year/month/page/#
	$cc_rules['(.+?)/([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&year=$matches[2]&monthnum=$matches[3]&paged=$matches[4]';

	# Handles pagination for URLs like /catname/year/page/#
	$cc_rules['(.+?)/([0-9]{4})/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&year=$matches[2]&paged=$matches[3]';

	# Handles pagination for URLs like /catname/page/#
	$cc_rules['(.+?)/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';

	# Handles the basic bare category for URLs like /catname
	$cc_rules['(.+?)/?$'] = 'index.php?category_name=$matches[1]';

	# Now append our rules to the ones WP generated
	return $rules + $cc_rules;

}

?>
