<?php
/**
 * SlideDeck 2 Template Functions
 * 
 * More information on this project:
 * http://www.slidedeck.com/
 * 
 * Full Usage Documentation: http://www.slidedeck.com/usage-documentation 
 * 
 * @package SlideDeck
 * @subpackage SlideDeck 2 Pro for WordPress
 * @author dtelepathy
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
?>
<?php
/**
 * Template functions for this plugin
 * 
 * Place all functions that may be usable in theme template files here.
 * 
 * @package SlideDeck
 * @author dtelepathy
 */

/**
 * SlideDeck markup creator public function. This function can be called from a template or theme
 * to embed a SlideDeck in your layout.
 * 
 * @param object $slidedeck_id The ID of the SlideDeck to render
 * @param array $styles [optional] The styles to apply to the main SlideDeck tag ( usually just width and height )
 * @param boolean $include_lens_files Include the lens files used by this SlideDeck
 * 
 * @uses slidedeck_process_template()
 * 
 * @return Rendered SlideDeck markup and JavaScript tag to initialize SlideDeck render
 */
if( !function_exists( 'slidedeck2' ) ) {
    function slidedeck2( $slidedeck_id, $styles=array( 'width' => '100%', 'height' => '370px' ), $include_lens_files = true ) {
        global $SlideDeckPlugin;
        
        $slidedeck = do_shortcode( "[SlideDeck2 id='$slidedeck_id' width='{$styles['width']}' height='{$styles['height']}' include_lens_files='" . ( $include_lens_files == true ? 1 : 0 ) . "']" );
        
        echo $slidedeck;
    }
}

/**
 * Get the URL for the specified plugin action
 * 
 * @param object $str Optional action handle passed in the menu definition
 * 
 * @global $SlideDeckPlugin
 * 
 * @uses SlideDeck::action()
 * 
 * @return The absolute URL to the plugin action specified
 */
if( !function_exists( 'slidedeck2_action' ) ) {
    function slidedeck2_action( $str = "" ) {
        global $SlideDeckPlugin;
        
        $action = $SlideDeckPlugin->action( $str );
        
        return $action;
    }
}

/**
 * Get the icon URL for a source
 * 
 * Defaults to the expected path for a built-in content source, can be overridden
 * by hooking into the filter in the return to set your own source if you are writing
 * a third-party source in another plugin or your theme's functions.php file.
 * 
 * @param mixed $source Either the source's slug or the source object itself (the slug will be extracted from the "name" property)
 * 
 * @global $SlideDeckPlugin
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_get_source_icon_url' ) ) {
    function slidedeck2_get_source_icon_url( $source ) {
        global $SlideDeckPlugin;
        
        // Get the slug from the Source object if that was passed in
        if( is_object( $source ) ) {
            $source = (string) $source->name;
        }
        
        $url = "";
        
        $sources = $SlideDeckPlugin->get_sources();
        if( isset( $sources[$source] ) ) {
            $file_data = $sources[$source]->get_source_file( "/images/icon.png" );
            $url = $file_data['url'];
        }
        
        $url = apply_filters( "{$SlideDeckPlugin->namespace}_source_icon_url", $url, $source );
        
        return $url;
    }
}

/**
 * Echo the icon URL for a SlideDeck source
 * 
 * @uses slidedeck2_get_source_icon_url()
 * 
 * @param mixed $source Either the source's slug or the source object itself (the slug will be extracted from the "name" property)
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_source_icon_url' ) ) {
    function slidedeck2_source_icon_url( $source ) {
        $url = slidedeck2_get_source_icon_url( $source );
        
        echo $url;
    }
}

/**
 * Get the chicklet URL for a source
 * 
 * Defaults to the expected path for a built-in content source, can be overridden
 * by hooking into the filter in the return to set your own source if you are writing
 * a third-party source in another plugin or your theme's functions.php file.
 * 
 * @param mixed $source Either the source's slug or the source object itself (the slug will be extracted from the "name" property)
 * 
 * @global $SlideDeckPlugin
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_get_source_chicklet_url' ) ) {
    function slidedeck2_get_source_chicklet_url( $source ) {
        global $SlideDeckPlugin;
        
        // Get the slug from the Source object if that was passed in
        if( is_object( $source ) ) {
            $source = (string) $source->name;
        }
        
        $url = "";
        
        $sources = $SlideDeckPlugin->get_sources();
        if( isset( $sources[$source] ) ) {
            $file_data = $sources[$source]->get_source_file( "/images/chicklet.png" );
            $url = $file_data['url'];
        }
        
        $url = apply_filters( "{$SlideDeckPlugin->namespace}_source_chicklet_url", $url, $source );
        
        return $url;
    }
}

/**
 * Convenience function to get a SlideDeck's shortcode string
 * 
 * @global $SlideDeckPlugin
 * 
 * @uses SlideDeckPlugin::get_slidedeck_shortcode()
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_get_shortcode' ) ) {
    function slidedeck2_get_shortcode( $slidedeck_id ) {
        global $SlideDeckPlugin;
        
        return $SlideDeckPlugin->get_slidedeck_shortcode( $slidedeck_id );
    }
}

/**
 * Echo the chicklet URL for a SlideDeck source
 * 
 * @uses slidedeck2_get_source_chicklet_url()
 * 
 * @param mixed $source Either the source's slug or the source object itself (the slug will be extracted from the "name" property)
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_source_chicklet_url' ) ) {
    function slidedeck2_source_chicklet_url( $source ) {
        $url = slidedeck2_get_source_chicklet_url( $source );
        
        echo $url;
    }
}

/**
 * Run the the_content filters on the passed in text
 * 
 * @param object $content The content to process
 * 
 * @global $SlideDeckPlugin
 * 
 * @uses SlideDeck::process_slide_content()
 * 
 * @return object $content The formatted content
 */
