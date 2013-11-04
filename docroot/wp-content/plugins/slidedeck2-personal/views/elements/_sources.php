<?php foreach( $sources as &$source ): ?>
    
    <div class="source">
        <img class="source-icon" src="<?php slidedeck2_source_icon_url( $source ); ?>" alt="<?php echo $source->label; ?>" />
        <a class="configure-source" href="#configure"><span>Configure</span></a>
        <div class="slidedeck-content-source hidden source-<?php echo $source->name; ?>">
            <h4><?php printf( __( "Configure your %s source", $namespace ), preg_replace( '/^your\s/i', '', $source->label ) ); ?></h4>
            
            <?php do_action( "{$namespace}_form_content_source", $slidedeck, $source->name ); ?>
			
			<div class="actions">
			    <a href="#apply" class="slidedeck-ajax-update button button-primary apply"><?php _e( "Apply", $namespace ); ?></a>
			    
			    <?php if( count( $sources ) > 1 ): ?>
			    	<a href="<?php echo admin_url( 'admin-ajax.php?action=' . $namespace . '_delete_source&_wpnonce=' . wp_create_nonce( $namespace . '-delete-source' ) ); ?>" class="delete link"><?php _e( "Delete", $namespace ); ?></a>
			    <?php endif; ?>
			    
			    <a href="#cancel" class="cancel link"><?php _e( "Close", $namespace ); ?></a>
			</div>
        </div>
    </div>
    
<?php endforeach; ?>

<a href="<?php echo admin_url( "admin-ajax.php?action=slidedeck_source_modal&slidedeck={$slidedeck_id}&_wpnonce_source_modal=" ) . wp_create_nonce( 'slidedeck-source-modal' ); ?>" class="button purple slidedeck-source-modal">Add Source</a>
