<?php
###
###  ajax support for admin pages
###

### supporting WP2.6 wp-load & custom wp-content / plugin dir
if ( file_exists('abspath.php') )
	include_once('abspath.php');
else
	$abspath='../../../';

if ( file_exists( $abspath . 'wp-load.php') )
	require_once( $abspath . 'wp-load.php' );
else
	require_once( $abspath . 'wp-config.php' );


$cformsSettings = get_option('cforms_settings');

###
###  reset captcha image
###
if ( isset($_POST['captcha']) ){

	$cap = $cformsSettings['global']['cforms_captcha_def'];
	$c1 = prep( $cap['c1'],'3' );
	$c2 = prep( $cap['c2'],'5' );
	$ac = prep( urlencode($cap['ac']),urlencode('abcdefghijkmnpqrstuvwxyz23456789') );
	$i = prep( $cap['i'],'' );
	$h = prep( $cap['h'],25 );
	$w = prep( $cap['w'],115 );
	$c = prep( $cap['c'],'000066' );
	$l = prep( $cap['l'],'000066' );
	$f = prep( $cap['f'],'font4.ttf' );
	$a1 = prep( $cap['a1'],-12 );
	$a2 = prep( $cap['a2'],12 );
	$f1 = prep( $cap['f1'],17 );
	$f2 = prep( $cap['f2'],19 );
	$bg = prep( $cap['bg'],'1.gif');
	$captcha_uri = "&amp;c1={$c1}&amp;c2={$c2}&amp;ac={$ac}&amp;i={$i}&amp;w={$w}&amp;h={$h}&amp;c={$c}&amp;l={$l}&amp;f={$f}&amp;a1={$a1}&amp;a2={$a2}&amp;f1={$f1}&amp;f2={$f2}&amp;b={$bg}";
	echo $cformsSettings['global']['cforms_root'].'/cforms-captcha.php?ts='.$no.str_replace('&amp;','&',$captcha_uri);
}
?>