<?php
/*
Plugin Name: Widon't
Plugin URI: http://www.shauninman.com/post/heap/2006/12/05/widont_2_wordpress_plugin
Description: Eliminates widows in your post titles (and now <a href="?page=si-widont.php">posts</a>!) by inserting a non-breaking space between the last two words of a title. What is a widow? In typesetting, a widow is a single word on a line by itself at the end of a paragraph and is considered bad style.
Version: 2.0
Author: Shaun Inman
Author URI: http://www.shauninman.com/
*/

add_filter('the_title', 	'widont');
add_filter('the_content',	'widont_filter');
add_action('admin_menu',	'widont_preferences_menu');

function widont($str = '')
{
	$str = rtrim($str);
	$space = strrpos($str, ' ');
	if ($space !== false)
	{
		$str = substr($str, 0, $space).'&nbsp;'.substr($str, $space + 1);
	}
	return $str;
}

function widont_filter($str = '')
{	
	$tags = get_option('widont_tags');
	
	if (!empty($tags) && preg_match_all('#<('.$tags.')>(.+)</\1>#', $str, $m))
	{	
		foreach ($m[0] as $match)
		{
			$str = str_replace($match, widont($match), $str);
		}
	}
	return $str;
}

function widont_preferences_menu()
{
	if (function_exists('add_submenu_page'))
	{
        add_submenu_page('plugins.php', __("Widon't Options"), __("Widon't Options"), 1, __FILE__, 'widont_post_preferences');
	}
}

function widont_post_preferences()
{
	add_option('widont_tags', '');
	$tags = str_replace('|', ' ', get_option('widont_tags'));
	
	if ($_POST['stage'] == 'process' && isset($_POST['widont_tags']))
	{
		$tags = trim($_POST['widont_tags']);
        update_option('widont_tags', preg_replace('#[\s,<>]+#', '|', $tags));
    }
	
	?>
	<div class="wrap">
        <h2 id="write-post">Widon't Options</h2>
        <p>With Widon't your post titles are spared unwanted widows. Extend that courtesy to arbitrary tags in your posts by entering tag names below. No need to include angle brackets. Separate multiple tag names with a space or comma.</p>
		<p>Eg. <code>h3 h4 h5</code></p>
        <form method="post" action="">
            <input type="hidden" name="stage" value="process" />
            <textarea rows="10" cols="40" name="widont_tags" id="widont_tags"><?echo $tags ?></textarea>
			<p><input type="submit" value="Update Preferences &raquo;" name="Submit" /></p>
        </form>
    </div>
	<?php
}

?>