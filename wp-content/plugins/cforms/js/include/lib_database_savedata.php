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

$f_id   = $_POST['id'];
$newVal = addslashes($_POST['value']);

if ( $f_id<>'' ) {

	$sql="UPDATE {$wpdb->cformsdata} SET field_val='$newVal' WHERE f_id = '$f_id'";
	$entries = $wpdb->get_results($sql);
	echo str_replace("\n",'<br />',stripslashes(stripslashes($newVal)));

}
?>