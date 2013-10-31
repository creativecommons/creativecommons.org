<?php
class SlideDeckSource_CustomContent extends SlideDeck {
    var $label = "Custom SlideDeck";
    var $name = "custom";
    var $default_lens = "tool-kit";
    var $taxonomies = array();
    
    var $slide_types = array();
    
    var $options_model = array(
        'Setup' => array(
            'total_slides' => array(
                'type' => 'hidden',
                'value' => 999
            )
        ),
        'Appearance' => array(
            'image_scaling' => array(
                'type' => 'hidden',
                'value' => "cover"
            )
        ),
        'Playback' => array(
            'start' => array(
                'type' => 'hidden',
                'value' => 1
            )
        )
    );
    
    var $wp_editor_settings = array(
        'media_buttons' => false,
        'quicktags' => false,
        'teeny' => false,
        'textarea_rows' => 10,
        'tinymce' => array(
            'theme_advanced_font_sizes' => "10px,12px,14px,16px,18px,20px,24px,28px,32px",
            'theme_advanced_buttons1' => "bold,italic,underline,strikethrough,sup,sub,fontsizeselect,separator,bullist,numlist,indent,outdent,separator,link,unlink,separator,undo,redo,separator,removeformat,cleanup"
        )
    );
    
    function __construct() {
        parent::__construct();
        
        include( dirname( __FILE__ ) . '/slide-model.php' );
        
        $slide_type_files = (array) glob( dirname( __FILE__ ) . '/slides/*/slide.php' );
        foreach( (array) $slide_type_files as $filename ) {
            if( is_readable( $filename ) ) {
                include_once( $filename );
                
                $slug = basename( dirname( $filename ) );
                $classname = slidedeck2_get_classname_from_filename( dirname( $filename ) );
                $prefix_classname = "SlideDeckSlideType_{$classname}";
                if( class_exists( $prefix_classname ) ) {
                    $this->slide_types[$slug] = new $prefix_classname;
                }
            }
        }
        
        $this->Slide = new SlideDeckSlideModel();
    }
    
    function add_hooks() {
        add_action( 'admin_print_scripts-toplevel_page_' . SLIDEDECK2_HOOK, array( &$this, 'admin_print_scripts' ), 11 );
        add_action( 'admin_footer-toplevel_page_' . SLIDEDECK2_HOOK, array( &$this, 'admin_print_footer_scripts' ), 11 );
        add_action( 'admin_print_styles-toplevel_page_' . SLIDEDECK2_HOOK, array( &$this, 'admin_print_styles' ), 11 );
        add_action( "{$this->namespace}_after_delete", array( &$this, 'slidedeck_after_delete' ), 10, 2 );
        add_action( "{$this->namespace}_content_control", array( &$this, 'slidedeck_content_control' ) );
        add_action( "{$this->namespace}_duplicate_slidedeck", array( &$this, 'slidedeck_duplicate_slidedeck' ), 10, 2 );
        add_action( "{$this->namespace}_options_model", array( &$this, 'slidedeck_options_model' ), 20, 2 );
        add_action( "{$this->namespace}_frame_classes", array( &$this, 'slidedeck_frame_classes' ), 40, 2 );
        add_action( "{$this->namespace}_pre_load", array( &$this, 'slidedeck_pre_load' ) );
        add_action( "wp_ajax_{$this->namespace}_add_custom_slide", array( &$this, 'ajax_add_custom_slide' ) );
        add_action( "wp_ajax_{$this->namespace}_add_slide_modal", array( &$this, 'ajax_choose_slide_type_modal' ) );
        add_action( "wp_ajax_{$this->namespace}_change_slide_type", array( &$this, 'ajax_choose_slide_type' ) );
        add_action( "wp_ajax_{$this->namespace}_change_slide_type_modal", array( &$this, 'ajax_choose_slide_type_modal' ) );
        add_action( "wp_ajax_{$this->namespace}_delete_slide", array( &$this, 'ajax_delete_slide' ) );
        add_action( "wp_ajax_{$this->namespace}_slide_editor_modal", array( &$this, 'ajax_slide_editor_modal' ) );
        add_action( "wp_ajax_{$this->namespace}_update_slide", array( &$this, 'ajax_update_slide' ) );
        add_action( "wp_ajax_{$this->namespace}_update_slide_order", array( &$this, 'ajax_update_slide_order' ) );
    }
    
