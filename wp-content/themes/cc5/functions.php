<?php
// Remove link rel="start" from blog pages
remove_action( 'wp_head', 'start_post_rel_link'); // Removes the start link

remove_action( 'wp_head', 'feed_links_extra', 3); // remove default feed links


if (function_exists('register_sidebar')) {
    register_sidebar(array('before_widget' => '<div class="widget">', 'after_widget' => '</div>'));

    register_sidebar(array(
    	'name' => 'Single Post',
    	'description' => 'Widgets in this area will only appear on single posts',
    	'before_widget' => '<div class="widget">',
    	'after_widget' => '</div>',
    	'before_title' => '<h2>',
    	'after_title' => '</h2>'));
}

function cc_progress_total() {
  $campaign_total = file_get_contents(__DIR__ . '../../includes/total.txt');

  print $campaign_total;
}


/* Requests the first available page with the 'show_on_index' custom field */
function cc_get_sticky_page() {
	global $wpdb;

	$query = "
		SELECT posts.*
		FROM $wpdb->posts posts, $wpdb->postmeta postmeta
		WHERE posts.ID = postmeta.post_id
		AND postmeta.meta_key = 'show_on_index'
		AND postmeta.meta_value = 'yes'
		AND posts.post_status = 'publish'
		AND posts.post_type = 'page'
		ORDER BY posts.post_date ASC LIMIT 1";
	$page = $wpdb->get_row ($query);

	return $page;
}

/* retrieve children pages, and parent breadcrumbs */
/* FIXME: Bit of a hack at the moment. Needs a better memory of where teh user is */
/* FIXME: Probably need to break into seperate functions. Works for the time being. */
function cc_list_pages($pageid, $sep = "&raquo;", $before = "<h3>", $after = "</h3>") {
	global $wpdb;
	
	$pages = $wpdb->get_results ("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='page' AND post_parent=0 OR post_parent=2");
	foreach ($pages as $page) {
//		if (($page->ID == $pageid)) {
//			echo $before .  $page->post_title . $after;
//		} else {
			echo $before . '<a href="' . get_page_link($page->ID) . '">' . $page->post_title . '</a>' . $after;
//		}
	} 
}

function cc_page_parent ($page) {
  global $wpdb;
  $path = "";
    
  $parent = $wpdb->get_row ("SELECT * FROM $wpdb->posts WHERE id = $page->post_parent");
  
  if ($parent)
    return $parent;
  else
    return null;
}

/* Equivalent to WP's the_excerpt()
 * This version builds out excerpt sans html and entities, safe for use in meta tags.
 */
function cc_post_excerpt() {
	global $post;

	$excerpt = htmlentities(strip_tags($post->post_content));
	$excerpt_a = array_slice (explode(" ", $excerpt), 0, 55);
	echo implode(" ", $excerpt_a) . "...";
}

function cc_get_attachment($id) {
  return get_children('post_parent='.$id.'&post_type=attachment');
}

// Return the first image attachment for post $id, with width of $width
// Used for homepage sticky splash (630px), and featured commoners (150px)
function cc_get_attachment_image($id, $width) {
  if ($attachments = cc_get_attachment($id)) {
    foreach ($attachments as $attachment => $attachment_id) {
      $image = wp_get_attachment_image_src($attachment, full);
      
      // Check if the image is the requested width, and break if it is.
      if ($image[1] == $width) { break; }
    }
  }
  return $image;
}
?>
