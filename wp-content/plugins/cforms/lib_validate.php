<?php

### Validating non Ajax form submission
###
###

$cflimit = '';
$filefield = 0;

$captchaopt = $cformsSettings['global']['cforms_captcha_def'];

for($i = 1; $i <= $field_count; $i++) {

		if ( !$custom )
			$field_stat = explode('$#$', $cformsSettings['form'.$no]['cforms'.$no.'_count_field_'.((int)$i+(int)$off)]);
		else
			$field_stat = explode('$#$', $customfields[((int)$i+(int)$off) - 1]);

		### filter non input fields
		while ( $field_stat[1] == 'fieldsetstart' || $field_stat[1] == 'fieldsetend' || $field_stat[1] == 'textonly' ) {
				$off++;

				if ( !$custom )
                    $field_stat = explode('$#$', $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . ((int)$i+(int)$off)]);
                else
                    $field_stat = explode('$#$', $customfields[((int)$i+(int)$off) - 1]);

				if( $field_stat[1] == '')
						break 2; ### all fields searched, break both while & for
		}


		### custom error set?
		$c_err = explode('|err:', $field_stat[0], 2);
		$c_title = explode('|title:', $c_err[0], 2);

		$field_name = $c_title[0];
		$field_type = $field_stat[1];
		$field_required = $field_stat[2];
		$field_emailcheck = $field_stat[3];


		### ommit certain fields; validation only!
		if( in_array($field_type,array('cauthor','url','email')) ){
			if ( $user->ID ){
				$validations[$i+$off] = 1;   ### auto approved
				continue;
			}
		}

		### captcha not for logged in users
		$jump = ($field_stat[1] == 'captcha') && is_user_logged_in() && $captchaopt['fo']<>'1';
		$jump = $jump || ( ($field_stat[1] == 'verification') && is_user_logged_in() && $captchaopt['foqa']<>'1' );

		if( $jump )	continue;

		### if subscribe not shown, skip
		$isSubscribed=='';
		if ( class_exists('sg_subscribe') ){
			global $sg_subscribe;
			sg_subscribe_start();
			$isSubscribed = $sg_subscribe->current_viewer_subscription_status();
		}
		if( in_array($field_type,array('subscribe')) && $isSubscribed<>'' )
			continue;

		### comment luv
		get_currentuserinfo();
		global $user_level;
		if( in_array($field_type,array('luv')) && $user_level==10 )
			continue;

		### input field names & label
		$custom_names = ($cformsSettings['form'.$no]['cforms'.$no.'_customnames']=='1')?true:false;

		if ( $custom_names ){

			preg_match('/^([^#\|]*).*/',$field_name,$input_name);

			if ( strpos($input_name[1],'[id:')!==false ){
				$idPartA = strpos($input_name[1],'[id:');
				$idPartB = strpos($input_name[1],']',$idPartA);
				$current_field = $_REQUEST[ cf_sanitize_ids( substr($input_name[1],$idPartA+4,($idPartB-$idPartA)-4) ) ];

				$field_name = substr_replace($input_name[1],'',$idPartA,($idPartB-$idPartA)+1);
			} else
				$current_field = $_REQUEST[ cf_sanitize_ids($input_name[1]) ];

		}
		else
			$current_field = $_REQUEST['cf'.$no.'_field_' . ((int)$i+(int)$off)];

		if( in_array($field_type,array('luv','subscribe','comment','url','email','cauthor')) )  ### WP comment field name exceptions
			$current_field = $_REQUEST[$field_type];

		$current_field = is_array($current_field) ? $current_field : stripslashes($current_field);

		if( $field_emailcheck ) {  ### email field

				### special email field in WP Commente
				if ( $field_type=='email' )
					$validations[$i+$off] = cforms_is_email( $_REQUEST['email']) || (!$field_required && $_REQUEST['email']=='');
				else
					$validations[$i+$off] = cforms_is_email( $current_field ) || (!$field_required && $current_field=='');

				if ( !$validations[$i+$off] && $err==0 ) $err=1;

		}
		else if( $field_required && !in_array($field_type,array('verification','captcha'))  ) { ### just required

				if( in_array($field_type,array('cauthor','url','comment','pwfield','textfield','datepicker','textarea','yourname','youremail','friendsname','friendsemail')) ){

							$validations[$i+$off] = ($current_field=='')?false:true;

				}else if( $field_type=="checkbox" ) {

							$validations[$i+$off] = ($current_field=='')?false:true;

				}else if( $field_type=="selectbox" || $field_type=="emailtobox" ) {

							$validations[$i+$off] = !($current_field == '-' );

				}else if( $field_type=="multiselectbox" ) {

							### how many multiple selects ?
                            $all_options = $current_field;
							if ( count($all_options) <= 1 && $all_options[0]=='' )
									$validations[$i+$off] = false;
                            else
									$validations[$i+$off] = true;

				}else if( $field_type=="upload" ) {  ### prelim upload check

							$validations[$i+$off] = !($_FILES['cf_uploadfile'.$no][name][$filefield++]=='');
							if ( !$validations[$i+$off] && $err==0 )
									{ $err=3; $fileerr = $cformsSettings['global']['cforms_upload_err2']; }
				}else if( in_array($field_type,array('cauthor','url','email','comment')) ) {

						$validations[$i+$off] = ($_REQUEST[$field_type]=='')?false:true;

				}else if( $field_type=="radiobuttons" ) {

						$validations[$i+$off] = ($current_field=='')?false:true;

				}

				if ( !$validations[$i+$off] && $err==0 ) $err=1;

		}
		else if( $field_type == 'verification' ){  ### visitor verification code

        		$validations[$i+$off] = 1;
				if ( $_REQUEST['cforms_a'.$no] <> md5(rawurlencode(strtolower($_REQUEST['cforms_q'.$no]))) ) {
						$validations[$i+$off] = 0;
						$err = !($err)?2:$err;
				}

		}
		else if( $field_type == 'captcha' ){  ### captcha verification

        		$validations[$i+$off] = 1;

				$a = explode('+',$_COOKIE['turing_string_'.$no]);

				$a = $a[1];
				$b = md5( ($captchaopt['i'] == 'i')?strtolower($_REQUEST['cforms_captcha'.$no]):$_REQUEST['cforms_captcha'.$no]);

				if ( $a <> $b ) {
						$validations[$i+$off] = 0;
						$err = !($err)?2:$err;
				}

		}
		else
			$validations[$i+$off] = 1;



		### REGEXP now outside of 'is required'
		if( in_array($field_type,array('cauthor','url','comment','pwfield','textfield','datepicker','textarea','yourname','youremail','friendsname','friendsemail')) ){

				### regexp set for textfields?
				$obj = explode('|', $c_title[0], 3);

  				if ( $obj[2] <> '') { ### check against other field!

  					if (  isset($_REQUEST[$obj[2]]) && $_REQUEST[$obj[2]]<>'' ){

						if( $current_field <> $_REQUEST[$obj[2]] )
						    $validations[$i+$off] = false;
  					}
  					else { ### classic regexp
						$reg_exp = str_replace('/','\/',stripslashes($obj[2]) );

						### multi-line textarea regexp trick
						if( $field_type == 'textarea' )
						    $valField = (string)str_replace(array("\r", "\r\n", "\n"), ' ', $current_field);
						else
						    $valField = $current_field;

                        if( $current_field<>'' && !preg_match('/'.$reg_exp.'/', $valField) ){
						    $validations[$i+$off] = false;
						}
					}
				}
				if ( !$validations[$i+$off] && $err==0 ) $err=1;
		}



		$all_valid = $all_valid && $validations[$i+$off];

		if ( $c_err[1] <> '' && $validations[$i+$off] == false ){
			$c_errflag=4;

			if ( $cformsSettings['global']['cforms_liID']=='1' ){
				$custom_error .= '<li><a href="#li-'.$no.'-'.($i+$off).'">'.stripslashes($c_err[1]).' &raquo;</li></a>';
			} else
				$custom_error .= '<li>' . stripslashes($c_err[1]) . '</li>';

		}

	}


