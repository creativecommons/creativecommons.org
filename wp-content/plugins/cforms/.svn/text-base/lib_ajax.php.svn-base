<?php
###
###  ajax submission of form
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

if (version_compare(PHP_VERSION, '5.0.0', '>'))
	require_once(dirname(__FILE__) . '/lib_email.php');
else
	require_once(dirname(__FILE__) . '/lib_email_php4.php');

require_once(dirname(__FILE__) . '/lib_aux.php');

###
###  reset captcha image
###
function reset_captcha( $no = '' ){
	### fix for windows!!!
	if ( strpos(__FILE__,'\\') ){
		$path = preg_replace( '|.*(wp-content.*)lib_ajax.php|','${1}', __FILE__ );
		$path = '/'.str_replace('\\','/',$path);
	}
	else
		$path = preg_replace( '|.*(/wp-content/.*)/.*|','${1}', __FILE__ );

	$path = get_bloginfo('wpurl') . $path;

	$newimage = 'newcap|'.$no.'|'.$path.'/cforms-captcha.php?ts='.$no.str_replace('&amp;','&',get_captcha_uri());
	return $newimage;
}

###
###  submit comment
###
function cforms_submitcomment($content) {
	global $cformsSettings, $wpdb, $subID, $smtpsettings, $track, $trackf, $Ajaxpid, $AjaxURL, $wp_locale, $abspath;

    $WPsuccess=false;

	### WP Comment flag
	$isAjaxWPcomment = strpos($content,'***');###  WP comment feature

	$content = explode('***', $content);
	$content = $content[0];

	$content = explode('+++', $content); ###  Added special fields

	if ( count($content) > 3 ){
	    $commentparent = $content[1];
	    $Ajaxpid = $content[2];
	    $AjaxURL = $content[3];
    }else {
	    $Ajaxpid = $content[1];
	    $AjaxURL = $content[2];
    }

	$segments = explode('$#$', $content[0]);
	$params = array();

	$sep = (strpos(__FILE__,'/')===false)?'\\':'/';
	$WPpluggable = $abspath . 'wp-includes'.$sep.'pluggable.php';
	if ( file_exists($WPpluggable) )
		require_once($WPpluggable);

    $CFfunctionsC = dirname(dirname(__FILE__)).$cformsSettings['global']['cforms_IIS'].'cforms-custom'.$cformsSettings['global']['cforms_IIS'].'my-functions.php';
    $CFfunctions = dirname(__FILE__).$cformsSettings['global']['cforms_IIS'].'my-functions.php';
    if ( file_exists($CFfunctionsC) )
        include_once($CFfunctionsC);
    else if ( file_exists($CFfunctions) )
        include_once($CFfunctions);


	if ( function_exists('wp_get_current_user') )
		$user = wp_get_current_user();

	for($i = 1; $i <= sizeof($segments); $i++)
		$params['field_' . $i] = $segments[$i];

	###  fix reference to first form
	if ( $segments[0]=='1' ) $params['id'] = $no = ''; else $params['id'] = $no = $segments[0];


	### TAF flag
    $isTAF = substr($cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'],0,1);


	###  user filter ?
	if( function_exists('my_cforms_ajax_filter') )
		$params = my_cforms_ajax_filter($params);


	###  init variables
	$track = array();
	$trackinstance = array();

 	$to_one = -1;
  	$ccme = false;
	$field_email = '';
	$off = 0;
	$fieldsetnr=1;

	$taf_youremail = false;
	$taf_friendsemail = false;

	###  form limit reached
	if ( ($cformsSettings['form'.$no]['cforms'.$no.'_maxentries']<>'' && get_cforms_submission_left($no)==0) || !cf_check_time($no) ){
	    $pre = $segments[0].'*$#'.substr($cformsSettings['form'.$no]['cforms'.$no.'_popup'],0,1);
	    return $pre . preg_replace ( '|\r\n|', '<br />', stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_limittxt'])) . $hide;
	}

	### for comment luv
	get_currentuserinfo();
	global $user_level;

	### Subscribe-To-Comments
	$isSubscribed=='';
	if ( class_exists('sg_subscribe') ){
		global $sg_subscribe;
		sg_subscribe_start();
		$isSubscribed = $sg_subscribe->current_viewer_subscription_status();
	}

	$captchaopt = $cformsSettings['global']['cforms_captcha_def'];

	for($i = 1; $i <= sizeof($params)-2; $i++) {

			$field_stat = explode('$#$', $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . ((int)$i+(int)$off)] );

			while ( in_array($field_stat[1],array('fieldsetstart','fieldsetend','textonly','captcha','verification')) ) {

				if ( $field_stat[1] == 'captcha' && !(is_user_logged_in() && !$captchaopt['fo']=='1') )
					break;
				if ( $field_stat[1] == 'verification' && !(is_user_logged_in() && !$captchaopt['foqa']=='1') )
					break;

                if ( $field_stat[1] == 'fieldsetstart' ){
                        $track['$$$'.((int)$i+(int)$off)] = 'Fieldset'.$fieldsetnr;
                        $track['Fieldset'.$fieldsetnr++] = $field_stat[0];
                    } elseif ( $field_stat[1] == 'fieldsetend' ){
                        $track['FieldsetEnd'.$fieldsetnr++] = '--';
                }

                ### get next in line...
                $off++;
                $field_stat = explode('$#$', $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . ((int)$i+(int)$off)] );

                if( $field_stat[1] == '')
                    break 2; ###  all fields searched, break both while & for

			}

			###  filter all redundant WP comment fields if user is logged in
			while ( in_array($field_stat[1],array('cauthor','email','url')) && $user->ID ) {

			    $temp = explode('|', $field_stat[0],3); ### get field name
			    $temp = explode('#', $temp[0],2);
		 		switch( $field_stat[1] ){
						case 'cauthor':
							$track['cauthor'] = $track[$temp[0]] = $user->display_name;
							$track['$$$'.((int)$i+(int)$off)] = $temp[0];
							break;
						case 'email':
							$track['email'] = $track[$temp[0]] = $field_email = $user->user_email;
							$track['$$$'.((int)$i+(int)$off)] = $temp[0];
							break;
						case 'url':
							$track['url'] = $track[$temp[0]] = $user->user_url;
							$track['$$$'.((int)$i+(int)$off)] = $temp[0];
							break;
					}

					$off++;
					$field_stat = explode('$#$', $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . ((int)$i+(int)$off)] );

					if( $field_stat[1] == '')
						break 2; ###  all fields searched, break both while & for
			}

			$field_name = $field_stat[0];
			$field_type = $field_stat[1];

			### remove [id: ] first
			if ( strpos($field_name,'[id:')!==false ){
				$idPartA = strpos($field_name,'[id:');
				$idPartB = strpos($field_name,']',$idPartA);
				$customTrackingID = substr($field_name,$idPartA+4,($idPartB-$idPartA)-4);
				$field_name = substr_replace($field_name,'',$idPartA,($idPartB-$idPartA)+1);
			}
			else
				$customTrackingID='';


			###  dissect field
		    $obj = explode('|', $field_name,3);

			###  strip out default value
			$field_name = $obj[0];


			###  special WP comment fields
			if( in_array($field_stat[1],array('luv','subscribe','cauthor','email','url','comment','send2author')) ){
			    $temp = explode('#', $field_name,2);

				if ( $temp[0] == '' )
                	$field_name = $field_stat[1];
				else
                	$field_name = $temp[0];

				### keep copy of values
    			$track[$field_stat[1]] = stripslashes( $params['field_' . $i] );

				if ( $field_stat[1] == 'email' )
					$field_email = $params['field_' . $i];
			}

			###  special Tell-A-Friend fields
			if ( $taf_friendsemail == '' && $field_type=='friendsemail' && $field_stat[3]=='1')
					$field_email = $taf_friendsemail = $params ['field_' . $i];
			if ( $taf_youremail == '' && $field_type=='youremail' && $field_stat[3]=='1')
					$taf_youremail = $params ['field_' . $i];
			if ( $field_type=='friendsname' )
					$taf_friendsname = $params ['field_' . $i];
			if ( $field_type=='yourname' )
					$taf_yourname = $params ['field_' . $i];


			###  lets find an email field ("Is Email") and that's not empty!
			if ( $field_email == '' && $field_stat[3]=='1') {
					$field_email = $params ['field_' . $i];
			}

			###  special case: select & radio
			if ( $field_type == "multiselectbox" || $field_type == "selectbox" || $field_type == "radiobuttons" || $field_type == "checkboxgroup") {
			  $field_name = explode('#',$field_name);
			  $field_name = $field_name[0];
			}

			###  special case: check box
			if ( $field_type == "checkbox" || $field_type == "ccbox" ) {
			  $field_name = explode('#',$field_name);
			  $field_name = ($field_name[1]=='')?$field_name[0]:$field_name[1];

			  $field_name = explode('|',$field_name);
			  $field_name = $field_name[0];

				###  if ccbox & checked
			  if ($field_type == "ccbox" && $params ['field_' . $i]<>"-" )
			      $ccme = 'field_' . $i;
			}

			if ( $field_type == "emailtobox" ){  			### special case where the value needs to bet get from the DB!

                $to_one = $params ['field_' . $i];
				$field_name = explode('#',$field_stat[0]);  ### can't use field_name, since '|' check earlier

	            $tmp = explode('|', $field_name[$to_one+1] );   ###  remove possible |set:true
	            $value  = $tmp[0];                              ###  values start from 0 or after!
				$to = $replyto = stripslashes($tmp[1]);

				$field_name = $field_name[0];
	 		}
			else {
			    if ( strtoupper(get_option('blog_charset')) <> 'UTF-8' && function_exists('mb_convert_encoding'))
        		    $value = mb_convert_encoding(utf8_decode( stripslashes( $params['field_' . $i] ) ), get_option('blog_charset'));   ###  convert back and forth to support also other than UTF8 charsets
                else
                    $value = stripslashes( $params['field_' . $i] );
            }

			### only if hidden!
			if( $field_type == 'hidden' )
				$value = rawurldecode($value);


			###  Q&A verification
			if ( $field_type == "verification" )
					$field_name = __('Q&A','cforms');


			### determine tracked field name
			$inc='';
			$trackname=trim($field_name);
			if ( array_key_exists($trackname, $track) ){
				if ( $trackinstance[$trackname]=='' )
					$trackinstance[$trackname]=2;
				$inc = '___'.($trackinstance[$trackname]++);
			}

			$track['$$$'.(int)($i+$off)] = $trackname.$inc;
			$track[$trackname.$inc] = $value;
			if( $customTrackingID<>'' )
				$track['$$$'.$customTrackingID] = $trackname.$inc;

	} ###  for


	###  assemble text & html email
	$r = formatEmail($track,$no);
    $formdata = $r['text'];
    $htmlformdata = $r['html'];


	###
	###  record:
	###
	$subID = ( $isTAF=='2' && $track['send2author']<>'1' )?'noid':write_tracking_record($no,$field_email);


	###
	###  allow the user to use form data for other apps
	###
	$trackf['id'] = $no;
	$trackf['data'] = $track;
	if( function_exists('my_cforms_action') )
		my_cforms_action($trackf);



    ###  Catch WP-Comment function | if send2author just continue
    if ( $isAjaxWPcomment!==false && $track['send2author']=='0' ){
		require_once (dirname(__FILE__) . '/lib_WPcomment.php');

	    ###  Catch WP-Comment function: error
	    if ( !$WPsuccess )
    	    return $segments[0].'*$#'.substr($cformsSettings['form'.$no]['cforms'.$no.'_popup'],1,1) . $WPresp .'|---';
    } ### Catch WP-Comment function



	###  multiple recipients? and to whom is the email sent? to_one = picked recip.
	if ( $isAjaxWPcomment!==false && $track['send2author']=='1' ){
			$to = $wpdb->get_results("SELECT U.user_email FROM $wpdb->users as U, $wpdb->posts as P WHERE P.ID = {$Ajaxpid} AND U.ID=P.post_author");
			$to = $replyto = ($to[0]->user_email<>'')?$to[0]->user_email:$replyto;
	}
	else if ( !($to_one<>-1 && $to<>'') )
		$to = $replyto = preg_replace( array('/;|#|\|/'), array(','), stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_email']) );



	### from
	$frommail = check_cust_vars(stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_fromemail']),$track,$no);


	###  T-A-F override?
	if ( $isTAF=='1' && $taf_youremail && $taf_friendsemail )
		$replyto = "\"{$taf_yourname}\" <{$taf_youremail}>";

    ### logic: dynamic admin email address
    if ( function_exists('my_cforms_logic') )
        $to = my_cforms_logic($trackf, $to,'adminTO');  ### use trackf!

	### either use configured subject or user determined
	$vsubject = stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_subject']);
	$vsubject = check_default_vars($vsubject,$no);
	$vsubject = check_cust_vars($vsubject,$track,$no);


	###  prep message text, replace variables
	$message = stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_header']);
	if ( function_exists('my_cforms_logic') )
		$message = my_cforms_logic($trackf, $message,'adminEmailTXT');
	$message = check_default_vars($message,$no);
	$message = check_cust_vars($message,$track,$no);

	###  actual user message
    $htmlmessage='';
    if( substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],2,1)=='1' ){
		$htmlmessage = stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_header_html']);
	    if ( function_exists('my_cforms_logic') )
	        $htmlmessage = my_cforms_logic($trackf, $htmlmessage,'adminEmailHTML');
		$htmlmessage = check_default_vars($htmlmessage,$no);
	    $htmlmessage = check_cust_vars($htmlmessage,$track,$no);

	}

	$mail = new cf_mail($no,$frommail,$to,$field_email, true);
	$mail->subj  = $vsubject;
	$mail->char_set = 'utf-8';

	### HTML email
	if ( $mail->html_show ) {
	    $mail->is_html(true);
	    $mail->body     =  "<html>".$mail->eol."<body>".$htmlmessage.( $mail->f_html?$mail->eol.$htmlformdata:'').$mail->eol."</body></html>".$mail->eol;
	    $mail->body_alt  =  $message . ($mail->f_txt?$mail->eol.$formdata:'');
	}
	else
	    $mail->body     =  $message . ($mail->f_txt?$mail->eol.$formdata:'');


	###  SMTP server or native PHP mail() ?
    if( $cformsSettings['form'.$no]['cforms'.$no.'_emailoff']=='1' || ($WPsuccess && $cformsSettings['form'.$no]['cforms'.$no.'_tellafriend']!='21') )
        $sentadmin = 1;
	else if ( $smtpsettings[0]=='1' )
		$sentadmin = cforms_phpmailer( $no, $frommail, $field_email, $to, $vsubject, $message, $formdata, $htmlmessage, $htmlformdata );
	else
	    $sentadmin = $mail->send();

	if( $sentadmin==1 )
	{
		  ###  send copy or notification?
	    if ( ($cformsSettings['form'.$no]['cforms'.$no.'_confirm']=='1' && $field_email<>'') || ($ccme&&$trackf[$ccme]<>'-') )  ###  not if no email & already CC'ed
	    {

	                $frommail = check_cust_vars(stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_fromemail']),$track,$no);

	                ###  actual user message
	                $cmsg = stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_cmsg']);
	                if ( function_exists('my_cforms_logic') )
	                    $cmsg = my_cforms_logic($trackf, $cmsg,'autoConfTXT');
	                $cmsg = check_default_vars($cmsg,$no);
	                $cmsg = check_cust_vars($cmsg,$track,$no);

	                ###  HTML text
	                $cmsghtml='';
	                if( substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],3,1)=='1' ){
	                    $cmsghtml = stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_cmsg_html']);
	                    if ( function_exists('my_cforms_logic') )
	                        $cmsghtml = my_cforms_logic($trackf, $cmsghtml,'autoConfHTML');
	                    $cmsghtml = check_default_vars($cmsghtml,$no);
	                    $cmsghtml = check_cust_vars($cmsghtml,$track,$no);
	                }

	                ### subject
	                $subject2 = stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_csubject']);
	                $subject2 = check_default_vars($subject2,$no);
	                $subject2 = check_cust_vars($subject2,$track,$no);

	                ###  different cc & ac subjects?
	                $s=explode('$#$',$subject2);
	                $s[1] = ($s[1]<>'') ? $s[1] : $s[0];

	                ###  email tracking via 3rd party?
	                ###  if in Tell-A-Friend Mode, then overwrite header stuff...
	                if ( $taf_youremail && $taf_friendsemail && $isTAF=='1' )
	                    $field_email = "\"{$taf_friendsname}\" <{$taf_friendsemail}>";
	                else
	                    $field_email = ($cformsSettings['form'.$no]['cforms'.$no.'_tracking']<>'')?$field_email.$cformsSettings['form'.$no]['cforms'.$no.'_tracking']:$field_email;

	                $mail = new cf_mail($no,$frommail,$field_email,$replyto);

	                ### auto conf attachment?
	                $a = $cformsSettings['form'.$no]['cforms'.$no.'_cattachment'][0];
	                $a = (substr($a,0,1)=='/') ? $a : dirname(__FILE__).$cformsSettings['global']['cforms_IIS'].$a;
	                if ( $a<>'' && file_exists( $a ) ) {
	                    $n = substr( $a, strrpos($a,$cformsSettings['global']['cforms_IIS'])+1, strlen($a) );
	                    $m = getMIME( strtolower( substr($n,strrpos($n, '.')+1,strlen($n)) ) );
	                    $mail->add_file($a, $n,'base64',$m); ### optional name
	                }

	                $mail->char_set = 'utf-8';

	                ### CC or auto conf?
	                if ( $ccme&&$trackf[$ccme]<>'-' ) {
	                    if ( $smtpsettings[0]=='1' )
	                        $sent = cforms_phpmailer( $no, $frommail, $replyto, $field_email, $s[1], $message, $formdata, $htmlmessage, $htmlformdata, 'ac' );
	                    else{
	                        $mail->subj = $s[1];
	                        if ( $mail->html_show_ac ) {
	                            $mail->is_html(true);
	                            $mail->body     =  "<html>".$mail->eol."<body>".$htmlmessage.( $mail->f_html?$mail->eol.$htmlformdata:'').$mail->eol."</body></html>".$mail->eol;
	                            $mail->body_alt  =  $message . ($mail->f_txt?$mail->eol.$formdata:'');
	                        }
	                        else
	                            $mail->body     =  $message . ($mail->f_txt?$mail->eol.$formdata:'');

	                        $sent = $mail->send();
	                    }
	                }
	                else {
	                    if ( $smtpsettings[0]=='1' )
	                        $sent = cforms_phpmailer( $no, $frommail, $replyto, $field_email, $s[0] , $cmsg , '', $cmsghtml, '', 'ac' );
	                    else{
	                        $mail->subj = $s[0];
	                        if ( $mail->html_show_ac ) {
	                            $mail->is_html(true);
	                            $mail->body     =  "<html>".$mail->eol."<body>".$cmsghtml."</body></html>".$mail->eol;
	                            $mail->body_alt  =  $cmsg;
	                        }
	                        else
	                            $mail->body     =  $cmsg;

	                        $sent = $mail->send();
	                    }
	                }

	                if( $sent<>'1' ) {
	                    $err = __('Error occurred while sending the auto confirmation message: ','cforms') . '<br />'. $smtpsettings[0]?'<br />'.$sent:$mail->ErrorInfo;
	                    $pre = $segments[0].'*$#'.substr($cformsSettings['form'.$no]['cforms'.$no.'_popup'],1,1);
	                    return $pre . $err .'|!!!';
	                }
	    } ###  cc

		###  return success msg
	    $pre = $segments[0].'*$#'.substr($cformsSettings['form'.$no]['cforms'.$no.'_popup'],0,1);

		###  WP-Comment: override
		if ( $WPsuccess )
			$successMsg = $WPresp;
		else{
        	$successMsg	= check_default_vars(stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_success']),$no);
			$successMsg	= str_replace ( $mail->eol, '<br />', $successMsg);
		}

		$successMsg	= check_cust_vars($successMsg,$track,$no);

	    ### logic: possibly change usermessage
	    if ( function_exists('my_cforms_logic') )
	        $successMsg = my_cforms_logic($trackf, $successMsg,'successMessage');


		$opt='';
		###  hide?
        if ( $cformsSettings['form'.$no]['cforms'.$no.'_hide'] || get_cforms_submission_left($no)==0 )
			$opt .= '|~~~';

		###  redirect to a different page on suceess?
		if ( $cformsSettings['form'.$no]['cforms'.$no.'_redirect'] ) {
			if ( function_exists('my_cforms_logic') ){
				$red = my_cforms_logic($trackf, $cformsSettings['form'.$no]['cforms'.$no.'_redirect_page'],'redirection');
            	if ( $red<>'' )
                	$opt .= '|>>>' . $red;  ### use trackf!
            } else
				$opt .= '|>>>' . $cformsSettings['form'.$no]['cforms'.$no.'_redirect_page'];
		}

	    return $pre.$successMsg.$opt;

	}
	else {  ###  no admin mail sent!

		###  return error msg
		$err = __('Error occurred while sending the message: ','cforms') . '<br />'. $smtpsettings[0]?'<br />'.$sentadmin:$mail->ErrorInfo;
	    $pre = $segments[0].'*$#'.substr($cformsSettings['form'.$no]['cforms'.$no.'_popup'],1,1);
	    return $pre . $err .'|!!!';

	}

} ### function



