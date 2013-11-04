<?php
$has_image_url = false;
$image_url = "";
$default_source = $slide->meta['_image_source'];
$default_layout = $slide->meta['_layout'];
$default_caption_position = $slide->meta['_caption_position'];
?>
<ul class="slide-content-fields">
    <li class="select-source">
    	<label><?php _e( "Upload an Image", $namespace ); ?><input type="radio" name="_image_source" class="fancy" value="upload"<?php if( $default_source == "upload" ) echo ' checked="checked"'; ?> /></label>
    	<label><?php _e( "Specify a URL", $namespace ); ?><input type="radio" name="_image_source" class="fancy" value="url"<?php if( $default_source == "url" ) echo ' checked="checked"'; ?> /></label>
    	<label><a href="<?php echo admin_url( 'media-upload.php?post_id=' . $parent_slidedeck_id . '&tab=library&slidedeck_custom=1&slide_id=' . $slide->ID . '&TB_iframe=1&width=640&height=515' ); ?>" class="thickbox" title="<?php _e( 'Add Media' ); ?>"><?php _e( "From Media Library", $namespace ); ?></a><input type="radio" name="_image_source" class="fancy" value="medialibrary"<?php if( $default_source == "medialibrary" ) echo ' checked="checked"'; ?> /></label>
    </li>

    <li id="sd-image-upload-container">&nbsp;</li>
    
    <li id="sd-image-url">
        <label><?php _e( "Image URL", $namespace ); ?> <input type="text" name="_image_url" value="<?php echo $slide->meta['_image_url']; ?>" /></label>
        <a href="#" id="update-image-url" class="greybtn"><?php _e( "Update", $namespace ); ?></a>
    </li>
    
    <li class="sd-flyout-thumbnail">
        <img src="<?php echo $thumbnail; ?>" alt="" /> <span class="label"><?php echo strlen( $image_filename ) > 50 ? substr( $image_filename, 0, 50 ) . "&hellip;" : $image_filename; ?></span><span class="change-media-src">&nbsp;</span>
    </li>
    
    <li>
        <ul>
        	<?php foreach( $layouts as $layout => $label ): ?>
        		<li class="layout">
        			<label <?php if( $default_layout == $layout ) echo 'class="active-layout"'; ?>>
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
    
    <li class="slide-copy option">
        <label><?php _e( "Description", $namespace ); ?><br />
            <textarea class="slidedeck_mceEditor" name="post_excerpt" cols="40" rows="5" id="slidedeck-slide-caption-description"><?php echo esc_textarea( wpautop( $slide->post_excerpt ) ); ?></textarea>
        </label>
    </li>
    
    <li class="preferred-image-size">
        <strong><?php _e( "Preferred Image Size", $namespace ); ?></strong>
        <?php slidedeck2_html_input( '_preferred_image_size', $slide->meta['_preferred_image_size'], $preferred_image_size_params ); ?>
    </li>
    
    <li class="image-scaling option">
        <strong><?php _e( "Image Scaling", $namespace ); ?></strong>
        <?php slidedeck2_html_input( "_image_scaling", $slide->meta['_image_scaling'], $image_scaling_params ); ?>
    </li>
    
    <li class="last text-position option">
        <strong><?php _e( "Caption Position", $namespace ); ?></strong>
        <?php foreach( $caption_positions as $position => $label ): ?>
            <label><input type="radio" class="fancy" name="_caption_position" value="<?php echo $position; ?>"<?php if( $default_caption_position == $position ) echo ' checked="checked"'; ?> /><?php echo $label; ?></label>
        <?php endforeach; ?>
    </li>
    
</ul>

