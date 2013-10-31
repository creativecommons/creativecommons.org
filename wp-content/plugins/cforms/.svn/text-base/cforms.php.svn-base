<?php
/*



	Copyright 2006-2009  Oliver Seidel (email : oliver.seidel @ deliciousdays.com)

	Software included:  PHPMailer - PHP email class
	Copyright (c) 2004-2007, Andy Prevost. All Rights Reserved.
    Copyright (c) 2001-2003, Brent R. Matzelle

	see also

	____LICENSE_CREDITS.txt
	____HISTORY.txt


Plugin Name: cforms
Plugin URI: http://www.deliciousdays.com/cforms-plugin
Description: cformsII offers unparalleled flexibility in deploying contact forms across your blog. Features include: comprehensive SPAM protection, Ajax support, Backup & Restore, Multi-Recipients, Role Manager support, Database tracking and many more. Please see ____HISTORY.txt for <strong>what's new</strong> and current <strong>bugfixes</strong>.
Author: Oliver Seidel
Version: 11.7
Author URI: http://www.deliciousdays.com



*/

global $localversion;
$localversion = '11.7';

### debug messages
$cfdebug = false;
$cfdebugmsg = '';

### db settings
global $wpdb, $wp_db_version, $cformsSettings;

$cformsSettings				= get_option('cforms_settings');
$wpdb->cformssubmissions	= $wpdb->prefix . 'cformssubmissions';
$wpdb->cformsdata       	= $wpdb->prefix . 'cformsdata';

### admin functions
require_once(dirname(__FILE__) . '/lib_functions.php');



### update 9x notice?
$dummy = __('Please go to the <a href="%s">cforms admin page</a> and run the update process.', 'cforms');
add_action('admin_notices', create_function('', 'global $plugindir, $cformsSettings; if (is_array($cformsSettings) && $cformsSettings[\'global\'][\'update\']) echo \'<div class="error"><p><strong>\' . sprintf( __(\'Please go to the <a href="%s">cforms admin page</a> and run the update process.\', \'cforms\') ,\'admin.php?page=\'.$plugindir.\'/cforms-global-settings.php\') . \'</strong></p></div>\';') );



### activate cforms
function cforms_activate() {
	global $localversion;
	cforms_init();
	require_once(dirname(__FILE__) . '/lib_activate.php');
}
add_action('activate_' . plugin_basename(__FILE__), 'cforms_activate' );



### settings corrputed?
if ( !is_array($cformsSettings) ){
	add_action('admin_menu', 'settings_corrupted');
    return;
}
function settings_corrupted() {
	$tmp = basename(dirname(__FILE__));

	if (function_exists('add_menu_page')){
		add_menu_page(__('cformsII', 'cforms'), __('cformsII', 'cforms'), 'manage_cforms', $tmp.'/cforms-corrupted.php', '', get_cf_plugindir().'/'.$tmp.'/images/cformsicon.gif' );
		add_submenu_page($tmp.'/cforms-corrupted.php', __('Corrupted Settings', 'cforms'), __('Corrupted Settings', 'cforms'), 'manage_cforms', $tmp.'/cforms-corrupted.php' );
    }
	elseif (function_exists('add_management_page'))
		add_management_page(__('cformsII', 'cforms'), __('cformsII', 'cforms'), 'manage_cforms', $tmp.'/cforms-corrupted.php');

    add_action('wp_print_scripts', 'cforms_scripts_corrupted' );
}
function cforms_scripts_corrupted(){
	echo	'<link rel="stylesheet" type="text/css" href="' . get_cf_plugindir() . basename(dirname(__FILE__)). '/cforms-admin.css" />' . "\n";
}


### load add'l files
if (version_compare(PHP_VERSION, '5.0.0', '>'))
	require_once(dirname(__FILE__) . '/lib_email.php');
else
	require_once(dirname(__FILE__) . '/lib_email_php4.php');

require_once (dirname(__FILE__) . '/lib_aux.php');
require_once (dirname(__FILE__) . '/lib_editor.php');



### http://trac.wordpress.org/ticket/3002
$plugindir   = $cformsSettings['global']['plugindir'];
$cforms_root = $cformsSettings['global']['cforms_root'];



### session control for multi-page form
add_action('template_redirect', 'start_cforms_session');
function start_cforms_session() {
	@session_cache_limiter('private, must-revalidate');
	@session_cache_expire(0);
	@session_start();
}