if( !function_exists( 'slidedeck2_process_slide_content' ) ) {
    function slidedeck2_process_slide_content( $content, $editing = false, $new_format = "" ) {
        global $SlideDeckPlugin;
        
        return $SlideDeckPlugin->process_slide_content( $content, $editing, $new_format );
    }
}

/**
 * Sanitize data using wp_kses() method
 * 
 * @param str $str Data to sanitize for storage
 * 
 * @uses wp_kses()
 * 
 * @return str Sanitized version of $str
 */
if( !function_exists( 'slidedeck2_sanitize' ) ) {
    function slidedeck2_sanitize( $str = "" ) {
        if ( !function_exists( 'wp_kses' ) ) {
            require_once( ABSPATH . 'wp-includes/kses.php' );
        }
        global $allowedposttags;
        global $allowedprotocols;
        
        if ( is_string( $str ) ) {
            $str = wp_kses( $str, $allowedposttags, $allowedprotocols );
        } elseif( is_array( $str ) ) {
            $arr = array();
            foreach( (array) $str as $key => $val ) {
                $arr[$key] = slidedeck2_sanitize( $val );
            }
            $str = $arr;
        }
        
        return $str;
    }
}

/**
 * Set a flash message
 * 
 * Sets a message in a cookie to display on the next view
 * 
 * @param string $str The message to display on the next page
 * @param boolean $error Set if this is an error message or not
 */
if( !function_exists( 'slidedeck2_set_flash' ) ) {
    function slidedeck2_set_flash( $str = "", $error = false ) {
        if( empty( $str ) )
            return false;
        
        // Set error flag
        if( $error === true )
            SlideDeckFlashMessage::set_cookie( 'flash_error', true, 30 );
        
        SlideDeckFlashMessage::set_cookie( 'flash', $str, 30 );
    }
}

