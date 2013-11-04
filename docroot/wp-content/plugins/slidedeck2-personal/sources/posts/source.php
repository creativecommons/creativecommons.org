<?php
class SlideDeckSource_Posts extends SlideDeck {
    var $label = "Your Posts";
    var $name = "posts";
    var $default_lens = "tool-kit";
    var $taxonomies = array( 'posts' );
    
    // The available sorting methods for posts
    var $post_type_sorts = array(
        'recent' => "Recent",
        'popular' => "Popular",
        'menu_order' => "User Sort Order"
    );
    
    var $options_model = array(
        'Setup' => array(
            'preferredImageSize' => array(
                'name' => "preferredImageSize",
                'type' => "select",
                'data' => "string",
                'value' => 'auto',
                'values' => array(
                    'auto' => "Auto (120%)",
                    'auto_100' => "Auto (100%)"
                ),
                'attr' => array(
                    'class' => "fancy"
                ),
                'label' => "Preferred Image Size",
                'description' => "Auto 100% and Auto 120% will automatically try to find an image that fits the deck. If you want to specify one of your own image sizes, set it here.",
                'weight' => 70
            ),
            'postsImageSource' => array(
                'name' => "postsImageSource",
                'type' => "select",
                'data' => "string",
                'value' => 'content',
                'values' => array(
                	'none' => "No Image",
                    'content' => "First image in content",
                    'thumbnail' => "Featured image",
                    'gallery' => "First image in gallery"
                ),
                'attr' => array(
                    'class' => "fancy"
                ),
                'label' => "Preferred Image Source",
                'description' => "Preferred location where an image be pulled from (will automatically fall back to other sources if none are found in the preferred location)",
                'weight' => 70
            ),
            'post_type' => array(
                'name' => 'post_type',
                'type' => "text",
                'data' => "string",
                'value' => 'post'
            ),
            'post_type_sort' => array(
                'name' => 'post_type_sort',
                'type' => "text",
                'data' => "string",
                'value' => 'recent'
            ),
            'filter_terms' => array(
                'name' => 'filter_terms',
                'type' => "text",
                'data' => "string",
                'value' => array()
            ),
            'filter_by_tax' => array(
                'type' => 'radio',
                'data' => 'boolean',
                'value' => false,
                'attr' => array(
                    'class' => "fancy"
                )
            ),
            'query_any_all' => array(
                'name' => "query_any_all",
                'type' => "select",
                'data' => "string",
                'value' => 'any',
                'values' => array(
                    'any' => "Any of these taxonomies",
                    'all' => "All of these taxonomies"
                ),
                'attr' => array(
                    'class' => "fancy"
                )
            ),
            'use-custom-post-excerpt' => array(
                'type' => 'radio',
                'label' => "Use Custom Excerpt?",
                'description' => "Turn on to use your custom crafted excerpt for posts (instead of the post content) when available",
                'data' => 'boolean',
                'value' => false,
                'attr' => array(
                	'class' => "fancy"
				),
                'weight' => 23
            )
        )
    );
    
    function add_hooks() {
        add_filter( "{$this->namespace}_classes", array( &$this, 'slidedeck_classes' ), 10, 2 );
        add_filter( "{$this->namespace}_frame_classes", array( &$this, 'slidedeck_frame_classes' ), 10, 2 );
        add_filter( "{$this->namespace}_options", array( &$this, 'slidedeck_options' ), 10, 3 );
        
        add_action( "wp_ajax_{$this->namespace}_available_filters", array( &$this, 'ajax_available_filters' ) );
        add_action( "wp_ajax_{$this->namespace}_available_terms", array( &$this, 'ajax_available_terms' ) );
        
        add_action( "admin_print_scripts-toplevel_page_" . SLIDEDECK2_HOOK, array( &$this, 'admin_print_scripts' ) );
    
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
    }
    
