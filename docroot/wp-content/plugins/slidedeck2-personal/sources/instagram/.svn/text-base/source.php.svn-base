<?php
class SlideDeckSource_Instagram extends SlideDeck {
    var $label = "Instagram";
    var $name = "instagram";
    var $taxonomies = array( 'images', 'social' );
    var $default_lens = "tool-kit";
    
    var $options_model = array(
        'Setup' => array(
            'total_slides' => array(
                'value' => 5,
                'data' => 'integer'
            ),
            'instagram_recent_or_likes' => array(
                'value' => "recent",
                'data' => 'string'
            ),
            'instagram_username' => array(
                'value' => "",
                'data' => 'string'
            ),
            'instagram_access_token' => array(
                'value' => "",
                'data' => 'string'
            )
        )
    );
    
    function add_hooks() {
        add_action( "{$this->namespace}_after_save", array( &$this, 'slidedeck_after_save' ), 10, 4 );
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
        add_action( "wp_ajax_{$this->namespace}_get_instagram_access_token", array( &$this, 'ajax_get_instangram_access_token' ) );
    }
    
    /**
     * AJAX response for redirecting the user to get an Instagram access token
     * 
     * Saves the SlideDeck and dies with an URL to re-direct the user to link their Instagram account
     * and return back to the user's SlideDeck installation with the access token.
     * 
     * @uses wp_die()
     * @uses wp_verify_nonce()
     */
    function ajax_get_instangram_access_token() {
        global $SlideDeckPlugin;
        
        if( !wp_verify_nonce( $_REQUEST['_wpnonce_get_instagram_access_token'], "{$this->namespace}-get-instagram-access-token" ) ) {
            wp_die( __( "You do not have the proper authority to access this page", $this->namespace ) );
        }

        $response = array(
            'valid' => true,
            'url' => ""
        );
        
        $slidedeck_id = intval( $_POST['id'] );
        
        // SlideDeck save command called from $SlideDeckPlugin instance instead of $this to avoid default_options failures due to $this->options_model override
        $slidedeck = $SlideDeckPlugin->SlideDeck->save( $slidedeck_id, $_POST );
        
        if( $slidedeck ) {
            $response['url'] = 'https://instagram.com/oauth/authorize/?client_id=529dede105394ad79dd253e0ec0ac090&redirect_uri=http%3A%2F%2Fwww.slidedeck.com%2Finstagram%3Fautofill_url%3D';
            $response['url'].= base64_encode( $SlideDeckPlugin->action( '&action=edit&slidedeck=' . $slidedeck_id ) ) . '&response_type=code';
        } else {
            $response['valid'] = false;
        }
        
        die( json_encode( $response ) );
    }
    
