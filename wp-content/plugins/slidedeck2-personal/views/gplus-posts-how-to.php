<?php
/**
 * SlideDeck Google+ How-to
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
    <h1>Getting your Google+ API Key</h1>
</div>
<div class="wrapper">
    <div class="inner">
        <p id="gplus-posts-how-to-steps" class="clearfix">
           <a href="#1" class="step-1 current"><span><?php _e( "Step 1", $namespace ); ?></span></a> 
           <a href="#2" class="step-2"><span><?php _e( "Step 2", $namespace ); ?></span></a> 
           <a href="#3" class="step-3"><span><?php _e( "Step 3", $namespace ); ?></span></a> 
        </p>
        <dl class="slidedeck" id="gplus-posts-how-to">
            <dt>Step 1</dt>
            <dd>
                <div class="section">
                    <h3><?php _e( "How do I get an API Key?", $namespace ); ?></h3>
                    <p><?php _e( "Anyone can get an API Key. All you need is a Google account and you must be logged in to get the key. We wish it were easier, but you should only need to get an API Key once.", $namespace ); ?></p>
                </div>
                <div class="section">
                    <h3><?php _e( "Enable API Access for Google+", $namespace ); ?></h3>
                    <p><?php _e( sprintf( "Acccess the %sGoogle APIs Console%s and, if necessary, click the &ldquo;Create Project&rdquo; link. Once you&rsquo;re in the Service screen, enable the Google+ API by turning the switch on.", '<a href="https://code.google.com/apis/console/#project:755401620327:services" target="_blank">', '</a>' ), $namespace ); ?></p>
                        
                    <img src="<?php echo SLIDEDECK2_URLPATH; ?>/images/gplus-how-to/step1.png" class="align-left" alt="<?php _e( "Start using the Google APIs console", $namespace ); ?>" />
                    <img src="<?php echo SLIDEDECK2_URLPATH; ?>/images/gplus-how-to/step2.png" class="align-right" alt="<?php _e( "Enable API Access", $namespace ); ?>" />
                </div>
            </dd>
            <dt>Step 2</dt>
            <dd>
                <div class="section">
                    <h3><?php _e( "Agree to the Terms", $namespace ); ?></h3>
                    <p><?php _e( "You&rsquo;ll need to click through some of the Terms of Service agreements (usually 2 of them). Once you&rsquo;ve agreed to the terms, you should be redirected back to the overview page.", $namespace ); ?></p>
                    
                    <img src="<?php echo SLIDEDECK2_URLPATH; ?>/images/gplus-how-to/step3.png" alt="<?php _e( "Agree to the Terms", $namespace ); ?>" />
                </div>
            </dd>
            <dt>Step 3</dt>
            <dd>
                <div class="section">
                    <h3><?php _e( "Copy your API Key then paste it into SlideDeck 2", $namespace ); ?></h3>
                    <p><?php _e( "Click the API Access menu at the top left and copy your API Key! We&rsquo;ll automatically save your key so it will be pre-populated for your next SlideDeck 2.", $namespace ); ?></p>
                    
                    <img src="<?php echo SLIDEDECK2_URLPATH; ?>/images/gplus-how-to/step4.png" alt="<?php _e( "Copy your API Key", $namespace ); ?>" />
                </div>
            </dd>
        </dl>
        
        <div id="gplus-modal-nav">
            <a href="#" id="gplus-how-to-why-link"><span class="twirly"></span><?php _e( "Why do I need an API Key?", $namespace ); ?></a>
            
            <span id="gplus-how-to-step"><?php _e( "Step", $namespace ); ?> <span class="current">1</span> <?php _e( "of", $namespace ); ?> <span class="total">3</span></span>
            
            <a href="#next" id="gplus-how-to-next"><?php _e( "Next Step", $namespace ); ?></a>
        </div>
        
        <p id="gplus-how-to-why"><?php _e( "Google uses API Keys for most of its content. This way if someone is being abusive towards Google (and more importantly) your data, they can be cut off. They also use the API key to limit the number of requests to their API and better handle load.", $namespace ); ?></p>
        
	</div>
</div>