<script type="text/javascript">

    sd_layoutoptions = {
        "caption" : {
            "fields" : ".slide-title, .slide-copy, .text-position, .image-scaling",
            "positions" : ['top', 'bottom'],
            "proper" : "Caption"
        },
        "body-text" : {
            "fields" : ".slide-title, .slide-copy, .text-position, .image-scaling",
            "positions" : ['left', 'right'],
            "proper" : "Body Text"
        },
        "none" : {
            "fields" : ".image-scaling"
        }
    };
    

    (function($, window, undefined){
        var defaultSource = "<?php echo $default_source; ?>";
        var thumbnail = "<?php echo $thumbnail; ?>";
        var $choices = $('#slidedeck-custom-slide-editor li.select-source input[type="radio"]');
        
        // Click to change image source
        $('#slidedeck-custom-slide-editor li.select-source').delegate('input', 'click', function(event) {
            var src = this.value;
            $('#slidedeck-custom-slide-editor li.select-source label').removeClass('on');
            $(this).parent('label').click().addClass('on');
            if ( src === 'url' ) {
                $('#sd-image-upload-container').slideUp();
                $('#sd-image-upload').slideUp();
                $('#sd-image-url').slideDown();
                $('li.preferred-image-size').slideUp();
            } else {
                $('#sd-image-url').slideUp();
                $('#sd-image-upload-container').slideDown();
                $('#sd-image-upload').slideDown();
                $('li.preferred-image-size').slideDown();
            }
        });
        
        $('#slidedeck-custom-slide-editor li.select-source').delegate('a.thickbox', 'click', function(event){
            var $input = $(this).siblings('input[type="radio"]');
            
            $choices.removeAttr('checked').each(function(){
                this.checked = false;
            });
            
            $input.attr('checked', 'checked')[0].checked = true;
            $input.click();
            
            $('#sd-image-upload-container').hide();
            $('#sd-image-upload').hide();
            $('#sd-image-url').hide();
        });
    
        // Clear URL/thumbnail info
        $('.slide-content-fields').delegate('.change-media-src', 'click', function(event) {
            event.preventDefault();
            $('li.sd-flyout-thumbnail').slideUp();
            $('#slidedeck-custom-slide-editor li.select-source').slideDown();
            $('#slidedeck-slide-upload-files').find('.progress').css('width', "0%");
            
            var selectedValue = $('#slidedeck-custom-slide-editor .slide-content-fields input[name="_image_source"]:checked').val();
            
            switch( selectedValue ) {
                case "url":
                    $('#sd-image-upload-container').slideUp();
                    $('#sd-image-upload').slideUp();
                    $('#sd-image-url').slideDown();
                break;
                
                case "upload":
                    $('#sd-image-url').slideUp();
                    $('#sd-image-upload-container').slideDown();
                    $('#sd-image-upload').slideDown();
                break;
                
                case "medialibrary":
                    $('#sd-image-url').hide();
                    $('#sd-image-upload-container').hide();
                    $('#sd-image-upload').hide();
                break;
            }
        });
        
        switch( defaultSource ) {
            case "url":
                $('#sd-image-upload-container').hide();
                $('#sd-image-upload').hide();
                $('#sd-image-url').show();
            break;
            
            case "upload":
                $('#sd-image-url').hide();
                $('#sd-image-upload-container').show();
                $('#sd-image-upload').show();
            break;
            
            case "medialibrary":
                $('#sd-image-url').hide();
                $('#sd-image-upload-container').hide();
                $('#sd-image-upload').hide();
            break;
        }
        
        // Display the correct li for URL input or video thumbnail
        var hasImageUrl = "<?php echo $thumbnail; ?>";
        if ( hasImageUrl.replace(/^\s+|\s+$/g, "") != "" ) {
            $('#sd-image-upload-container').hide();
            $('#sd-image-upload').hide();
            $('#sd-image-url').hide();
            $('#slidedeck-custom-slide-editor li.select-source').hide();
        } else {
            $('.sd-flyout-thumbnail').hide();
        };
    
        // Show correct fields for layout when opening flyout
        var layoutoption = sd_layoutoptions['<?php echo $default_layout; ?>'];
        $('.slide-content-fields').find('li.option').not(layoutoption.fields).hide();
        $('.slide-content-fields').find(layoutoption.fields).show();
        
        if ( layoutoption.positions ) {
            $('li.text-position strong').html(layoutoption.proper + ' Position');
            $('li.text-position label input').parent('label').hide().removeClass('on');
            for (var k in layoutoption.positions){
                var pos = layoutoption.positions[k];
                $('li.text-position label input[value='+pos+']').parent('label').show();
                if ( pos === '<?php echo $default_caption_position; ?>' ) {
                    $('li.text-position label input[value='+pos+']').parent('label').addClass('on');
                }
            }
            
            $('li.text-position').show();
        }
        
        $('#update-image-url').bind('click', function(event){
            event.preventDefault();
            
            $('#sd-image-upload-container, #sd-image-upload, #sd-image-url, #slidedeck-custom-slide-editor li.select-source').slideUp(500);
            
            var $thumbnail = $('#slidedeck-custom-slide-editor-form').find('.sd-flyout-thumbnail');
            var src = $('#sd-image-url input[name="_image_url"]').val();
            var label = src.substr(src.lastIndexOf("/") + 1);
                label = label.length > 50 ? label.substr(0,50) + "&hellip;" : label;
            $thumbnail.find('img').attr('src', src);
            $thumbnail.find('.label').html(label);
            $thumbnail.slideDown(500);
            $('#sd-image-upload-container, #sd-image-upload, #slidedeck-custom-slide-editor-form .select-source').slideUp(500);
        });
    })(jQuery, window, null);
</script>