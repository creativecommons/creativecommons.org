<h4><?php echo get_the_title( $slide->ID ); ?></h4>

<div class="slide-type-header slide-type-<?php echo $slide->meta['_slide_type']; ?>">
    <span class="slide-type-thumbnail" style="background-image:url(<?php echo $slide_types[$slide->meta['_slide_type']]->thumbnail_small; ?>);"></span>
    <span class="label"><?php echo $slide_types[$slide->meta['_slide_type']]->label; ?></span>
    <a href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=slidedeck_change_slide_type_modal&slide_id=' . $slide_id . "&slidedeck=" . $slidedeck['id'] ), "{$namespace}-change-slide-type-modal" ); ?>" class="change"><?php _e( "Change", $namespace ); ?></a>
</div>

<div class="slide-type-body">
    <?php do_action( "{$namespace}_before_custom_slide_editor_form", $slide, $slidedeck ); ?>

    <form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post" id="slidedeck-custom-slide-editor-form">
    	<input type="hidden" name="action" value="<?php echo $namespace; ?>_update_slide" />
    	<?php wp_nonce_field( "{$namespace}-custom-slide-editor-form" ); ?>
    	<input type="hidden" name="slide_id" value="<?php echo $slide_id; ?>" />
    	
    	<?php do_action( "{$namespace}_custom_slide_editor_form", $slide, $slidedeck ); ?>
    
    	<div class="actions">
    	    <input type="submit" value="<?php _e( "Apply", $namespace ); ?>" class="button button-primary" />
            <a href="#cancel" class="cancel link"><?php _e( "Close", $namespace ); ?></a>
    	</div>
    </form>
    
    <?php do_action( "{$namespace}_after_custom_slide_editor_form", $slide, $slidedeck ); ?>
</div>