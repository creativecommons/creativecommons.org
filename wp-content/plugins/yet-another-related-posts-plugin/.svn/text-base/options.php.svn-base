<?php
global $wpdb, $wp_version, $yarpp;

// Enforce YARPP setup:
$yarpp->enforce();

// check to see that templates are in the right place
if ( !count($yarpp->admin->get_templates()) ) {
	yarpp_set_option( array( 'template' => false, 'rss_template' => false) );
}

// 3.3: move version checking here, in PHP:
if ( current_user_can('update_plugins' ) ) {
	$yarpp_version_info = $yarpp->version_info();
	
	// these strings are not localizable, as long as the plugin data on wordpress.org
	// cannot be.
	$slug = 'yet-another-related-posts-plugin';
	$plugin_name = 'Yet Another Related Posts Plugin';
	$file = basename(YARPP_DIR) . '/yarpp.php';
	if ( $yarpp_version_info['result'] == 'new' ) {
		// make sure the update system is aware of this version
		$current = get_site_transient( 'update_plugins' );
		if ( !isset( $current->response[ $file ] ) ) {
			delete_site_transient( 'update_plugins' );
			wp_update_plugins();
		}
	
		echo '<div class="updated"><p>';
		$details_url = self_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $slug . '&TB_iframe=true&width=600&height=800');
		printf( __('There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%3$s">View version %4$s details</a> or <a href="%5$s">update automatically</a>.', 'yarpp'), $plugin_name, esc_url($details_url), esc_attr($plugin_name), $yarpp_version_info['current']['version'], wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file) );
		echo '</p></div>';
	} else if ( $yarpp_version_info['result'] == 'newbeta' ) {
		echo '<div class="updated"><p>';
		printf(__("There is a new beta (%s) of Yet Another Related Posts Plugin. You can <a href=\"%s\">download it here</a> at your own risk.","yarpp"), $yarpp_version_info['beta']['version'], $yarpp_version_info['beta']['url']);
		echo '</p></div>';
	}
}

if (isset($_POST['myisam_override'])) {
	yarpp_set_option('myisam_override',1);
	echo "<div class='updated'>"
	.__("The MyISAM check has been overridden. You may now use the \"consider titles\" and \"consider bodies\" relatedness criteria.",'yarpp')
	."</div>";
}

if ( !yarpp_get_option('myisam_override') ) {
	$yarpp_check_return = $yarpp->diagnostic_myisam_posts();
	if ($yarpp_check_return !== true) { // if it's not *exactly* true
		echo "<div class='updated'>"
		.sprintf(__("YARPP's \"consider titles\" and \"consider bodies\" relatedness criteria require your <code>%s</code> table to use the <a href='http://dev.mysql.com/doc/refman/5.0/en/storage-engines.html'>MyISAM storage engine</a>, but the table seems to be using the <code>%s</code> engine. These two options have been disabled.",'yarpp'), $wpdb->posts, $yarpp_check_return)
		."<br />"
		.sprintf(__("To restore these features, please update your <code>%s</code> table by executing the following SQL directive: <code>ALTER TABLE `%s` ENGINE = MyISAM;</code> . No data will be erased by altering the table's engine, although there are performance implications.",'yarpp'), $wpdb->posts, $wpdb->posts)
		."<br />"
		.sprintf(__("If, despite this check, you are sure that <code>%s</code> is using the MyISAM engine, press this magic button:",'yarpp'), $wpdb->posts)
		."<br />"
		."<form method='post'><input type='submit' class='button' name='myisam_override' value='"
		.__("Trust me. Let me use MyISAM features.",'yarpp')
		."'></input></form>"
		."</div>";

		$weight = yarpp_get_option('weight');
		unset($weight['title']);
		unset($weight['body']);
		yarpp_set_option(array('weight' => $weight));
		$yarpp->myisam = false;
	}
}

if ( $yarpp->myisam && !$yarpp->enabled() ) {
	echo '<div class="updated">';
	if ( $yarpp->activate() ) {
		_e('The YARPP database had an error but has been fixed.','yarpp');
	} else {
		_e('The YARPP database has an error which could not be fixed.','yarpp');
		printf(__('Please try <a href="%s" target="_blank">manual SQL setup</a>.','yarpp'), 'http://mitcho.com/code/yarpp/sql.php?prefix='.urlencode($wpdb->prefix));
	}
	echo '</div>';
}

