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

	<label for="cf_edit_label"><?php _e('Field label', 'cforms'); ?></label>
	<input type="text" id="cf_edit_label" name="cf_edit_label" value="">

	<label for="cf_edit_default"><?php _e('Default value', 'cforms'); ?></label>
	<input type="text" id="cf_edit_default" name="cf_edit_default" value="">

	<label for="cf_edit_regexp"><?php echo sprintf(__('Regular expression for field validation (e.g. %s). See Help! for more examples.', 'cforms'),'^[A-Za-z ]+$'); ?></label>
	<input type="text" id="cf_edit_regexp" name="cf_edit_regexp" value="">

	<label for="cf_edit_title"><?php _e('Input field title (displayed when mouse hovers over field)', 'cforms'); ?></label>
	<input type="text" id="cf_edit_title" name="cf_edit_title" value="">

	<label for="cf_edit_customerr"><?php _e('Custom error message (make sure to enable custom, per field err messages!)', 'cforms'); ?></label>
	<input type="text" id="cf_edit_customerr" name="cf_edit_customerr" value="">

</form>