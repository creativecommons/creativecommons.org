<?php
class SlideDeckSource_FiveHundredPixels extends SlideDeck {
    var $label = "500px";
    var $name = "fivehundredpixels";
    var $taxonomies = array( 'images' );
    var $default_lens = "tool-kit";
	var $client_id = 'AoTls3vzhhzi9Lmr4w1L2SB5OZp3d3ZoMGbwzGfZ';
    
    var $options_model = array(
        'Setup' => array(
            'fivehundredpixels_username' => array(
                'value' => "slidedeck",
                'data' => 'string'
            ),
            'fivehundredpixels_feed_type' => array(
                'value' => "user",
                'data' => 'string'
            ),
            'fivehundredpixels_category' => array(
                'value' => "Any Category",
                'data' => 'string'
            )
        )
    );
    
    function add_hooks() {
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
    }
    
    /**
     * Get FiveHundredPixels Image Feed
     * 
     * Fetches a FiveHundredPixels feed, caches it and returns the 
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
        
		$max_images = min( $slidedeck['options']['total_slides'], 20 ); // Respect thy API! http://developers.500px.com/docs/terms
		$image_size = 4;
		$sort = 'created_at';
        $feed_type = $slidedeck['options']['fivehundredpixels_feed_type'];
		
		if( $slidedeck['options']['fivehundredpixels_category'] == 'Any Category' ){
	        $feed_url = 'https://api.500px.com/v1/photos?feature=' . $feed_type . '&rpp=' . $max_images . '&sort=' . $sort . '&image_size=' . $image_size . '&username=' . $slidedeck['options']['fivehundredpixels_username'] . '&consumer_key=' . $this->client_id;
		}else{
			$feed_url = 'https://api.500px.com/v1/photos?feature=' . $feed_type . '&rpp=' . $max_images . '&only=' . urlencode( $slidedeck['options']['fivehundredpixels_category'] ) . '&sort=' . $sort . '&image_size=' . $image_size . '&username=' . $slidedeck['options']['fivehundredpixels_username'] . '&consumer_key=' . $this->client_id;
		}

        // Create a cache key
        $cache_key = $slidedeck['id'] . $feed_url . $slidedeck['options']['cache_duration'] . $this->name;
        
        // Attempt to read the cache
        $images = slidedeck2_cache_read( $cache_key );
        
        // If cache doesn't exist
        if( !$images ){
            $images = array();
            
            $response = wp_remote_get( $feed_url, $args );
            if( !is_wp_error( $response ) ) {
                $response_json = json_decode( $response['body'] );
                
                if( isset( $response_json->photos ) ) {
                    foreach( $response_json->photos as $index => $entry ){
                    	$large_image = $entry->image_url;
                    	$small_image = str_replace( '/4.', '/1.', $entry->image_url );
    					$avatar = ( preg_match( '/^http/', $entry->user->userpic_url ) ) ? $entry->user->userpic_url : false ;
    					
                        $images[ $index ]['title'] = $entry->name;
    					$images[ $index ]['description'] = $entry->description;
                        $images[ $index ]['width'] = $entry->width;
                        $images[ $index ]['height'] = $entry->height;
                        $images[ $index ]['created_at'] = strtotime ( $entry->created_at );
                        $images[ $index ]['image'] = $large_image;
                        $images[ $index ]['thumbnail'] = $small_image;
                        $images[ $index ]['permalink'] = 'http://500px.com/photo/' . $entry->id;
                        $images[ $index ]['comments_count'] = $entry->comments_count;
                        $images[ $index ]['likes_count'] = $entry->favorites_count;
                        
                        $images[ $index ]['author_name'] = $entry->user->fullname;
                        $images[ $index ]['author_username'] = $entry->user->username;
                        $images[ $index ]['author_url'] = 'http://500px.com/' . $entry->user->username;
                        $images[ $index ]['author_avatar'] = $avatar;
                    }
                }
            }else{
                return false;
            }
            // Write the cache
            slidedeck2_cache_write( $cache_key, $images, $slidedeck['options']['cache_duration'] );
        }
        return $images;
    }
    
    function slidedeck_form_content_source( $slidedeck, $source ) {
        // Fail silently if the SlideDeck is not this type or source
        if( !$this->is_valid( $source ) ) {
            return false;
        }
        
        $feed_types = array(
            'user' => __( "My Photos", $this->namespace ),
            'user_favorites' => __( "Photos I've favorited", $this->namespace ),
            'user_friends' => __( "Photos from my friends", $this->namespace )
        );
        
		$categories = array(
			'all' => 'Any Category',
			0 => 'Uncategorized',
			10 => 'Abstract',
			11 => 'Animals',
			5 => 'Black and White',
			1 => 'Celebrities',
			9 => 'City and Architecture',
			15 => 'Commercial',
			16 => 'Concert',
			20 => 'Family',
			14 => 'Fashion',
			2 => 'Film',
			24 => 'Fine Art',
			23 => 'Food',
			3 => 'Journalism',
			8 => 'Landscapes',
			12 => 'Macro',
			18 => 'Nature',
			4 => 'Nude',
			7 => 'People',
			19 => 'Performing Arts',
			17 => 'Sport',
			6 => 'Still Life',
			21 => 'Street',
			26 => 'Transporation',
			13 => 'Travel',
			22 => 'Underwater',
			27 => 'Urban Exploration',
			25 => 'Wedding'
		);
		
		$categories = array_combine( array_values( $categories ), array_values( $categories ) );
		
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
                
                // Build an in-line style tag if needed
                if( !empty( $slide_styles ) ) {
                    foreach( $slide_styles as $property => $value ) {
                        $slide['styles'] .= "{$property}:{$value};";
                    }
                }
	            
	            $slide_nodes['source'] = $slide['source'];
	            $slide_nodes['type'] = $slide['type'];
                
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
}