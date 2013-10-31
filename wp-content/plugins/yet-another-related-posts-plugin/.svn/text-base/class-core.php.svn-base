<?php

// new in 3.4: put everything YARPP into an object, expected to be a singleton global $yarpp
class YARPP {

	public $debug = false;
	
	public $cache;
	public $cache_bypass;
	private $active_cache;
	
	public $admin;
	private $storage_class;
	
	public $myisam = true;
	
	// here's a list of all the options YARPP uses (except version), as well as their default values, sans the yarpp_ prefix, split up into binary options and value options. These arrays are used in updating settings (options.php) and other tasks.
	public $default_options = array();
	public $default_hidden_metaboxes = array( 'yarpp_pool', 'yarpp_relatedness' );

	function __construct() {
		$this->load_default_options();

		// register text domain
		load_plugin_textdomain( 'yarpp', false, dirname(plugin_basename(__FILE__)) . '/lang' );

		// load cache object
		require_once(YARPP_DIR . '/class-cache.php');
		require_once(YARPP_DIR . '/cache-' . YARPP_CACHE_TYPE . '.php');
		$this->storage_class = $yarpp_storage_class;
		$this->cache = new $this->storage_class( $this );
		$this->cache_bypass = new YARPP_Cache_Bypass( $this );

		register_activation_hook( __FILE__, array($this, 'activate') );
		
		// new in 3.2: update cache on delete
		add_action( 'delete_post', array($this->cache, 'delete_post'), 10, 1 );
		// new in 3.2.1: handle post_status transitions
		// new in 3.5.3: use transition_post_status instead of save_post hook
		add_action( 'transition_post_status', array($this->cache, 'transition_post_status'), 10, 3);

		// automatic display hooks:
		add_filter( 'the_content', array( $this, 'the_content' ), 1200 );
		add_filter( 'the_content_feed', array( $this, 'the_content_feed' ), 600 );
		add_filter( 'the_excerpt_rss', array( $this, 'the_excerpt_rss' ), 600 );

		// register yarpp-thumbnail size, if theme has not already
		// @todo: make these UI-configurable?
		if ( false === $this->thumbnail_size() ) {
			$width = 120;
			$height = 120;
			$crop = true;
			add_image_size( 'yarpp-thumbnail', $width, $height, $crop );
		}

		if ( isset($_REQUEST['yarpp_debug']) )
			$this->debug = true;

		// new in 3.4: only load UI if we're in the admin
		if ( is_admin() ) {
			require_once(YARPP_DIR . '/class-admin.php');
			$this->admin = new YARPP_Admin( $this );
		}
	}
		
	/*
	 * OPTIONS
	 */
	
	private function load_default_options() {
		$this->default_options = array(
			'threshold' => 4, // changed default in 4
			'limit' => 4, // changed default in 4
			'excerpt_length' => 10,
			'recent' => false, // new in 3.5
			'before_title' => '<li>',
			'after_title' => '</li>',
			'before_post' => ' <small>',
			'after_post' => '</small>',
			'before_related' => '<h3>'.__('Related posts:','yarpp').'</h3><ol>',
			'after_related' => '</ol>',
			'no_results' => '<p>'.__('No related posts.','yarpp').'</p>',
			'order' => 'score DESC',
			'rss_limit' => 3,
			'rss_excerpt_length' => 10,
			'rss_before_title' => '<li>',
			'rss_after_title' => '</li>',
			'rss_before_post' => ' <small>',
			'rss_after_post' => '</small>',
			'rss_before_related' => '<h3>'.__('Related posts:','yarpp').'</h3><ol>',
			'rss_after_related' => '</ol>',
			'rss_no_results' => '<p>'.__('No related posts.','yarpp').'</p>',
			'rss_order' => 'score DESC',
			'past_only' => false,
			'show_excerpt' => false,
			'rss_show_excerpt' => false,
			'template' => false, // new in 3.5
			'rss_template' => false, // new in 3.5
			'show_pass_post' => false,
			'cross_relate' => false,
			'rss_display' => false, // changed default in 3.1.7
			'rss_excerpt_display' => true,
			'promote_yarpp' => false,
			'rss_promote_yarpp' => false,
			'myisam_override' => false,
			'exclude' => '', // conslidated YARPP 3.4 and further in 3.5
			'weight' => array( // consolidated in YARPP 3.4, format changed in 3.5
				'title' => 1,
				'body' => 1,
				'tax' => array(
					'category' => 1, // changed default in 3.4
					'post_tag' => 1
				)
			),
			'require_tax' => array(), // new in 3.5
			'optin' => false, // new in 4
			'thumbnails_heading' => __('Related posts:','yarpp'), // new in 4
			'thumbnails_default' => plugins_url( 'default.png', __FILE__ ), // new in 4
			'rss_thumbnails_heading' => __('Related posts:','yarpp'), // new in 4
			'rss_thumbnails_default' => plugins_url( 'default.png', __FILE__ ), // new in 4
			'display_code' => false, // new in 4
			'auto_display_archive' => false, // new in 4
			'auto_display_post_types' => array( 'post' ), // new in 4, replacing auto_display
			'pools' => array(), // new in 4
		);
	}
	
