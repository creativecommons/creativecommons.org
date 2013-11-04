<ol class="slides-sortable clearfix">
	<?php if( !empty( $slides ) ): ?>
	    <?php $count = 1; ?>
    	<?php foreach( $slides as $slide ): ?>
    	    <?php $thumbnail = slidedeck2_get_slide_thumbnail( $slide ); ?>
    		<li class="slide slide-id-<?php echo $slide->ID; ?>" style="<?php if( !empty( $thumbnail ) ) echo 'background-image:url(' . $thumbnail . ');'; ?>">
    			<a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=slidedeck_slide_editor_modal&slide_id=' . $slide->ID ), "{$namespace}-slide-editor-modal" ); ?>" class="thumbnail" title="<?php _e( "Edit slide properties", $namespace ); ?>" onclick="return false;">
    				<span class="slide-label"><?php _e( "Slide", $namespace ); ?> <span class="slide-number"><?php _e( "{$count}", $namespace ); ?></span></span>
    				<span class="tip"><?php _e( "Click to edit", $namespace ); ?></span>
    			</a>
    	        
    	        <?php if( count( $slides ) > 1 ): ?>
    	        	<a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=slidedeck_delete_slide&slide_id=' . $slide->ID . "&slidedeck=" . $slide->post_parent ), "{$namespace}-delete-slide" ); ?>" class="remove" onclick="return false;"><?php _e( "Remove", $namespace ); ?></a>
            	<?php endif; ?>
    	        
    	        <input type="hidden" name="slide_order[]" value="<?php echo $slide->ID; ?>" />
    		</li>
    		<?php $count++; ?>
    	<?php endforeach; ?>
    
    <?php else: ?>
        
        <li class="slide empty-slide">
            <a class="thumbnail" href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=slidedeck_add_slide_modal&slidedeck=' . $slidedeck_id ), "{$namespace}-change-slide-type-modal" ); ?>" onclick="return false;">
                <span class="slide-label"><?php _e( "Slide", $namespace ); ?> <span class="slide-number"><?php _e( "1", $namespace ); ?></span></span>
            </a>
        </li>
        
    <?php endif; ?>
	
	<li class="add-new-slide">
		<a class="button purple" href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=slidedeck_add_slide_modal&slidedeck=' . $slidedeck_id ), "{$namespace}-change-slide-type-modal" ); ?>" onclick="return false;">
			<?php _e( "Add Slide", $namespace ); ?>
		</a>
	</li>
</ol>

<?php wp_nonce_field( "{$namespace}-update-slide-order", "_wpnonce_update_slide_order", false ); ?>
<input type="hidden" name="source[]" value="custom" />
