<?php
/*
 Plugin Name: SlideDeck 2 Personal
 Plugin URI: http://www.slidedeck.com/wordpress
 Description: Create SlideDecks on your WordPress blogging platform and insert them into templates and posts. Get started creating SlideDecks from the new SlideDeck menu in the left hand navigation.
 Version: 2.1.20130325
 Author: digital-telepathy
 Author URI: http://www.dtelepathy.com
 License: GPL3
 */
/*
 Copyright 2012 digital-telepathy  (email : support@digital-telepathy.com)

 This file is part of SlideDeck.

 SlideDeck is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 SlideDeck is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with SlideDeck.  If not, see <http://www.gnu.org/licenses/>.
 */

class SlideDeckPlugin {
    var $package_slug = 'single';
    static $namespace = "slidedeck";
    static $friendly_name = "SlideDeck 2";
    
    static $cohort_name = 'afe4523';
    static $cohort_variation = '';
    static $overriding_cohorts = array(
        'ecf3509'
    );
    
    static $version = '2.1.20130325';
    static $license = 'PRO';

    // Generally, we are not installing addons. If we are, this gets set to true.
    static $slidedeck_addons_installing = false;

    // Static variable of addons that are currently installed
    static $addons_installed = array( 'tier_10' => 'tier_10' );

    static $cache_groups = array(
        "cover-get",
        "lenses-get",
        "lenses-get-meta",
        "get",
        "get-parent-id",
        "options",
        "slides",
        "get-meta-results"
    );
    
    var $decks = array();

    // Available sources to SlideDeck 2
    var $sources = array();
    
    // Lenses available to SlideDeck 2
    var $installed_lenses = array();

    // Default plugin options
    var $defaults = array(
        'always_load_assets' => true,
        'disable_wpautop' => true,
        'dont_enqueue_scrollwheel_library' => false,
        'dont_enqueue_easing_library' => false,
        'disable_edit_create' => false,
        'twitter_user' => "",
        'iframe_by_default' => false,
        'anonymous_stats_optin' => false,
        'anonymous_stats_has_opted' => false,
        'flush_wp_object_cache' => false
    );
    
    // JavaScript to be run in the footer of the page
    var $footer_scripts = "";

    // Styles to override Lens and Deck styles
    var $footer_styles = "";

    // Should SlideDeck assets be loaded?
    var $load_assets = false;
    
    var $slidedeck_ids = array();
    
    // Global property to determine if the current request is a preview
    var $preview = false;
    
    // Are there only iFrame decks on the page?
    var $only_has_iframe_decks = false;
    
    // Are there any RESS decks on the page?
    var $page_has_ress_deck = false;

    // Boolean to determine if video JavaScript files need to be loaded
    var $load_video_scripts = false;

    // Loaded sources
    var $loadedSources = array( );

    // Loaded slide type scripts
    var $loaded_slide_styles = array( );

    // Loaded slide type styles
    var $loaded_slide_scripts = array( );

    // WordPress Menu Items
    var $menu = array( );

    // Name of the option_value to store plugin options in
    var $option_name = "slidedeck2_global_options";

    var $sizes = array( 'small' => array( 'label' => "Small", 'width' => 300, 'height' => 300 ), 'medium' => array( 'label' => "Medium", 'width' => 500, 'height' => 500 ), 'large' => array( 'label' => "Large", 'width' => 960, 'height' => 500 ), 'custom' => array( 'label' => "Custom", 'width' => 500, 'height' => 500 ) );

    // Available slide animation transitions
    var $slide_transitions = array( 'stack' => "Card Stack", 'fade' => "Cross-fade", 'flipHorizontal' => "Flip Horizontal", 'flip' => "Flip Vertical", 'slide' => "Slide (Default)" );

    // Taxonomy categories for SlideDeck types
    var $taxonomies = array( 'images' => array( 'label' => "Images", 'color' => "#9a153c", 'thumbnail' => '/images/taxonomy-images.png', 'icon' => '/images/taxonomy-images-icon.png' ), 'social' => array( 'label' => "Social", 'color' => "#024962", 'thumbnail' => '/images/taxonomy-social.png', 'icon' => '/images/taxonomy-social-icon.png' ), 'posts' => array( 'label' => "Posts", 'color' => "#3c7120", 'thumbnail' => '/images/taxonomy-posts.png', 'icon' => '/images/taxonomy-posts-icon.png' ), 'videos' => array( 'label' => "Videos", 'color' => "#434343", 'thumbnail' => '/images/taxonomy-videos.png', 'icon' => '/images/taxonomy-videos-icon.png' ), 'feeds' => array( 'label' => "Feeds", 'color' => "#b24702", 'thumbnail' => '/images/taxonomy-feeds.png', 'icon' => '/images/taxonomy-feeds-icon.png' ) );

    // Array of lenses that need loading on a page
    var $lenses_included = array( );

    // Boolean of whether or not the Lenses have been loaded in the view yet
    var $lenses_loaded = false;

    // SlideDeck font @imports being loaded on the page
    var $font_imports_included = array( );

    // Options model groups for display in the order to be displayed
    var $options_model_groups = array( 'Appearance', 'Content', 'Navigation', 'Playback' );

    // Backgrounds for editor area
    var $stage_backgrounds = array( 'wood' => "Wood", 'light' => "Light", 'dark' => "Dark" );

    var $order_options = array( 'post_title' => "Alphabetical", 'post_modified' => "Last Modified", 'slidedeck_source' => "SlideDeck Source" );

    var $user_is_back = false;
    var $upgraded_to_tier = false;
    var $highest_tier_install_link = false;
    var $next_available_tier = false;
    