	function set_option( $options, $value = null ) {
		$current_options = $this->get_option();
	
		// we can call yarpp_set_option(key,value) if we like:
		if ( !is_array($options) ) {
			if ( isset($value) )
				$options = array( $options => $value );
			else
				return false;
		}
	
		$new_options = array_merge( $current_options, $options );
		update_option( 'yarpp', $new_options );
	
		// new in 3.1: clear cache when updating certain settings.
		$clear_cache_options = array( 'show_pass_post' => 1, 'recent' => 1, 'threshold' => 1, 'past_only' => 1 );

		$relevant_options = array_intersect_key( $options, $clear_cache_options );
		$relevant_current_options = array_intersect_key( $current_options, $clear_cache_options );
		$new_options_which_require_flush = array_diff_assoc($relevant_options, $relevant_current_options);
		if ( count($new_options_which_require_flush) ||
			( $new_options['limit'] > $current_options['limit'] ) ||
			( $new_options['weight'] != $current_options['weight'] ) ||
			( $new_options['exclude'] != $current_options['exclude'] ) ||
			( $new_options['require_tax'] != $current_options['require_tax'] ) )
			$this->cache->flush();
	}
	
	// 3.4b8: $option can be a path, of the query_str variety, i.e. "option[suboption][subsuboption]"
	function get_option( $option = null ) {
		$options = (array) get_option( 'yarpp', array() );
		// ensure defaults if not set:
		$options = array_merge( $this->default_options, $options );

		if ( is_null( $option ) )
			return $options;
	
		$optionpath = array();
		$parsed_option = array();
		wp_parse_str($option, $parsed_option);
		$optionpath = $this->array_flatten($parsed_option);
		
		$current = $options;
		foreach ( $optionpath as $optionpart ) {
			if ( !is_array($current) || !isset($current[$optionpart]) )
				return null;
			$current = $current[$optionpart];
		}
		return $current;
	}
	
	private function array_flatten($array, $given = array()) {
		foreach ($array as $key => $val) {
			$given[] = $key;
			if ( is_array($val) )
				$given = $this->array_flatten($val, $given);
		}
		return $given;
	}

	/*
	 * INFRASTRUCTURE
	 */

	function enabled() {
		global $wpdb;

		if ( $this->cache->is_enabled() === false )
			return false;

		return $this->diagnostic_fulltext_indices();
	}
		
	// @since 3.5.2: function to enforce YARPP setup
	// if new install, activate; else upgrade
	function enforce() {
		if ( !get_option('yarpp_version') )
			$this->activate();
		else
			$this->upgrade();
		
		if ( $this->get_option('optin') )
			$this->optin_ping();
	}
	
	function activate() {
		global $wpdb;
	
		if ( !$this->diagnostic_fulltext_indices() ) {
			$wpdb->query("ALTER TABLE $wpdb->posts ADD FULLTEXT `yarpp_title` ( `post_title` )");
			$wpdb->query("ALTER TABLE $wpdb->posts ADD FULLTEXT `yarpp_content` ( `post_content` )");
		}
		
		if ( $this->cache->is_enabled() === false ) {
			// Run the cache abstraction's setup method.
			$this->cache->setup();
		}

		// If we're not enabled, give up.
		if ( !$this->enabled() )
			return false;
		
		if ( !get_option('yarpp_version') ) {
			// new install
			add_option( 'yarpp_version', YARPP_VERSION );
			$this->version_info(true);
		} else {
			// upgrade
			$this->upgrade();
		}
	
		return true;
	}

	/*
	 * DIAGNOSTICS
	 * @since 4 moved into separate functions. Note return value types can differ.
	 */
	
	function diagnostic_myisam_posts() {
		global $wpdb;
		$tables = $wpdb->get_results("show table status like '{$wpdb->posts}'");
		foreach ($tables as $table) {
			if ($table->Engine == 'MyISAM')
				return true;
			else
				return $table->Engine;
		}
		return 'UNKNOWN';
	}
	
	function diagnostic_fulltext_indices() {
		global $wpdb;
		$wpdb->get_results("show index from $wpdb->posts where Key_name = 'yarpp_title' or Key_name = 'yarpp_content'");
		return ( $wpdb->num_rows >= 2 );
	}
	
	function diagnostic_hidden_metaboxes() {
		global $wpdb;
		$raw = $wpdb->get_var("select meta_value from $wpdb->usermeta where meta_key = 'metaboxhidden_settings_page_yarpp' order by length(meta_value) asc limit 1");
		
		if ( !$raw )
			return $this->default_hidden_metaboxes;
		
		$list = maybe_unserialize( $raw );
		if ( !is_array($list) )
			return $this->default_hidden_metaboxes;

		return implode('|', $list);
	}
	
	function diagnostic_post_thumbnails() {
		return current_theme_supports( 'post-thumbnails', 'post' );
	}
	
	function diagnostic_happy() {
		$stats = $this->cache->stats();

		if ( !(array_sum( $stats ) > 0) )
			return false;
		
		$sum = array_sum(array_map('array_product', array_map(null, array_values($stats), array_keys($stats))));
		$avg = $sum / array_sum( $stats );

		return $this->cache->cache_status() > 0.1 && $avg > 2;
	}
	
