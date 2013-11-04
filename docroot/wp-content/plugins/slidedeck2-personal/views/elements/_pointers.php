<?php 
/**
 * SlideDeck Pointers JavaScript
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
<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            
            <?php foreach( $pointers as &$pointer ): ?>
            
                $('<?php echo $pointer['selector']; ?>').pointer({
                    content: '<?php echo $pointer['content']; ?>',
                    pointerClass: 'wp-pointer <?php echo $pointer['id']; ?>',
                    position: {
                        edge: '<?php echo $pointer['position']['edge']; ?>',
                        align: '<?php echo $pointer['position']['align']; ?>'
                    },
                    close: function(){
                        $.post( ajaxurl, {
                            pointer: '<?php echo $pointer['id']; ?>',
                            action: 'dismiss-wp-pointer'
                        });
                    }
                }).pointer('open');
            
            <?php endforeach; ?>
            
        });
    })(jQuery);
</script>
