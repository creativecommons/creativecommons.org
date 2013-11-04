<?php
/**
 * SlideDeck Slide Model
 * 
 * Model for handling CRUD and other basic functionality for SlideDeck custom slides
 * 
 * @author dtelepathy
 * @package SlideDeck
 */

/*
Copyright 2012 digital-telepathy  (email : support@digital-telepathy.com)

This file is part of SlideDeck.

SlideDeck is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

SlideDeck is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SlideDeck.  If not, see <http://www.gnu.org/licenses/>.
*/
class SlideDeckSlideModel {
	var $namespace = "slidedeck";
	
	var $name = 'sd2_custom_slide';
    var $post_type = 'sd2_custom_slide';
	var $description = "Slides for Custom SlideDeck 2 SlideDecks";
	var $label = "SlideDeck 2 Custom Slides";
	
	var $url = "";
	var $thumbnail = "";
	var $filepath = "";
	
	function __construct() {
	    add_action( "init", array( &$this, 'register_post_types' ) );
        add_action( "{$this->namespace}_update_slide", array( &$this, '_slidedeck_update_slide' ), 100, 2 );
	}
    
    /**
     * Hook into slidedeck_update_slide action to cache bust
     * 
     * Increments a cache busting number to prevent pulling up old caches after update a slide
     * 
     * @param object $slide The slide post object
     * @param array $data The submitted POST data
     * 
     * @uses get_post_meta()
     * @uses update_post_meta()
     * @uses SlideDeckSlide::get_cache_buster()
     */
    function _slidedeck_update_slide( $slide, $data ) {
        $cache_buster = $this->get_cache_buster( $slide->ID );
        
        // Increment cache buster
        $cache_buster++;
        
        update_post_meta( $slide->ID, "_{$this->namespace}_cache_buster", $cache_buster );
    }
    
    /**
     * Load the cache buster for a slide
     * 
     * @param int $slide_id ID of the slide
     * 
     * @uses get_post_meta()
     * 
     * @return int
     */
    function get_cache_buster( $slide_id ) {
        $cache_buster = get_post_meta( $slide_id, "_{$this->namespace}_cache_buster", true );
        if( empty( $cache_buster ) )
            $cache_buster = 1;
        
        return $cache_buster;
    } 
    
    /**
     * Change the slide type for a Slide
     * 
     * Updates the slide type for a slide and returns success of the update
     * 
     * @param mixed $slide The Slide object or Slide ID to update
     * @param string $slide_type The slide type to change to
     * 
     * @return boolean
     */
    function change_slide_type( $slide, $slide_type ) {
        $success = false;
        
        // Look up Slide object if a Slide ID was passed
        if( !is_object( $slide ) ) {
            $slide = $this->get( $slide, false );
        }
        
        update_post_meta( $slide->ID, "_slide_type", $slide_type );
        update_post_meta( $slide->ID, "_caption_position", "top" );
        update_post_meta( $slide->ID, "_layout", "caption" );
        
        do_action( "{$this->namespace}_change_slide_type", $slide, $slide_type );
        
        return $success;
    }
	
	/**
	 * Create a new Slide
	 * 
	 * Creates a new Slide post entry associated with the SlideDeck passed in. Returns
	 * the ID of the new Slide post entry.
	 * 
	 * @param int $slidedeck_id The SlideDeck ID the Slide will be associated with
	 * 
	 * @return int
	 */
	function create( $slidedeck_id, $slide_type, $args = array() ) {
		// Get all the existing slides for this SlideDeck
		$slide_count = $this->get_slide_count( $slidedeck_id );
		
		$params = array(
			'menu_order' => ( $slide_count + 1 ),
			'post_type' => $this->post_type,
			'post_title' => "Slide " . ( $slide_count + 1 ),
			'post_parent' => $slidedeck_id,
			'post_status' => "publish"
		);
		$params = array_merge( $params, $args );
		
		$slide_id = wp_insert_post( $params );
		
		update_post_meta( $slide_id, "_slide_type", $slide_type );
		update_post_meta( $slide_id, "_image_scaling", "cover" );
		update_post_meta( $slide_id, "_caption_position", "top" );
		update_post_meta( $slide_id, "_layout", "caption" );
		
		$slide = $this->get( $slide_id );
		
		do_action( "{$this->namespace}_after_create_slide", $slide, $slidedeck_id );
		
        // Retrieve modified slide without cache
        $slide = $this->get( $slide_id, false );
        
		return $slide_id;
	}
	