/**
 * Outputs a flash message if one is set to be displayed. Can be set to fade out if the
 * fade parameter is set (default is -1, meaning it does not fade out). Errors will not
 * disapear automatically.
 * 
 * @param integer $fade The fade delay in milliseconds
 * @param boolean $echo Echo the response to the view or just return it, default is boolean(true)
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_flash' ) ) {
    function slidedeck2_flash( $fade = -1, $echo = true ) {
        if( empty( SlideDeckFlashMessage::$flash ) )
            return false;
        
        // Determine error or update message type
        $message_class = "updated";
        if( SlideDeckFlashMessage::$flash_error != false ) {
            $message_class = "error";
            // Errors should not disapear
            $fade = -1;
        }
        
        $html = '<div class="' . SlideDeckFlashMessage::$namespace . ' ' . $message_class . '"><p>' . SlideDeckFlashMessage::$flash . '</p></div>';
        
        // Output message fading JavaScript if needed
        if( $fade > -1 )
            $html.= '<script type="text/javascript">(function($){if(typeof($)!="undefined"){$(document).ready(function(){setTimeout(function(){$("#' . SlideDeckFlashMessage::$namespace . '").fadeOut("slow");},' . $fade . ');});}})(jQuery);</script>';
        
        if( $echo === true )
            echo $html;
        
        return $html;
    }
}

/**
 * Get the classname from a file name
 * 
 * Creates a string of the name of a class based off the name of a file.
 * All "-" characters in a file name will be treated as spaces, which will
 * then be eliminated to return a Pascal case class name. An optional class
 * prefix can be passed in as the second parameter.
 * 
 * @param string $filename The name of the file to get the class name from
 * @param string $prefix The optional prefix to use for the class name
 */
if( !function_exists( 'slidedeck2_get_classname_from_filename' ) ) {
    function slidedeck2_get_classname_from_filename( $filename = "", $prefix = "" ) {
        $classname = $prefix . str_replace( " ", "", ucwords( preg_replace( array( '/\.php$/', '/\-/' ), array( "", " " ), basename( $filename ) ) ) );
        
        return $classname;
    }
}

/**
 * Sets a WordPress Transient. Returns a boolean value of the success of the write.
 * 
 * @param string $name The name (key) for the file cache
 * @param mixed $content The content to store for the file cache
 * @param string $time_from_now time in minutes from now when the cache should expire
 * 
 * @uses set_transient()
 * 
 * @return boolean
 */
if( !function_exists( 'slidedeck2_cache_write' ) ) {
    function slidedeck2_cache_write( $name = "", $content = "", $time_from_now = 30 ) {
        $duration = $time_from_now * 60;
        $name = md5( $name . SLIDEDECK2_VERSION . SLIDEDECK2_DIRNAME );
        return set_transient( $name, $content, $duration );
    }
}

/**
 * Reads a file cache value and returns the content stored, 
 * or returns boolean(false)
 * 
 * @param string $name The name (key) for the transient
 * 
 * @uses get_transient()
 * 
 * @return mixed
 */
if( !function_exists( 'slidedeck2_cache_read' ) ) {
    function slidedeck2_cache_read( $name = "" ) {
        $name = md5( $name . SLIDEDECK2_VERSION . SLIDEDECK2_DIRNAME );
        return get_transient( $name );
    }
}

/**
 * Deletes a WordPress Transient Cache
 * 
 * @param string $name The name (key) for the file cache
 * 
 * @uses delete_transient()
 */
if( !function_exists( 'slidedeck2_cache_clear' ) ) {
    function slidedeck2_cache_clear( $name = "" ) {
        delete_transient( $name );
    }
}

