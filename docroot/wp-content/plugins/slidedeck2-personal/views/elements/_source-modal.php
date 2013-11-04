<?php
/**
 * SlideDeck Source Modal
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
<h3><?php _e( $title, $namespace ); ?></h3>

<form action="<?php echo admin_url( 'admin.php' ); ?>" method="GET">
    
    <?php if( $action != "slidedeck_add_source" ): ?>
        <p><?php _e( "You can add additional sources later.", $namespace ); ?></p>
    <?php endif; ?>
    
    <input type="hidden" name="page" value="<?php echo SLIDEDECK2_BASENAME; ?>" />
    <input type="hidden" name="action" value="<?php echo $action; ?>" />
    <?php if( $action == "{$this->namespace}_add_source" ): ?>
        <input type="hidden" name="slidedeck" value="<?php echo $slidedeck_id; ?>" />
        <?php wp_nonce_field( 'slidedeck-add-source' ); ?>
    <?php endif; ?>
    
    <?php do_action( "{$namespace}_source_modal_before_sources", $sources, $disabled_sources, $action, $slidedeck_id, $title ); ?>
    
    <ul class="sources">
        <?php foreach( $sources as &$source ): ?>
            
            <li class="source<?php if( in_array( $source->name, $disabled_sources ) ) echo ' disabled'; ?>">
                <label>
                    <span class="thumbnail">
                        <img src="<?php slidedeck2_source_icon_url( $source ); ?>" alt="<?php echo $source->label; ?>" />
                    </span>
                    <?php echo $source->label; ?>
                    <input type="radio" name="source" value="<?php echo $source->name; ?>"<?php if( in_array( $source->name, $disabled_sources ) ) echo ' disabled="disabled"'; ?> />
                </label>
            </li>
            
        <?php endforeach; ?>
    </ul>
    
    <?php do_action( "{$namespace}_source_modal_after_sources", $sources, $disabled_sources, $action, $slidedeck_id, $title ); ?>
    
</form>
