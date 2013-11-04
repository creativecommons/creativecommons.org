<?php
$has_video_url = false;
$video_url = "";
$default_layout = $slide->meta['_layout'];
$default_caption_position = $slide->meta['_caption_position'];

if( !empty( $slide->meta['_video_url'] ) && isset( $slide->meta['_video_url'] ) ) {
    $has_video_url = true;
    $video_url = $slide->meta['_video_url'];
}
?>

<ul class="slide-content-fields">

    <li id="video-url-input">
        <label><?php _e( "Add Video URL", $namespace ); ?> <input type="text" name="_video_url" value="<?php echo $video_url; ?>" /></label> 
        <a href="<?php echo admin_url( "admin-ajax.php?action={$namespace}_get_video_meta&_wpnonce=" . wp_create_nonce( "{$namespace}-get-video-meta" ) ); ?>" id="update-video-meta" class="greybtn"><?php _e( "Update", $namespace ); ?></a>
        <em class="subtext" style="margin-top:7px;margin-left:105px;"><?php _e( "Current video services supported: YouTube, DailyMotion, Vimeo", $namespace ); ?></em>
    </li>

    <li class="sd-flyout-thumbnail">
        <img src="<?php echo $thumbnail; ?>" alt="" /> <span class="label"><?php echo strlen( $video_url ) > 60 ? substr( $video_url, 0, 60 ) . "&hellip;" : $video_url; ?></span><span class="change-media-src">&nbsp;</span>
    </li>

    <li>
        <strong><?php _e( "Choose Text Type", $namespace ); ?></strong>
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
    <li class="slide-copy option">
        <label><?php _e( "Description", $namespace ); ?><br />
            <textarea class="slidedeck_mceEditor" name="post_excerpt" cols="40" rows="5" id="slidedeck-slide-caption-description"><?php echo esc_textarea( wpautop( $slide->post_excerpt ) ); ?></textarea>
        </label>
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
        $(function(){
            $('#update-video-meta').bind('click', function(event){
                event.preventDefault();
                
                var $editor = $('#slidedeck-custom-slide-editor');
                
                $.ajax({
                    url: this.href + "&video_url=" + escape($editor.find('input[name="_video_url"]').val()),
                    dataType: "JSON",
                    success: function(data){
                        $editor.find('input[name="_permalink"]').val(data.permalink);
                        $editor.find('input[name="post_title"]').val(data.title);
                        $editor.find('textarea[name="post_excerpt"]').val(data.description);
                        $editor.find('li#video-url-input').hide();
                        $editor.find('li.sd-flyout-thumbnail').show();
                        $editor.find('li.sd-flyout-thumbnail img').attr( 'src', data.thumbnail );
                        $editor.find('li.sd-flyout-thumbnail span.label').html( data.permalink );
                        
                        if(tinyMCE){
                            var content = data.description;
                            if ( tinyMCE.activeEditor.getParam('wpautop', true) && typeof(switchEditors) == 'object' ) {
                                content = switchEditors.wpautop(content);
                            }
                            tinyMCE.activeEditor.setContent(content);
                        }
                    }
                });
            });
            
            // Clear URL/thumbnail info
            $('.slide-content-fields').delegate('.change-media-src', 'click', function(event) {
                event.preventDefault();
                $('li.sd-flyout-thumbnail').hide();
                $('li#video-url-input').show();
            });
            
            // Display the correct li for URL input or video thumbnail
            var hasVideoUrl = "<?php echo $has_video_url; ?>";
            if ( hasVideoUrl ) {
                $('#video-url-input').hide();
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
             
        });   
    })(jQuery, window, null);
</script>