/**
 * Create input fields with labels based off of model data
 * 
 * Creates an input or select element with the specified properties. Returns a string of the
 * HTML markup for the field and its label
 * 
 * @param string $name The name attribute of the field to create
 * @param string $value The value of the field
 * @param array $params Array of parameters describing the field
 *              @param string $type The type of field to create (text|email|checkbox|select)
 *              @param string $label The label for the field
 *              @param array $attr Additional attributes to apply to the field HTML element
 *              @param array $values Available values to choose from (only used by select elements and non-boolean checkboxes)
 *              @param string $description Used as the tooltip if present
 *              @param string $suffix Optional suffix to appear after element
 *              @param array $thumbnail Optional thumbnail for the field, has multiple keyed options:
 *                           @param string $src The SRC attribute for the image tag, thumbnail will not be rendered without this
 *                           @param integer $width Width of the thumbnail
 *                           @param integer $height Height of the thumbnail
 *                           @param string $alt ALT attribute of the thumbnail
 * @param boolean $echo Echo out the resulting HTML? (default is boolean(true), echo response)
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_html_input' ) ) {
    function slidedeck2_html_input( $name, $value, $params, $echo = true ) {
        // The HTML return string built by this function
        $html = "";
        
        $field_model = array(
            'type' => "text",
            'label' => "",
            'attr' => array(
                'class' => ""
            ),
            'values' => array(),
            'description' => "",
            'thumbnail' => array(),
            'suffix' => "",
            'interface' => array(),
            'required' => false
        );
        $merged_params = array();
        foreach( $field_model as $key => $val ) {
            if( is_array( $val ) ) {
                if( isset( $params[$key] ) ) {
                    $merged_params[$key] = $params[$key];
                } else {
                    $merged_params[$key] = $val;
                }
            } else {
                $merged_params[$key] = isset( $params[$key] ) ? $params[$key] : $val;
            }
        }
        extract( $merged_params );
        
        // Alias the $description value as the tooltip
        if( !isset( $tooltip ) )
            $tooltip = &$description;
        
        // Build an ID from the name
        $id = trim( str_replace( array( "[", "]", " " ), array( "-", "", "_" ), trim( $name ) ) );
        // Override ID if it was passed in as an attribute
        if( array_key_exists( 'id',  $attr ) )
            $id = $attr['id'];
        
        // Build the Tooltip HTML string
        $tooltip_str = "";
        if( !empty( $tooltip ) )
            $tooltip_str = '<span class="tooltip" title="' . __( $tooltip, 'slidedeck' ) . '"></span>';
        
        // Build the Thumbnail HTML string
        $thumbnail_str = "";
        if( array_key_exists( 'src', $thumbnail ) ) {
            $thumbnail_params = array(
                'src' => "",
                'alt' => "",
                'width' => "",
                'height' => ""
            );
            $thumbnail = array_merge( $thumbnail_params, $thumbnail );
            
            $thumbnail_str .= '<img src="' . $thumbnail['src'] . '" alt="' . $thumbnail['alt'] . '"';
            if( !empty( $thumbnail['width'] ) ) $thumbnail_str .= ' width="' . $thumbnail['width'] . '"';
            if( !empty( $thumbnail['height'] ) ) $thumbnail_str .= ' height="' . $thumbnail['height'] . '"';
            $thumbnail_str .= ' />';
        }
        
        $required_str = "";
        if( $required == true ) {
            $required_str = '<span class="required" title="' . __( "Required", 'slidedeck' ) . '">*</span>';
        }
        
        switch( $type ) {
            case "hidden":
                $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '" id="' . $id . '" />';
            break;
            
            case "checkbox":
                if( !empty( $label ) ) {
                    $html .= '<span class="label">' . $required_str . __( $label, 'slidedeck' );
                    
                    $html .= $tooltip_str;
                    $html .= $thumbnail_str;
                    
                    $html .= '</span> ';
                }
                
                $html .= '<input type="checkbox" name="' . $name . '" value="1" id="' . $id . '"';
                
                // Check the checkbox if the value is true
                if( $value == true )
                    $html .= ' checked="checked"';
                
                foreach( $attr as $key => $val )
                    if( !in_array( $key, array( 'type', 'name', 'value', 'id', 'checked' ) ) ) 
                        $html .= ' ' . $key . '="' . trim( $val ) . '"';
                
                $html .= ' />';
            break;
            
            case "email":
            case "text":
            case "password":
                if( !empty( $label ) ) {
                    $html .= '<label for="' . $id . '" class="label">' . $required_str . __( $label, 'slidedeck' );
                    
                    $html .= $tooltip_str;
                    $html .= $thumbnail_str;
                    
                    $html .= '</label> ';
                }
                
                $html .= '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" id="' . $id . '"';
                
                foreach( $attr as $key => $val )
                    if( !in_array( $key, array( 'type', 'name', 'value', 'id' ) ) ) 
                        $html .= ' ' . $key . '="' . trim( $val ) . '"';
                
                $html .= ' />';
            break;
            
            case "textarea":
                if( !empty( $label ) ) {
                    $html .= '<label for="' . $id . '" class="label">' . $required_str . __( $label, 'slidedeck' );
                    
                    $html .= $tooltip_str;
                    $html .= $thumbnail_str;
                    
                    $html .= '</label> ';
                }
                
                $html .= '<textarea type="' . $type . '" name="' . $name . '" id="' . $id . '"';
                
                foreach( $attr as $key => $val )
                    if( !in_array( $key, array( 'type', 'name', 'id' ) ) ) 
                        $html .= ' ' . $key . '="' . trim( $val ) . '"';
                
                $html .= '>'; // Close
                
                $html .= $value;
                $html .= '</textarea>';
            break;
            
            case "select":
                if( !empty( $label ) ) {
                    $html .= '<label for="' . $id . '" class="label">' . $required_str . __( $label, 'slidedeck' );
                    
                    $html .= $tooltip_str;
                    $html .= $thumbnail_str;
                    
                    $html .= '</label> ';
                }
                
                $html .= '<select name="' . $name . '" id="' . $id . '"';
                
                foreach( $attr as $key => $val )
                    if( !in_array( $key, array( 'name', 'id' ) ) ) 
                        $html .= ' ' . $key . '="' . trim( $val ) . '"';
                
                $html .= '>';
                
                foreach( $values as $option_value => $option_text )
                    $html .= '<option value="' . $option_value . '"' . ( $option_value == $value ? ' selected="selected"' : '' ) . '>' . $option_text . '</option>';
                
                $html.= '</select>';
            break;
            
            case "radio":
                if( !empty( $label ) ) {
                    $html .= '<span class="label">' . $required_str . __( $label, 'slidedeck' );
                    
                    $html .= $tooltip_str;
                    $html .= $thumbnail_str;
                    
                    $html .= '</span> ';
                }
                
                $is_radio_boolean = false;
                
                if( empty( $values ) ) {
                    $is_radio_boolean = true;    
                    $values = array(
                        '1' => 'On',
                        '' => 'Off'
                    );                    
                }
                
                foreach( $values as $radio_value => $radio_text ){
                    
                    $id_suffix = $radio_value;
                    
                    if( $is_radio_boolean ){
                        switch( $radio_value ){
                            case '1':
                                $id_suffix = 'on';
                            break;
                            default:
                                $id_suffix = 'off';
                            break;    
                        }
                    }
                    
                    $html .= '<label for="' . $id . '-' . $id_suffix . '" class="label">' . $required_str . __( $radio_text, 'slidedeck' );
                    $html .= $thumbnail_str;
                    $html .= '<input id="' . $id . '-' . $id_suffix . '" type="radio" name="' . $name . '" value="' . $radio_value . '"' . ( $radio_value == $value ? ' checked="checked"' : '' );
                    
                    foreach( $attr as $key => $val )
                        if( !in_array( $key, array( 'type', 'name', 'id' ) ) ) 
                            $html .= ' ' . $key . '="' . trim( $val ) . '"';
                    
                    $html .= ' />';
                    
                    $html .= '</label> ';
                }
            break;
        }
        
        if( !empty( $suffix ) && $type != "hidden" )
            $html.= '<span class="suffix">' . __( $suffix, 'slidedeck' ) . '</span>';
        
        if( !empty( $interface ) ) {
            $html .= '<script type="text/javascript">SlideDeckInterfaces["' . $id . '"] = ' . json_encode( $interface ) . ';</script>';
        }
        
        $html = apply_filters( "slidedeck2_html_input", $html, $type, $name, $value, $label, $attr, $values );
        
        if( $echo == true )
            echo $html;
        
        return $html;
    }
}

/**
 * Check if video JavaScript libraries need to be loaded
 * 
 * @since 2.1
 * 
 * @global $SlideDeckPlugin
 * 
 * @return boolean
 */