###
### main function
###
function cforms($args = '',$no = '') {

	global $smtpsettings, $subID, $cforms_root, $wpdb, $track, $wp_db_version, $cformsSettings;

	parse_str($args, $r);

    $oldno = ($no=='1')?'':$no;  ### remeber old val, to reset session when in new MP form

    ##debug
    db("Original form on page #$oldno");

	### multi page form: overwrite $no
    $isWPcommentForm = (substr($cformsSettings['form'.$oldno]['cforms'.$oldno.'_tellafriend'],0,1)=='2');
    $isMPform = $cformsSettings['form'.$oldno]['cforms'.$oldno.'_mp']['mp_form'];
    $isTAF = substr($cformsSettings['form'.$oldno]['cforms'.$oldno.'_tellafriend'],0,1);

    ##debug
    db("Comment form = $isWPcommentForm");
    db("Multi-page form = $isMPform");

	if( $isMPform && is_array($_SESSION['cforms']) && $_SESSION['cforms']['current']>0 && !$isWPcommentForm )
		$no = $_SESSION['cforms']['current'];

	### Safety, in case someone uses '1' for the default form
	$no = ($no=='1')?'':$no;

    ##debug
    db("Switch to form #$no");

    $moveBack=false;
	### multi page form: reset button
	if( isset($_REQUEST['resetbutton'.$no]) && is_array($_SESSION['cforms']) ){
		$no = $oldno;
		unset($_SESSION['cforms']);
        $_SESSION['cforms']['current']=0;
	    $_SESSION['cforms']['first']=$oldno;
	    $_SESSION['cforms']['pos']=1;
	    ##debug
	    db("Reset-Button pressed");
	}
	else ### multi page form: back button
	if( isset($_REQUEST['backbutton'.$no]) && isset($_SESSION['cforms']) && ($_SESSION['cforms']['pos']-1)>=0){
		$no = $_SESSION['cforms']['list'][($_SESSION['cforms']['pos']--)-1];
	    $_SESSION['cforms']['current']=$no;
        $moveBack=true;
	    ##debug
	    db("Back-Button pressed");
	}
	else ### mp init: must be mp, first & not submitted!
	if( $isMPform && $cformsSettings['form'.$oldno]['cforms'.$oldno.'_mp']['mp_first'] && !isset($_REQUEST['sendbutton'.$no]) ){
	    ##debug
	    db("Current form is *first* MP-form");
        db("Session found, you're on the first form and session is reset!");

        $no = ($oldno=='1')?'':$oldno; ### restore old val
        unset($_SESSION['cforms']);

        $_SESSION['cforms']['current']=0;
        $_SESSION['cforms']['first']=$no;
        $_SESSION['cforms']['pos']=1;
    }

	##debug
	db(print_r($_SESSION,1));


	### custom fields support
	if ( !(strpos($no,'+') === false) ) {
	    $no = substr($no,0,-1);
		$customfields = build_fstat($args);
		$field_count = count($customfields);
		$custom=true;
	} else {
		$custom=false;
		$field_count = $cformsSettings['form'.$no]['cforms'.$no.'_count_fields'];
	}


	$content = '';

	$err=0;
	$filefield=0;

	$validations = array();
	$all_valid = 1;
	$off=0;
	$fieldsetnr=1;

	$c_errflag=false;
	$custom_error='';
	$usermessage_class='';


	### get user credentials
	if ( function_exists('wp_get_current_user') )
		$user = wp_get_current_user();


    ### non Ajax method
    if( isset($_REQUEST['sendbutton'.$no]) ) {
		require_once (dirname(__FILE__) . '/lib_nonajax.php');
		$usermessage_class = $all_valid?' success':' failure';
	}

    ### called from lib_WPcomments ?
	if ( $isWPcommentForm && $send2author )
		return $all_valid;




	###
	###
	### paint form
	###
	###
	$success=false;

    ###  fix for WP Comment (loading after redirect)
	if ( isset($_GET['cfemail']) && $isWPcommentForm ){
		$usermessage_class = ' success';
		$success=true;
		if ( $_GET['cfemail']=='sent' )
			$usermessage_text = preg_replace ( '|\r\n|', '<br />', stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_success']) );
		elseif ( $_GET['cfemail']=='posted' )
			$usermessage_text = preg_replace ( '|\r\n|', '<br />', stripslashes($cformsSettings['form'.$no]['cforms_commentsuccess']) );
	}


	$break='<br />';
	$nl="\n";
	$tab="\t";
	$tt="\t\t";
	$ntt="\n\t\t";
	$nttt="\n\t\t\t";

	### either show info message above or below
	$usermessage_text	= check_default_vars($usermessage_text,$no);
	$usermessage_text	= check_cust_vars($usermessage_text,$track,$no);
	### logic: possibly change usermessage
	if ( function_exists('my_cforms_logic') )
	    $usermessage_text = my_cforms_logic($trackf, $usermessage_text,'successMessage');

   	$umc = ($usermessage_class<>''&&$no>1)?' '.$usermessage_class.$no:'';

    ##debug
    db("User info for form #$no");

	### where to show message
	if( substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],0,1)=='y' ) {
		$content .= $ntt . '<div id="usermessage'.$no.'a" class="cf_info' . $usermessage_class . $umc .' ">' . $usermessage_text . '</div>';
		$actiontarget = 'a';
 	} else if ( substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],1,1)=='y' )
		$actiontarget = 'b';


	### multi page form: overwrite $no, move on to next form
	if( $all_valid && isset($_REQUEST['sendbutton'.$no]) ){

		$isMPformNext=false; ### default
    	$oldcurrent = $no;

		if( $isMPform && isset($_SESSION['cforms']) && $_SESSION['cforms']['current']>0 && $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_next']<>-1 ){

        	$isMPformNext=true;
            $no = check_form_name( $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_next'] );

	        ##debug
	        db("Session active and now moving on to form #$no");

	        ### logic: possibly change next form
	        if ( function_exists('my_cforms_logic') )
	            $no = my_cforms_logic($trackf, $no,"nextForm");  ### use trackf!

			$oldcurrent = $_SESSION['cforms']['current'];
	        $_SESSION['cforms']['current']=$no==''?1:$no;

			$field_count = $cformsSettings['form'.$no]['cforms'.$no.'_count_fields'];

	    }elseif( $isMPform && $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_next']==-1 ){

	        ##debug
	        db("Session was active but is being reset now");

			$oldcurrent = $no;

	        $no = $_SESSION['cforms']['first'];
	        unset( $_SESSION['cforms'] );

	        $_SESSION['cforms']['current']=0;
	        $_SESSION['cforms']['first']=$no;
	        $_SESSION['cforms']['pos']=1;

			$field_count = $cformsSettings['form'.$no]['cforms'.$no.'_count_fields'];

        }

	}

    ##debug
    db("All good, currently on form #$no");

	##debug: optional
	## db(print_r($_SESSION,1));
	## db(print_r($track,1));

	### redirect == 2 : hide form?    || or if max entries reached! w/ SESSION support if#2
	if (  $all_valid && (
    		( $cformsSettings['form'.$no]['cforms'.$no.'_hide'] && isset($_REQUEST['sendbutton'.$no]) ) ||
    	  	( $cformsSettings['form'.$oldcurrent]['cforms'.$oldcurrent.'_hide'] && isset($_REQUEST['sendbutton'.$oldcurrent]) )
          				)
       )
		return $content;
	else if ( ($cformsSettings['form'.$no]['cforms'.$no.'_maxentries']<>'' && get_cforms_submission_left($no)<=0) || !cf_check_time($no) ){

		if ( $cflimit == "reached" )
			return stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_limittxt']);
		else
			return $content.stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_limittxt']);

	}



 	### alternative form action
	$alt_action=false;
	if( $cformsSettings['form'.$no]['cforms'.$no.'_action']=='1' ) {
		$action = $cformsSettings['form'.$no]['cforms'.$no.'_action_page'];
		$alt_action=true;
	}
	else if( $isWPcommentForm )
		$action = $cforms_root . '/lib_WPcomment.php'; ### re-route and use WP comment processing
 	else
		$action = get_current_page(false) . '#usermessage'. $no . $actiontarget;


	$enctype = $cformsSettings['form'.$no]['cforms'.$no.'_formaction'] ? 'enctype="application/x-www-form-urlencoded"':'enctype="multipart/form-data"';

	### start with form tag
	$content .= $ntt . '<form '.$enctype.' action="' . $action . '" method="post" class="cform ' . sanitize_title_with_dashes($cformsSettings['form'.$no]['cforms'.$no.'_fname']). ' ' .( $cformsSettings['form'.$no]['cforms'.$no.'_dontclear']?' cfnoreset':'' ). '" id="cforms'.$no.'form">' . $nl;


    ### Session item counter (for default values)
    $sItem=1;

	### start with no fieldset
	$fieldsetopen = false;
	$verification = false;

	$captcha = false;
	$upload = false;
	$fscount = 1;
	$ol = false;

	for($i = 1; $i <= $field_count; $i++) {

		if ( !$custom )
      		$field_stat = explode('$#$', $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . $i]);
		else
    		$field_stat = explode('$#$', $customfields[$i-1]);

		$field_name       = $field_stat[0];
		$field_type       = $field_stat[1];
		$field_required   = $field_stat[2];
		$field_emailcheck = $field_stat[3];
		$field_clear      = $field_stat[4];
		$field_disabled   = $field_stat[5];
		$field_readonly   = $field_stat[6];


		### ommit certain fields
		if( in_array($field_type,array('cauthor','url','email')) && $user->ID )
			continue;


		### check for custom err message and split field_name
	    $obj = explode('|err:', $field_name,2);
	    $fielderr = $obj[1];

		if ( $fielderr <> '')	{
		    switch ( $field_type ) {
			    case 'upload':
					$custom_error .= 'cf_uploadfile' . $no . '-'. $i . '$#$'.$fielderr.'|';
	    			break;

			    case 'captcha':
					$custom_error .= 'cforms_captcha' . $no . '$#$'.$fielderr.'|';
	    			break;

			    case 'verification':
					$custom_error .= 'cforms_q'. $no . '$#$'.$fielderr.'|';
	    			break;

				case "cauthor":
				case "url":
				case "email":
				case "comment":
					$custom_error .= $field_type . '$#$'.$fielderr.'|';
	    			break;

			    default:
    				preg_match('/^([^#\|]*).*/',$field_name,$input_name);
    				if ( strpos($input_name[1],'[id:')>0 )
    					preg_match ('/\[id:(.+)\]/',$input_name[1],$input_name);

					$custom_error .= ($cformsSettings['form'.$no]['cforms'.$no.'_customnames']=='1')?cf_sanitize_ids($input_name[1]):'cf'.$no.'_field_'.$i;
					$custom_error .= '$#$'.$fielderr.'|';
	    			break;
		    }
		}


		### check for title attrib
	    $obj = explode('|title:', $obj[0],2);
		$fieldTitle = ($obj[1]<>'')?' title="'.str_replace('"','&quot;',stripslashes($obj[1])).'"':'';


		### special treatment for selectboxes
		if (  in_array($field_type,array('multiselectbox','selectbox','radiobuttons','send2author','luv','subscribe','checkbox','checkboxgroup','ccbox','emailtobox'))  ){

			$chkboxClicked = array();
			if (  in_array($field_type,array('luv','subscribe','checkbox','ccbox')) && strpos($obj[0],'|set:')>1 ){
				$chkboxClicked = explode('|set:', stripslashes($obj[0]) );
				$obj[0] = $chkboxClicked[0];
			}

			$options = explode('#', stripslashes($obj[0]) );
            $field_name = $options[0];

		}


		### check if fieldset is open
		if ( !$fieldsetopen && !$ol && $field_type<>'fieldsetstart') {
			$content .= $tt . '<ol class="cf-ol">';
			$ol = true;
		}


		$labelclass='';
		### visitor verification
		if ( !$verification && $field_type == 'verification' ) {
			srand(microtime()*1000003);
        	$qall = explode( "\r\n", $cformsSettings['global']['cforms_sec_qa'] );
			$n = rand(0,(count(array_keys($qall))-1));
			$q = $qall[ $n ];
			$q = explode( '=', $q );  ### q[0]=qestion  q[1]=answer
			$field_name = stripslashes(htmlspecialchars($q[0]));
			$labelclass = ' class="secq"';
		}
		else if ( $field_type == 'captcha' )
			$labelclass = ' class="seccap"';


		$defaultvalue = '';
		### setting the default val & regexp if it exists
		if ( ! in_array($field_type,array('fieldsetstart','fieldsetend','radiobuttons','send2author','luv','subscribe','checkbox','checkboxgroup','ccbox','emailtobox','multiselectbox','selectbox','verification')) ) {

		    ### check if default val & regexp are set
		    $obj = explode('|', $obj[0],3);

			if ( $obj[2] <> '')	$reg_exp = str_replace('"','&quot;',stripslashes($obj[2])); else $reg_exp='';
		    if ( $obj[1] <> '')	$defaultvalue = str_replace('"','&quot;', check_default_vars(stripslashes(($obj[1])),$no) );

			$field_name = $obj[0];
		}


		### label ID's
		$labelIDx = '';
		$labelID  = ($cformsSettings['global']['cforms_labelID']=='1')?' id="label-'.$no.'-'.$i.'"':'';

		### <li> ID's
		$liID = ( $cformsSettings['global']['cforms_liID']=='1' ||
				  substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],2,1)=="y" ||
				  substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],3,1)=="y" )?' id="li-'.$no.'-'.$i.'"':'';

		### input field names & label
		if ( $cformsSettings['form'.$no]['cforms'.$no.'_customnames']=='1' ){

			if ( strpos($field_name,'[id:')!==false ){
				$idPartA = strpos($field_name,'[id:');
				$idPartB = strpos($field_name,']',$idPartA);
				$input_id = $input_name = cf_sanitize_ids( substr($field_name,$idPartA+4,($idPartB-$idPartA)-4) );

				$field_name = substr_replace($field_name,'',$idPartA,($idPartB-$idPartA)+1);

			} else
				$input_id = $input_name = cf_sanitize_ids(stripslashes($field_name));

		} else
			$input_id = $input_name = 'cf'.$no.'_field_'.$i;


		$field_class = '';
		$field_value = '';

		switch ($field_type){
			case 'luv':
				$input_id = $input_name = 'luv';
				break;
			case 'subscribe':
				$input_id = $input_name = 'subscribe';
				break;
			case 'verification':
				if( is_user_logged_in() && $cformsSettings['global']['cforms_captcha_def']['foqa']<>'1' )
					continue(2);
				$input_id = $input_name = 'cforms_q'.$no;
				break;
			case 'captcha':
				if( is_user_logged_in() && $cformsSettings['global']['cforms_captcha_def']['fo']<>'1' )
					continue(2);
				$input_id = $input_name = 'cforms_captcha'.$no;
				break;
			case 'upload':
				$input_id = $input_name = 'cf_uploadfile'.$no.'-'.$i;
				$field_class = 'upload';
				break;
			case "send2author":
			case "email":
			case "cauthor":
			case "url":
				$input_id = $input_name = $field_type;
			case "datepicker":
			case "yourname":
			case "youremail":
			case "friendsname":
			case "friendsemail":
			case "textfield":
			case "pwfield":
				$field_class = 'single';
				break;
			case "hidden":
				$field_class = 'hidden';
				break;
			case 'comment':
				$input_id = $input_name = $field_type;
				$field_class = 'area';
				break;
			case 'textarea':
				$field_class = 'area';
				break;
		}



		### additional field classes
		if ( $field_disabled )		$field_class .= ' disabled';
		if ( $field_readonly )		$field_class .= ' readonly';
		if ( $field_emailcheck )	$field_class .= ' fldemail';
		if ( $field_required ) 		$field_class .= ' fldrequired';


		### error ?
		$liERR = $insertErr = '';


		### only for mp forms
		if( $moveBack || $isMPformNext )
				$field_value = htmlspecialchars( stripslashes(  $_SESSION['cforms']['cf_form'.$no][ $_SESSION['cforms']['cf_form'.$no]['$$$'.($sItem++)] ] ) );


		if( !$all_valid ){
			### errors...
			if ( $validations[$i]==1 )
				$field_class .= '';
			else{
				$field_class .= ' cf_error';

				### enhanced error display
				if(substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],2,1)=="y")
					$liERR = 'cf_li_err';
				if(substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],3,1)=="y")
					$insertErr = ($fielderr<>'')?'<ul class="cf_li_text_err"><li>'.stripslashes($fielderr).'</li></ul>':'';
			}


			if ( $field_type == 'multiselectbox' || $field_type == 'checkboxgroup' ){
				$field_value = $_REQUEST[$input_name];  ### in this case it's an array! will do the stripping later
			}
			else
				$field_value = htmlspecialchars(stripslashes($_REQUEST[$input_name]));

		} else if( (!isset($_REQUEST['sendbutton'.$no]) && isset($_REQUEST[$input_name])) || $cformsSettings['form'.$no]['cforms'.$no.'_dontclear'] ){

		    ### only pre-populating fields...
			if ( $field_type == 'multiselectbox' || $field_type == 'checkboxgroup' )
				$field_value = $_REQUEST[$input_name];  ### in this case it's an array! will do the stripping later
			else
				$field_value = htmlspecialchars(stripslashes($_REQUEST[$input_name]));

	    }


		### print label only for non "textonly" fields! Skip some others too, and handle them below indiv.
		if( ! in_array($field_type,array('hidden','textonly','fieldsetstart','fieldsetend','ccbox','luv','subscribe','checkbox','checkboxgroup','send2author','radiobuttons')) )
			$content .= $nttt . '<li'.$liID.' class="'.$liERR.'">'.$insertErr.'<label' . $labelID . ' for="'.$input_id.'"'. $labelclass . '><span>' . stripslashes(($field_name)) . '</span></label>';


		### if not reloaded (due to err) then use default values
		if ( $field_value=='' && $defaultvalue<>'' )
			$field_value=$defaultvalue;


		### field disabled or readonly, greyed out?
		$disabled = $field_disabled?' disabled="disabled"':'';
		$readonly = $field_readonly?' readonly="readonly"':'';


		### add input field
		$dp = '';
		$naming = false;
		$field  = '';
		$val = '';
		$force_checked = false;
		$cookieset = '';

		switch($field_type) {

			case "upload":
	  			$upload=true;  ### set upload flag for ajax suppression!
				$field = '<input' . $readonly.$disabled . ' type="file" name="cf_uploadfile'.$no.'[]" id="cf_uploadfile'.$no.'-'.$i.'" class="cf_upload ' . $field_class . '"'.$fieldTitle.'/>';
				break;

			case "textonly":
				$field .= $nttt . '<li'.$liID.' class="textonly' . (($defaultvalue<>'')?' '.$defaultvalue:'') . '"' . (($reg_exp<>'')?' style="'.$reg_exp.'" ':'') . '>' . stripslashes(($field_name)) . '</li>';
				break;

			case "fieldsetstart":
				if ($fieldsetopen) {
						$field = $ntt . '</ol>' . $nl .
								 $tt . '</fieldset>' . $nl;
						$fieldsetopen = false;
						$ol = false;
				}
				if (!$fieldsetopen) {
						if ($ol)
							$field = $ntt . '</ol>' . $nl;

						$field .= $tt .'<fieldset class="cf-fs'.$fscount++.'">' . $nl .
								  $tt . '<legend>' . stripslashes($field_name) . '</legend>' . $nl .
								  $tt . '<ol class="cf-ol">';
						$fieldsetopen = true;
						$ol = true;
		 		}
				break;

			case "fieldsetend":
				if ($fieldsetopen) {
						$field = $ntt . '</ol>' . $nl .
								 $tt . '</fieldset>' . $nl;
						$fieldsetopen = false;
						$ol = false;
				} else $field='';
				break;

			case "verification":
				$field = '<input type="text" name="'.$input_name.'" id="cforms_q'.$no.'" class="secinput ' . $field_class . '" value=""'.$fieldTitle.'/>';
		    	$verification=true;
				break;

			case "captcha":
				$field = '<input type="text" name="'.$input_name.'" id="cforms_captcha'.$no.'" class="secinput' . $field_class . '" value=""'.$fieldTitle.'/>'.
						 '<img id="cf_captcha_img'.$no.'" class="captcha" src="'.$cforms_root.'/cforms-captcha.php?ts='.$no.get_captcha_uri().'" alt=""/>'.
						 '<a title="'.__('reset captcha image', 'cforms').'" href="javascript:reset_captcha(\''.$no.'\')"><img class="captcha-reset" src="'.$cforms_root.'/images/spacer.gif" alt="Captcha"/></a>';
		    	$captcha=true;
				break;

			case "cauthor":
				$cookieset = 'comment_author_'.COOKIEHASH;
			case "url":
				$cookieset = ($cookieset=='')?'comment_author_url_'.COOKIEHASH:$cookieset;
			case "email":
				$cookieset = ($cookieset=='')?'comment_author_email_'.COOKIEHASH:$cookieset;
				$field_value = ( $_COOKIE[$cookieset]<>'' ) ? $_COOKIE[$cookieset] : $field_value;
			case "datepicker":
			case "yourname":
			case "youremail":
			case "friendsname":
			case "friendsemail":
			case "textfield":
			case "pwfield":

				$field_value = check_post_vars($field_value);

				$type = ($field_type=='pwfield')?'password':'text';
				$field_class = ($field_type=='datepicker')?$field_class.' cf_date':$field_class;

			    $onfocus = $field_clear?' onfocus="clearField(this)" onblur="setField(this)"' : '';

				$field = '<input' . $readonly.$disabled . ' type="'.$type.'" name="'.$input_name.'" id="'.$input_id.'" class="' . $field_class . '" value="' . $field_value  . '"'.$onfocus.$fieldTitle.'/>';
				  if ( $reg_exp<>'' )
	           		 $field .= '<input type="hidden" name="'.$input_name.'_regexp" id="'.$input_id.'_regexp" value="'.$reg_exp.'"'.$fieldTitle.'/>';

				$field .= $dp;
				break;

			case "hidden":

				$field_value = check_post_vars($field_value);

                if ( preg_match('/^<([a-zA-Z0-9]+)>$/',$field_value,$getkey) )
                    $field_value = $_GET[$getkey[1]];

				$field .= $nttt . '<li class="cf_hidden"><input type="hidden" class="cfhidden" name="'.$input_name.'" id="'.$input_id.'" value="' . $field_value  . '"'.$fieldTitle.'/></li>';
				break;

			case "comment":
			case "textarea":
			    $onfocus = $field_clear?' onfocus="clearField(this)" onblur="setField(this)"' : '';

				$field = '<textarea' . $readonly.$disabled . ' cols="30" rows="8" name="'.$input_name.'" id="'.$input_id.'" class="' . $field_class . '"'. $onfocus.$fieldTitle.'>' . $field_value  . '</textarea>';
				  if ( $reg_exp<>'' )
	           		 $field .= '<input type="hidden" name="'.$input_name.'_regexp" id="'.$input_id.'_regexp" value="'.$reg_exp.'"'.$fieldTitle.'/>';
				break;

			case "subscribe":
				if ( class_exists('sg_subscribe') && $field_type=='subscribe' ){
					global $sg_subscribe;
					sg_subscribe_start();
					if( ($email = $sg_subscribe->current_viewer_subscription_status())=='admin' && current_user_can('manage_options') ){
						$field .= '<li'.$liID.'>'.str_replace('[manager_link]', $sg_subscribe->manage_link($email, true, false), $sg_subscribe->author_text).'</li>';
						continue;
					}else if($email<>''){
						$field .= '<li'.$liID.'>'.str_replace('[manager_link]', $sg_subscribe->manage_link($email, true, false), $sg_subscribe->subscribed_text).'</li>';
						continue;
					}
					$val = ' value="subscribe"';
				}
			case "luv":
				if ( function_exists('comment_luv') && $field_type=='luv' ){
					get_currentuserinfo() ;
					global $user_level;
					if( $user_level==10 )
						continue (2);
					//empty for now
					$val = ' value="luv"';
				}
	   		case "ccbox":
			case "checkbox":
				if ( ! $field_value )
					$preChecked = ( strpos($chkboxClicked[1],'true') !== false ) ? ' checked="checked"':'';
				else
					$preChecked = ( $field_value && $field_value<>'-' )? ' checked="checked"':''; ### '-' for mp session!

				$err='';
				if( !$all_valid && $validations[$i]<>1 )
					$err = ' cf_errortxt';

				if ( $options[1]<>'' ) {
					    $opt = explode('|', $options[1],2);
				 		$before = '<li'.$liID.' class="'.$liERR.'">'.$insertErr;
						$after  = '<label'. $labelID . ' for="'.$input_id.'" class="cf-after'.$err.'"><span>' . $opt[0] . '</span></label></li>';
				 		$ba = 'a';
				}
				else {
					    $opt = explode('|', $field_name,2);
						$before = '<li'.$liID.' class="'.$liERR.'">'.$insertErr.'<label' . $labelID . ' for="'.$input_name.'" class="cf-before'. $err .'"><span>' . $opt[0] . '</span></label>';
				 		$after  = '</li>';
				 		$ba = 'b';
				}
				### if | val provided, then use "X"
				if( $val=='' )
					$val = ($opt[1]<>'')?' value="'.$opt[1].'"':'';
				$field = $nttt . $before . '<input' . $readonly.$disabled . ' type="checkbox" name="'.$input_name.'" id="'.$input_id.'" class="cf-box-' . $ba . $field_class . '"'.$val.$fieldTitle.$preChecked.'/>' . $after;

				break;


			case "checkboxgroup":
				$liID_b = ($liID <>'')?substr($liID,0,-1) . 'items"':'';
				array_shift($options);
				$field .= $nttt . '<li'.$liID.' class="cf-box-title">' . (($field_name)) . '</li>' .
						  $nttt . '<li'.$liID_b.' class="cf-box-group">';
				$id=1; $j=0;

                ### mp session support
                if ( $moveBack || $isMPformNext )
                    $field_value = explode(',',$field_value);

				foreach( $options as $option  ) {

						### supporting names & values
						$boxPreset = explode('|set:', $option );
				    	$opt = explode('|', $boxPreset[0],2);
						if ( $opt[1]=='' ) $opt[1] = $opt[0];

	                    $checked = '';
						if( $moveBack || $isMPformNext ){
		                    if ( in_array($opt[1],array_values($field_value)) )
		                        $checked = 'checked="checked"';
	                    } elseif ( is_array($field_value) ){
		                    if ( $opt[1]==htmlspecialchars( stripslashes(strip_tags($field_value[$j])) ) )  {
		                        $checked = 'checked="checked"';
		                        $j++;
		                    }
	                    }else{
							if ( strpos($boxPreset[1],'true')!==false )
						    $checked = ' checked="checked"';
	                    }

						if ( $labelID<>'' ) $labelIDx = substr($labelID,0,-1) . $id . '"';

						if ( $opt[0]=='' )
							$field .= $nttt . $tab . '<br />';
						else
							$field .= $nttt . $tab . '<input' . $readonly.$disabled . ' type="checkbox" id="'. $input_id .'-'. $id . '" name="'. $input_name . '[]" value="'.$opt[1].'" '.$checked.' class="cf-box-b"'.$fieldTitle.'/>'.
									  '<label' . $labelIDx . ' for="'. $input_id .'-'. ($id++) . '" class="cf-group-after"><span>'.$opt[0] . "</span></label>";

					}
				$field .= $nttt . '</li>';
				break;


			case "multiselectbox":
				### $field .= $nttt . '<li><label ' . $labelID . ' for="'.$input_name.'"'. $labelclass . '><span>' . stripslashes(($field_name)) . '</span></label>';
				$field .= '<select' . $readonly.$disabled . ' multiple="multiple" name="'.$input_name.'[]" id="'.$input_id.'" class="cfselectmulti ' . $field_class . '"'.$fieldTitle.'>';
				array_shift($options);
				$j=0;

                ### mp session support
                if ( $moveBack || $isMPformNext )
                    $field_value = explode(',',$field_value);

				foreach( $options as $option  ) {

                    ### supporting names & values
					$optPreset = explode('|set:', $option );
				    $opt = explode('|', $optPreset[0],2);
                    if ( $opt[1]=='' ) $opt[1] = $opt[0];

                    $checked = '';
					if( $moveBack || $isMPformNext ){
	                    if ( in_array($opt[1],array_values($field_value)) )
	                        $checked = 'selected="selected"';
                    } elseif ( is_array($field_value) ){
	                    if ( $opt[1]==stripslashes(htmlspecialchars(strip_tags($field_value[$j]))) )  {
	                        $checked = ' selected="selected"';
	                        $j++;
	                    }
	                }else{
						if ( strpos($optPreset[1],'true')!==false )
						    $checked = ' selected="selected"';
	                }

                    $field.= $nttt . $tab . '<option value="'. str_replace('"','&quot;',$opt[1]) .'"'.$checked.'>'.$opt[0].'</option>';

				}
				$field.= $nttt . '</select>';
				break;

			case "emailtobox":
			case "selectbox":
				$field = '<select' . $readonly.$disabled . ' name="'.$input_name.'" id="'.$input_id.'" class="cformselect' . $field_class . '" '.$fieldTitle.'>';
				array_shift($options); $jj=$j=0;

				foreach( $options as $option  ) {

					### supporting names & values
					$optPreset = explode('|set:', $option );
				    $opt = explode('|', $optPreset[0],2);
					if ( $opt[1]=='' ) $opt[1] = $opt[0];

					### email-to-box valid entry?
			    if ( $field_type == 'emailtobox' && $opt[1]<>'-' )
							$jj = $j; else $jj = '-';
          $j++;

				    $checked = '';

					if( $field_value == '' || $field_value == '-') {
							if ( strpos($optPreset[1],'true')!==false )
							    $checked = ' selected="selected"';
					}	else
							if ( $opt[1]==$field_value || $jj==$field_value )
								$checked = ' selected="selected"';

					$field.= $nttt . $tab . '<option value="'.(($field_type=='emailtobox')?$jj:$opt[1]).'"'.$checked.'>'.$opt[0].'</option>';

				}
				$field.= $nttt . '</select>';
				break;

			case "send2author":
				$force_checked = ( strpos($field_stat[0],'|set:')===false )? true:false;
			case "radiobuttons":
				$liID_b = ($liID <>'')?substr($liID,0,-1) . 'items"':'';	### only if label ID's active

				array_shift($options);
				$field .= $nttt . '<li'.$liID.' class="'.$liERR.' cf-box-title">'. $insertErr . (($field_name)) . '</li>' .
						  $nttt . '<li'.$liID_b.' class="cf-box-group">';

				$id=1;
				foreach( $options as $option  ) {
				    $checked = '';

						### supporting names & values
						$radioPreset = explode('|set:', $option );
				    	$opt = explode('|', $radioPreset[0],2);
						if ( $opt[1]=='' ) $opt[1] = $opt[0];

						if( $field_value == '' ) {

								if ( strpos($radioPreset[1],'true')!==false || ($force_checked && $id==1))
								    $checked = ' checked="checked"';

						}	else
								if ( $opt[1]==$field_value ) $checked = ' checked="checked"';

						if ( $labelID<>'' ) $labelIDx = substr($labelID,0,-1) . $id . '"';

						if ( $opt[0]=='' )
							$field .= $nttt . $tab . '<br />';
						else
							$field .= $nttt . $tab .
								  '<input' . $readonly.$disabled . ' type="radio" id="'. $input_id .'-'. $id . '" name="'.$input_name.'" value="'.$opt[1].'"'.$checked.' class="cf-box-b' . ($second?' cformradioplus':'') . ($field_required?' fldrequired':'') .'"'.$fieldTitle.'/>'.
								  '<label' . $labelIDx . ' for="'. $input_id .'-'. ($id++) . '" class="cf-after"><span>'.$opt[0] . "</span></label>";

					}
				$field .= $nttt  . '</li>';
				break;

		}

		### add new field
		$content .= $field;

		### adding "required" text if needed
		if($field_emailcheck == 1)
			$content .= '<span class="emailreqtxt">'.stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_emailrequired']).'</span>';
		else if($field_required == 1 && !in_array($field_type,array('ccbox','luv','subscribe','checkbox','radiobuttons')) )
			$content .= '<span class="reqtxt">'.stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_required']).'</span>';

		### close out li item
		if ( ! in_array($field_type,array('hidden','fieldsetstart','fieldsetend','radiobuttons','luv','subscribe','checkbox','checkboxgroup','ccbox','textonly','send2author')) )
			$content .= '</li>';

	} ### all fields


	### close any open tags
	if ( $ol )
		$content .= $ntt . '</ol>';
	if ( $fieldsetopen )
		$content .= $ntt . '</fieldset>';


	### rest of the form
	if ( $cformsSettings['form'.$no]['cforms'.$no.'_ajax']=='1' && !$upload && !$custom && !$alt_action )
		$ajaxenabled = ' onclick="return cforms_validate(\''.$no.'\', false)"';
	else if ( ($upload || $custom || $alt_action) && $cformsSettings['form'.$no]['cforms'.$no.'_ajax']=='1' )
		$ajaxenabled = ' onclick="return cforms_validate(\''.$no.'\', true)"';
	else
		$ajaxenabled = '';


	### just to appease html "strict"
	$content .= $ntt . '<fieldset class="cf_hidden">'.$nttt.'<legend>&nbsp;</legend>';


	### if visitor verification turned on:
	if ( $verification )
		$content .= $nttt .'<input type="hidden" name="cforms_a'.$no.'" id="cforms_a'.$no.'" value="' . md5(rawurlencode(strtolower($q[1]))) . '"/>';

	### custom error
	$custom_error=substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],2,1).substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],3,1).substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],4,1).$custom_error;


	### TAF or WP comment or Extra Fields
	if ( (int)$isTAF > 0 ){

		$nono = $isWPcommentForm?'':$no;

		if ( $isWPcommentForm )
			$content .= $nttt . '<input type="hidden" name="comment_parent" id="comment_parent" value="'.( ($_REQUEST['replytocom']<>'')?$_REQUEST['replytocom']:'0' ).'"/>';

		$content .= $nttt . '<input type="hidden" name="comment_post_ID'.$nono.'" id="comment_post_ID'.$nono.'" value="' . ( isset($_GET['pid'])? $_GET['pid'] : get_the_ID() ) . '"/>' .
					$nttt . '<input type="hidden" name="cforms_pl'.$no.'" id="cforms_pl'.$no.'" value="' . ( isset($_GET['pid'])? get_permalink($_GET['pid']) : get_permalink() ) . '"/>';
	}


	$content .= $nttt . '<input type="hidden" name="cf_working'.$no.'" id="cf_working'.$no.'" value="'.rawurlencode($cformsSettings['form'.$no]['cforms'.$no.'_working']).'"/>'.
				$nttt . '<input type="hidden" name="cf_failure'.$no.'" id="cf_failure'.$no.'" value="'.rawurlencode($cformsSettings['form'.$no]['cforms'.$no.'_failure']).'"/>'.
				$nttt . '<input type="hidden" name="cf_codeerr'.$no.'" id="cf_codeerr'.$no.'" value="'.rawurlencode($cformsSettings['global']['cforms_codeerr']).'"/>'.
				$nttt . '<input type="hidden" name="cf_customerr'.$no.'" id="cf_customerr'.$no.'" value="'.rawurlencode($custom_error).'"/>'.
				$nttt . '<input type="hidden" name="cf_popup'.$no.'" id="cf_popup'.$no.'" value="'.$cformsSettings['form'.$no]['cforms'.$no.'_popup'].'"/>';

	$content .= $ntt . '</fieldset>';


    ### multi page form: reset
	$reset='';
    if( $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_form'] && $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_reset'] )
		$reset = '<input tabindex="999" type="submit" name="resetbutton'.$no.'" id="resetbutton'.$no.'" class="resetbutton" value="' . $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_resettext'] . '" onclick="return confirm(\''.__('Note: This will reset all your input!', 'cforms').'\')">';


    ### multi page form: back
	$back='';
    if( $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_form'] && $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_back'] )
		$back = '<input type="submit" name="backbutton'.$no.'" id="backbutton'.$no.'" class="backbutton" value="' . $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_backtext'] . '">';


	$content .= $ntt . '<p class="cf-sb">'.$reset.$back.'<input type="submit" name="sendbutton'.$no.'" id="sendbutton'.$no.'" class="sendbutton" value="' . stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_submit_text'])) . '"'.$ajaxenabled.'/></p></form><p class="linklove" id="ll'. $no .'"><a href="http://www.deliciousdays.com/cforms-plugin"><em>cforms</em> contact form by delicious:days</a></p>';

	### either show message above or below
	$usermessage_text	= check_default_vars($usermessage_text,$no);
	$usermessage_text	= check_cust_vars($usermessage_text,$track,$no);

	if( substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],1,1)=='y' && !($success&&$cformsSettings['form'.$no]['cforms'.$no.'_hide']))
		$content .= $tt . '<div id="usermessage'.$no.'b" class="cf_info ' . $usermessage_class . $umc . '" >' . $usermessage_text . '</div>' . $nl;

	### flush debug messages
	dbflush();

	return $content;
}



