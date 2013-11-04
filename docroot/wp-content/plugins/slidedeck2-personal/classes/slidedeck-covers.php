<?php
/**
 * SlideDeck Covers Model
 * 
 * Model for handling CRUD and other basic functionality for Covers
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
class SlideDeckCovers {
    var $fonts = array();
    
    var $namespace = "slidedeck";
    
    var $options_model = array(
        'accent_color' => array(
            'name' => 'accent_color',
            'type' => 'text',
            'data' => "string",
            'value' => "#c3609e",
            'attr' => array(
                'class' => "color-picker",
                'size' => 10,
                'maxlength' => 7
            ),
            'label' => "Accent Color"
        ),
        'back_title' => array(
            'name' => 'back_title',
            'type' => 'textarea',
            'data' => "string",
            'attr' => array(
                'rows' => 3,
                'cols' => 20,
                'maxlength' => 70
            ),
            'label' => "Back Title",
            'value' => "The End"
        ),
        'button_label' => array(
            'name' => 'button_label',
            'type' => 'text',
            'data' => "string",
            'attr' => array(
                'size' => 20,
                'maxlength' => 20
            ),
            'label' => "Button Label",
            'value' => "Visit slidedeck.com"
        ),
        'button_url' => array(
            'name' => 'button_url',
            'type' => 'text',
            'data' => "string",
            'attr' => array(
                'size' => 20,
                'maxlength' => 255
            ),
            'label' => "Button URL",
            'value' => "http://www.slidedeck.com/"
        ),
        'front_title' => array(
            'name' => 'front_title',
            'type' => 'textarea',
            'data' => "string",
            'attr' => array(
                'rows' => 3,
                'cols' => 20,
                'maxlength' => 70
            ),
            'value' => "",
            'label' => "Front Title"
        ),
        'title_font' => array(
            'name' => 'title_font',
            'type' => 'select',
            'data' => "string",
            'label' => "Font",
            'description' => "Font for front and back covers",
            'value' => "raleway",
            'values' => array()
        ),
        'show_curator' => array(    
            'name' => 'show_curator',
            'type' => 'radio',
            'data' => 'boolean',
            'value' => true,
            'label' => "Show Curator",
            'description' => "Display credit to the author of this SlideDeck"
        ),
        'cover_style' => array(
            'name' => 'cover_style',
            'type' => 'select',
            'data' => "string",
            'label' => "Style",
            'value' => "fabric",
            'values' => array(
        		'leather' => "Leather",
        		'book' => "Book",
        		'modern' => "Modern",
        		'fabric' => "Fabric",
        		'wood' => "Wood",
        		'glass' => "Glass"
            )
        ),
        'variation' => array(
        	'name' => 'variation',
        	'type' => 'select',
        	'data' => "string",
        	'label' => "Variation",
        	'value' => "",
        	'values' => array()
        ),
        'peek' => array(
        	'name' => 'peek',
        	'type' => 'radio',
        	'data' => "boolean",
        	'label' => "Peek",
        	'value' => false
		),
		'show-front-cover' => array(
			'name' => 'show-front-cover',
			'type' => 'checkbox',
			'data' => 'boolean',
			'label' => "Enable Front Cover",
			'value' => false
		),
		'show-back-cover' => array(
			'name' => 'show-back-cover',
			'type' => 'checkbox',
			'data' => 'boolean',
			'label' => "Enable Back Cover",
			'value' => false
		)
    );
	
	// Available variations for cover styles
	var $variations = array(
		'leather' => array(),
		'book' => array(
			'natural' => "Natural",
			'light' => "Light",
			'dark' => "Dark"
		),
		'modern' => array(),
		'fabric' => array(),
		'wood' => array(),
		'glass' => array()
	);
    
    function __construct() {
        add_filter( "{$this->namespace}_footer_scripts", array( &$this, 'slidedeck_footer_scripts' ), 10, 2 );
        add_filter( "{$this->namespace}_frame_classes", array( &$this, 'slidedeck_frame_classes' ), 10, 2 );
        add_filter( "{$this->namespace}_render_slidedeck_before", array( &$this, 'slidedeck_before_slidedeck_render' ), 1, 2 );
        add_filter( "{$this->namespace}_render_slidedeck_after", array( &$this, 'slidedeck_after_slidedeck_render' ), 100, 2 );
    }

    /**
     * Get Cover Settings
     * 
     * Gets the Cover data for a SlideDeck
     * 
     * @param integer $slidedeck_id Parent SlideDeck ID
     * 
     * @global $SlideDeckPlugin
     * 
     * @uses get_post_meta()
     * @uses SlideDeck::get_parent_id()
     * @uses wp_cache_get()
     * @uses wp_cache_set()
     * 
     * @return array
     */
    function get( $slidedeck_id ) {
        global $SlideDeckPlugin;
        
        $parent_slidedeck_id = $SlideDeckPlugin->SlideDeck->get_parent_id( $slidedeck_id );
        
        $cache_key = $this->namespace . "--" . md5( __METHOD__ . $parent_slidedeck_id );
        
        $cover = wp_cache_get( $cache_key, slidedeck2_cache_group( 'cover-get' ) );
        
        if( $cover == false ) {
            $cover_defaults = $this->get_defaults( $slidedeck_id );
            
            foreach( $this->options_model as $name => $properties ) {
                $cover[$name] = get_post_meta( $parent_slidedeck_id, "{$this->namespace}_cover_$name", true );
                
                if( $cover[$name] === "" ) {
                    $cover_value = $properties['value'];
                    
                    switch( $name ) {
                        case "front_title":
                            $cover_value = get_the_title( $parent_slidedeck_id );
                        break;
                        
                        case "accent_color":
                        case "title_font":
                            $cover_value = $cover_defaults[$name];
                        break;
                    }
                    
                    $cover[$name] = $cover_value;
                }
                
                switch( $properties['data'] ) {
                    case "string":
                        $cover[$name] = (string) $cover[$name];
                    break;
                    case "integer":
                        $cover[$name] = (integer) $cover[$name];
                    break;
                    case "boolean":
                        $cover[$name] = $cover[$name] == 1 ? true : false;
                    break;
                }
            }
            
            wp_cache_set( $cache_key, $cover, slidedeck2_cache_group( 'cover-get' ) );
        }

        return $cover;
    }

    /**
     * Get Default Values
     * 
     * Builds a single dimension array of keys and their values.
     * 
     * @param integer $slidedeck_id Optional SlideDeck ID to get some defaults from SlideDeck
     * 
     * @return array
     */
    function get_defaults( $slidedeck_id = null ) {
        global $SlideDeckPlugin;
        
        $defaults = array();
        
        foreach( $this->options_model as $option => $properties ) {
            $defaults[$option] = $properties['value'];
        }
        
        if( isset( $slidedeck_id ) ) {
            $slidedeck = $SlideDeckPlugin->SlideDeck->get( $slidedeck_id );
            
            if( !empty( $slidedeck ) ) {
                $defaults['accent_color'] = $slidedeck['options']['accentColor'];
                $defaults['title_font'] = $slidedeck['options']['titleFont'];
            }
        }
        
        return $defaults;
    }
    
    /**
     * Check if there are saved covers for a SlideDeck
     * 
     * @param integer $slidedeck_id SlideDeck ID
     * 
     * @return boolean
     */
    function has_saved_covers( $slidedeck_id ) {
        $has_saved_covers = false;
        
        foreach( $this->options_model as $name => $properties ) {
            $cover_value = get_post_meta( $slidedeck_id, "{$this->namespace}_cover_$name", true );
            if( !empty( $cover_value ) ) {
                $has_saved_covers = true;
            }
        }
        
        return $has_saved_covers;
    }
    
    /**
     * Render SlideDeck Cover
     * 
     * @param integer $slidedeck_id SlideDeck ID
     * @param string $front_back Get either the front or back cover (front|back)
     * 
     * @return string
     */
    function render( $slidedeck_id, $front_back = 'front' ) {
        global $SlideDeckPlugin;
        
        $slidedeck = $SlideDeckPlugin->SlideDeck->get( $slidedeck_id );
        $cover = $this->get( $slidedeck_id );
        $fonts = $SlideDeckPlugin->SlideDeck->get_fonts( $slidedeck );
        
        extract( $cover );
        
        $title_font = $fonts[$title_font];
        
        $slidedeck_author = get_userdata( $slidedeck['author'] );
        $curator_name = !empty( $slidedeck_author->display_name ) ? $slidedeck_author->display_name : $slidedeck_author->user_login;
        $curator_avatar = get_avatar( $slidedeck_author->ID, 15, "Mystery Man", $curator_name );
        
        $html = "";
        
        ob_start();
            
            include( SLIDEDECK2_DIRNAME . '/views/elements/_' . $front_back . '-cover.php' );
        
            $html.= ob_get_contents();
        
        ob_end_clean();
        
        $html = apply_filters( "{$this->namespace}_render_cover", $html, $slidedeck_id, $front_back );

        return $html;
    }
    
    /**
     * Save SlideDeck Cover
     * 
     * Saves Cover data and returns an array of the saved data
     * 
     * @param integer $slidedeck_id SlideDeck ID
     * @param array $params Array of data to save for the cover
     * 
     * @return array
     */
    function save( $slidedeck_id, $params ) {
    	global $SlideDeckPlugin;
		
		$parent_slidedeck_id = $SlideDeckPlugin->SlideDeck->get_parent_id( $slidedeck_id );
		
        if( !isset( $params['show_curator'] ) ) {
            $params['show_curator'] = false;
        }
        
        $cover = array_merge( $this->get_defaults( $slidedeck_id ), $params );
        
        foreach( $this->options_model as $name => $properties ) {
            $value = $cover[$name];
            
            switch( $properties['data'] ) {
                case "string":
                    $value = stripslashes( strip_tags( $value ) );
                break;
                case "boolean";
                    $value = isset( $params[$name] ) ? (bool) $params[$name] : false;
                    if( $value === true ) {
                        $value = 1;
                    } else {
                        $value = 0;
                    }
                break;
                case "integer":
                    $value = intval( $cover[$name] );
                break;
            }
            
            $cover[$name] = $value;
            update_post_meta( $parent_slidedeck_id, "{$this->namespace}_cover_$name", $cover[$name] );
        }
        
        return $cover;
    }
    
    /**
     * slidedeck_after_slidedeck_render filter hook-in
     * 
     * Looks up the cover for a SlideDeck (if it exists) and renders it before the SlideDeck
     * 
     * @param string $html HTML being rendered before the SlideDeck
     * @param array $slidedeck The SlideDeck object
     * 
     * @uses SlideDeckCovers::render()
     * 
     * @return string
     */
    function slidedeck_after_slidedeck_render( $html, $slidedeck ) {
        // Make exception when nocovers is an URL parameter (for preview modal)
        if( isset( $_GET['nocovers'] ) )
            return $html;
        
        if( $slidedeck['options']['show-back-cover'] ) {
            $html .= $this->render( $slidedeck['id'], 'back' );
        }
        
        return $html;
    }
    
    /**
     * slidedeck_before_slidedeck_render filter hook-in
     * 
     * Looks up the cover for a SlideDeck (if it exists) and renders it before the SlideDeck
     * 
     * @param string $html HTML being rendered before the SlideDeck
     * @param array $slidedeck The SlideDeck object
     * 
     * @uses SlideDeckCovers::render()
     * 
     * @return string
     */
    function slidedeck_before_slidedeck_render( $html, $slidedeck ) {
        // Make exception when nocovers is an URL parameter (for preview modal)
        if( isset( $_GET['nocovers'] ) )
            return $html;
        
        if( $slidedeck['options']['show-front-cover'] ) {
            $html .= $this->render( $slidedeck['id'], 'front' );
        }
        
        return $html;
    }
    
    /**
     * slidedeck_footer_scripts filter hook-in
     * 
     * Add font @import directives for Google fonts. Keeps track of fonts being loaded to
     * prevent multiple @import requests for the same font.
     * 
     * @param string $scripts The existing CSS styles to be output
     * @param array $slidedeck The SlideDeck object
     * 
     * @global $SlideDeckPlugin
     * 
     * @uses SlideDeckCovers::get()
     * 
     * @return string
     */
    function slidedeck_footer_scripts( $scripts, $slidedeck ) {
        global $SlideDeckPlugin;
        
        $fonts_to_check = array();
        
        // Make exception when nocovers is an URL parameter (for preview modal)
        if( isset( $_GET['nocovers'] ) )
            return $scripts;
        
        if( $slidedeck['options']['show-front-cover'] || $slidedeck['options']['show-back-cover'] ) {
            $cover = $this->get( $slidedeck['id'] );
            $fonts_to_check[] = $cover['title_font'];
        }
        
        $fonts = $SlideDeckPlugin->SlideDeck->get_fonts( $slidedeck );
        
        foreach( $fonts_to_check as $font_key ) {
            if( !empty( $font_key ) ){
                $font = $fonts[$font_key];
                if( array_key_exists( 'import', $font ) ) {
                    if( !in_array( $font['import'], $SlideDeckPlugin->font_imports_included ) ) {
                        $scripts.= '<link rel="stylesheet" type="text/css" href="' . $font['import'] . '" />';
                        $SlideDeckPlugin->font_imports_included[] = $font['import'];
                    }
                }
            }
        }
        
        return $scripts;
    }

    function slidedeck_frame_classes( $classes, $slidedeck ) {
        if( $slidedeck['options']['show-front-cover'] || $slidedeck['options']['show-back-cover'] ) {
            $cover = $this->get( $slidedeck['id'] );
            
			$class_prefix = "slidedeck-cover-";
            
            $classes[] = $class_prefix . 'style-' . $cover['cover_style'];
            if( !empty( $cover['variation'] ) ) $classes[] = $class_prefix . $cover['variation'];
            if( $cover['peek'] ) $classes[] = $class_prefix . 'peek';
        }
        
        return $classes;
    }
}
