<?php

require_once(YARPP_DIR.'/magic.php');
require_once(YARPP_DIR.'/keywords.php');
require_once(YARPP_DIR.'/intl.php');
require_once(YARPP_DIR.'/services.php');

if ( !defined('WP_CONTENT_URL') )
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

global $yarpp_value_options, $yarpp_binary_options, $yarpp_clear_cache_options;
// here's a list of all the options YARPP uses (except version), as well as their default values, sans the yarpp_ prefix, split up into binary options and value options. These arrays are used in updating settings (options.php) and other tasks.
$yarpp_value_options = array(
	'threshold' => 5,
	'limit' => 5,
	'template_file' => '', // new in 2.2
	'excerpt_length' => 10,
	'recent_number' => 12,
	'recent_units' => 'month',
	'before_title' => '<li>',
	'after_title' => '</li>',
	'before_post' => ' <small>',
	'after_post' => '</small>',
	'before_related' => '<p>'.__('Related posts:','yarpp').'</p><ol>',
	'after_related' => '</ol>',
	'no_results' => '<p>'.__('No related posts.','yarpp').'</p>',
	'order' => 'score DESC',
	'rss_limit' => 3,
	'rss_template_file' => '', // new in 2.2
	'rss_excerpt_length' => 10,
	'rss_before_title' => '<li>',
	'rss_after_title' => '</li>',
	'rss_before_post' => ' <small>',
	'rss_after_post' => '</small>',
	'rss_before_related' => '<p>'.__('Related posts:','yarpp').'</p><ol>',
	'rss_after_related' => '</ol>',
	'rss_no_results' => '<p>'.__('No related posts.','yarpp').'</p>',
	'rss_order' => 'score DESC',
	'title' => '2',
	'body' => '2',
	'categories' => '1', // changed default in 3.3
	'tags' => '2',
	'distags' => '',
	'discats' => '');
$yarpp_binary_options = array(
	'past_only' => true,
	'show_excerpt' => false,
	'recent_only' => false, // new in 3.0
	'use_template' => false, // new in 2.2
	'rss_show_excerpt' => false,
	'rss_use_template' => false, // new in 2.2
	'show_pass_post' => false,
	'cross_relate' => false,
	'auto_display' => true,
	'rss_display' => false, // changed default in 3.1.7
	'rss_excerpt_display' => true,
	'promote_yarpp' => false,
	'rss_promote_yarpp' => false);
// These are options which, when updated, will trigger a clearing of the cache
$yarpp_clear_cache_options = array(
	'distags','discats','show_pass_post','recent_only','threshold','title','body','categories',
	'tags');

function yarpp_enabled() {
	global $wpdb, $yarpp_cache;
	if ($yarpp_cache->is_enabled() === false)
		return false;
	$indexdata = $wpdb->get_results("show index from $wpdb->posts");
	foreach ($indexdata as $index) {
		if ($index->Key_name == 'yarpp_title')
			return true;
	}
	return false;
}

function yarpp_activate() {
	global $yarpp_version, $wpdb, $yarpp_binary_options, $yarpp_value_options, $yarpp_cache;
	foreach (array_keys($yarpp_value_options) as $option) {
		if (get_option("yarpp_$option") === false)
			add_option("yarpp_$option",$yarpp_value_options[$option] . ' ');
	}
	foreach (array_keys($yarpp_binary_options) as $option) {
		if (get_option("yarpp_$option") === false)
			add_option("yarpp_$option",$yarpp_binary_options[$option]);
	}

	$wpdb->get_results("show index from $wpdb->posts where Key_name='yarpp_title'");
	if (!$wpdb->num_rows)
		$wpdb->query("ALTER TABLE $wpdb->posts ADD FULLTEXT `yarpp_title` ( `post_title` )");

	$wpdb->get_results("show index from $wpdb->posts where Key_name='yarpp_content'");
	if (!$wpdb->num_rows)
		$wpdb->query("ALTER TABLE $wpdb->posts ADD FULLTEXT `yarpp_content` ( `post_content` )");
	
	if (!yarpp_enabled()) {
		// If we are still not enabled, run the cache abstraction's setup method.
		$yarpp_cache->setup();
		// If we're still not enabled, give up.
		if (!yarpp_enabled())
			return 0;
	}
	
	if (!get_option('yarpp_version')) {
		add_option('yarpp_version',YARPP_VERSION);	
		yarpp_version_info(true);
	} else {
		yarpp_upgrade_check();
	}

	return 1;
}

