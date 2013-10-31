<?php
class SlideDeckSource_GPlusImages extends SlideDeck {
    var $label = "Google Plus Images";
    var $name = "gplusimages";
    var $taxonomies = array( 'images' );
    var $default_lens = "tool-kit";
    
    var $options_model = array(
        'Setup' => array(
            'gplus_user_id' => array(
                'value' => "105237212888595777019", // Trey Ratcliff
                'data' => 'string'
            ),
            'gplus_images_album' => array(
                'value' => '5623042490481885105', // Portfolio - The Counter-Earth, the one some of us see...
                'data' => "string"
            ),
            'gplus_max_image_size' => array(
                'value' => 720,
                'data' => "integer"
            )
        )
    );
    
    function add_hooks() {
        add_filter( "{$this->namespace}_options", array( &$this, 'slidedeck_options' ), 10, 3 );
        
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
        add_action( "wp_ajax_update_gplus_albums", array( &$this, 'wp_ajax_update_gplus_albums' ) );
    }
    
    /**
     * Ajax function to get the user's albums
     * 
     * @return string A <select> element containing the albums.
     */
    function wp_ajax_update_gplus_albums() {
        $gplus_userid = $_REQUEST['gplus_userid'];
        
        echo $this->get_gplus_albums_from_userid( $gplus_userid );
        exit;
    }
    
    /**
     * Get Google Plus Image Feed
     * 
     * Fetches a Google Plus feed, caches it and returns the 
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
        
        $gplus_user_id = $slidedeck['options']['gplus_user_id'];
        $max_image_size = $slidedeck['options']['gplus_max_image_size'];
        // API Max: http://code.google.com/apis/picasaweb/docs/2.0/reference.html#Parameters

        switch( $slidedeck['options']['gplus_images_album'] ){
            case 'recent':
                $feed_url = 'http://photos.googleapis.com/data/feed/api/user/' . $gplus_user_id . '?kind=photo&alt=json&imgmax=' . $max_image_size . '&max-results=' . $slidedeck['options']['total_slides'];
            break;
            default:
                $album_id = (string) $slidedeck['options']['gplus_images_album'];
                $feed_url = 'http://photos.googleapis.com/data/feed/api/user/' . $gplus_user_id . '/albumid/' . $album_id . '?alt=json&imgmax=' . $max_image_size . '&max-results=' . $slidedeck['options']['total_slides'];
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
                if( !empty( $response_json ) ) {
                    slidedeck2_cache_write( $cache_key, $response, $slidedeck['options']['cache_duration'] );
                }
            } else {
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
        
        $images = array();
        foreach( (array) $response_json->feed->entry as $index => $entry ){
            $images[ $index ]['title'] = urldecode( $entry->title->{'$t'} );
            $images[ $index ]['description'] = $entry->summary->{'$t'};
            $images[ $index ]['width'] = $entry->{'gphoto$width'}->{'$t'};
            $images[ $index ]['height'] = $entry->{'gphoto$height'}->{'$t'};
            $images[ $index ]['created_at'] = strtotime( $entry->published->{'$t'} );
            $images[ $index ]['image'] = preg_replace( '/^(http:|https:)/', '', $entry->content->src );
            $images[ $index ]['thumbnail'] = preg_replace( '/^(http:|https:)/', '', $entry->{'media$group'}->{'media$thumbnail'}[1]->url );
            
            $url = "";
            foreach( $entry->link as $link ){
                // Make accommodation for videos to link to the canonical source instead of the alternate since the alternate does not work yet for videos
                if( isset( $entry->{'gphoto$originalvideo'} ) ) {
                    if( preg_match( "/canonical$/", $link->rel ) ) {
                        $url = $link->href;
                    }
                } else if( $link->rel == 'alternate' ) {
                    $url = $link->href;
                }
            }
            $images[ $index ]['permalink'] = $url;
            
            $images[ $index ]['comments_count'] = $entry->{'gphoto$commentCount'}->{'$t'};
            $images[ $index ]['author_name'] = $entry->{'media$group'}->{'media$credit'}[0]->{'$t'};
            $images[ $index ]['author_url'] = 'https://picasaweb.google.com/' . $gplus_user_id;
        }
        
        return $images;
    }
    
    /**
     * Gets the List of albums from a gplus user
     * 
     * @return string The HTML necessary for sselecting an album.
     */
    function get_gplus_albums_from_userid( $user_id = false, $slidedeck = null ){
        $albums = false;
        
        $args = array(
            'sslverify' => false
        );
        
        $feed_url = "https://picasaweb.google.com/data/feed/api/user/{$user_id}?alt=json&orderby=updated";
        
        if( isset( $user_id ) && !empty( $user_id ) ){
            $albums = array();
            
            // This is not cached intentionally so newly added playlists will show up for a user immediately
            $response = wp_remote_get( $feed_url, $args );
            
            if( !is_wp_error( $response ) ) {
                $response_json = json_decode( $response['body'] );
                
                if( !empty( $response_json ) ) {
                    foreach( $response_json->feed->entry as $key => $entry ) {
                        // Only if the album has photos in it.
                        if( intval( $entry->{'gphoto$numphotos'}->{'$t'} ) > 0 ) {
                            $albums[ $key ] = array(
                                'album_id' => $entry->{'gphoto$id'}->{'$t'},
                                'title' => $entry->title->{'$t'} . sprintf( _n( " (%d photo)", " (%d photos)", $entry->{'gphoto$numphotos'}->{'$t'}, $this->namespace ), $entry->{'gphoto$numphotos'}->{'$t'} ),
                                'thumbnail' => $entry->{'media$group'}->{'media$thumbnail'}[0]->url,
                            );
                        }
                    }
                }
            } else {
                return false;
            }
        }
        
        $albums_select = array( 
            'recent' => __( 'Recent Images', $this->namespace )
        );
        
        if( $albums ){
            foreach( $albums as $album ){
                $albums_select[ $album['album_id'] ] = $album['title'];
            }
        }
        
        return slidedeck2_html_input( 'options[gplus_images_album]', $slidedeck['options']['gplus_images_album'], array( 'type' => 'select', 'label' => "Album", 'attr' => array( 'class' => 'fancy' ), 'values' => $albums_select ), false ); 
    }
    
    function slidedeck_form_content_source( $slidedeck, $source ) {
        // Fail silently if the SlideDeck is not this type or source
        if( !$this->is_valid( $source ) ) {
            return false;
        }
        
        $albums_select = $this->get_gplus_albums_from_userid( $slidedeck['options']['gplus_user_id'], $slidedeck );
        
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
     * Hook-in to slidedeck_options filter
     * 
     * @param array $options Options available
     * @deprecated @param string $deprecated The SlideDeck type
     * 
     * @return array 
     */
    function slidedeck_options( $options, $deprecated, $source ) {
        if( $source == $this->name ) {
            if( isset( $options['gplus_max_image_size'] ) )
                $options['gplus_max_image_size'] = max( 94, min( (integer) $options['gplus_max_image_size'], 1600 ) );
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
}