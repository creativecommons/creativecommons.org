<?php
class SlideDeckSlideType_Image extends SlideDeckSlideModel {
	var $name = "image";
	var $label = "Image";
	
	var $defaults = array(
		'_caption_position' => "bottom",
		'_image_attachment' => "",
		'_image_source' => "upload",
		'_image_url' => "",
		'_layout' => "caption",
		'_permalink' => ""
	);
	
	// Available layouts
	var $layouts = array(
		'caption' => "Caption",
		'body-text' => "Body Text",
		'none' => "None"
	);
	
	// Available caption positions for all layouts
	var $caption_positions = array(
		'top' => "Top",
		'bottom' => "Bottom",
		'left' => "Left",
		'right' => "Right"
	);
	
	function __construct() {
		$this->filepath = dirname( __FILE__ );
		$this->url = SLIDEDECK2_URLPATH . '/sources/custom/slides/image';
		$this->thumbnail = $this->url . '/thumbnail.png';
        $this->thumbnail_small = $this->url . '/thumbnail-small.png';
		$this->slide_default_thumbnail = $this->url . '/default-thumbnail.jpg';
		
		add_action( "admin_init", array( &$this, 'admin_init' ) );
        add_action( 'admin_print_scripts-toplevel_page_' . SLIDEDECK2_HOOK, array( &$this, 'admin_print_scripts' ), 11 );
        add_action( "admin_print_scripts-media-upload-popup", array( &$this, 'admin_print_scripts_media_upload_popup' ) );
		add_action( 'init', array( &$this, 'register_assets' ) );
		add_action( "{$this->namespace}_update_slide", array( &$this, 'slidedeck_update_slide' ), 10, 2 );
		add_action( "{$this->namespace}_after_create_slide", array( &$this, 'slidedeck_after_create_slide' ), 10, 3 );
        add_action( "{$this->namespace}_change_slide_type", array( &$this, 'slidedeck_change_slide_type' ), 10, 3 );
        add_action( "{$this->namespace}_custom_slide_editor_form", array( &$this, 'slidedeck_custom_slide_editor_form' ), 10, 2 );
        add_action( "{$this->namespace}_get_slide_thumbnail", array( &$this, 'slidedeck_get_slide_thumbnail' ), 10, 2 );
		add_action( "{$this->namespace}_before_custom_slide_editor_form", array( &$this, 'slidedeck_before_custom_slide_editor_form' ), 10, 2 );
        add_action( "wp_ajax_{$this->namespace}_get_slide_attachment_thumbnail_url", array( &$this, 'ajax_get_slide_attachment_thumbnail_url' ) );
		add_action( "wp_ajax_{$this->namespace}_html4_image_upload_form", array( &$this, 'ajax_html4_image_upload_form' ) );
		add_action( "wp_ajax_{$this->namespace}_slide_upload_image", array( &$this, 'ajax_slide_upload_image' ) );
        add_action( "wp_ajax_{$this->namespace}_slide_add_from_medialibrary", array( &$this, 'ajax_slide_add_from_medialibrary' ) );
        add_action( "wp_ajax_{$this->namespace}_slide_bulk_upload", array( &$this, 'ajax_slide_bulk_upload' ) );

		add_filter( "{$this->namespace}_custom_slide_nodes", array( &$this, 'slidedeck_slide_nodes' ), 10, 3 );
	}
	
