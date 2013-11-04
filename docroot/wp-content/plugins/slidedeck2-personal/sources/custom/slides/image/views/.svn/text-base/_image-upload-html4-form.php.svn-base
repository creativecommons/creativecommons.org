<!DOCTYPE html>
<html>
	<head></head>
	<body>
		<form id="slidedeck-slide-image-form" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post" enctype="multipart/form-data">
			<fieldset>
				<input type="hidden" name="action" value="slidedeck_slide_upload_image" />
				<input type="hidden" name="slide_id" value="<?php echo $slide_id; ?>" />
				<?php wp_nonce_field( "{$namespace}-slide-upload-image" ); ?>
				
				<p><label>Choose a file to upload: <input type="file" name="file" /></label> <input type="submit" value="Upload" class="button" /></p>
			</fieldset>
		</form>
	</body>
</html>