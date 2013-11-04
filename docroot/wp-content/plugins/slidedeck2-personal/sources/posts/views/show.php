<?php
/**
 * SlideDeck Posts Content Source
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

<div id="content-source-posts">
    <input type="hidden" name="source[]" value="<?php echo $this->name; ?>" />
    <div class="inner">
        <div class="left">
            <ul class="content-source-fields">
                <li>
                    <?php slidedeck2_html_input( 'options[postsImageSource]', $slidedeck['options']['postsImageSource'], $this->options_model['Setup']['postsImageSource'] ); ?>
                </li>
                <li id="preferred-image-size-row" style="<?php echo $show_image_size ? '' : 'display:none;' ;?>">
                    <?php slidedeck2_html_input( 'options[preferredImageSize]', $slidedeck['options']['preferredImageSize'], $this->options_model['Setup']['preferredImageSize'] ); ?>
                </li>
                <li>
                    <?php slidedeck2_html_input( 'options[post_type]', $slidedeck['options']['post_type'], array( 'type' => 'select', 'label' => __( "Post Type", $this->namespace ), 'attr' => array( 'class' => 'fancy' ), 'values' => $post_types ) ); ?>
                </li>
                <li>
                    <?php slidedeck2_html_input( 'options[post_type_sort]', $slidedeck['options']['post_type_sort'], array( 'type' => 'select', 'label' => __( "Which Posts? (order by)", $this->namespace ), 'attr' => array( 'class' => 'fancy' ), 'values' => $post_type_sorts ) ); ?>
                </li>
                <li>
                    <?php slidedeck2_html_input( 'options[use-custom-post-excerpt]', $slidedeck['options']['use-custom-post-excerpt'], $this->options_model['Setup']['use-custom-post-excerpt'] ); ?>
                </li>
                <li>
                    <?php slidedeck2_html_input( 'options[filter_by_tax]', $slidedeck['options']['filter_by_tax'], array( 'type' => 'radio', 'label' => __( "Filter by Taxonomy?", $this->namespace ), 'attr' => array( 'class' => 'fancy' ) ) ); ?>
                </li>
            </ul>
            <div class="slidedeck-ajax-loading" style="display:none;"><?php _e( "Loading your taxonomies...", $this->namespace ); ?></div>
            <div id="slidedeck-filters"><?php echo $this->available_filters( $slidedeck['options']['post_type'], $slidedeck ); ?></div>
        </div>
        <div class="right" style="<?php echo ( $slidedeck['options']['filter_by_tax'] == '1' ) ? '' : 'display:none'; ?>">
            <div class="trailblazer" style="display:none;">
                <p><?php _e( "Toggle one or more taxonomies in the left column to see the selection boxes appear here.", $this->namespace ); ?></p>
            </div>
            <div id="any-or-all-taxonomies" style="display:none;"><?php slidedeck2_html_input( "options[query_any_all]", $slidedeck['options']['query_any_all'], $this->options_model['Setup']['query_any_all'] ); ?></div>
            <div id="poststuff">
                <div id="slidedeck-terms">
                    <?php 
                        // Loop through the selected taxonomies and output the current terms.
                        if( isset($slidedeck['options']['taxonomies']) && !empty( $slidedeck['options']['taxonomies'] ) ){
	                        foreach( (array) $slidedeck['options']['taxonomies'] as $taxonomy => $value ){
	                        	if( isset( $value ) && !empty( $value ) )
	                            	echo $this->available_terms( $slidedeck['options']['post_type'], $slidedeck, $taxonomy );
	                        }
                        }
                    ?>
                </div>
            </div>
            <div class="slidedeck-ajax-loading" style="display:none;"><?php _e( "Loading terms chooser...", $this->namespace ); ?></div>
        </div>
    </div>
</div>