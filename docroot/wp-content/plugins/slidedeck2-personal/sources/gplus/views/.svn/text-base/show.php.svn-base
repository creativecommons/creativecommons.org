<?php
/**
 * SlideDeck Google Plus Posts Content Source
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

<div id="content-source-gplus">
    <input type="hidden" name="source[]" value="<?php echo $this->name; ?>" />
    <div class="inner">
        <ul class="content-source-fields">
            <li>
				<?php slidedeck2_html_input( 'options[gplus_api_key]', $slidedeck['options']['gplus_api_key'], array( 'type' => 'password', 'label' => "Google+ API key", 'attr' => array( 'size' => 40, 'maxlength' => 255 ), 'required' => true ) ); ?>
				<em class="note-below"><?php printf( __( 'You need an API Key to view your/someone else\'s Google+ Posts.%1$sHere\'s %2$show to get one%3$s. (you only need to do this once)' ), "<br />", "<a id='gplus-how-to' href='#'>", "</a>" ); ?></em>
            </li>
            <li>
				<?php 
					$tooltip = sprintf( __( 'The number in the URL when you visit: %1$sYour Google+ Profile%2$s.' ), "<a href='https://plus.google.com/me' target='_blank'>", "</a>" );
					slidedeck2_html_input( 'options[gplusUserId]', $slidedeck['options']['gplusUserId'], array( 'label' => "Google+ User Id" . '<span class="tooltip" title="' . __( "You can use yours, or someone else's user ID", $namespace ) . '"></span>', 'attr' => array( 'size' => 40, 'maxlength' => 255 ), 'required' => true ) );
				?>
				<em class="note-below"><?php echo $tooltip; ?></em>
            </li>
        </ul>
    </div>
</div>