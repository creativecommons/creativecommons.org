<?php

$yarpp_storage_class = 'YARPP_Cache_Postmeta';

define('YARPP_POSTMETA_KEYWORDS_KEY','_yarpp_keywords');
define('YARPP_POSTMETA_RELATED_KEY', '_yarpp_related');

class YARPP_Cache_Postmeta extends YARPP_Cache {

	public $name = "postmeta";

	// variables used for lookup
	private $related_postdata = array();
	private $related_IDs = array();

	/**
	 * SETUP/STATUS
	 */
	function __construct( &$core ) {
		parent::__construct( $core );
	}

	public function is_enabled() {
		return true; // always enabled.
	}

	public function setup() {
	}
	
	public function upgrade( $last_version ) {
		if ( $last_version && version_compare('3.4b1', $last_version) > 0 ) {
			// 3.4 moved _yarpp_body_keywords and _yarpp_title_keywords into the single
			// postmeta _yarpp_keywords, so clear out the old data now.
			delete_post_meta_by_key('_yarpp_title_keywords');
			delete_post_meta_by_key('_yarpp_body_keywords');
		}	
	}

	public function cache_status() {
		global $wpdb;
		return $wpdb->get_var("select (count(p.ID)-sum(m.meta_value IS NULL))/count(p.ID)
			FROM `{$wpdb->posts}` as p
			LEFT JOIN `{$wpdb->postmeta}` as m ON (p.ID = m.post_id and m.meta_key = '" . YARPP_POSTMETA_RELATED_KEY . "')
			WHERE p.post_status = 'publish'");
	}

	public function uncached($limit = 20, $offset = 0) {
		global $wpdb;
		return $wpdb->get_col("select SQL_CALC_FOUND_ROWS p.ID
			FROM `{$wpdb->posts}` as p
			LEFT JOIN `{$wpdb->postmeta}` as m ON (p.ID = m.post_id and m.meta_key = '" . YARPP_POSTMETA_RELATED_KEY . "')
			WHERE p.post_status = 'publish' and m.meta_value IS NULL
			LIMIT $limit OFFSET $offset");
	}

	public function stats() {
		global $wpdb;
		return wp_list_pluck($wpdb->get_results("select num, count(*) as ct from (select 0 + if(meta_value = '" . YARPP_NO_RELATED . "', 0, substring(substring_index(meta_value,':',2),3)) as num from `{$wpdb->postmeta}` where meta_key = '" . YARPP_POSTMETA_RELATED_KEY . "') as t group by num order by num asc", OBJECT_K), 'ct');
	}

	/**
	 * MAGIC FILTERS
	 */
	public function where_filter($arg) {
		global $wpdb;
		// modify the where clause to use the related ID list.
		if (!count($this->related_IDs))
			$this->related_IDs = array(0);
		$arg = preg_replace("!{$wpdb->posts}.ID = \d+!","{$wpdb->posts}.ID in (".join(',',$this->related_IDs).")",$arg);

		// if recent is set, add an additional condition
		$recent = yarpp_get_option('recent');
		if ( !!$recent )
			$arg .= " and post_date > date_sub(now(), interval {$recent}) ";
		return $arg;
	}

	public function orderby_filter($arg) {
		global $wpdb;
		// only order by score if the score function is added in fields_filter, which only happens
		// if there are related posts in the postdata
		if ($this->score_override &&
		    is_array($this->related_postdata) && count($this->related_postdata))
			return str_replace("$wpdb->posts.post_date","score",$arg);
		return $arg;
	}

	public function fields_filter($arg) {
		global $wpdb;
		if (is_array($this->related_postdata) && count($this->related_postdata)) {
			$scores = array();
			foreach ($this->related_postdata as $related_entry) {
				$scores[] = " WHEN {$related_entry['ID']} THEN {$related_entry['score']}";
			}
			$arg .= ", CASE {$wpdb->posts}.ID" . join('',$scores) ." END as score";
		}
		return $arg;
	}

	public function limit_filter($arg) {
		if ($this->online_limit)
			return " limit {$this->online_limit} ";
		return $arg;
	}

	/**
	 * RELATEDNESS CACHE CONTROL
	 */
	public function begin_yarpp_time($reference_ID) {
		$this->yarpp_time = true;
		// get the related posts from postmeta, and also construct the relate_IDs array
		$this->related_postdata = get_post_meta($reference_ID,YARPP_POSTMETA_RELATED_KEY,true);
		if (is_array($this->related_postdata) && count($this->related_postdata))
			$this->related_IDs = wp_list_pluck( $this->related_postdata, 'ID' );
		add_filter('posts_where',array(&$this,'where_filter'));
		add_filter('posts_orderby',array(&$this,'orderby_filter'));
		add_filter('posts_fields',array(&$this,'fields_filter'));
		add_filter('post_limits',array(&$this,'limit_filter'));
		add_action('pre_get_posts',array(&$this,'add_signature'));
		// sets the score override flag.
		add_action('parse_query',array(&$this,'set_score_override_flag'));
	}

	public function end_yarpp_time() {
		$this->yarpp_time = false;
		$this->related_IDs = array();
		$this->related_postdata = array();
		remove_filter('posts_where',array(&$this,'where_filter'));
		remove_filter('posts_orderby',array(&$this,'orderby_filter'));
		remove_filter('posts_fields',array(&$this,'fields_filter'));
		remove_filter('post_limits',array(&$this,'limit_filter'));
		remove_action('pre_get_posts',array(&$this,'add_signature'));
		// sets the score override flag.
		remove_action('parse_query',array(&$this,'set_score_override_flag'));
	}

	// @return YARPP_NO_RELATED | YARPP_RELATED | YARPP_NOT_CACHED
	public function is_cached($reference_ID) {
		$related = get_post_meta($reference_ID, YARPP_POSTMETA_RELATED_KEY, true);
		if ( YARPP_NO_RELATED === $related )
			return YARPP_NO_RELATED;			
		if ( '' == $related )
			return YARPP_NOT_CACHED;
		return YARPP_RELATED;
	}

	public function clear( $reference_IDs ) {
		$reference_IDs = wp_parse_id_list( $reference_IDs );
	
		if ( !count($reference_IDs) )
			return;
		
		// clear each cache
		foreach( $reference_IDs as $id ) {
			delete_post_meta( $id, YARPP_POSTMETA_RELATED_KEY );
			delete_post_meta( $id, YARPP_POSTMETA_KEYWORDS_KEY );
		}
	}

	// @return YARPP_NO_RELATED | YARPP_RELATED
	// @used by enforce
	protected function update($reference_ID) {
		global $wpdb;

		$original_related = $this->related($reference_ID);
		$related = $wpdb->get_results($this->sql($reference_ID), ARRAY_A);
		$new_related = wp_list_pluck( $related, 'ID' );

		if ( count($new_related) ) {
			update_post_meta($reference_ID, YARPP_POSTMETA_RELATED_KEY, $related);
			if ($this->core->debug) echo "<!--YARPP just set the cache for post $reference_ID-->";

			// Clear the caches of any items which are no longer related or are newly related.
			if (count($original_related)) {
				$this->clear(array_diff($original_related, $new_related));
				$this->clear(array_diff($new_related, $original_related));
			}

			return YARPP_RELATED;
		} else {
			update_post_meta($reference_ID, YARPP_POSTMETA_RELATED_KEY, YARPP_NO_RELATED);

			// Clear the caches of those which are no longer related.
			if (count($original_related))
				$this->clear($original_related);

			return YARPP_NO_RELATED;
		}
	}

	public function flush() {
		delete_post_meta_by_key( YARPP_POSTMETA_RELATED_KEY );
		delete_post_meta_by_key( YARPP_POSTMETA_KEYWORDS_KEY );
	}

	public function related($reference_ID = null, $related_ID = null) {
		global $wpdb;

		if ( !is_int( $reference_ID ) && !is_int( $related_ID ) ) {
			_doing_it_wrong( __METHOD__, 'reference ID and/or related ID must be set', '3.4' );
			return;
		}

		if (!is_null($reference_ID) && !is_null($related_ID)) {
			$results = get_post_meta($reference_ID,YARPP_POSTMETA_RELATED_KEY,true);
			foreach($results as $result) {
				if ($result['ID'] == $related_ID)
					return true;
			}
			return false;
		}

		// return a list of ID's of "related" entries
		if (!is_null($reference_ID)) {
			$results = get_post_meta($reference_ID,YARPP_POSTMETA_RELATED_KEY,true);
			if (!$results || $results == YARPP_NO_RELATED)
				return array();
			return wp_list_pluck( $results, 'ID' );
		}

		// return a list of entities which list this post as "related"
		if (!is_null($related_ID)) {
			return $wpdb->get_col("select post_id from `{$wpdb->postmeta}` where meta_key = '" . YARPP_POSTMETA_RELATED_KEY . "' and meta_value regexp 's:2:\"ID\";s:\d+:\"{$related_ID}\"'");
		}

		return false;
	}

	/**
	 * KEYWORDS CACHE CONTROL
	 */
	 
	// @return (array) with body and title keywords
	private function cache_keywords($ID) {
		$keywords = array(
			'body' => $this->body_keywords($ID),
			'title' => $this->title_keywords($ID)
		);
		update_post_meta($ID, YARPP_POSTMETA_KEYWORDS_KEY, $keywords);
		return $keywords;
	}

	// @param $ID (int)
	// @param $type (string) body | title | all
	// @return (string|array) depending on whether "all" were requested or not
	public function get_keywords( $ID, $type = 'all' ) {
		if ( !$ID = absint($ID) )
			return false;
	
		$keywords = get_post_meta($ID, YARPP_POSTMETA_KEYWORDS_KEY, true);

		if ( empty($keywords) ) // if empty, try caching them first.
			$keywords = $this->cache_keywords($ID);

		if ( empty($keywords) )
			return false;
		
		if ( 'all' == $type )
			return $keywords;
		return $keywords[$type];
	}
}