    /**
     * Get the thumbnail image for a post's image
     * 
     * Attempts to find the URL for the thumbnail size of an image attached to a Post
     * 
     * @param int $post_id The ID of the WordPress Post
     * @param string $image_url The URL of the image to get a thumbnail for
     * 
     * @global $wpdb
     * 
     * @uses current_theme_supports()
     * @uses get_the_post_thumbnail()
     * @uses wpdb::prepare()
     * @uses wpdb::get_row()
     * @uses wp_get_attachment_image()
     * @uses wp_upload_dir()
     * 
     * @return string
     */
    private function _get_post_thumbnail( $post_id, $image_url ) {
        global $wpdb;
        
        $thumbnail_src = $image_url;
        $thumbnail_html = "";
        
        // Use the post thumbnail if possible
        if( current_theme_supports( 'post-thumbnails' ) ) {
            $thumbnail_html = get_the_post_thumbnail( $post_id, 'thumbnail' );
        }
        
        // Process for a thumbnail version of the background image
        $url_parts = parse_url( $image_url ); // Get array of URL parts of the image
        $wp_upload_dir = wp_upload_dir(); // Get array of URL parts of the upload directory
        $relative_upload_base = str_replace( ABSPATH, "", str_replace( $wp_upload_dir['subdir'], "", $wp_upload_dir['path'] ) );
        if( isset( $url_parts['host'] ) && $url_parts['host'] == $_SERVER['HTTP_HOST'] && strpos( $url_parts['path'], "/" . $relative_upload_base ) !== false ) {
            $image_url_original = preg_replace( "/\-[0-9]+x[0-9]+\.(jpeg|jpg|gif|png|bmp)$/", ".$1", $image_url );
            $post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE guid = %s", $image_url_original ) );
            if( !empty( $post ) ) {
                $thumbnail_html = wp_get_attachment_image( $post->ID, 'thumbnail' );
            }
        }
        
        if( !empty( $thumbnail_html ) ) {
            $matches = array();
            preg_match( "/ src\=\"([^\"]+)\"/", $thumbnail_html, $matches );
            if( count( $matches ) > 1 ) {
                $thumbnail_src = $matches[1];
            }
        }
        
        return $thumbnail_src;
    }
    
    /**
     * Hook into admin_print_scripts for edit pages of SlideDeck 2 plugin
     * 
     * @uses wp_enqueue_script()
     */
    function admin_print_scripts() {
        wp_enqueue_script( 'post' );
    }

    /**
     * AJAX response for available terms options for a post type
     * 
     * @uses SlideDeck::get()
     * @uses SlideDeckSource_Posts::_available_terms()
     */
    function ajax_available_terms() {
        $slidedeck_id = (integer) $_REQUEST['slidedeck'];
        $filter_by_tax = (integer) ( isset( $_REQUEST['filter_by_tax'] ) ) ? $_REQUEST['filter_by_tax'] : 0;
        $taxonomy = (string) $_REQUEST['taxonomy'];
        $post_type = $_REQUEST['post_type'];
        
        // Get the SlideDeck
        $slidedeck = $this->get( $slidedeck_id );
        // Add the filter by tax option (passed in via Ajax to the unsaved deck)
        $slidedeck['options']['filter_by_tax'] = $filter_by_tax;
        
        $html = $this->available_terms( $post_type, $slidedeck, $taxonomy );
        
        die( $html );
    }
    
    /**
     * AJAX response for available filtering options for a post type
     * 
     * @uses SlideDeck::get()
     * @uses SlideDeckSource_Posts::_available_filters()
     */
    function ajax_available_filters() {
        $slidedeck_id = (integer) $_REQUEST['slidedeck'];
        $filter_by_tax = (integer) $_REQUEST['filter_by_tax'];
        $post_type = $_REQUEST['post_type'];
        
        $slidedeck = $this->get( $slidedeck_id );
        $slidedeck['options']['filter_by_tax'] = $filter_by_tax;
        
        $html = $this->available_filters( $post_type, $slidedeck );
        
        die( $html );
    }
    
