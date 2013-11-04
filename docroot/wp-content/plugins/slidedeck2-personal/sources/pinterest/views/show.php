<?php
/**
 * SlideDeck Pinterest Content Source
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

<div id="content-source-pinterest">
    <input type="hidden" name="source[]" value="<?php echo $this->name; ?>" />
    <div class="inner">
        <ul class="content-source-fields">
            <li>
                <?php slidedeck2_html_input( 'options[pinterest_url]', $slidedeck['options']['pinterest_url'], array( 'label' => __( "A Pinterest URL", $this->namespace ), 'attr' => array( 'size' => 20, 'maxlength' => 255 ), 'required' => true ) ); ?>
            </li>
        </ul>
        <em class="note-below">
        	eg: A user - <strong><a href="http://pinterest.com/dtelepathy/">http://pinterest.com/dtelepathy/</a></strong><br />
        	eg: A board - <strong><a href="http://pinterest.com/dtelepathy/innovation-inspiration/">http://pinterest.com/dtelepathy/innovation-inspiration/</a></strong>
        </em>
        <em class="note-below disclaimer">
        	We are using an unofficial API (RSS) for this Pinterest source.<br />This could and probably will change in the future.
        </em>
    </div>
</div>