<?php
$default_layout = $slide->meta['_layout'];
?>

<ul class="slide-content-fields">
    <li>
        <strong><?php _e( "Choose Layout", $namespace ); ?></strong>
        <ul>
        	<?php foreach( $layouts as $layout => $label ): ?>
        		<li class="layout">
        			<label<?php if( $default_layout == $layout ) echo ' class="active-layout"'; ?>>
        				<img src="<?php echo $url; ?>/images/layout-thumbnail-<?php echo $layout; ?>.png" alt="<?php echo $label; ?>" />
        				<span class="label"><?php echo $label; ?></span>
        				<input type="radio" name="_layout" value="<?php echo $layout; ?>"<?php if( $slide->meta['_layout'] == $layout ) echo ' checked="checked"'; ?> />
        			</label>
        		</li>
        	<?php endforeach; ?>
        </ul>
    </li>

    <li>
        <label><?php _e( "Slide Link", $namespace ); ?><br />
            <input type="text" name="_permalink" value="<?php echo $slide->meta['_permalink']; ?>" />
        </label>
    </li>
    
    <li class="slide-title no-border option">
        <label><?php _e( "Title", $namespace ); ?><br />
            <input type="text" name="post_title" value="<?php echo get_the_title( $slide->ID ); ?>" />
        </label>
    </li>
    
    <li class="last slide-copy option">
        <label><?php _e( "Copy", $namespace ); ?><br />
            <textarea class="slidedeck_mceEditor" name="post_excerpt" cols="40" rows="5"><?php echo esc_textarea( wpautop( $slide->post_excerpt ) ); ?></textarea>
        </label>
    </li>
    
</ul>

<script type="text/javascript">
    sd_layoutoptions = {
        "basic" : {
            "fields" : ".slide-title, .slide-copy, .image-scaling"
        },
        "multi-column" : {
            "fields" : ".slide-title, .slide-copy, .image-scaling"
        },
        "block-quote" : {
            "fields" : ".slide-title, .slide-copy, .image-scaling"
        }
    };

    (function($, window, undefined){
        $(function(){
            // Show correct fields for layout when opening flyout
            var layoutoption = sd_layoutoptions['<?php echo $default_layout; ?>'];
            $('.slide-content-fields').find('li.option').not(layoutoption.fields).hide();
            $('.slide-content-fields').find(layoutoption.fields).show();
        });   
    })(jQuery, window, null);
</script>