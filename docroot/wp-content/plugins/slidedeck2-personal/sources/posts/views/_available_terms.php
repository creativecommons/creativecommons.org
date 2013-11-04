<?php
/**
 * SlideDeck Available Terms
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
<?php if ( !is_taxonomy_hierarchical( $taxonomy ) ): ?>
<div id="tagsdiv-<?php echo $taxonomy_object->name; ?>" class="postbox tagsdiv taxonomy <?php echo $taxonomy_object->name; ?>">
    <h3 class="widget-top"><?php echo $taxonomy_object->label; ?></h3>
	<div class="inside">
		<?php slidedeck2_post_tags_meta_box( null, array(
			'id' => 'tagsdiv-'.$taxonomy,
			'title' => $taxonomy_object->label,
			'callback' => 'post_tags_meta_box',
			'args' => array(
				'taxonomy' => $taxonomy,
				'tags' => implode( ',', $filtered )
			) ) );
		?>
	</div>
</div>
<?php else: ?>
<div id="categorydiv-<?php echo $taxonomy_object->name; ?>" class="postbox taxonomy <?php echo $taxonomy_object->name; ?>">
    <h3 class="widget-top"><?php echo $taxonomy_object->label; ?></h3>
	<div class="inside">
		<?php slidedeck2_post_categories_meta_box( null, array(
			'id' => $taxonomy.'div',
			'title' => $taxonomy_object->label,
			'callback' => 'post_categories_meta_box',
			'args' => array(
				'taxonomy' => $taxonomy,
				'selected_cats' => $filtered
			) ) );
	    ?>
	</div>
</div>
<?php endif; ?>
