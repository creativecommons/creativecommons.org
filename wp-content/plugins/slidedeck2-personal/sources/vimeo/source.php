<?php
class SlideDeckSource_Vimeo extends SlideDeck {
    var $label = "Vimeo Videos";
    var $name = "vimeo";
    var $default_lens = "tool-kit";
    var $taxonomies = array( 'videos' );
    
    var $default_options = array(
        'cache_duration' => 1800 // seconds
    );
    
    var $options_model = array(
        'Setup' => array(
            'vimeo_username' => array(
                'value' => 'madebyhand'
            ),
            'vimeo_album' => array(
                'value' => 'recent'
            )
        )
    );
            
    function add_hooks() {
        add_action( 'wp_ajax_update_vimeo_playlists', array( &$this, 'wp_ajax_update_vimeo_playlists' ) );
        add_action( 'wp_ajax_update_video_thumbnail', array( &$this, 'wp_ajax_update_video_thumbnail' ) );
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
    }
    
    /**
     * Ajax function to get the user's playlists
     * 
     * @return string A <select> element containing the playlists.
     */
    function wp_ajax_update_vimeo_playlists() {
        $vimeo_username = $_REQUEST['vimeo_username'];
        
        echo $this->get_vimeo_playlists_from_username( $vimeo_username );
        exit;
    }
    
    /**
     * Ajax function to get the video's thumbnail
     * 
     * @return string an image URL.
     */
    function wp_ajax_update_video_thumbnail() {
        $video_url = $_REQUEST['video_url'];
        
        echo $this->get_video_thumbnail( $video_url );
        exit;
    }
	
    function get_vimeo_playlists_from_username( $user_id = false, $slidedeck = null ){
        $playlists = false;
        
        $args = array(
            'sslverify' => false
        );
        
        $feed_url = "http://vimeo.com/api/v2/{$user_id}/albums.json";
        
        if( isset( $user_id ) && !empty( $user_id ) ){
            // Attempt to read the cache (no cache here)
            $playlists = false;
            
            // If cache doesn't exist
            if( !$playlists ){
                $playlists = array();
                
                $response = wp_remote_get( $feed_url, $args );
                if( !is_wp_error( $response ) ) {
                    $response_json = json_decode( $response['body'] );
                    
                    if( !empty( $response_json ) ){
                        foreach( $response_json as $key => $entry ){
                            $playlists[ ] = array(
                                'href' => "http://vimeo.com/api/v2/album/{$entry->id}/videos.json",
                                'title' => $entry->title,
                                'created' => $entry->created_on,
                                'updated' => $entry->last_modified
                            );
                        }
                    }
                }else{
                    return false;
                }
            }
        }

        // Vimeo User playlists Call
        $playlists_select = array( 
            'recent' => __( 'Recent Uploads', $this->namespace )
        );

        if( $playlists ){
            foreach( $playlists as $playlist ){
                $playlists_select[ $playlist['href'] ] = $playlist['title'];
            }
        }
        
        $html_input = array(
            'type' => 'select',
            'label' => "Vimeo Album",
            'attr' => array( 'class' => 'fancy' ),
            'values' => $playlists_select
        );
        
        return slidedeck2_html_input( 'options[vimeo_album]', $slidedeck['options']['vimeo_album'], $html_input, false ); 
    }

    /**
     * Load all slides associated with this SlideDeck
     * 
     * @param integer $slidedeck_id The ID of the SlideDeck being loaded
     * 
     * @uses WP_Query
     * @uses get_the_title()
     * @uses maybe_unserialize()
     */
    function get_slides_nodes( $slidedeck ) {
        $args = array(
            'sslverify' => false
        );
        $slidedeck_id = $slidedeck['id'];
        
        if( isset( $slidedeck['options']['vimeo_album'] ) && !empty( $slidedeck['options']['vimeo_album'] ) ) {
            switch( $slidedeck['options']['vimeo_album'] ){
                case 'recent':
                    $feed_url = 'http://vimeo.com/api/v2/' . $slidedeck['options']['vimeo_username'] . '/videos.json?page=1';
                break;
                default:
                    // Feed of the Playlist's Videos
                    $feed_url = $slidedeck['options']['vimeo_album'];
                break;
            }
            
            // Create a cache key
            $cache_key = $slidedeck_id . $feed_url . $slidedeck['options']['cache_duration'] . $slidedeck['options']['total_slides'] . $this->name;
            
            $response = slidedeck2_cache_read( $cache_key );
            
            if( !$response ) {
                $response = wp_remote_get( $feed_url, $args );
                
                if( !is_wp_error( $response ) ) {
                    // Write the cache if a valid response
                    if( !empty( $response ) ) {
                        slidedeck2_cache_write( $cache_key, $response, $slidedeck['options']['cache_duration'] );
                    }
                }
            }
            
            // Fail if an error occured
            if( is_wp_error( $response ) ) {
                return false;
            }
            
            $response_json = json_decode( $response['body'] );
            
            // Fallback fail if response was empty
            if( empty( $response_json ) ) {
                return false;
            }
            
            $count = 0;
            foreach( $response_json as $key => $entry ){
                if( $count < $slidedeck['options']['total_slides'] ){
                    $videos[$key]['created_at'] = strtotime( $entry->upload_date );
                    $videos[$key]['author_name'] = $entry->user_name;
                    $videos[$key]['author_url'] = $entry->user_url;
                    $videos[$key]['author_avatar'] = $entry->user_portrait_small;
                    
                    $videos[$key]['video_meta'] = $this->get_video_meta_from_url( $entry->url );
                }
                
                $count++;
            }
        }
        
        return $videos;
    }

