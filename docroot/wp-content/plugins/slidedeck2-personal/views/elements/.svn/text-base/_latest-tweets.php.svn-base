<?php 
/**
 * SlideDeck Latest Tweets
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
<dl class="tweet-list slidedeck">
    
    <?php foreach( $formatted_rss_items as $key => $value ): ?>
        <?php 
            if( preg_match( '/\/slidedeck/i', $value['permalink'] ) ){
                $icon = 'slidedeck-icon';
            }elseif( preg_match( '/\/dtelepathy/i', $value['permalink'] ) ){
                $icon = 'dtelepathy-icon';
            }
        ?>
        
        <dd class="tweet">
            <div class="tweet-inner">
                <div class="slidedeck-vertical-center-outer">
                    <div class="slidedeck-vertical-center-middle">
                        <div class="slidedeck-vertical-center-inner">
                            <div class="tweet"><?php echo $value['tweet']; ?></div>
                            <a class="time-ago" href="<?php echo $value['permalink']; ?>" target="_blank"><div class="icon <?php echo $icon; ?>"></div><?php echo $value['time_ago']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </dd>
    <?php endforeach; ?>
    
</dl>
<div class="nav-wrapper"></div>
<a class="prev navigation" href="#prev">Prev</a>
<a class="next navigation" href="#next">Next</a>
