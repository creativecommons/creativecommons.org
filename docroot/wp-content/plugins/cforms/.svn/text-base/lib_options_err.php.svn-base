<?php

$userconfirm = $cformsSettings['form'.$no]['cforms'.$no.'_confirmerr'];

### check URI
$request_uri = $_SERVER['REQUEST_URI'];
if (!isset($_SERVER['REQUEST_URI'])){
    if(isset($_SERVER['SCRIPT_NAME']))
        $request_uri = $_SERVER['SCRIPT_NAME'];
    else
        $request_uri = $_SERVER['PHP_SELF'];
}

$uriprefix='';
if ( ($x=strpos($request_uri,'/wp-admin/')) > 0 )
	$uriprefix = substr($request_uri,0,$x);


echo '<form name="errmessages" action="#" method="post"><input type="hidden" name="switchform" value="'.$noDISP.'"/>';

if ( $cformsSettings['form'.$no]['cforms'.$no.'_showpos']=='' && (($userconfirm&1)==0) ) {
		$text = sprintf(__('please check the <a href="%s" %s>success/failure message settings</a> and >>Show messages<< options below!', 'cforms'),'#anchormessage','onclick="setshow(1)"');
		showmesssage(1);
}


if ( $cformsSettings['form'.$no]['cforms'.$no.'_upload_dir']=='' && (($userconfirm&2)==0) ) {
		$text = sprintf(__('please check the new <a href="%s" %s>file upload/attachment</a> relevant settings below! You can ignore the message if you\'re not using any file upload field(s).', 'cforms'),'#fileupload','onclick="setshow(0)"');
		showmesssage(2);
}


### check for set email header
if ( $cformsSettings['form'.$no]['cforms'.$no.'_header']=='' && (($userconfirm&4)==0) ) {
		$text = sprintf(__('please check the <a href="%s" %s>email header settings</a> below!', 'cforms'),'#anchoremail','onclick="setshow(2)"');
		showmesssage(4);
}

### check for URI prefix
if ( $uriprefix<>'' && (($userconfirm&8)==0) ) {
		$text = sprintf(__('It seems that your ROOT directory for Wordpress is %s. <strong>cforms</strong> tried to auto-adjust its settings accordingly, however if you still encounter issues with Ajax (form submission & CAPTCHA reset) please open the file %s in your cforms plugin folder and check the %s variable. (After changing the file, please emtpy your browser cache!)', 'cforms'),"<strong>$uriprefix</strong>",'<strong>js/cforms.js</strong>','<strong>sajax_uri</strong>');
		showmesssage(8);
}

### check for TAF
if ( substr($cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'],0,1)=='1' && (($userconfirm&16)==0) ) {
		$text = __('You have enabled the <strong>Tell a Friend</strong> feature for this form, please make sure you follow the guidelines on the HELP! page <strong>otherwise your form may not show</strong>!', 'cforms');
		showmesssage(16);
}

### 32 taken in global settings!

### set fancy errors by default
$tmp = $cformsSettings['form'.$no]['cforms'.$no.'_showpos'];
if ( strlen($tmp)<=2 ) {
	$cformsSettings['form'.$no]['cforms'.$no.'_showpos'] = $tmp.'yy';
	$text = __('Please note that the <strong>fancy error</strong> feature has been enabled. You can turn it off in the <em>Redirection, Messages...</em> section below.', 'cforms');
	showmesssage(64);

	update_option('cforms_settings',$cformsSettings);
}

echo '</form>';

function showmesssage($confirm){
	global $no, $userconfirm, $text, $cformsSettings;

	if ( $confirm<8 )
		$text = __('It seems that you have recently upgraded cforms','cforms').' '.$text;

	if ( isset($_GET['cf_confirm']) && $_GET['cf_confirm']=='confirm'.$confirm ){
		$cformsSettings['form'.$no]['cforms'.$no.'_confirmerr'] = ($userconfirm|$confirm);
		update_option('cforms_settings',$cformsSettings);
    }
	else
		echo '<div id="message'.$confirm.'" class="updated fade"><p>'.$text.'</p><p><a href="?page='.$cformsSettings['global']['plugindir'].'/cforms-options.php&cf_confirm=confirm'.$confirm.'" class="rm_button allbuttons">'.__('Remove Message','cforms').'</a></p></div>';
}

?>