	/**
	 * Delete a slide
	 * 
	 * @param int $slide_id
	 * 
	 * @return boolean
	 */
	function delete( $slide_id ) {
		do_action( "{$this->namespace}_delete_slide", $slide_id );
		
		$success = wp_delete_post( $slide_id, true );
		
		return $success !== false;
	}
	
	/**
	 * Get a Slide or slides
	 * 
	 * Looks up a slide or slides by their ID(s) and returns an array of Slide objects
	 * or if a single slide was requested, returns that slide object
	 * 
	 * @param mixed $slide_id Slide ID (int) or array of Slide IDs
     * @param bool $cached Load using cached results if available
	 * 
	 * @return mixed
	 */
	function get( $slide_id, $cached = true ) {
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $this->post_type,
			'post_status' => "any",
			'orderby' => "menu_order",
			'order' => "ASC"
		);
		if( is_array( $slide_id ) ) {
			$args['post__in'] = $slide_id;
		} elseif( is_numeric( $slide_id ) ) {
			$args['p'] = $slide_id;
		}
        
        // Return no slides if the no slide IDs were requested.
        if( empty( $slide_id ) ) {
            return array();
        }
        
        $cache_key = md5( serialize( $args ) );
        if( is_array( $slide_id ) ) {
            $cache_key.= "--";
            foreach( $slide_id as $_slide_id ) {
                $cache_key.= "-" . $this->get_cache_buster( $_slide_id );
            } 
        } else {
            $cache_key.= "--" . $this->get_cache_buster( $slide_id );
        }
        
        $slides = wp_cache_get( $cache_key, slidedeck2_cache_group( 'slides' ) );
        
        // If a non-cached response was requested, reset the cached result to force a look-up
        if( !$cached ) $slides = false;
        
        if( !$slides ) {
    		$query = new WP_Query( $args );
    		
    		$slides = $query->posts;
            
            wp_cache_set( $cache_key, $slides, slidedeck2_cache_group( 'slides' ) );
        }
		
		// Attach all post meta for each slide to the object
		foreach( $slides as &$slide ) {
			$slide->meta = $this->get_meta( $slide->ID, $cached );
		}
		
		$slides = apply_filters( "{$this->namespace}_get_custom_slides", $slides );
		
        // Only return the first slide if this was a request by ID
		if( is_numeric( $slide_id ) ) {
			$slides = reset( $slides );
		}
		
