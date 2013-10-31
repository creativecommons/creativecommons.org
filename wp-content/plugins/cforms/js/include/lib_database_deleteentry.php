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

if( !current_user_can('track_cforms') )
	wp_die("access restricted.");

global $wpdb;

$wpdb->cformssubmissions	= $wpdb->prefix . 'cformssubmissions';
$wpdb->cformsdata       	= $wpdb->prefix . 'cformsdata';

### new global settings container, will eventually be the only one!
$cformsSettings = get_option('cforms_settings');

$sub_id = $_POST['id'];

if ( $sub_id<>'' && $sub_id >= 0){

	$sql 		= "SELECT field_val,form_id FROM {$wpdb->cformsdata},{$wpdb->cformssubmissions} WHERE sub_id = '$sub_id' AND id=sub_id AND field_name LIKE '%[*%'";
	$filevalues = $wpdb->get_results($sql);

	$del='';
	$found = 0;

	foreach( $filevalues as $fileval ) {

		$temp = explode( '$#$',stripslashes(htmlspecialchars($cformsSettings['form'.$fileval->form_id]['cforms'.$fileval->form_id.'_upload_dir'])) );
		$fileuploaddir = $temp[0];

		$file = $fileuploaddir.'/'.$sub_id.'-'.$fileval->field_val;

		if ( $fileval->field_val <> '' ){
			if ( file_exists( $file ) ){
				unlink ( $file );
				$found = $found | 1;
			}
			else{
				$found = $found | 2;
			}
		}
	}

	if ( $found==3 )
		$del = ' '.__('(some associated attachment/s were not found!)','cforms');
	else if ( $found==2 )
		$del = ' '.__('(associated attachment/s were not found!)','cforms');
	else if ( $found==1 )
		$del = ' '.__('(including all attachment/s)','cforms');

	$nuked = $wpdb->query("DELETE FROM {$wpdb->cformssubmissions} WHERE id = '$sub_id'");
	$nuked = $wpdb->query("DELETE FROM {$wpdb->cformsdata} WHERE sub_id = '$sub_id'");

	?>
	<p><strong><?php echo $i; ?> <?php _e('Entry successfully removed', 'cforms'); echo $del; ?>.</strong></p>
	<?php
}
?>