    /**
     * Instantiation construction
     *
     * @uses add_action()
     * @uses SlideDeckPlugin::wp_register_scripts()
     * @uses SlideDeckPlugin::wp_register_styles()
     */
    function __construct( ) {
        SlideDeckPlugin::load_constants();
        
        $this->friendly_name = SlideDeckPlugin::$friendly_name;
        $this->namespace = SlideDeckPlugin::$namespace;

        /**
         * Make this plugin available for translation.
         * Translations can be added to the /languages/ directory.
         */
        load_plugin_textdomain( $this->namespace, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        // Load all library files used by this plugin
        $lib_files = glob( SLIDEDECK2_DIRNAME . '/lib/*.php' );
        foreach( $lib_files as $filename ) {
            include_once ($filename);
        }
        
        // Loop through $cache_groups to add to Non Persistent Cache
        if( function_exists( 'wp_cache_add_non_persistent_groups' ) ){
            foreach( SlideDeckPlugin::$cache_groups as $cache_group ){
                wp_cache_add_non_persistent_groups( slidedeck2_cache_group( $cache_group ) );
            }
        }

        // WordPress Pointers helper
        $this->Pointers = new SlideDeckPointers( );

        // The Lens primary class
        include_once (SLIDEDECK2_DIRNAME . '/classes/slidedeck-lens.php');
        $this->Lens = new SlideDeckLens( );

        // The Cover primary class
        if( file_exists( SLIDEDECK2_DIRNAME . '/classes/slidedeck-covers.php' ) ){
            include_once (SLIDEDECK2_DIRNAME . '/classes/slidedeck-covers.php');
            $this->Cover = new SlideDeckCovers( );
        }

        // The Lens scaffold
        include_once (SLIDEDECK2_DIRNAME . '/classes/slidedeck-lens-scaffold.php');

        // The Deck primary class for Deck types to child from
        include_once (SLIDEDECK2_DIRNAME . '/classes/slidedeck.php');

        // Stock Lenses that come with SlideDeck distribution
        $lens_files = glob( SLIDEDECK2_DIRNAME . '/lenses/*/lens.php' );
        if( is_dir( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
            if( is_readable( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
                // Get additional uploaded custom Lenses
                $custom_lens_files = (array) glob( SLIDEDECK2_CUSTOM_LENS_DIR . '/*/lens.php' );
                // Merge Lenses available and loop through to load
                $lens_files = array_merge( $lens_files, $custom_lens_files );
            }
        }

        // Load all the custom Lens types
        foreach( (array) $lens_files as $filename ) {
            if( is_readable( $filename ) ) {
                $classname = slidedeck2_get_classname_from_filename( dirname( $filename ) );
                $prefix_classname = "SlideDeckLens_{$classname}";
                $slug = basename( dirname( $filename ) );
                
                if( !class_exists( $prefix_classname ) ) {
                    include_once ( $filename );
                    $this->installed_lenses[] = $slug;
                }
                
                if( class_exists( $prefix_classname ) ) {
                    $this->lenses[$classname] = new $prefix_classname;
                }
            }
        }

        $source_files = (array) glob( SLIDEDECK2_DIRNAME . '/sources/*/source.php' );
        foreach( (array) $source_files as $filename ) {
            if( is_readable( $filename ) ) {
                include_once ($filename);

                $slug = basename( dirname( $filename ) );
                $classname = slidedeck2_get_classname_from_filename( dirname( $filename ) );
                $prefix_classname = "SlideDeckSource_{$classname}";
                if( class_exists( $prefix_classname ) ) {
                    $this->sources[$slug] = new $prefix_classname;
                } elseif( class_exists( "{$prefix_classname}Content" ) && $slug == "custom" ) {
                    $custom_content_classname = "{$prefix_classname}Content";
                    $this->sources[$slug] = new $custom_content_classname;
                }
            }
        }

        $this->SlideDeck = new SlideDeck( );

        $this->add_hooks( );
    }

    /**
     * Render a SlideDeck in an iframe
     *
     * Generates an iframe tag with a SlideDeck rendered in it. Only accessible
     * via
     * the shortcode with the iframe property set.
     *
     * @param integer $id SlideDeck ID
     * @param integer $width Width of SlideDeck
     * @param integer $height Height of SlideDeck
     * @param boolean $nocovers Whether or not to include covers in the render
     *
     * @global $wp_scripts
     *
     * @uses SlideDeck::get()
     * @uses SlideDeck::get_unique_id()
     * @uses SlideDeckPlugin::get_dimensions()
     * @uses SlideDeckPlugin::get_iframe_url()
     *
     * @return string
     */
    private function _render_iframe( $id, $width = null, $height = null, $nocovers = false, $ress = false, $proportional = true ) {
        global $wp_scripts;

        // Load the SlideDeck itself
        $slidedeck = $this->SlideDeck->get( $id );
        if( empty( $slidedeck ) ) return ''; // return an empty string if there's no deck

        // Generate a unique HTML ID
        $slidedeck_unique_id = $this->namespace . '_' . $slidedeck['id'] . '_' . uniqid();
        
        // Get the inner and outer dimensions for the SlideDeck
        $dimensions = $this->get_dimensions( $slidedeck );
        $ratio = $dimensions['outer_height'] / $dimensions['outer_width'];
        
        // Get the IFRAME source URL
        $iframe_url = $this->get_iframe_url( $id, $dimensions['outer_width'], $dimensions['outer_height'] );
        $iframe_url .= "&slidedeck_unique_id=" . $slidedeck_unique_id;
        $iframe_url .= "&start=";

        if( $nocovers )
            $iframe_url .= "&nocovers=1";

        if( !$ress ) {
            /**
             * Regular iFrame embed
             */
            $html = '<div id="' . $slidedeck_unique_id . '-wrapper"><iframe class="slidedeck-iframe-embed" id="' . $slidedeck_unique_id . '" frameborder="0" allowtransparency="yes"  src="' . $iframe_url . '" style="width:' . $dimensions['outer_width'] . 'px;height:' . $dimensions['outer_height'] . 'px;"></iframe></div>';
        } else {
            /**
             * Setup the RESS Scripts
             */
            $style = ' style="';
            
            
            if( $proportional !== true ) {
                $style .= "height:{$dimensions['outer_height']}px;";
            }else{
                $style .= "height:auto;";
            }
            
            
            $style .= '"';
            $html = '<div id="' . $slidedeck_unique_id . '-wrapper" class="sd2-ress-wrapper"' . $style . '>';
            $html .= '<dl class="sd2-alternate-hidden-content" style="height:0;overflow:hidden;visibility:hidden;">';
            
            $slides = $this->SlideDeck->fetch_and_sort_slides( $slidedeck );
            $html .= $this->SlideDeck->render_dt_and_dd_elements( $slidedeck, $slides );
            
            $html .= '</dl>';
            $html .= '</div>';
            
            $this->footer_styles .= '.sd2-alternate-hidden-content{display:none!important;}';
            
            // Setup the RESS properties for this deck
            $ress_properties = array(
                'id' => $slidedeck_unique_id,
                'src' => '',
                'domain' => $_SERVER['HTTP_HOST'],
                'element' => $slidedeck_unique_id . '-wrapper',
                'style' => ''
            );
            
            // Append a footer script for each deck
            ob_start( );
            include( SLIDEDECK2_DIRNAME . '/views/elements/_ress-js-footer-part.php' );
            $this->footer_scripts .= ob_get_contents( );
            ob_end_clean( );
            
        }

        return $html;
    }

    /**
     * Save a SlideDeck autodraft
     *
     * Saves a SlideDeck auto-draft and returns an array with dimension
     * information, the ID
     * of the auto-draft and the URL for the iframe preview.
     *
     * @param integer $slidedeck_id The ID of the parent SlideDeck
     * @param array $data All data about the SlideDeck being auto-drafted
     *
     * @return array
     */
    private function _save_autodraft( $slidedeck_id, $data ) {
        // Preview SlideDeck object
        $preview = $this->SlideDeck->save_preview( $slidedeck_id, $data );

        $dimensions = $this->get_dimensions( $preview );

        $iframe_url = $this->get_iframe_url( $preview['id'], $dimensions['outer_width'], $dimensions['outer_height'], $dimensions['outer_width'], $dimensions['outer_height'], true );

        $response = $dimensions;
        $response['preview_id'] = $preview['id'];
        $response['preview'] = $preview;
        $response['url'] = $iframe_url;

        return $response;
    }

    /**
     * uasort() sorting method for sorting by weight property
     *
     * @return boolean
     */
    private function _sort_by_weight( $a, $b ) {
        $default_weight = 100;

        $a_weight = is_object( $a ) ? (isset( $a->weight ) ? $a->weight : $default_weight) : (is_array( $a ) && isset( $a['weight'] ) ? $a['weight'] : $default_weight);
        $b_weight = is_object( $b ) ? (isset( $b->weight ) ? $b->weight : $default_weight) : (is_array( $b ) && isset( $b['weight'] ) ? $b['weight'] : $default_weight);

        return $a_weight > $b_weight;
    }

    /**
     * Get the URL for the specified plugin action
     *
     * @param object $str [optional] Expects the handle passed in the menu
     * definition
     *
     * @uses admin_url()
     *
     * @return The absolute URL to the plugin action specified
     */
    function action( $str = "" ) {
        $path = admin_url( "admin.php?page=" . SLIDEDECK2_BASENAME );

        if( !empty( $str ) ) {
            return $path . $str;
        } else {
            return $path;
        }
    }

    /**
     * Hook into register_activation_hook action
     * 
     * NOTE: DO NOT RELY ON THIS PLUGIN HOOK FOR DATABASE MIGRATIONS
     * -------------------------------------------------------------
     * WordPress will not run the activation hook properly when it is bulk upgrading plugins
     * so database migrations and the like will not run if the user has selected to bulk update
     * all of their SlideDeck plugins at once. 
     * 
     * Put code here that needs to happen when your plugin is first activated
     * (database
     * creation, permalink additions, etc.)
     *
     * @uses wp_remote_fopen()
     */
    static function activate( ) {
        SlideDeckPlugin::load_constants();
        include_once( dirname( __FILE__ ) . '/lib/template-functions.php' );
        
        if( !is_dir( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
            if( is_writable( dirname( SLIDEDECK2_CUSTOM_LENS_DIR ) ) ) {
                mkdir( SLIDEDECK2_CUSTOM_LENS_DIR, 0777 );
            }
        }

        self::check_plugin_updates( );
        

        $installed_version = get_option( "slidedeck_version", false );
        $installed_license = get_option( "slidedeck_license", false );

        if( $installed_license ) {
            if( strtolower( $installed_license ) == "lite" && strtolower( self::$license ) != "lite" ) {
                // Upgrade from Lite to PRO
                slidedeck2_km( "Upgrade to PRO", array( 'license' => self::$license, 'version' => self::$version ) );
            }
        }

        // First time installation
        if( !$installed_version ) {
            slidedeck2_km( "SlideDeck Installed", array( 'license' => self::$license, 'version' => self::$version ) );
            
            // Setup the cohorts data
            self::set_cohort_data();
        }


        if( $installed_version && version_compare( self::$version, $installed_version, '>' ) ) {
            /**
             * 2.0.x to 2.1.x upgrade process...
             */
            if( version_compare( $installed_version, '2.1', '<' ) ) {
                if( !class_exists( "SlideDeck" ) ) {
                    include ( dirname( __FILE__ ) . '/classes/slidedeck.php' );
                }
                if( !class_exists( "SlideDeckSlideModel" ) ) {
                    include( dirname( __FILE__ ) . '/sources/custom/slide-model.php' );
                }

                global $SlideDeckPlugin, $wpdb;
                
                // Import Media Library and List of Videos decks
                $SlideDeck = new SlideDeck();
                $SlideDeckSlide = new SlideDeckSlideModel();
                $SlideDeckPlugin = (object) array();
                $SlideDeckPlugin->SlideDeck = $SlideDeck;
                
                $slidedecks = $SlideDeck->get( null, 'post_title', 'ASC', 'publish' );
                
                foreach( $slidedecks as $slidedeck ) {
                    $sources = $slidedeck['source'];
                    if( !is_array( $sources ) ) {
                        $sources = array( $sources );
                    }
                    
                    // Import Media Library SlideDecks
                    if( in_array( "medialibrary", $sources ) && !in_array( "custom", $sources ) ) {
                        $media_ids = $slidedeck['options']['medialibrary_ids'];
                        $slide_counter = 0;
                        
                        foreach( $media_ids as $media_id ) {
                            $media_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE ID = %d", $media_id ) );
                            // Get the image meta data
                            $meta_data = wp_get_attachment_metadata( $media_id );
                            
                            // Create the slide
                            $slide_id = $SlideDeckSlide->create( $slidedeck['id'], "image", array(
                                'menu_order' => ( $slide_counter + 1 ),
                                'post_title' => $media_post->post_title,
                                'post_excerpt' => $media_post->post_excerpt,
                                'post_content' => $media_post->post_excerpt,
                                'post_date' => $media_post->post_date
                            ) );
                            
                            // Associate the image with the slide
                            update_post_meta( $slide_id, "_image_attachment", $media_id );
                            update_post_meta( $slide_id, "_image_source", "upload" );
                            update_post_meta( $slide_id, "_image_url", "" );
                            
                            $_layout = "body-text";
                            $_caption_position = "left";
                            switch( $slidedeck['lens'] ) {
                                case "block-title":
                                    $_layout = "body-text";
                                    $_caption_position = "left";
                                break;
                                
                                case "fashion":
                                    $_layout = "body-text";
                                    $_caption_position = "left";
                                break;
                                
                                case "half-moon":
                                    $_layout = "body-text";
                                    $_caption_position = "right";
                                break;
                                
                                case "o-town":
                                    $_layout = "body-text";
                                    $_caption_position = "left";
                                break;
                                
                                case "proto":
                                    $_layout = "body-text";
                                    $_caption_position = "left";
                                break;
                            }
                            update_post_meta( $slide_id, "_layout", $_layout );
                            update_post_meta( $slide_id, "_caption_position", $_caption_position );
                            
                            $slidedeck['options']['total_slides'] = 999;
                            
                            $slide_counter++;
                        }
                        
                        // Update the source
                        add_post_meta( $slidedeck['id'], "slidedeck_source", "custom" );
                    }
                    
                    // Import Video List SlideDecks
                    if( in_array( "listofvideos", $sources ) && !in_array( "custom", $sources ) ) {
                        $video_urls = get_post_meta( $slidedeck['id'], "slidedeck_list_of_videos", true );
                        $video_urls = explode( "\n", $video_urls );
                        $video_urls = array_map( "trim", $video_urls );
                        
                        $slide_counter = 0;
                        foreach( $video_urls as $video_url ) {
                            $video_meta = $SlideDeck->get_video_meta_from_url( $video_url );
                            // Create the slide
                            $slide_id = $SlideDeckSlide->create( $slidedeck['id'], "video", array(
                                'menu_order' => ( $slide_counter + 1 ),
                                'post_title' => slidedeck2_stip_tags_and_truncate_text( $video_meta['title'], $slidedeck['options']['titleLength'] ),
                                'post_excerpt' => slidedeck2_stip_tags_and_truncate_text( $video_meta['description'], $slidedeck['options']['descriptionLength'] ),
                                'post_content' => slidedeck2_stip_tags_and_truncate_text( $video_meta['description'], $slidedeck['options']['descriptionLength'] )
                            ) );
                            
                            update_post_meta( $slide_id, "_permalink", $video_meta['permalink'] );
                            update_post_meta( $slide_id, "_video_url", $video_url );
                            update_post_meta( $slide_id, "_video_meta", $video_meta );
                            
                            $_layout = "body-text";
                            $_caption_position = "left";
                            switch( $slidedeck['lens'] ) {
                                case "fashion":
                                    $_layout = "body-text";
                                    $_caption_position = "left";
                                break;
                                
                                case "o-town":
                                    $_layout = "body-text";
                                    $_caption_position = "left";
                                break;
                                
                                case "video":
                                    $_layout = "body-text";
                                    $_caption_position = "left";
                                break;
                            }
                            update_post_meta( $slide_id, "_layout", $_layout );
                            update_post_meta( $slide_id, "_caption_position", $_caption_position );
                            
                            $slidedeck['options']['total_slides'] = 999;
                            $slidedeck['options']['show-excerpt'] = true;
                            $slidedeck['options']['show-title'] = true;
                            $slidedeck['options']['show-readmore'] = true;
                            
                            $slide_counter++;
                        }
                        
                        // Update the source
                        add_post_meta( $slidedeck['id'], "slidedeck_source", "custom" );
                        
                        // Proto video lens is being deprecated for custom SlideDecks
                        if( $slidedeck['lens'] == "video" ) {
                            update_post_meta( $slidedeck['id'], "slidedeck_lens", "tool-kit" );
                        }
                    }
                    
                    update_post_meta( $slidedeck['id'], "slidedeck_options", $slidedeck['options'] );
                }
                // End of Import Media Library and List of Videos decks

                
                // Update various cache settings and durations...
                $SlideDeck = new SlideDeck( );
                $slidedecks = $SlideDeck->get( null, 'post_title', 'ASC', 'publish' );

                foreach( $slidedecks as $slidedeck ) {
                    $sources = $slidedeck['source'];
                    if( !is_array( $sources ) ) {
                        $sources = array( $sources );
                    }

                    if( count( $slidedeck['source'] ) > 1 ) {
                        continue;
                    }

                    // Update cache duration option name
                    if( isset( $slidedeck['options']['feedCacheDuration'] ) ) {
                        $slidedeck['options']['cache_duration'] = $slidedeck['options']['feedCacheDuration'];
                        unset( $slidedeck['options']['feedCacheDuration'] );
                    }

                    // Update Twitter source meta
                    if( in_array( 'twitter', $sources ) ) {
                        $slidedeck['options']['twitter_search_or_user'] = $slidedeck['options']['search_or_user'];
                        unset( $slidedeck['options']['search_or_user'] );
                    }

                    // Adjust cache to minutes instead of seconds
                    $intersect = array_intersect( array( 'twitter', 'youtube', 'vimeo', 'dailymotion' ), $sources );
                    if( !empty( $intersect ) ) {
                        $slidedeck['options']['cache_duration'] = round( $slidedeck['options']['cache_duration'] / 60 );
                    }

                    update_post_meta( $slidedeck['id'], "slidedeck_options", $slidedeck['options'] );
                }
                // End of Update various cache settings and durations...

            }

            // Upgrade to new version
            slidedeck2_km( "SlideDeck Upgraded", array( 'license' => self::$license, 'version' => self::$version ) );
        }

        update_option( "slidedeck_version", self::$version );
        update_option( "slidedeck_license", self::$license );
        
        /**
         * Installation timestamp: SlideDeck 2
         */
        $existing_timestamp = get_option( 'slidedeck2_installed', false );
        if( !$existing_timestamp ){
            update_option( 'slidedeck2_installed', time() );
        }
                
        /**
         * Installation timestamp: SlideDeck 2 Paid
         */
        $existing_timestamp = get_option( 'slidedeck2_paid_installed', false );
        if( !$existing_timestamp ){
            update_option( 'slidedeck2_paid_installed', time() );
        }
        
        // Activation
        slidedeck2_km( "SlideDeck Activated", array( 'license' => self::$license, 'version' => self::$version ) );
    }

    /**
     * Add help tab to a page
     *
     * Loads a help file and render's its content to an output buffer, using its
     * content as content
     * for a help tab. Runs the WP_Screen::add_help_tab() method to create a help
     * tab. Returns a boolean
     * value for success of the help addition. Will return boolean(false) if the
     * help file could not
     * be found.
     *
     * @param string $help_id The slug of the help content to get (the name of
     * the help PHP file without the .php extension)
     *
     * @return boolean
     */
    function add_help_tab( $help_id, $title ) {
        $help_filename = SLIDEDECK2_DIRNAME . '/views/help/' . $help_id . '.php';

        $success = false;

        if( file_exists( $help_filename ) ) {
            // Get the help file's HTML content
            ob_start( );
            include_once ($help_filename);
            $html = ob_get_contents( );
            ob_end_clean( );

            get_current_screen( )->add_help_tab( array( 'id' => $help_id, 'title' => __( $title, $this->namespace ), 'content' => $html ) );

            $success = true;
        }

        return $success;
    }

    /**
     * Add in various hooks
     *
     * Place all add_action, add_filter, add_shortcode hook-ins here
     */
    function add_hooks( ) {
        // Upload/Insert Media Buttons
        add_action( 'media_buttons', array( &$this, 'media_buttons' ), 20 );

        // Add SlideDeck button to TinyMCE navigation
        add_action( 'admin_init', array( &$this, 'add_tinymce_buttons' ) );

        // Options page for configuration
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        add_action( 'admin_menu', array( &$this, 'license_key_check' ) );

        // Add JavaScript for pointers
        add_action( 'admin_print_footer_scripts', array( &$this, 'admin_print_footer_scripts' ) );

        // Add the JavaScript constants
        add_action( 'admin_print_footer_scripts', array( &$this, 'print_javascript_constants' ) );
        add_action( "{$this->namespace}_print_footer_scripts", array( &$this, 'print_javascript_constants' ) );
        add_action( 'wp_print_footer_scripts', array( &$this, 'print_javascript_constants' ) );

        // Add JavaScript and Stylesheets for admin interface on appropriate
        // pages
        add_action( 'admin_print_scripts-slidedeck-2_page_' . SLIDEDECK2_HOOK . '/options', array( &$this, 'admin_print_scripts' ) );
        add_action( 'admin_print_styles-slidedeck-2_page_' . SLIDEDECK2_HOOK . '/options', array( &$this, 'admin_print_styles' ) );
        add_action( 'admin_print_scripts-slidedeck-2_page_' . SLIDEDECK2_HOOK . '/upgrades', array( &$this, 'admin_print_scripts' ) );
        add_action( 'admin_print_styles-slidedeck-2_page_' . SLIDEDECK2_HOOK . '/upgrades', array( &$this, 'admin_print_styles' ) );
        add_action( 'admin_print_scripts-toplevel_page_' . SLIDEDECK2_HOOK, array( &$this, 'admin_print_scripts' ) );
        add_action( 'admin_print_styles-toplevel_page_' . SLIDEDECK2_HOOK, array( &$this, 'admin_print_styles' ) );
        add_action( 'admin_print_scripts-slidedeck-2_page_' . SLIDEDECK2_HOOK . '/lenses', array( &$this, 'admin_print_scripts' ) );
        add_action( 'admin_print_styles-slidedeck-2_page_' . SLIDEDECK2_HOOK . '/lenses', array( &$this, 'admin_print_styles' ) );

        // Print editor page only styles
        add_action( 'admin_print_styles', array( &$this, 'admin_print_editor_styles' ) );

        // Load IE only stylesheets
        add_action( 'admin_print_styles', array( &$this, 'admin_print_ie_styles' ), 1000 );

        // Add custom post type
        add_action( 'init', array( &$this, 'register_post_types' ) );

        // Route requests for form processing
        add_action( 'init', array( &$this, 'route' ) );

        // Register all JavaScript files used by this plugin
        add_action( 'init', array( &$this, 'wp_register_scripts' ), 1 );

        // Register all Stylesheets used by this plugin
        add_action( 'init', array( &$this, 'wp_register_styles' ), 1 );

        // Hook into post save to save featured flag and featured title name
        add_action( 'save_post', array( &$this, 'save_post' ) );

        add_action( "{$this->namespace}_content_control", array( &$this, 'slidedeck_content_control' ) );

        // Add AJAX actions
        add_action( "wp_ajax_{$this->namespace}_add_source", array( &$this, 'ajax_add_source' ) );
        add_action( "wp_ajax_{$this->namespace}_delete_source", array( &$this, 'ajax_delete_source' ) );
        add_action( "wp_ajax_{$this->namespace}_delete_lens_authorize", array( &$this, 'ajax_delete_lens_authorize' ) );
        add_action( "wp_ajax_{$this->namespace}_change_lens", array( &$this, 'ajax_change_lens' ) );
        add_action( "wp_ajax_{$this->namespace}_change_source_view", array( &$this, 'ajax_change_source_view' ) );
        add_action( "wp_ajax_{$this->namespace}_create_new_with_slidedeck", array( &$this, 'ajax_create_new_with_slidedeck' ) );
        add_action( "wp_ajax_{$this->namespace}_covers_modal", array( &$this, 'ajax_covers_modal' ) );
        add_action( "wp_ajax_{$this->namespace}_first_save_dialog", array( &$this, 'ajax_first_save_dialog' ) );
        add_action( "wp_ajax_{$this->namespace}_getcode_dialog", array( &$this, 'ajax_getcode_dialog' ) );
        add_action( "wp_ajax_{$this->namespace}_gplus_posts_how_to_modal", array( &$this, 'ajax_gplus_posts_how_to_modal' ) );
        add_action( "wp_ajax_{$this->namespace}_insert_iframe", array( &$this, 'ajax_insert_iframe' ) );
        add_action( "wp_ajax_{$this->namespace}_insert_iframe_update", array( &$this, 'ajax_insert_iframe_update' ) );
        add_action( "wp_ajax_{$this->namespace}_post_header_redirect", array( &$this, 'ajax_post_header_redirect' ) );
        add_action( "wp_ajax_{$this->namespace}_preview_iframe", array( &$this, 'ajax_preview_iframe' ) );
        add_action( "wp_ajax_nopriv_{$this->namespace}_preview_iframe", array( &$this, 'ajax_preview_iframe' ) );
        add_action( "wp_ajax_{$this->namespace}_preview_iframe_update", array( &$this, 'ajax_preview_iframe_update' ) );
        add_action( "wp_ajax_{$this->namespace}_sort_manage_table", array( &$this, 'ajax_sort_manage_table' ) );
        add_action( "wp_ajax_{$this->namespace}_check_license_expiry", array( &$this, 'ajax_check_license_expiry' ) );
        add_action( "wp_ajax_{$this->namespace}_source_modal", array( &$this, 'ajax_source_modal' ) );
        add_action( "wp_ajax_{$this->namespace}_stage_background", array( &$this, 'ajax_stage_background' ) );
        add_action( "wp_ajax_{$this->namespace}_update_available_lenses", array( &$this, 'ajax_update_available_lenses' ) );
        add_action( "wp_ajax_{$this->namespace}_validate_copy_lens", array( &$this, 'ajax_validate_copy_lens' ) );
        add_action( "wp_ajax_{$this->namespace}_verify_license_key", array( &$this, 'ajax_verify_license_key' ) );
        add_action( "wp_ajax_{$this->namespace}_verify_addons_license_key", array( &$this, 'ajax_verify_addons_license_key' ) );
        add_action( "wp_ajax_{$this->namespace}2_blog_feed", array( &$this, 'ajax_blog_feed' ) );
        add_action( "wp_ajax_{$this->namespace}2_tweet_feed", array( &$this, 'ajax_tweet_feed' ) );

        add_action( "wp_ajax_{$this->namespace}_upsell_modal_content", array( &$this, 'ajax_upsell_modal_content' ) );
        add_action( "wp_ajax_{$this->namespace}_anonymous_stats_optin", array( &$this, 'ajax_anonymous_stats_optin' ) );
        
        // Append necessary lens and initialization script commands to the bottom
        // of the DOM for proper loading
        add_action( 'wp_print_footer_scripts', array( &$this, 'print_footer_scripts' ) );

        // Add required JavaScript and Stylesheets for displaying SlideDecks in
        // public view
        add_action( 'wp_print_scripts', array( &$this, 'wp_print_scripts' ) );
        
        // Prints some JavaScript constants in the head tag.
        add_action( 'wp_print_scripts', array( &$this, 'print_header_javascript_constants' ) );

        // Front-end only actions
        if( !is_admin( ) ) {
            // Pre-loading for lenses used by SlideDeck(s) in post(s) on a page
            add_action( 'wp', array( &$this, 'wp_hook' ) );

            // Print required lens stylesheets
            add_action( 'wp_print_styles', array( &$this, 'wp_print_styles' ) );
        }

        add_action( 'update-custom_upload-slidedeck-lens', array( &$this, 'upload_lens' ) );

        // Add full screen buttons to post editor
        add_filter( 'wp_fullscreen_buttons', array( &$this, 'wp_fullscreen_buttons' ) );
        // Add a settings link next to the "Deactivate" link on the plugin
        // listing page
        add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );

        add_filter( "{$this->namespace}_form_content_source", array( &$this, 'slidedeck_form_content_source' ), 10, 2 );
        add_filter( "{$this->namespace}_options_model", array( &$this, 'slidedeck_options_model' ), 9999, 2 );
        add_filter( "{$this->namespace}_create_custom_slidedeck_block", array( &$this, 'slidedeck_create_custom_slidedeck_block' ) );
        add_filter( "{$this->namespace}_create_dynamic_slidedeck_block", array( &$this, 'slidedeck_create_dynamic_slidedeck_block' ) );
        add_filter( "{$this->namespace}_get_slides", array( &$this, 'slidedeck_get_slides' ), 1000, 2 );

        add_filter( "{$this->namespace}_lens_selection_after_lenses", array( &$this, 'slidedeck_lens_selection_after_lenses' ), 10, 2 );
        add_filter( "{$this->namespace}_manage_lenses_after_lenses", array( &$this, 'slidedeck_manage_lenses_after_lenses') );
        add_filter( "upgrader_post_install", array( &$this, 'upgrader_post_install' ), 1000, 3 );
        add_action( 'after_plugin_row_' . basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) , array( &$this, 'after_plugin_row' ) );
        
        // Add shortcode to replace SlideDeck shortcodes in content with
        // SlideDeck contents
        add_shortcode( 'SlideDeck2', array( &$this, 'shortcode' ) );
    }

    /**
     * Setup TinyMCE button for fullscreen editor
     *
     * @uses add_filter()
     */
    function add_tinymce_buttons( ) {
        add_filter( 'mce_external_plugins', array( &$this, 'add_tinymce_plugin' ) );
    }

    /**
     * Add the SlideDeck TinyMCE plugin to the TinyMCE plugins list
     *
     * @param object $plugin_array The TinyMCE options array
     *
     * @uses slidedeck_is_plugin()
     *
     * @return object $plugin_array The modified TinyMCE options array
     */
    function add_tinymce_plugin( $plugin_array ) {
        if( !$this->is_plugin( ) ) {
            $plugin_array['slidedeck2'] = SLIDEDECK2_URLPATH . '/js/tinymce3/editor-plugin.js';
        }

        return $plugin_array;
    }

    /**
     * Process update page form submissions
     *
     * @uses slidedeck2_sanitize()
     * @uses wp_redirect()
     * @uses wp_verify_nonce()
     * @uses wp_die()
     * @uses update_option()
     * @uses esc_html()
     * @uses wp_safe_redirect()
     */
    function admin_options_update( ) {
        // Verify submission for processing using wp_nonce
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-update-options" ) )
            wp_die( __( "Unauthorized form submission!", $this->namespace ) );

        $data = array( );
        /**
         * Loop through each POSTed value and sanitize it to protect against
         * malicious code. Please
         * note that rich text (or full HTML fields) should not be processed by
         * this function and
         * dealt with directly.
         */
        foreach( $_POST['data'] as $key => $val ) {
            $data[$key] = slidedeck2_sanitize( $val );
        }

        // Get the old options
        $old_options = get_option( $this->option_name );

        $options = array(
            'always_load_assets' => isset( $data['always_load_assets'] ) && !empty( $data['always_load_assets'] ) ? true : false,
            'disable_wpautop' => isset( $data['disable_wpautop'] ) && !empty( $data['disable_wpautop'] ) ? true : false,
            'dont_enqueue_scrollwheel_library' => isset( $data['dont_enqueue_scrollwheel_library'] ) && !empty( $data['dont_enqueue_scrollwheel_library'] ) ? true : false,
            'dont_enqueue_easing_library' => isset( $data['dont_enqueue_easing_library'] ) && !empty( $data['dont_enqueue_easing_library'] ) ? true : false,
            'disable_edit_create' => isset( $data['disable_edit_create'] ) && !empty( $data['disable_edit_create'] ) ? true : false,
            'twitter_user' => str_replace( "@", "", $data['twitter_user'] ), 'license_key' => $old_options['license_key'],
            'iframe_by_default' => isset( $data['iframe_by_default'] ) && !empty( $data['iframe_by_default'] ) ? true : false,
            'anonymous_stats_optin' => isset( $data['anonymous_stats_optin'] ) && !empty( $data['anonymous_stats_optin'] ) ? true : false,
            'anonymous_stats_has_opted' => true,
            'flush_wp_object_cache' => isset( $data['flush_wp_object_cache'] ) && !empty( $data['flush_wp_object_cache'] ) ? true : false
        );

        if( $options['anonymous_stats_optin'] === true || self::partner_override() ) {
            slidedeck2_km( "SlideDeck Installed", array( 'license' => self::$license, 'version' => self::$version ), self::partner_override() );
        }
        
        /**
         * Verify License Key
         */
        $response_json = $this->is_license_key_valid( $data['license_key'] );
        if( $response_json !== false ) {
            if( $response_json->valid == true ) {
                $options['license_key'] = $data['license_key'];
            }
        } else {
            $options['license_key'] = $data['license_key'];
        }

        if( empty( $data['license_key'] ) )
            $options['license_key'] = '';

        /**
         * Updating the options that
         * need to be updated by themselves.
         */
        // Update the Instagram Key
        update_option( $this->namespace . '_last_saved_instagram_access_token', slidedeck2_sanitize( $_POST['last_saved_instagram_access_token'] ) );
        // Update the Google+ API  Key
        update_option( $this->namespace . '_last_saved_gplus_api_key', slidedeck2_sanitize( $_POST['last_saved_gplus_api_key'] ) );

        /**
         * Updating the options that can be serialized.
         */
        // Update the options value with the data submitted
        update_option( $this->option_name, $options );

        slidedeck2_set_flash( "<strong>" . esc_html( __( "Options Successfully Updated", $this->namespace ) ) . "</strong>" );

        // Flush WordPress' memory of plugin updates.
        self::check_plugin_updates( );

        // Redirect back to the options page with the message flag to show the
        // saved message
        wp_safe_redirect( $_REQUEST['_wp_http_referer'] );
        exit ;
    }

    /**
     * Print editor only styles
     */
    function admin_print_editor_styles( ) {
        if( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post.php', 'post-new.php' ) ) ) {
            include_once (SLIDEDECK2_DIRNAME . '/views/elements/_editor-styles.php');
        }
    }

    /**
     * Load footer JavaScript for admin pages
     *
     * @uses SlideDeckPlugin::is_plugin()
     * @uses SlideDeckPointers::render()
     */
    function admin_print_footer_scripts( ) {
        global $wp_scripts, $wp_styles;

        if( $this->is_plugin( ) ) {
            $this->Pointers->render( );
        }

        // Add target="_blank" to support navigation element
        echo '<script type="text/javascript">var feedbacktab=jQuery("#toplevel_page_' . str_replace( ".php", "", SLIDEDECK2_BASENAME ) . '").find(".wp-submenu ul li a[href$=\'/support\']").attr("target", "_blank");</script>';
    }

    /**
     * Load JavaScript for the admin options page
     *
     * @uses SlideDeckPlugin::is_plugin()
     * @uses wp_enqueue_script()
     */
    function admin_print_scripts( ) {
        global $wp_scripts;
        
        $anonymous_stats = array(
            'apikey' => SLIDEDECK2_KMAPI_KEY,
            'optin' => $this->get_option( 'anonymous_stats_optin' ),
            'hash' => SLIDEDECK2_USER_HASH,
            'opted' => $this->get_option( 'anonymous_stats_has_opted' )
        );

        $license_expired = 'false';
        $license_key_expiry_message = $this->get_license_key_expiry_message();
        
        if( $license_key_expiry_message['type'] == 'unspecified' ) {
            $license_expired = 'false';
        } else {
            $license_expired = 'true';
        }
        
        echo '<script type="text/javascript">var SlideDeckInterfaces = {}; var SlideDeckAnonymousStats = ' . json_encode( $anonymous_stats ) . '; var SlideDeckLicenseExpired = ' . $license_expired . '; var SlideDeckLicenseExpiredOn = ' . intval( $license_key_expiry_message['date'] ) . ';</script>';
        
        $wp_scripts->registered["{$this->namespace}-library-js"]->src .= "?noping";
        
        wp_enqueue_script( "{$this->namespace}-library-js" );
        wp_enqueue_script( "{$this->namespace}-admin" );
        wp_enqueue_script( "{$this->namespace}-public" );
        wp_enqueue_script( "{$this->namespace}-preview" );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'thickbox' );
        wp_enqueue_script( 'editor' );
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_script( 'quicktags' );
        wp_enqueue_script( 'slidedeck-fancy-form' );
        wp_enqueue_script( 'tooltipper' );
        wp_enqueue_script( "{$this->namespace}-simplemodal" );
        wp_enqueue_script( 'jquery-minicolors' );
        wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_script( 'jquery-masonry' );
    }

    /**
     * Load stylesheets for the admin pages
     *
     * @uses wp_enqueue_style()
     * @uses SlideDeckPlugin::is_plugin()
     * @uses SlideDeck::get()
     * @uses SlideDeckPlugin::wp_print_styles()
     */
    function admin_print_styles( ) {
        wp_enqueue_style( "{$this->namespace}-admin" );
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_style( 'editor-buttons' );
        wp_enqueue_style( 'slidedeck-fancy-form' );

        // Make accommodations for the editing view to only load the lens files
        // for the SlideDeck being edited
        if( $this->is_plugin( ) ) {
            if( isset( $_GET['slidedeck'] ) ) {
                $slidedeck = $this->SlideDeck->get( $_GET['slidedeck'] );
                $lens = $slidedeck['lens'];
            } else {
                $lens = SLIDEDECK2_DEFAULT_LENS;
            }

            if( in_array( "gplus", $this->SlideDeck->current_source ) ) {
                wp_enqueue_style( "gplus-how-to-modal" );
            }

            $this->lenses_included = array( $lens => 1 );
        }

        if( $this->is_plugin( ) ) {
            wp_enqueue_style( 'wp-pointer' );
            wp_enqueue_style( 'jquery-minicolors' );
        }

        // Run the non-admin print styles method to load required lens CSS files
        $this->wp_print_styles( );
    }

    /**
     * Load IE only stylesheets for admin pages
     *
     * @uses SlideDeckPlugin::is_plugin()
     */
    function admin_print_ie_styles( ) {
        if( $this->is_plugin( ) ) {
            echo '<!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="' . SLIDEDECK2_URLPATH . '/css/ie.css" /><![endif]-->';
            echo '<!--[if gte IE 9]><link rel="stylesheet" type="text/css" href="' . SLIDEDECK2_URLPATH . '/css/ie9.css" /><![endif]-->';
        }
    }

    /**
     * Define the admin menu options for this plugin
     *
     * @uses add_action()
     * @uses add_options_page()
     */
    function admin_menu( ) {
        $show_menu = true;
        if( $this->get_option( 'disable_edit_create' ) == true ) {
            if( !current_user_can( 'manage_options' ) ) {
                $show_menu = false;
            }
        }
        if( $show_menu === true ) {
            add_menu_page( 'SlideDeck 2', 'SlideDeck 2', 'publish_posts', SLIDEDECK2_BASENAME, array( &$this, 'page_route' ), SLIDEDECK2_URLPATH . '/images/icon.png', 37 );

            $this->menu['manage'] = add_submenu_page( SLIDEDECK2_BASENAME, 'Manage SlideDecks', 'Manage', 'publish_posts', SLIDEDECK2_BASENAME, array( &$this, 'page_route' ) );
            $this->menu['lenses'] = add_submenu_page( SLIDEDECK2_BASENAME, 'SlideDeck Lenses', 'Lenses', 'manage_options', SLIDEDECK2_BASENAME . '/lenses', array( &$this, 'page_lenses_route' ) );
            $this->menu['options'] = add_submenu_page( SLIDEDECK2_BASENAME, 'SlideDeck Options', 'Advanced Options', 'manage_options', SLIDEDECK2_BASENAME . '/options', array( &$this, 'page_options' ) );
            $this->menu['upgrades'] = add_submenu_page( SLIDEDECK2_BASENAME, 'Get More Features', 'Get More Features', 'manage_options', SLIDEDECK2_BASENAME . '/upgrades', array( &$this, 'page_upgrades' ) );
            $this->menu['support'] = add_submenu_page( SLIDEDECK2_BASENAME, 'Get Support', 'Get Support', 'manage_options', SLIDEDECK2_BASENAME . '/support', array( &$this, 'page_route' ) );

            add_action( "load-{$this->menu['manage']}", array( &$this, "load_admin_page" ) );
            add_action( "load-{$this->menu['lenses']}", array( &$this, "load_admin_page" ) );
            add_action( "load-{$this->menu['options']}", array( &$this, "load_admin_page" ) );
        }
    }

    function after_plugin_row() {
        $license_key = slidedeck2_get_license_key();
        $message = self::get_license_key_expiry_message();
        
        if( !empty( $license_key ) && !empty( $message['text'] ) ) {
            // If license is expired, or expiring.
            $message['text'] .= ". Keep your license current for support and updates.";
            $style = '';

            if( in_array( $message['type'], array( 'nearing-expiration', 'expired' ) ) ) {
                $style = 'background-color: #ffebe8; border-color: #c00;';
            }

            if( !empty( $message['text'] ) ) {
                echo '<tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div style="' . $style . '" class="slidedeck2 update-message">' . $message['text'] . '</div></td></tr>';
            }
        }
    }
    
    /**
     * AJAX response to adding a source to a SlideDeck
     * 
     * Adds a source to a SlideDeck and its preview SlideDeck entry and returns HTML
     * markup for the slide manager area. This method also checks to see if things like
     * the lens need to be changed based off the sources now in the SlideDeck.
     * 
     * @uses SlideDeck::add_source()
     * @uses SlideDeck::save_preview()
     * @uses SlideDeckPlugin::get_sources()
     * @uses wp_verify_nonce()
     */
    function ajax_add_source( ) {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'slidedeck-add-source' ) ) {
            die( "false" );
        }

