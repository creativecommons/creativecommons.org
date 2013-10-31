<?php 
/**
 * SlideDeck Insert iFrame Table
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
<table cellpadding="0" cellspacing="0" class="slidedeck-row">
    <tbody>
        
        <?php foreach( $slidedecks as &$slidedeck ): ?>
            <tr class="<?php if( in_array( $slidedeck['id'], $selected ) ) echo ' selected'; ?><?php if( $slidedeck == end( $slidedecks ) ) echo ' last'; ?>">
                <td class="col-1 col-slidedeck-type">
                    <?php if( in_array( 'custom', $slidedeck['source'] ) ): ?>
                        <img src="<?php slidedeck2_source_icon_url( "custom" ); ?>" alt="" class="icon" />
                    <?php else: ?>
                        <?php if( count($slidedeck['source']) > 1 ): ?>
                            <div class="multisource-icon">
                                <?php $count = 0; foreach( $slidedeck['source'] as $source ): ?>
                                    <?php if( $count < 4 ): ?>
                                        <img src="<?php slidedeck2_source_chicklet_url( $source ); ?>" alt="" class="chicklet" />
                                    <?php endif; ?>
                                <?php $count++; endforeach; ?>
                            </div>
                        <?php else: ?>
                            <img src="<?php slidedeck2_source_icon_url( $slidedeck['source'][0] ); ?>" alt="" class="icon" />
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td class="col-2 col-slidedeck-title">
                    <span class="slidedeck-title">
                        <input type="checkbox" name="slidedecks[]" class="slidedecks-insert" value="<?php echo $slidedeck['id']; ?>"<?php if( in_array( $slidedeck['id'], $selected ) ) echo ' checked="checked"'; ?> />
                        <?php echo $slidedeck['title']; ?>
                        <span class="slidedeck-id">(<?php echo $slidedeck['id']; ?>)</span>
                    </span>
                </td>
                <td class="col-3 col-slidedeck-created">
                    <span class="slidedeck-created">Created <?php echo date( "M d, Y", strtotime( $slidedeck['created_at'] ) + ( get_option( 'gmt_offset' ) * 3600 ) ); ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
        
    </tbody>
</table>