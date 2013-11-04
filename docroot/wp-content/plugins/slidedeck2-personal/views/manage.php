<?php
/**
 * Overview list of SlideDecks
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

<?php slidedeck2_flash(); ?>

<div class="slidedeck-wrapper">
    <div class="wrap" id="slidedeck-overview">
        <?php if( isset( $_GET['msg_deleted'] ) ): ?>
            <div id="slidedeck-flash-message" class="updated" style="max-width:964px;"><p><?php _e( "SlideDeck successfully deleted!", $namespace ); ?></p></div>
            <script type="text/javascript">(function($){if(typeof($)!="undefined"){$(document).ready(function(){setTimeout(function(){$("#slidedeck-flash-message").fadeOut("slow");},5000);});}})(jQuery);</script>
        <?php endif; ?>
        
    	<div id="slidedeck-types">
    	    <?php echo $this->upgrade_button('manage'); ?>
        	<h1><?php _e( "Manage SlideDeck 2", $namespace ); ?></h1>
        	<?php
        	   $create_dynamic_slidedeck_block_html = apply_filters( "{$namespace}_create_dynamic_slidedeck_block", "" );
               echo $create_dynamic_slidedeck_block_html;
			   
        	   $create_custom_slidedeck_block_html = apply_filters( "{$namespace}_create_custom_slidedeck_block", "" );
               echo $create_custom_slidedeck_block_html;
        	?>
    	</div>
	    
	    <div id="slidedeck-table">
	        <?php if( !empty( $slidedecks ) ): ?>
    	        <form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" id="slidedeck-table-sort">
    	            <fieldset>
        	            <input type="hidden" value="<?php echo $namespace; ?>_sort_manage_table" name="action" />
        	            <?php wp_nonce_field( "slidedeck-sort-manage-table" ); ?>
        	            
        	            <label for="slidedeck-table-sort-select"><?php _e( "Sort By:", $namespace ); ?></label> 
        	            <select name="orderby" id="slidedeck-table-sort-select" class="fancy">
        	                <?php foreach( $order_options as $value => $label ): ?>
        	                    <option value="<?php echo $value; ?>"<?php if( $value == $orderby ) echo ' selected="selected"'; ?>><?php _e( $label, $namespace ); ?></option>
                            <?php endforeach; ?>
        	            </select>
    	            </fieldset>
    	        </form>
	        <?php endif; ?>
	        
	        <div class="float-wrapper">
    	        <div class="left">
                	<?php include( SLIDEDECK2_DIRNAME . '/views/elements/_manage-table.php' ); ?>
    	        </div>
    	        <div class="right">
    	            <div class="right-inner">
        	            <div id="manage-iab" class="iab">
                            <iframe height="100%" frameborder="0" scrolling="no" width="100%" allowtransparency="true" src="<?php echo $sidebar_ad_url; ?>"></iframe>
        	            </div>
        	            <div id="slidedeck-support-questions" class="right-column-module">
            	            <h4><?php _e( "Have questions?", $namespace ); ?></h4>
            	            <p><?php _e( "See if there are any solutions in our support section.", $namespace ); ?></p>
            	            <a href="<?php admin_url( 'admin.php' ); ?>?page=slidedeck2.php/support" class="button slidedeck-noisy-button" target="_blank"><span><?php _e( "Get Support" , $namespace ); ?></span></a>
        	            </div>
                        
                        <?php do_action( "{$namespace}_manage_sidebar_bottom" ); ?>
    	            </div>
    	        </div>
	        </div>
	    </div>
	    
	    <div id="slidedeck-manage-footer">
	        <div class="float-wrapper">
	            <div class="left">
	                <?php // TODO: Remove width: 100%; ?>
	                <div class="leftLeft" style="width: 100%;">
                        <div class="module news">
                            <h3><?php _e( "News and Updates", $namespace ); ?></h3>
                            <div id="slidedeck-blog-rss-feed">
                                <span class="loading"><?php _e( "Fetching RSS Feeds...", $namespace ) ?></span>
                            </div>
                        </div>
	                </div>
	                <?php // TODO: Remove display:none; ?>
	                <div class="leftRight" style="display:none;">
                        <div class="module resources">
                            <h3><?php _e( "Resource Center", $namespace ); ?></h3>
                            <ul>
                                <li>
                                    <div class="icon screencast"></div>
                                    <a href="#">Create an image gallery with Instagram</a>
                                </li>
                                <li>
                                    <div class="icon document"></div>
                                    <a href="#">How to change your background color using CSS</a>
                                </li>
                                <li>
                                    <div class="icon screencast"></div>
                                    <a href="#">Create a video slider from a YouTube playlist</a>
                                </li>
                            </ul>
                        </div>
	                </div>
	            </div>
	            <div class="right">
	                <div class="module slidedeck tweets">
	                    <h3><?php _e( "Latest Tweets", $namespace ); ?></h3>
	                    <div id="slidedeck-latest-tweets">
                            <span class="loading"><?php _e( "Fetching Latest Tweets...", $namespace ) ?></span>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div id="dt-footer-logo">
                <span id="a-product-of"><?php _e( "A product of", $namespace ); ?></span>
                <a href="http://www.dtelepathy.com" target="_blank"><img border="0" class="logo" src="<?php echo SLIDEDECK2_URLPATH; ?>/images/dt-logo.png" alt="<?php _e( "digital-telepathy", $namespace ); ?>" /></a>
                <p>
                    <a href="http://www.dtelepathy.com" target="_blank"><span id="orange-tag"><?php _e( "UX Design Studio", $namespace ); ?></span></a>
                </p>
	        </div>
	    </div>
    	
    </div>
</div>