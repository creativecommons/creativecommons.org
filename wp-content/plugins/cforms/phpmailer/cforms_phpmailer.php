<?php

###
### SMTP / PHPmailer support
###
function cforms_phpmailer( $no, $frommail, $field_email, $to, $vsubject, $message, $formdata, $htmlmessage, $htmlformdata, $file='ac' ) {

	global $smtpsettings, $phpmailer, $cformsSettings;

    $eol = ($cformsSettings['global']['cforms_crlf'][b]!=1)?"\r\n":"\n";

	if ( file_exists(dirname(__FILE__) . '/class.phpmailer.php') && !class_exists('PHPMailer') ) {
		require_once(dirname(__FILE__) . '/class.phpmailer.php');
		require_once(dirname(__FILE__) . '/class.smtp.php');

		if( $smtpsettings[6]=='1' )
			require_once(dirname(__FILE__) . '/class.pop3.php');
	}

		### pop before smtp?
		if( $smtpsettings[6]=='1' && class_exists('POP3') ){
			$debuglevel = 0;
		    $pop = new POP3();
	    	$pop->Authorise($smtpsettings[7], $smtpsettings[8], 30, $smtpsettings[9], $smtpsettings[10], $debuglevel);
		}

		$mail = new PHPMailer();
		$mail->ClearAllRecipients();
		$mail->ClearAddresses();
		$mail->ClearAttachments();
		$mail->CharSet = 'utf-8';
        $mail->SetLanguage('en', dirname(__FILE__).'/');
		$mail->PluginDir = dirname(__FILE__).'/';
		$mail->IsSMTP();
		$mail->Host	= $smtpsettings[1];

        if( (int)$cformsSettings['form'.$no]['cforms'.$no.'_emailpriority'] > 0 )
	        $mail->Priority = (int)$cformsSettings['form'.$no]['cforms'.$no.'_emailpriority'];

		### $mail->SMTPDebug = true;

		if ( $smtpsettings[4]<>'' ){
			$mail->SMTPSecure = $smtpsettings[4];   ### sets the prefix to the servier
			$mail->Port       = $smtpsettings[5];   ### set the SMTP port
		}

		if ( $smtpsettings[2]<>'' ){
			$mail->SMTPAuth = true;         	### turn on SMTP authentication
			$mail->Username = $smtpsettings[2]; ### SMTP username
			$mail->Password = $smtpsettings[3]; ### SMTP password
		}

		$temp2=array();
		###from
		if( preg_match('/([\w-\.]+@([\w-]+\.)+[\w-]{2,4})/',$frommail,$temp) )
			$mail->From     = $temp[0];

		if( preg_match('/(.*)\s+(([\w-\.]+@|<)).*/',$frommail,$temp2) )
			$mail->FromName = str_replace('"','',$temp2[1]);
		else
			$mail->FromName = $temp[0];

		$temp2=array();

		### reply-to
		if( preg_match('/([\w-\.]+@([\w-]+\.)+[\w-]{2,4})/',$field_email,$temp) ) {
			if ( preg_match('/(.*)\s+(([\w-\.]+@|<)).*/',$field_email,$temp2) )
				$mail->AddReplyTo($temp[0] ,str_replace('"','',$temp2[1]) );
			else
				$mail->AddReplyTo($temp[0]);
		}

		### TAF: add CC
		if ( substr($cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'],0,1)=='1' && $file=='ac' && $cformsSettings['form'.$no]['cforms'.$no.'_tafCC']=='1' )
			$mail->AddCC($temp[0],str_replace('"','',$temp2[1]));


	    ### bcc
	    $te=array();
	    $t=array();
	    $addresses = explode(',',stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_bcc']) );
	    foreach( $addresses as $a ){
	        if( preg_match('/([\w-+\.]+@([\w-]+\.)+[\w-]{2,4})/',$a,$te) )
					$mail->AddBCC($te[0]);
		}



		###to
		###if( preg_match('/[\w-\.]+@([\w-]+\.)+[\w-]{2,4}/',$to,$temp) )
		###	$mail->AddAddress($temp[0]);

		$addresses = explode(',',$to);
		foreach( $addresses as $address ){

			if( preg_match('/([\w-\.]+@([\w-]+\.)+[\w-]{2,4})/',$address,$temp) ) {
				if ( preg_match('/(.*)\s+(([\w-\.]+@|<)).*/',$address,$temp2) )
					$mail->AddAddress($temp[0] ,str_replace('"','',$temp2[1]) );
				else
					$mail->AddAddress($temp[0]);
			}

		}

        ###
		### HTML email ?
        ###
		if ($htmlmessage<>'') {
			$htmlmessage = str_replace('=3D','=',$htmlmessage);  ###remove 3D's
			$htmlformdata = str_replace('=3D','=',$htmlformdata);  ###remove 3D's,
			$mail->IsHTML(true);
			$mail->Body     =  "<html>".$eol."<body>".stripslashes($htmlmessage).
            					( (substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],1,1)=='1' && $htmlformdata<>'') ? $eol.$htmlformdata : '' ).
                                $eol."</body></html>".$eol;
			$mail->AltBody  =  stripslashes($message).((substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],0,1)=='1'&&$formdata<>'')?$eol.$formdata:'');
		}
		else
			$mail->Body     =  stripslashes($message).((substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],0,1)=='1'&&$formdata<>'')?$eol.$formdata:'');


	    ###
	    ### adding attachments
	    ###
		global $fdata;

		if (	$file=='1' && !$cformsSettings['form'.$no]['cforms'.$no.'_noattachments'] ) {

            foreach ( $fdata as $file ) {
				if ( $file[name] <> '' ){
	                $n = substr( $file[name], strrpos($file[name],$cformsSettings['global']['cforms_IIS'])+1, strlen($file[name]) );
	                $m = getMIME( strtolower( substr($n,strrpos($n, '.')+1,strlen($n)) ) );
					$mail->AddAttachment($file[name], $n,'base64',$m); ### optional name
				}

            } ### for

 		}
	    ### end adding attachments

		$mail->Subject  = $vsubject;
		$sentadmin      = $mail->Send();

		if ($sentadmin)
			return 1;
		else
			return $mail->ErrorInfo;
}
?>