    /**
     * Available filtering options for a post type
     * 
     * Loads all available taxonomies or categories associated with a specific post type and returns
     * an HTML string of the available taxonomy tags and/or categories to filter by. If a SlideDeck is
     * passed in, selected options will be pre-checked.
     * 
     * @param string $post_type The slug of the post type to query by
     * @param object $slidedeck The optional SlideDeck object being rendered to pre-check selected options
     * 
     * @return string
     */
    function available_filters( $post_type, $slidedeck = null ) {
        $html = "";
        if( isset( $slidedeck ) ){
            if( !$slidedeck['options']['filter_by_tax'] ){
                return $html;
            }
        }
        
        // Get existing filtered parameters for this SlideDeck
        $filtered = array();
        if( isset( $slidedeck ) ) {
            $filtered = (array) $slidedeck['options']['filter_terms'];
        }
        
        // Get all taxonomy types (including categories) for this post type
        $taxonomies = get_object_taxonomies( $post_type, 'objects' );
        
        // Get all terms for each taxonomy type and get its terms
        foreach( $taxonomies as &$taxonomy ) {
            $taxonomy->terms = get_terms( $taxonomy->name, array(
                'orderby' => 'name',
                'hide_empty' => false
            ) );
        }
        
        // Process HTML output
        ob_start();
        
            include( dirname( __FILE__ ) . '/views/_available_taxonomies.php' );
            $html = ob_get_contents();
        
        ob_end_clean();
        
        return $html;
    }
    