function yarpp_myisam_check() {
	global $wpdb;
	$tables = $wpdb->get_results("show table status like '{$wpdb->posts}'");
	foreach ($tables as $table) {
		if ($table->Engine == 'MyISAM') return true;
		else return $table->Engine;
	}
	return 'UNKNOWN';
}

function yarpp_upgrade_check() {
	$last_version = get_option('yarpp_version');
	if (version_compare(YARPP_VERSION, $last_version) === 0)
		return;

	global $yarpp_value_options, $yarpp_binary_options, $yarpp_cache;

	foreach (array_keys($yarpp_value_options) as $option) {
		if (get_option("yarpp_$option") === false)
			add_option("yarpp_$option",$yarpp_value_options[$option].' ');
	}
	foreach (array_keys($yarpp_binary_options) as $option) {
		if (get_option("yarpp_$option") === false)
			add_option("yarpp_$option",$yarpp_binary_options[$option]);
	}

	$yarpp_cache->upgrade($last_version);

	yarpp_version_info(true);

	update_option('yarpp_version',YARPP_VERSION);
}

function yarpp_admin_menu() {
	$hook = add_options_page(__('Related Posts (YARPP)','yarpp'),__('Related Posts (YARPP)','yarpp'), 'manage_options', 'yarpp', 'yarpp_options_page');
	add_action("load-$hook",'yarpp_load_thickbox');
	// new in 3.3: load options page sections as metaboxes
	include('options-meta-boxes.php');
	// new in 3.0.12: add settings link to the plugins page
	add_filter('plugin_action_links', 'yarpp_settings_link', 10, 2);
}

// since 3.3
function yarpp_admin_enqueue() {
	global $current_screen;
	if (is_object($current_screen) && $current_screen->id == 'settings_page_yarpp') {
		wp_enqueue_script( 'postbox' );
		wp_enqueue_style( 'yarpp_options', plugins_url( 'options.css', __FILE__ ), array(), YARPP_VERSION );
	}
}

function yarpp_settings_link($links, $file) {
	$this_plugin = dirname(plugin_basename(__FILE__)) . '/yarpp.php';
	if($file == $this_plugin) {
		$links[] = '<a href="options-general.php?page=yarpp">' . __('Settings', 'yarpp') . '</a>';
	}
	return $links;
}

function yarpp_load_thickbox() {
	wp_enqueue_script( 'thickbox' );
	if (function_exists('wp_enqueue_style')) {
		wp_enqueue_style( 'thickbox' );
	}
}

function yarpp_options_page() {
	// for proper metabox support:
	require(YARPP_DIR.'/options.php');
}

function widget_yarpp_init() {
	register_widget('YARPP_Widget');
}

// vaguely based on code by MK Safi
// http://msafi.com/fix-yet-another-related-posts-plugin-yarpp-widget-and-add-it-to-the-sidebar/
class YARPP_Widget extends WP_Widget {
	function YARPP_Widget() {
		parent::WP_Widget(false, $name = __('Related Posts (YARPP)','yarpp'));
	}

	function widget($args, $instance) {
		global $post;
		if (!is_singular())
			return;

		extract($args);

		$type = ($post->post_type == 'page' ? array('page') : array('post'));
		if (yarpp_get_option('cross_relate'))
			$type = array('post','page');

		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ( !$instance['use_template'] ) {
			echo $before_title;
			if ($title)
				echo $title;
			else
				_e('Related Posts (YARPP)','yarpp');
			echo $after_title;
		}
		echo yarpp_related($type,$instance,false,false,'widget');
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		// this starts with default values.
		$instance = array( 'promote_yarpp' => 0, 'use_template' => 0 );
		foreach ( $instance as $field => $val ) {
			if ( isset($new_instance[$field]) )
				$instance[$field] = 1;
		}
		if ($instance['use_template']) {
			$instance['template_file'] = $new_instance['template_file'];
			$instance['title'] = $old_instance['title'];
		} else {
			$instance['template_file'] = $old_instance['template_file'];
			$instance['title'] = $new_instance['title'];
		}
		return $instance;
	}

