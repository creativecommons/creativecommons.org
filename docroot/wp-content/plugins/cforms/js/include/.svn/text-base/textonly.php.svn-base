<?php

### supporting WP2.6 wp-load & custom wp-content / plugin dir
if ( file_exists('../../abspath.php') )
	include_once('../../abspath.php');
else
	$abspath='../../../../../';

if ( file_exists( $abspath . 'wp-load.php') )
	require_once( $abspath . 'wp-load.php' );
else
	require_once( $abspath . 'wp-config.php' );
?>

<form method="post">

	<label for="cf_edit_label"><?php _e('Text (HTML is supported)', 'cforms'); ?></label>
	<input type="text" id="cf_edit_label" name="cf_edit_label" value="">

	<label for="cf_edit_css"><?php _e('CSS (assigns class to this form element)', 'cforms'); ?></label>
	<input type="text" id="cf_edit_css" name="cf_edit_css" value="">

	<label for="cf_edit_style"><?php echo sprintf(__('Inline style (e.g. %s)', 'cforms'),'<strong>color:red; font-size:11px;</strong>'); ?></label>
	<input type="text" id="cf_edit_style" name="cf_edit_style" value="">

</form>