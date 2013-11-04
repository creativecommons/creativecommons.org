<?php
class SlideDeckSource_Flickr extends SlideDeck {
    var $label = "Flickr";
    var $name = "flickr";
    var $taxonomies = array( 'images' );
    var $default_lens = "tool-kit";
    
    var $options_model = array(
        'Setup' => array(
            'flickr_recent_or_favorites' => array(
                'value' => "recent",
                'data' => 'string'
            ),
            'flickr_user_or_group' => array(
                'value' => "user",
                'data' => 'string'
            ),
            'flickr_userid' => array(
                'value' => "76066843@N02",
                'data' => 'string'
            ),
            'flickr_tags_mode' => array(
                'value' => "any",
                'data' => "string"
            ),
            'flickr_tags' => array(
                'value' => "",
                'data' => "string"
            )
        )
    );
    
    function add_hooks() {
        add_action( "{$this->namespace}_after_delete", array( &$this, 'slidedeck_after_delete' ), 10, 3 );
        add_action( "{$this->namespace}_after_save", array( &$this, 'slidedeck_after_save' ), 10, 4 );
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
    }
    
    /**
     * Get Flickr Image Feed
     * 
     * Fetches a Flickr feed, caches it and returns the 
     * cached result or the results after caching them.
     * 
     * @param string $feed_url The URL of the gplus feed with a JSON response
     * @param integer $slidedeck_id The ID of the deck (for caching)
     * 
     * @return array An array of arrays containing the images and various meta.
     */
    function get_slides_nodes( $slidedeck ){
        switch( $slidedeck['options']['flickr_user_or_group'] ){
            case 'user':
                $feed_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=';
            break;
            case 'group':
                $feed_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id=';
                $slidedeck['options']['flickr_recent_or_favorites'] = 'recent';
            break;
        }
        
        switch( $slidedeck['options']['flickr_recent_or_favorites'] ){
            case 'recent':
                $feed_url .= $slidedeck['options']['flickr_userid'] . '&format=rss_200_enc';
                $tags_string = get_post_meta( $slidedeck['id'], "{$this->namespace}_flickr_tags", true );
                if( !empty( $tags_string ) ){
                    switch( $slidedeck['options']['flickr_tags_mode'] ){
                        case 'any':
                            $feed_url .= '&tagmode=any&tags=' . $tags_string . '&format=rss_200_enc';
                        break;
                        case 'all':
                            $feed_url .= '&tagmode=all&tags=' . $tags_string . '&format=rss_200_enc';
                        break;
                    }
                }
            break;
            case 'favorites':
                $feed_url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id=' . $slidedeck['options']['flickr_userid'] . '&format=rss_200_enc';
            break;
        }
        
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
                $images[ $index ]['title'] = $item->get_title();
                $images[ $index ]['width'] = $item->get_enclosure()->width;
                $images[ $index ]['height'] = $item->get_enclosure()->height;
                $images[ $index ]['created_at'] = strtotime( $item->get_date( "Y-m-d H:i:s" ) );
                $images[ $index ]['image'] = preg_replace( '/^(http:|https:)/', '', $item->get_enclosure()->link );
                $images[ $index ]['thumbnail'] = preg_replace( '/^(http:|https:)/', '', $item->get_enclosure()->thumbnails[0] );
                $images[ $index ]['permalink'] = $item->get_permalink();
                $images[ $index ]['content'] = $images[ $index ]['description'] = $item->get_content();
                
                $images[ $index ]['author_name'] = $item->get_enclosure()->credits[0]->name;
                $images[ $index ]['author_url'] = 'http://www.flickr.com/photos/' . $slidedeck['options']['flickr_userid'];
            }
        }        
        
        return $images;
    }
    
    /**
     * Get Flickr List of Tags
     * 
     * @param array The Array of tags
     * 
     * @return string The HTML required for display of the tags.
     */
    function get_flickr_tags_html( $tags ) {
        $html = '';
        
        foreach( $tags as $tag ){
            $html .= '<span>';
            $html .= '<a href="#delete" class="delete">X</a> ';
            $html .= $tag;
            $html .= '<input type="hidden" name="flickr_tags[]" value="' . $tag . '" />';
            $html .= '</span> ';
        }
        
        return $html;
    }
    
    /**
     * SlideDeck After Deletion hook-in
     * 
     * Deletes slides associated with the deleted SlideDeck
     * 
     * @param integer $slidedeck_id The ID of the SlideDeck that was deleted
     * @param string $source The source of the SlideDeck
     */
    function slidedeck_after_delete( $slidedeck_id, $source ) {
        // Only delete other slides if the SlideDeck belongs to this Deck type
        if( $this->is_valid( $source ) ) {
            return false;
        }
        
        // Nuke the tags associated with this deck
        delete_post_meta( $slidedeck_id, "{$this->namespace}_flickr_tags" );
    }

    /**
     * SlideDeck After Save hook-in
     * 
     * Saves additional data for this Deck type when saving a SlideDeck
     * 
     * @param integer $id The ID of the SlideDeck being saved
     * @param array $data The data submitted containing information about the SlideDeck to the save method
     * @deprecated @param string $deprecated The type of SlideDeck being saved
     * 
     * @uses SlideDeck::get()
     * @uses get_post_meta()
     * @uses update_post_meta()
     */
    function slidedeck_after_save( $id, $data, $deprecated, $source ) {
        if( $this->is_valid( $source ) ) {
            // Add the Flickr tags
            $tags = array();
            if( isset( $data['flickr_tags'] ) )
                $tags = (array) $data['flickr_tags'];
            
            update_post_meta( $id, "{$this->namespace}_flickr_tags", implode( ",", $tags ) );
        }
    }
    
    function slidedeck_form_content_source( $slidedeck, $source ) {
        // Fail silently if the SlideDeck is not this type or source
        if( !$this->is_valid( $source ) ) {
            return false;
        }
        
        // Load the list of videos
        $flickr_tags = array();
        $flickr_tags = get_post_meta( $slidedeck['id'], "{$this->namespace}_flickr_tags", true );
        
        if( empty( $flickr_tags ) ) {
            if( isset( $slidedeck['options']['slidedeck_flickr_tags'] ) && !empty( $slidedeck['options']['slidedeck_flickr_tags'] ) ){
                $flickr_tags = $slidedeck['options']['slidedeck_flickr_tags'];
            }
        }
        
        $tags = explode( ",", $flickr_tags );
        $tags = array_filter( $tags, 'strlen' );
        $tags_html = $this->get_flickr_tags_html( $tags );
        
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
     * Hook into wp_feed_options action
     * 
     * Hook into the SimplePie feed options object to modify parameters when looking up
     * feeds for RSS based feed SlideDecks.
     */
    function wp_feed_options( $feed, $url ) {
        $feed->set_cache_duration( $this->current_slidedeck['options']['cache_duration'] );
    }
}