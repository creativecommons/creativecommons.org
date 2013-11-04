<?php
/**
 * SlideDeck Avaialable Taxonomies
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
<?php if( !empty( $taxonomies ) ): ?>
	<ul>
	<?php foreach( $taxonomies as &$taxonomy ): ?>
	    <?php 
	    	if( !empty( $taxonomy->terms ) ): 
	    		$value = false;
				if( !empty( $slidedeck['options']['taxonomies'][$taxonomy->name] ) )
					$value = $slidedeck['options']['taxonomies'][$taxonomy->name];
    		?>
	    	
	        <li class="taxonomy">
	        	<?php slidedeck2_html_input( "options[taxonomies][{$taxonomy->name}]", $value, array( 'type' => 'radio', 'label' => $taxonomy->label . ' <span class="count">(' . count( $taxonomy->terms ) . ')</span>', 'attr' => array( 'class' => 'fancy' ) ) ); ?>
	    	</li>
	    <?php endif; ?>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>