	function admin_init() {
		wp_register_script( "{$this->namespace}-medialibrary-media-upload-popup", $this->url . '/js/medialibrary.js', array( 'jquery' ), '1.0.0' );
		wp_register_script( "{$this->namespace}-browserplus", 'http://bp.yahooapis.com/2.4.21/browserplus-min.js', array( 'jquery' ), '1.5.4' );
		wp_register_script( "{$this->namespace}-plupload", $this->url . '/js/plupload.js', array( 'jquery' ), '1.5.4' );
		wp_register_script( "{$this->namespace}-plupload-gears", $this->url . '/js/plupload.gears.js', array( 'jquery', "{$this->namespace}-plupload" ), '1.5.4' );
		wp_register_script( "{$this->namespace}-plupload-silverlight", $this->url . '/js/plupload.silverlight.js', array( 'jquery', "{$this->namespace}-plupload" ), '1.5.4' );
		wp_register_script( "{$this->namespace}-plupload-flash", $this->url . '/js/plupload.flash.js', array( 'jquery', "{$this->namespace}-plupload" ), '1.5.4' );
		wp_register_script( "{$this->namespace}-plupload-browserplus", $this->url . '/js/plupload.browserplus.js', array( 'jquery', "{$this->namespace}-plupload" ), '1.5.4' );
		wp_register_script( "{$this->namespace}-plupload-html4", $this->url . '/js/plupload.html4.js', array( 'jquery', "{$this->namespace}-plupload" ), '1.5.4' );
		wp_register_script( "{$this->namespace}-plupload-html5", $this->url . '/js/plupload.html5.js', array( 'jquery', "{$this->namespace}-plupload" ), '1.5.4' );
	}
    
    /**
     * Hook into admin_print_scripts-toplevel_page_slidedeck2 action
     * 
     * Outputs all necessary JavaScript support libraries for this slide type when a
     * custom SlideDeck is being edited.
     * 
     * @global $SlideDeckPlugin
     * 
     * @uses wp_enqueue_script()
     */
    function admin_print_scripts() {
        global $SlideDeckPlugin;
        
        // Only load if the current SlideDeck's source is "custom" 
        if( in_array( 'custom', $SlideDeckPlugin->SlideDeck->current_source ) ) {
            wp_enqueue_script( "{$this->namespace}-browserplus" );
            wp_enqueue_script( "{$this->namespace}-plupload" );
            wp_enqueue_script( "{$this->namespace}-plupload-gears" );
            wp_enqueue_script( "{$this->namespace}-plupload-silverlight" );
            wp_enqueue_script( "{$this->namespace}-plupload-flash" );
            wp_enqueue_script( "{$this->namespace}-plupload-browserplus" );
            wp_enqueue_script( "{$this->namespace}-plupload-html4" );
            wp_enqueue_script( "{$this->namespace}-plupload-html5" );
        }
    }
    
    /**
     * Load JavaScript in the Media Library Popup
     */
    function admin_print_scripts_media_upload_popup() {
        wp_enqueue_script( "{$this->namespace}-medialibrary-media-upload-popup" );
        
        echo '<script type="text/javascript">var _medialibrary_nonce = "' . wp_create_nonce( "{$this->namespace}-medialibrary-add-images" ) . '";</script>';
    }
    
    /**
     * AJAX response to get a slide's thumbnail
     */
    function ajax_get_slide_attachment_thumbnail_url() {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-get-slide-thumbnail-url" ) ) {
            wp_die( '<h3>' . __( "You are not authorized to access this page", $this->namespace ) . '</h3><p>' . __( "The page you are attempting to access requires higher permission privileges than you currently have. Please make sure you typed in the correct URL or ask your administrator to elevate your privileges.", $this->namespace ) . '</p>' );
        }
        
        $slide_id = intval( $_REQUEST['slide_id'] );
        $slide_meta = $this->get_meta( $slide_id, false );
        $media_meta = $this->get_media_meta( $slide_meta['_image_attachment'] );
        $thumbnail_url = $media_meta['src'][0];
        