    function slidedeck_form_content_source( $slidedeck, $source ) {
        // Fail silently if the SlideDeck is not this type or source
        if( !$this->is_valid( $source ) ) {
            return false;
        }
        
        $namespace = $this->namespace;
        
        if( isset( $_GET['token'] ) && !empty( $_GET['token'] ) )
            $token = $_GET['token'];
        else
            $token = $slidedeck['options']['instagram_access_token'];
        
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
     * Get Instagram Image Feed
     * 
     * Fetches an Instagram feed, caches it and returns the 
     * cached result or the results after caching them.
     * 
     * @param string $feed_url The URL of the gplus feed with a JSON response
     * @param integer $slidedeck_id The ID of the deck (for caching)
     * 
     * @return array An array of arrays containing the images and various meta.
     */
    function get_slides_nodes( $slidedeck ){
        $args = array(
            'sslverify' => false
        );
		
        switch( $slidedeck['options']['instagram_recent_or_likes'] ){
            case 'recent':
                // If there are no 
                if( empty( $slidedeck['options']['instagram_username'] ) ){
                    $feed_url = 'https://api.instagram.com/v1/users/self/media/recent?access_token=' . $slidedeck['options']['instagram_access_token'] . '&count=' . $slidedeck['options']['total_slides'];
                }else{
    				$user_id = $this->get_instagram_userid( $slidedeck['options']['instagram_access_token'], $slidedeck['options']['instagram_username'] );
    				if( !empty( $user_id ) ){
    	                $feed_url = 'https://api.instagram.com/v1/users/' . $user_id . '/media/recent?access_token=' . $slidedeck['options']['instagram_access_token'] . '&count=' . $slidedeck['options']['total_slides'];
    				}
                }
            break;
            case 'likes':
                $feed_url = 'https://api.instagram.com/v1/users/self/media/liked?access_token=' . $slidedeck['options']['instagram_access_token'] . '&count=' . $slidedeck['options']['total_slides'];
            break;
        }
		
        // Create a cache key
        $cache_key = $slidedeck['id'] . $feed_url . $slidedeck['options']['cache_duration'] . $this->name;
        
        // Attempt to read the cache
        $response = slidedeck2_cache_read( $cache_key );
        
        if( !$response ) {
            $response = wp_remote_get( $feed_url, $args );
            
            if( !is_wp_error( $response ) ) {
                // Write the cache
                slidedeck2_cache_write( $cache_key, $response, $slidedeck['options']['cache_duration'] );
            }
        }
        
        // Fail if an error occured
        if( is_wp_error( $response ) ) {
            return false;
        }
        
        $response_json = json_decode( $response['body'] );
        
        // If the response JSON was empty, end processing
        if( empty( $response_json ) ) {
            return false;
        }
        
        // If we have no data to process, end processing
        if( !isset( $response_json->data ) ) {
            return false;
        }
        
        $images = array();
        foreach( (array) $response_json->data as $index => $entry ){
            $images[ $index ]['title'] = isset( $entry->caption->text ) ? $entry->caption->text : "";
            //$images[ $index ]['description'] = $entry->caption->text; // Do we need the duped data?
            $images[ $index ]['width'] = $entry->images->standard_resolution->width;
            $images[ $index ]['height'] = $entry->images->standard_resolution->height;
            $images[ $index ]['created_at'] = $entry->created_time;
            $images[ $index ]['image'] = preg_replace( '/^(http:|https:)/', '', $entry->images->standard_resolution->url );
            $images[ $index ]['thumbnail'] = preg_replace( '/^(http:|https:)/', '', $entry->images->thumbnail->url );
            $images[ $index ]['permalink'] = $entry->link;
            $images[ $index ]['comments_count'] = $entry->comments->count;
            $images[ $index ]['likes_count'] = $entry->likes->count;
            $images[ $index ]['author_name'] = $entry->user->full_name;
            $images[ $index ]['author_username'] = $entry->user->username;
            $images[ $index ]['author_avatar'] = $entry->user->profile_picture;
        }


        return $images;
    }

	function get_instagram_userid( $token, $username ){
        $args = array(
            'sslverify' => false
        );
		
		// We do the extra trimming and URL encoding because technically... it's a search.
		$feed_url = 'https://api.instagram.com/v1/users/search?access_token=' . $token . '&q=' . urlencode( trim( $username ) ) . '&count=1';
        
        // Create a cache key
        $cache_key = 'instagram-search' . $username;
        
        // Attempt to read the cache
        $response = slidedeck2_cache_read( $cache_key );
        
        // If cache doesn't exist
        if( !$response ){
            $response = wp_remote_get( $feed_url, $args );
            
            if( !is_wp_error( $response ) ) {
                // Write the cache
                slidedeck2_cache_write( $cache_key, $response, 1440 );
            }
        }
		
        // Fail if an error occured
        if( is_wp_error( $response ) ) {
            return false;
        }
        
        $response_json = json_decode( $response['body'] );
		return (string) $response_json->data[0]->id;
	}
    
    /**
     * SlideDeck After Save hook-in
     * 
     * Saves additional data for this Deck type when saving a SlideDeck
     * 
     * @param integer $id The ID of the SlideDeck being saved
     * @param array $data The data submitted containing information about the SlideDeck to the save method
     * @deprecated @param string $deprecated The type of SlideDeck being saved
     * @param string $source The source of the SlideDeck
     * 
     * @uses SlideDeck::get()
     * @uses get_post_meta()
     * @uses update_post_meta()
     */
    function slidedeck_after_save( $id, $data, $deprecated, $source ) {
        if( $this->is_valid( $source ) ) {
    		// Save the API Key for later use...
    		if( !empty( $data['options']['instagram_access_token'] ) ) {
    			update_option( $this->namespace . '_last_saved_instagram_access_token', $data['options']['instagram_access_token'] );
    		}
        }
    }
    
    /**
     * SlideDeck default options hook-in
     * 
     * @param array $options The SlideDeck Options
     * @deprecated @param string $deprecated The SlideDeck Type
     * @param string $lens The SlideDeck Lens
     * @param string $source The SlideDeck source
     * 
     * @return array
     */
    function slidedeck_default_options( $options, $deprecated, $lens, $source ) {
        if( $this->is_valid( $source ) ) {
            // Check for last_saved_instagram_access_token
            if( $last_saved_instagram_access_token = get_option( $this->namespace . '_last_saved_instagram_access_token' ) ){
                $options['instagram_access_token'] = $last_saved_instagram_access_token;
            }
        }
        
        return $options;
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
     * @uses Legacy::get_slides()
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
        if( is_array( $slides_nodes ) ){
            foreach( $slides_nodes as &$slide_nodes ) {
                $slide = array(
                	'source' => $this->name,
                    'title' => $slide_nodes['title'],
                    'classes' => array( 'has-image' ),
                    'thumbnail' => (string) $slide_nodes['thumbnail'],
                    'created_at' => $slide_nodes['created_at'],
                    'type' => "image"
                );
                $slide = array_merge( $this->slide_node_model, $slide );
                
	            $slide_nodes['source'] = $slide['source'];
	            $slide_nodes['type'] = $slide['type'];
				
                // Build an in-line style tag if needed
                if( !empty( $slide_styles ) ) {
                    foreach( $slide_styles as $property => $value ) {
                        $slide['styles'] .= "{$property}:{$value};";
                    }
                }
                
                $slide['title'] = $slide_nodes['title'] = slidedeck2_stip_tags_and_truncate_text( $slide_nodes['title'], $slidedeck['options']['titleLengthWithImages'], "&hellip;" );
                $slide_nodes['content'] = isset( $slide_nodes['description'] ) ? $slide_nodes['description'] : "";
                $slide_nodes['excerpt'] = slidedeck2_stip_tags_and_truncate_text( $slide_nodes['content'], $slidedeck['options']['excerptLengthWithImages'], "&hellip;" );
                
				if( !empty( $slide_nodes['title'] ) ) {
					$slide['classes'][] = "has-title";
				} else {
					$slide['classes'][] = "no-title";
				}
				
				if( !empty( $slide_nodes['description'] ) ) {
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
        }
        
        return $slides;
    }
    
    /**
     * slidedeck_options_model hook-in
     * 
     * @param array $options_model The Options Model
     * @param string $slidedeck The SlideDeck object
     * 
     * @return array
     */
    function _slidedeck_options_model( $options_model, $slidedeck ) {
        if( $this->is_valid( $slidedeck['source'] ) ) {            // Check for last_saved_instagram_access_token
            $last_saved_instagram_access_token = get_option( $this->namespace . '_last_saved_instagram_access_token' );
            if( !empty( $last_saved_instagram_access_token ) ){
                $options['instagram_access_token'] = $last_saved_instagram_access_token;
            }
        }

        return $options_model;
    }
}