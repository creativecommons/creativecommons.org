<?php
/**
 * SlideDeck Lens Scaffold
 * 
 * This is the non-instantiated parent class for SlideDeck Lenses to be built
 * off of. All of the necessary action and filter hooking with regards to lens
 * modifiable areas is done here to keep things as DRY as possible.
 * 
 * @author dtelepathy
 * @package SlideDeck
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
class SlideDeckLens_Scaffold {
    var $namespace = "slidedeck";
    
    var $options_model = array();
    
    // SlideDeck classes prefix
    var $prefix = "sd2-";
    
    function __construct() {
        // Classes applied to the SlideDeck's frame element on render
        if( method_exists( $this, "slidedeck_frame_classes" ) )
            add_filter( "{$this->namespace}_frame_classes", array( &$this, "slidedeck_frame_classes" ), 20, 2 );
        
        // Classes applied to the SlideDeck element on render
        if( method_exists( $this, "slidedeck_classes" ) )
            add_filter( "{$this->namespace}_classes", array( &$this, "slidedeck_classes" ), 20, 2 );
        
        // In-line Styles applied to the SlideDeck element on render
        if( method_exists( $this, "slidedeck_styles" ) )
            add_filter( "{$this->namespace}_styles", array( &$this, "slidedeck_styles" ), 20, 2 );
        
        // Post-processing of the rendered slides on render
        if( method_exists( $this, "slidedeck_render_slides" ) )
            add_filter( "{$this->namespace}_render_slides", array( &$this, "slidedeck_render_slides" ), 20, 2 );
        
        // HTML content before the SlideDeck
        if( method_exists( $this, "slidedeck_render_slidedeck_before" ) )
            add_filter( "{$this->namespace}_render_slidedeck_before", array( &$this, "slidedeck_render_slidedeck_before" ), 20, 2 );
        
        // HTML content after the SlideDeck
        if( method_exists( $this, "slidedeck_render_slidedeck_after" ) )
            add_filter( "{$this->namespace}_render_slidedeck_after", array( &$this, "slidedeck_render_slidedeck_after" ), 20, 2 );
        
        // Vertical slide properties on render
        if( method_exists( $this, "slidedeck_vertical_properties" ) )
            add_filter( "{$this->namespace}_vertical_properties", array( &$this, "slidedeck_vertical_properties" ), 20, 2 );
        
        // SlideDeck options upon save
        if( method_exists( $this, "slidedeck_options" ) )
            add_filter( "{$this->namespace}_options", array( &$this, "slidedeck_options" ), 20, 2 );
        
        // Available SlideDeck interface optinos
        if( method_exists( $this, "slidedeck_options_model" ) )
            add_filter( "{$this->namespace}_options_model", array( &$this, "slidedeck_options_model" ), 20, 2 );
        
        // Default SlideDeck options upon retrieval
        if( method_exists( $this, "slidedeck_default_options" ) )
            add_filter( "{$this->namespace}_default_options", array( &$this, "slidedeck_default_options" ), 20, 4 );
        
        // Default SlideDeck sizes
        if( method_exists( $this, "slidedeck_sizes" ) )
            add_filter( "{$this->namespace}_sizes", array( &$this, "slidedeck_sizes" ), 20, 2 );
                    
        // SlideDeck frame inline styles array
        if( method_exists( $this, "slidedeck_frame_styles_arr" ) )
            add_filter( "{$this->namespace}_frame_styles_arr", array( &$this, "slidedeck_frame_styles_arr" ), 20, 2 );
                    
        // SlideDeck frame inline styles array
        if( method_exists( $this, "slidedeck_styles_arr" ) )
            add_filter( "{$this->namespace}_styles_arr", array( &$this, "slidedeck_styles_arr" ), 20, 2 );
                    
        // SlideDeck node pre-processing for slide rendering
        if( method_exists( $this, "slidedeck_slide_nodes" ) )
            add_filter( "{$this->namespace}_slide_nodes", array( &$this, "slidedeck_slide_nodes" ), 20, 2 );
                    
        // Additional JavaScript to render in the footer
        if( method_exists( $this, "slidedeck_footer_scripts" ) )
            add_filter( "{$this->namespace}_footer_scripts", array( &$this, "slidedeck_footer_scripts" ), 20, 2 );
                    
        // Additional CSS styles to render in the footer
        if( method_exists( $this, "slidedeck_footer_styles" ) )
            add_filter( "{$this->namespace}_footer_styles", array( &$this, "slidedeck_footer_styles" ), 20, 2 );
                    
        // After save processing
        if( method_exists( $this, "slidedeck_after_save" ) )
            add_action( "{$this->namespace}_after_save", array( &$this, "slidedeck_after_save" ), 20, 4 );
                    
        // After save processing
        if( method_exists( $this, "slidedeck_dimensions" ) )
            add_action( "{$this->namespace}_dimensions", array( &$this, "slidedeck_dimensions" ), 20, 5 );
        
        // Flag to process all horizontal slides as vertical
        if( method_exists( $this, "slidedeck_process_as_vertical") )
			add_filter( "{$this->namespace}_process_as_vertical", array( &$this, 'slidedeck_process_as_vertical' ), 10, 2 );
		
        // Extend/Merge fonts, sizes and options automatically
        add_filter( "{$this->namespace}_default_options", array( &$this, "_slidedeck_default_options" ), 19, 4 );
        add_filter( "{$this->namespace}_dimensions", array( &$this, "_slidedeck_dimensions" ), 19, 5 );
        add_filter( "{$this->namespace}_footer_scripts", array( &$this, "_slidedeck_footer_scripts" ), 19, 2 );
        add_filter( "{$this->namespace}_frame_classes", array( &$this, "_slidedeck_frame_classes" ), 19, 3 );
        add_filter( "{$this->namespace}_get_font", array( &$this, "_slidedeck_get_font" ), 19, 3 );
        add_filter( "{$this->namespace}_options_model", array( &$this, "_slidedeck_options_model" ), 19, 2 );
        add_filter( "{$this->namespace}_sizes", array( &$this, "_slidedeck_sizes" ), 19, 2 );
        
        add_action( "{$this->namespace}_get_lenses", array( &$this, '_slidedeck_get_lenses' ), 9, 2 );
        
        // Register JavaScript used by each lens
        add_action( 'init', array( &$this, '_slidedeck_register_scripts' ), 1 );

        // Register Stylesheet used by this lens
        add_action( 'init', array( &$this, '_slidedeck_register_styles' ), 1 );
    }
    
    function __get( $name ) {
        switch( $name ) {
            case "lens":
                return $this->get_lens();
            break;
            
            case "slug":
                return $this->get_slug();
            break;
            
            default:
                $trace = debug_backtrace();
                trigger_error( "Undefined property via __get(): " . $name . " in " . $trace[0]['file'] . " on line " . $trace[0]['line'], E_USER_NOTICE );
            break;
        }
    }
    
    /**
     * WordPress init hook-in
     * 
     * Setup JSON based options model values for this lens
     */
    function _slidedeck_get_lenses( $lenses, $slug ) {
        foreach( $lenses as $lens ) {
            if( isset( $slug ) && $slug == $lens['slug'] ) {
                if( isset( $lens['meta']['fonts'] ) )
                    foreach( $lens['meta']['fonts'] as $key => $font ) {
                        $this->options_model['Appearance']['titleFont']['values'][$key] = $font['label'];
                        $this->options_model['Appearance']['bodyFont']['values'][$key] = $font['label'];
                    }
                
                if( isset( $lens['meta']['variations'] ) && !empty( $lens['meta']['variations'] ) ) {
                    $variation_keys = array_keys( $lens['meta']['variations'] );
                    $lens_variations = array(
                        'type' => 'select',
                        'data' => 'string',
                        'label' => "Lens Variation",
                        'value' => reset( $variation_keys ),
                        'values' => $lens['meta']['variations'],
                        'description' => "Some lenses have multiple variations. Pick the variant that best matches your site, and content.",
                        'weight' => 30
                    );
                    
                    if( isset( $options_model['Appearance']['lensVariations'] ) ){
                        $this->options_model['Appearance']['lensVariations'] = array_merge( $options_model['Appearance']['lensVariations'] , $lens_variations );
                    }else{
                        $this->options_model['Appearance']['lensVariations'] = $lens_variations;
                    }

                }
                
                if( isset( $lens['meta']['variations'] ) && $lens['meta']['variations'] == false ) {
                    $this->options_model['Appearance']['lensVariations']['type'] = 'hidden';
                }
            }
        }

        return $lenses;
    }
        
    /**
     * Merge Lens defaults into SlideDeck options
     * 
     * @param array $options Default options
     * @param string $deprecated DEPRECATED SlideDeck type slug (formerly $type), removed in 2.1
     * @param string $lens The lens being used
     * @param string $source The source being used
     * 
     * @return array
     */
    function _slidedeck_default_options( $options, $deprecated, $lens, $source ) {
        if( $this->is_valid( $lens ) ) {
            if( isset( $this->options_model ) ) {
                $default_options = array();
                foreach( $this->options_model as &$options_groups ) {
                    foreach( $options_groups as $name => $properties ) {
                        if( isset( $properties['value'] ) )
                            $default_options[$name] = $properties['value'];
                    }
                }
                $options = array_merge( $options, $default_options );
            }
        }
        
        return $options;
    }
    
    /**
     * Modify Lens dimensions
     * 
     * Reference array action to modify width and height of a SlideDeck
     * 
     * @param integer $width Width of SlideDeck
     * @param integer $height Height of SlideDeck
     * @param integer $outer_width Outer width of SlideDeck (for iframes)
     * @param integer $outer_height Outer height of SlideDeck (for iframes)
     * @param array $slidedeck The SlideDeck object
     */
    function _slidedeck_dimensions( $width, $height, $outer_width, $outer_height, $slidedeck ) {
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            if( $slidedeck['options']['size'] != "custom" ) {
                // Check if this Lens has custom sizes
                if( isset( $this->lens['meta']['sizes'] ) ) {
                    // Check if this Lens has this size
                    if( isset( $this->lens['meta']['sizes'][$slidedeck['options']['size']] ) ) {
                        $width = $this->lens['meta']['sizes'][$slidedeck['options']['size']]['width'];
                        $height = $this->lens['meta']['sizes'][$slidedeck['options']['size']]['height'];
                    }
                }
            }
        }
    }
    
    /**
     * Add appropriate classes for this Lens to the SlideDeck frame
     * 
     * @param array $slidedeck_classes Classes to be applied
     * @param array $slidedeck The SlideDeck object being rendered
     * 
     * @return array
     */
    function _slidedeck_frame_classes( $slidedeck_classes, $slidedeck ) {
        global $SlideDeckPlugin;
        
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            $slidedeck_classes[] = $this->prefix . $slidedeck['options']['size'];
            
            // If this is a custom size, try and find the closest pre-defined size class
            if( $slidedeck['options']['size'] == "custom" ) {
                $closest_size = $SlideDeckPlugin->SlideDeck->get_closest_size( $slidedeck );
                
                $slidedeck_classes[] = $this->prefix . $closest_size;
            }
            
            if( isset( $slidedeck['options']['lensVariations'] ) ) {
                $slidedeck_classes[] = $this->prefix . $slidedeck['options']['lensVariations'];
            }
			
			$default_nav_styles = true;
			if( isset( $this->lens['meta']['default_nav_styles'] ) && $this->lens['meta']['default_nav_styles'] === false ) {
				$default_nav_styles = false;
			}
			
			if( $default_nav_styles )
				$slidedeck_classes[] = "default-nav-styles";
			
			if( isset( $this->options_model ) ) {
	        	foreach( (array) $this->options_model as $options_group => $options ) {
	        		foreach( $options as $name => $properties ) {
		                if( preg_match( "/^(hide|show)/", $name ) ) {
		                    if( $slidedeck['options'][$name] == true )
		                        $slidedeck_classes[] = $this->prefix . $name;
		                }
	        		}
	        	}
			}
        }
        
        return $slidedeck_classes;
    }
    
    /**
     * slidedeck_footer_scripts filter hook-in
     * 
     * Add font @import directives for Google fonts. Keeps track of fonts being loaded to
     * prevent multiple @import requests for the same font.
     * 
     * @param string $styles The existing CSS styles to be output
     * @param array $slidedeck The SlideDeck object
     * 
     * @global $SlideDeckPlugin
     * 
     * @return string
     */
    function _slidedeck_footer_scripts( $scripts, $slidedeck ) {
        global $SlideDeckPlugin;
        
        $title_font = $SlideDeckPlugin->SlideDeck->get_title_font( $slidedeck );
        $body_font = $SlideDeckPlugin->SlideDeck->get_body_font( $slidedeck );
        
        foreach( array( $title_font, $body_font ) as $font ) {
            if( array_key_exists( 'import', (array) $font ) ) {
                if( !in_array( $font['import'], $SlideDeckPlugin->font_imports_included ) ) {
                    $scripts.= '<link rel="stylesheet" type="text/css" href="' . $font['import'] . '" media="all" />';
                    $SlideDeckPlugin->font_imports_included[] = $font['import'];
                }
            }
        }
        
        return $scripts;
    }
    
    /**
     * slidedeck_get_font_stack filter hook-in
     * 
     * Add font stacks from the Lens automatically to the available stacks
     * 
     * @param array $fonts The font stacks available
     * @param array $slidedeck The SlideDeck object
     * 
     * @return array
     */
    function _slidedeck_get_font( $fonts, $slidedeck ) {
        if( $this->is_valid( $slidedeck['lens'] ) && isset( $this->lens['meta']['fonts'] ) ) {
            $fonts = array_merge( $fonts, (array) $this->lens['meta']['fonts'] );
			uksort( $fonts, 'strnatcasecmp' );
        }
        
        return $fonts;
    }
    
    /**
     * Hook into options model to add additional Lens options
     * 
     * @param array $options_model The options to be output
     * @param array $slidedeck The SlideDeck object
     * 
     * @return array
     */
    function _slidedeck_options_model( $options_model, $slidedeck ) {
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            // Check if Lens has Options Model additions
            if( isset( $this->options_model ) ) {
                // Loop through Options Model groups
                foreach( $this->options_model as $options_group => $options ) {
                    // Loop through options in each Options Model group
                    foreach( $options as $option_key => $option_params ) {
                        // Check if the option exists and needs merging or addition
                        if( isset( $options_model[$options_group][$option_key] ) ) {
                            // Check if this option has values to and merge them
                            if( isset( $options_model[$options_group][$option_key]['values'] ) ) {
                                // Only merge if the Lens Options Model option has additional values
                                if( isset( $option_params['values'] ) )
                                    $option_params['values'] = array_merge( $options_model[$options_group][$option_key]['values'], $option_params['values'] );
                            }
                            // Merge options
                            $options_model[$options_group][$option_key] = array_merge( (array) $options_model[$options_group][$option_key], $option_params );
                        } else {
                            // Define option
                            $options_model[$options_group][$option_key] = $option_params;
                        }
                    }
                }
            }
        }
        
        return $options_model;
    }
    
    /**
     * SlideDeck Sizes
     * 
     * @param array $sizes Sizes available
     * @param array $slidedeck The SlideDeck being loaded
     * 
     * @return array
     */
    function _slidedeck_sizes( $sizes, $slidedeck ) {
        if( $this->is_valid( $slidedeck['lens'] ) && isset( $this->lens['meta']['sizes'] ) )
            $sizes = array_merge( $sizes, $this->lens['meta']['sizes'] );
        
        return $sizes;
    }
    
    /**
     * SlideDeck Register Scripts
     * Registration of the lens scripts making them ready to enqueue
     */
    function _slidedeck_register_scripts( ) {
        if( isset( $this->lens['script_url'] ) ) {
            wp_register_script( "{$this->namespace}-lens-js-{$this->lens['slug']}", $this->lens['script_url'], array( 'jquery', "{$this->namespace}-library-js" ), SLIDEDECK2_VERSION );
            if( isset( $this->lens['admin_script_url'] ) ) {
                wp_register_script( "{$this->namespace}-lens-admin-js-{$this->lens['slug']}", $this->lens['admin_script_url'], array( 'jquery', "{$this->namespace}-admin" ), SLIDEDECK2_VERSION, true );
            }
        }
    }
    
    /**
     * SlideDeck Register Styles
     * Registration of the lens styles making them ready to enqueue
     */
    function _slidedeck_register_styles( ) {
        $version = isset(  $this->lens['meta']['version'] ) && !empty( $lens['meta']['version'] ) ? $lens['meta']['version'] : SLIDEDECK2_VERSION;
        wp_register_style( "{$this->namespace}-lens-{$this->lens['slug']}", $this->lens['url'], array( $this->namespace ), $version );
    }
    
    /**
     * Get SlideDeck Lens
     * 
     * Get's the lens for the current SlideDeck and assigns it to the lens property
     * 
     * @return array
     */
    private function get_lens() {
        global $SlideDeckPlugin;
        
        if( !isset( $this->lens ) )
            $this->lens = $SlideDeckPlugin->Lens->get( $this->slug );
        
        return $this->lens;
    }
    
    /**
     * Lens Slug
     * 
     * Builds the slug of the Lens based off of the name of the instance's
     * class name.
     * 
     * @return string
     */
    private function get_slug() {
        if( !isset( $this->slug ) ) {
            $patterns = array(
                "/^SlideDeckLens_/",
                "/([A-Z])/",
                "/([a-zA-Z]+)(\d+)([a-zA-Z]+)?/"
            );
            $replacements= array(
                "",
                " $1",
                "$1 $2 $3",
            );
            
            $classname = get_class( $this );
            
            $words = trim( preg_replace( $patterns, $replacements, $classname ) );
            
            $this->slug = strtolower( implode( "-", explode( " ", $words ) ) );
        }
        
        return $this->slug;
    }

    /**
     * Check if this Lens should be processed
     * 
     * Validates if the lens slug being passed in matches this Lens' slug
     * 
     * @param string $slug The slug of the Lens
     * 
     * @return boolean
     */
    protected final function is_valid( $slug ) {
        return $slug == $this->slug;
    }
}