	function diagnostic_generate_thumbnails() {
		return defined('YARPP_GENERATE_THUMBNAILS') && YARPP_GENERATE_THUMBNAILS;
	}
	
	function thumbnail_size() {
		global $_wp_additional_image_sizes;
		if ( !isset($_wp_additional_image_sizes['yarpp-thumbnail']) )
			return false;
		return $_wp_additional_image_sizes['yarpp-thumbnail'];
	}
	
	// code based on Viper's Regenerate Thumbnails plugin
	function ensure_resized_post_thumbnail( $post_id, $size, $dimensions ) {
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		$downsized = image_downsize( $thumbnail_id, $size );
		if ( $dimensions['crop'] && $downsized[1] && $downsized[2] && 
			( $downsized[1] != $dimensions['width'] || $downsized[2] != $dimensions['height'] ) ) {
			// we want to trigger recomputation of the thumbnail here
			// (only if downsized width and height are specified, for Photon behavior)
			$fullsizepath = get_attached_file( $thumbnail_id );
			if ( false !== $fullsizepath && file_exists( $fullsizepath ) ) {
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$metadata = wp_generate_attachment_metadata( $thumbnail_id, $fullsizepath );
				if ( !is_wp_error( $metadata ) ) {
					wp_update_attachment_metadata( $thumbnail_id, $metadata );
				}
			}
		}
	}
	
	/*
	 * UPGRADE ROUTINES
	 */
	
	function upgrade() {
		$last_version = get_option( 'yarpp_version' );
		if (version_compare(YARPP_VERSION, $last_version) === 0)
			return;
	
		if ( $last_version && version_compare('3.4b2', $last_version) > 0 )
			$this->upgrade_3_4b2();
		if ( $last_version && version_compare('3.4b5', $last_version) > 0 )
			$this->upgrade_3_4b5();
		if ( $last_version && version_compare('3.4b8', $last_version) > 0 )
			$this->upgrade_3_4b8();
		if ( $last_version && version_compare('3.4.4b2', $last_version) > 0 )
			$this->upgrade_3_4_4b2();
		if ( $last_version && version_compare('3.4.4b3', $last_version) > 0 )
			$this->upgrade_3_4_4b3();
		if ( $last_version && version_compare('3.4.4b4', $last_version) > 0 )
			$this->upgrade_3_4_4b4();
		if ( $last_version && version_compare('3.5.2b2', $last_version) > 0 )
			$this->upgrade_3_5_2b2();
		if ( $last_version && version_compare('3.6b7', $last_version) > 0 )
			$this->upgrade_3_6b7();
		if ( $last_version && version_compare('4.0.1', $last_version) > 0 )
			$this->upgrade_4_0_1();
		
		$this->cache->upgrade($last_version);
		// flush cache in 3.4.1b5 as 3.4 messed up calculations.
		if ( $last_version && version_compare('3.4.1b5', $last_version) > 0 )
			$this->cache->flush();
	
		$this->version_info(true);
	
		update_option('yarpp_version',YARPP_VERSION);
		$this->delete_transient( 'yarpp_optin' );
	}
	
