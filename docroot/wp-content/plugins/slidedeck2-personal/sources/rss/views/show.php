<?php
/**
 * SlideDeck RSS Content Source
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

<div id="content-source-rss">
    <input type="hidden" name="source[]" value="<?php echo $this->name; ?>" />
    <div class="inner">
        <ul class="content-source-fields">
            <li>
				<?php slidedeck2_html_input( 'options[feedUrl]', $slidedeck['options']['feedUrl'], array( 'label' => __( "RSS Feed URL", $this->namespace ), 'attr' => array( 'rows' => 3 ), 'required' => true, 'type' => 'textarea' ) ); ?>
                
                <em><?php echo __( '(you can enter multiple feeds, one per line)', $this->namespace ); ?></em>
                <em><?php echo sprintf( __( 'Needs to be a valid RSS feed. See the %1$sW3C Feed Validator Service%2$s to check your feed. NOTE: Some servers are not configured to follow redirects, make sure you are using the feed URL&rsquo;s final destination URL.', $this->namespace ), "<a href='http://validator.w3.org/feed/' target='_blank'>", "</a>" ); ?></em>
            </li>
            <li>
				<?php slidedeck2_html_input( 'options[rssImageSource]', $slidedeck['options']['rssImageSource'], $this->options_model['Setup']['rssImageSource'] ); ?>
            </li>
            <li>
				<?php slidedeck2_html_input( 'options[rssValidateImages]', $slidedeck['options']['rssValidateImages'], $this->options_model['Setup']['rssValidateImages'] ); ?>
            </li>
        </ul>
    </div>
</div>