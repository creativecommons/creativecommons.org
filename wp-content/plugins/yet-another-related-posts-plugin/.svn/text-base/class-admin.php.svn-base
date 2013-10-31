<?php

class YARPP_Admin {
	public $core;
	public $hook;
	
	function __construct( &$core ) {
		$this->core = &$core;
		
		// if action=flush and the nonce is correct, reset the cache
		if ( isset($_GET['action']) && $_GET['action'] == 'flush' &&
			 check_ajax_referer( 'yarpp_cache_flush', false, false ) !== false ) {
			$this->core->cache->flush();
			wp_redirect( admin_url( '/options-general.php?page=yarpp' ) );
			exit;
		}

		// if action=copy_templates and the nonce is correct, copy templates
		if ( isset($_GET['action']) && $_GET['action'] == 'copy_templates' &&
			 check_ajax_referer( 'yarpp_copy_templates', false, false ) !== false ) {
			$this->copy_templates();
			wp_redirect( admin_url( '/options-general.php?page=yarpp' ) );
			exit;
		}
		
		add_action( 'admin_init', array( $this, 'ajax_register' ) );
		add_action( 'admin_menu', array( $this, 'ui_register' ) );
		add_filter( 'current_screen', array( $this, 'settings_screen' ) );
		add_filter( 'screen_settings', array( $this, 'render_screen_settings' ), 10, 2 );
		// new in 3.3: set default meta boxes to show:
		add_filter( 'default_hidden_meta_boxes', array( $this, 'default_hidden_meta_boxes' ), 10, 2 );
	}
	
	private $templates = null;
	public function get_templates() {
		if ( is_null($this->templates) ) {
			$this->templates = glob(STYLESHEETPATH . '/yarpp-template-*.php');
			// if glob hits an error, it returns false.
			if ( $this->templates === false )
				$this->templates = array();
			// get basenames only
			$this->templates = array_map(array($this, 'get_template_data'), $this->templates);
		}
		return (array) $this->templates;
	}
	
	public function get_template_data( $file ) {
		$headers = array(
			'name' => 'Template Name',
			'description' => 'Description',
			'author' => 'Author',
			'uri' => 'Author URI',
		);
		$data = get_file_data( $file, $headers );
		$data['file'] = $file;
		$data['basename'] = basename($file);
		if ( empty($data['name']) )
			$data['name'] = $data['basename'];
		return $data;
	}
	
	function ajax_register() {
		// Register AJAX services
		if ( defined('DOING_AJAX') && DOING_AJAX ) {
			add_action( 'wp_ajax_yarpp_display_exclude_terms', array( $this, 'ajax_display_exclude_terms' ) );
			add_action( 'wp_ajax_yarpp_display_demo', array( $this, 'ajax_display_demo' ) );
			add_action( 'wp_ajax_yarpp_display', array( $this, 'ajax_display' ) );
			add_action( 'wp_ajax_yarpp_optin_data', array( $this, 'ajax_optin_data' ) );
			add_action( 'wp_ajax_yarpp_optin', array( $this, 'ajax_optin' ) );
			add_action( 'wp_ajax_yarpp_set_display_code', array( $this, 'ajax_set_display_code' ) );
		}
	}
	
