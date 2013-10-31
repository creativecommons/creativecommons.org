<div id="slidedeck-choose-slide-type">
	
    <h4><?php _e( $title, $namespace ); ?></h4>
    
	<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post">
		<input type="hidden" name="action" value="<?php echo $action; ?>" />
		<input type="hidden" name="slidedeck_id" value="<?php echo $slidedeck_id; ?>" />
		<?php wp_nonce_field( "{$namespace}-choose-slide-type" ); ?>
		
		<?php if( isset( $slide_id ) ): ?>
			<input type="hidden" name="slide_id" value="<?php echo $slide_id; ?>" />
		<?php endif; ?>
		
		<ul id="slidedeck-slide-types">
			<?php foreach( $slide_types as $slide_type ): ?>
				<li class="slide-type<?php if( isset( $slide_type->disabled ) ) echo ' disabled'; ?>">
					<label data-for="<?php echo $slide_type->name; ?>">
					    <img src="<?php echo $slide_type->thumbnail; ?>" />
						<span><?php echo $slide_type->label; ?> Slide</span>
						
						<?php if( !isset( $slide_type->disabled ) ): ?>
                            <input type="radio" name="_slide_type" value="<?php echo $slide_type->name; ?>" />
                        <?php endif; ?>
					</label>
					<?php if( $slide_type->name == "image" ): ?>
					    or <a href="<?php echo admin_url( 'media-upload.php' ); ?>?post_id=<?php echo $slidedeck_id; ?>&slidedeck_bulkupload=1&TB_iframe=1" class="thickbox">upload multiple</a>
				    <?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
    
        <div class="actions">
            <a href="<?php echo $cancel_url; ?>" class="cancel link"><?php _e( "Cancel", $namespace ); ?></a>
        </div>
	</form>
	
</div>
