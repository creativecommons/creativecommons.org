<?php
/**
 * SlideDeck Gplus Content Source
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

<div id="content-source-glpusimages">
    <input type="hidden" name="source[]" value="<?php echo $this->name; ?>" />
    <div class="inner">
        <ul class="content-source-fields">
            <li>
            	<?php $tooltip = sprintf( __( 'This is either the first part of your Gmail address *****@gmail.com %1$s or the number in the URL when you visit: %2$sYour Google+ Profile%3$s.', $this->namespace ), '<br />', "<a href='https://plus.google.com/me' target='_blank'>", '</a>' ); ?>
			    <?php slidedeck2_html_input( 'options[gplus_user_id]', $slidedeck['options']['gplus_user_id'], array( 'label' => __( "Google+ User ID", $this->namespace ) . '<span class="tooltip" title="' . $tooltip . '"></span>', 'attr' => array( 'size' => 20, 'maxlength' => 255 ), 'required' => true ) ); ?>
				<a class="gplus-images-ajax-update button" href="#update"><?php _e( "Update", $this->namespace ); ?></a>
            </li>
            <li>
				<?php if( $albums_select ): ?>
				<div id="gplus-user-albums" class="select-wrapper">
				    <?php echo $albums_select; ?>
				</div>
				<?php endif; ?>
            </li>
            <li class="gplusphotos max-image-size">
            	<?php 
            		global $SlideDeckPlugin;
            		$tooltip = sprintf( __( 'Google Allows %1$s to ask for images no larger than the size shown here. Choose a size appropriate for the deck you\'re making.', $this->namespace ), $SlideDeckPlugin->friendly_name );
            	?>
            	<label class="label" for="options-gplus_max_image_size"><?php _e( "Image Size", $this->namespace ); echo '<span class="tooltip" title="' . $tooltip . '"></span>' ?></label>
            	<div class="jqueryui-slider-wrapper">
            		<div id="gplus-image-size-slider" class="image-size-slider"></div>
            		<span class="ui-slider-value gplus-image-size-slider-value"></span>
            	</div>
                <?php slidedeck2_html_input( 'options[gplus_max_image_size]', $slidedeck['options']['gplus_max_image_size'], array( 'type' => 'hidden', 'attr' => array( 'class' => 'feed-cache-duration', 'size' => 5, 'maxlength' => 5 ) ) ); ?>
            </li>
        </ul>
    </div>
</div>