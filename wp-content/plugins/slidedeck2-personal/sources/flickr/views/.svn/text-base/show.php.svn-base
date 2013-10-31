<?php
/**
 * SlideDeck Flickr Content Source
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

$tags_hidden = ( $slidedeck['options']['flickr_user_or_group'] == 'group' ) ? ' style="display: none;"' : '';
$favorties_hidden = ( $slidedeck['options']['flickr_user_or_group'] == 'group' ) ? ' style="display: none;"' : '';

?>
<div id="content-source-flickr">
    <input type="hidden" name="source[]" value="<?php echo $this->name; ?>" />
    <div class="inner">
        <ul class="content-source-fields">
            <li class="favorites"<?php echo $favorties_hidden; ?>>
                <?php slidedeck2_html_input( 'options[flickr_recent_or_favorites]', $slidedeck['options']['flickr_recent_or_favorites'], array( 'type' => 'radio', 'attr' => array( 'class' => 'fancy' ), 'label' => __( 'Photos to get', $this->namespace ), 'values' => array(
                    'recent' => __( 'Recent', $this->namespace ),
                    'favorites' => __( 'Favorites', $this->namespace )
                ) ) ); ?>
            </li>
            <li>
                <?php slidedeck2_html_input( 'options[flickr_user_or_group]', $slidedeck['options']['flickr_user_or_group'], array( 'type' => 'radio', 'attr' => array( 'class' => 'fancy' ), 'label' => __( 'User or Group?', $this->namespace ), 'values' => array(
                    'user' => __( 'User', $this->namespace ),
                    'group' => __( 'Group', $this->namespace )
                ) ) ); ?>
            </li>
            <li>
                <?php 
                $tooltip = sprintf(__('This is your Flickr ID, not username. Check here for yours: %1$sidGettr.com%2$s.'), "<a href='http://idgettr.com/' target='_blank'>", '</a>');
                slidedeck2_html_input( 'options[flickr_userid]', $slidedeck['options']['flickr_userid'], array( 'label' => __( "User/Group ID", $this->namespace ), 'attr' => array( 'size' => 20, 'maxlength' => 255 ), 'required' => true ) );
                ?>
                <em><?php echo $tooltip; ?></em>
            </li>
            <li<?php echo $tags_hidden; ?>>
                <?php slidedeck2_html_input( 'options[flickr_tags_mode]', $slidedeck['options']['flickr_tags_mode'], array( 'type' => 'radio', 'attr' => array( 'class' => 'fancy' ), 'label' => __( 'Tag mode: ', $this->namespace ), 'values' => array(
                    'any' => __( 'Any of these', $this->namespace ),
                    'all' => __( 'All of these', $this->namespace )
                ) ) ); ?>
            </li>
            <li class="add-button-li"<?php echo $tags_hidden; ?>>
                <div class="add-button-wrapper flickr">
                    <?php 
                    $tooltip = __('Enter one or more tags separated by commas.') . "<br />" . __('Tags can only be used with recent photos.');
                    slidedeck2_html_input( 'flickr-add-tag-field', '', array( 'label' => __( "Flickr Tags", $this->namespace ) . '<span class="tooltip" title="' . $tooltip . '"></span>', 'attr' => array( 'size' => 10, 'maxlength' => 255 ) ) );
                    ?>
                    <a class="flickr-tag-add add-button" href="#add"><?php _e( "Add", $this->namespace ); ?></a>
                </div>
                <div id="flickr-tags-wrapper"><?php echo $tags_html; ?></div>
            </li>
        </ul>
    </div>
</div>