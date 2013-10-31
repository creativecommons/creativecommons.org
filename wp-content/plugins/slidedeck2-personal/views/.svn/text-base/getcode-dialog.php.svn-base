<?php
/**
 * SlideDeck Get Code Dialog
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
<div class="slidedeck-header">
    <h1><?php _e( "Three Simple Ways to Publish Your SlideDeck", $namespace ); ?></h1>
</div>
<div class="wrapper">
    <div class="inner">
        
        <div id="slidedeck-publish-method-insert" class="publish-method">
            <h3>Method 1</h3>
            <p><?php _e( "Insert into existing" . ( current_user_can( 'edit_pages' ) ? " pages" : "" ) . " posts just like you would an image", $namespace ); ?></p>
            <div class="action">
                <img src="<?php echo SLIDEDECK2_URLPATH; ?>/images/upload-insert-screenshot.png" alt="<?php _e( "Insert SlideDeck into pages or posts", $namespace ); ?>" />
            </div>
        </div>
        
        <div id="slidedeck-publish-method-launch-new-post" class="publish-method">
            <h3>Method 2</h3>
            <p><?php _e( "Click to launch a new " . ( current_user_can( 'edit_pages' ) ? "page or " : "" ) . "post with your new SlideDeck", $namespace ); ?></p>
            
            <div class="action">
                <?php if( current_user_can( 'edit_pages' ) ): ?>
                    <a class="slidedeck-button-primary" href="<?php echo admin_url( 'admin-ajax.php?action=slidedeck_create_new_with_slidedeck&post_type=page&slidedeck=' . $slidedeck_id ); ?>"><?php _e( "New Page", $namespace ); ?></a>
                    <span><?php _e( "or", $namespace ); ?></span>
                <?php endif;?>
                <a class="slidedeck-button-primary" href="<?php echo admin_url( 'admin-ajax.php?action=slidedeck_create_new_with_slidedeck&post_type=post&slidedeck=' . $slidedeck_id ); ?>"><?php _e( "New Post", $namespace ); ?></a>
            </div>
        </div>
        
        <div id="slidedeck-publish-method-copy-paste" class="publish-method">
            <h3>Method 3</h3>
            <p><?php _e( "Copy &amp; Paste this shortcode into your post" . ( current_user_can( 'edit_pages' ) ? " or page" : "" ), $namespace ); ?></p>
            
            <div class="action">
                <input type="text" value="<?php echo slidedeck2_get_shortcode( $slidedeck_id ); ?>" readonly="readonly"<?php if( $iframe_by_default ) echo ' style="font-size:12px;"'; ?> />
                <a href="#" class="slidedeck-copy-to-clipboard"><?php _e( "Copy Shortcode to Clipboard", $namespace ); ?> <img src="<?php echo SLIDEDECK2_URLPATH; ?>/images/icon-clipboard.png" alt=""></a>
                <span class="complete-message" style="display:none;"><?php _e( "Copied Successfully!", $namespace ); ?></span>
            </div>
        </div>
        
    </div>
    <div id="get-code-close" class="inner">
        <a class="close" href="#close"><?php _e( "Close", $namespace ); ?></a>
    </div>
</div>