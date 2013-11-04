<?php
/* Helper functions for CC3 theme */
/* Alex Roberts, 2006 */

function cc_progress_total() {
  $campaign_total = file_get_contents(__DIR__ . '../../includes/total.txt');
  
  print $campaign_total;
}

function cc_monetize($money, $delim = ",") {

  $chunks = str_split(strrev(sprintf("%.0f", $money)), 3);
  
  $ized = "";
  foreach($chunks as $chunk) {
    $ized = $ized . $chunk . (((strlen($chunk) > 2) && ($chunk != $chunks[count($chunks) -1])) ? $delim : "");
  }
  
  return strrev($ized);
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

/* retrieve url of uploaded resource from its title */
function cc_get_attachment ($id) {
	global $wpdb;
 	return $wpdb->get_row("SELECT guid AS uri, post_content AS descr FROM $wpdb->posts WHERE post_parent=$id AND post_status='inherit' ORDER BY guid DESC LIMIT 1;");
}

function cc_get_attachment_desc ($title) {
	global $wpdb;
	echo $wpdb->get_var("SELECT post_content FROM $wpdb->posts WHERE post_title='$title';");
}



/* check if post has a similarly named attachment */
function cc_has_attachment ($title) {
	global $wpdb; 
	
	if ($wpdb->get_var("SELECT id FROM $wpdb->posts WHERE post_title='$title';")){
		return true;
	}
	return false;
}

function cc_footer_links() {
	global $wpdb;
	
	$links = $wpdb->select("SELECT link_url, link_name, link_description FROM $wpdb->links WHERE category_id = 7 ORDER BY id;");
	
}


function cc_intro_blurb() {
  return stripslashes (get_option ('cc_intro_blurb'));
}

function cc_current_feature() {
  return stripslashes (get_option ('cc_current_feature'));
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

// Removes over-zealously placed <br/>'s and </p>'s from commented out html, and RDF blocks
// Also removes <br/>'s from the end of </li>'s
function cc_post_content_process($content) {
  $wrong_br = array("/(<\!--.*)?<(br\/|\/p)>/", "/(<\!--.*)?<p>\&\#8211;>/", "/<\/li><br\s+\/>/", "/<label><br\s+\/>/");
  $fixed = array("$1", "$1 -->", "</li>", "<label>");
  
  $content = preg_replace($wrong_br, $fixed, $content);

  return $content;
}

//add_filter('the_content', 'cc_post_content_process');


/* theme options page */
add_action ('admin_menu', 'cc_theme_menu');

function cc_theme_menu() {
  add_theme_page('Customize CC', 'Customize CC', 5, basename(__FILE__), 'cc_theme_options');
}

function cc_theme_options() {
  
  if ($_POST['cc_blurb']) {
    update_option ('cc_intro_blurb', $_POST['cc_blurb']);
    $message = "Intro blurb updated!";
  }
  if ($_POST['cc_feature']) {
    update_option ('cc_current_feature', $_POST['cc_feature']);
    $message = "Current feature updated!";
  }
  
  // display feedback that something happened
  if ($message) {
    ?>
    <div class="wrap"><?= $message ?></div>
    <?php    
  }
  ?>
  
  <div class="wrap">
   <h2>Home Page Intro Blurb</h2>
   <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" name="blurb" method="post" accept-charset="utf-8">
    <textarea name="cc_blurb" rows="8" cols="60"><?= cc_intro_blurb() ?></textarea>

    <p><input type="submit" value="Update &rarr;" /></p>
   </form>
   
   <h2>Current Feature</h2>
   <p><small>Item at top of index, above Blog.</small></p>
   <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" name="feature" method="post" accept-charset="utf-8">
     <textarea name="cc_feature" rows="8" cols="60"><?= cc_current_feature() ?></textarea>

     <p><input type="submit" value="Update &rarr;" /></p>
    </form>
  </div>
  <?php
}
/*
// set up theme defaults
if (!get_option('cc_intro_blurb')) {
  add_option ('cc_intro_blurb', "Creative Commons", "Informational introduction text at head of the home page.");
}
if (!get_option('cc_current_feature')) {
  add_option ('cc_current_feature', "Creative Commons", "Current featured CC project, above blog.");
}
*/
?>
