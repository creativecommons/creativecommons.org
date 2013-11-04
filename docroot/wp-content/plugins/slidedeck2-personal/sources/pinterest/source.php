<?php
class SlideDeckSource_Pinterest extends SlideDeck {
    var $label = "Pinterest";
    var $name = "pinterest";
    var $taxonomies = array( 'images' );
    var $default_lens = "tool-kit";
    
    var $options_model = array(
        'Setup' => array(
            'pinterest_url' => array(
                'value' => "http://pinterest.com/dtelepathy/",
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
        
        $username = false;
        $board_name = false;
        if( isset( $slidedeck['options']['pinterest_url'] ) && !empty( $slidedeck['options']['pinterest_url'] ) ){
            
            preg_match( '#pinterest\.com/([0-9a-zA-Z\-_]+)/?([0-9a-zA-Z\-_]+)?#', $slidedeck['options']['pinterest_url'], $matches );
            
            if( isset( $matches['1'] ) && !empty( $matches['1'] ) ) {
                // Try Username
                $username = $matches['1'];
                $feed_url = 'http://pinterest.com/' . $username . '/feed.rss';
                if( isset( $matches['2'] ) && !empty( $matches['2'] ) ) {
                    // Try board slug
                    $board_name = $matches['2'];
                    $feed_url = 'http://pinterest.com/' . $username . '/' . $board_name . '/rss';
                }
            }
        }
        
        
        // Create a cache key
        // Set a value attached to this object of the current SlideDeck to access it in the wp_feed_options method
        $this->current_slidedeck = $slidedeck;
        // Add a feed options action for this fetch_feed() call
        add_action( 'wp_feed_options', array( &$this, 'wp_feed_options' ), 10, 2 );
        // Fetch our feed
        $rss = fetch_feed( $feed_url );
        // Remove the feed options modification action
        remove_action( 'wp_feed_options', array( &$this, 'wp_feed_options' ), 10, 2 );
        // Remove the temporary SlideDeck value
        unset( $this->current_slidedeck );
        
        // Only process if there were no errors
        if( !is_wp_error( $rss ) ) {
            // Get the total amount of items in the feed, maximum is the user set total slides option
            $maxitems = $rss->get_item_quantity( $slidedeck['options']['total_slides'] );
            $rss_items = $rss->get_items( 0, $maxitems );
            
            // Loop through each item to build an array of slides
            $counter = 0;
            foreach( $rss_items as $index => $item ){
                $images[ $index ]['title'] = strip_tags( $item->get_content() );
                $images[ $index ]['content'] = $item->get_content();
                $images[ $index ]['excerpt'] = strip_tags( $item->get_content() );
                $images[ $index ]['created_at'] = strtotime( $item->get_date( "Y-m-d H:i:s" ) );
                $images[ $index ]['permalink'] = $item->get_permalink();
                
                $images[ $index ]['author_name'] = $username;
                $images[ $index ]['author_url'] = 'http://pinterest.com/' . $username;
            }
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
            // Loop through all slide nodes to build a structured slides array
            foreach( $slides_nodes as &$slide_nodes ) {
                $slide = array(
                    'source' => $this->name,
                    'title' => $slide_nodes['title'],
                    'created_at' => $slide_nodes['created_at']
                );
                $slide = array_merge( $this->slide_node_model, $slide );
                
                // Look to see if an image is associated with this slide
                
                $has_image = false;
                $images = $SlideDeckPlugin->Lens->parse_html_for_images( $slide_nodes['content'] );
                if( !empty( $images ) ) {
                    $first_image = reset( $images );
                    $has_image = $first_image;
                }
                
                if( $has_image ) {
                    $thumbnail = $has_image;
                    $full_image = preg_replace( '/\.com\/[0-9]+x\//', '.com/600x/', $has_image );
                    $slide['classes'][] = "has-image";
                    $slide['thumbnail'] = $thumbnail;
                    $slide['type'] = "image";
                } else {
                    $slide['classes'][] = "no-image";
                }
                
                $slide_nodes['source'] = $slide['source'];
                $slide_nodes['type'] = $slide['type'];
                
                // Excerpt node
                if( !array_key_exists( 'excerpt', $slide_nodes ) || empty( $slide_nodes['excerpt'] ) )
                    $slide_nodes['excerpt'] = $slide_nodes['content'];
                
                // Truncate excerpt node length
                $excerpt_length = $has_image ? $slidedeck['options']['excerptLengthWithImages'] : $slidedeck['options']['excerptLengthWithoutImages'];
                $slide_nodes['excerpt'] = slidedeck2_stip_tags_and_truncate_text( $slide_nodes['excerpt'], $excerpt_length, "&hellip;" );
                
                // Truncate title node length
                $title_length = $has_image ? $slidedeck['options']['titleLengthWithImages'] : $slidedeck['options']['titleLengthWithoutImages'];
                $slide_nodes['title'] = slidedeck2_stip_tags_and_truncate_text( $slide['title'], $title_length, "&hellip;" );
                
                if( !empty( $slide_nodes['excerpt'] ) ) {
                    $slide['classes'][] = "has-excerpt";
                } else {
                    $slide['classes'][] = "no-excerpt";
                }
                
                if( !empty( $slide_nodes['title'] ) ) {
                    $slide['classes'][] = "has-title";
                } else {
                    $slide['classes'][] = "no-title";
                }
                
                // Set image node
                if( $has_image ) $slide_nodes['image'] = $full_image;
                
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
     * Hook into wp_feed_options action
     * 
     * Hook into the SimplePie feed options object to modify parameters when looking up
     * feeds for RSS based feed SlideDecks.
     * 
     * @uses SimplePie::set_cache_location()
     * @uses SimplePie::set_cache_duration()
     */
    function wp_feed_options( $feed, $url ) {
        $feed->set_cache_duration( $this->current_slidedeck['options']['cache_duration'] * 60 );
    }

}