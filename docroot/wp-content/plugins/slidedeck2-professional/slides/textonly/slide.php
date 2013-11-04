<?php
class SlideDeckSlideType_TextOnly extends SlideDeckSlideModel {
	var $name = "textonly";
	var $label = "Text";
	
	var $defaults = array(
		'_caption_position' => "top",
		'_layout' => "basic",
        '_permalink' => ""
	);
	
	// Available layouts
	var $layouts = array(
		'basic' => "Basic",
		'multi-column' => "Multi-Column",
		'block-quote' => "Block Quote"
	);
	
	function __construct() {
		$this->filepath = dirname( __FILE__ );
		$this->url = SLIDEDECK2_PROFESSIONAL_URLPATH . '/slides/textonly';
		$this->thumbnail = $this->url . '/thumbnail.png';
        $this->thumbnail_small = $this->url . '/thumbnail-small.png';
        $this->slide_default_thumbnail = $this->url . '/default-thumbnail.jpg';
		
		add_action( 'init', array( &$this, 'register_assets' ) );
		add_action( "{$this->namespace}_after_create_slide", array( &$this, 'slidedeck_after_create_slide' ), 10, 3 );
        add_action( "{$this->namespace}_change_slide_type", array( &$this, 'slidedeck_change_slide_type' ), 10, 3 );
		add_action( "{$this->namespace}_custom_slide_editor_form", array( &$this, 'slidedeck_custom_slide_editor_form' ), 10, 2 );
		add_action( "{$this->namespace}_update_slide", array( &$this, 'slidedeck_update_slide' ), 10, 2 );
        
		add_filter( "{$this->namespace}_custom_slide_nodes", array( &$this, 'slidedeck_slide_nodes' ), 10, 3 );
	}

	/**
	 * Hook into WordPress init action
	 * 
	 * Register any needed assets for this slide type
	 */
	function register_assets() {
		wp_register_style( "slidedeck-slide-{$this->name}", $this->url . '/slide.css', array(), '1.0', "screen" );
	}
	
	/**
	 * Hook into slidedeck_after_create_slide action
	 * 
	 * Add additional meta options when creating a new slide
	 * 
	 * @param object $slide Slide object
	 * @param int $slidedeck_id SlideDeck ID
	 */
	function slidedeck_after_create_slide( $slide, $slidedeck_id ) {
		if( $this->is_valid( $slide->meta['_slide_type'] ) ) {
			foreach( $this->defaults as $key => $val ) {
				update_post_meta( $slide->ID, $key, $val );
			}
		}
	}
    
    /**
     * Hook into slidedeck_change_slide_type action
     * 
     * Update available slide meta for the new slide type
     * 
     * @param object $slide The Slide object
     * @param string $slide_type The slide type being changed to
     */
    function slidedeck_change_slide_type( $slide, $slide_type ) {
        if( $this->is_valid( $slide_type ) ) {
            foreach( $this->defaults as $key => $val ) {
                if( !isset( $slide->meta[$key] ) || in_array( $key, array( '_layout', '_caption_position' ) ) ) {
                    update_post_meta( $slide->ID, $key, $val );
                }
            }
        }
    }
		
	/**
	 * Hook into slidedeck_custom_slide_editor_form action
	 * 
	 * Output the editing form for the slide editor modal for this slide type
	 * 
	 * @param object $slide The Slide object
	 * @param array $slidedeck The SlideDeck object
	 */
	function slidedeck_custom_slide_editor_form( $slide, $slidedeck ) {
		if( !$this->is_valid( $slide->meta['_slide_type'] ) ) {
			return false;
		}
		
		$namespace = $this->namespace;
		$layouts = $this->layouts;
        $url = $this->url;
		
        if ( ! class_exists( '_WP_Editors' ) )
            require( ABSPATH . WPINC . '/class-wp-editor.php' );
        
		include( dirname( __FILE__ ) . '/views/show.php' );
	}
	
	/**
	 * Hook into slidedeck_slide_nodes filter
	 * 
	 * Add additional nodes to the slide when rendering SlideDecks
	 * 
	 * @param array $slide_nodes Array of slide nodes
	 * @param object $slide The slide object itself
	 * @param array $slidedeck The SlideDeck
	 * 
	 * @return array
	 */
	function slidedeck_slide_nodes( $slide_nodes, $slide, $slidedeck ) {
		global $SlideDeckPlugin;
		
		if( $this->is_valid( $slide->meta['_slide_type'] ) ) {
			$slide_nodes['content'] = $slide_nodes['excerpt'] = $slide->post_excerpt;
            
	        $sizes = apply_filters( "{$this->namespace}_sizes", $SlideDeckPlugin->sizes, $slidedeck );
	        $width = ( $slidedeck['options']['size'] != "custom" ? $sizes[$slidedeck['options']['size']]['width'] : $slidedeck['options']['width'] );
	        $height = ( $slidedeck['options']['size'] != "custom" ? $sizes[$slidedeck['options']['size']]['height'] : $slidedeck['options']['height'] );
		}
		
		return $slide_nodes;
	}
	
	/**
	 * Hook into slidedeck_update_slide action
	 * 
	 * Save image data when the edit form is submitted.
	 * 
	 * @param object $slide Slide object
     * @param array $data Santized $_POST data
     * 
     * @global $SlideDeckPlugin
     * 
     * @uses SlideDeck::get_video_meta_from_url()
     * @uses SlideDeckSlide::is_valid()
     * @uses update_post_meta()
     * @uses wp_update_post()
	 */
	function slidedeck_update_slide( $slide, $data ) {
	    global $SlideDeckPlugin;
        
		if( $this->is_valid( $slide->meta['_slide_type'] ) ) {
			update_post_meta( $slide->ID, "_layout", $data['_layout'] );
            update_post_meta( $slide->ID, "_permalink", $data['_permalink'] );
            
			$post_excerpt = strip_tags( $data['post_excerpt'], "<p><a><strong><b><i><em><del><span><sup><sub><ul><ol><li><h1><h2><h3><h4><h5><h6><pre>" );
			$post_title = strip_tags( $data['post_title'] );
			
			$args = array(
				'ID' => $slide->ID,
				'post_title' => $post_title,
				'post_excerpt' => $post_excerpt
			);
			wp_update_post( $args );
		}
	}
}