if( !function_exists( 'slidedeck2_load_video_scripts' ) ) {
    function slidedeck2_load_video_scripts() {
        global $SlideDeckPlugin;
        
        return $SlideDeckPlugin->load_video_scripts;
    }
}

/**
 * Display post categories form fields.
 *
 * @since 2.6.0
 *
 * @param object $post
 */
if( !function_exists( 'slidedeck2_post_categories_meta_box' ) ) {
    function slidedeck2_post_categories_meta_box( $post, $box ) {
        $defaults = array('taxonomy' => 'category');
        if ( !isset($box['args']) || !is_array($box['args']) )
            $args = array();
        else
            $args = $box['args'];
        extract( wp_parse_args($args, $defaults), EXTR_SKIP );
        $tax = get_taxonomy($taxonomy);
    
        ?>
        <div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
            <ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
                <li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>
                <li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php _e( 'Most Used' ); ?></a></li>
            </ul>
    
            <div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
                <ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
                    <?php $popular_ids = wp_popular_terms_checklist($taxonomy); ?>
                </ul>
            </div>
    
            <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
                <?php
                $name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';
                echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
                ?>
                <ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">
                    <?php wp_terms_checklist(0, array( 'taxonomy' => $taxonomy, 'selected_cats' => $args['selected_cats'], 'popular_cats' => $popular_ids ) ) ?>
                </ul>
            </div>
        </div>
        <?php
    }
}