### some css for positioning the form elements
function cforms_style() {
	global $wp_query, $cforms_root, $localversion, $cformsSettings;

	### add content actions and filters
	$page_obj = $wp_query->get_queried_object();

  $exclude = ($cformsSettings['global']['cforms_inexclude']['ex']=='1');
	$onPages  = str_replace(' ','',stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_inexclude']['ids'] )));
	$onPagesA = explode(',', $onPages);

	if( $onPages=='' || (in_array($page_obj->ID,$onPagesA) && !$exclude) || (!in_array($page_obj->ID,$onPagesA) && $exclude)){

		echo "\n<!-- Start Of Script Generated By cforms v".$localversion." [Oliver Seidel | www.deliciousdays.com] -->\n";
		if( $cformsSettings['global']['cforms_no_css']<>'1' )
			echo '<link rel="stylesheet" type="text/css" href="' . $cforms_root . '/styling/' . $cformsSettings['global']['cforms_css'] . '" />'."\n";
		echo '<script type="text/javascript" src="' . $cforms_root. '/js/cforms.js"></script>'."\n";
		if( $cformsSettings['global']['cforms_datepicker']=='1' ){
			$nav = $cformsSettings['global']['cforms_dp_nav'];
			$dformat = str_replace(array('M','EE','E'),array('m','dddd','ddd'),stripslashes($cformsSettings['global']['cforms_dp_date']));
			echo '<script type="text/javascript" src="' . $cforms_root. '/js/cformsadmincal.js"></script>'."\n";
			echo '<script type="text/javascript">'."\n".
				 // "\t".'var cforms = jQuery.noConflict(false);'."\n".
				 "\t".'Date.dayNames = ['.stripslashes($cformsSettings['global']['cforms_dp_days']).'];'."\n".
				 "\t".'Date.abbrDayNames = ['.stripslashes($cformsSettings['global']['cforms_dp_days']).'];'."\n".
				 "\t".'Date.monthNames = ['.stripslashes($cformsSettings['global']['cforms_dp_months']).'];'."\n".
				 "\t".'Date.abbrMonthNames = ['.stripslashes($cformsSettings['global']['cforms_dp_months']).'];'."\n".
				 "\t".'Date.firstDayOfWeek = '.stripslashes($cformsSettings['global']['cforms_dp_start']).';'."\n".
				 "\t".''."\n".
				 "\t".'Date.fullYearStart = "20";'."\n".
				 "\t".'jQuery.dpText = { TEXT_PREV_YEAR:"'.stripslashes($nav[0]).'",'. ### Previous year
				 'TEXT_PREV_MONTH:"'.stripslashes($nav[1]).'",'.
				 'TEXT_NEXT_YEAR:"'.stripslashes($nav[2]).'",'.
				 'TEXT_NEXT_MONTH:"'.stripslashes($nav[3]).'",'.
				 'TEXT_CLOSE:"'.stripslashes($nav[4]).'",'.
				 'TEXT_CHOOSE_DATE:"'.stripslashes($nav[5]).'",'.
				 'ROOT:"'.$cforms_root.'"};'."\n".
				 "\t".'jQuery(function() { Date.format = "dd/mm/yyyy"; jQuery(".cf_date").datePicker({startDate:"01/01/1899",verticalOffset:10,horizontalOffset:5,horizontalPosition:1 } ); Date.format = "'.$dformat.'"; });'."\n".
				 '</script>'."\n";
		}
		echo '<!-- End Of Script Generated By cforms -->'."\n\n";
	}
}


### custom routine to find last item
function findlast( $haystack,$needle,$offset=NULL ){
	if( ($pos = strpos( strrev($haystack) , strrev($needle) , $offset)) === false ) return false;
    return strlen($haystack) - $pos - strlen($needle);
}


### replace placeholder by generated code
function cforms_insert( $content ) {
	global $post, $cformsSettings; $newcontent='';

	$last=0;
	if ( ($a=strpos($content,'<!--cforms'))!==false ) {  ### only if form tag is present!

		$p_offset= 0;
		$part_content = substr( $content, 0, $a-$last );
		$p_open  = findlast($part_content,'<p>');
		$p_close = findlast($part_content,'</p>');

		### wrapped in <p> ?
		$p_offset = ($p_close < $p_open || ($p_open!==false && $p_close===false) ) ? $p_open : $a;

		$forms = $cformsSettings['global']['cforms_formcount'];

		$fns = array();
		for ($i=0;$i<$forms;$i++) {
			$no = ($i==0)?'':($i+1);
			$fns[sanitize_title_with_dashes($cformsSettings['form'.$no]['cforms'.$no.'_fname'])] = $i+1;
		}

		while( $a !== false ){

			$b = strpos($content,'-->',$a);

			$Fid = substr($content,$a+10,($b-$a-10));
			$Fname = '';

			if ( ($fQ=strpos($Fid,'"'))!==false )
				$Fname = sanitize_title_with_dashes(substr( $Fid, $fQ+1, strpos($Fid,'"',$fQ+1)-$fQ-1 ));

			$newcontent .= substr($content,$last,$p_offset-$last);

			if( $Fname !== '' ){
			  if ( check_for_taf( $fns[$Fname],cfget_pid() ) )
  				$newcontent .= cforms('',$fns[$Fname]);
			}else{
			  if ( check_for_taf( $Fid,cfget_pid() ) )
    			$newcontent .= cforms('',$Fid);
      }

			$p_open_after  = strpos($content,'<p>',$b);
			$p_close_after = strpos($content,'</p>',$b);

			### wrapped in <p> ?
			$b = ($p_close_after < $p_open_after || ($p_close_after!==false && $p_open_after===false)) ? $p_close_after+1 : $b;  //add'l +3 covered by $last = $b+3; !! :-)


			$a = strpos($content,'<!--cforms',$b);
			$last = $b+3;


			### next wrapping <p> tags
			$part_content = substr( $content, $last, $a-$last );
			$p_open  = findlast($part_content,'<p>');
			$p_close = findlast($part_content,'</p>');

			### wrapped in <p> ?
			$p_offset = ($p_close < $p_open) ? $a-(strlen($part_content)-$p_open) : $a;

		}
		$newcontent .= substr($content,$last);

		return $newcontent;
	}
	else
		return $content;
}


### build field_stat string from array (for custom forms)
function build_fstat($f) {
    $cfarray = array();
    for($i=0; $i<count($f['label']); $i++) {
        if ( $f['type'][$i] == '') $f['type'][$i] = 'textfield';
        if ( $f['isreq'][$i] == '') $f['isreq'][$i] = '0';
        if ( $f['isemail'][$i] == '') $f['isemail'][$i] = '0';
        if ( $f['isclear'][$i] == '') $f['isclear'][$i] = '0';
        if ( $f['isdisabled'][$i] == '') $f['isdisabled'][$i] = '0';
        if ( $f['isreadonly'][$i] == '') $f['isreadonly'][$i] = '0';
        $cfarray[$i]=$f['label'][$i].'$#$'.$f['type'][$i].'$#$'.$f['isreq'][$i].'$#$'.$f['isemail'][$i].'$#$'.$f['isclear'][$i].'$#$'.$f['isdisabled'][$i].'$#$'.$f['isreadonly'][$i];
    }
    return $cfarray;
}


### inserts a cform anywhere you want
function insert_cform($no='',$custom='',$c='') {
	global $post;

  $pid = cfget_pid();

	if ( !$pid )
		echo cforms($custom,$no.$c);
	else
		echo check_for_taf($no,$pid)?cforms($custom,$no.$c):'';
}


### GET $pid
function cfget_pid() {
	global $post;

	if ( isset($_GET['pid']) )
		$pid = $_GET['pid'];
	else if ($post->ID == 0)
		$pid = false;
	else
		$pid = $post->ID;

  return $pid;
}


### inserts a custom cform anywhere you want
function insert_custom_cform($fields='',$no='') {
	insert_cform($no, $fields, '+');
}


### check form names/id's
function check_form_name($no) {

	if( is_numeric($no) || $no=='' ) return $no;

	if( !(is_array($cformsSettings) && $cformsSettings['global']['cforms_formcount']>0) )
		$cformsSettings = get_option('cforms_settings');

	$forms = $cformsSettings['global']['cforms_formcount'];

	for ($i=0;$i<$forms;$i++) {
		$no2 = ($i==0)?'':($i+1);
		if ( stripslashes($cformsSettings['form'.$no2]['cforms'.$no2.'_fname']) == $no )
			return $no2;
	}
	return '';
}


### check if t-f-a is set
function check_for_taf($no,$pid) {
	global $cformsSettings;

	if ( substr($cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'],0,1)<>'1')
		return true;

  if( is_single() || in_the_loop() ){
  	$tmp = get_post_custom($pid);
  	return ( $tmp["tell-a-friend"][0] == '1' )?true:false;
  }else
    return true;
}


### public function: check if post is t-f-a enabled
function is_tellafriend($pid) {
	$tmp = get_post_custom($pid);
	return ($tmp["tell-a-friend"][0]=='1')?true:false;
}


### WP 2.7 admin menu hook
function cforms_post_box(){
	global $tafstring;
	echo $tafstring;
}


function add_cforms_post_boxes(){
	add_meta_box('cformspostbox', __('cforms Tell-A-Friend', 'cforms'), 'cforms_post_box', 'post', 'normal', 'high');
	add_meta_box('cformspostbox', __('cforms Tell-A-Friend', 'cforms'), 'cforms_post_box', 'page', 'normal', 'high');
}


### up to WP 2.7 and lower
function taf_admin() {
	global $wp_db_version, $tafstring;
    if ( $wp_db_version >= 6846 ){
        ?>
        <fieldset id="cformsTAF" class="postbox closed">
            <h3><?php _e('cforms Tell-A-Friend', 'cforms'); ?></h3>
            <div class="inside"><?php echo $tafstring; ?></div>
        </fieldset>
        <?php
    }else {
        ?>
        <fieldset id="cformsTAF" class="dbx-box">
            <h3 class="dbx-handle"><?php _e('cforms Tell-A-Friend', 'cforms'); ?></h3>
                <div class="dbx-content"><?php echo $tafstring; ?></div>
        </fieldset>
        <?php
    }
}


### Add Tell A Friend processing
function enable_tellafriend($post_ID) {
	global $wpdb;

	if ( isset($_POST['action']) && ($_POST['action']=='autosave' || $_POST['action']=='inline-save')  )
    	return;

	$tellafriend_status = isset($_POST['tellafriend']);

	if($tellafriend_status && intval($post_ID) > 0)
		add_post_meta($post_ID, 'tell-a-friend', '1', true);
	else if ( isset($_POST['post_ID']) )
		delete_post_meta($post_ID, 'tell-a-friend');
}


### cforms widget
function widget_cforms_init() {

	global $cforms_root, $wp_registered_widgets, $cformsSettings;

    $cformsSettings = get_option('cforms_settings');
    $options = $cformsSettings['global']['widgets'];
    $prefix = 'cforms';

	if (! function_exists("wp_register_sidebar_widget")) {
		return;
	}

	        function widget_cforms($args) {
	            $cformsSettings = get_option('cforms_settings');
	            $options = $cformsSettings['global']['widgets'];
	            extract($args);

	            $prefix = 'cforms';

	            $id = substr($widget_id, 7);
	            $no = ($options[$id]['form']=='1')?'':$options[$id]['form'];
	            $title = htmlspecialchars(stripslashes($options[$id]['title']));

	            echo $before_widget.$before_title.$title.$after_title;
	            insert_cform($no);
	            echo $after_widget;
	        }

	        function widget_cforms_options($args) {
	            global $wpdb;

	            $cformsSettings = get_option('cforms_settings');
	            $options = $cformsSettings['global']['widgets'];
	            $prefix = 'cforms';

	            if(empty($options)) $options = array();
	            if(isset($options[0])) unset($options[0]);

	           // update options array
	            if( is_array($_POST) && !empty($_POST[$prefix]) ){
	                foreach($_POST[$prefix] as $widget_number => $values){
	                    if(empty($values) && isset($options[$widget_number])) // user clicked cancel
	                        continue;

	                    if(!isset($options[$widget_number]) && $args['number'] == -1){
	                        $args['number'] = $widget_number;
	                        $options['last_number'] = $widget_number;
	                    }
	                    $options[$widget_number] = $values;
	                }

	                // update number
	                if($args['number'] == -1 && !empty($options['last_number'])){
	                    $args['number'] = $options['last_number'];
	                }

	                // clear unused options and update options in DB. return actual options array
	                $options = cforms_widget_update($prefix, $options, $_POST[$prefix], $_POST['sidebar'], 'widget_cforms');

	            }

	            $number = ($args['number'] == -1)? '%i%' : $args['number'];

	            // stored data
	            $opts  = @$options[$number];
	            $title = @$opts['title'];
	            $form = @$opts['form'];


                $opt = '';
                $forms = $cformsSettings['global']['cforms_formcount'];
                for ($i=1;$i<=$forms;$i++) {
                    $no = ($i==1)?'':($i);
                    $selected = ( $i==$form )? ' selected="selected"':'';
                    $name = stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_fname']);
                    $name = (strlen($name)>40) ? substr($name,0,40).'&#133':$name;
                    $opt .= '<option value="'.$i.'"'. $selected .'>'.$name.'</option>';
                }

	            echo '<label for="' .$prefix. '-' . $number. '-title">' . __('Title', 'cforms') . ':</label>'.
	                 '<input type="text" id="' .$prefix. '-' . $number. '-title" name="' .$prefix. '[' . $number. '][title]" value="' .$title. '" /><br />';

				echo '<label for="' .$prefix. '-' . $number. '-form">' . __('Form', 'cforms') . ':</label>'.
                	 '<select id="' .$prefix. '-' . $number. '-form" name="' .$prefix. '[' . $number. '][form]" style="width:220px; font-size:10px; font-family:Arial;">'. $opt .'</select>';

	        }

	        function cforms_widget_update($id_prefix, $options, $post, $sidebar, $option_name = ''){

	                global $wp_registered_widgets;
	                static $updated = false;

				    $cformsSettings = get_option('cforms_settings');

	                // get active sidebar
	                $sidebars_widgets = wp_get_sidebars_widgets();
	                if ( isset($sidebars_widgets[$sidebar]) )
	                    $this_sidebar =& $sidebars_widgets[$sidebar];
	                else
	                    $this_sidebar = array();

	                // search unused options
	                foreach ( $this_sidebar as $_widget_id ) {
	                    if(preg_match('/'.$id_prefix.'-([0-9]+)/i', $_widget_id, $match)){
	                        $widget_number = $match[1];

	                        if(!in_array($match[0], $_POST['widget-id'])){
	                            unset($options[$widget_number]);
	                        }
	                    }
	                }

	                // update database
	                $cformsSettings['global']['widgets'] = $options;
                    update_option('cforms_settings',$cformsSettings);
                    $updated = true;

	                // return updated array
	                return $options;

            }


    $widget_ops = array('classname' => 'widgetcform', 'description' => __('Add any cforms form to your sidebar', 'cforms') );
    $control_ops = array('width' => 200, 'height' => 200, 'id_base' => 'cforms' );
	$name = 'cforms';

	if(isset($options[0])) unset($options[0]);

	if(!empty($options)){
		foreach(array_keys($options) as $widget_number){
			wp_register_sidebar_widget($prefix.'-'.$widget_number, $name, 'widget_cforms', $widget_ops, array( 'number' => $widget_number ));
			wp_register_widget_control($prefix.'-'.$widget_number, $name, 'widget_cforms_options', $control_ops, array( 'number' => $widget_number ));
		}
	} else{
		$options = array();
		$widget_number = 1;
		wp_register_sidebar_widget($prefix.'-'.$widget_number, $name, 'widget_cforms', $widget_ops, array( 'number' => $widget_number ));
		wp_register_widget_control($prefix.'-'.$widget_number, $name, 'widget_cforms_options', $control_ops, array( 'number' => $widget_number ));
	}
}


### get # of submission left (max subs)
function get_cforms_submission_left($no='') {
	global $wpdb, $cformsSettings;

	if ( $no==0 || $no==1 ) $no='';
	$max   = (int)$cformsSettings['form'.$no]['cforms'.$no.'_maxentries'];

	if( $max == '' || $max == 0 || $cformsSettings['global']['cforms_database']=='0' )
		return -1;

	$entries = $wpdb->get_row("SELECT count(id) as submitted FROM {$wpdb->cformssubmissions} WHERE form_id='{$no}'");

	if( $max-$entries->submitted > 0)
		return ($max-$entries->submitted);
	else
		return 0;
}


### get current page
function get_request_uri() {
	$request_uri = $_SERVER['REQUEST_URI'];
	if ( !isset($_SERVER['REQUEST_URI']) || (strpos($_SERVER['SERVER_SOFTWARE'],'IIS')!==false && strpos($_SERVER['REQUEST_URI'],'wp-admin')===false) ){
	    if(isset($_SERVER['SCRIPT_NAME']))
	        $request_uri = $_SERVER['SCRIPT_NAME'];
	    else
	        $request_uri = $_SERVER['PHP_SELF'];
	}
	return $request_uri;
}


### PLUGIN VERSION CHECK ON PLUGINS PAGE
//add_action( 'after_plugin_row', 'cf_check_plugin_version' );
function cf_check_plugin_version($plugin)
{
	global $plugindir,$localversion;

 	if( strpos($plugindir.'/cforms.php',$plugin)!==false )
 	{
		$checkfile = "http://www.deliciousdays.com/download/cforms.chk";

		$vcheck = wp_remote_fopen($checkfile);

		if($vcheck)
		{
			$version = $localversion;

			$status = explode('@', $vcheck);
			$theVersion = $status[1];
			$theMessage = $status[3];

			if( (version_compare(strval($theVersion), strval($version), '>') == 1) )
			{
				$msg = __("Latest version available: ", "cforms").'<strong>v'.$theVersion.'</strong> - '.$theMessage;
				echo '<td colspan="5" class="plugin-update" style="line-height:1.2em; font-size:11px; padding:1px;"><div style="background:#A2F099;border:1px solid #4FE23F; padding:2px; font-weight:bold;">'.__("New cformsII update available", "cforms").' <a href="javascript:void(0);" onclick="jQuery(\'#cf-update-msg\').toggle();">'.__("(more info)", "cforms").'</a>.</div><div id="cf-update-msg" style="display:none; padding:10px; text-align:center;" >'.$msg.'</div></td>';
			} else {
				return;
			}
		}
	}
}


### add actions
global $tafstring;
if (function_exists('add_action')){

	### widget init
	add_action('plugins_loaded', 'widget_cforms_init');

	### get location?
	$request_uri = get_request_uri();

	$admin   = ( strpos($request_uri,'wp-admin')!==false )?true:false;
	$cfadmin = ( strpos($_SERVER['QUERY_STRING'],$plugindir.'/cforms')!==false )?true:false;

	### dashboard
	if ( $cformsSettings['global']['cforms_showdashboard']=='1' && $cformsSettings['global']['cforms_database']=='1' ) {
		require_once(dirname(__FILE__) . '/lib_dashboard.php');
	    if ( $wp_db_version < 9872 )
			add_action( 'activity_box_end', 'cforms_dashboard', 1 );
        else
			add_action( 'wp_dashboard_setup', 'cforms_dashboard_27_setup', 1 );
	}
	### cforms specific stuff
	if ( $cfadmin ) {
		require_once(dirname(__FILE__) . '/lib_functions.php');
		add_action('admin_head', 'cforms_options_page_style');
		add_action('init', 'download_cforms');
        add_action('admin_print_scripts', 'cforms_scripts' );
	}
 	### other admin stuff
	if ( $admin ) {
		require_once(dirname(__FILE__) . '/lib_functions.php');
		add_action('admin_menu', 'cforms_menu');
		add_action('init', create_function('', 'load_plugin_textdomain(\'cforms\');') );

	    ### Check all forms for TAF and set variables
	    for ( $i=1;$i<=$cformsSettings['global']['cforms_formcount'];$i++ ) {
	        $tafenabled = ( substr($cformsSettings['form'.(($i=='1')?'':$i)]['cforms'.(($i=='1')?'':$i).'_tellafriend'],0,1)=='1') ? true : false;
	        if ( $tafenabled ) break;
	    }
	    $tafform = ($i==1)?'':$i;

	    if ( $tafenabled ){
	        $edit_post = intval($_GET['post']);
            $tmp = get_post_custom($edit_post);
            $taf = $tmp["tell-a-friend"][0];

            $tafchk = ($taf=='1' || ($edit_post=='' && substr($cformsSettings['form'.$tafform]['cforms'.$tafform.'_tellafriend'],1,1)=='1') )?'checked="checked"':'';

			$tafstring = '<label for="tellafriend" class="selectit"><input type="checkbox" id="tellafriend" name="tellafriend" value="1"'. $tafchk .'/>&nbsp;'. __('T-A-F enable this post/page', 'cforms').'</label>';

	        ### add admin boxes
	        if ( $wp_db_version < 6846 ){
	            add_action('dbx_post_sidebar', 'taf_admin');
	            add_action('dbx_page_sidebar', 'taf_admin');        ###  < WP25.
	        }else if ( $wp_db_version < 9872 ) {
	            add_action('edit_form_advanced', 'taf_admin');      ### >= WP2.5
	            add_action('edit_page_form', 'taf_admin');          ### >= WP2.5
	        }else{
	            add_action('admin_menu', 'add_cforms_post_boxes');  ### >= WP2.7
	        }
			add_action('save_post', 'enable_tellafriend');

	    } ### if tafenabled

	} ### if admin

}

### cforms runtime JS scripts
function cforms_runtime_scripts() {
	global $wp_scripts, $localversion;

	### get options
	$cformsSettings = get_option('cforms_settings');
	$r=$cformsSettings['global']['cforms_root'];

    if ( version_compare(strval($wp_scripts->registered['jquery']->ver), strval("1.4.2") ) === -1 ){
		wp_deregister_script('jquery');
	    wp_register_script('jquery',$r.'/js/jquery.js',false,'1.4.2');
    	wp_enqueue_script('jquery');
    }
}

### attaching to filters
add_filter('wp_head', 'cforms_runtime_scripts');
add_filter('wp_head', 'cforms_style');
add_filter('the_content', 'cforms_insert',10);
?>