if (isset($_POST['update_yarpp'])) {
	$new_options = array();
	foreach ($yarpp->default_options as $option => $default) {
		if ( is_bool($default) )
			$new_options[$option] = isset($_POST[$option]);
		// @todo: do we really want to stripslashes here anymore?
		if ( (is_string($default) || is_int($default)) &&
			 isset($_POST[$option]) && is_string($_POST[$option]) )
			$new_options[$option] = stripslashes($_POST[$option]);
	}

	if ( isset($_POST['weight']) ) {
		$new_options['weight'] = array();
		$new_options['require_tax'] = array();
		foreach ( (array) $_POST['weight'] as $key => $value) {
			if ( $value == 'consider' )
				$new_options['weight'][$key] = 1;
			if ( $value == 'consider_extra' )
				$new_options['weight'][$key] = YARPP_EXTRA_WEIGHT;
		}
		foreach ( (array) $_POST['weight']['tax'] as $tax => $value) {
			if ( $value == 'consider' )
				$new_options['weight']['tax'][$tax] = 1;
			if ( $value == 'consider_extra' )
				$new_options['weight']['tax'][$tax] = YARPP_EXTRA_WEIGHT;
			if ( $value == 'require_one' ) {
				$new_options['weight']['tax'][$tax] = 1;
				$new_options['require_tax'][$tax] = 1;
			}
			if ( $value == 'require_more' ) {
				$new_options['weight']['tax'][$tax] = 1;
				$new_options['require_tax'][$tax] = 2;
			}
		}
	}
	
	if ( isset( $_POST['auto_display_post_types'] ) ) {
		$new_options['auto_display_post_types'] = array_keys( $_POST['auto_display_post_types'] );
	} else {
		$new_options['auto_display_post_types'] = array();
	}

	$new_options['recent'] = isset($_POST['recent_only']) ?
		$_POST['recent_number'] . ' ' . $_POST['recent_units'] : false;

	if ( isset($_POST['exclude']) )
		$new_options['exclude'] = implode(',',array_keys($_POST['exclude']));
	else
		$new_options['exclude'] = '';
	
	$new_options['template'] = $_POST['use_template'] == 'custom' ? $_POST['template_file'] : 
		( $_POST['use_template'] == 'thumbnails' ? 'thumbnails' : false );
	$new_options['rss_template'] = $_POST['rss_use_template'] == 'custom' ? $_POST['rss_template_file'] : 
		( $_POST['rss_use_template'] == 'thumbnails' ? 'thumbnails' : false );
	
	$new_options = apply_filters( 'yarpp_settings_save', $new_options );
	yarpp_set_option($new_options);

	echo '<div class="updated fade"><p>'.__('Options saved!','yarpp').'</p></div>';
}

?>
<div class="wrap">
		<h2>
			<?php _e('Yet Another Related Posts Plugin Options','yarpp');?> <small><?php
				echo apply_filters( 'yarpp_version_html', esc_html( get_option('yarpp_version') ) );
			?></small>
		</h2>

	<form method="post">

	<div id="yarpp_author_text">
	<small><?php printf(__('by <a href="%s" target="_blank">mitcho (Michael 芳貴 Erlewine)</a>','yarpp'), 'http://mitcho.com/');?></small>
	</div>

<?php
wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
wp_nonce_field( 'yarpp_display_demo', 'yarpp_display_demo-nonce', false );
wp_nonce_field( 'yarpp_display_exclude_terms', 'yarpp_display_exclude_terms-nonce', false );
wp_nonce_field( 'yarpp_optin_data', 'yarpp_optin_data-nonce', false );
wp_nonce_field( 'yarpp_set_display_code', 'yarpp_set_display_code-nonce', false );
if ( !count($yarpp->admin->get_templates()) && $yarpp->admin->can_copy_templates() )
	wp_nonce_field( 'yarpp_copy_templates', 'yarpp_copy_templates-nonce', false );
?>
<div id="poststuff" class="metabox-holder has-right-sidebar">

<?php if ( !$yarpp->get_option('rss_display') ): ?>
<style>
.rss_displayed {
	display: none;
}
</style>
<?php endif; ?>

<div class="inner-sidebar" id="side-info-column">
<?php
do_meta_boxes( 'settings_page_yarpp', 'side', array() );
?>
</div>

<div id="post-body-content">
<?php
do_meta_boxes( 'settings_page_yarpp', 'normal', array() );
?>
</div>

<script language="javascript">
var spinner = '<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>',
	loading = '<img class="loading" src="'+spinner+'" alt="loading..."/>';
</script>

<div>
	<input type="submit" class='button-primary' name="update_yarpp" value="<?php _e( 'Save Changes' )?>" />
</div>

</div><!--#poststuff-->

</form>

</div><!--.wrap-->