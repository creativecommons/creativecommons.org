<?php
/**
 * SlideDeck Covers Modal
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
    <h1><?php _e( "Configure Covers", $namespace ); ?></h1>
</div>

<div id="slidedeck-covers-swap">
    <span class="label"><?php _e( "Select Cover", $namespace ); ?></span>
    <span class="toggles">
        <a href="#front" class="toggle toggle-front selected"><?php _e( "Front", $namespace ); ?></a><a href="#back" class="toggle toggle-back"><?php _e( "Back", $namespace ); ?></a>
    </span>
</div>

<form action="" method="post">
    
    <div id="slidedeck-covers-preview-wrapper" style="width:<?php echo intval( $dimensions['outer_width'] * $scaleRatio ); ?>px;height:<?php echo intval( $dimensions['outer_height'] * $scaleRatio ); ?>px">
        <span class="mask"></span>
        <div id="slidedeck-covers-preview" class="slidedeck-frame slidedeck-cover-easing-back slidedeck-cover-style-<?php echo $cover['cover_style']; ?><?php if( !empty( $cover['variation'] ) ) echo ' slidedeck-cover-' . $cover['variation']; ?><?php if( $cover['peek'] ) echo ' slidedeck-cover-peek'; ?> sd2-<?php echo $size_class; ?>" style="width:<?php echo $dimensions['outer_width']; ?>px;height:<?php echo $dimensions['outer_height']; ?>px;-webkit-transform: scale(<?php echo $scaleRatio; ?>);-webkit-transform-origin: 0 0;-moz-transform: scale(<?php echo $scaleRatio; ?>);-moz-transform-origin: 0 0;-o-transform: scale(<?php echo $scaleRatio; ?>);-o-transform-origin: 0 0;-ms-transform: scale(<?php echo $scaleRatio; ?>);-ms-transform-origin: 0 0;transform: scale(<?php echo $scaleRatio; ?>);transform-origin: 0 0;">
            <?php echo $this->Cover->render( $slidedeck_id, 'front' ); ?>
            <?php echo $this->Cover->render( $slidedeck_id, 'back' ); ?>
            <?php echo do_shortcode( "[SlideDeck2 id=$slidedeck_id iframe=1 nocovers=1]" ); ?>
        </div>
    </div>
    
    <fieldset>
        
        <div class="inner clearfix">
            
            <ul class="options-list front-options">
                <?php foreach( $front_options as $option ): ?>
                    <li><span class="inner"><?php slidedeck2_html_input( $option, $cover[$option], $cover_options_model[$option] ); ?></span></li>
                <?php endforeach; ?>
            </ul>
            
            <ul class="options-list back-options" style="height:0;">
                <?php foreach( $back_options as $option ): ?>
                    <li><span class="inner"><?php slidedeck2_html_input( $option, $cover[$option], $cover_options_model[$option] ); ?></span></li>
                <?php endforeach; ?>
            </ul>
            
        </div>
        
    </fieldset>
    
    <fieldset>
        
        <?php wp_nonce_field( "{$this->namespace}-cover-update" ); ?>
        <input type="hidden" name="slidedeck" value="<?php echo $slidedeck_id; ?>" />
        
        <div class="inner clearfix">
            
            <ul class="options-list global-options">
                <?php foreach( $global_options as $option ): ?>
                    <li<?php if( $option == "variation" && empty( $variations[$cover['cover_style']] ) ) echo ' style="display:none;"'; ?>><span class="inner"><?php slidedeck2_html_input( $option, $cover[$option], $cover_options_model[$option] ); ?></span></li>
                <?php endforeach; ?>
            </ul>
            
        </div>
    
    </fieldset>
    
    <p class="submit-row">
        <a href="#cancel" class="cancel-modal">Cancel</a>
        <input type="submit" class="button button-primary" value="Save Changes" />
    </p>
    
    <script type="text/javascript">
        SlideDeckPlugin.CoversEditor.fonts = <?php echo json_encode( $slidedeck_fonts ); ?>;
        SlideDeckPlugin.CoversEditor.variations = <?php echo json_encode( $variations ); ?>;
    </script>
    
</form>
