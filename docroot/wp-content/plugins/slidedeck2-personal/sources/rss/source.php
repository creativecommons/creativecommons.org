<?php
class SlideDeckSource_RSS extends SlideDeck {
    var $label = "RSS Feeds";
    var $name = "rss";
    var $default_lens = "tool-kit";
    var $taxonomies = array( 'feeds', 'posts' );
    
    // Default configuration options for this source
    var $default_options =  array(
        'feedUrl' => "http://feeds.feedburner.com/dtelepathy"
    );
    
    var $options_model = array(
        'Setup' => array(
            'rssImageSource' => array(
                'name' => "rssImageSource",
                'type' => "select",
                'data' => "string",
                'value' => 'enclosure',
                'values' => array(
                	'none' => "No Image",
                    'content' => "First image in content",
                    'enclosure' => "Media attachment (RSS only)"
                ),
                'attr' => array(
                    'class' => "fancy"
                ),
                'label' => "Preferred Image Source",
                'description' => "Preferred location where an image be pulled from (will automatically fall back to other sources if none are found in the preferred location)",
                'weight' => 70
            ),
            'rssValidateImages' => array(
            	'name' => 'rssValidateImages',
                'type' => 'radio',
                'data' => 'boolean',
                'value' => true,
                'attr' => array(
                	'class' => "fancy"
				),
                'label' => "Validate Images",
                'description' => "Helps with some website feeds that include advertisement pixel images in their posts",
                'weight' => 80
            )
        )
    );
    
    function add_hooks() {
        add_filter( "{$this->namespace}_classes", array( &$this, 'slidedeck_classes' ), 10, 2 );
        add_filter( "{$this->namespace}_frame_classes", array( &$this, 'slidedeck_frame_classes' ), 10, 2 );
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
    }

    /**
     * Get the image for a slide
     * 
     * Looks up the type of image that is supposed to be retrieved and returns its URL or boolean(false) if
     * no image could be found.
     * 
     * @param array $slide The slide object to process
     * @param array $slidedeck The SlideDeck object to process
     * @param string $source Optional image source
     * 
     * @return mixed
     */
    function get_image( $slide, $slidedeck, $source = null, $tried_sources = array() ) {
        global $SlideDeckPlugin;
        
        // Set default return value
        $image_src = false;
        
        // If the image is actually set already, just use it.
        if( isset( $slide['image'] ) && !empty( $slide['image'] ) ){
            $image_src = $slide['image'];
            return $image_src;
        }
        
        if( !isset( $slidedeck['options']['rssImageSource'] ) )
            $slidedeck['options']['rssImageSource'] = "content";
		
		// Just return boolean(false) if the user doesn't want any images
		if( $slidedeck['options']['rssImageSource'] == "none" )
			return false;
        
        $sources = array( 'content', 'enclosure' );
        
        if( !isset( $source ) )
            $source = $slidedeck['options']['rssImageSource'];
        
        switch( $source ) {
            default:
            case "content":
                $images = $SlideDeckPlugin->Lens->parse_html_for_images( $slide['content'] );
                
                if( !empty( $images ) ) {
                    $first_image = reset( $images );
                    $image_src = $first_image;
                }
            break;
            
            case "enclosure":
                if( isset( $slide['enclosures'] ) ) {
                    $previous_diff = 9999;
                    foreach( $slide['enclosures'] as $enclosure ) {
                        $enclosure_width = (integer) $enclosure->get_width();
                        
                        $slidedeck_lens = $SlideDeckPlugin->Lens->get( $slidedeck['lens'] );
                        $slidedeck_width = (integer) $slidedeck['options']['size'] != "custom" ? $slidedeck_lens['meta']['sizes'][$slidedeck['options']['size']]['width'] : $slidedeck['options']['width'];
                        
                        $this_diff = abs( $slidedeck_width - $enclosure_width );
                        
                        if( $this_diff < $previous_diff ) {
                            $previous_diff = $this_diff;
                            $image_src = $enclosure->get_link();
                        }
                        
                        if( $slidedeck_width < $enclosure_width ) {
                            $image_src = $enclosure->get_link();
                        }
                    }
                }
            break;
        }
        
        if( $image_src == false ) {
            $tried_sources[] = $source;
            // Only try other sources if we haven't tried them all
            if( count( array_intersect( $sources, $tried_sources ) ) < count( $sources ) ) {
                // Loop through sources to find an untried source to try
                $next_source = false;
                foreach( $sources as $untried_source ) {
                    if( !in_array( $untried_source, $tried_sources ) ) {
                        $next_source = $untried_source;
                    }
                }
                
                if( $next_source ) {
                    $image_src = $this->get_image( $slide, $slidedeck, $next_source, $tried_sources );
                }
            }
        }
        
        $image_src = $SlideDeckPlugin->Lens->test_image_for_ads_and_tracking( $image_src );
        
        return $image_src;
    }

