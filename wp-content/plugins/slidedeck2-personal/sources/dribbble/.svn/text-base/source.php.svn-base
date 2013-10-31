<?php
class SlideDeckSource_Dribbble extends SlideDeck {
    var $label = "Dribbble";
    var $name = "dribbble";
    var $taxonomies = array( 'images' );
    var $default_lens = "tool-kit";
    
    var $options_model = array(
        'Setup' => array(
            'dribbble_shots_or_likes' => array(
                'value' => "shots",
                'data' => 'string'
            ),
            'dribbble_username' => array(
                'value' => "moonspired",
                'data' => 'string'
            )
        )
    );
    
    function add_hooks() {
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
    }
    
    /**
     * Get Dribbble Image Feed
     * 
     * Fetches a Dribbble feed, caches it and returns the 
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
        
        switch( $slidedeck['options']['dribbble_shots_or_likes'] ){
            case 'shots':
                $feed_url = 'http://api.dribbble.com/players/' . $slidedeck['options']['dribbble_username'] . '/shots?per_page=' . $slidedeck['options']['total_slides'];
            break;
            case 'likes':
                $feed_url = 'http://api.dribbble.com/players/' . $slidedeck['options']['dribbble_username'] . '/shots/likes?per_page=' . $slidedeck['options']['total_slides'];
            break;
        }
        // Create a cache key
        $cache_key = $slidedeck['id'] . $feed_url . $slidedeck['options']['cache_duration'] . $this->name;
        
        // Attempt to read the cache
        $response = slidedeck2_cache_read( $cache_key );
        
        // If cache doesn't exist
        if( !$response ){
            $response = wp_remote_get( $feed_url, $args );
            
            if( !is_wp_error( $response ) ) {
                // Write the cache
                slidedeck2_cache_write( $cache_key, $response, $slidedeck['options']['cache_duration'] );
            }
        }
        
        $images = array();
        if( !is_wp_error( $response ) && isset( $response['body'] ) ) {
            $response_json = json_decode( $response['body'] );

            foreach( $response_json->shots as $index => $entry ){
                $images[ $index ]['title'] = $entry->title;
                $images[ $index ]['width'] = $entry->width;
                $images[ $index ]['height'] = $entry->height;
                $images[ $index ]['created_at'] = strtotime( $entry->created_at );
                $images[ $index ]['image'] = preg_replace( '/^(http:|https:)/', '', $entry->image_url );
                $images[ $index ]['thumbnail'] = preg_replace( '/^(http:|https:)/', '', $entry->image_teaser_url );
                $images[ $index ]['permalink'] = $entry->url;
                $images[ $index ]['comments_count'] = $entry->comments_count;
                $images[ $index ]['likes_count'] = $entry->likes_count;
                
                $images[ $index ]['author_name'] = $entry->player->name;
                $images[ $index ]['author_username'] = $entry->player->username;
                $images[ $index ]['author_avatar'] = $entry->player->avatar_url;
                $images[ $index ]['author_url'] = 'http://dribbble.com/' . $entry->player->username;
            }
        } else {
            return false;
        }
        
        return $images;
    }
    
    function slidedeck_form_content_source( $slidedeck, $source ) {
        // Fail silently if the SlideDeck is not this type or source
        if( !$this->is_valid( $source ) ) {
            return false;
        }
        
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