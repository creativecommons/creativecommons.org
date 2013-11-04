<?php
/**
 * SlideDeck Administrative Options
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
<div class="wrap" id="slidedeck_lens_management">
    
    <?php echo $this->upgrade_button('lenses'); ?>
    
    <div class="slidedeck-header">
        <h1>SlideDeck Lenses</h1>
        
        <a class="button<?php if( $is_writable->valid !== true ) echo ' disabled' ?>" href="<?php echo slidedeck2_action( '/lenses&action=add' ); ?>">Upload Lens</a>
        <?php do_action( "{$namespace}_lens_management_header", $is_writable ); ?>
    </div>
    
    <div id="slidedeck-lenses-wrapper">
        
        <?php slidedeck2_flash( 5000 ); ?>
        
        <?php if( $is_writable->valid !== true ): ?>
            <div class="slidedeck-flash-message error"><p><?php _e( $is_writable->error, $namespace ); ?></p></div>
        <?php endif; ?>
        
        <?php if( !empty( $lenses ) ): ?>
                
            <div id="slidedeck-lenses" class="lenses clearfix">
                
                <?php foreach( $lenses as &$lens ): ?>
                    
                    <?php include( SLIDEDECK2_DIRNAME . '/views/elements/_lens.php' ); ?>
                
                <?php endforeach; ?>
                
                <?php do_action( "{$this->namespace}_manage_lenses_after_lenses", $lenses ); ?>
                
            </div>
            
        <?php endif; ?>
        
    </div>
    
</div>
