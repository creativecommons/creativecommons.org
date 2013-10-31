<?php 
/**
 * SlideDeck Blog Feed
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
<ul class="postList">
    
    <?php foreach( $rss_items as $key => $value ): ?>
        <?php 
            if( preg_match( '/~r\/Slidedeck/', $value->get_permalink() ) ){
                $icon = 'slidedeck-icon';
            }else{
                $icon = 'dtelepathy-icon';
            }
        ?>
        <li>
            <div class="icon <?php echo $icon; ?>"></div>
            <a href="<?php echo $value->get_permalink(); ?>" target="_blank">
                <?php echo $value->get_title(); ?>
            </a>
        </li>
    <?php endforeach; ?>

</ul>