	function upgrade_3_4b2() {
		global $wpdb;
	
		$yarpp_3_3_options = array(
			'threshold' => 4,
			'limit' => 4,
			'template_file' => '', // new in 2.2
			'excerpt_length' => 10,
			'recent_number' => 12,
			'recent_units' => 'month',
			'before_title' => '<li>',
			'after_title' => '</li>',
			'before_post' => ' <small>',
			'after_post' => '</small>',
			'before_related' => '<h3>'.__('Related posts:','yarpp').'</h3><ol>',
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
			'rss_before_related' => '<h3>'.__('Related posts:','yarpp').'</h3><ol>',
			'rss_after_related' => '</ol>',
			'rss_no_results' => '<p>'.__('No related posts.','yarpp').'</p>',
			'rss_order' => 'score DESC',
			'title' => '2',
			'body' => '2',
			'categories' => '1', // changed default in 3.3
			'tags' => '2',
			'distags' => '',
			'discats' => '',
			'past_only' => false,
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
	
		$yarpp_options = array();
		foreach ( $yarpp_3_3_options as $key => $default ) {
			$value = get_option( "yarpp_$key", null );
			if ( is_null($value) )
				continue;

			if ( is_bool($default) ) {
				$yarpp_options[$key] = (boolean) $value;
				continue;
			}

			// value options used to be stored with a bajillion slashes...
			$value = stripslashes(stripslashes($value));
			// value options used to be stored with a blank space at the end... don't ask.
			$value = rtrim($value, ' ');
			
			if ( is_int($default) )
				$yarpp_options[$key] = absint($value);
			else
				$yarpp_options[$key] = $value;
		}
		
		// add the options directly first, then call set_option which will ensure defaults,
		// in case any new options have been added.
		update_option( 'yarpp', $yarpp_options );
		$this->set_option( $yarpp_options );
		
		$option_keys = array_keys( $yarpp_options );
		// append some keys for options which are long deprecated:
		$option_keys[] = 'ad_hoc_caching';
		$option_keys[] = 'excerpt_len';
		$option_keys[] = 'show_score';
		if ( count($option_keys) ) {
			$in = "('yarpp_" . join("', 'yarpp_", $option_keys) . "')";
			$wpdb->query("delete from {$wpdb->options} where option_name in {$in}");
		}
	}
	
	function upgrade_3_4b5() {
		$options = $this->get_option();
		$options['exclude'] = array(
			'post_tag' => $options['distags'],
			'category' => $options['discats']
		);
		unset( $options['distags'] );
		unset( $options['discats'] );
		update_option( 'yarpp', $options );
	}
	
	function upgrade_3_4b8() {
		$options = $this->get_option();
		$options['weight'] = array(
			'title' => (int) @$options['title'],
			'body' => (int) @$options['body'],
			'tax' => array(
				'post_tag' => (int) @$options['tags'],
				'category' => (int) @$options['categories'],
			)
		);
		
		// ensure that we consider something
		if ( $options['weight']['title'] < 2 &&
			 $options['weight']['body'] < 2 &&
			 $options['weight']['tax']['post_tag'] < 2 &&
			 $options['weight']['tax']['category'] < 2 )
			$options['weight'] = $this->default_options['weight'];
			
		unset( $options['title'] );
		unset( $options['body'] );
		unset( $options['tags'] );
		unset( $options['categories'] );
		update_option( 'yarpp', $options );
	}
	
	function upgrade_3_4_4b2() {
		$options = $this->get_option();

		// update weight values; split out tax weights into weight[tax] and require_tax
		$weight_map = array( 2 => 1, 3 => YARPP_EXTRA_WEIGHT );
		if ((int) $options['weight']['title'] == 1)
			unset( $options['weight']['title'] );
		else 
			$options['weight']['title'] = $weight_map[(int) $options['weight']['title']];

		if ((int) $options['weight']['body'] == 1)
			unset( $options['weight']['body'] );
		else 
			$options['weight']['body'] = $weight_map[(int) $options['weight']['body']];
		
		$options['require_tax'] = array();
		foreach ( $options['weight']['tax'] as $tax => $value ) {
			if ( $value == 3 )
				$options['require_tax'][$tax] = 1;
			if ( $value == 4 )
				$options['require_tax'][$tax] = 2;			
			
			if ( $value > 1 )
				$options['weight']['tax'][$tax] = 1;
			else
				unset( $options['weight']['tax'][$tax] );
		}

		// consolidate excludes, using tt_ids.
		$exclude_tt_ids = array();
		if ( isset($options['exclude']) && is_array($options['exclude']) ) {
			foreach ($options['exclude'] as $tax => $term_ids) {
				if ( !empty($term_ids) )
					$exclude_tt_ids = array_merge( wp_list_pluck(get_terms( $tax, array('include' => $term_ids) ), 'term_taxonomy_id'), $exclude_tt_ids );
			}
		}
		$options['exclude'] = join(',', $exclude_tt_ids);

		update_option( 'yarpp', $options );
	}
	
	function upgrade_3_4_4b3() {
		$options = $this->get_option();
		$options['template'] = $options['use_template'] ? $options['template_file'] : false;
		$options['rss_template'] = $options['rss_use_template'] ? $options['rss_template_file'] : false;
		unset( $options['use_template'] );
		unset( $options['template_file'] );
		unset( $options['rss_use_template'] );
		unset( $options['rss_template_file'] );
		update_option( 'yarpp', $options );
	}
	
	function upgrade_3_4_4b4() {
		$options = $this->get_option();
		$options['recent'] = $options['recent_only'] ?
			$options['recent_number'] . ' ' . $options['recent_units'] : false;
		unset( $options['recent_only'] );
		unset( $options['recent_number'] );
		unset( $options['recent_units'] );
		update_option( 'yarpp', $options );
	}
	
	function upgrade_3_5_2b2() {
		// fixing the effects of a previous bug affecting non-MyISAM users
		if ( is_null( yarpp_get_option('weight') ) ||
			!is_array( yarpp_get_option('weight') ) ) {
			$weight = $this->default_options['weight'];
			// if we're still not using MyISAM
			if ( !yarpp_get_option('myisam_override') && 
				$this->diagnostic_myisam_posts() !== true ) {
				unset( $weight['title'] );
				unset( $weight['body'] );
			}
			yarpp_set_option(array('weight' => $weight));
		}
	}

	function upgrade_3_6b7() {
		// migrate auto_display setting to auto_display_post_types
		$options = $this->get_option();
		$options['auto_display_post_types'] = $options['auto_display'] ?
			array( 'post' ) : array();
		unset( $options['auto_display'] );
		update_option( 'yarpp', $options );
	}
	
	function upgrade_4_0_1() {
		delete_transient('yarpp_version_info');
	}
	
	private $post_types = null;
	function get_post_types( $field = 'name' ) {
		if ( is_null($this->post_types) ) {
			$this->post_types = get_post_types(array(), 'objects');
			$this->post_types = array_filter( $this->post_types, array($this, 'post_type_filter') );
		}
		
		if ( 'objects' == $field )
			return $this->post_types;
		return wp_list_pluck( $this->post_types, $field );
	}
	
	private function post_type_filter( $post_type ) {
		if ( $post_type->_builtin && $post_type->show_ui )
			return true;
		if ( isset($post_type->yarpp_support) )
			return $post_type->yarpp_support;
		return false;
	}
	
	private $taxonomies = null;
	function get_taxonomies( $field = false ) {
		if ( is_null($this->taxonomies) ) {
			$this->taxonomies = get_taxonomies(array(), 'objects');
			$this->taxonomies = array_filter( $this->taxonomies, array($this, 'taxonomy_filter') );
		}
		
		if ( $field )
			return wp_list_pluck( $this->taxonomies, $field );
		return $this->taxonomies;
	}
	
	private function taxonomy_filter( $taxonomy ) {
		if ( !count(array_intersect( $taxonomy->object_type, $this->get_post_types() )) )
			return false;

		// if yarpp_support is set, follow that; otherwise include if show_ui is true
		if ( isset($taxonomy->yarpp_support) )
			return $taxonomy->yarpp_support;
		return $taxonomy->show_ui;
	}
	
	public function optin_data() {
		global $wpdb, $yarpp;

		$comments = wp_count_comments();
		$users = count_users();

		$settings = get_option( 'yarpp' );
		$collect = array_flip(array(
			'threshold', 'limit', 'excerpt_length', 'recent', 'rss_limit',
			'rss_excerpt_length', 'past_only', 'show_excerpt', 'rss_show_excerpt',
			'template', 'rss_template', 'show_pass_post', 'cross_relate',
			'rss_display', 'rss_excerpt_display', 'promote_yarpp', 'rss_promote_yarpp',
			'myisam_override', 'weight', 'require_tax', 'auto_display_archive'
		));
		$check_changed = array(
			'before_title', 'after_title', 'before_post', 'after_post',
			'after_related', 'no_results', 'order', 'rss_before_title',
			'rss_after_title', 'rss_before_post', 'rss_after_post', 'rss_after_related',
			'rss_no_results', 'rss_order', 'exclude', 'thumbnails_heading',
			'thumbnails_default', 'rss_thumbnails_heading', 'rss_thumbnails_default', 'display_code'
		);

		$data = array(
			'versions' => array(
				'yarpp' => YARPP_VERSION,
				'wp' => get_bloginfo( 'version' ),
				'php' => phpversion()
			),
			'yarpp' => array(
				'settings' => array_intersect_key( $settings, $collect ),
				'cache_engine' => YARPP_CACHE_TYPE
			),
			'diagnostics' => array(
				'myisam_posts' => $this->diagnostic_myisam_posts(),
				'fulltext_indices' => $this->diagnostic_fulltext_indices(),
				'hidden_metaboxes' => $this->diagnostic_hidden_metaboxes(),
				'post_thumbnails' => $this->diagnostic_post_thumbnails(),
				'happy' => $this->diagnostic_happy(),
				'generate_thumbnails' => $this->diagnostic_generate_thumbnails()
			),
			'stats' => array(
				'counts' => array(),
				'terms' => array(),
				'comments' => array(
					'moderated' => $comments->moderated,
					'approved' => $comments->approved,
					'total' => $comments->total_comments,
					'posts' => $wpdb->get_var( "select count(ID) from $wpdb->posts where post_type = 'post' and comment_count > 0" )
				),
				'users' => $wpdb->get_var("select count(ID) from $wpdb->users"),
			),
			'locale' => get_bloginfo( 'language' ),
			'url' => get_bloginfo('url'),
			'plugins' => array(
				'active' => implode( '|', get_option( 'active_plugins', array() ) ),
				'sitewide' => implode( '|', get_site_option( 'active_sitewide_plugins', array() ) )
			),
			'pools' => $settings['pools']
		);
		$data['yarpp']['settings']['auto_display_post_types'] = implode( '|', $settings['auto_display_post_types'] );
		
		$changed = array();
		foreach ( $check_changed as $key ) {
			if ( $yarpp->default_options[$key] != $settings[$key] )
				$changed[] = $key;
		}
		foreach ( array( 'before_related', 'rss_before_related' ) as $key ) {
			if ( $settings[$key] != '<p>'.__('Related posts:','yarpp').'</p><ol>' &&
				$settings[$key] != $yarpp->default_options[$key] )
				$changed[] = $key;
		}
		$data['yarpp']['changed_settings'] = implode( '|', $changed );
		
		if ( method_exists( $yarpp->cache, 'cache_status' ) )
			$data['yarpp']['cache_status'] = $yarpp->cache->cache_status();
		if ( method_exists( $yarpp->cache, 'stats' ) ) {
			$stats = $yarpp->cache->stats();
			$flattened = array();
			foreach ( $stats as $key => $value )
				$flattened[] = "$key:$value";
			$data['yarpp']['stats'] = implode( '|', $flattened );
		}
			
		if ( method_exists( $wpdb, 'db_version' ) )
			$data['versions']['mysql'] = preg_replace('/[^0-9.].*/', '', $wpdb->db_version());

		$counts = array();
		foreach (get_post_types( array('public' => true) ) as $post_type) {
			$counts[$post_type] = wp_count_posts($post_type);
		}
		$data['stats']['counts'] = wp_list_pluck($counts, 'publish');

		foreach (get_taxonomies( array('public' => true) ) as $taxonomy) {
			$data['stats']['terms'][$taxonomy] = wp_count_terms($taxonomy);
		}
		
		if ( is_multisite() ) {
			$data['multisite'] = array(
				'url' => network_site_url(),
				'users' => get_user_count(),
				'sites' => get_blog_count()
			);
		}
		
		return $data;
	}
	
	function pretty_echo( $data ) {
		echo "<pre>";
		$formatted = print_r($data, true);
		$formatted = str_replace(array('Array', '(', ')', "\n    "), array('', '', '', "\n"), $formatted);
		echo preg_replace("/\n\s*\n/u", "\n", $formatted);
		echo "</pre>";
	}
	
	/*
	 * CORE LOOKUP + DISPLAY FUNCTIONS
	 */
	 
	/* new in 2.1! the domain argument refers to {website,widget,rss} */
	/* new in 3.0! new query-based approach: EXTREMELY HACKY! */
	/* 
	 * @param (int) $reference_ID
	 * @param (array) $args
	 * @param (bool) $echo
	 */
	function display_related($reference_ID = null, $args = array(), $echo = true) {
		// if we're already in a YARPP loop, stop now.
		if ( $this->cache->is_yarpp_time() || $this->cache_bypass->is_yarpp_time() )
			return false;

		global $wp_query, $pagenow;
	
		$this->enforce();

		if ( is_numeric( $reference_ID ) )
			$reference_ID = (int) $reference_ID;
		else
			$reference_ID = get_the_ID();

		// @since 3.5.3: don't compute on revisions
		if ( $the_post = wp_is_post_revision($reference_ID) )
			$reference_ID = $the_post;
		
		$this->setup_active_cache( $args );

		$options = array( 'domain', 'limit', 'template', 'order', 'promote_yarpp', 'optin' );
		extract( $this->parse_args( $args, $options ) );

		$cache_status = $this->active_cache->enforce($reference_ID);
		// If cache status is YARPP_DONT_RUN, end here without returning or echoing anything.
		if ( YARPP_DONT_RUN == $cache_status )
			return;
		
		if ( YARPP_NO_RELATED == $cache_status ) {
			// There are no results, so no yarpp time for us... :'(
		} else {
			// Get ready for YARPP TIME!
			$this->active_cache->begin_yarpp_time($reference_ID, $args);
		}
	
		// so we can return to normal later
		$current_query = $wp_query;
		$current_pagenow = $pagenow;
	
		$wp_query = new WP_Query();
		if ( YARPP_NO_RELATED == $cache_status ) {
			// If there are no related posts, get no query
		} else {
			$orders = explode(' ',$order);
			$wp_query->query(array(
				'p' => $reference_ID,
				'orderby' => $orders[0],
				'order' => $orders[1],
				'showposts' => $limit,
				'post_type' => ( isset($args['post_type']) ? $args['post_type'] : $this->get_post_types() )
			));
		}
		$this->prep_query( $current_query->is_feed );
		$related_query = $wp_query; // backwards compatibility
		$related_count = $related_query->post_count;

		$output = "<div class='";
		if ( 'website' == $domain )
			$output .= "yarpp-related";
		else
			$output .= "yarpp-related-{$domain}";
		if ( 1 > $related_count )
			$output .= " yarpp-related-none";
		$output .= "'>\n";

		if ( 'metabox' == $domain ) {
			include(YARPP_DIR . '/template-metabox.php');
		} elseif ( !!$template && 'thumbnails' == $template ) {
			include(YARPP_DIR . '/template-thumbnails.php');			
		} elseif ( !!$template && file_exists(STYLESHEETPATH . '/' . $template) ) {
			global $post;
			ob_start();
			include(STYLESHEETPATH . '/' . $template);
			$output .= ob_get_contents();
			ob_end_clean();
		} elseif ( 'widget' == $domain ) {
			include(YARPP_DIR . '/template-widget.php');
		} else {
			include(YARPP_DIR . '/template-builtin.php');
		}
		$output = trim($output) . "\n";
	
		if ( YARPP_NO_RELATED == $cache_status ) {
			// Uh, do nothing. Stay very still.
		} else {
			$this->active_cache->end_yarpp_time(); // YARPP time is over... :(
		}
	
		// restore the older wp_query.
		$wp_query = $current_query; unset($current_query); unset($related_query);
		wp_reset_postdata();
		$pagenow = $current_pagenow; unset($current_pagenow);
	
		if ( $related_count > 0 && $promote_yarpp && $domain != 'metabox' )
			$output .= "<p>".sprintf(__("Related posts brought to you by <a href='%s'>Yet Another Related Posts Plugin</a>.",'yarpp'), 'http://yarpp.org')."</p>\n";
	
		if ( $optin )
			$output .= "<img src='http://yarpp.org/pixels/" . md5(get_bloginfo('url')) . "'/>\n";

		$output .= "</div>\n";
			
		if ($echo)
			echo $output;
		return $output;
	}
	
	/* 
	 * @param (int) $reference_ID
	 * @param (array) $args
	 */
	function get_related($reference_ID = null, $args = array()) {
		// if we're already in a YARPP loop, stop now.
		if ( $this->cache->is_yarpp_time() || $this->cache_bypass->is_yarpp_time() )
			return false;

		$this->enforce();

		if ( is_numeric( $reference_ID ) )
			$reference_ID = (int) $reference_ID;
		else
			$reference_ID = get_the_ID();

		// @since 3.5.3: don't compute on revisions
		if ( $the_post = wp_is_post_revision($reference_ID) )
			$reference_ID = $the_post;
			
		$this->setup_active_cache( $args );

		$options = array( 'limit', 'order' );
		extract( $this->parse_args( $args, $options ) );

		$cache_status = $this->active_cache->enforce($reference_ID);
		if ( YARPP_DONT_RUN == $cache_status || YARPP_NO_RELATED == $cache_status )
			return array();
					
		// Get ready for YARPP TIME!
		$this->active_cache->begin_yarpp_time($reference_ID, $args);
	
		$related_query = new WP_Query();
		$orders = explode(' ',$order);
		$related_query->query(array(
			'p' => $reference_ID,
			'orderby' => $orders[0],
			'order' => $orders[1],
			'showposts' => $limit,
			'post_type' => ( isset($args['post_type']) ? $args['post_type'] : $this->get_post_types() )
		));
		$this->active_cache->end_yarpp_time(); // YARPP time is over... :(
	
		return $related_query->posts;
	}
	
	/* 
	 * @param (int) $reference_ID
	 * @param (array) $args
	 */
	function related_exist($reference_ID = null, $args = array()) {
		// if we're already in a YARPP loop, stop now.
		if ( $this->cache->is_yarpp_time() || $this->cache_bypass->is_yarpp_time() )
			return false;

		$this->enforce();	
	
		if ( is_numeric( $reference_ID ) )
			$reference_ID = (int) $reference_ID;
		else
			$reference_ID = get_the_ID();

		// @since 3.5.3: don't compute on revisions
		if ( $the_post = wp_is_post_revision($reference_ID) )
			$reference_ID = $the_post;
				
		$this->setup_active_cache( $args );
	
		$cache_status = $this->active_cache->enforce($reference_ID);
	
		if ( YARPP_NO_RELATED == $cache_status )
			return false;
	
		$this->active_cache->begin_yarpp_time($reference_ID); // get ready for YARPP TIME!
		$related_query = new WP_Query();
		$related_query->query(array(
			'p' => $reference_ID,
			'showposts' => 1,
			'post_type' => ( isset($args['post_type']) ? $args['post_type'] : $this->get_post_types() )
		));
		$return = $related_query->have_posts();
		unset($related_query);
		$this->active_cache->end_yarpp_time(); // YARPP time is over. :(
	
		return $return;
	}
		
	/* 
	 * @param (array) $args
	 * @param (bool) $echo
	 */
	function display_demo_related($args = array(), $echo = true) {
		global $wp_query;
	
		if ( $this->cache_bypass->demo_time ) // if we're already in a demo YARPP loop, stop now.
			return false;
	
		$options = array( 'domain', 'limit', 'template', 'order', 'promote_yarpp' );
		extract( $this->parse_args( $args, $options ) );
	
		$this->cache_bypass->begin_demo_time( $limit );
	
		$output = "<div class='";
		if ( 'website' == $domain )
			$output .= "yarpp-related";
		else
			$output .= "yarpp-related-{$domain}";
		$output .= "'>\n";

		$wp_query = new WP_Query();
		$wp_query->query('');
	
		$this->prep_query( $domain == 'rss' );
		$related_query = $wp_query; // backwards compatibility
	
		if ( !!$template && 'thumbnails' == $template ) {
			include(YARPP_DIR . '/template-thumbnails.php');
		} elseif ( !!$template && file_exists(STYLESHEETPATH . '/' . $template) ) {
			global $post;
			ob_start();
			include(STYLESHEETPATH . '/' . $template);
			$output .= ob_get_contents();
			ob_end_clean();
		} else {
			include(YARPP_DIR.'/template-builtin.php');
		}
		$output = trim($output) . "\n";
		
		$this->cache_bypass->end_demo_time();
		
		if ($promote_yarpp)
			$output .= "<p>".sprintf(__("Related posts brought to you by <a href='%s'>Yet Another Related Posts Plugin</a>.",'yarpp'), 'http://yarpp.org')."</p>\n";

		$output .= "</div>";
	
		if ( $echo )
			echo $output;
		return $output;
	}
	
	public function parse_args( $args, $options ) {
		$options_with_rss_variants = array(
			'limit', 'template', 'excerpt_length', 'before_title',
			'after_title', 'before_post', 'after_post', 'before_related',
			'after_related', 'no_results', 'order', 'promote_yarpp',
			'thumbnails_heading', 'thumbnails_default' );

		if ( !isset($args['domain']) )
			$args['domain'] = 'website';

		$r = array();
		foreach ( $options as $option ) {
			if ( 'rss' == $args['domain'] &&
				 in_array( $option, $options_with_rss_variants ) )
				$default = $this->get_option( 'rss_' . $option );
			else
				$default = $this->get_option( $option );
			
			if ( isset($args[$option]) && $args[$option] !== $default ) {
				$r[$option] = $args[$option];
			} else {
				$r[$option] = $default;
			}
		}
		return $r;
	}
	
	private function setup_active_cache( $args ) {
		// the options which the main sql query cares about:
		$magic_options = array( 'limit', 'threshold', 'show_pass_post', 'past_only', 'weight', 'exclude', 'require_tax', 'recent' );

		$defaults = $this->get_option();
		foreach ( $magic_options as $option ) {
			if ( !isset($args[$option]) )
				continue;
				
			// limit is a little different... if it's less than what we cache,
			// let it go.
			if ( 'limit' == $option &&
				 $args[$option] <= max($defaults['limit'], $defaults['rss_limit']) )
				 continue;
			
			if ( $args[$option] !== $defaults[$option] ) {
				$this->active_cache = $this->cache_bypass;
				return;
			}
		}
		$this->active_cache = $this->cache;
	}
	
	private function prep_query( $is_feed = false ) {
		global $wp_query;
		$wp_query->in_the_loop = true;
		$wp_query->is_feed = $is_feed;
		// make sure we get the right is_single value
		// (see http://wordpress.org/support/topic/288230)
		$wp_query->is_single = false;
	}
	
	/*
	 * DEFAULT CONTENT FILTERS
	 */
	 
	function the_content($content) {
		// this filter doesn't handle feeds
		if ( is_feed() )
			return $content;

		$auto_display_post_types = $this->get_option( 'auto_display_post_types' );

		// if it's not an auto-display post type, return
		if ( !in_array( get_post_type(), $auto_display_post_types ) )
			return $content;

		if ( !is_singular() && !(
			  $this->get_option('auto_display_archive') &&
			  ( is_archive() || is_home() )
			) )
			return $content;
	
		if ( $this->get_option('cross_relate') )
			$post_types = $this->get_post_types();
		else
			$post_types = array( get_post_type() );

		$post_types = apply_filters( 'yarpp_map_post_types', $post_types, 'website' );
	
		return $content . $this->display_related(null, array(
			'post_type' => $post_types,
			'domain' => 'website'
		), false);
	}
	
	function the_content_feed($content) {
		if ( !$this->get_option('rss_display') )
			return $content;

		if ( $this->get_option('cross_relate') )
			$post_types = $this->get_post_types();
		else
			$post_types = array( get_post_type() );

		$post_types = apply_filters( 'yarpp_map_post_types', $post_types, 'rss' );
	
		return $content . $this->display_related(null, array(
			'post_type' => $post_types,
			'domain' => 'rss'
		), false);
	}
	
	function the_excerpt_rss($content) {
		if ( !$this->get_option('rss_excerpt_display') || 
		     !$this->get_option('rss_display') )
			return $content;

		if ( $this->get_option('cross_relate') )
			$type = $this->get_post_types();
		else if ( 'page' == get_post_type() )
			$type = array( 'page' );
		else
			$type = array( 'post' );
	
		return $content . $this->clean_pre($this->display_related(null, array('post_type' => $type, 'domain' => 'rss'), false));
	}
	
	/*
	 * UTILS
	 */
	
	// @since 3.3: use PHP serialized format instead of JSON
	function version_info( $enforce_cache = false ) {
		if (false === ($result = $this->get_transient('yarpp_version_info')) || $enforce_cache) {
			$version = YARPP_VERSION;
			$remote = wp_remote_post("http://yarpp.org/checkversion.php?format=php&version={$version}");
			
			if (is_wp_error($remote))
				return false;
			
			if ( $result = @unserialize($remote['body']) )
				$this->set_transient('yarpp_version_info', $result, 60 * 60 * 24);
		}
		return $result;
	}

	// @since 4: optional data collection (default off)
	function optin_ping() {
		if ( $this->get_transient( 'yarpp_optin' ) )
			return true;

		$remote = wp_remote_post( 'http://yarpp.org/optin/2/', array( 'body' => $this->optin_data() ) );
		if ( is_wp_error($remote) || !isset($remote['body']) || $remote['body'] != 'ok' ) {
			// try again later
			$this->set_transient( 'yarpp_optin', null, 60 * 60 );
			return false;
		}
		$this->set_transient( 'yarpp_optin', null, 60 * 60 * 24 * 7 );
		return true;
	}
	
	// a version of the transient functions which is unaffected by caching plugin behavior.
	// we want to control the lifetime of data.
	private function get_transient( $transient ) {
		$transient_timeout = $transient . '_timeout';
		if ( intval( get_option( $transient_timeout ) ) < time() ) {
			delete_option( $transient_timeout );
			return false; // timed out
		}
		return get_option( $transient, true ); // still ok
	}

	private function set_transient( $transient, $data = null, $expiration = 0 ) {
		$transient_timeout = $transient . '_timeout';
		if ( false === get_option( $transient_timeout ) ) {
			add_option( $transient_timeout, time() + $expiration, '', 'no' );
			if ( !is_null( $data ) )
				add_option( $transient, $data, '', 'no' );
		} else {
			update_option( $transient_timeout, time() + $expiration );
			if ( !is_null( $data ) )
				update_option( $transient, $data );
		}
	}
	
	private function delete_transient( $transient ) {
		delete_option( $transient );
		delete_option( $transient . '_timeout' );
	}
	
	// 3.5.2: clean_pre is deprecated in WP 3.4, so implement here.
	function clean_pre( $text ) {
		$text = str_replace(array('<br />', '<br/>', '<br>'), array('', '', ''), $text);
		$text = str_replace('<p>', "\n", $text);
		$text = str_replace('</p>', '', $text);
		return $text;
	}
}
