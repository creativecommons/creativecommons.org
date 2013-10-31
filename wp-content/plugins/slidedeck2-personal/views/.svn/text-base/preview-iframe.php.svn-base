<?php
/**
 * Preview SlideDeck iframe template
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
<!doctype html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $slidedeck['title']; ?></title>
        
        <script type="text/javascript">
            var SlideDeckLens={};
            var slideDeck2Version = '<?php echo SLIDEDECK2_VERSION; ?>';
            var slideDeck2Distribution = '<?php echo strtolower( SLIDEDECK2_LICENSE ); ?>';
            var slideDeck2CurrentSlide = null;
        </script>
        
        <?php
            foreach( $scripts as $script ) {
                $src = $wp_scripts->registered[$script]->src;
                if ( !preg_match( '|^https?://|', $src ) && !( $content_url && 0 === strpos( $src, $content_url ) ) ) {
                    $src = $base_url . $src;
                }
                
                if( $preview && $script == "slidedeck-library-js" ) {
                    $src.= "?noping";
                }
                
				/**
				 * This is an effort to reduce the number of requests that this iFrame needs to make.
				 */
				if( preg_match( '#/slidedeck([^/]+/js/)#', $wp_scripts->registered[$script]->src ) ){
					// If the script is in our JS folder, echo it instead of adding a script tag with a src.
					$parts = explode( '/js/', $wp_scripts->registered[$script]->src );
					echo "\n" . '<script type="text/javascript">' . "\n";
					echo "// {$wp_scripts->registered[$script]->src}" . "\n";
					include( SLIDEDECK2_DIRNAME . '/js/' . end( $parts ) );
					echo "\n" . '</script>' . "\n";
				}else{
					// If the script is not in our plugin folder, then include it regularly.
	                echo '<script type="text/javascript" src="' . $src . ( strpos( $src, "?" ) !== false ? "&" : "?" ) . "v=" . $wp_scripts->registered[$script]->ver . '"></script>';
				}
            }
        ?>
        
        <link rel="stylesheet" type="text/css" href="<?php echo $wp_styles->registered['slidedeck']->src . ( strpos( $wp_styles->registered['slidedeck']->src, "?" ) !== false ? "&" : "?" ) . "v=" . $wp_styles->registered['slidedeck']->ver; ?>" />
        
        <link rel="stylesheet" type="text/css" href="<?php echo $lens['url']; ?>?v=<?php echo isset( $lens['meta']['version'] ) && !empty( $lens['meta']['version'] ) ? $lens['meta']['version'] : SLIDEDECK2_VERSION; ?>" />
        
        <?php echo $this->Lens->get_css( $lens ); ?>
        
        <style type="text/css">
            body, html {
                margin: 0;
                padding: 0;
                overflow: hidden;
                width: 100%;
                height: 100%;
            }
            #mask {
                position: absolute;
                z-index: 1;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background: #f2f2f2;
                -webkit-opacity: 0;
                -moz-opacity: 0;
                -o-opacity: 0;
                opacity: 0;
                filter: Alpha(opacity=0);
                -ms-filter: "Alpha(opacity=0)";
                -webkit-transition: opacity 0.35s;
                -moz-transition: opacity 0.35s;
                -o-transition: opacity 0.35s;
                transition: opacity 0.35s;
                font-size: 10px;
            }
            #mask.visible {
                z-index: 99999;
                left: 0;
                -webkit-opacity: 1;
                -moz-opacity: 1;
                -o-opacity: 1;
                opacity: 1;
                filter: Alpha(opacity=100);
                -ms-filter: "Alpha(opacity=100)";
            }
            #mask .mask-loading-wrapper {
            	position: absolute;
            	top: 0;
            	right: 0;
            	bottom: 0;
            	left: 0;
            	width: 100%;
            	height: 100%;
            }
            #mask .mask-loading-title {
            	position: absolute;
            	display: block;
            	top: 50%;
            	left: 0;
            	right: 0;
            	margin: -7.9em 0 0 0;
            	text-indent: -999em;
            	width: 100%;
            	line-height: 5.3em;
            	max-height: 53px;
            	font-size: 1em;
            	background: url('<?php echo SLIDEDECK2_URLPATH; ?>/images/loading-title.png') center center no-repeat;
            	background-size: contain;
            }
            #mask .mask-loading-copy {
				position: absolute;
				top: 50%;
				left: 50%;
				text-align: center;
				margin: 0 0 0 -8.675em;
				text-align: center;
				width: 17.35em;
				background: url('<?php echo SLIDEDECK2_URLPATH; ?>/images/border-loading.png') center 0 no-repeat;
				background-size: contain;
				padding: 1em 0 0;
				font: italic 2em/1.6em Georigia, serif;
				color: #aeaeae;
				text-shadow: 0 1px 1px #fff;
            }
            #mask .mask-loading-wrapper img {
            	position: absolute;
            	top: 50%;
            	left: 50%;
            	width: 3.1em;
            	height: 3.1em;
            	margin: 0;
            	max-width: 31px;
            	max-height: 31px;
            	margin: -1.55em 0 0 -1.55em;
            }
            .slidedeck-frame { z-index: 2; }
            
            #status {
            	position: fixed;
            	top: 0;
            	right: 0;
            	padding: 5px;
            	background: #fff;
            	font-size: 11px;
            	color: #333;
            	z-index: 999999;
            	font-family: sans-serif;
				-webkit-border-bottom-left-radius: 5px;
				-moz-border-radius-bottomleft: 5px;
				border-bottom-left-radius: 5px;
				-webkit-box-shadow: 0 0 10px rgba(0,0,0,0.75);
				-moz-box-shadow: 0 0 10px rgba(0,0,0,0.75);
				box-shadow: 0 0 10px rgba(0,0,0,0.75);
				border: 1px solid #ccc;
                -webkit-opacity: 0.25;
                -moz-opacity: 0.25;
                opacity: 0.25;
                -webkit-transition: opacity 0.25s;
                -moz-transition: opacity 0.25s;
                transition: opacity 0.25s;
	        }
            body:hover #status {
                -webkit-opacity: 1;
                -moz-opacity: 1;
                opacity: 1;
            }
            
	        .button {
                text-decoration: none;
                font-size: 12px !important;
                line-height: 13px;
                padding: 3px 8px;
                cursor: pointer;
                border-width: 1px;
                border-style: solid;
                -webkit-border-radius: 11px;
                border-radius: 11px;
                -moz-box-sizing: content-box;
                -webkit-box-sizing: content-box;
                box-sizing: content-box;
                border-color: #bbb;
                color: #464646;
                background: #f2f2f2 url('<?php echo admin_url(); ?>/images/white-grad.png') repeat-x scroll left top;
                text-shadow: rgba(255,255,255,1) 0 1px 0;
                margin-right: 5px;
            }
            .button:hover {
                color: #000;
                border-color: #666;
            }
            .button:active {
                background: #eee url('<?php echo admin_url(); ?>/images/white-grad-active.png') repeat-x scroll left top;
            }
        </style>
        
        <script type="text/javascript">
        	(function($){
        		$(document).ready(function(){
    				var $mask = $('#mask'),
    					$window = $(window);
					var $wrapper = $mask.find('.mask-loading-wrapper');
    				
        			$window.resize(function(){
				        var width = $window.width(),
				            height = $window.height();
				        
				        $wrapper.css('font-size', (Math.round(Math.min((width/347)*1000, 1139))/1000) + "em");
        			});
        			
        			$mask.removeClass('visible');
        		});
        	})(jQuery);
        </script>
        
        <?php do_action( "{$namespace}_iframe_header", $slidedeck, $preview ); ?>
    </head>
    <body>
    	<?php if( SLIDEDECK2_ENVIRONMENT != "production" && $preview ): ?>
    		<span id="status">
    		    <button class="button" onclick="parent.SlideDeckPreview.ajaxUpdate();">Refresh</button> 
    		    <strong>Refreshed:</strong> <?php echo date( "Y-m-d H:i:s"); ?>
		    </span>
		<?php endif; ?>
        <div id="mask" class="visible">
        	<div class="mask-loading-wrapper" style="<?php if( isset( $preview_font_size ) ) echo 'font-size: ' . $preview_font_size . 'em'; ?>">
        		<img src="<?php echo SLIDEDECK2_URLPATH; ?>/images/loading.gif" alt="<?php _e( "Loading", $namespace ); ?>">
	        	<div class="mask-loading-title">Loading</div>
	        	<div class="mask-loading-copy"><?php _e( "We&rsquo;re decking out your content!", $namespace ); ?></div>
        	</div>
        </div>
        <?php 
        	$shortcode = "[SlideDeck2 id={$slidedeck['id']} echo_js=1";
        	if( isset( $width ) )
				$shortcode .= " width={$width}";
				
        	if( isset( $height ) )
				$shortcode .= " height={$height}";
			
			if( $start_slide !== false )
				$shortcode .= " start={$start_slide}";
			
        	$shortcode .= ( $preview ? ' preview=1' : '' ) . "]";
        	echo do_shortcode( $shortcode );
        ?>
        <?php 
            if( slidedeck2_load_video_scripts() ) {
                foreach( array( 'froogaloop', 'youtube-api', 'dailymotion-api' ) as $script ) {
                    $src = $wp_scripts->registered[$script]->src;
                    if ( !preg_match( '|^https?://|', $src ) && !( $content_url && 0 === strpos( $src, $content_url ) ) ) {
                        $src = $base_url . $src;
                    }
                    
                    echo '<script type="text/javascript" src="' . $src . ( strpos( $src, "?" ) !== false ? "&" : "?" ) . "v=" . $wp_scripts->registered[$script]->ver . '"></script>';
                }
            }
        ?>
        
        <?php $this->print_footer_scripts(); ?>

        
        <?php if( $ress ): ?>
	        <!-- child iFrame code -->
			<script type="text/javascript">
				(function($){
					var deckWrapper = $('#SlideDeck-<?php echo $slidedeck['id']; ?>-frame');
					var deck = deckWrapper.find('.slidedeck').slidedeck();
					
					/**
					 * Check for the old before/complete in a 
					 * document ready so there's time for others to 
					 * bind to it first.
					 */
					$(document).ready(function(){
						var oldBefore = deck.options.before;
						slideDeck2CurrentSlide = deck.current;
				        deck.options.before = function(deck){
				            // If the old before option was a function, run it
				            if(typeof(oldBefore) == 'function') oldBefore(deck);
				            
				            // Make this iFrame's current slide accessible within this window.
				            slideDeck2CurrentSlide = deck.current;
				        };
					});
					
					var messageParent = function(deckWrapper){
						// SlideDeck Unique ID plus the height plus currentSlide
						message = "<?php echo $_REQUEST['slidedeck_unique_id']; ?>__" + parseInt( deckWrapper.outerHeight(true) ) + "__" + slideDeck2CurrentSlide;
						
						if(top.postMessage){
							// If the browser we're in is non-crappy enough to post a message... DO IT!
							top.postMessage( message , '*');
						} else {
							// If the browser is likely crapp-a-crap-tastic IE, then try hash-ifying the URL...
							window.location.hash = 'message'+message;
						}
					}
					
					messageParent(deckWrapper);
					window.onresize = function() {
						messageParent(deckWrapper);
					}
	            })(jQuery);
			</script>
		    <!-- end child iFrame code -->
    	<?php endif; ?>

        <?php if( $preview ): ?>
            <script type="text/javascript">
                // Force all links to be target _blank in preview
                (function($){
                    $(document).ready(function(){
                        $('a').attr('target', '_blank');
                    });
                })(jQuery);
            </script>
        <?php endif; ?>
        
            <script type="text/javascript">
                /**
                 * If a link is NOT set to open in a new window, then 
                 * make sure it tries to open in the top frame.
                 * 
                 * This only applies for the caption area.
                 */
                (function($){
                    $(document).ready(function(){
                        $('.slidedeck-frame dl dd .sd2-node-caption a[target!="_blank"]').attr('target', '_top');
                    });
                })(jQuery);
            </script>
        
        <?php do_action( "{$namespace}_iframe_footer", $slidedeck, $preview ); ?>
    </body>
</html>