/**
 * Display post tags form fields.
 *
 * @since 2.6.0
 *
 * @param object $post
 */
if( !function_exists( 'slidedeck2_post_tags_meta_box' ) ) {
    function slidedeck2_post_tags_meta_box($post, $box) {
        $defaults = array('taxonomy' => 'post_tag');
        if ( !isset($box['args']) || !is_array($box['args']) )
            $args = array();
        else
            $args = $box['args'];
        extract( wp_parse_args($args, $defaults), EXTR_SKIP );
        $tax_name = esc_attr($taxonomy);
        $taxonomy = get_taxonomy($taxonomy);
        $disabled = true;
    ?>
    <div class="tagsdiv" id="<?php echo $tax_name; ?>">
        <div class="jaxtag">
        <div class="nojs-tags hide-if-js">
        <p><?php echo $taxonomy->labels->add_or_remove_items; ?></p>
        <textarea name="<?php echo "tax_input[$tax_name]"; ?>" rows="3" cols="20" class="the-tags" id="tax-input-<?php echo $tax_name; ?>" <?php echo $disabled; ?>><?php echo $args['tags']; // textarea_escaped by esc_attr() ?></textarea></div>
        <?php if ( current_user_can($taxonomy->cap->assign_terms) ) : ?>
        <div class="ajaxtag hide-if-no-js">
            <label class="screen-reader-text" for="new-tag-<?php echo $tax_name; ?>"><?php echo $box['title']; ?></label>
            <div class="taghint"><?php echo $taxonomy->labels->add_new_item; ?></div>
            <p><input type="text" id="new-tag-<?php echo $tax_name; ?>" name="newtag[<?php echo $tax_name; ?>]" class="newtag form-input-tip" size="16" autocomplete="off" value="" />
            <input type="button" class="button tagadd" value="<?php esc_attr_e('Add'); ?>" tabindex="3" /></p>
        </div>
        <p class="howto"><?php echo esc_attr( $taxonomy->labels->separate_items_with_commas ); ?></p>
        <?php endif; ?>
        </div>
        <div class="tagchecklist"></div>
    </div>
    <?php if ( current_user_can($taxonomy->cap->assign_terms) ) : ?>
    <p class="hide-if-no-js"><a href="#titlediv" class="tagcloud-link" id="link-<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->choose_from_most_used; ?></a></p>
    <?php endif; ?>
    <?php
    }
}


/**
 * Truncate text to a specified length
 * 
 * Returns a substring of the text passed in truncated down to the specified length.
 * Does not take into account proper closing of HTML tags.
 * 
 * @param string $str The string to truncate
 * @param integer $length Length to truncate to in characters
 * @param string $suffix The text to append to the end of a truncated string
 */
if( !function_exists( 'slidedeck2_stip_tags_and_truncate_text' ) ) {
    function slidedeck2_stip_tags_and_truncate_text( $str, $length = 55, $suffix = "&hellip;" ) {
        $truncated = trim( mb_substr( strip_tags( $str ), 0, (int) $length ) );
        
        $str_length = function_exists( 'mb_strlen' ) ? mb_strlen( $str ) : strlen( $str );
        
        if( $str_length > $length ) {
            $truncated .= $suffix;
        }
        
        return $truncated;
    }
}

/**
 * Get an avatar for a user
 * 
 * @param mixed $id Email or user ID for this blog
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_get_avatar' ) ) {
    function slidedeck2_get_avatar( $id_or_email, $size = '96' ) {
        $avatar = get_avatar( $id_or_email, $size );
        
        if( $avatar ) {
            $avatar = substr( $avatar, ( strpos( $avatar, " src='" ) + 6 ) );
            $avatar = substr( $avatar, 0, strpos( $avatar, "?s=" ) );
        }
        
        return $avatar;
    }
}

/**
 * Get the License Key
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_get_license_key' ) ) {
    function slidedeck2_get_license_key() {
        global $SlideDeckPlugin;
        
        if( $SlideDeckPlugin ){
            return (string) $SlideDeckPlugin->get_license_key();
        }
        
        return '';
    }
}


/**
 * Get the Upgrade URL
 * 
 * @return string
 */