        die( $thumbnail_url );
    }

	/**
	 * AJAX Upload form for HTML4 legacy support
	 * 
	 * @uses wp_die()
	 * @uses wp_verify_nonce()
	 */
	function ajax_html4_image_upload_form() {
		if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-html4-upload-form" ) ) {
			wp_die( '<h3>' . __( "You are not authorized to access this page", $this->namespace ) . '</h3><p>' . __( "The page you are attempting to access requires higher permission privileges than you currently have. Please make sure you typed in the correct URL or ask your administrator to elevate your privileges.", $this->namespace ) . '</p>' );
		}
		
		$namespace = $this->namespace;
		$slide_id = intval( $_REQUEST['slide_id'] );
		
		include( dirname( __FILE__ ) . '/views/_image-upload-html4-form.php' );
		exit;
	}
	
	/**
	 * AJAX Upload response
	 * 
	 * Processes upload of image and attaches it to the Slide.
	 * 
	 * @uses current_user_can()
	 * @uses is_wp_error()
	 * @uses sanitize_title()
	 * @uses SlideDeckSource_Image::get_media_meta()
	 * @uses wp_check_filetype_and_ext()
	 * @uses wp_generate_attachment_metadata()
	 * @uses wp_handle_upload()
	 * @uses wp_insert_attachment()
	 * @uses wp_read_image_metadata()
	 * @uses wp_update_attachment_metadata()
	 * @uses wp_verify_nonce()
	 * @uses WP_Error::get_error_code()
	 * @uses WP_Error::get_error_data()
	 */
	function ajax_slide_upload_image() {
		if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-slide-upload-image" ) ) {
			return false;
		}
		
		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		$slide_id = intval( $_REQUEST['slide_id'] );
        $slidedeck_id = intval( $_REQUEST['slidedeck'] );
		$file_id = 'file';
		
		// A correct MIME type will pass this test. Override $mimes or use the upload_mimes filter.
		$wp_filetype = wp_check_filetype_and_ext( $_FILES[$file_id]['tmp_name'], $_FILES[$file_id]['name'] );

		// Check to see if wp_check_filetype_and_ext() determined the filename was incorrect
		if ( $wp_filetype['proper_filename'] )
			$uploaded_file['name'] = $proper_filename;

		if ( ( !$wp_filetype['type'] || !$wp_filetype['ext'] ) && !current_user_can( 'unfiltered_upload' ) ) {
			die( '{"jsonrpc" : "2.0", "error" : {"code": "101", "message": "' . __( 'Sorry, this file type is not permitted for security reasons.' ) . '" }}');
		}

		if ( !$wp_filetype['ext'] )
			$wp_filetype['ext'] = ltrim( strrchr( $uploaded_file['name'], '.' ), '.' );

		if ( !$wp_filetype['type'] )
			$wp_filetype['type'] = $file['type'];
	
		$name = $_FILES[$file_id]['name'];
		$file = wp_handle_upload( $_FILES[$file_id], array( 'test_form' => false ) );
	
		if ( isset( $file['error'] ) ) {
			$error = new WP_Error( 'upload_error', $file['error'] );
			die( '{"jsonrpc" : "2.0", "error" : {"code": "' . $error->get_error_code() . '", "message": "' . $error->get_error_data() . '" }}');
		}
	
		$name_parts = pathinfo($name);
		$name = trim( substr( $name, 0, -(1 + strlen($name_parts['extension'])) ) );
	
		$url = $file['url'];
		$type = $file['type'];
		$file = $file['file'];
		$title = $name;
		$content = '';
	
		// use image exif/iptc data for title and caption defaults if possible
		if( $image_meta = @wp_read_image_metadata( $file ) ) {
			if( trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) )
				$title = $image_meta['title'];
			if( trim( $image_meta['caption'] ) )
				$content = $image_meta['caption'];
		}
	
		// Construct the attachment array
		$attachment = array(
			'post_mime_type' => $type,
			'guid' => $url,
			'post_parent' => $slidedeck_id,
			'post_title' => $title,
			'post_content' => $content
		);
	
		// This should never be set as it would then overwrite an existing attachment.
		if ( isset( $attachment['ID'] ) )
			unset( $attachment['ID'] );
		
		// Save the data
		$id = wp_insert_attachment( $attachment, $file, $slidedeck_id );
		if ( !is_wp_error( $id ) ) {
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
		}
		
		$response = array(
			"jsonrpc" => "2.0",
			"id" => $id
		);
		
		update_post_meta( $slide_id, "_image_attachment", $id );
		
		// Return JSON-RPC response
		die( json_encode( $response ) );
	}

    /**
     * AJAX response for adding an image to a slide from the media library
     * 
     * @uses wp_verify_nonce()
     */
    function ajax_slide_add_from_medialibrary() {
        $response = array(
            'valid' => true,
            'error' => ""
        );
        
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-medialibrary-add-images" ) ) {
            $response['valid'] = false;
            $response['error'] = __( "Validation failed", $this->namespace );
        }
        
        // Make sure media_id and slide_id are passed in
        if( !isset( $_REQUEST['media_id'] ) || !isset( $_REQUEST['slide_id'] ) ) {
            $response['valid'] = false;
            $response['error'] = __( "You did not pass a valid slide or media ID", $this->namespace );
        }
        
        if( $response['valid'] === true ) {
            // Clean passed in data
            $media_id = intval( $_REQUEST['media_id'] );
            $slide_id = intval( $_REQUEST['slide_id'] );
            
            update_post_meta( $slide_id, "_image_attachment", $media_id );
            
            $response['media_meta'] = $this->get_media_meta( $media_id );
            $response['filename'] = basename( $response['media_meta']['meta']['file'] );
        }
        
        die( json_encode( $response ) );
    }
    
    /**
     * AJAX response for bulk image upload and slide addition
     * 
     * @uses SlideDeckSlide::create()
     * @uses update_post_meta()
     * @uses wp_verify_nonce()
     */
    function ajax_slide_bulk_upload() {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-medialibrary-add-images" ) ) {
            return false;
        }
        
        $slidedeck_id = intval( $_REQUEST['slidedeck'] );
        $media_ids = array_map( 'intval', $_REQUEST['media'] );
        $namespace = $this->namespace;
        
        $response = array(
            'valid' => true
        );
        
        // Loop through the existing slides to find the highest menu_order value to ensure slides are added to the end of the SlideDeck
        $slides = $this->get_slidedeck_slides( $slidedeck_id );
        $menu_order_start = 0;
        foreach( $slides as $slide ) {
            $menu_order_start = max( $menu_order_start, $slide->menu_order );
        }
        
        foreach( $media_ids as $media_id ) {
            $media_meta = $this->get_media_meta( $media_id );
            $slide_meta = array(
                'menu_order' => $menu_order_start + 1,
                'post_title' => $media_meta['post']->post_title,
            );
            if( $slide_id = $this->create( $slidedeck_id, $this->name, $slide_meta ) ) {
                update_post_meta( $slide_id, "_image_attachment", $media_id );
                $menu_order_start++;
            } else {
                $response['valid'] = false;
            }
        }
        
        // If everything went well, update the content control area with new HTML that contains the new slides
        if( $response['valid'] === true ) {
            $slides = $this->get_slidedeck_slides( $slidedeck_id, false );
            
            ob_start();
                include( SLIDEDECK2_DIRNAME . '/sources/custom/views/slides.php' );
                $response['html'] = ob_get_contents();
            ob_end_clean();
        }

        die( json_encode( $response ) );
    }
    
    /**
     * Get Media Meta
     * 
     * Retrieves all relevant meta for any media entries and returns an array of
     * data keyed on the media ID.
     * 
     * @param mixed $media_ids Array of media IDs or single integer for one media ID
     * 
     * @uses WP_Query
     * @uses wp_get_attachment_metadata()
     * @uses wp_get_attachment_image_src()
     * 
     * @return array
     */
    function get_media_meta( $media_ids ) {
        $single = false;
        
        if( !is_array( $media_ids ) ) {
            $media_ids = array( $media_ids );
            $single = true;
        }
        
        $query_args = array(
            'post__in' => $media_ids,
            'post_type' => 'attachment',
            'post_status' => 'any',
            'nopaging' => true
        );
        $query = new WP_Query( $query_args );
		
        $media = array();
        foreach( $media_ids as $media_id ) {
            $image = array(
                'meta' => wp_get_attachment_metadata( $media_id ),
                'src' => wp_get_attachment_image_src( $media_id, array( 96, 96 ) )
            );
            
            $media_link = get_post_meta( $media_id, "{$this->namespace}_media_link", true );
            if( empty( $media_link ) )
                $media_link = get_attachment_link( $media_id );
            
            $image['media_link'] = $media_link;
            
            foreach( $query->posts as $post ) {
                if( $post->ID == $media_id )
                $image['post'] = $post;
            }
            
            $media[$media_id] = $image;
        }
        
        if( $single )
            return reset( $media );
        else 
            return $media;
    }

	/**
	 * Hook into WordPress init action
	 * 
	 * Register any needed assets for this slide type
	 */
	function register_assets() {
		wp_register_style( "slidedeck-slide-{$this->name}", $this->url . '/slide.css', array(), '1.0', "screen" );
		wp_register_style( "slidedeck-slide-{$this->name}-admin", $this->url . '/slide-admin.css', array(), '1.0', "screen" );
		wp_register_script( "slidedeck-slide-{$this->name}", $this->url . '/slide.js', array( 'jquery', 'slidedeck-library-js' ), '1.0' );
	}
	
	/**
	 * Hook into slidedeck_after_create_slide action
	 * 
	 * Add additional meta options when creating a new slide
	 * 
	 * @param object $slide Slide object
	 * @param int $slidedeck_id SlideDeck ID
	 */
	function slidedeck_after_create_slide( $slide, $slidedeck_id ) {
		if( $this->is_valid( $slide->meta['_slide_type'] ) ) {
			foreach( $this->defaults as $key => $val ) {
				update_post_meta( $slide->ID, $key, $val );
			}
		}
	}
    
    /**
     * Hook into slidedeck_change_slide_type action
     * 
     * Update available slide meta for the new slide type
     * 
     * @param object $slide The Slide object
     * @param string $slide_type The slide type being changed to
     */
    function slidedeck_change_slide_type( $slide, $slide_type ) {
        if( $this->is_valid( $slide_type ) ) {
            foreach( $this->defaults as $key => $val ) {
                if( !isset( $slide->meta[$key] ) || in_array( $key, array( '_layout', '_caption_position' ) ) ) {
                    update_post_meta( $slide->ID, $key, $val );
                }
            }
        }
    }
	
	/**
	 * Hook into slidedeck_custom_slide_editor_form action
	 * 
	 * Output the editing form for the slide editor modal for this slide type
	 * 
	 * @param object $slide The Slide object
	 * @param array $slidedeck The SlideDeck object
	 */
	function slidedeck_before_custom_slide_editor_form( $slide, $slidedeck ) {
		global $wp_scripts;
		
		if( !$this->is_valid( $slide->meta['_slide_type'] ) ) {
			return false;
		}

		$namespace = $this->namespace;
		$url = $this->url;
		$slide_id = $slide->ID;
        $slidedeck_id = $slidedeck['id'];
		$matches = array( "M", "K" );
		$replacements = array( "mb", "kb" );
		$max_filesize = str_replace( $matches, $replacements, ini_get( "upload_max_filesize" ) );
		
		$scripts = array(
			"{$this->namespace}-browserplus",
			"{$this->namespace}-plupload",
			"{$this->namespace}-plupload-gears",
			"{$this->namespace}-plupload-silverlight",
			"{$this->namespace}-plupload-flash",
			"{$this->namespace}-plupload-browserplus",
			"{$this->namespace}-plupload-html4",
			"{$this->namespace}-plupload-html5"
		);
		
		include( dirname( __FILE__ ) . '/views/_image-upload.php' );
	}
	
	/**
	 * Hook into slidedeck_custom_slide_editor_form action
	 * 
	 * Output the editing form for the slide editor modal for this slide type
	 * 
	 * @param object $slide The Slide object
	 * @param array $slidedeck The SlideDeck object
	 */
	function slidedeck_custom_slide_editor_form( $slide, $slidedeck ) {
	    global $SlideDeckPlugin;
        $parent_slidedeck_id = $SlideDeckPlugin->SlideDeck->get_parent_id( $slidedeck['id'] );
        
        $preferred_image_size_params = array(
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
            'weight' => 70
        );
        
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
                $preferred_image_size_params['values'][$size] = ucwords( $size ) . ' (' . $sizes['size_w'] . 'x' . $sizes['size_h'] . $sizes['crop'] . ')';
            } elseif( !empty( $sizes['size_w'] ) && empty( $sizes['size_h'] ) ) {
                $preferred_image_size_params['values'][$size] = ucwords( $size ) . ' (' . $sizes['size_w'] . $sizes['crop'] . ')';
            } elseif( empty( $sizes['size_w'] ) && !empty( $sizes['size_h'] ) ) {
                $preferred_image_size_params['values'][$size] = ucwords( $size ) . ' (' . $sizes['size_h'] . $sizes['crop'] . ')';
            } else {
                $preferred_image_size_params['values'][$size] = ucwords( $size );
            }
        }
        // This is a fake size that should cause the function to return the original
        $preferred_image_size_params['values']['sd2-full-size-image'] = __( "Original Image", $this->namespace );

        
		if( !$this->is_valid( $slide->meta['_slide_type'] ) ) {
			return false;
		}
		
		$namespace = $this->namespace;
		$layouts = $this->layouts;
		$caption_positions = $this->caption_positions;
        $url = $this->url;
        $thumbnail = "";
        $image_filename = "";
        
        if( ( in_array( $slide->meta['_image_source'], array( 'upload', 'medialibrary' ) ) && !empty( $slide->meta['_image_attachment'] ) ) || ( $slide->meta['_image_source'] == "url" && !empty( $slide->meta['_image_url'] )) ) {
            $custom_source = $SlideDeckPlugin->get_sources( 'custom' );
            $custom_source = $custom_source['custom'];
            
            $thumbnail = $custom_source->get_slide_thumbnail( $slide );
            $image_filename = basename( $thumbnail );
        }
		
        $image_scaling_params = array(
            'type' => 'select',
            'data' => "string",
            'value' => $slide->meta['_image_scaling'],
            'values' => $SlideDeckPlugin->SlideDeck->options_model['Appearance']['image_scaling']['values'],
            'attr' => array(
                'class' => "fancy"
            )
        );
        
        if ( ! class_exists( '_WP_Editors' ) )
            require( ABSPATH . WPINC . '/class-wp-editor.php' );
    
		include( dirname( __FILE__ ) . '/views/show.php' );
	}
	
	/**
     * Hook into slidedeck_get_slide_thumbnail filter
     * 
     * @param string $thumbnail The current thumbnail
     * @param object $slide The Slide object
     * 
     * @return string
     */
	function slidedeck_get_slide_thumbnail( $thumbnail, $slide ) {
	    if( $this->is_valid( $slide->meta['_slide_type'] ) ) {
            if( in_array( $slide->meta['_image_source'], array( "upload", "medialibrary" ) ) ) {
                if( !empty( $slide->meta['_image_attachment'] ) ) {
                    $attachment = $this->get_media_meta( $slide->meta['_image_attachment'] );
                    $thumbnail = $attachment['src'][0];
                }
            } elseif( $slide->meta['_image_source'] == "url" ) {
                $thumbnail = $slide->meta['_image_url'];
            }
	    }
        
        return $thumbnail;
	}
		
	/**
	 * Hook into slidedeck_slide_nodes filter
	 * 
	 * Add additional nodes to the slide when rendering SlideDecks
	 * 
	 * @param array $slide_nodes Array of slide nodes
	 * @param object $slide The slide object itself
	 * @param array $slidedeck The SlideDeck
	 * 
	 * @return array
	 */
	function slidedeck_slide_nodes( $slide_nodes, $slide, $slidedeck ) {
		global $SlideDeckPlugin;
		
		if( $this->is_valid( $slide->meta['_slide_type'] ) ) {
			$slide_nodes['permalink'] = $slide->meta['_permalink'];
            
			$slide_nodes['content'] = $slide_nodes['excerpt'] = $slide->post_excerpt;
			
            /**
             * Grab the width and height of the deck. Then we should
             * create an expansion factor that will hopefully grab images _just_
             * larger than we need. Grabbing the big size automatically makes
             * some browsers (mostly Chrome) chug and is bad for the end user too.
             */
            $slidedeck_dimensions = $SlideDeckPlugin->get_dimensions( $slidedeck );
            
            // Set the expansion factor based on the auto or auto_100 options
            if( $slide->meta['_preferred_image_size'] == 'auto' ) {
                $expansion_factor = 1.2; // 120%
            } elseif ( $slide->meta['_preferred_image_size'] == 'auto_100' ) {
                $expansion_factor = 1; // 100%
            }
            
            $expanded_width = $slidedeck_dimensions['outer_width'] * $expansion_factor;
            $expanded_height = $slidedeck_dimensions['outer_height'] * $expansion_factor;
            
            // Determine image size to retrieve (closest size greater to SlideDeck size, or full of image scaling is off)
            $image_size = array( $expanded_width, $expanded_height );
            
            if( ($slide->meta['_preferred_image_size'] != 'auto') && ($slide->meta['_preferred_image_size'] != 'auto_100') ) {
                $image_size = $slide->meta['_preferred_image_size'];
            }
            
			$thumbnail_url = $image_url = "";
			if( in_array( $slide->meta['_image_source'], array( "upload", "medialibrary" ) ) ) {
				if( !empty( $slide->meta['_image_attachment'] ) ) {
					$attachment = $this->get_media_meta( $slide->meta['_image_attachment'] );
                    
                    // Determine image size to retrieve (closest size greater to SlideDeck size, or full of image scaling is off)
		            $image_src = wp_get_attachment_image_src( $attachment['post']->ID, $image_size );
		            
					$image_url = $image_src[0];
					$thumbnail_url = $attachment['src'][0];
				}
			} elseif( $slide->meta['_image_source'] == "url" ) {
				$thumbnail_url = $image_url = $slide->meta['_image_url'];
			}
			$slide_nodes['image'] = $image_url;
			$slide_nodes['thumbnail'] = $thumbnail_url;
		}
		
		return $slide_nodes;
	}
	
	/**
	 * Hook into slidedeck_update_slide action
	 * 
	 * Save image data when the edit form is submitted.
	 * 
	 * @param object $slide Slide object
	 */
	function slidedeck_update_slide( $slide, $data ) {
		if( $this->is_valid( $slide->meta['_slide_type'] ) ) {
			update_post_meta( $slide->ID, "_caption_position", $data['_caption_position'] );
			update_post_meta( $slide->ID, "_image_source", $data['_image_source'] );
			update_post_meta( $slide->ID, "_image_url", $data['_image_url'] );
			update_post_meta( $slide->ID, "_layout", $data['_layout'] );
			update_post_meta( $slide->ID, "_permalink", $data['_permalink'] );
            update_post_meta( $slide->ID, "_image_scaling", strip_tags( $data['_image_scaling'] ) );
            update_post_meta( $slide->ID, "_preferred_image_size", $data['_preferred_image_size'] );
	
			$post_excerpt = strip_tags( $data['post_excerpt'], "<p><a><strong><b><i><em><del><span><sup><sub><ul><ol><li><h1><h2><h3><h4><h5><h6><pre><address>" );
			$post_title = strip_tags( $data['post_title'] );
			
			$args = array(
				'ID' => $slide->ID,
				'post_title' => $post_title,
				'post_excerpt' => $post_excerpt
			);
			wp_update_post( $args );
		}
	}
}