        $namespace = $this->namespace;
        $slidedeck_id = intval( $_REQUEST['slidedeck'] );
        
        // Add source to the parent SlideDeck
        $slidedeck_sources = $this->SlideDeck->add_source( $slidedeck_id, $_REQUEST['source'] );
        
        // Update the preview SlideDeck
        $_REQUEST['source'] = $slidedeck_sources;
        $slidedeck = $this->SlideDeck->save_preview( $slidedeck_id, $_REQUEST );
        
        $default_to_toolkit = false;
        
        // Reset the lens choice to Tool-kit if coming from a Twitter only SlideDeck
        if( $slidedeck['lens'] == "twitter" && (count( $slidedeck_sources ) > 1) ) {
            $default_to_toolkit = true;
        }
        
        // Reset the lens choice to Tool-kit if coming from an all video SlideDeck to a non-all-video SlideDeck
        $all_video_slidedeck = true;
        foreach( $slidedeck_sources as $source ) {
            if( !in_array( $source, array( 'vimeo', 'youtube', 'dailymotion' ) ) ) {
                $all_video_slidedeck = false;
            }
        }
        if( $slidedeck['lens'] == "video" && $all_video_slidedeck !== true ) {
            $default_to_toolkit = true;
        }
        
        // Update the SlideDeck preview with the Tool-kit lens if needed
        if( $default_to_toolkit == true ) {
            $_REQUEST['lens'] = "tool-kit";
            $slidedeck = $this->SlideDeck->save_preview( $slidedeck_id, $_REQUEST );
        }
        
        // Get all sources models that apply to the updated SlideDeck
        $sources = $this->get_sources( $slidedeck_sources );
        if( isset( $sources['custom'] ) )
            unset( $sources['custom'] );

