<?php
class SlideDeckSource_GPlus extends SlideDeck {
    var $label = "Google+ Public Posts";
    var $name = "gplus";
    var $default_lens = "tool-kit";
    var $taxonomies = array( 'social', 'posts', 'feeds' );
    
    // Default configuration options for this source
    var $default_options =  array(
        'gplusUserId' => "",
        'gplus_api_key' => ""
    );
    
    var $options_model = array();
    
    function add_hooks() {
        add_filter( "{$this->namespace}_classes", array( &$this, 'slidedeck_classes' ), 10, 2 );
        add_filter( "{$this->namespace}_frame_classes", array( &$this, 'slidedeck_frame_classes' ), 10, 2 );
        
        add_action( "{$this->namespace}_after_save", array( &$this, 'slidedeck_after_save' ), 10, 4 );
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
    function get_image( $slide ) {
        global $SlideDeckPlugin;
        
        // Set default return value
        $image_src = false;
        
        // If the image is actually set already, just use it.
        if( isset( $slide['image'] ) && !empty( $slide['image'] ) ){
            $image_src = $slide['image'];
            return $image_src;
        }
        
        // Try and find an image from the content source if we don't have one yet
        $images = $SlideDeckPlugin->Lens->parse_html_for_images( $slide['content'] );
        if( !empty( $images ) ) {
            $first_image = reset( $images );
            $image_src = $first_image['src'];
        }
        
        return $image_src;
    }

    /**
     * Load slides for Google+ feed sourced SlideDecks
     * 
     * @uses fetch_feed()
     * @uses $SlideDeck->get_dimensions()
     * 
     * @return array
     */
    function get_slides_nodes( $slidedeck ) {
        $slides = array();
        $slidedeck_id = $slidedeck['id'];
        $slidedeck_dimensions = $this->get_dimensions( $slidedeck );
        $expansion_factor = 1; // We may want to adjust this to multiply the size later
        $expanded_width = $slidedeck_dimensions['outer_width'] * $expansion_factor;
        $expanded_height = $slidedeck_dimensions['outer_height'] * $expansion_factor;
        
        $args = array(
            'sslverify' => false
        );
        
        if( isset( $slidedeck['options']['gplusUserId'] ) && !empty( $slidedeck['options']['gplusUserId'] ) && isset( $slidedeck['options']['gplus_api_key'] ) && !empty( $slidedeck['options']['gplus_api_key'] )){
            // https Google Plus public posts feed:
            $feed_url = 'https://www.googleapis.com/plus/v1/people/' . $slidedeck['options']['gplusUserId'] . '/activities/public?key=' . $slidedeck['options']['gplus_api_key'] . '&maxResults=' . $slidedeck['options']['total_slides'] . '&alt=json';
        }else{
            return $slides;
        }
        
        // Set a reference to the current SlideDeck for reference in actions
        $this->__transient_slidedeck &= $slidedeck;

        // Create a cache key
        $cache_key = $slidedeck_id . $feed_url . $slidedeck['options']['cache_duration'] . $this->name;
    
        // Attempt to read the cache
        $response = slidedeck2_cache_read( $cache_key );
        if( !$response ) {
            $response = wp_remote_get( $feed_url, $args );
            
            // Write the cache
            if( !is_wp_error( $response ) ) {
                if( !empty( $response_json ) ) {
                    slidedeck2_cache_write( $cache_key, $response, $slidedeck['options']['cache_duration'] );
                }
            } else {
                // Kill processing if we were unable to get the response
                return false;
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
        
        // Begin building the processed Google+ slide node array
        $gplus_posts = array();
        foreach( (array) $response_json->items as $entry ){
            /**
             * If the post was a re-share then hanle differently.
             */
            if( $entry->verb == 'share' ){
                $author_name = $entry->object->actor->displayName;
                $author_first_name = $entry->object->actor->displayName;
                $author_url = $entry->object->actor->url;
                $author_avatar = $entry->object->actor->image->url;
            }else{
                $author_name = $entry->actor->displayName;
                $author_first_name = $entry->actor->name->givenName;
                $author_url = $entry->actor->url;
                $author_avatar = $entry->actor->image->url;
            }
            
            // Set default title
            $title = $entry->title;
            // Extra excerpt
            $article_excerpt = '';
            
            // Look for media:
            $post_image = false;
            $post_video = false;
            
            if( !empty( $entry->object->attachments ) && isset( $entry->object->attachments ) ){
                foreach( $entry->object->attachments as $attachment ){
					
                    // If there's an image, grab it...
                    if( property_exists( $attachment, 'image' ) ){
                        $post_image = $attachment->image->url;
                    }
                    
                    // if there's a full image, grab it too!
                    if( property_exists( $attachment, 'fullImage' ) ){
                    	/**
						 * If there's no width or height on the fullImage, Google doesn't return
						 * an image URL, but it returns a page URL instead. In this case, we should 
						 * set the image to false. (the threshold is around 150px or so)
						 */
						if( isset( $attachment->fullImage->width ) && isset( $attachment->fullImage->height ) ){
                        	$post_image = $attachment->fullImage->url;
						}else{
							$post_image = false;
						}
                    }
                    
                    // If there's a video, grab its data
                    if( property_exists( $attachment, 'embed' ) ) {
                        $post_video = $attachment->embed->url;
                    }
                    
                    // Override title if an article is attached ie: A link with images
                    if( $attachment->objectType == 'article' ){
                        // Add the article title if one exists.
                        if( isset( $attachment->displayName ) && !empty( $attachment->displayName ) )
                            $title = $attachment->displayName;
                        // Fill the extra excerpt content if it exists.
                        if( isset( $attachment->content ) && !empty( $attachment->content ) )
                            $article_excerpt = $attachment->content;
                        
                    }
                }
            }

            /**
             * If the post was a checkin (business level location attached)
             * 
             * For Checkins, it would be nice to say that 'User' Checked in 'Place',
             * so let's modify the title so it says so. G+ has no titles really 
             * anyway, so it's no big deal... I think.
             */
            if( $entry->verb == 'checkin' ){
                if( isset( $entry->placeName ) && !empty( $entry->placeName ) )
                $title = "{$author_first_name} checked in at {$entry->placeName}";
                
                /**
                 * If we've gotten this far, and there's no image, let's use a map!
                 * We love images! Images are good m'kay?
                 */
                if( empty( $post_image ) ){
                    $geocode = str_replace( ' ', ',', $entry->geocode ); // lat,lon
                    $scale_factor = 2; // Integer!
                    $map_zoom_level = 16; // Integer!
                    $post_image = 'http://maps.googleapis.com/maps/api/staticmap?sensor=false&format=png8&markers=' . $geocode . '&center=' . $geocode . '&zoom=' . $map_zoom_level . '&maptype=roadmap&scale=' . $scale_factor . '&size=' . round( $expanded_width/$scale_factor ) . 'x' . round( $expanded_height/$scale_factor );
                }
            }

            /**
             * Build the final array of cool stuff for the Google+ Slide: 
             */
            $gplus_posts[] = array(
                'id' => $entry->id,
                'title' => $title,
                'permalink' => $entry->url,
                'image' => preg_replace( '/^(http:|https:)/', '', $post_image ),
                'video' => $post_video,
                'author_name' => $author_name,
                'author_url' => $author_url,
                'author_email' => false,
                'author_avatar' => $author_avatar,
                'content' => empty( $entry->object->content ) ? $entry->object->content : false,
                'comment_count' => $entry->object->replies->totalItems,
                'plusone_count' => $entry->object->plusoners->totalItems,
                'reshare_count' => $entry->object->resharers->totalItems,
                'excerpt' => strip_tags( $entry->object->content . ' ' . $article_excerpt, "<b><strong><i><em><a>" ),
                'created_at' => strtotime( $entry->published ),
                'local_created_at' => $entry->published,
            );
        }
        
        return $gplus_posts;
    }

    /**
     * SlideDeck After Save hook-in
     * 
     * Saves additional data for this Deck type when saving a SlideDeck
     * 
     * @param integer $id The ID of the SlideDeck being saved
     * @param array $data The data submitted containing information about the SlideDeck to the save method
     * @deprecated @param string $type The type of SlideDeck being saved
     * @param string $source The source of the SlideDeck being saved
     */
    function slidedeck_after_save( $id, $data, $deprecated, $source ) {
        // Fail silently if the Deck type is not this Deck type
        if( $this->is_valid( $source ) ) {
            return false;
        }
        
        // Save the API Key for later use...
        if( !empty( $data['options']['gplus_api_key'] ) ){
            update_option( $this->namespace . '_last_saved_gplus_api_key', $data['options']['gplus_api_key'] );
        }
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
     * SlideDeck default options hook-in
     * 
     * @param array $options The SlideDeck Options
     * @param string $deprecated The SlideDeck Type
     * @param string $lens The SlideDeck Lens
     * @param string $source The SlideDeck Source
     * 
     * @return array
     */
    function slidedeck_default_options( $options, $deprecated, $lens, $source ) {
        if( $this->is_valid( $source ) ) {
            // Check for last_saved_gplus_api_key
            if( $last_saved_gplus_api_key = get_option( $this->namespace . '_last_saved_gplus_api_key' ) ){
                $options['gplus_api_key'] = $last_saved_gplus_api_key;
            }
        }
        
        return $options;
    }
    
    /**
     * Content Source form section for Google+ Public Posts
     * 
     * Loads necessary data for sourcing a SlideDeck based off of Google+ and renders out
     * the form interaction.
     * 
     * @param array $slidedeck The SlideDeck object
     * @param object $source The source object
     * 
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
        
        $slides_nodes = $this->get_slides_nodes( $slidedeck );
        
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
            $has_image = $this->get_image( $slide_nodes );
            // Look to see if a video is associated with this slide
            $has_video = isset( $slide_nodes['video'] ) ? $slide_nodes['video'] : false;
            
            if( $has_image ) {
                $slide['classes'][] = "has-image";
                $slide['thumbnail'] = $has_image;
                $slide['type'] = "image";
            } else {
                $slide['classes'][] = "no-image";
            }
            
            if( $has_video ) {
                $slide_nodes['video_meta'] = $this->get_video_meta_from_url( $has_video );
                $slide['classes'][] = "has-video";
                $slide['type'] = "video";
                $slide_nodes['slide_counter'] = $slide_counter;
                $slide_nodes['deck_iteration'] = $deck_iteration;
            }
            
            $slide_nodes['source'] = $slide['source'];
            $slide_nodes['type'] = $slide['type'];
            
            // Excerpt node
            if( !array_key_exists( 'excerpt', $slide_nodes) || empty( $slide_nodes['excerpt'] ) )
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
