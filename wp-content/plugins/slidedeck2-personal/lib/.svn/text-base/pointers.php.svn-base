<?php
/**
 * SlideDeck Pointers Class
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
class SlideDeckPointers {
    // Pointers for the current admin page
    var $pointers = array();
    
    // Namespace for IDs and classes
    var $namespace = "slidedeck";
    
    /**
     * Add a pointer
     * 
     * Adds a pointer to the pointer array to queue it up for rendering on the page by the
     * SlideDeckPlugin::pointer_script() method.
     * 
     * @param string $pointer_id The ID to identify the pointer by
     * @param string $selector The jQuery JavaScript selector for the element to attach the pointer to
     * @param array $args Optional argument overrides (position and the like)
     * 
     * @uses is_rtl()
     * @uses wp_parse_args()
     */
    final function create( $pointer_id, $selector, $content, $args = array() ) {
        $pointer = array(
            'id' => "{$this->namespace}_{$pointer_id}",
            'selector' => $selector,
            'content' => $content,
            'position' => array(
                'edge' => ( is_rtl() ? "right" : "left" ),
                'align' => "left"
            )
        );
        
        $pointer = wp_parse_args( $args, $pointer );
        
        $this->pointers["{$this->namespace}_{$pointer_id}"] = $pointer;
    }
    
    function pointer_lens_management() {
        global $SlideDeckPlugin;
        
        $content = '<h3 class="' . $this->namespace . '">' . esc_js( __( "New Feature: Lens Management", $this->namespace ) ) . '</h3>';
        $content.= '<p>' . esc_js( __( "Skins are now Lenses and they're more powerful than ever! We've made managing, editing, uploading and creating your own lenses easier than ever! Now you can edit lenses right from the WordPress control panel, make copies of stock lenses and upload new lenses for all your SlideDecks.", $this->namespace ) ) . '</p>';
        
        $this->create( "lens-management", '#' . $SlideDeckPlugin->menu['manage'] . ' a[href$="' . SLIDEDECK2_BASENAME . '/lenses"]', $content );
    }
    
    /**
     * Output admin pointers
     * 
     * Loops through the pointer JavaScript set by admin panel pages in the footer of 
     * the admin page.
     * 
     * @uses get_user_meta()
     * @uses get_current_user_id()
     */
    final function render() {
        $pointers = $this->pointers;
        $namespace = "slidedeck";
        
        // Get dismissed pointers
        $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
        foreach( $dismissed as $dismiss )
            unset( $pointers[$dismiss] );
        
        if( empty( $pointers ) )
            return false;
        
        include( SLIDEDECK2_DIRNAME . '/views/elements/_pointers.php' );
    }
}