###
### have to upload a file?
###

global $file;
$file='';
$i=0;

if( isset($_FILES['cf_uploadfile'.$no]) && $all_valid){

 	$file = $_FILES['cf_uploadfile'.$no];

	foreach( $file[name] as $value ) {

		if(!empty($value)){   ### this will check if any blank field is entered

			if ( function_exists('my_cforms_logic') )
                $file[name][$i] = my_cforms_logic($_REQUEST,$_FILES['cf_uploadfile'.$no][name][$i],"filename");

            $fileerr = '';
              ### A successful upload will pass this test. It makes no sense to override this one.
              if ( $file['error'][$i] > 0 )
                      $fileerr = $cformsSettings['global']['cforms_upload_err1'];

              ### A successful upload will pass this test. It makes no sense to override this one.
              $fileext[$i] = strtolower( substr($value,strrpos($value, '.')+1,strlen($value)) );
              $allextensions = explode(',' ,  preg_replace('/\s/', '', strtolower($cformsSettings['form'.$no]['cforms'.$no.'_upload_ext'])) );

              if ( $cformsSettings['form'.$no]['cforms'.$no.'_upload_ext']<>'' && !in_array($fileext[$i], $allextensions) )
                      $fileerr = $cformsSettings['global']['cforms_upload_err5'];

              ### A non-empty file will pass this test.
              if ( !( $file['size'][$i] > 0 ) )
                      $fileerr = $cformsSettings['global']['cforms_upload_err2'];

				### A non-empty file will pass this test.
				if ( (int)$cformsSettings['form'.$no]['cforms'.$no.'_upload_size'] > 0 ) {
	            	if ( $file['size'][$i] >= (int)$cformsSettings['form'.$no]['cforms'.$no.'_upload_size'] * 1024 )
						$fileerr = $cformsSettings['global']['cforms_upload_err3'];
				}

              ### A properly uploaded file will pass this test. There should be no reason to override this one.
              if (! @ is_uploaded_file( $file['tmp_name'][$i] ) )
                      $fileerr = $cformsSettings['global']['cforms_upload_err4'];

              if ( $fileerr <> '' ){

                      $err = 3;
                      $all_valid = false;

              } ### file uploaded

        } ### if !empty
		$i++;

    } ### while all file

} ### no file upload triggered

###
### what kind of error message?
###
switch($err){
	case 0: break;
	case 1:
			$usermessage_text = preg_replace ( array("|\\\'|",'/\\\"/','|\r\n|'),array('&#039;','&quot;','<br />'), $cformsSettings['form'.$no]['cforms'.$no.'_failure'] );
			break;
	case 2:
			$usermessage_text = preg_replace ( array("|\\\'|",'/\\\"/','|\r\n|'),array('&#039;','&quot;','<br />'), $cformsSettings['global']['cforms_codeerr'] );
			break;
	case 3:
			$usermessage_text = preg_replace ( array("|\\\'|",'/\\\"/','|\r\n|'),array('&#039;','&quot;','<br />'), $fileerr);
			break;
	case 4:
			$usermessage_text = preg_replace ( array("|\\\'|",'/\\\"/','|\r\n|'),array('&#039;','&quot;','<br />'), $cformsSettings['form'.$no]['cforms'.$no.'_failure'] );
			break;

}
if ( $err<>0 && $c_errflag )
	$usermessage_text .= '<ol>'.$custom_error.'</ol>';

### proxy functions
function cforms_is_email($string){
	return eregi("^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $string);
}

?>