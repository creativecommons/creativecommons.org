<div id="sd-image-upload">
	<iframe id="slidedeck-slide-image-html4" style="display:none;" frameborder="0" scrolling="no" allowtransparency="yes" src="<?php echo admin_url( "admin-ajax.php?action={$this->namespace}_html4_image_upload_form&slide_id={$slide_id}&slidedeck={$slidedeck_id}&_wpnonce=" ) . wp_create_nonce( "{$namespace}-html4-upload-form" ); ?>"></iframe>
	
	<div id="slidedeck-slide-image">
	    <strong>Add Image</strong>
		<button id="slidedeck-slide-upload-browse" class="greybtn">Browse Image</button>
		<div id="slidedeck-slide-upload-files" style="display:none;"></div>
	</div>
</div>

<script type="text/javascript">
	(function($, window, undefined){
		window.uploader = new plupload.Uploader({
			runtimes : 'gears,html5,flash,silverlight,browserplus,html4',
			browse_button : 'slidedeck-slide-upload-browse',
			container: 'slidedeck-slide-image',
			multi_selection: false,
			max_file_size : '<?php echo $max_filesize; ?>',
			url : '<?php echo admin_url( 'admin-ajax.php' ) . '?action=slidedeck_slide_upload_image&slide_id=' . $slide_id . '&slidedeck=' . $slidedeck_id . '&_wpnonce=' . wp_create_nonce( "{$namespace}-slide-upload-image" ); ?>',
			flash_swf_url : '<?php echo $url; ?>/js/plupload.flash.swf',
			silverlight_xap_url : '<?php echo $url; ?>/js/plupload.silverlight.xap',
			filters : [
				{title : "Image files", extensions : "jpg,gif,png,jpeg"}
			]
		});
		
		uploader.bind('Init', function(up, params) {
			if(params.runtime == "html4"){
				$('#slidedeck-slide-image-html4').show();
				$('#slidedeck-slide-image').hide();
			}
			
			//$('#slidedeck-slide-upload-files').html("<div>Current runtime: " + params.runtime + "</div>");
		});
		
		uploader.bind('FilesAdded', function(up, files) {
			$('#slidedeck-slide-upload-files').html("");
			
			for (var i in files) {
			    var label = files[i].name.substr(0,50) + ' (' + plupload.formatSize(files[i].size) + ')';
				$('#slidedeck-slide-upload-files').append('<div id="' + files[i].id + '" class="file-upload"><span class="label">' + label + '</span><span class="progress">' + label + '</span><button id="slidedeck-slide-upload-upload" class="bluebtn">Upload</button></div>').show();
			}
		});
		
		uploader.bind('UploadComplete', function(up, file){
		    $.get('<?php echo admin_url( 'admin-ajax.php' ) . '?action=slidedeck_get_slide_attachment_thumbnail_url&slide_id=' . $slide_id . '&_wpnonce=' . wp_create_nonce( "{$namespace}-get-slide-thumbnail-url" ); ?>', function(data){
		        var $thumbnail = $('#slidedeck-custom-slide-editor-form').find('.sd-flyout-thumbnail');
		        var label = file[0].name.length > 50 ? file[0].name.substr(0,50) + "&hellip;" : file[0].name;
		        $thumbnail.find('img').attr('src', data);
		        $thumbnail.find('.label').html(label);
		        $thumbnail.slideDown(500);
		        $('#sd-image-upload-container, #sd-image-upload, #slidedeck-custom-slide-editor-form .select-source').slideUp(500);
		    });
		});
		
		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id).find('span.progress').css('width', file.percent + "%");
		});
		
		$('#slidedeck-slide-upload-files').delegate('#slidedeck-slide-upload-upload', 'click', function(event){
			event.preventDefault();
			uploader.start();
		})
		
		uploader.init();
	})(jQuery, window, null);
</script>