###
###  sajax stuff
###

if (!isset($SAJAX_INCLUDED)) {

	$GLOBALS['sajax_version'] = '0.12';
	$GLOBALS['sajax_debug_mode'] = 0;
	$GLOBALS['sajax_export_list'] = array();
	$GLOBALS['sajax_request_type'] = 'POST';
	$GLOBALS['sajax_remote_uri'] = '';
	$GLOBALS['sajax_failure_redirect'] = '';

	function sajax_init() {
	}

	function sajax_get_my_uri() {
		return $_SERVER["REQUEST_URI"];
	}
	$sajax_remote_uri = sajax_get_my_uri();

	function sajax_get_js_repr($value) {
		$type = gettype($value);

		if ($type == "boolean") {
			return ($value) ? "Boolean(true)" : "Boolean(false)";
		}
		elseif ($type == "integer") {
			return "parseInt($value)";
		}
		elseif ($type == "double") {
			return "parseFloat($value)";
		}
		elseif ($type == "array" || $type == "object" ) {
			$s = "{ ";
			if ($type == "object") {
				$value = get_object_vars($value);
			}
			foreach ($value as $k=>$v) {
				$esc_key = sajax_esc($k);
				if (is_numeric($k))
					$s .= "$k: " . sajax_get_js_repr($v) . ", ";
				else
					$s .= "\"$esc_key\": " . sajax_get_js_repr($v) . ", ";
			}
			if (count($value))
				$s = substr($s, 0, -2);
			return $s . " }";
		}
		else {
			$esc_val = sajax_esc($value);
			$s = "'$esc_val'";
			return $s;
		}
	}

	function sajax_handle_client_request() {
		global $sajax_export_list;

		$mode = "";

		if (! empty($_GET["rs"]))
			$mode = "get";

		if (!empty($_POST["rs"]))
			$mode = "post";

		if (empty($mode))
			return;

		$target = "";

		if ($mode == "get") {
			###  Bust cache in the head
			header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    ###  Date in the past
			header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			###  always modified
			header ("Cache-Control: no-cache, must-revalidate");  ###  HTTP/1.1
			header ("Pragma: no-cache");                          ###  HTTP/1.0
			$func_name = sajax_sanitize( $_GET["rs"] );
			if (! empty($_GET["rsargs"]))
				$args = sajax_sanitize( $_GET["rsargs"] );
			else
				$args = array();
		}
		else {
			$func_name = sajax_sanitize( $_POST["rs"] );
			if (! empty($_POST["rsargs"]))
				$args = sajax_sanitize( $_POST["rsargs"] );
			else
				$args = array();
		}

		if (! in_array($func_name, $sajax_export_list))
			echo "-:$func_name not callable";
		else {
			$result = call_user_func_array($func_name, $args);
			echo "+:";
			echo "var res = " . trim(sajax_get_js_repr($result)) . "; res;";
		}
		exit;
	}

	### sanitize
	function sajax_sanitize($t) {
		//$t = preg_replace('/\s/', '', $t);
		$t = str_replace('<php', '', $t);
		$t = str_replace('<?', '', $t);
		return $t;
	}

	###  javascript escape a value
	function sajax_esc($val)
	{
		$val = str_replace("\\", "\\\\", $val);
		$val = str_replace("\r", "\\r", $val);
		$val = str_replace("\n", "\\n", $val);
		$val = str_replace("'", "\\'", $val);
		return str_replace('"', '\\"', $val);
	}

	function sajax_get_one_stub($func_name) {
		ob_start();
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function sajax_show_one_stub($func_name) {
		echo sajax_get_one_stub($func_name);
	}

	function sajax_export() {
		global $sajax_export_list;

		$n = func_num_args();
		for ($i = 0; $i < $n; $i++) {
			$sajax_export_list[] = func_get_arg($i);
		}
	}

	$sajax_js_has_been_shown = 0;
	function sajax_get_javascript()
	{
		global $sajax_js_has_been_shown;
		global $sajax_export_list;

		$html = "";
		if (! $sajax_js_has_been_shown) {
			$html .= sajax_get_common_js();
			$sajax_js_has_been_shown = 1;
		}
		foreach ($sajax_export_list as $func) {
			$html .= sajax_get_one_stub($func);
		}
		return $html;
	}

	$SAJAX_INCLUDED = 1;
}

###  $sajax_debug_mode = 1;
sajax_init();
sajax_export("cforms_submitcomment");
sajax_export("reset_captcha");
sajax_handle_client_request();
?>