	function ui_register() {
		global $wp_version;
		if ( get_option( 'yarpp_activated' ) ) {
			if ( version_compare($wp_version, '3.3b1', '>=') ) {
				delete_option( 'yarpp_activated' );
				add_action( 'admin_enqueue_scripts', array( $this, 'pointer_enqueue' ) );
				add_action( 'admin_print_footer_scripts', array( $this, 'pointer_script' ) );
			}
		} elseif ( !$this->core->get_option('optin') &&
 			current_user_can('manage_options') &&
			!get_user_option( 'yarpp_saw_optin' )
			) {
			add_action( 'admin_notices', array( $this, 'optin_notice' ) );
		}
		
		// setup admin
		$this->hook = add_options_page(__('Related Posts (YARPP)','yarpp'),__('Related Posts (YARPP)','yarpp'), 'manage_options', 'yarpp', array( $this, 'options_page' ) );
		
		// new in 3.0.12: add settings link to the plugins page
		add_filter('plugin_action_links', array( $this, 'settings_link' ), 10, 2);

		// new in 3.0: add meta box		
		add_meta_box( 'yarpp_relatedposts', __( 'Related Posts' , 'yarpp') . ' <span class="postbox-title-action"><a href="' . esc_url( admin_url('options-general.php?page=yarpp') ) . '" class="edit-box open-box">' . __( 'Configure' ) . '</a></span>', array( $this, 'metabox' ), 'post', 'normal' );
		
		// new in 3.3: properly enqueue scripts for admin:
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	// 3.5.4: only load metabox code if we're going to be on the settings page
	function settings_screen( $current_screen ) {
		if ( $current_screen->id != 'settings_page_yarpp' )
			return $current_screen;
		
		// new in 3.3: load options page sections as metaboxes
		require_once('options-meta-boxes.php');		

		// 3.5.5: check that add_help_tab method callable (WP >= 3.3)
		if ( is_callable(array($current_screen, 'add_help_tab')) ) {
			$current_screen->add_help_tab(array(
				'id' => 'faq',
				'title' => __('Frequently Asked Questions', 'yarpp'),
				'callback' => array( &$this, 'help_faq' )
			));	
			$current_screen->add_help_tab(array(
				'id' => 'dev',
				'title' => __('Developing with YARPP', 'yarpp'),
				'callback' => array( &$this, 'help_dev' )
			));
			$current_screen->add_help_tab(array(
				'id' => 'optin',
				'title' => __('Optional Data Collection', 'yarpp'),
				'callback' => array( &$this, 'help_optin' )
			));
		}
		
		return $current_screen;
	}
	
	private $readme = null;
	
	public function help_faq() {
		if ( is_null($this->readme) )
			$this->readme = file_get_contents( YARPP_DIR . '/readme.txt' );
		
		$matches = array();
		if ( preg_match('!== Frequently Asked Questions ==(.*?)^==!sm', $this->readme, $matches) )
			echo $this->markdown( $matches[1] );
		else
			echo '<a href="https://wordpress.org/extend/plugins/yet-another-related-posts-plugin/faq/">' . __(
			'Frequently Asked Questions', 'yarpp') . '</a>';
	}
	
	public function help_dev() {
		if ( is_null($this->readme) )
			$this->readme = file_get_contents( YARPP_DIR . '/readme.txt' );
		
		$matches = array();
		if ( preg_match('!== Developing with YARPP ==(.*?)^==!sm', $this->readme, $matches) )
			echo $this->markdown( $matches[1] );
		else
			echo '<a href="https://wordpress.org/extend/plugins/yet-another-related-posts-plugin/other_notes/">' . __(
			'Developing with YARPP', 'yarpp') . '</a>';
	}

	public function help_optin() {
		// todo: i18n
		echo '<p>' . sprintf( __( "With your permission, YARPP will send information about YARPP's settings, usage, and environment back to a central server at %s.", 'yarpp' ), '<code>yarpp.org</code>') . ' ';
		echo __( "This information will be used to improve YARPP in the future and help decide future development decisions for YARPP.", 'yarpp' ) . ' ';
		echo '<strong>' . __( "Contributing this data will help make YARPP better for you and for other YARPP users.", 'yarpp' ) . '</strong></p>';

		if ( !$this->core->get_option( 'optin' ) ) {
			echo '<p>';
			$this->print_optin_button();
			echo '</p>';
		}
		
		echo '<p>' . __( "If you opt-in, the following information is sent back to YARPP:", 'yarpp' ) . '</p>';
		echo '<div id="optin_data_frame"></div>';
		echo '<p>' . __( "In addition, YARPP also loads an invisible pixel image with your YARPP results to know how often YARPP is being used.", 'yarpp' ) . '</p>';
	}
	
	function print_optin_button() {
		echo '<a id="yarpp-optin-button" class="button">' . __('Send settings and usage data back to YARPP', 'yarpp') . '</a><span class="yarpp-thankyou" style="display:none"><strong>' . __('Thank you!', 'yarpp') . '</strong></span>';
		wp_nonce_field( 'yarpp_optin', 'yarpp_optin-nonce', false );
		echo "<script type='text/javascript'>
			jQuery(function($){
			$('#yarpp-optin-button').click(function() {
				$(this)
					.hide()
					.siblings('.yarpp-thankyou').show('slow');
				$('#yarpp-optin').attr('checked', true);
				$.ajax({type:'POST',
					url: ajaxurl,
					data: {
						action: 'yarpp_optin',
						'_ajax_nonce': $('#yarpp_optin-nonce').val()
					}});			
			});
			});
		</script>\n";
	}

	function optin_notice() {
		$screen = get_current_screen();
		if ( is_null($screen) || $screen->id == 'settings_page_yarpp' )
			return;

		$user = get_current_user_id();
		update_user_option( $user, 'yarpp_saw_optin', true );

		echo '<div class="updated fade"><p>';
		_e( "<strong>Help make YARPP better</strong> by sending information about YARPP's settings and usage statistics.", 'yarpp' );

		echo '</p><p>';
		$this->print_optin_button();
		echo '<a class="button" href="options-general.php?page=yarpp#help-optin">' . __( 'Learn More', 'yarpp' ) . '</a>';
		echo '</p></div>';
	}
	
	// faux-markdown, required for the help text rendering
	protected function markdown( $text ) {
		$replacements = array(
			// strip each line
			'!\s*[\r\n] *!' => "\n",
			
			// headers
			'!^=(.*?)=\s*$!m' => '<h3>\1</h3>',
			
			// bullets
			'!^(\* .*([\r\n]\* .*)*)$!m' => "<ul>\n\\1\n</ul>",
			'!^\* (.*?)$!m' => '<li>\1</li>',
			'!^(\d+\. .*([\r\n]\d+\. .*)*)$!m' => "<ol>\n\\1\n</ol>",
			'!^\d+\. (.*?)$!m' => '<li>\1</li>',
			
			// code block
			'!^(\t.*([\r\n]\t.*)*)$!m' => "<pre>\n\\1\n</pre>",
			
			// wrap p
			'!^([^<\t].*[^>])$!m' => '<p>\1</p>',
			// bold
			'!\*([^*]*?)\*!' => '<strong>\1</strong>',
			// code
			'!`([^`]*?)`!' => '<code>\1</code>',
			// links
			'!\[([^]]+)\]\(([^)]+)\)!' => '<a href="\2" target="_new">\1</a>',
		);
		$text = preg_replace(array_keys($replacements), array_values($replacements), $text);
		
		return $text;
	}
	
	function render_screen_settings( $output, $current_screen ) {
		if ( $current_screen->id != 'settings_page_yarpp' )
			return $output;

		$output .= "<div id='yarpp_extra_screen_settings'><label for='yarpp_display_code'><input type='checkbox' name='yarpp_display_code' id='yarpp_display_code'";
		$output .= checked( $this->core->get_option('display_code'), true, false );
		$output .= " />";
		$output .= __('Show example code output', 'yarpp');
		$output .= '</label></div>';

		return $output;
	}
	
	// since 3.3
	function enqueue() {
		$version = defined('WP_DEBUG') && WP_DEBUG ? time() : YARPP_VERSION;
		$screen = get_current_screen();
		if ( !is_null($screen) && $screen->id == 'settings_page_yarpp' ) {
			wp_enqueue_script( 'postbox' );
			$this->pointer_enqueue();
			wp_enqueue_style( 'yarpp_options', plugins_url( 'options.css', __FILE__ ), array(), $version );
			wp_enqueue_script( 'yarpp_options', plugins_url( 'js/options.js', __FILE__ ), array('jquery'), $version );
		}
		if ( !is_null($screen) && $screen->id == 'post' ) {
			wp_enqueue_script( 'yarpp_metabox', plugins_url( 'js/metabox.js', __FILE__ ), array('jquery'), $version );
		}
	}
	
	// since 3.4 and WP 3.3
	function pointer_enqueue() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
	function pointer_script() {
		$content = '<h3>' . str_replace('<span>', '<span style="font-style:italic; font-weight: inherit;">', __('Thank you for installing <span>Yet Another Related Posts Plugin</span>!', 'yarpp') )  . '</h3>';
		$content .= '<p>' . str_replace('<a>', '<a href="' . esc_url(admin_url('options-general.php?page=yarpp')) .'">', __('Make sure to visit the <a>Related Posts settings page</a> to customize YARPP.', 'yarpp') ). '</p>';
		?>
<script>
jQuery(function () {
	var body = jQuery(document.body),
	menu = jQuery('#menu-settings'),
	collapse = jQuery('#collapse-menu'),
	yarpp = menu.find("a[href='options-general.php?page=yarpp']"),
	options = {
		content: '<?php echo $content; ?>',
		position: {
			edge: 'left',
			align: 'center',
			of: menu.is('.wp-menu-open') && !menu.is('.folded *') ? yarpp : menu
		},
		close: function() {
			menu.unbind('mouseenter mouseleave', yarpp_pointer);
			collapse.unbind('mouseenter mouseleave', yarpp_pointer);
		}};
	
	if ( !yarpp.length )
		return;
	
	body.pointer(options).pointer('open');
	
	if ( menu.is('.folded *') || !menu.is('.wp-menu-open') ) {
		function yarpp_pointer(e) {
			setTimeout(function() {
				if (yarpp.is(':visible'))
					options.position.of = yarpp;
				else
					options.position.of = menu;
				body.pointer( options );
			}, 200);
		}
		menu.bind('mouseenter mouseleave', yarpp_pointer);
		collapse.bind('mouseenter mouseleave', yarpp_pointer);
	}
});
</script>
		<?php
	}
		
	function settings_link($links, $file) {
		$this_plugin = dirname(plugin_basename(__FILE__)) . '/yarpp.php';
		if($file == $this_plugin) {
			$links[] = '<a href="options-general.php?page=yarpp">' . __('Settings') . '</a>';
		}
		return $links;
	}
	
	function options_page() {
		// for proper metabox support:
		require(YARPP_DIR.'/options.php');
	}

	// @since 3.4: don't actually compute results here, but use ajax instead		
	function metabox() {
		?>
		<style>
		#yarpp_relatedposts h3 .postbox-title-action {
			right: 30px;
			top: 5px;
			position: absolute;
			padding: 0;
		}
		#yarpp_relatedposts:hover .edit-box {
			display: inline;
		}
		</style>
		<?php
		if ( !get_the_ID() ) {
			echo "<div><p>".__("Related entries may be displayed once you save your entry",'yarpp').".</p></div>";
		} else {
			wp_nonce_field( 'yarpp_display', 'yarpp_display-nonce', false );
			echo '<div id="yarpp-related-posts"><img src="' . esc_url( admin_url( 'images/wpspin_light.gif' ) ) . '" alt="" /></div>';
		}
	}
	
	// @since 3.3: default metaboxes to show:
	function default_hidden_meta_boxes($hidden, $screen) {
		if ( 'settings_page_yarpp' == $screen->id )
			$hidden = $this->core->default_hidden_metaboxes;
		return $hidden;
	}
	
	// @since 4: UI to copy templates
	function can_copy_templates() {
		$theme_dir = get_stylesheet_directory();
		// If we can't write to the theme, return false
		if ( !is_dir($theme_dir) || !is_writable($theme_dir) )
			return false;
		
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		WP_Filesystem( false, get_stylesheet_directory() );
		global $wp_filesystem;			
		// direct method is the only method that I've tested so far
		return $wp_filesystem->method == 'direct';
	}
	
	function copy_templates() {
		$templates_dir = trailingslashit(trailingslashit(YARPP_DIR) . 'yarpp-templates');
		
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		WP_Filesystem( false, get_stylesheet_directory() );
		global $wp_filesystem;
		if ( $wp_filesystem->method != 'direct' )
			return false;
		
		return copy_dir( $templates_dir, get_stylesheet_directory(), array('.svn') );
	}
	
	/*
	 * AJAX SERVICES
	 */

	function ajax_display_exclude_terms() {
		check_ajax_referer( 'yarpp_display_exclude_terms' );
		
		if ( !isset($_REQUEST['taxonomy']) )
			return;
		
		$taxonomy = (string) $_REQUEST['taxonomy'];
		
		header("HTTP/1.1 200");
		header("Content-Type: text/html; charset=UTF-8");
		
		$exclude_tt_ids = wp_parse_id_list(yarpp_get_option('exclude'));
		$exclude_term_ids = $this->get_term_ids_from_tt_ids( $taxonomy, $exclude_tt_ids );
//		if ( 'category' == $taxonomy )
//			$exclude .= ',' . get_option( 'default_category' );

		$terms = get_terms($taxonomy, array(
			'exclude' => $exclude_term_ids,
			'hide_empty' => false,
			'hierarchical' => false,
			'number' => 100,
			'offset' => $_REQUEST['offset']
		));
		
		if ( !count($terms) ) {
			echo ':('; // no more :(
			exit;
		}
		
		foreach ($terms as $term) {
			echo "<span><input type='checkbox' name='exclude[{$term->term_taxonomy_id}]' id='exclude_{$term->term_taxonomy_id}' value='true' /> <label for='exclude_{$term->term_taxonomy_id}'>" . esc_html($term->name) . "</label></span> ";
		}
		exit;
	}
	
	function get_term_ids_from_tt_ids( $taxonomy, $tt_ids ) {
		global $wpdb;
		$tt_ids = wp_parse_id_list($tt_ids);
		if ( empty($tt_ids) )
			return array();
		return $wpdb->get_col("select term_id from $wpdb->term_taxonomy where taxonomy = '{$taxonomy}' and term_taxonomy_id in (" . join(',', $tt_ids) . ")");
	}
	
	function ajax_display() {
		check_ajax_referer( 'yarpp_display' );

		if ( !isset($_REQUEST['ID']) )
			return;

		header("HTTP/1.1 200");
		header("Content-Type: text/html; charset=UTF-8");

		$args = array(
			'post_type' => array('post'),
			'domain' => isset($_REQUEST['domain']) ? $_REQUEST['domain'] : 'website'
		);
		if ( $this->core->get_option('cross_relate') )
			$args['post_type'] = $this->core->get_post_types();
			
		$return = $this->core->display_related(absint($_REQUEST['ID']), $args, false);
		echo $return;
		exit;
	}

	function ajax_display_demo() {
		check_ajax_referer( 'yarpp_display_demo' );

		header("HTTP/1.1 200");
		header("Content-Type: text/html; charset=UTF-8");
	
		$args = array(
			'post_type' => array('post'),
			'domain' => isset($_REQUEST['domain']) ? $_REQUEST['domain'] : 'website'
		);
			
		$return = $this->core->display_demo_related($args, false);
		echo preg_replace("/[\n\r]/",'',nl2br(htmlspecialchars($return)));
		exit;
	}
	
	function ajax_optin_data() {
		check_ajax_referer( 'yarpp_optin_data' );

		header("HTTP/1.1 200");
		header("Content-Type: text/html; charset=UTF-8");
	
		$data = $this->core->optin_data();
		$this->core->pretty_echo($data);
		exit;
	}

	function ajax_optin() {
		check_ajax_referer( 'yarpp_optin' );

		header("HTTP/1.1 200");
		header("Content-Type: text; charset=UTF-8");
		
		$data = yarpp_set_option('optin', true);
		$this->core->optin_ping();
		echo 'ok';
		exit;
	}

	function ajax_set_display_code() {
		check_ajax_referer( 'yarpp_set_display_code' );

		header("HTTP/1.1 200");
		header("Content-Type: text; charset=UTF-8");
		
		$data = yarpp_set_option( 'display_code', isset($_REQUEST['checked']) );
		echo 'ok';
		exit;
	}
}