    /**
     * WordPress admin_print_scripts hook-in
     * 
     * @uses wp_enqueue_script()
     */
    function admin_print_scripts() {
        global $SlideDeckPlugin, $wp_scripts;
        
        if( $this->is_valid( $this->current_source ) ) {
            $slide_types = $this->get_slide_types();
            foreach( $slide_types as $slide_type ) {
                wp_enqueue_script( "slidedeck-slide-{$slide_type->name}-admin" );
            }
            
            wp_enqueue_script('editor');
            wp_enqueue_script('wplink');
            wp_enqueue_script('wpdialogs-popup');
            wp_enqueue_style('wp-jquery-ui-dialog');
        }
    }
    
    function admin_print_footer_scripts() {
        if( !$this->is_valid( $this->current_source ) ) {
            return false;
        }

        if ( ! class_exists( '_WP_Editors' ) )
            require( ABSPATH . WPINC . '/class-wp-editor.php' );
        
        /**
         * TODO: Make this not so hacky :)
         * 
         * To get things setup so we can actually have TinyMCE loaded for use in the 
         * individual slide source editors, we need to get TinyMCE loaded on the page first.
         * Unfortunately TinyMCE is not a registered script for WordPress yet, so its
         * not as easy as using wp_enqueue_script() to get it in there. TinyMCE is however
         * hard coded for output with the wp_editor() script. All we need to do is run it
         * once to get it scheduled for loading and the _WP_Editors class takes care of
         * the rest to get it loaded when we actually call the _WP_Editors::editor_js()
         */
        ob_start();
        
        wp_editor( "", $this->namespace, apply_filters( "{$this->namespace}_wp_editor_settings", $this->wp_editor_settings ) );
        
        ob_end_clean();
        
        _WP_Editors::editor_js();
    }
    
    /**
     * WordPress admin_print_styles hook-in
     * 
     * @uses wp_enqueue_style()
     */
    function admin_print_styles() {
        global $SlideDeckPlugin;
        
        if( $this->is_valid( $this->current_source ) ) {
            $slide_types = $this->get_slide_types();
            foreach( $slide_types as $slide_type ) {
                wp_enqueue_style( "slidedeck-slide-{$slide_type->name}-admin" );
            }
            
            wp_print_styles('editor-buttons');
        }
    }
    
    /**
     * Add slide AJAX response
     * 
     * @uses wp_verify_nonce()
     */
    function ajax_add_custom_slide() {
        global $SlideDeckPlugin;
        
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-choose-slide-type" ) ) {
            die( "false" );
        }
        
        $slidedeck_id = intval( $_REQUEST['slidedeck_id'] );
        $slide_type = $_REQUEST['_slide_type'];
        $slidedeck = $SlideDeckPlugin->SlideDeck->get( $slidedeck_id );
        
        $slides = $this->Slide->get_slidedeck_slides( $slidedeck_id );
        $menu_order_start = 0;
        foreach( $slides as $slide ) {
            $menu_order_start = max( $menu_order_start, $slide->menu_order );
        }
        
        $this->Slide->create( $slidedeck_id, $slide_type, array( 'menu_order' => $menu_order_start + 1 ) );
        