		return $slides;
	}
	
	/**
	 * Get all meta associated with a slide
	 * 
	 * Retrieves all post meta associated with a slide and returns it as a
	 * key => value array.
	 * 
	 * @param int $slide_id Slide ID
     * @param bool $cached Load using cached results if available
     * 
	 * @global $wpdb
	 * 
	 * @uses wpdb::get_results()
	 * @uses wpdb::prepare()
	 * 
	 * @return array
	 */
	function get_meta( $slide_id, $cached = true ) {
		global $wpdb;
		
		$sql = "SELECT * FROM {$wpdb->postmeta} WHERE post_id = %d";
		$sql = $wpdb->prepare( $sql, $slide_id );
        
        $cache_key = md5( $sql );
        $results = wp_cache_get( $cache_key, slidedeck2_cache_group( 'get-meta-results' ) );
        
        // If a non-cached response was requested, reset the cached result to force a look-up
        if( !$cached ) $results = false;
        
        if( !$results ) {
    		$results = $wpdb->get_results( $sql );
            
            wp_cache_set( $cache_key, $results, slidedeck2_cache_group( 'get-meta-results' ));
        }
		
		$meta = array();
		foreach( $results as $result ) {
			$meta[$result->meta_key] = maybe_unserialize( $result->meta_value );
		}
        
        if( !isset( $meta['_image_scaling'] ) ) {
            $meta['_image_scaling'] = "cover";
        }
		
		return $meta;
	}
	
	/**
	 * Get a count of all the slides attached to a SlideDeck
	 * 
	 * @param int $slidedeck_id SlideDeck ID
	 * 
	 * @global $wpdb
	 * 
	 * @uses wpdb::get_var()
	 * @uses wpdb::prepare()
	 * 
	 * @return integer
	 */
	function get_slide_count( $slidedeck_id ) {
		global $wpdb;
		
		$sql = "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_parent = %d AND post_type = %s";
		$count = (int) $wpdb->get_var( $wpdb->prepare( $sql, $slidedeck_id, $this->post_type ) );
		
		return $count;
	}
	
	/**
	 * Get all slides for a SlideDeck
	 * 
	 * Looks up all slides associated with a SlideDeck and returns an array of the slides
	 * 
	 * @param int $slidedeck_id SlideDeck ID
	 * 
	 * @global $wpdb
	 * 
	 * @uses wpdb::get_col()
	 * @uses wpdb::prepare()
	 * @uses SlideDeckSource::get()
	 * 
	 * @return array
	 */
	function get_slidedeck_slides( $slidedeck_id, $cached = true ) {
		global $wpdb;
		
		$sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_parent = %d;";
		$slide_ids = $wpdb->get_col( $wpdb->prepare( $sql, $this->post_type, $slidedeck_id ) );
		
		$slides = $this->get( $slide_ids, $cached );
		
		return $slides;
	}
	
	/**
	 * Get all templates available to a slide type
	 * 
	 * Looks at all files in a "templates" directory contained within the root folder passed
	 * in to the method. Returns and array keyed of the slugs of the template files.
	 * 
	 * @param $string Root relative filepath to the slide type being loaded
	 * 
	 * @return array
	 */
	function get_templates( $slide_path ) {
		$templates = array();
		
		$slide_path = untrailingslashit( $slide_path );
		$template_path = $slide_path . '/templates';
		
		if( is_dir( $template_path ) ) {
			$template_files = glob( $template_path . "/*.thtml" );
			foreach( $template_files as $template ) {
				$key = basename( substr( $template, 0, strrpos( $template, "." ) ) );
				$templates[$key] = $template;
			}
		}
		
		return $templates;
	}
	
	/**
     * Hook into init to register post types
     * 
     * @uses register_post_type()
     */
	function register_post_types() {
        register_post_type( $this->post_type, array(
            'label' => $this->label,
            'description' => $this->description,
            'public' => false
        ) );
	}
	
	/**
	 * Check slide type validity
	 * 
	 * Checks if the current slide data in an action or filter is of the slide type currently
	 * processing the action or filter.
	 * 
	 * @param string $slide_type The slide type slug
	 * 
	 * @return boolean
	 */
	function is_valid( $slide_type ) {
		$valid = false;
		
		if( isset( $this->name ) ) {
			if( $this->name == $slide_type ) {
				$valid = true;
			}
		}
		
		return $valid;
	}
	
	/**
	 * Update slide order
	 * 
	 * @param array $slide_order Array of slide IDs
	 * 
	 * @uses wp_update_post()
	 */
	function update_order( $slide_order ) {
		$count = 0;
		
		foreach( $slide_order as $slide_id ) {
			$args = array(
				'ID' => $slide_id,
				'menu_order' => $count++
			);
				
			wp_update_post( $args );
		}
	}
}	