if( !function_exists( 'slidedeck2_get_renewal_url' ) ) {
    function slidedeck2_get_renewal_url() {
        return SLIDEDECK2_RENEWAL_URL . '&renewal_keyhash=' . md5( slidedeck2_get_license_key() );
    }
}


/**
 * Track an even with KISS metrics
 * 
 * @param string $event The name of the event to track
 * @param array $properties Additional properties to send
 * 
 * @uses wp_remote_fopen()
 */
if( !function_exists( 'slidedeck2_km' ) ) {
    function slidedeck2_km( $event = "", $properties = array(), $force = false ) {
        global $SlideDeckPlugin;
        
        $options = get_option( "slidedeck2_global_options", array() );
        
        if ( $force == false ) {
            // If the user has not opted-in to anonymous stats, fail silently
            if( !isset($options['anonymous_stats_optin']) || !$options['anonymous_stats_optin'] ) {
                return false;
            }
        }
        
        // Setup for events that should be traccked once
        $once_events_option_name = SlideDeckPlugin::$namespace . "_completed_once_events";
        $once_events = array(
            'SlideDeck Installed' => false
        );
        $completed_once_events = get_option( $once_events_option_name, $once_events );
        $completed_once_events = array_merge( $once_events, $completed_once_events );
        
        // If the event should only happen once and it has been logged as already happened, don't log it
        if( isset( $completed_once_events[$event] ) && $completed_once_events[$event] === true ) {
            return false;
        }

        $params = array(
            '_k' => SLIDEDECK2_KMAPI_KEY,
            '_p' => SLIDEDECK2_USER_HASH,
            '_n' => urlencode( $event ),
            'license' => SLIDEDECK2_LICENSE,
            'version' => SLIDEDECK2_VERSION,
            'tier' => SlideDeckPlugin::highest_installed_tier()
        );

        // Get the cohort data from the database
        $cohort = SlideDeckPlugin::get_cohort_data();
        foreach( $cohort as $key => $value ) {
            $params['cohort_' . $key ] = ( isset( $cohort[$key] ) && !empty( $cohort[$key] ) ) ? $cohort[$key] : '' ;
        }
        
        $params = array_merge( $params, $properties );
        
        wp_remote_fopen( "http://trk.kissmetrics.com/e?" . http_build_query( $params ) );
        
        // Log one time events as completed
        if( isset( $once_events[$event] ) ) {
            $completed_once_events[$event] = true;
            update_option( $once_events_option_name, $completed_once_events );
        }
    }
}

if( !function_exists( 'slidedeck2_km_link' ) ) {
    function slidedeck2_km_link( $event = "", $properties = array() ) {
        $params = "";
        
        $options = get_option( "slidedeck2_global_options", array() );
        
        // If the user has not opted-in to anonymous stats, fail silently
        if( !isset($options['anonymous_stats_optin']) || !$options['anonymous_stats_optin'] ) {
            return $params;
        }
        
        $params.= "&kmi=" . SLIDEDECK2_USER_HASH;
        if( !empty( $event ) ) {
            $params.= "&kme=" . urlencode( $event );
        }
        foreach( $properties as $property => $value ) {
            $params.= "&km_{$property}={$value}";
        }
        
        return $params;
    }
}

/**
 * Build the SlideDeck 2 cache groups for use in non-persistent cache and setting cache group names
 * 
 * @param string $group_key The name of the cache group
 * 
 * @return $cache_group
 */
if( !function_exists( 'slidedeck2_cache_group' ) ){
    function slidedeck2_cache_group( $group_key ){
        $cache_groups = array_combine( SlideDeckPlugin::$cache_groups, SlideDeckPlugin::$cache_groups );
        $cache_group = SlideDeckPlugin::$namespace . "-" . $cache_groups[$group_key];

        return $cache_group;
    }
}