    function slidedeck_form_content_source( $slidedeck, $source ) {
        // Fail silently if the SlideDeck is not this type or source
        if( !$this->is_valid( $source ) ) {
            return false;
        }
        
        $playlists_select = $this->get_vimeo_playlists_from_username( $slidedeck['options']['vimeo_username'], $slidedeck );
        
        include( dirname( __FILE__ ) . '/views/show.php' );
    }
    
    /**
     * Hook into slidedeck_get_source_file_basedir filter
     * 
     * Modifies the source's basedir value for relative file referencing
     * 
     * @param string $basedir The defined base directory
     * @param string $source_slug The slug of the source being requested
     * 
     * @uses SlideDeck::is_valid()
     * 
     * @return string
     */
    function slidedeck_get_source_file_basedir( $basedir, $source_slug ) {
        if( $this->is_valid( $source_slug ) ) {
            $basedir = dirname( __FILE__ );
        }
        
        return $basedir;
    }
    
    /**
     * Hook into slidedeck_get_source_file_baseurl filter
     * 
     * Modifies the source's basedir value for relative file referencing
     * 
     * @param string $baseurl The defined base directory
     * @param string $source_slug The slug of the source being requested
     * 
     * @uses SlideDeck::is_valid()
     * 
     * @return string
     */
    function slidedeck_get_source_file_baseurl( $baseurl, $source_slug ) {
        if( $this->is_valid( $source_slug ) ) {
           $baseurl = SLIDEDECK2_URLPATH . '/sources/' . basename( dirname( __FILE__ ) );
        }
        
        return $baseurl;
    }
    
    /**
     * Render slides for SlideDecks of this type
     * 
     * Loads the slides associated with this SlideDeck if it matches this Deck type and returns
     * a string of HTML markup.
     * 
     * @param array $slides_arr Array of slides
     * @param object $slidedeck SlideDeck object
     * 
     * @global $SlideDeckPlugin
     * 
     * @uses SlideDeckPlugin::process_slide_content()
     * @uses Legacy::get_slides_nodes()
     * 
     * @return string
     */
    function slidedeck_get_slides( $slides, $slidedeck ) {
        global $SlideDeckPlugin;
        
        // Fail silently if not this Deck type
        if( !$this->is_valid( $slidedeck['source'] ) ) {
            return $slides;
        }
        
        // How many decks are on the page as of now.
        $deck_iteration = 0;
        if( isset( $SlideDeckPlugin->SlideDeck->rendered_slidedecks[ $slidedeck['id'] ] ) )
        	$deck_iteration = $SlideDeckPlugin->SlideDeck->rendered_slidedecks[ $slidedeck['id'] ];
        
        // Slides associated with this SlideDeck
        $slides_nodes = $this->get_slides_nodes( $slidedeck );
        
        $slide_counter = 1;
        foreach( (array) $slides_nodes as $slide_nodes ) {
            $slide = array(
                'source' => $this->name,
                'title' => $slide_nodes['video_meta']['title'],
                'thumbnail' => (string) $slide_nodes['video_meta']['thumbnail'],
                'created_at' => $slide_nodes['created_at'],
                'classes' => array( 'has-image' ),
                'type' => 'video'
            );
            $slide = array_merge( $this->slide_node_model, $slide );
            
            $slide_nodes['source'] = $slide['source'];
            $slide_nodes['type'] = $slide['type'];
            
            // In-line styles to apply to the slide DD element
            $slide_styles = array();
            $slide_nodes['slide_counter'] = $slide_counter;
            $slide_nodes['deck_iteration'] = $deck_iteration;
            
            $slide['title'] = $slide_nodes['title'] = slidedeck2_stip_tags_and_truncate_text( $slide_nodes['video_meta']['title'], $slidedeck['options']['titleLengthWithImages'] );
            $slide_nodes['permalink'] = $slide_nodes['video_meta']['permalink'];
            $slide_nodes['excerpt'] = slidedeck2_stip_tags_and_truncate_text( $slide_nodes['video_meta']['description'], $slidedeck['options']['excerptLengthWithImages'] );
            $slide_nodes['image'] = $slide_nodes['video_meta']['full_image'];
            
            // Build an in-line style tag if needed
            if( !empty( $slide_styles ) ) {
                foreach( $slide_styles as $property => $value ) {
                    $slide['styles'] .= "{$property}:{$value};";
                }
            }
            
			if( !empty( $slide['title'] ) ) {
				$slide['classes'][] = "has-title";
			} else {
				$slide['classes'][] = "no-title";
			}
			
			if( !empty( $slide_nodes['video_meta']['description'] ) ) {
				$slide['classes'][] = "has-excerpt";
			} else {
				$slide['classes'][] = "no-excerpt";
			}
			
            // Set link target node
            $slide_nodes['target'] = $slidedeck['options']['linkTarget'];
            
            $slide['content'] = $SlideDeckPlugin->Lens->process_template( $slide_nodes, $slidedeck );
            
            $slide_counter++;
            
            $slides[] = $slide;
        }
        
        return $slides;
    }
}