        $this->slidedeck_content_control( $slidedeck );
        exit;
    }
    
    function ajax_choose_slide_type() {
        global $SlideDeckPlugin;
        
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-choose-slide-type" ) ) {
            die( "false" );
        }
        
        $slidedeck_id = intval( $_REQUEST['slidedeck_id'] );
        $slidedeck_id = $SlideDeckPlugin->SlideDeck->get_parent_id( $slidedeck_id );
        $slide_id = intval( $_REQUEST['slide_id'] );
        $slide_type = $_REQUEST['_slide_type'];
        $slidedeck = $SlideDeckPlugin->SlideDeck->get( $slidedeck_id );
        
        $this->Slide->change_slide_type( $slide_id, $slide_type );
        
        $slides = $this->Slide->get_slidedeck_slides( $slidedeck_id, false );
        
        $this->slidedeck_content_control( $slidedeck );
        exit;
     }
    
    /**
     * Delete slide AJAX response
     * 
     * @uses wp_verify_nonce()
     * @uses SlideDeckSource::delete()
     */
    function ajax_delete_slide() {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-delete-slide" ) ) {
            die( "false" );
        }
        
        $slide_id = intval( $_REQUEST['slide_id'] );
        if( $this->Slide->delete( $slide_id ) ) {
            die( "true" );
        }
        
        die( "false" );
    }
    
    /**
     * Change slide type modal AJAX response
     * 
     * @uses wp_verify_nonce()
     * @uses wp_die()
     * @uses SlideDeckSource_Custom::get_slide_types()
     */
    function ajax_choose_slide_type_modal() {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-change-slide-type-modal" ) ) {
            wp_die( '<h3>' . __( "You are not authorized to access this page", $this->namespace ) . '</h3><p>' . __( "The page you are attempting to access requires higher permission privileges than you currently have. Please make sure you typed in the correct URL or ask your administrator to elevate your privileges.", $this->namespace ) . '</p>' );
        }
        
        // Default URL for closing the flyout
        $cancel_url = "#cancel";
        
        $action = "slidedeck_add_custom_slide";
        $title = "Choose Your Slide Type";
        if( $_REQUEST['action'] == "{$this->namespace}_change_slide_type_modal" ) {
            $action = "slidedeck_change_slide_type";
            $slide_id = intval( $_REQUEST['slide_id'] );
            $title = "Change Your Slide Type";
            
            // If this is a change slide type flyout, the cancellation URL should take the user back to the editing flyout
            $cancel_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=slidedeck_slide_editor_modal&slide_id=' . $slide_id ), "{$this->namespace}-slide-editor-modal" );
        }
        
        $namespace = $this->namespace;
        $slidedeck_id = intval( $_REQUEST['slidedeck'] );
        $slide_types = $this->get_slide_types();
        
        $this->get_source_file();
        
        // If Professional is not installed
        if( !defined( 'SLIDEDECK2_PROFESSIONAL_VERSION' ) ) {
            $slide_types[] = (object) array(
                'thumbnail' => $this->baseurl . '/images/slide-textonly-thumbnail.png',
                'label' => "Text Only",
                'name' => "textonly",
                'disabled' => true
            );
            $slide_types[] = (object) array(
                'thumbnail' => $this->baseurl . '/images/slide-video-thumbnail.png',
                'label' => "Video",
                'name' => "video",
                'disabled' => true
            );
        }
        
        // If Developer version is not installed
        if( !defined( 'SLIDEDECK2_DEVELOPER_VERSION' ) ) {
            $slide_types[] = (object) array(
                'thumbnail' => $this->baseurl . '/images/slide-html-thumbnail.png',
                'label' => "HTML",
                'name' => "html",
                'disabled' => true
            );
        }
        
        include( dirname( __FILE__ ) . '/views/choose-type.php' );
        exit;
    }
    
    /**
     * Update slide AJAX response
     * 
     * @global $SlideDeckPlugin
     * 
     * @uses do_action()
     * @uses SlideDeck::get()
     * @uses SlideDeckSource::get()
     * @uses SlideDeckSource_Custom::slidedecK_content_control()
     * @uses wp_die()
     * @uses wp_verify_nonce()
     */
    function ajax_update_slide() {
        global $SlideDeckPlugin;
        
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-custom-slide-editor-form" ) ) {
            wp_die( '<h3>' . __( "You are not authorized to access this page", $this->namespace ) . '</h3><p>' . __( "The page you are attempting to access requires higher permission privileges than you currently have. Please make sure you typed in the correct URL or ask your administrator to elevate your privileges.", $this->namespace ) . '</p>' );
        }
        
        $slide_id = intval( $_REQUEST['slide_id'] );
        $slide = $this->Slide->get( $slide_id );
        $slidedeck_id = $slide->post_parent;
        $slidedeck = $SlideDeckPlugin->SlideDeck->get( $slidedeck_id );
        $data = slidedeck2_sanitize( $_POST );
            
        do_action( "{$this->namespace}_update_slide", $slide, $data );
        
        $this->slidedeck_content_control( $slidedeck );
        exit;
    }
    
    /**
     * Update slide ordering
     * 
     * @uses SlideDeckSource::update_order()
     * @uses wp_die()
     * @uses wp_verify_nonce()
     */
    function ajax_update_slide_order() {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce_update_slide_order'], "{$this->namespace}-update-slide-order" ) ) {
            wp_die( '<h3>' . __( "You are not authorized to access this page", $this->namespace ) . '</h3><p>' . __( "The page you are attempting to access requires higher permission privileges than you currently have. Please make sure you typed in the correct URL or ask your administrator to elevate your privileges.", $this->namespace ) . '</p>' );
        }
        
        $this->Slide->update_order( $_REQUEST['slide_order'] );
        exit;
    }
    
    /**
     * Slide editing modal AJAX response
     * 
     * @uses wp_verify_nonce()
     * @uses wp_die()
     * @uses SlideDeckSlide::get()
     * @uses SlideDeck::get()
     */
    function ajax_slide_editor_modal() {
        global $SlideDeckPlugin;
        
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-slide-editor-modal" ) ) {
            wp_die( '<h3>' . __( "You are not authorized to access this page", $this->namespace ) . '</h3><p>' . __( "The page you are attempting to access requires higher permission privileges than you currently have. Please make sure you typed in the correct URL or ask your administrator to elevate your privileges.", $this->namespace ) . '</p>' );
        }
        
        // Do not cache this page
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        
        $namespace = $this->namespace;
        $slide_id = (int) $_REQUEST['slide_id'];
        $slide = $this->Slide->get( $slide_id );
        
        $slidedeck_id = $SlideDeckPlugin->SlideDeck->get_preview_id( $slide->post_parent );
        if( empty( $slidedeck_id ) ) {
            $slidedeck_id = $slide->post_parent;
        }
        
        $slidedeck = $SlideDeckPlugin->SlideDeck->get( $slidedeck_id );
        $slide_types = $this->get_slide_types();
        
        include( dirname( __FILE__ ) . '/views/slide-editor.php' );
        exit;
    }
    
    /**
     * Get available slide types
     * 
     * Returns an array of SlideDeck 2 slide type objects
     * 
     * @return array
     */
    function get_slide_types() {
        $slide_types = (array) apply_filters( "{$this->namespace}_get_slide_types", $this->slide_types );
        
        return $slide_types;
    }
    
    /**
     * Get the thumbnail for a Slide
     * 
     * Returns the URL of the thumbnail for a slide (if it has a thumbnail)
     * 
     * @param mixed $slide The Slide object or ID
     * 
     * @return string
     */
    function get_slide_thumbnail( $slide ) {
        if( !is_object( $slide ) ) {
            $slide = $this->Slide->get( $slide );
        }
        
        $slide_types = $this->get_slide_types();
        
        $thumbnail = $slide_types[$slide->meta['_slide_type']]->slide_default_thumbnail;
        $thumbnail = apply_filters( "{$this->namespace}_get_slide_thumbnail", $thumbnail, $slide );
        
        return $thumbnail;
    }
    
    /**
     * Register scripts used by Decks
     * 
     * @uses wp_register_script()
     */
    function register_scripts() {
        // Fail silently if this is not a sub-class instance
        if( !isset( $this->name ) ) {
            return false;
        }
        
        wp_register_script( "slidedeck-deck-{$this->name}-admin", SLIDEDECK2_URLPATH . '/sources/' . $this->name . '/source.js', array( 'jquery', 'slidedeck-admin' ), SLIDEDECK2_VERSION, true );
    }
        
    /**
     * Register styles used by Decks
     * 
     * @uses wp_register_style()
     */
    function register_styles() {
        // Fail silently if this is not a sub-class instance
        if( !isset( $this->name ) ) {
            return false;
        }
        
        wp_register_style( "slidedeck-deck-{$this->name}-admin", SLIDEDECK2_URLPATH . '/sources/' . $this->name . '/source.css', array( 'slidedeck-admin' ), SLIDEDECK2_VERSION, 'screen' );
    }
    
    /**
     * Process slide nodes through the chosen template
     * 
     * Takes an array of slide nodes and processes its properties through the
     * appropriate slide type template, returning a string of the compiled HTML.
     * 
     * @param array $nodes Nodes available to a slide for template processing
     * @param object $slide Slide object
     * @param string $deck_iteration Iteration of SlideDeck on the page
     * 
     * @return string
     */
    function process_template( $nodes, $slidedeck, $deck_iteration ) {
        global $SlideDeckPlugin;
        
        $html = "";
        
        $slide_type = $nodes['type'];
        $slide_types = $this->get_slide_types();
        
        if( isset( $slide_types[$slide_type] ) ) {
            $template = $slide_types[$slide_type]->filepath . "/template.thtml";
            
            if( file_exists( $template ) ) {
                // Unset template node so as not to overwrite the filepath for the template itself
                if( isset( $nodes['template'] ) ) unset( $nodes['template'] );
                
                // Figure out the size of the SlideDeck itself
                $size = $slidedeck['options']['size'];
                if( $size == "custom" ) {
                    $size = $SlideDeckPlugin->SlideDeck->get_closest_size( $slidedeck );
                }
                
                // Extract all values to be available as variables in the template
                extract( $nodes );
                
                // Provide a filtered content value to templates so we don't need to run a blanket apply_filters('the_content') on the output
                $filtered_content = do_shortcode( shortcode_unautop( wpautop( convert_chars( convert_smilies( wptexturize( $content ) ) ) ) ) );
                
                if( isset( $video_meta ) ){
                    $video_container = "<div id=\"video__{$video_meta['id']}__{$slidedeck['id']}-{$deck_iteration}-{$slide_counter}\" class=\"{$video_meta['service']} video-container\" data-video-id=\"{$video_meta['id']}\"></div>";
                }
                
                ob_start();
                    include( $template );
                    $html = ob_get_contents();
                ob_end_clean();
                
                // Add full slide link to all slides
                if( isset( $permalink ) && !empty( $permalink ) ) {
                    $html .= '<a href="' . $permalink . '" class="full-slide-link-hit-area" target="' . $target . '"></a>';
                }
            }
        }
        
        return $html;
    }
    
    /**
     * Hook into slidedeck_after_delete action
     * 
     * Deletes all child slides associated with the SlideDeck after it 
     * has been deleted.
     * 
     * @param int $slidedeck_id SlideDeck ID
     * @param array $source SlideDeck sources
     * 
     * @uses SlideDeck::is_valid()
     * @uses SlideDeckSource::get_slides()
     * @uses SlideDeckSource::delete()
     */
    function slidedeck_after_delete( $slidedeck_id, $source ) {
        if( $this->is_valid( $source ) ) {
            $slides = $this->Slide->get_slidedeck_slides( $slidedeck_id );
            
            foreach( $slides as $slide ) {
                $this->Slide->delete( $slide->ID );
            }
        }
    }
    
    /**
     * Hook into slidedeck_content_control action
     * 
     * Out put Custom SlideDeck slide content control interface
     * 
     * @uses SlideDeck::is_valid()
     * @uses SlideDeckSource::get_slides()
     */
    function slidedeck_content_control( $slidedeck ) {
        if( $this->is_valid( $slidedeck['source'] ) ) {
            $namespace = $this->namespace;
            $slidedeck_id = $slidedeck['id'];
            $slides = $this->Slide->get_slidedeck_slides( $slidedeck_id, false );
            
            include( dirname( __FILE__ ) . '/views/slides.php' );
        }
    }
    
    /**
     * Hook into slidedeck_duplicate_slidedeck action
     * 
     * When a custom SlideDeck is duplicated, make sure that all its slides are duplicated as well
     *
     * @param int $slidedeck_id The ID of the SlideDeck being duplicated
     * @param int $slidedeck_copy_id The ID of the SlideDeck dupe 
     */
    function slidedeck_duplicate_slidedeck( $slidedeck_id, $slidedeck_copy_id ) {
        global $wpdb;
        
        $slidedeck_source = get_post_meta( $slidedeck_id, 'slidedeck_source' );
        
        // Make sure that the SlideDeck being duplicated is a custom SlideDeck
        if( $this->is_valid( $slidedeck_source ) ) {
            $slides = $this->Slide->get_slidedeck_slides( $slidedeck_id );
            
            $slide_count = 0;
            foreach( $slides as $slide ) {
                $post_args = array(
                    'menu_order' => $slide->menu_order,
                    'post_type' => $slide->post_type,
                    'post_title' => $slide->post_title,
                    'post_excerpt' => $slide->post_excerpt,
                    'post_content' => $slide->post_content,
                    'post_content_filtered' => $slide->post_content,
                    'post_parent' => $slidedeck_copy_id,
                    'post_author' => $slide->post_author,
                    'post_status' => "publish"
                );
                $slide_copy_id = wp_insert_post( $post_args );
                
                foreach( $slide->meta as $meta_key => $meta_value ) {
                    update_post_meta( $slide_copy_id, $meta_key, $meta_value );
                }
            }
        }
    }
    
    /**
     * Filter the date format classes on Custom Slides
     * 
     * @param array $slidedeck_classes Classes to be applied
     * @param array $slidedeck The SlideDeck object being rendered
     * 
     * @return array
     */
    function slidedeck_frame_classes( $slidedeck_classes, $slidedeck ) {
        if( $slidedeck['source'][0] == 'custom' ) {
            $prefix = 'date-format-';
            $excluded_classes = array(
                $prefix . 'none',
                $prefix . 'timeago',
                $prefix . 'human-readable',
                $prefix . 'human-readable-abbreviated',
                $prefix . 'raw',
                $prefix . 'raw-eu'
            );
            
            foreach( $slidedeck_classes as $id => $class ) {
                if( in_array( $class, $excluded_classes ) ) {
                    unset( $slidedeck_classes[ $id ] );
                }
            }
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
     * @global $wp_scripts
     * @global $wp_styles
     * 
     * @return array
     */
    function slidedeck_get_slides( $slides, $slidedeck ) {
        global $SlideDeckPlugin, $wp_scripts, $wp_styles;
        
        // Fail silently if not this Deck type
        if( !$this->is_valid( $slidedeck['source'] ) ) {
            return $slides;
        }
        
        $slidedeck_id = $SlideDeckPlugin->SlideDeck->get_parent_id( $slidedeck['id'] );
        
        // How many decks are on the page as of now.
        $deck_iteration = isset( $SlideDeckPlugin->SlideDeck->rendered_slidedecks[$slidedeck_id] ) ? $SlideDeckPlugin->SlideDeck->rendered_slidedecks[$slidedeck_id] : 1;
        
        // The slide objects for each slide associated with this SlideDeck
        $slidedeck_slides = $this->Slide->get_slidedeck_slides( $slidedeck_id );
        
        // Slide counter
        $slide_counter = 1;
        
        // Loop through each slide for processing
        foreach( $slidedeck_slides as $slidedeck_slide ) {
            $slide_nodes = array(
                'author_name' => "",
                'content' => "",
                'counter' => $slide_counter++,
                'created_at' => $slidedeck_slide->post_date_gmt,
                'excerpt' => "",
                'id' => $slidedeck_slide->ID,
                'local_created_at' => $slidedeck_slide->post_date,
                'permalink' => isset( $slidedeck_slide->meta['_permalink'] ) ? $slidedeck_slide->meta['_permalink'] : "",
                'target' => $slidedeck['options']['linkTarget'],
                'template' => "default",
                'title' => get_the_title( $slidedeck_slide->ID ),
                'type' => (string) $slidedeck_slide->meta['_slide_type']
            );
            $slide_nodes = apply_filters( "{$this->namespace}_custom_slide_nodes", $slide_nodes, $slidedeck_slide, $slidedeck );
            
            // The slide array being returned
            $slide = array(
                'created_at' => $slide_nodes['created_at'],
                'image' => "",
                'source' => $this->name,
                'thumbnail' => "",
                'title' => $slide_nodes['title'],
                'type' => $slide_nodes['type'],
                'video' => "",
                'id' => $slide_nodes['id']
            );
            
            // Image presence classes
            if( isset( $slide_nodes['image'] ) ) {
                $slide['image'] = $slide_nodes['image'];
                $slide['classes'][] = "has-image";
                $slide['thumbnail'] = isset( $slide_nodes['thumbnail'] ) ? $slide_nodes['thumbnail'] : $slide_nodes['image'];
            } else {
                $slide['classes'][] = "no-image";
            }
            
            // Video presence classes
            if( isset( $slide_nodes['video'] ) ) {
                $slide['video'] = $slide_nodes['video'];
                $slide['classes'][] = "has-video";
            }
            
            // Excerpt presence classes
            if( !empty( $slide_nodes['excerpt'] ) ) {
                $slide['classes'][] = "has-excerpt";
            } else {
                $slide['classes'][] = "no-excerpt";
            }
            
            // Title presence classes
            if( !empty( $slide_nodes['title'] ) ) {
                $slide['classes'][] = "has-title";
            } else {
                $slide['classes'][] = "no-title";
            }
            
            // Add the layout and caption position classes
            $slide['classes'][] = "custom-layout-{$slidedeck_slide->meta['_layout']}";
            $slide['classes'][] = "custom-caption-position-{$slidedeck_slide->meta['_caption_position']}";
            
            // Add the image scaling class
            $slide['classes'][] = "sd2-image-scaling-" . $slidedeck_slide->meta['_image_scaling'];
            
            // Compiled slide content HTML
            $slide['content'] = $this->process_template( $slide_nodes, $slidedeck, $deck_iteration );
            
            $slide_asset_slug = "slidedeck-slide-{$slide['type']}";
            
            /**
             * Check to see if the page has a mixture of deck types.
             * The $SlideDeckPlugin->only_has_iframe_decks propety will return false
             * if it is checked in an iFrame, or if there are a mixture of deck
             * types on the page.
             */
            if( !$SlideDeckPlugin->only_has_iframe_decks ) {
                // Load slide type JavaScript files if they haven't been loaded already
                if( isset( $wp_scripts->registered[$slide_asset_slug] ) ) {
                    if( !isset( $SlideDeckPlugin->loaded_slide_scripts[$slide['type']] ) && !isset( $wp_scripts->queue[$slide_asset_slug] ) ) {
                        $slide_script_url = $wp_scripts->registered[$slide_asset_slug]->src . "?v=" . $wp_scripts->registered[$slide_asset_slug]->ver;
                        $SlideDeckPlugin->footer_scripts .= '<script type="text/javascript" src="' . $slide_script_url .'"></script>';
                        
                        // Mark script as loaded
                        $SlideDeckPlugin->loaded_slide_scripts[$slide['type']] = true;
                    }
                }
                
                // Load slide type stylesheets if they haven't been loaded already
                if( isset( $wp_styles->registered[$slide_asset_slug] ) ) {
                    if( !isset( $SlideDeckPlugin->loaded_slide_styles[$slide['type']] ) && !isset( $wp_styles->queue[$slide_asset_slug] ) ) {
                        $slide_style_url = $wp_styles->registered[$slide_asset_slug]->src . "?v=" . $wp_styles->registered[$slide_asset_slug]->ver;
                        $SlideDeckPlugin->footer_scripts .= '<link rel="stylesheet" type="text/css" href="' . $slide_style_url .'" />';
                        
                        // Mark stylesheet as loaded
                        $SlideDeckPlugin->loaded_slide_styles[$slide['type']] = true;
                    }
                }
            }
            
            
            $slides[] = $slide;
        }
        
        return $slides;
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
     * SlideDeck Options Model
     * 
     * @param array $options_model The Options Model
     * @param string $slidedeck SlideDeck object
     * 
     * @return array
     */
    function slidedeck_options_model( $options_model, $slidedeck ) {
        if( $this->is_valid( $slidedeck['source'] ) ) {
            $options_model['Setup']['total_slides'] = array(
                'type' => 'hidden',
                'name' => 'total_slides',
                'value' => 999
            );
            
            foreach( $options_model['Content'] as $key => $val ) {
                $safe_keys = array(
                    'cache_duration',
                    'linkTarget',
                    'show-link-slide'
                );
                if( !in_array( $key, $safe_keys ) ) {
                    $options_model['Content'][$key]['type'] = "hidden";
                }
            }
            
            $options_model['Content']['show-title']['value'] = true;
            $options_model['Content']['show-excerpt']['value'] = true;
            $options_model['Appearance']['text-color']['type'] = "hidden";
            $options_model['Appearance']['text-position']['type'] = "hidden";
        }
        
        return $options_model;
    }
    
    /**
     * Hook into slidedeck_pre_load action
     * 
     * @param array $slidedeck SlideDeck object
     */
    function slidedeck_pre_load( $slidedeck ) {
        if( $this->is_valid( $slidedeck['source'] ) ) {
            $slides = $this->Slide->get_slidedeck_slides( $slidedeck['id'] );
            foreach( $slides as $slide ) {
                wp_enqueue_style( "slidedeck-slide-{$slide->meta['_slide_type']}" );
                wp_enqueue_script( "slidedeck-slide-{$slide->meta['_slide_type']}" );
            }
        }
    }
}

/**
 * Template function alias for SlideDeckSource_CustomContent::get_slide_thumbnail()
 * 
 * @global $SlideDeckPlugin
 * 
 * @uses SlideDeckSource_CustomContent::get_slide_thumbnail()
 */
function slidedeck2_get_slide_thumbnail( $slide ) {
    global $SlideDeckPlugin;
    
    return $SlideDeckPlugin->sources['custom']->get_slide_thumbnail( $slide );
}
