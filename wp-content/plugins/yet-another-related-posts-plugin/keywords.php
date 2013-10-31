<?php

function yarpp_extract_keywords($source,$max = 20) {
	global $overusedwords;

	// 3.2.2: ignore soft hyphens
	// Requires PHP 5: http://bugs.php.net/bug.php?id=25670
	$softhyphen = html_entity_decode('&#173;',ENT_NOQUOTES,'UTF-8');
	$source = str_replace($softhyphen, '', $source);

	$charset = get_option('blog_charset');
	if ( function_exists('mb_split') && !empty($charset) ) {
		mb_regex_encoding($charset);
		$wordlist = mb_split('\s*\W+\s*', mb_strtolower($source, $charset));
	} else
		$wordlist = preg_split('%\s*\W+\s*%', strtolower($source));

	// Build an array of the unique words and number of times they occur.
	$tokens = array_count_values($wordlist);

	// Remove the stop words from the list.
	foreach ($overusedwords as $word) {
		 unset($tokens[$word]);
	}
	// Remove words which are only a letter
	foreach (array_keys($tokens) as $word) {
		if (function_exists('mb_strlen'))
			if (mb_strlen($word) < 2) unset($tokens[$word]);
		else
			if (strlen($word) < 2) unset($tokens[$word]);
	}

	arsort($tokens, SORT_NUMERIC);

	$types = array_keys($tokens);

	if (count($types) > $max)
		$types = array_slice($types, 0, $max);
	return implode(' ', $types);
}

function post_title_keywords($ID,$max = 20) {
	return yarpp_extract_keywords(yarpp_html_entity_strip(get_the_title($ID)),$max);
}

function yarpp_html_entity_strip( $html ) {
	$html = preg_replace('/&#x[0-9a-f]+;/','',$html);
	$html = preg_replace('/&#[0-9]+;/','',$html);
	$html = preg_replace('/&[a-zA-Z]+;/','',$html);
	return $html;
}

function post_body_keywords( $ID, $max = 20 ) {
	$post = get_post( $ID );
	if ( empty($post) )
		return '';
	$content = strip_tags( apply_filters_if_white( 'the_content', $post->post_content ) );
	$content = yarpp_html_entity_strip( $content );
	return yarpp_extract_keywords( $content, $max );
}

/* new in 2.0! apply_filters_if_white (previously apply_filters_without) now has a blacklist. It's defined here. */

/* blacklisted so far:
	- diggZ-Et
	- reddZ-Et
	- dzoneZ-Et
	- WP-Syntax
	- Viper's Video Quicktags
	- WP-CodeBox
	- WP shortcodes
	- WP Greet Box
	//- Tweet This - could not reproduce problem.
*/

$yarpp_blacklist = array(null,'yarpp_default','diggZEt_AddBut','reddZEt_AddBut','dzoneZEt_AddBut','wp_syntax_before_filter','wp_syntax_after_filter','wp_codebox_before_filter','wp_codebox_after_filter','do_shortcode');//,'insert_tweet_this'
$yarpp_blackmethods = array(null,'addinlinejs','replacebbcode','filter_content');

function yarpp_white($filter) {
	global $yarpp_blacklist;
	global $yarpp_blackmethods;
	if (is_array($filter)) {
		if (array_search($filter[1],$yarpp_blackmethods))
			return false;
	}
	if (array_search($filter,$yarpp_blacklist))
		return false;
	return true;
}

/* FYI, apply_filters_if_white was used here to avoid a loop in apply_filters('the_content') > yarpp_default() > yarpp_related() > current_post_keywords() > apply_filters('the_content').*/
function apply_filters_if_white($tag, $value) {
	global $wp_filter, $merged_filters, $wp_current_filter;

	$args = array();
	$wp_current_filter[] = $tag;

	// Do 'all' actions first
	if ( isset($wp_filter['all']) ) {
		$args = func_get_args();
		_wp_call_all_hook($args);
	}

	if ( !isset($wp_filter[$tag]) ) {
		array_pop($wp_current_filter);
		return $value;
	}

	// Sort
	if ( !isset( $merged_filters[ $tag ] ) ) {
		ksort($wp_filter[$tag]);
		$merged_filters[ $tag ] = true;
	}

	reset( $wp_filter[ $tag ] );

	if ( empty($args) )
		$args = func_get_args();

	do {
		foreach( (array) current($wp_filter[$tag]) as $the_ )
			if ( !is_null($the_['function'])
			and yarpp_white($the_['function'])){ // HACK
				$args[1] = $value;
				$value = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
			}

	} while ( next($wp_filter[$tag]) !== false );

	array_pop( $wp_current_filter );

	return $value;
}
