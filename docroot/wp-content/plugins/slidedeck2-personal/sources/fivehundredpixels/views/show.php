<?php
/**
 * SlideDeck FiveHundredPixels Content Source
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

<div id="content-source-fivehundredpixels">
    <input type="hidden" name="source[]" value="<?php echo $this->name; ?>" />
    <div class="inner">
        <ul class="content-source-fields">
            <li>
                <?php slidedeck2_html_input( 'options[fivehundredpixels_username]', $slidedeck['options']['fivehundredpixels_username'], array( 'label' => __( "500px Username", $this->namespace ), 'attr' => array( 'size' => 20, 'maxlength' => 255 ), 'required' => true ) ); ?>
            </li>
            <li>
                <?php slidedeck2_html_input( 'options[fivehundredpixels_feed_type]', $slidedeck['options']['fivehundredpixels_feed_type'], array( 'type' => 'select', 'label' => __( "Fetch From", $this->namespace ), 'attr' => array( 'class' => 'fancy' ), 'values' => $feed_types ) ); ?>
            </li>
            <li>
                <?php slidedeck2_html_input( 'options[fivehundredpixels_category]', $slidedeck['options']['fivehundredpixels_category'], array( 'type' => 'select', 'label' => __( "Only This Category", $this->namespace ), 'attr' => array( 'class' => 'fancy' ), 'values' => $categories ) ); ?>
            </li>
        </ul>
    </div>
</div>