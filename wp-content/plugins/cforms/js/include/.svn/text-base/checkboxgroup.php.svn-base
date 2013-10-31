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

	<label for="cf_edit_label_group"><?php _e('Field label', 'cforms'); ?></label>
	<input type="text" id="cf_edit_label_group" name="cf_edit_label_group" value="">

	<div class="cf_edit_groups_header">
		<span class="cf_option"><?php _e('Check box/radio box option (displayed)', 'cforms'); ?></span>
		<span class="cf_optVal"><?php _e('Optional value (transmitted)', 'cforms'); ?></span>
		<span class="cf_chked" title="<?php _e('Set default state', 'cforms'); ?>"></span>
		<span class="cf_br" title="<?php _e('Carriage return / New Line', 'cforms'); ?>"></span>
	</div>

	<div id="cf_edit_groups">
	</div>
	<div class="add_group_item"><a href="#" id="add_group_button" class="cf_edit_plus"></a></div>

	<label style="clear:left; padding-top:5px;" for="cf_edit_title"><?php _e('Input field title (displayed when mouse hovers over field)', 'cforms'); ?></label>
	<input type="text" id="cf_edit_title" name="cf_edit_title" value="">

	<label for="cf_edit_customerr"><?php _e('Custom error message (make sure to enable custom, per field err messages!)', 'cforms'); ?></label>
	<input type="text" id="cf_edit_customerr" name="cf_edit_customerr" value="">

</form>