        include (SLIDEDECK2_DIRNAME . '/views/elements/_sources.php');
        exit ;
    }

    /**
     * AJAX response to upsell modal
     */
    function ajax_anonymous_stats_optin() {
        include( SLIDEDECK2_DIRNAME . '/views/elements/_anonymous-stats-optin-modal.php' );
        exit;
    }

    /**
     * Outputs an <ul> for the SlideDeck Blog on the "Overview" page
     *
     * @uses fetch_feed()
     * @uses wp_redirect()
     * @uses SlideDeckPlugin::action()
     * @uses is_wp_error()
     * @uses SimplePie::get_item_quantity()
     * @uses SimplePie::get_items()
     */
    function ajax_blog_feed( ) {
        if( !SLIDEDECK2_IS_AJAX_REQUEST ) {
            wp_redirect( $this->action( ) );
            exit ;
        }

        $rss = fetch_feed( array( 'http://feeds.feedburner.com/Slidedeck', 'http://feeds.feedburner.com/digital-telepathy' ) );

        // Checks that the object is created correctly
        if( !is_wp_error( $rss ) ) {
            // Figure out how many total items there are, but limit it to 5.
            $maxitems = $rss->get_item_quantity( 3 );

            // Build an array of all the items, starting with element 0 (first
            // element).
            $rss_items = $rss->get_items( 0, $maxitems );

            include (SLIDEDECK2_DIRNAME . '/views/elements/_blog-feed.php');
            exit ;
        }

        die( "Could not connect to SlideDeck blog feed..." );
    }

    /**
     * AJAX response for an updated list of available lenses to a SlideDeck
     *
     * Looks up available lenses for a SlideDeck and returns the markup to update
     * the Lens options group.
     *
     * @uses SlideDeckPlugin::get_slidedeck_lenses()
     */
    function ajax_update_available_lenses( ) {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-update-available-lenses" ) ) {
            wp_die( __( "You are not authorized to do that.", $this->namespace ) );
        }

        $slidedeck_id = intval( $_REQUEST['slidedeck_id'] );
        $slidedeck_preview_id = $this->SlideDeck->get_preview_id( $slidedeck_id );
        $slidedeck = $this->SlideDeck->get( $slidedeck_preview_id );
        $this->SlideDeck->current_source = $slidedeck['source'];
        $lenses = $this->get_slidedeck_lenses( $slidedeck );

        include (SLIDEDECK2_DIRNAME . '/views/elements/_options-lenses.php');
        exit ;
    }

    /**
     * AJAX response to upsell modal
     */
    function ajax_upsell_modal_content() {
        include( SLIDEDECK2_DIRNAME . '/views/upsells/_upsell-modal-' . $_REQUEST['feature'] . '.php' );
        exit;
    }

    /**
     * Outputs SlideDeck Markup for the latest tweets deck
     *
     * @uses fetch_feed()
     * @uses wp_redirect()
     * @uses SlideDeckPlugin::action()
     * @uses is_wp_error()
     * @uses SimplePie::get_item_quantity()
     * @uses SimplePie::get_items()
     */
    function ajax_tweet_feed( ) {
        if( !SLIDEDECK2_IS_AJAX_REQUEST ) {
            wp_redirect( $this->action( ) );
            exit ;
        }

        // Combines the dt and sd feeds:
        $rss = fetch_feed( array( 'http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=slidedeck', 'http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=dtelepathy', ) );

        // Checks that the object is created correctly
        if( !is_wp_error( $rss ) ) {
            // Figure out how many total items there are, but limit it to 5.
            $maxitems = $rss->get_item_quantity( 5 );

            // Build an array of all the items, starting with element 0 (first
            // element).
            $rss_items = $rss->get_items( 0, $maxitems );

            $url_regex = '/((https?|ftp|gopher|telnet|file|notes|ms-help):((\/\/)|(\\\\))+[\w\d:#@%\/\;$()~_?\+-=\\\.&]*)/';
            $formatted_rss_items = array( );
            foreach( $rss_items as $key => $value ) {
                $tweet = $value->get_title( );

                // Remove the 'dtelepathy: ' part at the beginning of the feed:
                $tweet = preg_replace( '/^[^\s]+:\s/', '', $tweet );
                // Link all the links:
                $tweet = preg_replace( $url_regex, '<a href="$1" target="_blank">' . "$1" . '</a>', $tweet );
                // Link the hashtags and mentions
                $tweet = preg_replace( array( '/\@([a-zA-Z0-9_]+)/', # Twitter
                # Usernames
                '/\#([a-zA-Z0-9_]+)/' # Hash Tags
                ), array( '<a href="http://twitter.com/$1" target="_blank">@$1</a>', '<a href="http://twitter.com/search?q=%23$1" target="_blank">#$1</a>' ), $tweet );

                $formatted_rss_items[] = array( 'tweet' => $tweet, 'time_ago' => human_time_diff( strtotime( $value->get_date( ) ), current_time( 'timestamp' ) ) . " ago", 'permalink' => $value->get_permalink( ) );
            }

            include (SLIDEDECK2_DIRNAME . '/views/elements/_latest-tweets.php');
            exit ;
        }

        die( "Could not connect to Twitter..." );
    }

    /**
     * Change Lens for the current SlideDeck
     *
     * @uses wp_verify_nonce()
     * @uses SlideDeckPlugin::_save_autodraft()
     * @uses apply_filters()
     */
    function ajax_change_lens( ) {
        // Fail silently if the request could not be verified
        if( !wp_verify_nonce( $_REQUEST['_wpnonce_lens_update'], 'slidedeck-lens-update' ) ) {
            die( "false" );
        }

        $namespace = $this->namespace;

        $slidedeck_id = intval( $_REQUEST['id'] );
        $response = $this->_save_autodraft( $slidedeck_id, $_REQUEST );

        $slidedeck = $response['preview'];

        $lenses = $this->get_slidedeck_lenses( $slidedeck );

        $lens = $this->Lens->get( $slidedeck['lens'] );
        $lens_classname = slidedeck2_get_classname_from_filename( $slidedeck['lens'] );
        $response['lens'] = $lens;

        $options_model = $this->get_options_model( $slidedeck );
        
        // If this Lens has an options model, loop through it and set the new
        // defaults
        if( isset( $this->lenses[$lens_classname]->options_model ) ) {
            $lens_options_model = $this->lenses[$lens_classname]->options_model;
            // Loop through Lens' option groups
            foreach( $lens_options_model as $lens_options_group => $lens_group_options ) {
                // Loop through Lens' option group options
                foreach( $lens_group_options as $name => $properties ) {
                    // If the filtered options model has a value set, use it as
                    // an override to the saved value
                    if( isset( $options_model[$lens_options_group][$name]['value'] ) )
                        $slidedeck['options'][$name] = $options_model[$lens_options_group][$name]['value'];
                }
            }
        }

        $response['sizes'] = apply_filters( "{$this->namespace}_sizes", $this->sizes, $slidedeck );

        uksort( $options_model['Appearance']['titleFont']['values'], 'strnatcasecmp' );
        uksort( $options_model['Appearance']['bodyFont']['values'], 'strnatcasecmp' );

        // Trim out the Setup key
        $trimmed_options_model = $options_model;
        unset( $trimmed_options_model['Setup'] );
        $options_groups = $this->options_model_groups;

        $sizes = apply_filters( "{$this->namespace}_sizes", $this->sizes, $slidedeck );

        ob_start( );
        include (SLIDEDECK2_DIRNAME . '/views/elements/_options.php');
        $response['options_html'] = ob_get_contents( );
        ob_end_clean( );
        
        die( json_encode( $response ) );
    }

    /**
     * AJAX response for checking the license expiry state
     */
    function ajax_check_license_expiry( ) {
        // Fail silently if the request could not be verified
        if( !wp_verify_nonce( $_REQUEST['_license_status_nonce'], 'slidedeck-check-license-status' ) ) {
            die( "false" );
        }

        $data = slidedeck2_sanitize( $_REQUEST );

        $license_key_status = $this->is_license_key_valid( $this->get_license_key() );

        // Update the extra cached options
        update_option( 'slidedeck2_cached_tier', $license_key_status->tier );
        update_option( 'slidedeck2_cached_expiration', $license_key_status->expires );

        echo $this->upgrade_button( $data['context'] );
        exit;
    }

    /**
     * AJAX response for Covers edit modal
     */
    function ajax_covers_modal( ) {
        if( !class_exists( 'SlideDeckCovers' ) )
            return false;
        
        $slidedeck_id = $_REQUEST['slidedeck'];

        $slidedeck = $this->SlideDeck->get( $slidedeck_id );
        $cover = $this->Cover->get( $slidedeck_id );
        
        $slidedeck_fonts = $this->SlideDeck->get_fonts( $slidedeck );
        
        foreach( $slidedeck_fonts as $font => $properties ) {
            $this->Cover->options_model['title_font']['values'][$font] = $properties['label'];
        }
        
        $dimensions = $this->SlideDeck->get_dimensions( $slidedeck );
        $scaleRatio = 516 / $dimensions['outer_width'];
        if( $scaleRatio > 1 )
            $scaleRatio = 1;

        $size_class = $slidedeck['options']['size'];
        if( $slidedeck['options']['size'] == "custom" ) {
            $size_class = $this->SlideDeck->get_closest_size( $slidedeck );
        }

        $namespace = $this->namespace;

        $cover_options_model = $this->Cover->options_model;

        // Options for both front and back covers
        $global_options = array( 'title_font', 'accent_color', 'cover_style', 'variation', 'peek' );
        // Front cover options
        $front_options = array( 'front_title', 'show_curator' );
        // Back cover options
        $back_options = array( 'back_title', 'button_label', 'button_url' );

        $variations = $this->Cover->variations;
        $cover_options_model['variation']['values'] = $variations[$cover['cover_style']];

        include (SLIDEDECK2_DIRNAME . '/views/cover-modal.php');
        exit ;
    }

    /**
     * Create a new post/page with a SlideDeck
     *
     * @uses admin_url()
     * @uses current_user_can()
     * @uses get_post_type_object()
     * @uses wp_die()
     * @uses wp_insert_post()
     * @uses wp_redirect()
     */
    function ajax_create_new_with_slidedeck( ) {
        // Allowed post types to start with a SlideDeck
        $acceptable_post_types = array( 'post', 'page' );
        $post_type = in_array( $_REQUEST['post_type'], $acceptable_post_types ) ? $_REQUEST['post_type'] : 'post';

        // Get the post type object
        $post_type_object = get_post_type_object( $post_type );

        // Make sure the user can actually edit this post type, if not fail
        if( !current_user_can( $post_type_object->cap->edit_posts ) )
            wp_die( __( "You are not authorized to do that", $this->namespace ) );

        $slidedeck_id = intval( $_REQUEST['slidedeck'] );

        $params = array( 'post_type' => $post_type, 'post_status' => 'auto-draft', 'post_title' => "", 'post_content' => "<p>" . $this->get_slidedeck_shortcode( $slidedeck_id ) . "</p>" );

        $new_post_id = wp_insert_post( $params );

        wp_redirect( admin_url( 'post.php?post=' . $new_post_id . '&action=edit' ) );
        exit ;
    }

    /**
     * Delete a SlideDeck
     *
     * AJAX response for deletion of a SlideDeck
     *
     * @uses wp_verify_nonce()
     * @uses wp_delete_post()
     * @uses SlideDeckPlugin::load_slides()
     * @uses wp_remote_fopen()
     */
    function ajax_delete( ) {
        if( !SLIDEDECK2_IS_AJAX_REQUEST ) {
            wp_redirect( $this->action( ) );
            exit ;
        }

        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-delete-slidedeck" ) ) {
            die( "false" );
        }

        $slidedeck_id = $_REQUEST['slidedeck'];

        $this->SlideDeck->delete( $slidedeck_id );

        $redirect = $this->action( ) . "&msg_deleted=1";

        slidedeck2_km( "SlideDeck Deleted" );

        die( $redirect );
    }

    /**
     * Duplicate a SlideDeck
     *
     * AJAX response for duplication of a SlideDeck
     *
     * @uses wp_verify_nonce()
     */
    function ajax_duplicate( ) {
        if( !SLIDEDECK2_IS_AJAX_REQUEST ) {
            wp_redirect( $this->action( ) );
            exit ;
        }

        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-duplicate-slidedeck" ) ) {
            die( "false" );
        }

        $slidedeck_id = $_REQUEST['slidedeck'];
        $this->SlideDeck->duplicate_slidedeck( $slidedeck_id );

        // Grab the order from the saved option value
        $orderby = get_option( "{$this->namespace}_manage_table_sort" );
        $order = $orderby == 'post_modified' ? 'DESC' : 'ASC';

        $namespace = $this->namespace;
        $slidedecks = $this->SlideDeck->get( null, $orderby, $order, 'publish' );

        include (SLIDEDECK2_DIRNAME . '/views/elements/_manage-table.php');

        slidedeck2_km( "SlideDeck Duplicated" );
        exit ;
    }

    /**
     * Delete a lens
     *
     * AJAX response for deleting a SlideDeck lens
     *
     * @uses SlideDeckLens::delete()
     */
    function ajax_delete_lens( ) {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-delete-lens" ) ) {
            die( "false" );
        }

        header( "Content Type: application/json" );


        $data = slidedeck2_sanitize( $_POST );
        $response = array( 'message' => "Lens deleted successfuly", 'error' => false );
        
        if( !current_user_can( 'delete_themes' ) ) {
            $response['message'] = "Sorry, your user does not have permission to delete a lens";
            $response['error'] = true;
            die( json_encode( $response ) );
        }

        if( !isset( $data['lens'] ) ) {
            $response['message'] = "No lens was specified";
            $response['error'] = true;
            die( json_encode( $response ) );
        }

        if( !$response['error'] ) {
            $lens = $this->Lens->delete( $data['lens'] );
            if( $lens == false ) {
                $response['message'] = "Folder could not be deleted, please make sure the server can delete this folder";
                $response['error'] = true;
                $response['redirect'] = $this->action( '/lenses' ) . '&action=delete_authorize&lens=' . $data['lens'] . '&_wpnonce=' . wp_create_nonce( $this->namespace . '-delete-lens-authorize' );
            } else {
                slidedeck2_km( "SlideDeck Deleted Lens", array( 'slug' => $data['lens'] ) );
            }
        }

        die( json_encode( $response ) );
    }
        
    /**
     * Delete a source
     *
     * AJAX response for deleting a SlideDeck source from a multi-source
     * dynamic SlideDeck.
     */
    function ajax_delete_source( ) {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-delete-source" ) ) {
            die( "false" );
        }

        if( !isset( $_REQUEST['slidedeck'] ) ) {
            die( "false" );
        }

        $namespace = $this->namespace;
        $source = $_REQUEST['source'];
        $slidedeck_id = intval( $_REQUEST['slidedeck'] );

        $this->SlideDeck->delete_source( $slidedeck_id, $source );
        $slidedeck_preview_id = $this->SlideDeck->get_preview_id( $slidedeck_id );
        $slidedeck = $this->SlideDeck->get( $slidedeck_preview_id );

        $sources = $this->get_sources( $slidedeck['source'] );
        if( isset( $sources['custom'] ) )
            unset( $sources['custom'] );

        include (SLIDEDECK2_DIRNAME . '/views/elements/_sources.php');
        exit ;
    }

    /**
     * First save dialog box
     *
     * AJAX response for display of first save dialog box
     *
     * @uses SlideDeck::get()
     */
    function ajax_first_save_dialog( ) {
        $slidedeck_id = intval( $_REQUEST['slidedeck'] );
        $slidedeck = $this->SlideDeck->get( $slidedeck_id );
        $namespace = $this->namespace;

        $iframe_by_default = $this->get_option( 'iframe_by_default' );

        include (SLIDEDECK2_DIRNAME . '/views/first-save-dialog.php');
        exit ;
    }

    /**
     * Get code dialog box
     *
     * AJAX response for display of get code dialog box
     *
     * @uses SlideDeck::get()
     */
    function ajax_getcode_dialog( ) {
        $slidedeck_id = intval( $_REQUEST['slidedeck'] );
        $slidedeck = $this->SlideDeck->get( $slidedeck_id );
        $namespace = $this->namespace;
        
        $iframe_by_default = $this->get_option( 'iframe_by_default' );

        include (SLIDEDECK2_DIRNAME . '/views/getcode-dialog.php');
        exit ;
    }

    /**
     * Google+ Posts How to Modal
     *
     * AJAX response for Google+ Posts How to Modal
     */
    function ajax_gplus_posts_how_to_modal( ) {
        $namespace = $this->namespace;

        include (SLIDEDECK2_DIRNAME . '/views/gplus-posts-how-to.php');
        exit ;
    }

    /**
     * Insert SlideDeck iframe
     *
     * Generates a list of SlidDecks available to insert into a post
     *
     * @global $wp_scripts
     *
     * @uses SlideDeckPlugin::get_insert_iframe_table()
     */
    function ajax_insert_iframe( ) {
        global $wp_scripts;

        $order_options = $this->order_options;
        $orderby = isset( $_GET['orderby'] ) ? $_GET['orderby'] : get_option( "{$this->namespace}_manage_table_sort", reset( array_keys( $this->order_options ) ) );

        $namespace = $this->namespace;
        $previous_slidedeck_type = "";

        $insert_iframe_table = $this->get_insert_iframe_table( $orderby );
        
        $scripts = array( 'jquery', 'slidedeck-admin', 'slidedeck-fancy-form' );
        $content_url = defined( 'WP_CONTENT_URL' ) ? WP_CONTENT_URL : '';
        $base_url = !site_url( ) ? wp_guess_url( ) : site_url( );

        include (SLIDEDECK2_DIRNAME . '/views/insert-iframe.php');
        exit ;
    }

    /**
     * AJAX update of Insert SlideDeck iframe table
     *
     * Changes the ordering of the SlideDecks in the insert table
     *
     * @uses wp_verify_nonce()
     * @uses wp_die()
     * @uses SlideDeckPlugin::get_insert_iframe_table()
     */
    function ajax_insert_iframe_update( ) {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce_insert_update'], "slidedeck-update-insert-iframe" ) )
            wp_die( __( "Unauthorized form submission!", $this->namespace ) );

        $selected = isset( $_REQUEST['slidedecks'] ) ? $_REQUEST['slidedecks'] : array( );

        $insert_iframe_table = $this->get_insert_iframe_table( $_REQUEST['orderby'], (array)$selected );

        die( $insert_iframe_table );
    }

    /**
     * AJAX response for post header redirect
     *
     * @uses wp_verify_nonce()
     */
    function ajax_post_header_redirect( ) {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-post-header-redirect" ) ) {
            wp_die( "You do not have access to this URL" );
        }

        $location = $_REQUEST['location'];

        $message = "";
        if( isset( $_REQUEST['message'] ) ) {
            $message = urldecode( $_REQUEST['message'] );
            slidedeck2_set_flash( $message, true );
        }

        wp_redirect( $location );
        exit ;
    }

    /**
     * AJAX function for previewing a SlideDeck in an iframe
     *
     * @param int $_GET['slidedeck_id'] The ID of the SlideDeck to load
     * @param int $_GET['width'] The width of the preview window
     * @param int $_GET['height'] The height of the preview window
     * @param int $_GET['outer_width'] The width of the SlideDeck in the preview
     * window
     * @param int $_GET['outer_height'] The height of the SlideDeck in the
     * preview window
     *
     * @return the preview window as templated in views/preview-iframe.php
     */
    function ajax_preview_iframe( ) {
        global $wp_scripts, $wp_styles;
        
        $slidedeck_id = $_GET['slidedeck'];
        if( isset( $_GET['width'] ) && is_numeric( $_GET['width'] ) )
            $width = $_GET['width'];
        if( isset( $_GET['height'] ) && is_numeric( $_GET['height'] ) )
            $height = $_GET['height'];
        if( isset( $_GET['outer_width'] ) && is_numeric( $_GET['outer_width'] ) )
            $outer_width = $_GET['outer_width'];
        if( isset( $_GET['outer_height'] ) && is_numeric( $_GET['outer_height'] ) )
            $outer_height = $_GET['outer_height'];
        
        $start_slide = false;
        if( isset( $_GET['start'] ) && is_numeric( $_GET['start'] ) )
            $start_slide = (int) $_GET['start'];
        
        $slidedeck = $this->SlideDeck->get( $slidedeck_id );
        
        /**
         * If there's no width or height specified, we should infer the 
         * width and height based on the outer width or outer height.
         */
        if( empty( $width ) || empty( $height ) ) {
            $slidedeck_dimensions = $this->SlideDeck->get_dimensions( $slidedeck );
            // $width_diff = $slidedeck_dimensions['outer_width'] - $slidedeck_dimensions['width'];
            // $height_diff = $slidedeck_dimensions['outer_height'] - $slidedeck_dimensions['height'];
            
            if( empty( $width ) ) {
                $width = $outer_width;
            }
                
            if( empty( $height ) ) {
                $height = $outer_height;
            }
            
            $slidedeck['options']['size'] = 'custom';
            $slidedeck['options']['width'] = $width;
            $slidedeck['options']['height'] = $height;
            
        }
        

        $lens = $this->Lens->get( $slidedeck['lens'] );
        
        // Is this a preview or an iframe=1 shortcode embed?
        $preview = false;
        if( isset( $_GET['preview'] ) ) {
            if( (int) $_GET['preview'] === 1 ) {
                $this->preview = $preview = true;
            }
        }
        
        // Is this a RESS shortcode embed?
        $ress = false;
        if( isset( $_GET['slidedeck_unique_id'] ) ) {
            if( !empty( $_GET['slidedeck_unique_id'] ) ) {
                $ress = true;
            }
        }
        
        // Kill caching if using W3TC when updating the preview
        if( $preview ) {
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
            
            if( !defined( 'DONOTCACHEOBJECT' ) ) define( 'DONOTCACHEOBJECT', true );
            if( !defined( 'DONOTCACHEPAGE' ) ) define( 'DONOTCACHEPAGE', true );
            if( !defined( 'DONOTCACHEDB' ) ) define( 'DONOTCACHEDB', true );
        }
        
        $namespace = $this->namespace;

        if( isset( $outer_width ) ) {
            $preview_scale_ratio = $outer_width / 347;
            $preview_font_size = intval( min( $preview_scale_ratio * 1000, 1139 ) ) / 1000;
        }
        
        $scripts = apply_filters( "{$this->namespace}_iframe_scripts", array( 'jquery', 'jquery-easing', 'scrolling-js', 'slidedeck-library-js', 'slidedeck-public' ), $slidedeck );

        $content_url = defined( 'WP_CONTENT_URL' ) ? WP_CONTENT_URL : '';
        $base_url = !site_url( ) ? wp_guess_url( ) : site_url( );

        include (SLIDEDECK2_DIRNAME . '/views/preview-iframe.php');
        exit ;
    }

    /**
     * AJAX function for getting a new preview URL in an iframe
     *
     * Saves an auto-draft of the SlideDeck being worked on and renders a JSON
     * response
     * with the URL to update the preview iframe, showing the auto-draft values.
     */
    function ajax_preview_iframe_update( ) {
        // Fail silently if the request could not be verified
        if( !wp_verify_nonce( $_REQUEST['_wpnonce_preview'], 'slidedeck-preview-iframe-update' ) ) {
            die( "false" );
        }

        // Parent SlideDeck ID
        $slidedeck_id = intval( $_REQUEST['id'] );
        $response = $this->_save_autodraft( $slidedeck_id, $_REQUEST );

        die( json_encode( $response ) );
    }

    /**
     * AJAX sort of manage table
     *
     * AJAX response to change sort of the manage view table of the user's
     * SlideDecks.
     * Updates the chosen sort method as well and uses it here and the insert
     * modal.
     *
     * @uses wp_verify_nonce()
     * @uses SlideDeck::get()
     * @uses update_option()
     */
    function ajax_sort_manage_table( ) {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'slidedeck-sort-manage-table' ) ) {
            die( "false" );
        }

        $orderby = in_array( $_REQUEST['orderby'], array_keys( $this->order_options ) ) ? $_REQUEST['orderby'] : reset( array_keys( $this->order_options ) );
        $order = $orderby == 'post_modified' ? 'DESC' : 'ASC';

        $namespace = $this->namespace;
        $slidedecks = $this->SlideDeck->get( null, $orderby, $order, 'publish' );

        update_option( "{$this->namespace}_manage_table_sort", $orderby );

        include (SLIDEDECK2_DIRNAME . '/views/elements/_manage-table.php');
        exit ;
    }

    /**
     * AJAX function for the source choice modal
     *
     * @uses wp_verify_nonce()
     */
    function ajax_source_modal( ) {
        // Fail silently if the request could not be verified
        if( !wp_verify_nonce( $_REQUEST['_wpnonce_source_modal'], 'slidedeck-source-modal' ) ) {
            die( "false" );
        }

        $sources = $this->get_sources( );
        if( isset( $sources['custom'] ) )
            unset( $sources['custom'] );

        $namespace = $this->namespace;
        $title = "Choose a source to get started";
        $action = "create";
        $disabled_sources = array( );
        $slidedeck_id = 0;

        if( isset( $_REQUEST['slidedeck'] ) && !empty( $_REQUEST['slidedeck'] ) ) {
            $action = "{$this->namespace}_add_source";
            $title = "Choose an additional content source";
            $slidedeck_id = intval( $_REQUEST['slidedeck'] );

            $slidedeck = $this->SlideDeck->get( $slidedeck_id );
            $disabled_sources = $slidedeck['source'];
        }

        include (SLIDEDECK2_DIRNAME . '/views/elements/_source-modal.php');
        exit ;
    }

    /**
     * AJAX response to save stage background preferences
     *
     * @global $current_user
     *
     * @uses get_currentuserinfo()
     * @uses wp_verify_nonce()
     * @uses update_post_meta()
     * @uses update_user_meta()
     */
    function ajax_stage_background( ) {
        global $current_user;
        get_currentuserinfo( );

        // Fail silently if not authorized
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-stage-background" ) ) {
            exit ;
        }

        $slidedeck_id = intval( $_POST['slidedeck'] );

        if( in_array( $_POST['background'], array_keys( $this->stage_backgrounds ) ) ) {
            update_post_meta( $slidedeck_id, "{$this->namespace}_stage_background", $_POST['background'] );
            update_user_meta( $current_user->ID, "{$this->namespace}_default_stage_background", $_POST['background'] );
        }
    }

    /**
     * AJAX response to validate a lens for copying
     *
     * @uses slidedeck2_sanitize()
     * @uses SlideDeckLens::get()
     */
    function ajax_validate_copy_lens( ) {
        header( "Content Type: application/json" );

        $data = slidedeck2_sanitize( $_REQUEST );
        $response = array( 'valid' => true );

        if( !isset( $data['slug'] ) ) {
            $response['valid'] = false;
        }

        if( $response['valid'] !== false ) {
            $lens = $this->Lens->get( $data['slug'] );

            if( $lens !== false ) {
                $response['valid'] = false;
            }
        }

        die( json_encode( $response ) );
    }

    /**
     * Ajax Verify License Key
     *
     * This function sends a request to the license server and
     * attempts to get a status on the license key in question.
     *
     * @uses wp_verify_nonce()
     *
     * @return string
     */
    function ajax_verify_license_key( ) {
        if( !wp_verify_nonce( $_REQUEST['verify_license_nonce'], "{$this->namespace}_verify_license_key" ) )
            wp_die( __( "Unauthorized request!", $this->namespace ) );

        $key = $_REQUEST['key'];

        $response_json = $this->is_license_key_valid( $key );

        if( $response_json !== false ) {
            if( $response_json->valid == true ) {
                // If the response is true, we save the key.

                // Get the options and then save em.
                $options = get_option( $this->option_name );
                $options['license_key'] = $key;
                update_option( $this->option_name, $options );

            }
            echo $response_json->message;
        } else {
            echo 'Connection error';
        }
        exit ;
    }

    /**
     * Ajax Verify Addon License Key
     *
     * This function sends a request to the license server and
     * attempts to get a status on the license key in question and
     * the installation buttons for the addons purchased
     *
     * @uses wp_verify_nonce()
     *
     * @return string
     */
    function ajax_verify_addons_license_key( ) {
        if( !wp_verify_nonce( $_REQUEST['verify_addons_nonce'], "{$this->namespace}_verify_addons_license_key" ) )
            wp_die( __( "Unauthorized request!", $this->namespace ) );

        $license_key = $_REQUEST['data']['license_key'];
        $install_link = false;
        $installable_addons = false;
        $cohort_data = self::get_cohort_data();

        if( isset( $_REQUEST['imback'] ) && $_REQUEST['imback'] === 'true' )
            $this->user_is_back = true;

        if( isset( $_REQUEST['tier'] ) && !empty( $_REQUEST['tier'] ) )
            $this->upgraded_to_tier = intval( $_REQUEST['tier'] );

        $response = wp_remote_post( SLIDEDECK2_UPDATE_SITE . '/available-addons', array(
                'method' => 'POST', 
                'timeout' => 4, 
                'redirection' => 5, 
                'httpversion' => '1.0', 
                'blocking' => true,
                'headers' => array(
                    'SlideDeck-Version' => SLIDEDECK2_VERSION,
                    'User-Agent' => 'WordPress/' . get_bloginfo("version"),
                    'Referer' => get_bloginfo("url"),
                    'Addons' => '1'
                ),
                'body' => array(
                    'key' => md5( $license_key ),
                    'redirect_after' => urlencode( admin_url( '/admin.php?page=' . basename( SLIDEDECK2_BASENAME ) ) ),
                    'installed_addons' => SlideDeckPlugin::$addons_installed,
                    'user_is_back' => $this->user_is_back,
                    'upgraded_to_tier' => $this->upgraded_to_tier,
                    'cohort_data' => $cohort_data,
                ), 
                'cookies' => array(),
                'sslverify' => false
            )
        );
        if( !is_wp_error( $response ) ) {
            //echo json_decode( $response['body'], true );
            echo $response['body'];
        }
        exit;
    }

    /**
     * Is Key Vaild?
     *
     * @return object Response Object
     */
    function is_license_key_valid( $key ) {
        $key = slidedeck2_sanitize( $key );
        $upgrade_url = SLIDEDECK2_UPDATE_SITE . '/wordpress-update/' . md5( $key );
        
        $response = wp_remote_post( $upgrade_url, array( 'method' => 'POST', 'timeout' => 4, 'redirection' => 5, 'httpversion' => '1.0', 'blocking' => true, 'headers' => array( 'SlideDeck-Version' => SLIDEDECK2_VERSION, 'User-Agent' => 'WordPress/' . get_bloginfo( "version" ), 'Referer' => get_bloginfo( "url" ), 'Verify' => '1' ), 'body' => null, 'cookies' => array( ), 'sslverify' => false ) );

        if( !is_wp_error( $response ) ) {
            $response_body = $response['body'];
            $response_json = json_decode( $response_body );

            // Only return if the response is a JSON response
            if( is_object( $response_json ) ) {
                return $response_json;
            }
        }

        // Return boolean(false) if this request was not valid
        return false;
    }

    /**
     * Copy a lens
     *
     * Form submission response for copying a SlideDeck lens
     *
     * @uses SlideDeckLens::copy()
     */
    function copy_lens( ) {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-copy-lens" ) ) {
            die( "false" );
        }

        $data = slidedeck2_sanitize( $_POST );

        if( !isset( $data['new_lens_slug'] ) ) {
            slidedeck2_set_flash( "<strong>ERROR:</strong> " . esc_html( __( "No lens slug was specified", $this->namespace ) ), true );
            wp_redirect( $_REQUEST['_wp_http_referer'] );
            exit ;
        }

        if( $this->Lens->get( $data['new_lens_slug'] ) !== false ) {
            slidedeck2_set_flash( "<strong>ERROR:</strong> " . esc_html( __( "The lens slug must be unique", $this->namespace ) ), true );
            wp_redirect( $_REQUEST['_wp_http_referer'] );
            exit ;
        }

        // A new suggested lens name from the user
        $new_lens_name = isset( $data['new_lens_name'] ) ? $data['new_lens_name'] : "";
        // A new suggested slug name from the user
        $new_lens_slug = isset( $data['new_lens_slug'] ) ? $data['new_lens_slug'] : "";
        
        $replace_js = false;
        if( $data['create_or_copy'] == "create" )
            $replace_js = true;

        /**
         * If the lens is compatible with having its JS copied,
         * then we can attempt to do so. The eventual plan is to
         * have all lenses support this.
         */
        $lens_whitelist = array(
            'block-title',
            'fashion',
            'half-moon',
            'o-town',
            'proto',
            'tool-kit',
            'reporter',
            'video'
        );
        if( in_array( $data['lens'], $lens_whitelist ) )
            $replace_js = true;

        $lens = $this->Lens->copy( $data['lens'], $new_lens_name, $new_lens_slug, $replace_js );

        if( $lens ) {
            slidedeck2_set_flash( "<strong>" . esc_html( __( "Lens Copied Successfully", $this->namespace ) ) . "</strong>" );
            slidedeck2_km( "New Lens " . ( $data['create_or_copy'] == 'create' ? "Created" : "Copied" ) );
        } else {
            slidedeck2_set_flash( __( "<strong>ERROR:</strong> Could not copy skin because the " . SLIDEDECK2_CUSTOM_LENS_DIR . " directory is not writable or does not exist.", 'slidedeck' ), true );
        }

        wp_redirect( $this->action( "/lenses" ) );
        exit ;
    }

    /**
     * Delete plugin update record meta to re-check plugin for version update
     *
     * @uses delete_option()
     * @uses wp_update_plugins()
     */
    public static function check_plugin_updates( ) {
        delete_site_transient( 'update_plugins' );
        wp_update_plugins( );
    }

    /**
     * Hook into register_deactivation_hook action
     *
     * Put code here that needs to happen when your plugin is deactivated
     *
     * @uses SlideDeckPlugin::check_plugin_updates()
     * @uses wp_remote_fopen()
     */
    static function deactivate( ) {
        SlideDeckPlugin::load_constants();
        self::check_plugin_updates( );
        
        include (dirname( __FILE__ ) . '/lib/template-functions.php');

        slidedeck2_km( "SlideDeck Deactivated" );
    }
    
    /**
     * Remove a lens (for system setups that require authorization)
     *
     * @since 2.8.0
     *
     * @param string $stylesheet Stylesheet of the theme to delete
     * @param string $redirect Redirect to page when complete.
     * @return mixed
     */
    function delete_lens_authorize( $lens, $redirect = '' ) {
        global $wp_filesystem;
    
        if( empty( $lens ) )
            return false;
        
        ob_start();
        if( empty( $redirect ) )
            $redirect = $this->action( '/lenses' ) . '&action=delete_authorize&lens=' . $lens . '&_wpnonce=' . wp_create_nonce( $this->namespace . '-delete-lens-authorize' );
        if( false === ( $credentials = request_filesystem_credentials( $redirect ) ) ) {
            $data = ob_get_contents();
            ob_end_clean();
            if( ! empty($data) ){
                include_once( ABSPATH . 'wp-admin/admin-header.php');
                echo $data;
                include( ABSPATH . 'wp-admin/admin-footer.php');
                exit;
            }
            return;
        }
    
        if( !WP_Filesystem( $credentials ) ) {
            request_filesystem_credentials( $url, '', true ); // Failed to connect, Error and request again
            $data = ob_get_contents();
            ob_end_clean();
            if ( ! empty($data) ) {
                include_once( ABSPATH . 'wp-admin/admin-header.php');
                echo $data;
                include( ABSPATH . 'wp-admin/admin-footer.php');
                exit;
            }
            return;
        }
    
        if( !is_object( $wp_filesystem ) )
            return new WP_Error( 'fs_unavailable', __( 'Could not access filesystem.' ) );
    
        if( is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() )
            return new WP_Error( 'fs_error', __( 'Filesystem error.' ), $wp_filesystem->errors );
    
        //Get the base plugin folder
        $custom_lenses_dir = SLIDEDECK2_CUSTOM_LENS_DIR;
        if( empty( $custom_lenses_dir ) )
            return new WP_Error( 'fs_no_themes_dir', __( 'Unable to locate SlideDeck 2 lens directory.', $this->namespace ) );
    
        $custom_lenses_dir = trailingslashit( $custom_lenses_dir );
        $custom_lenses_dir = trailingslashit( $custom_lenses_dir . $lens );
        $deleted = $wp_filesystem->delete( $custom_lenses_dir, true );
        
        if( !$deleted )
            return new WP_Error( 'could_not_remove_theme', sprintf( __('Could not fully remove the lens %s.', $this->namespace ), $lens ) );
        
        slidedeck2_km( "SlideDeck Deleted Lens", array( 'slug' => $lens ) );
        
        return true;
    }

    /**
     * Get dimensions of a SlideDeck
     *
     * Returns an array of the inner and outer dimensions of the SlideDeck
     *
     * @param array $slidedeck The SlideDeck object
     *
     * @return array
     */
    function get_dimensions( $slidedeck ) {
        $dimensions = array( );

        $sizes = apply_filters( "{$this->namespace}_sizes", $this->sizes, $slidedeck );

        $dimensions['width'] = $slidedeck['options']['size'] != "custom" ? $sizes[$slidedeck['options']['size']]['width'] : $slidedeck['options']['width'];
        $dimensions['height'] = $slidedeck['options']['size'] != "custom" ? $sizes[$slidedeck['options']['size']]['height'] : $slidedeck['options']['height'];
        $dimensions['outer_width'] = $dimensions['width'];
        $dimensions['outer_height'] = $dimensions['height'];

        do_action_ref_array( "{$this->namespace}_dimensions", array( &$dimensions['width'], &$dimensions['height'], &$dimensions['outer_width'], &$dimensions['outer_height'], &$slidedeck ) );

        return $dimensions;
    }

    /**
     * Get the URL for an iframe preview
     *
     * @param integer $id The ID of the SlideDeck to preview
     * @param integer $width Optional width of the SlideDeck itself
     * @param integer $height Optional height of the SlideDeck itself
     * @param integer $outer_width Optional outer width of the SlideDeck iframe
     * area
     * @param integer $outer_height Optional outer height of the SlideDeck iframe
     * area
     */
    function get_iframe_url( $id, $width = null, $height = null, $outer_width = null, $outer_height = null, $preview = false ) {
        $uniqueid = uniqid();
        
        if( func_num_args( ) < 5 ) {
            $slidedeck = $this->SlideDeck->get( $id );
            if( empty( $slidedeck ) )
                return '';

            $slidedeck_dimensions = $this->get_dimensions( $slidedeck );
            
            if( !$preview ) $uniqueid = strtotime( $slidedeck['updated_at'] );
        }

        if( !isset( $width ) )
            $width = $slidedeck_dimensions['width'];

        if( !isset( $height ) )
            $height = $slidedeck_dimensions['height'];

        if( !isset( $outer_width ) )
            $outer_width = $slidedeck_dimensions['outer_width'];

        if( !isset( $outer_height ) )
            $outer_height = $slidedeck_dimensions['outer_height'];

        $dimensions = array( 'width' => $width, 'height' => $height, 'outer_width' => $outer_width, 'outer_height' => $outer_height );
        
        /**
         * The problem we were having was that the http_build_query() was encoding the & characters to the HTML entity equivelant and causing RESS and Iframe/preview decks to break in dimensions
         * Afer some reading we discovered that passing in the separator fixed the issue: http://php.net/manual/en/function.http-build-query.php
         */
        $url = admin_url( "admin-ajax.php?action={$this->namespace}_preview_iframe&uniqueid=" . $uniqueid . "&slidedeck={$id}&" . http_build_query( $dimensions, '', '&' ) );
        
        if( $preview ) $url .= "&preview=1";
        
        return $url;
    }

    /**
     * Insert SlideDeck iframe URL
     *
     * @global $post
     *
     * @return string
     */
    function get_insert_iframe_src( ) {
        global $post;

        $url = admin_url( "admin-ajax.php?action={$this->namespace}_insert_iframe&post_id={$post->ID}&TB_iframe=1&width=640&height=515" );

        return $url;
    }

    /**
     * Get Insert SlideDeck iframe table
     *
     * @param string $orderby What to order by
     * (post_date|post_title|slidedeck_source)
     * @param array $selected Optional array of pre-selected SlideDecks
     *
     * @uses SlideDeck::get()
     *
     * @return string
     */
    function get_insert_iframe_table( $orderby, $selected = array() ) {
        // Swap direction when ordering by date so newest is first
        $order = $orderby == "post_modified" ? 'DESC' : 'ASC';
        // Get all SlideDecks
        $slidedecks = $this->SlideDeck->get( null, $orderby, $order, 'publish' );
        // Namespace for use in the view
        $namespace = $this->namespace;

        ob_start( );
        include (SLIDEDECK2_DIRNAME . '/views/elements/_insert-iframe-table.php');
        $html = ob_get_contents( );
        ob_end_clean( );

        return $html;
    }

    /**
     * Get License Key
     *
     * Gets the current stored License Key
     *
     * @return string
     */
    function get_license_key( ) {
        // Is a license key defined as a constant?
        $defined_key = '';
        if( defined( 'SLIDEDECK_LICENSE_KEY' ) )
            $defined_key = SLIDEDECK_LICENSE_KEY;
        
        // Is there a stored key?
        $stored_key = $this->get_option( 'license_key' );
        
        // If the stored key is blank, then use the defined key.
        if( empty( $stored_key ) ){
            return (string) $defined_key;
        }
        
        return (string) $stored_key;
    }
    
    /**
     * Creates a text/HTML message based on the cached expiry date
     * 
     * @return array containing the message type and message text
     */
    static function get_license_key_expiry_message() {
        $text = '';
        $type = 'unspecified';
        $expiry_date = get_option( 'slidedeck2_cached_expiration' );
        
        if( $expiry_date ) {
            if( $expiry_date <= time() ) {
                $type = 'expired';
                $text = 'Your license key seems to be expired. <a href="' . slidedeck2_get_renewal_url() . '">Renew now</a>';
            } elseif( $expiry_date < strtotime( "+30 day" ) ) {
                $type = 'nearing-expiration';
                $text = 'Your license key has fewer than 30 days remaining. <a href="' . slidedeck2_get_renewal_url() . '">Renew now</a>';
            }
        }
        
        return array(
            'type' => $type,
            'text' => $text,
            'date' => $expiry_date
        );
    }

    /**
     * Get available lenses for a SlideDeck
     *
     * Looks up all lenses and returns a filtered array of only those lenses
     * available to this SlideDeck. While the lens get() method is already
     * filtered, there are certain parameters that
     *
     * @param array $slidedeck The SlideDeck object
     *
     * @uses SlideDeckLens::get()
     *
     * @return array
     */
    function get_slidedeck_lenses( $slidedeck ) {
        $lenses = $this->Lens->get( );

        // Loop through sources to see if we have an all video SlideDeck
        $video_sources = array( 'youtube', 'vimeo', 'dailymotion' );
        $all_videos = true;
        foreach( $slidedeck['source'] as $source ) {
            if( !in_array( $source, $video_sources ) ) {
                $all_videos = false;
            }
        }

        $filtered = array( );
        foreach( $lenses as $lens ) {
            // Skip the Twitter lens from use if Twitter is not the only source
            if( count( $slidedeck['source'] ) > 1 && in_array( "twitter", $slidedeck['source'] ) && $lens['slug'] == "twitter" ) {
                continue;
            }

            if( $all_videos == false && $lens['slug'] == "video" ) {
                continue;
            }

            $lens_intersect = array_intersect( $slidedeck['source'], $lens['meta']['sources'] );
            if( !empty( $lens_intersect ) ) {
                $filtered[] = $lens;
            }
        }
        $lenses = $filtered;

        // Re-order things so that Tool-kit is always first
        $toolkit_index = -1;
        for( $i = 0; $i < count( $lenses ); $i++ ) {
            if( $lenses[$i]['slug'] == "tool-kit" ) {
                $toolkit_index = $i;
            }
        }

        if( $toolkit_index != -1 ) {
            $toolkit = $lenses[$toolkit_index];
            array_splice( $lenses, $toolkit_index, 1 );
            array_unshift( $lenses, $toolkit );
        }

        return $lenses;
    }

    /**
     * Retrieve the stored plugin option or the default if no user specified
     * value is defined
     *
     * @param string $option_name The name of the option you wish to retrieve
     *
     * @uses get_option()
     *
     * @return mixed Returns the option value or false(boolean) if the option is
     * not found
     */
    function get_option( $option_name ) {
        // Load option values if they haven't been loaded already
        if( !isset( $this->options ) || empty( $this->options ) ) {
            /**
             * If the SlideDeck 2 global options key doesn't
             * exist, then we should copy the old key over to the new one.
             */
            $slidedeck_global_options = get_option( $this->option_name, false );
            if( $slidedeck_global_options === false ) {
                $old_slidedeck_global_options = get_option( "slidedeck_global_options", false );
                if( empty( $slidedeck_global_options ) && !empty( $old_slidedeck_global_options ) ){
                    // If the new options array is empty, and there's an old one, copy the old ones over.
                    update_option( "slidedeck2_global_options", $old_slidedeck_global_options );
                }
                
                $slidedeck_global_options = get_option( $this->option_name, $this->defaults );
            }
            
            $this->options = $slidedeck_global_options;
        }
        
        if( array_key_exists( $option_name, $this->options ) ) {
            return $this->options[$option_name];
            // Return user's specified option value
        } elseif( array_key_exists( $option_name, $this->defaults ) ) {
            return $this->defaults[$option_name];
            // Return default option value
        }
        return false;
    }

    /**
     * Get the options model for this SlidDeck and lens
     *
     * @param array $slidedeck The SlideDeck object
     */
    function get_options_model( $slidedeck ) {
        $options_model = apply_filters( "{$this->namespace}_options_model", $this->SlideDeck->options_model, $slidedeck );

        return $options_model;
    }
    
    /**
     * Get the shortcode for a SlideDeck
     * 
     * @param int $slidedeck_id The ID of the SlideDeck
     * 
     * @return string
     */
    function get_slidedeck_shortcode( $slidedeck_id ) {
        $shortcode = "[SlideDeck2 id={$slidedeck_id}";
        
        if( $this->get_option( 'iframe_by_default' ) == true ) {
            $shortcode.= " iframe=1";
        }
        
        $shortcode.= "]";
        
        return $shortcode;
    }

    /**
     * Get all SlideDeck sources
     *
     * Returns an array of stock sources and adds a hook for loading additional
     * third-party sources
     *
     * @uses apply_filters()
     *
     * @return array
     */
    function get_sources( $source_slugs = array() ) {
        $sources = (array) apply_filters( "{$this->namespace}_get_sources", $this->sources );

        if( !empty( $source_slugs ) ) {
            if( !is_array( $source_slugs ) ) {
                $source_slugs = array( $source_slugs );
            }

            $filtered_sources = array( );
            foreach( $sources as $source_name => $source_object ) {
                if( in_array( $source_name, $source_slugs ) ) {
                    $filtered_sources[$source_name] = $source_object;
                }
            }
            $sources = $filtered_sources;
        }

        uasort( $sources, array( &$this, '_sort_by_weight' ) );

        return $sources;
    }

    /**
     * Highest Installed Tier
     * 
     * Attempts to find the highest installed tier.
     */
    static function highest_installed_tier() {
        $installed_addons = self::$addons_installed;
        ksort( $installed_addons );
        $tier = end( $installed_addons );
        
        return $tier;
    }
    
    /**
     * Initialization function to hook into the WordPress init action
     *
     * Instantiates the class on a global variable and sets the class, actions
     * etc. up for use.
     */
    static function instance( ) {
        global $SlideDeckPlugin;

        // Only instantiate the Class if it hasn't been already
        if( !isset( $SlideDeckPlugin ) )
            $SlideDeckPlugin = new SlideDeckPlugin( );
    }

    /**
     * Convenience method to determine if we are viewing a SlideDeck plugin page
     *
     * @global $pagenow
     *
     * @return boolean
     */
    function is_plugin( ) {
        global $pagenow;
        
        $is_plugin = false;
        
        if( !function_exists( 'get_current_screen' ) )
            return false;

        $screen_id = get_current_screen( );
        if( empty( $screen_id ) )
            return false;
        
        if( isset( $screen_id->id ) )
            $is_plugin = (boolean) in_array(  $screen_id->id, array_values( $this->menu ) );
        
        return $is_plugin;
    }

    /**
     * License Key Check
     *
     * Checks to see whether or not we need to hook into the admin
     * notices area and let the user know that they have not
     * entered their lciense key.
     *
     * @return boolean
     */
    function license_key_check( ) {
        global $current_user;
        wp_get_current_user( );

        $license_key = $this->get_license_key( );
        if( empty( $license_key ) && !isset( $_POST['submit'] ) ) {
            if( in_array( 'tier_10', SlideDeckPlugin::$addons_installed ) )
                add_action( 'admin_notices', array( &$this, 'license_key_notice' ) );
            
            return false;
        } else {
            $license_key_status = $this->is_license_key_valid( $license_key );
            $addons_need_installing = false;
            
            if( isset( $license_key_status->addons ) ){
                foreach( $license_key_status->addons as $addon_key => $addon_data ) {
                    if( !in_array( $addon_key, self::$addons_installed ) ) {
                        $addons_need_installing = true;
                    }
                }
            }
            
            if( $addons_need_installing ) {
                add_action( 'admin_notices', array( &$this, 'addons_available_message' ) );
            }
        }
        
        return true;
    }

    /**
     * Addons available for installation message
     *
     * Echoes the standard message to prompt a user to install available addons for their license
     * key that they have input.
     */
    function addons_available_message() {
        if( $this->is_plugin() || preg_match( "/^\/wp-admin\/plugins\.php/", $_SERVER['REQUEST_URI'] ) ) {
            $message = "<div id='{$this->namespace}-addon-notice' class='error updated fade'><p><strong>";
            $message .= sprintf( __( 'Addons are available for %s!', $this->namespace ), $this->friendly_name );
            $message .= "</strong> ";
            $message .= sprintf( __( 'There are addons available for your installation of %1$s. %2$sInstall Your Addons%3$s', $this->namespace ), $this->friendly_name, '<a class="button" style="text-decoration:none;color:#333;" href="' . $this->action( '/upgrades&referrer=Addons+Available+Message' ) . '">', '</a>' );
            $message .= "</p></div>";
    
            echo $message;
        }
    }

    /**
     * License Key Notice
     *
     * Echoes the standard message for a license key
     * that has not been entered.
     *
     */
    function license_key_notice( ) {
        $message = "<div id='{$this->namespace}-license-key-warning' class='error fade'><p><strong>";
        $message .= sprintf( __( '%s is not activated yet.', $this->namespace ), $this->friendly_name );
        $message .= "</strong> ";
        $message .= sprintf( __( 'You must %1$senter your license key%2$s to receive automatic updates and support.', $this->namespace ), '<a class="button" style="text-decoration:none;color:#333;" href="' . $this->action( '/options' ) . '">', '</a>' );
        $message .= "</p></div>";

        echo $message;
    }

    /**
     * Hook into load-$page action
     *
     * Implement help tabs for various admin pages related to SlideDeck
     */
    function load_admin_page( ) {
        $screen = get_current_screen( );

        if( !in_array( $screen->id, $this->menu ) ) {
            return false;
        }

        // Page action for sub-section handling
        $action = isset( $_GET['action'] ) ? $_GET['action'] : "";

        switch( $screen->id ) {
            // SlideDeck Manage Page
            case $this->menu['manage']:
                switch( $action ) {
                    case "create":
                    case "edit":
                    break;

                    default:
                        /**
                         * TODO: Add FAQ and Help Tab elements
                         *
                         * $this->add_help_tab( 'whats-new', "What's New?" );
                         * $this->add_help_tab( 'faqs', "FAQs" );
                         */
                    break;
                }

            break;
        }

        do_action( "{$this->namespace}_help_tabs", $screen, $action );
    }

    /**
     * Load Constants
     * 
     * Conveninece function to load the constants files for 
     * the activation and construct
     */
    static function load_constants() {
        if( defined( 'SLIDEDECK2_BASENAME' ) )
            return false;
        
        // SlideDeck Plugin Basename
        define( 'SLIDEDECK2_BASENAME', basename( __FILE__ ) );
        define( 'SLIDEDECK2_HOOK', preg_replace( "/\.php$/", "", SLIDEDECK2_BASENAME ) );
        
        // Include constants file
        require_once (dirname( __FILE__ ) . '/lib/constants.php');
    }

    /**
     * Hook into WordPress media_buttons action
     *
     * Adds Insert SlideDeck button next to Upload/Insert media button on post
     * and page editor pages
     */
    function media_buttons( ) {
        global $post, $wp_version;
        
        if( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post-new.php', 'page-new.php', 'post.php', 'page.php' ) ) ) {
            $img = '<img src="' . esc_url( SLIDEDECK2_URLPATH . '/images/icon-15x15.png?v=' . SLIDEDECK2_VERSION ) . '" width="15" height="15" />';
            
            /**
             * Use the newer button format for versions of WordPress greater than or equal to 3.5
             */
            if ( version_compare( $wp_version, 3.5, '>=' ) ) {
                echo '<a href="' . esc_url( $this->get_insert_iframe_src( ) ) . '" style="padding-left:0.4em;" class="thickbox add_slidedeck button" id="add_slidedeck" title="' . esc_attr__( 'Insert your SlideDeck', $this->namespace ) . '" onclick="return false;"> ' . $img . __( 'Insert SlideDeck', $this->namespace ) . '</a>';
            } else {
                echo '<a href="' . esc_url( $this->get_insert_iframe_src( ) ) . '" class="thickbox add_slidedeck" id="add_slidedeck" title="' . esc_attr__( 'Insert your SlideDeck', $this->namespace ) . '" onclick="return false;"> ' . $img . '</a>';
            }
        }
    }

    /**
     * Check for an override for specific partners
     *
     * @uses SlideDeckPlugin::get_cohort_data()
     * 
     * @return boolean whether or not this cohort_name should override
     */
    static function partner_override() {
        $cohort = self::get_cohort_data();
        $cohort_name = ( isset( $cohort['name'] ) && !empty( $cohort['name'] ) ) ? $cohort['name'] : '' ;

        if( in_array( $cohort_name, self::$overriding_cohorts ) ) {
            return true;
        }

        return false;
    }

    /**
     * Create/Edit SlideDeck Page
     *
     * Expects either a "slidedeck" or "type" URL parameter to be present. If a
     * "slidedeck"
     * URL parameter is found, it will attempt to load the requested ID. If no
     * "slidedeck"
     * URL parameter is found and a "type" parameter is found, a new SLideDeck of
     * that type
     * will be created.
     *
     * @global $current_user
     *
     * @uses get_currentuserinfo()
     * @uses get_post_meta()
     * @uses get_user_meta()
     * @uses slidedeck2_set_flash()
     * @uses wp_redirect()
     * @uses SlideDeckPlugin::action()
     * @uses SlideDeck::get()
     * @uses SlideDeck::create()
     * @uses SlideDeckLens::get()
     * @uses apply_filters()
     */
    function page_create_edit( ) {
        global $current_user;
        get_currentuserinfo( );

        $form_action = "create";
        if( isset( $_REQUEST['slidedeck'] ) ) {
            $form_action = "edit";
        }

        $sources_available = $this->get_sources( );

        // Redirect to the manage page if creating and an invalid source was
        // specified
        if( $form_action == "create" ) {
            $source = $_REQUEST['source'];
            if( !is_array( $source ) )
                $source = array( $source );

            $source_valid_message = "";
            if( !isset( $_REQUEST['source'] ) ) {
                $source_valid_message = "You must specify a valid SlideDeck source";
            }

            $source_intersect = array_intersect( $source, array_keys( $sources_available ) );
            if( empty( $source_intersect ) ) {
                $source_valid_message = "You do not have access to this SlideDeck source, please make sure you have the correct add-ons installed.";
            }

            if( !empty( $source_valid_message ) ) {
                $this->post_header_redirect( $this->action( ), "<strong>ERROR:</strong> " . $source_valid_message );
            }
        }

        if( $form_action == "edit" ) {
            $slidedeck = $this->SlideDeck->get( $_REQUEST['slidedeck'] );

            $source_intersect = array_intersect( $slidedeck['source'], array_keys( $sources_available ) );
            if( empty( $source_intersect ) ) {
                $this->post_header_redirect( $this->action( ), "<strong>ERROR:</strong> " . "You do not have access to this SlideDeck source, please make sure you have the correct add-ons installed." );
            }

            // SlideDeck's saved stage background
            $the_stage_background = get_post_meta( $slidedeck['id'], "{$this->namespace}_stage_background", true );
        } else {
            $slidedeck = $this->SlideDeck->create( "", $source );

            // Default stage background
            $the_stage_background = get_user_meta( $current_user->ID, "{$this->namespace}_default_stage_background", true );
        }

        // Set the default stage background if none has been set yet
        if( empty( $the_stage_background ) ) {
            $the_stage_background = "wood";
        }

        if( !$slidedeck ) {
            slidedeck2_set_flash( "Requested SlideDeck could not be loaded or created", true );
            wp_redirect( $this->action( ) );
            exit ;
        }

        $sizes = apply_filters( "{$this->namespace}_sizes", $this->sizes, $slidedeck );
        $lenses = $this->get_slidedeck_lenses( $slidedeck );

        // Set preview rendering dimensions to chosen size
        $dimensions = $this->get_dimensions( $slidedeck );

        // Iframe URL for preview
        $iframe_url = $this->get_iframe_url( $slidedeck['id'], $dimensions['outer_width'], $dimensions['outer_height'], $dimensions['outer_width'], $dimensions['outer_height'], true );

        $options_model = $this->get_options_model( $slidedeck );

        uksort( $options_model['Appearance']['titleFont']['values'], 'strnatcasecmp' );
        uksort( $options_model['Appearance']['bodyFont']['values'], 'strnatcasecmp' );

        // Trim out the Setup key
        $trimmed_options_model = $options_model;
        unset( $trimmed_options_model['Setup'] );
        $options_groups = $this->options_model_groups;

        $namespace = $this->namespace;

        // Get all available fonts
        $fonts = $this->SlideDeck->get_fonts( $slidedeck );

        // Backgrounds for the editor area
        $stage_backgrounds = $this->stage_backgrounds;

        $form_title = apply_filters( "{$namespace}_form_title", __( ucwords( $form_action ) . " SlideDeck", $this->namespace ), $slidedeck, $form_action );
        
        $has_saved_covers = false;
        if( class_exists( 'SlideDeckCovers' ) )
            $has_saved_covers = $this->Cover->has_saved_covers( $slidedeck['id'] );

        $slidedeck_is_dynamic = $this->slidedeck_is_dynamic( $slidedeck );

        include (SLIDEDECK2_DIRNAME . '/views/form.php');
    }

    /**
     * Manage Existing SlideDecks Page
     *
     * Loads all SlideDecks created by user and new creation options
     *
     * @uses SlideDeck::get()
     */
    function page_manage( ) {
        $order_options = $this->order_options;
        $orderby = get_option( "{$this->namespace}_manage_table_sort", reset( array_keys( $this->order_options ) ) );
        $order = $orderby == 'post_modified' ? 'DESC' : 'ASC';

        // Get a list of all SlideDecks in the system
        $slidedecks = $this->SlideDeck->get( null, $orderby, $order, 'publish' );

        // Available taxonomies for SlideDeck types
        $taxonomies = $this->taxonomies;

        // Get the available sources
        $sources = $this->get_sources( );

        // Initiate pointers on this page
        //$this->Pointers->pointer_lens_management();

        $default_view = get_user_option( "{$this->namespace}_default_manage_view" );
        if( !$default_view )
            $default_view = 'decks';

        $namespace = $this->namespace;

        $sidebar_ad_url = apply_filters( "{$this->namespace}_sidebar_ad_url", "//www.slidedeck.com/wordpress-plugin-iab/" );

        // Render the overview list
        include (SLIDEDECK2_DIRNAME . '/views/manage.php');
    }

    /**
     * The admin section options page rendering method
     *
     * @uses current_user_can()
     * @uses wp_die()
     */
    function page_options( ) {
        if( !current_user_can( 'manage_options' ) )
            wp_die( __( "You do not have privileges to access this page", $this->namespace ) );

        $defaults = array(
            'always_load_assets' => true,
            'disable_wpautop' => false,
            'dont_enqueue_scrollwheel_library' => false,
            'dont_enqueue_easing_library' => false,
            'disable_edit_create' => false,
            'license_key' => "",
            'twitter_user' => "",
            'iframe_by_default' => false,
            'flush_wp_object_cache' => false
        );
        $data = (array) get_option( $this->option_name, $defaults );
        $data = array_merge( $defaults, $data );

        $namespace = $this->namespace;

        /**
         * We handle these separately due to the funky characters.
         * Let's not risk breaking serialization.
         */
        // Get the Instagram Key
        $last_saved_instagram_access_token = get_option( $this->namespace . '_last_saved_instagram_access_token' );
        
        // Get the Google+ API  Key
        $last_saved_gplus_api_key = get_option( $this->namespace . '_last_saved_gplus_api_key' );

        include (SLIDEDECK2_DIRNAME . '/views/admin-options.php');
    }

    /**
     * The admin section upgrades page rendering method
     *
     * @uses current_user_can()
     * @uses wp_die()
     */
    function page_upgrades( ) {
        if( !current_user_can( 'manage_options' ) )
            wp_die( __( "You do not have privileges to access this page", $this->namespace ) );

        $namespace = $this->namespace;
        $plugins = array( );
        $license_key = slidedeck2_get_license_key( );
        
        /**
         * Here let's set the I'm back variable to true. This allows us to
         * know that they user is expecting a dialog showing the big install
         * button.
         * In this case, we don't need to immediately show the page, we can just
         * wait for a load.
         */
        if( isset( $_REQUEST['imback'] ) && $_REQUEST['imback'] === 'true' )
            $this->user_is_back = true;

        if( isset( $_GET['install'] ) && !empty( $_GET['install'] ) ) {
            
            // We're doing a SlideDeck addon install.
            SlideDeckPlugin::$slidedeck_addons_installing = true;
            include( 'lib/slidedeck-plugin-install.php' );
            
            if( isset( $_GET['package'] ) && !empty( $_GET['package'] ) ) {
                foreach( (array) $_GET['package'] as $package ) {
                    /**
                     * Some servers don't allow http or https in a querystring.
                     * Understandable, but since we're logged in for this action, I think 
                     * it's relatively safe. The woraround is to add the protocol here.
                     */
                    if( !preg_match( '/^http|https/', $package ) )
                        $package = 'http://' . $package;
                    
                    $plugins[] = $package;
                }
            }
            
            $ssl = (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on') ? 's' : '';
            $port = ($_SERVER['SERVER_PORT'] != '80') ? ':' . $_SERVER['SERVER_PORT'] : '';
            $url = sprintf( 'http%s://%s%s%s', $ssl, $_SERVER['SERVER_NAME'], $port, $_SERVER['REQUEST_URI'] );

            $type = '';
            $title = '';
            $nonce = '';

            $skin = new SlideDeckPluginInstallSkin( compact( 'type', 'title', 'nonce', 'url' ) );
            $skin->sd_header = isset( $data['body_header'] ) ? $data['body_header'] : '';
            $skin->sd_footer = isset( $data['body_footer'] ) ? $data['body_footer'] : '';

            $Installer = new SlideDeckPluginInstall( $skin );

            $Installer->install( $plugins );

            exit ;
        }
        
        if( isset( $_REQUEST['referrer'] ) && !empty( $_REQUEST['referrer'] ) ) {
            slidedeck2_km( "Visit Addons", array( 'cta' => $_REQUEST['referrer'] ) );
        }
        
        include (SLIDEDECK2_DIRNAME . '/views/admin-upgrades.php');
    }

    /**
     * SlideDeck Lens Add New View
     *
     * Page to upload a new lens to the user's WordPress installation.
     *
     * @uses current_user_can()
     * @uses wp_die()
     */
    function page_lenses_add( ) {
        if( !current_user_can( 'install_themes' ) )
            wp_die( __( "You do not have privileges to access this page", $this->namespace ) );

        $namespace = $this->namespace;

        include (SLIDEDECK2_DIRNAME . '/views/lenses/add.php');
    }
    
    function page_lenses_delete_authorize() {
        if( !wp_verify_nonce( $_REQUEST['_wpnonce'], $this->namespace . '-delete-lens-authorize' ) ) {
            wp_die( __( "Sorry, you do not have permission to access this page", $this->namespace ) );
        }
        
        $redirect = "";
        if( isset( $_REQUEST['redirect'] ) ) {
            $redirect = $_REQUEST['redirect'];
        }
        
        if( isset( $_REQUEST['lens'] ) && !empty( $_REQUEST['lens'] ) ) {
            $this->delete_lens_authorize( $_REQUEST['lens'], $redirect );
            
            $this->post_header_redirect( $this->action( "/lenses" ), "<strong>Lens successfully deleted</strong>" );
            exit;
        }
    }

    /**
     * SlideDeck Lens Management Page
     *
     * Renders the primary lens management page where a user can see their
     * existing lenses, upload
     * new lenses, make copies of lenses, access a lens for editing and delete
     * existing lenses.
     *
     * @uses current_user_can()
     */
    function page_lenses_manage( ) {
        // Die if user cannot manage options
        if( !current_user_can( 'manage_options' ) )
            wp_die( __( "You do not have privileges to access this page", $this->namespace ) );

        $namespace = $this->namespace;

        $sources = $this->get_sources( );

        $lenses = $this->Lens->get( );
        foreach( $lenses as &$lens ) {
            $lens['is_protected'] = $this->Lens->is_protected( $lens['files']['meta'] );
        }

        $is_writable = $this->Lens->is_writable( );
        
        $can_edit_lenses = !in_array( self::highest_installed_tier(), array( 'tier_5', 'tier_10', 'tier_20' ) );
        
        include (SLIDEDECK2_DIRNAME . '/views/lenses/manage.php');
    }

    /**
     * SlideDeck Lenses Page Router
     *
     * Routes admin page requests to the appropriate SlideDeck Lens page for
     * managing, editing
     * and uploading new lenses.
     */
    function page_lenses_route( ) {
        $action = array_key_exists( 'action', $_REQUEST ) ? $_REQUEST['action'] : "";

        // Define action as manage when accessing the manage page since the URL
        // does not contain an action query parameter
        if( empty( $action ) )
            $action = "manage";

        switch( $action ) {
            case "manage":
                $this->page_lenses_manage( );
            break;

            case "add":
                $this->page_lenses_add( );
            break;
            
            case "delete_authorize":
                $this->page_lenses_delete_authorize();
            break;

            default:
                do_action( "{$this->namespace}_page_lenses_route", $action );
            break;
        }

    }

    /**
     * SlideDecks Page Router
     *
     * Based off the action requested the page will either display the manage
     * view for managing
     * existing SlideDecks (default) or the editing/creation view for a
     * SlideDeck.
     *
     * @uses SlideDeckPlugin::page_manage()
     * @uses SlideDeckPlugin::page_create_edit()
     */
    function page_route( ) {
        $action = array_key_exists( 'action', $_REQUEST ) ? $_REQUEST['action'] : "";

        switch( $action ) {
            // Create a new SlideDeck
            case "create":
                $this->page_create_edit( );
            break;

            // Edit existing SlideDecks
            case "edit":
                $this->page_create_edit( );
            break;

            // Manage existing SlideDecks
            default:
                $this->page_manage( );
            break;
        }
    }

    /**
     * Hook into plugin_action_links filter
     *
     * Adds a "Settings" link next to the "Deactivate" link in the plugin listing
     * page
     * when the plugin is active.
     *
     * @param object $links An array of the links to show, this will be the
     * modified variable
     * @param string $file The name of the file being processed in the filter
     */
    function plugin_action_links( $links, $file ) {
        $new_links = array( );

        if( $file == plugin_basename( SLIDEDECK2_DIRNAME . '/' . SLIDEDECK2_BASENAME ) ) {
            $new_links[] = '<a href="admin.php?page=' . SLIDEDECK2_BASENAME . '">' . __( 'Create New SlideDeck' ) . '</a>';
        }

        return array_merge( $new_links, $links );
    }

    /**
     * Post Header Redirect
     *
     * Outputs a JavaScript redirect directive to process redirects and set an
     * optional
     * message after headers have already been sent.
     *
     * @param string $location The destination
     * @param string $message Optional message to set
     */
    function post_header_redirect( $location, $message = "" ) {
        $url = admin_url( 'admin-ajax.php' ) . '?action=' . $this->namespace . '_post_header_redirect&_wpnonce=' . wp_create_nonce( "{$this->namespace}-post-header-redirect" );
        $url .= "&location=" . urlencode( $location );
        if( !empty( $message ) ) {
            $url .= "&message=" . urlencode( $message );
        }

        echo '<script type="text/javascript">document.location.href = "' . $url . '";</script>';
        exit ;
    }

    /**
     * Truncate the title string
     *
     * Truncate a title string for better visual display in Smart SlideDecks.This
     * function is multibyte aware so it should handle UTF-8 strings correctly.
     *
     * @param $text str The text to truncate
     * @param $length int (100) The length in characters to truncate to
     * @param $ending str The ending to tack onto the end of the truncated title
     * (if the title was truncated)
     */
    function prepare_title( $text, $length = 100, $ending = '&hellip;' ) {
        $truncated = mb_substr( strip_tags( $text ), 0, $length, 'UTF-8' );

        $original_length = function_exists( 'mb_strlen' ) ? mb_strlen( $text, 'UTF-8' ) : strlen( $text );

        if( $original_length > $length ) {
            $truncated .= $ending;
        }

        return $truncated;
    }

    /**
     * Used for printing out the JavaScript commands to load SlideDecks and
     * appropriately
     * read the DOM for positioning, sizing, dimensions, etc.
     *
     * @return Echo out the JavaScript tags generated by
     * slidedeck_process_template;
     */
    function print_footer_scripts( ) {
        // If the page has at least one RESS deck, we MUST load the public JS.
        if( $this->page_has_ress_deck ){
            // Append a footer script for each deck
            $ress_code = '<script type="text/javascript">' . "\n";
            $ress_code .= file_get_contents( SLIDEDECK2_DIRNAME . "/js/{$this->namespace}-ress" . (SLIDEDECK2_ENVIRONMENT == 'development' ? '.dev' : '') . ".js" ) . "\n";
            $ress_code .= '</script>' . "\n";
            
            $this->footer_scripts = $ress_code . $this->footer_scripts;
        }
        
        echo $this->footer_scripts;
        
        // Output IE Conditional CSS if present
        foreach( (array) $this->lenses_included as $lens_slug => $val ) {
            $lens = $this->Lens->get( $lens_slug );
            echo $this->Lens->get_css( $lens );
        }
        
        if( !empty( $this->footer_styles ) ) {
            echo '<style type="text/css" id="' . $this->namespace . '-footer-styles">' . $this->footer_styles . '</style>';
        }

        do_action( "{$this->namespace}_print_footer_scripts" );
    }

    /**
     * Print JavaScript Constants
     *
     * prints some JavaScript constants that are used for
     * covers and other UI elements.
     */
    function print_javascript_constants( ) {
        if( !isset( $this->constants_printed ) ) {
            echo '<script type="text/javascript">' . "\n";
            echo 'var slideDeck2URLPath = "' . SLIDEDECK2_URLPATH . '";' . "\n";
            echo 'var slideDeck2AddonsURL = "' . slidedeck2_action( "/upgrades" ) . '";' . "\n";
            echo 'var slideDeck2iframeByDefault = ' . var_export( $this->get_option( 'iframe_by_default' ), true ) . ';' . "\n";
            echo '</script>' . "\n";
            
            $this->constants_printed = true;
        }
    }

    /**
     * Print JavaScript Constants
     *
     * prints some JavaScript constants that are used for
     * covers and other UI elements.
     */
    function print_header_javascript_constants( ) {
        if( !isset( $this->header_constants_printed ) ) {
            echo '<script type="text/javascript">' . "\n";
            echo 'window.slideDeck2Version = "' . SLIDEDECK2_VERSION . '";' . "\n";
            echo 'window.slideDeck2Distribution = "' . strtolower( SLIDEDECK2_LICENSE ) . '";' . "\n";
            echo '</script>' . "\n";
            
            $this->header_constants_printed = true;
        }
    }

    /**
     * Run the the_content filters on the passed in text
     *
     * @param object $content The content to process
     * @param object $editing Process for editing or for viewing (viewing is
     * default)
     *
     * @uses do_shortcode()
     * @uses get_user_option()
     * @uses SlideDeckPlugin::get_option()
     * @uses wpautop()
     *
     * @return object $content The formatted content
     */
    function process_slide_content( $content, $editing = false ) {
        $content = stripslashes( $content );

        if( $editing === false ) {
            $content = do_shortcode( $content );
        }

        if( 'true' == get_user_option( 'rich_editing' ) || ($editing === false) ) {
            if( $this->get_option( 'disable_wpautop' ) != true ) {
                $content = wpautop( $content );
            }
        }

        $content = str_replace( ']]>', ']]&gt;', $content );

        return $content;
    }

    /**
     * Add the SlideDeck button to the TinyMCE interface
     *
     * @param object $buttons An array of buttons for the TinyMCE interface
     *
     * @return object $buttons The modified array of TinyMCE buttons
     */
    function register_button( $buttons ) {
        array_push( $buttons, "separator", "slidedeck" );
        return $buttons;
    }

    /**
     * Register post types used by SlideDeck
     *
     * @uses register_post_type
     */
    function register_post_types( ) {
        register_post_type( 'slidedeck2', array( 'labels' => array( 'name' => 'slidedeck2', 'singular_name' => __( 'SlideDeck 2', $this->namespace ) ), 'public' => false ) );
    }

    /**
     * Route the user based off of environment conditions
     *
     * This function will handling routing of form submissions to the appropriate
     * form processor.
     *
     * @uses wp_verify_nonce()
     * @uses SlideDeckPlugin::admin_options_update()
     * @uses SlideDeckPlugin::save()
     * @uses SlideDeckPlugin::ajax_delete()
     */
    function route( ) {
        $uri = $_SERVER['REQUEST_URI'];
        $protocol = isset( $_SERVER['HTTPS'] ) ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $url = "{$protocol}://{$hostname}{$uri}";
        $is_post = (bool)(strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST");
        $nonce = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : false;

        // Check if a nonce was passed in the request
        if( $nonce ) {
            // Handle POST requests
            if( $is_post ) {
                if( wp_verify_nonce( $nonce, "{$this->namespace}-update-options" ) ) {
                    $this->admin_options_update( );
                }

                if( wp_verify_nonce( $nonce, "{$this->namespace}-create-slidedeck" ) || wp_verify_nonce( $nonce, "{$this->namespace}-edit-slidedeck" ) ) {
                    $this->save( );
                }

                if( wp_verify_nonce( $nonce, "{$this->namespace}-delete-slidedeck" ) ) {
                    $this->ajax_delete( );
                }

                if( wp_verify_nonce( $nonce, "{$this->namespace}-duplicate-slidedeck" ) ) {
                    $this->ajax_duplicate( );
                }

                if( wp_verify_nonce( $nonce, "{$this->namespace}-save-lens" ) ) {
                    $this->save_lens( );
                }

                if( wp_verify_nonce( $nonce, "{$this->namespace}-copy-lens" ) ) {
                    $this->copy_lens( );
                }

                if( wp_verify_nonce( $nonce, "{$this->namespace}-delete-lens" ) ) {
                    $this->ajax_delete_lens( );
                }

                if( wp_verify_nonce( $nonce, "{$this->namespace}-cover-update" ) ) {
                    $this->update_cover( );
                }
                
                if( wp_verify_nonce( $nonce, "{$this->namespace}-stats-optin" ) ) {
                    $this->update_stats_optin();
                }
            }
            // Handle GET requests
            else {

            }
        }

        if( $this->is_plugin( ) && isset( $_GET['msg_deleted'] ) )
            slidedeck2_set_flash( __( "SlideDeck successfully deleted!", $this->namespace ) );

        if( preg_match( "/admin\.php\?.*page\=" . SLIDEDECK2_BASENAME . "\/support/", $uri ) ) {
            wp_redirect( "https://dtelepathy.zendesk.com/requests/new" );
            exit ;
        }

        if( preg_match( "/admin\.php\?.*page\=" . SLIDEDECK2_BASENAME . "\/need-support/", $uri ) ) {
            wp_redirect( $this->action('/upgrades&referrer=Get+Support+Menu+Navigation') );
            exit ;
        }

        do_action( "{$this->namespace}_route", $uri, $protocol, $hostname, $url, $is_post, $nonce );
    }

    /**
     * Save a SlideDeck
     */
    function save( ) {
        if( !isset( $_POST['id'] ) ) {
            return false;
        }

        $slidedeck_id = intval( $_POST['id'] );

        $slidedeck = $this->SlideDeck->save( $slidedeck_id, $_POST );

        $action = '&action=edit&slidedeck=' . $slidedeck_id;

        if( $_POST['action'] == "create" ) {
            $action .= '&firstsave=1';
            slidedeck2_km( "New SlideDeck Created", array( 'source' => $slidedeck['source'], 'lens' => $slidedeck['lens'] ) );
        }

        wp_redirect( $this->action( $action ) );
        exit ;
    }

    /**
     * Process saving of SlideDeck custom meta information for posts and pages
     *
     * @uses wp_verify_nonce()
     * @uses update_post_meta()
     * @uses delete_post_meta()
     */
    function save_post( ) {
        if( isset( $_POST['slidedeck-for-wordpress-dynamic-meta_wpnonce'] ) && !empty( $_POST['slidedeck-for-wordpress-dynamic-meta_wpnonce'] ) ) {
            if( !wp_verify_nonce( $_POST['slidedeck-for-wordpress-dynamic-meta_wpnonce'], 'slidedeck-for-wordpress' ) ) {
                return false;
            }

            $slidedeck_post_meta = array( '_slidedeck_slide_title', '_slidedeck_post_featured' );

            foreach( $slidedeck_post_meta as $meta_key ) {
                if( isset( $_POST[$meta_key] ) && !empty( $_POST[$meta_key] ) ) {
                    update_post_meta( $_POST['ID'], $meta_key, $_POST[$meta_key] );
                } else {
                    delete_post_meta( $_POST['ID'], $meta_key );
                }
            }
        }
    }

    /**
     * Lens Edit Form Submission
     *
     * @uses slidedeck2_sanitize()
     * @uses SlideDeckLens::save()
     */
    function save_lens( ) {
        $lens = $this->Lens->get( slidedeck2_sanitize( $_POST['lens'] ) );
        $lens_filename = dirname( $lens['files']['meta'] ) . "/" . slidedeck2_sanitize( $_POST['filename'] );

        if( $this->Lens->is_protected( $lens_filename ) )
            wp_die( '<h3>' . __( "Cannot Update Protected File", $this->namespace ) . '</h3><p>' . __( "The file you tried to write to is a protected file and cannot be overwritten.", $this->namespace ) . '</p><p><a href="' . $this->action( '/lenses' ) . '">' . __( "Return to Lens Manager", $this->namespace ) . '</a></p>' );

        // Lens CSS Content
        $lens_content = $_POST['lens_content'];

        $lens_meta = slidedeck2_sanitize( $_POST['data'] );

        // Save JSON meta if it was submitted
        if( !empty( $lens_meta ) ) {
            $lens_meta['contributors'] = array_map( 'trim', explode( ",", $lens_meta['contributors'] ) );

            $variations = array_map( 'trim', explode( ",", $lens_meta['variations'] ) );
            $lens_meta['variations'] = array( );
            foreach( $variations as $variation ) {
                $lens_meta['variations'][strtolower( $variation )] = ucwords( $variation );
            }

            $this->Lens->save( $lens['files']['meta'], "", $lens['slug'], $lens_meta );
        }

        // Save the lens file
        $lens = $this->Lens->save( $lens_filename, $lens_content, $lens['slug'] );

        // Mark response as an error or not
        $error = (boolean)($lens === false);

        // Set response message default
        $message = "<strong>" . esc_html( __( "Update Successful!", $this->namespace ) ) . "</strong>";
        if( $error )
            $message = "<strong>ERROR:</strong> " . esc_html( __( "Could not write the lens.css file for this lens. Please check file write permissions.", $this->namespace ) );

        slidedeck2_set_flash( $message, $error );

        wp_redirect( $this->action( '/lenses&action=edit&slidedeck-lens=' . $lens['slug'] . "&filename=" . basename( $lens_filename ) ) );
        exit ;
    }

    /**
     * Sets up the user's cohort data
     * 
     * @uses get_option()
     * @uses add_option()
     */
    static function set_cohort_data() {
        $data = array(
            'name' => self::$cohort_name,
            'variation' => self::$cohort_variation,
            'year' => date("Y"),
            'month' => date("m")
        );
        
        // Only set the cohort if it does not exist. 
        if( get_option( self::$namespace . '_cohort', false ) === false ) {
            add_option( self::$namespace . '_cohort', $data );
        }
    }
    
    /**
     * Sets up the user's cohort data
     * 
     * @uses get_option()
     * @uses add_option()
     */
    static function get_cohort_data() {
        return get_option( self::$namespace . '_cohort', false );
    }

    /**
     * Outputs the cohort info as a query string
     * 
     * @param $starting_character
     * 
     * @uses self::get_cohort_data()
     */
    static function get_cohort_query_string( $starting_character = '?' ) {
        $cohorts = self::get_cohort_data();
        $processed = array();
        if( !empty($cohorts ) ) {
            foreach( $cohorts as $key => $value ){
                if( !empty( $value ) ){
                    $processed['cohort_' . $key] = $value;
                }
            }
            return $starting_character . http_build_query( $processed );
        }
    }

    /**
     * Process the SlideDeck shortcode
     *
     * @param object $atts Attributes of the shortcode
     *
     * @uses shortcode_atts()
     * @uses slidedeck_process_template()
     *
     * @return object The processed shortcode
     */
    function shortcode( $atts ) {
        global $post;
        $default_deck_link_text = '';
        
        if( isset( $atts['id'] ) && !empty( $atts['id'] ) )
            $default_deck_link_text = get_the_title( $atts['id'] ) . ' <small>[' . __( "see the SlideDeck", $this->namespace ) . ']</small>';
        
        // Filter the shortcode attributes
        $atts = apply_filters( "{$this->namespace}_shortcode_atts", $atts );
        
        extract( shortcode_atts( array(
            'id' => (boolean) false,
            'width' => null,
            'height' => null,
            'include_lens_files' => (boolean) true,
            'iframe' => (boolean) false,
            'ress' => (boolean) false,
            'proportional' => (boolean) true,
            'feed_link_text' => $default_deck_link_text,
            'nocovers' => (boolean) false,
            'preview' => (boolean) false,
            'echo_js' => (boolean) false,
            'start' => false
        ), $atts ) );
        
        // Make sure that the RESS flag is set so we load the necessary assets in the footer
        if( $ress == true ) {
            $this->page_has_ress_deck = true;
        }
        
        if( $id !== false ) {
            // If this is a feed, just render a link
            if( $this->is_feed() )
                return '<div class="slidedeck-link"><a href="' . get_permalink( $post->ID ) . '#SlideDeck-' . $id . '">' . $feed_link_text . '</a></div>';
        
            if( ( $iframe !== false ) || ( $ress !== false ) ) {
                return $this->_render_iframe( $id, $width, $height, $nocovers, $ress, $proportional );
            } else {
                return $this->SlideDeck->render( $id, array( 'width' => $width, 'height' => $height ), $include_lens_files, $preview, $echo_js, $start );
            }
        } else {
            return "";
        }
    }
    
    /**
     * Is Feed?
     * 
     * An extension of the is_feed() function.
     * We first check WWordPress' built in method and if it passes,
     * then we say yes this is a feed. If it fails, we try to detect FeedBurner
     * 
     * @return boolean
     */
    function is_feed(){
        if( is_feed() ){
            return true;
        }elseif( preg_match( '/feedburner/', strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) ){
            return true;
        }
        return false;
    }
    
    /**
     * Hook into slidedeck_create_custom_slidedeck_block filter
     *
     * Outputs the create custom slidedeck block on the manage page. By default,
     * the user
     * must have the Professional version of SlideDeck 2 installed to access
     * custom SlideDecks
     * so this will output a block with a link the upgrades page by default. The
     * Professional plugin will hook into this as well and output a block that
     * actually links
     * to the Custom SlideDeck type that it adds.
     *
     * @param string $html The HTML to be output
     *
     * @return string
     */
    function slidedeck_create_custom_slidedeck_block( $html ) {
        ob_start( );
        include (SLIDEDECK2_DIRNAME . '/views/elements/_create-custom-slidedeck-block.php');
        $html = ob_get_contents( );
        ob_end_clean( );

        return $html;
    }
    
    /**
     * Hook into slidedeck_create_dynamic_slidedeck_block filter
     *
     * Outputs the create dynamic slidedeck block on the manage page.
     *
     * @param string $html The HTML to be output
     *
     * @return string
     */
    function slidedeck_create_dynamic_slidedeck_block( $html ) {
        ob_start( );
        include (SLIDEDECK2_DIRNAME . '/views/elements/_create-dynamic-slidedeck-block.php');
        $html = ob_get_contents( );
        ob_end_clean( );

        return $html;
    }

    /**
     * Hook into slidedeck_content_control action
     *
     * Outputs the appropriate editor interface for either custom or dynamic
     * SlideDecks
     *
     * @param array $slidedeck The SlideDeck object
     *
     * @uses SlideDeck::is_dynamic()
     */
    function slidedeck_content_control( $slidedeck ) {
        if( $this->slidedeck_is_dynamic( $slidedeck ) ) {
            $namespace = $this->namespace;

            $sources = $this->get_sources( $slidedeck['source'] );
            if( isset( $sources['custom'] ) )
                unset( $sources['custom'] );

            $slidedeck_id = $slidedeck['id'];

            include (SLIDEDECK2_DIRNAME . '/views/elements/_sources.php');
        }
    }

    function slidedeck_form_content_source( $slidedeck, $source ) {
        global $wp_scripts, $wp_styles;

        $loaded_sources = array_unique( $this->loadedSources );

        if( !is_array( $source ) ) {
            if( !in_array( $source, $loaded_sources ) ) {
                if( isset( $wp_scripts->registered["slidedeck-deck-{$source}-admin"] ) ) {
                    $src = $wp_scripts->registered["slidedeck-deck-{$source}-admin"]->src;
                    echo '<script type="text/javascript" src="' . $src . (strpos( $src, "?" ) !== false ? "&" : "?") . "v=" . $wp_scripts->registered["slidedeck-deck-{$source}-admin"]->ver . '"></script>';
                }
                $href = $wp_styles->registered["slidedeck-deck-{$source}-admin"]->src;
                echo '<link rel="stylesheet" type="text/css" href="' . $href . (strpos( $href, "?" ) !== false ? "&" : "?") . "v=" . $wp_styles->registered["slidedeck-deck-{$source}-admin"]->ver . '" />';
            }
        }
    }
    
    /**
     * Hook into slidedeck_get_slides filter
     * 
     * Modify slide array to add classes based on certain settings
     * 
     * @param array $slides Array of slides to render
     * @param array $slidedeck The SlideDeck object
     * 
     * @return array
     */
    function slidedeck_get_slides( $slides, $slidedeck ) {
        if( $this->slidedeck_is_dynamic( $slidedeck ) ) {
            if( isset( $slidedeck['options']['image_scaling'] ) ) {
                foreach( $slides as &$slide ) {
                    if( isset( $slide['vertical_slides'] ) ) {
                        foreach( $slide['vertical_slides'] as &$vertical_slide ) {
                            $vertical_slide['classes'][] = $this->SlideDeck->prefix . "image-scaling-" . $slidedeck['options']['image_scaling'];
                        }
                    } else {
                        $slide['classes'][] = $this->SlideDeck->prefix . "image-scaling-" . $slidedeck['options']['image_scaling'];
                    }
                }
            }
        }
        
        return $slides;
    }

    /**
     * Check if a SlideDeck is dynamic
     *
     * @param array $slidedeck The SlideDeck object
     *
     * @uses apply_filters()
     *
     * @return boolean
     */
    function slidedeck_is_dynamic( $slidedeck ) {
        $dynamic = (bool) apply_filters( "{$this->namespace}_is_dynamic", !in_array( "custom", $slidedeck['source'] ), $slidedeck );

        return $dynamic;
    }
    
    /**
     * After Lenses Hook.
     * 
     * Outputs additional information about the lenses on the lens list view
     * on the SlideDeck options pane, when editing a deck.
     */
    function slidedeck_lens_selection_after_lenses( $lenses, $slidedeck ) {
        $tier = self::highest_installed_tier();
        $lens_slugs = $this->installed_lenses;
        
        include( SLIDEDECK2_DIRNAME . '/views/upsells/_upsell-additional-lenses.php' );
    }
    
    /**
     * Outputs additional information about the lenses on the lens management page
     */
    function slidedeck_manage_lenses_after_lenses( $lenses ) {
        
        // Creating an array of slugs only for easier digestion on the lens selection screen
        $lens_slugs = array();
        foreach( $lenses as $lens ) {
            array_push( $lens_slugs, $lens['slug']);
        }
        
        include( SLIDEDECK2_DIRNAME . '/views/upsells/_upsell-additional-lens-manage.php' );
    }

    /**
     * Sort all options by weight
     *
     * @param array $options_model The Options Model Array
     * @param array $slidedeck The SlideDeck object
     *
     * @return array
     */
    function slidedeck_options_model( $options_model, $slidedeck ) {
        // Sorted options model to return
        $sorted_options_model = array( );

        foreach( $options_model as $options_group => $options ) {
            $sorted_options_model[$options_group] = array( );

            $sorted_options_group = $options;
            uasort( $sorted_options_group, array( &$this, '_sort_by_weight' ) );

            $sorted_options_model[$options_group] = $sorted_options_group;
        }

        return $sorted_options_model;
    }

    /**
     * Save SlideDeck Cover data
     *
     * @uses slidedeck2_sanitize()
     * @uses SlideDeckCovers::save()
     */
    function update_cover( ) {
        $data = slidedeck2_sanitize( $_REQUEST );

        $this->Cover->save( $data['slidedeck'], $data );

        die( "Saved!" );
    }
    
    /**
     * AJAX submission for updating the stats optin from the modal form
     */
    function update_stats_optin() {
        $data = slidedeck2_sanitize( $_REQUEST['data'] );
        
        $options = get_option( $this->option_name, false );
        $options['anonymous_stats_optin'] = isset( $data['anonymous_stats_optin'] ) && !empty( $data['anonymous_stats_optin'] ) ? true : false;
        $options['anonymous_stats_has_opted'] = true;
        
        update_option( $this->option_name, $options );
        
        if( $options['anonymous_stats_optin'] == true || self::partner_override() ) {
            slidedeck2_km( "SlideDeck Installed", array( 'license' => self::$license, 'version' => self::$version ), self::partner_override() );
        }
    }

    /**
     * Upload lens request submission
     *
     * Adaptation of WordPress core theme upload and install routines for
     * uploading and
     * installing lenses via a ZIP file upload.
     *
     * @uses wp_verify_nonce()
     * @uses wp_die()
     * @uses wp_enqueue_style()
     * @uses add_query_tag()
     * @uses slidedeck2_action()
     * @uses SlideDeckLens::copy_inc()
     * @uses File_Upload_Upgrader
     * @uses SlideDeck_Lens_Installer_Skin
     * @uses SlideDeck_Lens_Upload
     * @uses SlideDeck_Lens_Upload::install()
     * @uses is_wp_error()
     * @uses File_Upload_Upgrader::cleanup()
     */
    function upload_lens( ) {
        if( !current_user_can( 'install_themes' ) )
            wp_die( __( 'You do not have sufficient permissions to install SlideDeck lenses on this site.', $this->namespace ) );

        check_admin_referer( "{$this->namespace}-upload-lens" );

        // Load the SlideDeck Lens Upload Classes
        if( !class_exists( 'SlideDeckLensUpload' ) )
            include (SLIDEDECK2_DIRNAME . '/classes/slidedeck-lens-upload.php');

        $file_upload = new File_Upload_Upgrader( 'slidedecklenszip', 'package' );

        $title = __( "Upload SlideDeck Lens", $this->namespace );
        $parent_file = "";
        $submenu_file = "";
        wp_enqueue_style( "{$this->namespace}-admin" );
        require_once (ABSPATH . 'wp-admin/admin-header.php');

        $title = sprintf( __( "Installing SlideDeck Lens from uploaded file: %s", 'slidedeck' ), basename( $file_upload->filename ) );
        $nonce = "{$this->namespace}-upload-lens";
        $url = add_query_arg( array( 'package' => $file_upload->id ), 'update.php?action=upload-slidedeck-lens' );
        $type = 'upload';
        
        $lens_dirname = preg_replace( "/\.([a-zA-Z0-9]+)$/", "", basename( $file_upload->filename ) );

        $upgrader = new SlideDeck_Lens_Upload( new SlideDeck_Lens_Installer_Skin( compact( 'type', 'title', 'lens_dirname', 'nonce', 'url' ) ) );
        $result = $upgrader->install( $file_upload->package );

        if( $result || is_wp_error( $result ) )
            $file_upload->cleanup( );
        
        include (ABSPATH . 'wp-admin/admin-footer.php');
    }

    /**
     * Upgrade Button
     * 
     * Outputs the green upgrade button that displays contextually.
     * 
     * @param string $message_text The button area subtitle
     * 
     * @param string $button_text The main button text CTA
     *      */
    function upgrade_button( $context = 'manage' ) {
        $tier = self::highest_installed_tier();
        
        // Here's the defaults
        $defaults = array(
            'context' => 'manage',
            'cta_text' => 'Upgrade',
            'message_text' => 'Get more from SlideDeck',
            'cta_url' => slidedeck2_action( "/upgrades" )
        );
        
        $array_data = array(
            'tier_5' => array(
                'manage' => array(
                    'message_text' => 'Get more lenses!',
                ),
                'edit' => array(
                    'message_text' => 'Get more sources!',
                ),
                'lenses' => array(
                    'message_text' => 'Copy and edit lenses!',
                )
            ),
            'tier_10' => array(
                'manage' => array(
                    'message_text' => 'Get more custom slides',
                ),
                'edit' => array(
                    'message_text' => 'Get the Classic lens',
                ),
                'lenses' => array(
                    'message_text' => 'Copy and edit lenses!',
                ),
            ),
            'tier_20' => array(
                'manage' => array(
                    'message_text' => 'Get more custom slides',
                ),
                'edit' => array(
                    'message_text' => 'Use SlideDeck on more sites',
                ),
                'lenses' => array(
                    'message_text' => 'Copy and edit lenses!',
                )
            )
        );
        
        // Fetch the data (using cache!)
        $url = SLIDEDECK2_UPDATE_SITE . '/upgrade-buttons.json?v=' . SLIDEDECK2_VERSION;
        $response = slidedeck2_cache_read( $url );
        
        if( !$response ) {
            $response = wp_remote_get( $url, array( 'sslverify' => false ) );
            if( !is_wp_error( $response ) ) {
                slidedeck2_cache_write( $url, $response, ( 60 * 60 * 24 ) );
                // Decode the data
                $array_data = json_decode( $response['body'], true );
            }else{
                slidedeck2_cache_write( $url, '', ( 60 * 60 * 24 ) );
            }
        }
        
        /**
         * Use the default values if all else fails.
         * If the found data isn't empty, replace the defaults.
         */
        $values = $defaults;
        if( isset( $array_data[$tier][$context] ) && !empty( $array_data[$tier][$context] ) ){
            $values = array_merge( $defaults, $array_data[$tier][$context] );
            $values['tier'] = $tier;
            $values['context'] = $context;
        }
        
        // Overrides for license renewal
        $renewal_message = self::get_license_key_expiry_message();
        $license_key = slidedeck2_get_license_key();

        // If this is the developer tier, and there's no expiry message...
        if( ( $tier == 'tier_30' ) && ( $renewal_message['type'] == 'unspecified' ) )
            return ''; // don't return a button in this case

        switch( $renewal_message['type'] ) {
            case 'nearing-expiration':
                $values['cta_text'] = "Renew Now";
                $values['cta_url'] = slidedeck2_get_renewal_url() . '&when=warning';
                $values['message_text'] = "<strong>License expiring soon.</strong> Upgrade to continue getting updates and support.";
                if( $context == 'manage' ) {
                    $values['message_text'] = "<strong>License expiring soon.</strong> Upgrade to<br>continue getting updates and support.";
                }
            break;
            case 'expired':
                $values['cta_text'] = "Expired - Renew Now";
                $values['cta_url'] = slidedeck2_get_renewal_url() . '&when=expired';
                $values['message_text'] = "Your license is <strong>expired</strong>. Renew to resume updates and support";
                if( $context == 'manage' ) {
                    $values['message_text'] = "Your license is <strong>expired</strong>. Renew to<br>resume updates and support";
                }
            break;
        }
        
        // Render the button!
        ob_start( );
        include (SLIDEDECK2_DIRNAME . '/views/elements/_upgrade_button.php');
        $html = ob_get_contents( );
        ob_end_clean( );

        return $html;
    }

    /**
     * Hook into upgrader_post_install
     * 
     * Do actions or modify return after upgrader has run for lens installation
     * 
     * @param mixed $res boolean(true) if the upload is deemed successful or WP_Error object if failed
     * @param array $hook_extra Extra values passed to the upload routine
     * @param array $result The results of the upload
     * 
     * @return mixed
     */
    function upgrader_post_install( $res, $hook_extra, $result ) {
        // Make sure this is a lens upload
        if( !is_wp_error( $res ) && isset( $hook_extra['lens_dirname'] ) ) {
            $lens_meta = $this->Lens->get_meta( $result['destination'] . "lens.json" );
            slidedeck2_km( "SlideDeck Installed Lens", array( 'name' => $lens_meta['meta']['name'], 'slug' => $lens_meta['slug'] ) );
        }

        return $res;
    }

    /**
     * Hook into wp_fullscreen_buttons filter
     *
     * Adds insert SlideDeck button to fullscreen TinyMCE editor
     *
     * @param array $buttons Array of buttons to render
     *
     * @return array
     */
    function wp_fullscreen_buttons( $buttons ) {
        $buttons[] = 'separator';

        $buttons['slidedeck'] = array( 'title' => __( "Insert SlideDeck", $this->namespace ), 'onclick' => "tinyMCE.execCommand('mceSlideDeck2');", 'both' => false );

        return $buttons;
    }

    /**
     * Determine which SlideDecks are being loaded on this page
     *
     * @uses SlideDeck::get()
     */
    function wp_hook( ) {
        global $posts;
        
        if( isset( $posts ) && !empty( $posts ) ) {
            $this->slidedeck_ids = array( );
            
            // SlideDecks being loaded with iframe=1
            $iframe_slidedecks = array();
            
            // Process through $posts for the existence of SlideDecks
            foreach( (array) $posts as $post ) {
                $matches = array( );
                preg_match_all( '/\[SlideDeck2( ([a-zA-Z0-9]+)\=\'?([a-zA-Z0-9\%\-_\.]+)\'?)*\]/', $post->post_content, $matches );
                if( !empty( $matches[0] ) ) {
                    foreach( $matches[0] as $match ) {
                        $str = $match;
                        $str_pieces = explode( " ", $str );
                        foreach( $str_pieces as $piece ) {
                            $attrs = explode( "=", $piece );
                            if( $attrs[0] == "id" ) {
                                // Add the ID of this SlideDeck to the ID array
                                // for loading
                                $this->slidedeck_ids[] = intval( str_replace( "'", '', $attrs[1] ) );
                                
                                // Check for iframe or ress = 1, yes, true
                                if( preg_match( "/(iframe|ress)=('|\")?(1|yes|true)('|\")?/", $str, $matches ) ) {
                                    $iframe_slidedecks[] = $attrs[1];

                                    // If at least one deck has RESS, we must force the public JavaScript to load.
                                    if( $matches[1] == 'ress' ) {
                                        // If we process at least one RESS deck, set this to true.
                                        $this->page_has_ress_deck = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            if( !empty( $this->slidedeck_ids ) ) {
                // Check if there are actually SlideDecks that need even need their assets loaded
                if( count( $this->slidedeck_ids ) > count( $iframe_slidedecks ) ) {
                    // If there are more regular SlideDecks than iFrame SlideDecks, load the assets.
                    $this->load_assets = true;
                } else {
                    // If there are the same amount of regular and iFrame SlideDecks, don't load the assets.
                    $this->load_assets = false;
                    // Also, make a note of that...
                    $this->only_has_iframe_decks = true;
                }
                
                // Load SlideDecks used on this URL passing the array of IDs
                $slidedecks = $this->SlideDeck->get( $this->slidedeck_ids );
                
                // Loop through SlideDecks used on this page and add their lenses
                // to the $lenses_included array for later use
                foreach( (array) $slidedecks as $slidedeck ) {
                    // Only queue assets to be loaded if the SlideDeck is not being loaded via iframe
                    if( !in_array( $slidedeck['id'], $iframe_slidedecks ) ) {
                        $lens_slug = isset( $slidedeck['lens'] ) && !empty( $slidedeck['lens'] ) ? $slidedeck['lens'] : 'default';
    
                        $this->lenses_included[$lens_slug] = true;
                        foreach( $slidedeck['source'] as $source ) {
                            $this->sources_included[$source] = true;
                        }
    
                        /**
                         * @deprecated DEPRECATED third $type_slug parameter since
                         * 2.1
                         */
                        do_action( "{$this->namespace}_pre_load", $slidedeck, $lens_slug, "", $slidedeck['source'] );
                    }
                }
            }
        }
    }

    /**
     * Load the SlideDeck library JavaScript and support files in the public
     * views to render SlideDecks
     *
     * @uses wp_register_script()
     * @uses wp_enqueue_script()
     * @uses SlideDeck::get()
     * @uses SlideDeckPlugin::is_plugin()
     * @uses SlideDeckLens::get()
     */
    function wp_print_scripts( ) {
        if( !empty( $this->slidedeck_ids ) || $this->get_option( 'always_load_assets' ) ) {
            wp_enqueue_script( 'jquery' );
        }

        $load_assets = $this->load_assets;
        
        if( $this->only_has_iframe_decks ) {
            $load_assets = false;
        }
        
        if( $this->get_option( 'always_load_assets' ) || is_admin() ) {
            $load_assets = true;
        }
        
        if( $load_assets === true ) {
    
            if( $this->get_option( 'dont_enqueue_scrollwheel_library' ) != true ) {
                wp_enqueue_script( 'scrolling-js' );
            }
    
            if( $this->get_option( 'dont_enqueue_easing_library' ) != true ) {
                wp_enqueue_script( 'jquery-easing' );
            }
    
            if( !is_admin( ) ) {
                wp_enqueue_script( "{$this->namespace}-library-js" );
                wp_enqueue_script( "{$this->namespace}-public" );
                wp_enqueue_script( "twitter-intent-api" );
            }
    
            // Make accommodations for the editing view to only load the lens files
            // for the SlideDeck being edited
            if( $this->is_plugin( ) ) {
                if( isset( $_GET['slidedeck'] ) ) {
                    $slidedeck = $this->SlideDeck->get( $_GET['slidedeck'] );
                    $lens = $slidedeck['lens'];
                    $this->lenses_included = array( $lens => 1 );
                }
            }
    
            foreach( (array) $this->lenses_included as $lens_slug => $val ) {
                $lens = $this->Lens->get( $lens_slug );
                if( isset( $lens['script_url'] ) ) {
                    wp_enqueue_script( "{$this->namespace}-lens-js-{$lens_slug}" );
                    if( $this->is_plugin( ) ) {
                        if( isset( $lens['admin_script_url'] ) ) {
                            wp_enqueue_script( "{$this->namespace}-lens-admin-js-{$lens_slug}" );
                        }
                    }
                }
            }
    
            $this->lenses_loaded = true;
        }
    }

    /**
     * Load SlideDeck support CSS files for lenses used by SlideDecks on a page
     *
     * @uses SlideDeckLens::get()
     * @uses SlideDeckLens::get_css()
     */
    function wp_print_styles( ) {
        $load_assets = $this->load_assets;
        
        if( $this->only_has_iframe_decks ) {
            $load_assets = false;
        }
        
        if( $this->get_option( 'always_load_assets' ) || is_admin() ) {
            $load_assets = true;
        }
        
        if( $load_assets === true ) {
            wp_enqueue_style( $this->namespace );
                
            foreach( (array) $this->lenses_included as $lens_slug => $val ) {
                wp_enqueue_style( "{$this->namespace}-lens-{$lens_slug}" );
            }
    
        }
    }

    /**
     * Register scripts used by this plugin for enqueuing elsewhere
     *
     * @uses wp_register_script()
     */
    function wp_register_scripts( ) {
        // Admin JavaScript
        wp_register_script( "{$this->namespace}-admin", SLIDEDECK2_URLPATH . "/js/{$this->namespace}-admin" . (SLIDEDECK2_ENVIRONMENT == 'development' ? '.dev' : '') . ".js", array( 'jquery', 'media-upload', 'slidedeck-fancy-form', $this->namespace . '-simplemodal' ), SLIDEDECK2_VERSION, true );
        // SlideDeck JavaScript Core
        wp_register_script( "{$this->namespace}-library-js", SLIDEDECK2_URLPATH . '/js/slidedeck.jquery' . (SLIDEDECK2_ENVIRONMENT == 'development' ? '.dev' : '') . '.js', array( 'jquery' ), '1.4.1' );
        // Public Javascript
        wp_register_script( "{$this->namespace}-public", SLIDEDECK2_URLPATH . '/js/slidedeck-public' . (SLIDEDECK2_ENVIRONMENT == 'development' ? '.dev' : '') . '.js', array( 'jquery', 'slidedeck-library-js' ), SLIDEDECK2_VERSION );
        // Mouse Scrollwheel jQuery event library
        wp_register_script( "scrolling-js", SLIDEDECK2_URLPATH . '/js/jquery-mousewheel/jquery.mousewheel.min.js', array( 'jquery' ), '3.0.6' );
        // Fancy Form Elements jQuery library
        wp_register_script( "{$this->namespace}-fancy-form", SLIDEDECK2_URLPATH . '/js/fancy-form' . (SLIDEDECK2_ENVIRONMENT == 'development' ? '.dev' : '') . '.js', array( 'jquery' ), '1.0.0' );
        // Tooltipper jQuery library
        wp_register_script( "tooltipper", SLIDEDECK2_URLPATH . '/js/tooltipper' . (SLIDEDECK2_ENVIRONMENT == 'development' ? '.dev' : '') . '.js', array( 'jquery' ), '1.0.1' );
        // jQuery Easing Library
        wp_register_script( "jquery-easing", SLIDEDECK2_URLPATH . '/js/jquery.easing.1.3.js', array( 'jquery' ), '1.3' );
        // jQuery MiniColors Color Picker
        wp_register_script( "jquery-minicolors", SLIDEDECK2_URLPATH . '/js/jquery-minicolors/jquery.minicolors.min.js', array( 'jquery' ), '7d21e3c363' );
        // SlideDeck Preview Updater
        wp_register_script( "{$this->namespace}-preview", SLIDEDECK2_URLPATH . '/js/slidedeck-preview' . (SLIDEDECK2_ENVIRONMENT == 'development' ? '.dev' : '') . '.js', array( 'jquery' ), SLIDEDECK2_VERSION );
        // Simple Modal Library
        wp_register_script( "{$this->namespace}-simplemodal", SLIDEDECK2_URLPATH . '/js/simplemodal' . (SLIDEDECK2_ENVIRONMENT == 'development' ? '.dev' : '') . '.js', array( 'jquery' ), '1.0.1' );
        // Twitter Intent API
        wp_register_script( "twitter-intent-api", (is_ssl( ) ? 'https:' : 'http:') . "//platform.twitter.com/widgets.js", array( ), '1316526300' );
        // Froogaloop for handling Vimeo videos
        wp_register_script( 'froogaloop', SLIDEDECK2_URLPATH . '/js/froogaloop.min.js', array( ), SLIDEDECK2_VERSION, true );
        // Youtube JavaScript API
        wp_register_script( 'youtube-api', (is_ssl( ) ? 'https' : 'http') . '://www.youtube.com/player_api', array( ), SLIDEDECK2_VERSION, true );
        // Dailymotion JavaScript API
        wp_register_script( 'dailymotion-api', (is_ssl( ) ? 'https' : 'http') . '://api.dmcdn.net/all.js', array( ), SLIDEDECK2_VERSION, true );
        // jQuery Masonry
        wp_register_script( 'jquery-masonry', SLIDEDECK2_URLPATH . '/js/jquery.masonry.js', array( 'jquery' ), '2.1.01' );
    }

    /**
     * Register styles used by this plugin for enqueuing elsewhere
     *
     * @uses wp_register_style()
     */
    function wp_register_styles( ) {
        // Admin Stylesheet
        wp_register_style( "{$this->namespace}-admin", SLIDEDECK2_URLPATH . "/css/{$this->namespace}-admin.css", array( ), SLIDEDECK2_VERSION, 'screen' );
        // Gplus How-to Modal Stylesheet
        wp_register_style( "gplus-how-to-modal", SLIDEDECK2_URLPATH . "/css/gplus-how-to-modal.css", array( ), SLIDEDECK2_VERSION, 'screen' );
        // Public Stylesheet
        wp_register_style( $this->namespace, SLIDEDECK2_URLPATH . "/css/slidedeck.css", array( ), SLIDEDECK2_VERSION, 'screen' );
        // Fancy Form Elements library
        wp_register_style( "{$this->namespace}-fancy-form", SLIDEDECK2_URLPATH . '/css/fancy-form.css', array( ), '1.0.0', 'screen' );
        // jQuery MiniColors Color Picker
        wp_register_style( "jquery-minicolors", SLIDEDECK2_URLPATH . '/css/jquery.minicolors.css', array( ), '7d21e3c363', 'screen' );
    }

}
    
register_activation_hook( __FILE__, array( 'SlideDeckPlugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'SlideDeckPlugin', 'deactivate' ) );

// SlideDeck Personal should load, then Lite, then Professional, then Developer
add_action( 'plugins_loaded', array( 'SlideDeckPlugin', 'instance' ), 10 );