    /**
     * Load slides for RSS feed sourced SlideDecks
     * 
     * @uses fetch_feed()
     * 
     * @return array
     */
    function get_slides( $slidedeck ) {
        $slides = array();
        
        // Parse the textarea for newline separated URLs
        $feed_urls = array();
        $feed_urls_exploded = explode( "\n", $slidedeck['options']['feedUrl'] );
        foreach( $feed_urls_exploded as $feed_url ) {
            $trimmed_url = trim( $feed_url );
            $escaped_url = wp_specialchars_decode( esc_url( $trimmed_url ) );
            if( !empty( $escaped_url ) ) {
                $feed_urls[] = $escaped_url;
            }
        }
        
        // Set a reference to the current SlideDeck for reference in actions
        $this->__transient_slidedeck = &$slidedeck;
        // Add a feed options action for this fetch_feed() call
        add_action( 'wp_feed_options', array( &$this, 'wp_feed_options' ), 10, 2 );
        // Fetch our feed
        $rss = fetch_feed( $feed_urls );
        // Remove the feed options modification action
        remove_action( 'wp_feed_options', array( &$this, 'wp_feed_options' ), 10, 2 );
        // Unset the SlideDeck reference
        unset( $this->__transient_slidedeck );
        
        // Only process if there were no errors
        if( !is_wp_error( $rss ) ) {
            // Get the total amount of items in the feed, maximum is the user set total slides option
            $maxitems = $rss->get_item_quantity( $slidedeck['options']['total_slides'] );
            $rss_items = $rss->get_items( 0, $maxitems );
            
            // Loop through each item to build an array of slides
            foreach( $rss_items as &$item ) {
                $author = $item->get_author();
                
                $slide = array(
                    'id' => $item->get_id(),
                    'title' => $item->get_title(),
                    'permalink' => $item->get_permalink(),
                    'author_name' => isset( $author ) ? $item->get_author()->get_name() : "",
                    'author_url' => isset( $author ) ? $item->get_author()->get_link() : "",
                    'author_email' => isset( $author ) ? $item->get_author()->get_email() : "",
                    'author_avatar' => isset( $author ) ? slidedeck2_get_avatar( $item->get_author()->get_email() ) : "",
                    'content' => $item->get_content(),
                    'excerpt' => strip_tags( $item->get_content(), "<b><strong><i><em><a>" ),
                    'created_at' => strtotime( $item->get_date( "Y-m-d H:i:s" ) ),
                    'local_created_at' => $item->get_local_date(),
                    'latitude' => $item->get_latitude(),
                    'longitude' => $item->get_longitude()
                );
                
                if( $enclosures = $item->get_enclosures() ) {
                    $slide['enclosures'] = $enclosures;
                }
                
                $slides[] = $slide;
            }
        }
        
        return $slides;
    }

    /**
     * SlideDeck element class hook-in
     * 
     * Add additional variation classes to the SlideDeck element
     * 
     * @return array
     */
    function slidedeck_classes( $slidedeck_classes, $slidedeck ) {
        if( $this->is_valid( $slidedeck['source'] ) ) {
            if( !empty( $slidedeck['options']['lensVariation'] ) ) {
                $slidedeck_classes[] = $slidedeck['options']['lensVariation'];
            }
        }
        
        return $slidedeck_classes;
    }
    
    /**
     * Content Source form section for Posts
     * 
     * Loads necessary data for sourcing a SlideDeck based off of Posts and renders out
     * the form interaction.
     * 
     * @param array $slidedeck The SlideDeck object
     * @param object $source The source object
     * 
     * @uses get_post_types()
     * @uses current_theme_supports()
     */
    function slidedeck_form_content_source( $slidedeck, $source ) {
        // Fail silently if the SlideDeck is not this type or source
        if( !$this->is_valid( $source ) ) {
            return false;
        }
        
        $slidedeck['options'] = array_merge( $this->default_options, $slidedeck['options'] );
        $namespace = $this->namespace;
        
        include( dirname( __FILE__ ) . '/views/show.php' );
    }

    /**
     * Add appropriate classes for this Lens to the SlideDeck frame
     * 
     * @param array $slidedeck_classes Classes to be applied
     * @param array $slidedeck The SlideDeck object being rendered
     * 
     * @return array
     */
    function slidedeck_frame_classes( $slidedeck_classes, $slidedeck ) {
        if( $this->is_valid( $slidedeck['source'] ) ) {
            $slidedeck_classes[] = "date-format-{$slidedeck['options']['date-format']}";
        }
        
        return $slidedeck_classes;
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
     * Get slides for SlideDecks of this type
     * 
     * Loads the slides associated with this SlideDeck if it matches this Deck type and returns
     * an array of structured slide data.
     * 
     * @param array $slides_arr Array of slides
     * @param object $slidedeck SlideDeck object
     * 
     * @global $SlideDeckPlugin
     * 
     * @uses SlideDeckPlugin::process_slide_content()
     * @uses SlideDeck_Posts::get_slides()
     * 
     * @return array
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
        
        $slides_nodes = $this->get_slides( $slidedeck );
        
        // Current slide
        $slide_counter = 1;
        
        // Loop through all slide nodes to build a structured slides array
        foreach( $slides_nodes as &$slide_nodes ) {
            $slide = array(
                'source' => $this->name,
                'title' => $slide_nodes['title'],
                'created_at' => $slide_nodes['created_at']
            );
            $slide = array_merge( $this->slide_node_model, $slide );
			
            // Look to see if an image is associated with this slide
            $has_image = $this->get_image( $slide_nodes, $slidedeck );
            
            if( $has_image ) {
                $slide['classes'][] = "has-image";
                $slide['thumbnail'] = $has_image;
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
            if( $has_image ) $slide_nodes['image'] = $has_image;
            
            // Set link target node
            $slide_nodes['target'] = $slidedeck['options']['linkTarget'];
            
            $slide['content'] = $SlideDeckPlugin->Lens->process_template( $slide_nodes, $slidedeck );
            
            $slide_counter++;
            
            $slides[] = $slide;
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
        $feed->set_cache_duration( $this->__transient_slidedeck['options']['cache_duration'] * 60 );
    }
}
