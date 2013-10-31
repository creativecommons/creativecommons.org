<?php
/**
 * SlideDeck 2 Widget Class
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
class SlideDeck2Widget extends WP_Widget {
    var $namespace = "slidedeck2";
    
    /**
     * Constructor function for Class
     * 
     * @uses WP_Widget()
     */
    function SlideDeck2Widget() {
        $widget_options = array(
            'classname' => $this->namespace . '_widget',
            'description' => 'Add SlideDeck 2 SlideDecks to your widget areas'
        );
        $this->WP_Widget( $this->namespace . '_widget', 'SlideDeck 2 Widget', $widget_options );
    }
    
    /**
     * Initialization function to register widget
     * 
     * @uses register_widget()
     */
    function init() {
        register_widget( "SlideDeck2Widget" );
    }
    
    /**
     * Form function for the widget control panel
     * 
     * @param object $instance Option data for this widget instance
     * 
     * @uses slidedeck_load()
     * @uses slidedeck_dir()
     */
    function form( $instance ) {
        global $SlideDeckPlugin;
        
        $instance = wp_parse_args( (array) $instance, array(
            'slidedeck_id' => "",
            $this->namespace . '_deploy_as_iframe' => false,
            $this->namespace . '_use_ress' => false,
            $this->namespace . '_proportional' => true
        ) );
        
        $slidedeck_id = strip_tags( $instance['slidedeck_id'] );
        $deploy_as_iframe = $instance[$this->namespace . '_deploy_as_iframe'];
        $use_ress = $instance[$this->namespace . '_use_ress'];
        $proportional = $instance[$this->namespace . '_proportional'];
        $title = strip_tags( $instance[ $this->namespace . '_title'] );
        $before_deck = $instance[ $this->namespace . '_before_deck'];
        $after_deck = $instance[ $this->namespace . '_after_deck'];
        
        
        $namespace = $this->namespace;
        $slidedecks = $SlideDeckPlugin->SlideDeck->get( null, 'post_title', 'ASC', 'publish' );
        
        include( SLIDEDECK2_DIRNAME . '/views/elements/_widget-form.php' );
    }
    
    /**
     * Update processing function for saving widget instance settings
     * 
     * @param object $new_instance Option data submitted for this widget instance
     * @param object $old_instance Existing option data for this widget instance
     * 
     * @return object $instance Updated option data
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        
        $instance['slidedeck_id'] = $new_instance['slidedeck_id'];
        $instance[$this->namespace . '_deploy_as_iframe'] = isset( $new_instance[$this->namespace . '_deploy_as_iframe'] );
        $instance[$this->namespace . '_use_ress'] = isset( $new_instance[$this->namespace . '_use_ress'] );
        $instance[$this->namespace . '_proportional'] = isset( $new_instance[$this->namespace . '_proportional'] );
        $instance[$this->namespace . '_title'] = $new_instance[ $this->namespace . '_title'];
        $instance[$this->namespace . '_before_deck'] = $new_instance[ $this->namespace . '_before_deck'];
        $instance[$this->namespace . '_after_deck'] = $new_instance[ $this->namespace . '_after_deck'];
        
        return $instance;
    }
    
    /**
     * Widget output function
     * 
     * Loads a SlideDeck instance based off the widget settings specified by the user
     * 
     * @param object $args Extra arguments provided for this widget output see documentation at
     *                     http://codex.wordpress.org/Function_Reference/the_widget
     * @param object $instance Option data for this widget instance
     * 
     * @uses slidedeck()
     */
    function widget( $args, $instance ) {
        global $SlideDeckPlugin;
        global $slidedeck_footer_scripts;
        
        extract( $args, EXTR_SKIP );
        $title = isset( $instance[$this->namespace . '_title'] ) ? $instance[$this->namespace . '_title'] : '';
        $before_deck = isset( $instance[$this->namespace . '_before_deck'] ) ? $instance[$this->namespace . '_before_deck'] : '';
        $after_deck = isset( $instance[$this->namespace . '_after_deck'] ) ? $instance[$this->namespace . '_after_deck'] : '';
        
        echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;

        
        $shortcode = "[SlideDeck2 id={$instance['slidedeck_id']}";
        if( $instance[$this->namespace . '_deploy_as_iframe'] ) $shortcode.= " iframe=1";
        if( $instance[$this->namespace . '_use_ress'] ) {
            $shortcode.= " ress=1";
            
            // If this widget makes this page have a RESS deck...
            $SlideDeckPlugin->page_has_ress_deck = true;
        }
        
		/**
		 * The proportional option is negative only. Proportional
		 * is default, and this option being false only overrides it.
		 */
        if( !$instance[$this->namespace . '_proportional'] ) {
        	$shortcode.= " proportional=false";
        }
        
        $shortcode.= "]";
        
        if ( $before_deck )
            echo '<div class="sd2-before">' . $before_deck . '</div>';

        echo do_shortcode( $shortcode );
        
        if ( $after_deck )
            echo '<div class="sd2-after">' . $after_deck . '</div>';

        echo $after_widget;
    }
}
add_action( 'widgets_init', array( 'SlideDeck2Widget', 'init' ) );