	function form($instance) {
		$title = esc_attr($instance['title']);
		$template_file = $instance['template_file'];
		?>
				<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

			<?php // if there are YARPP templates installed...
			
				$templates = glob(STYLESHEETPATH . '/yarpp-template-*.php');
				if ( is_array(templates) && count($templates) ): ?>

				<p><input class="checkbox" id="<?php echo $this->get_field_id('use_template'); ?>" name="<?php echo $this->get_field_name('use_template'); ?>" type="checkbox" <?php checked($instance['use_template'], true) ?> /> <label for="<?php echo $this->get_field_id('use_template'); ?>"><?php _e("Display using a custom template file",'yarpp');?></label></p>
				<p id="<?php echo $this->get_field_id('template_file_p'); ?>"><label for="<?php echo $this->get_field_id('template_file'); ?>"><?php _e("Template file:",'yarpp');?></label> <select name="<?php echo $this->get_field_name('template_file'); ?>" id="<?php echo $this->get_field_id('template_file'); ?>">
					<?php foreach ($templates as $template): ?>
					<option value='<?php echo htmlspecialchars(basename($template))?>'<?php echo (basename($template)==$template_file)?" selected='selected'":'';?>><?php echo htmlspecialchars(basename($template))?></option>
					<?php endforeach; ?>
				</select><p>

			<?php endif; ?>

				<p><input class="checkbox" id="<?php echo $this->get_field_id('promote_yarpp'); ?>" name="<?php echo $this->get_field_name('promote_yarpp'); ?>" type="checkbox" <?php checked($instance['images'], true) ?> /> <label for="<?php echo $this->get_field_id('promote_yarpp'); ?>"><?php _e("Help promote Yet Another Related Posts Plugin?",'yarpp'); ?></label></p>

				<script type="text/javascript">
				jQuery(function() {
					function ensureTemplateChoice() {
						if (jQuery('#<?php echo $this->get_field_id('use_template'); ?>').attr('checked')) {
							jQuery('#<?php echo $this->get_field_id('title'); ?>').attr('disabled',true);
							jQuery('#<?php echo $this->get_field_id('template_file_p'); ?>').show();
						} else {
							jQuery('#<?php echo $this->get_field_id('title'); ?>').attr('disabled',false);
							jQuery('#<?php echo $this->get_field_id('template_file_p'); ?>').hide();
						}
					}
					jQuery('#<?php echo $this->get_field_id('use_template'); ?>').change(ensureTemplateChoice);
					ensureTemplateChoice();
				});
				</script>

		<?php
	}
}


function yarpp_default($content) {
	global $wpdb, $post;

	if (is_feed())
		return yarpp_rss($content);

	$type = ($post->post_type == 'page' ? array('page') : array('post'));
	if (yarpp_get_option('cross_relate'))
		$type = array('post','page');

	if (yarpp_get_option('auto_display') and is_single())
		return $content . yarpp_related($type,array(),false,false,'website');
	else
		return $content;
}

function yarpp_rss($content) {
	global $wpdb, $post;

	$type = ($post->post_type == 'page' ? array('page') : array('post'));
	if (yarpp_get_option('cross_relate'))
		$type = array('post','page');

	if (yarpp_get_option('rss_display'))
		return $content.yarpp_related($type,array(),false,false,'rss');
	else
		return $content;
}

function yarpp_rss_excerpt($content) {
	global $wpdb, $post;

	$type = ($post->post_type == 'page' ? array('page') : array('post'));
	if (yarpp_get_option('cross_relate'))
		$type = array('post','page');

	if (yarpp_get_option('rss_excerpt_display') && yarpp_get_option('rss_display'))
		return $content.clean_pre(yarpp_related($type,array(),false,false,'rss'));
	else
		return $content;
}

