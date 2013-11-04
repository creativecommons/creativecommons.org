<?php 
/**
 * SlideDeck No Content Found Template
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
<div class="no-content-found-wrapper">
	<div class="no-content-found-middle" style="font-size:<?php echo $preview_font_size; ?>em;">
		<div class="no-content-found">
				<div class="no-content-found-title"><?php _e( "No Content Found", $namespace ); ?></div>
				<div class="no-content-found-copy">
					<?php _e( 'We&rsquo;re having trouble finding your content. Please double-check<br /> your <a href="#source-configuration" class="no-content-source-configuration"><span class="gear-icon"></span>source configuration</a> at the top of the page.', $namespace ); ?>
				</div>
		</div>
	</div>
</div>