    /**
     * Available terms
     * 
     * @param string $post_type The slug of the post type to query by
     * @param object $slidedeck The optional SlideDeck object being rendered to pre-check selected options
     * @param string $taxonomy The Taxonomy slug being queried
     * 
     * @return string
     */
    function available_terms( $post_type, $slidedeck = null, $taxonomy = '' ) {
        $html = "";
        
        // Get existing filtered parameters for this SlideDeck
        // In other words, these are checked...
        $filtered = array();
        if( isset( $slidedeck ) ) {
        	if( isset( $slidedeck['options']['filter'][$taxonomy]['terms'] ) && !empty( $slidedeck['options']['filter'][$taxonomy]['terms'] ) )
            	$filtered = (array) $slidedeck['options']['filter'][$taxonomy]['terms'];
        }
        
        $taxonomy_object = get_taxonomy( $taxonomy );

        $terms = get_terms( $taxonomy, array(
            'orderby' => 'name',
            'hierarchical' => true,
            'hide_empty' => false
        ) );
        
        // Process HTML output
        ob_start();
        
            include( dirname( __FILE__ ) . '/views/_available_terms.php' );
            $html = ob_get_contents();
        
        ob_end_clean();

        return $html;
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
        
        /**
         * Grab the width and height of the deck. Then we should
         * create an expansion factor that will hopefully grab images _just_
         * larger than we need. Grabbing the big size automatically makes
         * some browsers (mostly Chrome) chug and is bad for the end user too.
         */
        $slidedeck_dimensions = $this->get_dimensions( $slidedeck );
        
        // Set the expansion factor based on the auto or auto_100 options
        if( $slidedeck['options']['preferredImageSize'] == 'auto' ) {
            $expansion_factor = 1.2; // 120%
        } else {
            $expansion_factor = 1; // 100%
        }
        
        $expanded_width = $slidedeck_dimensions['outer_width'] * $expansion_factor;
        $expanded_height = $slidedeck_dimensions['outer_height'] * $expansion_factor;
        
        // Determine image size to retrieve (closest size greater to SlideDeck size, or full of image scaling is off)
        $image_size = array( $expanded_width, $expanded_height );
        
        if( ($slidedeck['options']['preferredImageSize'] != 'auto') && ($slidedeck['options']['preferredImageSize'] != 'auto_100') ) {
            $image_size = $slidedeck['options']['preferredImageSize'];
        }

        // Set default return value
        $image_src = false;
        
        // If the image is actually set already, just use it.
        if( isset( $slide['image'] ) && !empty( $slide['image'] ) ){
            $image_src = $slide['image'];
            return $image_src;
        }
        
        if( !isset( $slidedeck['options']['postsImageSource'] ) )
            $slidedeck['options']['postsImageSource'] = "content";
        
        $sources = array( 'content', 'gallery', 'thumbnail' );
        
        if( !isset( $source ) )
            $source = $slidedeck['options']['postsImageSource'];
		
		// Just return boolean(false) if the user doesn't want any images
		if( $slidedeck['options']['postsImageSource'] == "none" )
			return false;
        
        switch( $source ) {
            default:
            case "content":
                $images = $SlideDeckPlugin->Lens->parse_html_for_images( $slide['content'] );
                if( !empty( $images ) ) {
                    $image_src = reset( $images );
                }
            break;
            
            case "gallery":
                if( is_numeric( $slide['id'] ) ) {
                    $query_args = array(
                        'post_parent' => $slide['id'],
                        'posts_per_page' => -1,
                        'post_type' => 'attachment',
                        'post_status' => 'any',
                        'order' => 'ASC',
                        'orderby' => 'menu_order'
                    );
                    $attachments = new WP_Query( $query_args );
                    
                    if( !empty( $attachments->posts ) ) {
                        /**
                         * By default, when a media attachment is uploaded, it has no specified menu order, so all
                         * attachments will have a menu_order value of 0 and the sort order will default to the
                         * PRIMARY KEY of the database (the ID column), effectively sorting them by upload order.
                         * Once a user intentionally sorts and saves the gallery order, this gets updated, but lets
                         * make sure and accommodate for the "un-sorted" default as well. 
                         */
                        
                        // Assume no sort has been implied
                        $menu_order_set = false;
                        // Loop through media attachments
                        foreach( $attachments->posts as $post ) {
                            // If any media attachment has a non-zero menu_order, a sort has at one point been implied
                            if( $post->menu_order > 0 ) {
                                $menu_order_set = true;
                            }
                        }
                        
                        // If no sort order has been applied by the user, flip the order so the image that is displayed first in the gallery list is used
                        if( $menu_order_set === false ) {
                            $attachments->posts = array_reverse( $attachments->posts );
                        }
                        
                        $first_image = reset( $attachments->posts );
                        
                        $thumbnail = wp_get_attachment_image_src( $first_image->ID, $image_size );
                        $image_src = $thumbnail[0];
                    }
                }
            break;
            
            case "thumbnail":
                if( current_theme_supports( 'post-thumbnails' ) ) {
                    if( is_numeric( $slide['id'] ) ) {
                        $thumbnail_id = get_post_thumbnail_id( $slide['id'] );
                        if( $thumbnail_id ) {
                            if( isset( $slidedeck['options']['image_scaling'] ) ) {
                                if( $slidedeck['options']['image_scaling'] == "none" ) {
                                    $image_size = "full";
                                }
                            }
                            
                            $thumbnail = wp_get_attachment_image_src( $thumbnail_id, $image_size );
                            $image_src = $thumbnail[0];
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
        
        return $image_src;
    }

    /**
     * Load slides for SlideDecks sourced from WordPress posts
     * 
     * @param array $slidedeck The SlideDeck object
     * 
     * @uses WP_Query
     * @uses get_the_title()
     * @uses maybe_unserialize()
     * 
     * @return array
     */
    function get_slides_nodes( $slidedeck ) {
        $post_type = $slidedeck['options']['post_type'];
        $post_type_sort = $slidedeck['options']['post_type_sort'];
        
        // Default Query Arguments
        $query_args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => $slidedeck['options']['total_slides'],
            'ignore_sticky_posts' => 1
        );
        
        switch( $post_type_sort ) {
            case "recent":
                $query_args['orderby'] = "date";
                $query_args['order'] = "DESC";
            break;
            
            case "popular":
                $query_args['orderby'] = "comment_count date";
                $query_args['order'] = "DESC";
            break;
            
            case "menu_order":
                $query_args['orderby'] = "menu_order";
                $query_args['order'] = "ASC";
            break;
        }
        
        // If filtering is on...
        if( $slidedeck['options']['filter_by_tax'] ){
            
            
            // Set up the tag/category filtering 
            $filter_terms = array();
            if( isset( $slidedeck['options']['filter'] ) )
                $filter_terms = $slidedeck['options']['filter'];
            // Loop through the taxonomies and the terms.
            if( isset( $filter_terms ) && !empty( $filter_terms ) ) {
                
                // Are we getting any of the taxonomies or all of them?
                if( $slidedeck['options']['query_any_all'] == 'any' ){
                    $query_args['tax_query']['relation'] = 'OR';
                }else{
                    $query_args['tax_query']['relation'] = 'AND';
                }
                
                foreach( $filter_terms as $taxonomy => $terms ) {
                    // Get the taxonomy object
                    $taxonomy_object = get_taxonomy( $taxonomy );
                    
                    // Which field to we query?
                    if( $taxonomy_object->hierarchical ) {
                        $field = 'id';
                    }else{
                        $field = 'slug';
                    }
                    // Add each taxonomy query to the tax query array...
                    foreach( $terms as $term_ids ) {
                        $query_args['tax_query'][] = array(
                            'taxonomy' => $taxonomy,
                            'field' => $field,
                            'terms' => $term_ids
                        );
                    }
                }
            }
        }

        $query_args = apply_filters( "{$this->namespace}_posts_slidedeck_query_args", $query_args, $slidedeck );
        
        add_filter( 'posts_where', array( &$this, 'filter_password_protected' ) );
        $query = new WP_Query( $query_args );
        remove_filter( 'posts_where', array( &$this, 'filter_password_protected' ) );
        
        $slides = array();
        foreach( (array) $query->posts as $post ) {
            $post_id = $post->ID;
            
            /**
             * Set the author and the default post_content.
             */
            $author = get_userdata( $post->post_author );
            $post_content = $post->post_content;
            
            $slide = array(
                'id' => $post_id,
                'title' => $post->post_title,
                'permalink' => get_permalink( $post_id ),
                'author_id' => $post->post_author,
                'author_name' => $author->display_name,
                'author_url' => $author->user_url,
                'author_email' => $author->user_email,
                'author_avatar' => slidedeck2_get_avatar( $author->user_email ),
                'content' => $post_content,
                'excerpt' => $post->post_excerpt,
                'created_at' => strtotime( $post->post_date_gmt ),
                'local_created_at' => $post->post_date
            );
            
            $slides[] = $slide;
        }
        
        return $slides;
    }
    
    /**
     * Hook into WordPress posts_where filter
     * 
     * Filter out password protected posts for SlideDeck queries.
     * 
     * @param string $where The WHERE portion of the query clause
     * 
     * @return string
     */
    function filter_password_protected( $where = "" ) {
        $where .= " AND post_password = ''";
        return $where;
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
        
        // Available post types to choose from excluding SlideDeck related post types and invalid post types like navigation and revisions
        $post_types = get_post_types( array(), 'objects' );
        $invalid_post_types = array( 'revision', 'attachment', 'nav_menu_item', SLIDEDECK1_POST_TYPE, SLIDEDECK1_SLIDE_POST_TYPE, SLIDEDECK2_POST_TYPE, SLIDEDECK2_SLIDE_POST_TYPE );
        foreach( $invalid_post_types as &$invalid_post_type )
            unset( $post_types[$invalid_post_type] );
        
        foreach( $post_types as &$post_type )
            $post_type = $post_type->labels->name;
        
        // Post sorting methods
        $post_type_sorts = $this->post_type_sorts;
        
        if( !current_theme_supports( 'post-thumbnails' ) )
            unset( $this->options_model['Setup']['postsImageSource']['values']['thumbnail'] );
        
        // Add the additional image sizes to the dropdown
        $additional_image_sizes = get_intermediate_image_sizes();
        foreach( $additional_image_sizes as $size ) {
            
            $sizes = array(
                'size_w' => get_option("{$size}_size_w"),
                'size_h' => get_option("{$size}_size_h"),
                'crop' => ''
            );
            if( get_option("{$size}_crop") ) $sizes['crop'] = ' cropped';
            
            /**
             * Add the sizes to the dropdown menu.
             * The formatting is strange here, and we need to account for 
             * the different variations in registered sizes.
             */
            if( !empty( $sizes['size_w'] ) && !empty( $sizes['size_h'] ) ) {
                $this->options_model['Setup']['preferredImageSize']['values'][$size] = ucwords( $size ) . ' (' . $sizes['size_w'] . 'x' . $sizes['size_h'] . $sizes['crop'] . ')';
            } elseif( !empty( $sizes['size_w'] ) && empty( $sizes['size_h'] ) ) {
                $this->options_model['Setup']['preferredImageSize']['values'][$size] = ucwords( $size ) . ' (' . $sizes['size_w'] . $sizes['crop'] . ')';
            } elseif( empty( $sizes['size_w'] ) && !empty( $sizes['size_h'] ) ) {
                $this->options_model['Setup']['preferredImageSize']['values'][$size] = ucwords( $size ) . ' (' . $sizes['size_h'] . $sizes['crop'] . ')';
            } else {
                $this->options_model['Setup']['preferredImageSize']['values'][$size] = ucwords( $size );
            }
        }
        // This is a fake size that should cause the function to return the original
        $this->options_model['Setup']['preferredImageSize']['values']['sd2-full-size-image'] = __( "Original Image", $this->namespace );
        
        // Set a simple boolean flag to show/hide the image size dropdown
        switch( $slidedeck['options']['postsImageSource'] ) {
            case 'thumbnail':
            case 'gallery':
                $show_image_size = true;
            break;
            default:
                $show_image_size = false;
            break;
        }

        
        $namespace = $this->namespace;
        
        $image_sources['none'] = "No Image";
        
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
     * Modify options for this content source
     * 
     * @param array $options Array of options
     * @deprecated @param $deprecated DEPRECATED SlideDeck type
     * @param string $source The source of the current SlideDeck
     * 
     * @return array
     */
    function slidedeck_options( $options, $deprecated, $source ){
        if( $this->is_valid( $source ) ) {
            // Convert the taxonomy terms
            /**
             * We're doing it this was because we're using the
             * default WordPress cateogry box. This is a better user
             * experience as it's familiar, but requires a bit more work
             * to translate from WP language to SD language.
             */
            
            if( $options['filter_by_tax'] ){
                // For each taxonomy type...
                foreach( (array) $options['taxonomies'] as $taxonomy => $value ){
                    if( isset( $_REQUEST['tax_input'][$taxonomy] ) ){
                        /**
                         * Is this tax type sent with the tax_input prefix?
                         * If so then it's probably a custom taxonomy...
                         */
                        if( is_array( $_REQUEST['tax_input'][$taxonomy] ) ) {
                            /**
                             * OK, we're getting mightly tricky now. ^^
                             * If the tax_input is an array, it's probably categories.
                             */
                            $options['filter'][$taxonomy]['terms'] = $_REQUEST['tax_input'][$taxonomy];
                        }else{
                            /**
                             * If it's a string, then it's probably some tags.
                             */
                            $tag_tax = reset( array_keys( $_REQUEST['newtag'] ) );
                            $tags = explode( ',', $_REQUEST['tax_input'][$taxonomy] );
                            foreach( $tags as &$tag )
                                $tag = trim( $tag );
                            
                            $options['filter'][$tag_tax]['terms'] = $tags;
                        }
                    }else{
                        /**
                         * Else... this is probably a default category taxonomy.
                         */
                        if( isset( $_REQUEST['post_'.$taxonomy] ) )
                        	$options['filter'][$taxonomy]['terms'] = $_REQUEST['post_'.$taxonomy];
                    }
                }
            }
        }
        
        return $options;
    }

    /**
     * slidedeck_options_model hook-in
     * 
     * @param array $options_model The Options Model
     * @param string $slidedeck The SlideDeck object
     * 
     * @return array
     */
    function slidedeck_options_model( $options_model, $slidedeck ) {
        if( $this->is_valid( $slidedeck['source'] ) ) {
            if( !current_theme_supports( 'post-thumbnails' ) ) {
                unset( $options_model['Setup']['postsImageSource']['values']['thumbnail'] );
            }
        }
        
        return $options_model;
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
            $has_image = $this->get_image( $slide_nodes, $slidedeck );
            
            /**
             * If the users would like to override their post content with their 
             * post excerpt. We love our users. Really we do!
             */
            if( isset( $slidedeck['options']['use-custom-post-excerpt'] ) && !empty( $slidedeck['options']['use-custom-post-excerpt'] ) )
                if( $slidedeck['options']['use-custom-post-excerpt'] && !empty( $post->post_excerpt ) )
                $slide_nodes['content'] = $slide_nodes['excerpt'];
            
            $slide_nodes['content'] = strip_shortcodes( $slide_nodes['content'] );
            
            if( $has_image ) {
                $slide['classes'][] = "has-image";
                $slide['type'] = "image";
                $slide['thumbnail'] = $this->_get_post_thumbnail( $slide_nodes['id'], $has_image );
            } else {
                $slide['classes'][] = "no-image";
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
            $slide_nodes['title'] = convert_chars( wptexturize( $slide_nodes['title'] ) );
            
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
}
