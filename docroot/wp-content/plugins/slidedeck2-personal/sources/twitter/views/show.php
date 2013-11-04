<?php
/**
 * SlideDeck Twitter Content Source
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

$search_hidden = ( $slidedeck['options']['twitter_search_or_user'] == 'user' ) ? ' style="display: none;"' : '';
$username_hidden = ( $slidedeck['options']['twitter_search_or_user'] == 'search' ) ? ' style="display: none;"' : '';
?>

<div id="content-source-twitter">
    <input type="hidden" name="source[]" value="<?php echo $this->name; ?>" />
    <div class="inner">
        <ul class="content-source-fields">
            <li>
                <?php slidedeck2_html_input( 'options[twitter_search_or_user]', $slidedeck['options']['twitter_search_or_user'], $this->options_model['Setup']['twitter_search_or_user'] ); ?>
            </li>
            <li class="twitter-search"<?php echo $search_hidden; ?>>
        		<?php slidedeck2_html_input( 'options[twitter_q]', $slidedeck['options']['twitter_q'], $this->options_model['Setup']['twitter_q'] ); ?>
            </li>
            <li class="twitter-username"<?php echo $username_hidden; ?>>
        		<?php slidedeck2_html_input( 'options[twitter_username]', $slidedeck['options']['twitter_username'], $this->options_model['Setup']['twitter_username'] ); ?>
            </li>
            <li class="twitter-useGeolocationImage">
                <?php slidedeck2_html_input( 'options[useGeolocationImage]', $slidedeck['options']['useGeolocationImage'], $this->options_model['Setup']['useGeolocationImage'] ); ?>
            </li>
        </ul>
    </div>
</div>