// Used only in demo mode
if (!defined('LOREMIPSUM'))
	define('LOREMIPSUM','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Cras tincidunt justo a urna. Ut turpis. Phasellus convallis, odio sit amet cursus convallis, eros orci scelerisque velit, ut sodales neque nisl at ante. Suspendisse metus. Curabitur auctor pede quis mi. Pellentesque lorem justo, condimentum ac, dapibus sit amet, ornare et, erat. Quisque velit. Etiam sodales dui feugiat neque suscipit bibendum. Integer mattis. Nullam et ante non sem commodo malesuada. Pellentesque ultrices fermentum lectus. Maecenas hendrerit neque ac est. Fusce tortor mi, tristique sed, cursus at, pellentesque non, dui. Suspendisse potenti.');

function yarpp_excerpt($content,$length) {
	$content = strip_tags( (string) $content );
	preg_replace('/([,;.-]+)\s*/','\1 ',$content);
	return implode(' ',array_slice(preg_split('/\s+/',$content),0,$length)).'...';
}

function yarpp_set_option($option,$value) {
	global $yarpp_value_options, $yarpp_clear_cache_options, $yarpp_cache;
	if (array_search($option,array_keys($yarpp_value_options)) !== false)
		update_option("yarpp_$option",$value.' ');
	else
		update_option("yarpp_$option",$value);
	// new in 3.1: clear cache when updating certain settings.
	if (array_search($option,$yarpp_clear_cache_options) !== false)
		$yarpp_cache->flush();
}

function yarpp_get_option($option,$escapehtml = false) {
	global $yarpp_value_options;
	if (array_search($option,array_keys($yarpp_value_options)) !== false)
		$return = chop(get_option("yarpp_$option"));
	else
		$return = get_option("yarpp_$option");
	if ($escapehtml)
		$return = htmlspecialchars(stripslashes($return));
	return $return;
}

function yarpp_microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
}

// new in 3.3: use PHP serialized format instead of JSON
function yarpp_version_info($enforce_cache = false) {
	if (false === ($result = get_transient('yarpp_version_info')) || $enforce_cache) {
		$version = YARPP_VERSION;
		$remote = wp_remote_post("http://mitcho.com/code/yarpp/checkversion.php?format=php&version={$version}");
		
		if (is_wp_error($remote))
			return false;
		
		$result = unserialize($remote['body']);
		set_transient('yarpp_version_info', $result, 60*60*12);
	}
	return $result;
}

function yarpp_add_metabox() {
	if (function_exists('add_meta_box')) {
		add_meta_box( 'yarpp_relatedposts', __( 'Related Posts' , 'yarpp'), 'yarpp_metabox', 'post', 'normal' );
	}
}
function yarpp_metabox() {
	global $post;
	echo '<div id="yarpp-related-posts">';
	if ($post->ID)
		yarpp_related(array('post'),array('limit'=>1000),true,false,'metabox');
	else
		echo "<p>".__("Related entries may be displayed once you save your entry",'yarpp').".</p>";
	echo '</div>';
}

// since 3.3: default metaboxes to show:
function yarpp_default_hidden_meta_boxes($hidden, $screen) {
	if ( 'settings_page_yarpp' == $screen->id )
		$hidden = array( 'yarpp_pool', 'yarpp_relatedness' );
	return $hidden;
}

// since 3.3.2: fix for WP 3.0.x
if ( !function_exists( 'self_admin_url' ) ) {
	function self_admin_url($path = '', $scheme = 'admin') {
		if ( defined( 'WP_NETWORK_ADMIN' ) && WP_NETWORK_ADMIN )
			return network_admin_url($path, $scheme);
		elseif ( defined( 'WP_USER_ADMIN' ) && WP_USER_ADMIN )
			return user_admin_url($path, $scheme);
		else
			return admin_url($path, $scheme);
	}
}
