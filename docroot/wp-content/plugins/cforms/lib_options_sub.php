<?php

	### set to nothing
	$usermsg='';

	$verification=false;
	$captcha=false;
	$ccbox=false;
	$emailtobox=false;
	$WPc=false;
	$taf=false;
	$uploadfield=false;

	for($i = 1; $i <= $field_count; $i++) {

		if ($_REQUEST['field_' . $i . '_name']<>''){ ### safety

	        $allgood=true;
	        $name = magic(str_replace('$#$', '$', $_REQUEST['field_' . $i . '_name']));
	        $type = $_REQUEST['field_' . $i . '_type'];
	        $required = 0;
	        $emailcheck = 0;
	        $clear = 0;
	        $disabled = 0;
	        $readonly = 0;

			     $isTAF = (int)substr($cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'],0,1);

        if( !$uploadfield )
          $uploadfield = ($type == 'upload');

				if( in_array($type, array('cauthor','email','url','comment','send2author')) && !($isTAF==2) ){
					$allgood = $WPc?false:true;
					$usermsg .= '<span class="exMsg">'.__('WP comment form fields only supported when <em>WP comment feature</em> turned on!', 'cforms').'</span>';
					$WPc=true;
				}
				if( in_array($type, array('yourname','youremail','friendsname','friendsemail')) && !($isTAF==1) ){
					$allgood = $taf?false:true;
					$usermsg .= '<span class="exMsg">'.__('TAF fields only supported when <em>TAF feature</em> turned on!', 'cforms').'</span>';
					$taf=true;
				}

				if( $type=='verification' ){
					$allgood = $verification?false:true;
					$usermsg .= $verification?'<span class="exMsg">'.__('Only one <em>Visitor verification</em> field is permitted!', 'cforms').'</span>':'';
					$verification=true;
				}
				if( $type=='captcha' ){
					$allgood = $captcha?false:true;
					$usermsg .= $captcha?'<span class="exMsg">'.__('Only one <em>captcha</em> field is permitted!', 'cforms').'</span>':'';
					$captcha=true;
				}
				if( $type=='ccbox' ){
					$allgood = $ccbox?false:true;
					$usermsg .= $ccbox?'<span class="exMsg">'.__('Only one <em>CC:</em> field is permitted!', 'cforms').'</span>':'';
					$ccbox=true;
				}
				if( $type=='emailtobox' ){
					$allgood = $emailtobox?false:true;
					$usermsg .= $emailtobox?'<span class="exMsg">'.__('Only one <em>Multiple Recipients</em> field is permitted!'.'</span>', 'cforms'):'';
					$emailtobox=true;
				}

				if(isset($_REQUEST['field_' . $i . '_required']) && in_array($type,array('pwfield','textfield','datepicker','textarea','checkbox','multiselectbox','selectbox','emailtobox','upload','yourname','youremail','friendsname','friendsemail','email','cauthor','url','comment','radiobuttons')) ) {
					$required = 1;
				}

				if(isset($_REQUEST['field_' . $i . '_emailcheck']) && in_array($type,array('textfield','datepicker','youremail','friendsemail','email')) ){
					$emailcheck = 1;
				}

				if(isset($_REQUEST['field_' . $i . '_clear']) && in_array($type,array('pwfield','textfield','datepicker','textarea','yourname','youremail','friendsname','friendsemail','email','cauthor','url','comment')) ) {
					$clear = 1;
				}

				if(isset($_REQUEST['field_' . $i . '_disabled']) && in_array($type,array('pwfield','textarea','datepicker','textfield','checkbox','checkboxgroup','multiselectbox','selectbox','radiobuttons','upload')) ) {
					$disabled = 1;
				}

				if(isset($_REQUEST['field_' . $i . '_readonly']) && in_array($type,array('pwfield','textarea','datepicker','textfield','checkbox','checkboxgroup','multiselectbox','selectbox','radiobuttons','upload')) ) {
					$readonly = 1;
				}

				$all_fields[$i-1] = $name . '$#$' . $type . '$#$' . $required. '$#$' . $emailcheck . '$#$'. $clear . '$#$' . $disabled . '$#$' . $readonly;

				if ($allgood)
						$cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . $i] = $all_fields[$i-1];

		}
	}


	### update new settings container
	$cformsSettings['form'.$no]['cforms'.$no.'_fname'] =          preg_replace( array('/\\\+/','/\//','/"/'), array('\\','-','\''), magic($_REQUEST['cforms_fname']) );


	$cformsSettings['form'.$no]['cforms'.$no.'_noid'] =           $_REQUEST['cforms_upload_noid']?'1':'0';
	if( $uploadfield && $_REQUEST['cforms_upload_dir']<>'' )
    $cformsSettings['form'.$no]['cforms'.$no.'_upload_dir'] =     magic($_REQUEST['cforms_upload_dir'].'$#$'.$_REQUEST['cforms_upload_dir_url']);
  $cformsSettings['form'.$no]['cforms'.$no.'_upload_ext'] =     magic($_REQUEST['cforms_upload_ext']);
  $cformsSettings['form'.$no]['cforms'.$no.'_upload_size'] =    $_REQUEST['cforms_upload_size'];
  $cformsSettings['form'.$no]['cforms'.$no.'_noattachments'] =  $_REQUEST['cforms_noattachments']?'1':'0';


	$cformsSettings['form'.$no]['cforms'.$no.'_submit_text'] =   magic($_REQUEST['cforms_submit_text']);
	$cformsSettings['form'.$no]['cforms'.$no.'_working'] =       $_REQUEST['cforms_working'];
  	$cformsSettings['form'.$no]['cforms'.$no.'_required'] =      $_REQUEST['cforms_required'];
  	$cformsSettings['form'.$no]['cforms'.$no.'_emailrequired'] = $_REQUEST['cforms_emailrequired'];
	$cformsSettings['form'.$no]['cforms'.$no.'_success'] =       magic($_REQUEST['cforms_success']);
	$cformsSettings['form'.$no]['cforms'.$no.'_failure'] =       magic($_REQUEST['cforms_failure']);
	$cformsSettings['form'.$no]['cforms'.$no.'_popup'] =         ($_REQUEST['cforms_popup1']?'y':'n').($_REQUEST['cforms_popup2']?'y':'n') ;
	$cformsSettings['form'.$no]['cforms'.$no.'_showpos'] =       ($_REQUEST['cforms_showposa']?'y':'n').($_REQUEST['cforms_showposb']?'y':'n').
																 ($_REQUEST['cforms_errorLI']?'y':'n').($_REQUEST['cforms_errorINS']?'y':'n').
																 ($_REQUEST['cforms_jump']?'y':'n') ;

	$cformsSettings['form'.$no]['cforms'.$no.'_formaction'] =     $_REQUEST['cforms_formaction']?true:false;
	$cformsSettings['form'.$no]['cforms'.$no.'_dontclear'] =     $_REQUEST['cforms_dontclear']?true:false;
	$cformsSettings['form'.$no]['cforms'.$no.'_dashboard'] =	 $_REQUEST['cforms_dashboard']?'1':'0';
    $cformsSettings['form'.$no]['cforms'.$no.'_notracking'] =    $_REQUEST['cforms_notracking']?true:false;
	$cformsSettings['form'.$no]['cforms'.$no.'_customnames'] =	 $_REQUEST['cforms_customnames']?'1':'0';
	$cformsSettings['form'.$no]['cforms'.$no.'_hide'] =			 $_REQUEST['cforms_hide']?true:false;

	$cformsSettings['form'.$no]['cforms'.$no.'_maxentries'] =	 				$_REQUEST['cforms_maxentries']==''?'':(int)$_REQUEST['cforms_maxentries'];
	if ($_REQUEST['cforms_startdate']<>'' && $_REQUEST['cforms_starttime']=='') $_REQUEST['cforms_starttime'] = '00:00';
	if ($_REQUEST['cforms_starttime']<>'' && $_REQUEST['cforms_startdate']=='') $_REQUEST['cforms_startdate'] = date('d/m/Y');
	if ($_REQUEST['cforms_enddate']<>'' && $_REQUEST['cforms_endtime']=='')     $_REQUEST['cforms_endtime'] = '00:00';
	if ($_REQUEST['cforms_endtime']<>'' && $_REQUEST['cforms_enddate']=='')     $_REQUEST['cforms_enddate'] = date('d/m/Y');
	$cformsSettings['form'.$no]['cforms'.$no.'_startdate'] = 					preg_replace("/\\\+/", "\\",$_REQUEST['cforms_startdate']).' '.
    																			preg_replace("/\\\+/", "\\",$_REQUEST['cforms_starttime']);
    $cformsSettings['form'.$no]['cforms'.$no.'_enddate'] =  					preg_replace("/\\\+/", "\\",$_REQUEST['cforms_enddate']).' '.
    																			preg_replace("/\\\+/", "\\",$_REQUEST['cforms_endtime']);
	if( isset($_REQUEST['cforms_limittxt']) )
		$cformsSettings['form'.$no]['cforms'.$no.'_limittxt'] =   				magic($_REQUEST['cforms_limittxt']);

	$cformsSettings['form'.$no]['cforms'.$no.'_redirect'] =       $_REQUEST['cforms_redirect']?true:false;
	$cformsSettings['form'.$no]['cforms'.$no.'_redirect_page'] =  preg_replace("/\\\+/", "\\",$_REQUEST['cforms_redirect_page']);
	$cformsSettings['form'.$no]['cforms'.$no.'_action'] =         $_REQUEST['cforms_action']?'1':'0';
	$cformsSettings['form'.$no]['cforms'.$no.'_action_page'] =    preg_replace("/\\\+/", "\\",$_REQUEST['cforms_action_page']);
	$cformsSettings['form'.$no]['cforms'.$no.'_rss'] =            $_REQUEST['cforms_rss']?true:false;
	$cformsSettings['form'.$no]['cforms'.$no.'_rss_count'] =      $_REQUEST['cforms_rsscount'];
	if( isset($_REQUEST['cforms_rssfields']) ){
		$i=1;
		foreach($_REQUEST['cforms_rssfields'] as $e)
        	$cformsSettings['form'.$no]['cforms'.$no.'_rss_fields'][$i++] = $e;
	}


	$cformsSettings['form'.$no]['cforms'.$no.'_emailoff'] =		 $_REQUEST['cforms_emailoff']?'1':'0';
	$cformsSettings['form'.$no]['cforms'.$no.'_fromemail'] =     magic($_REQUEST['cforms_fromemail']);
	$cformsSettings['form'.$no]['cforms'.$no.'_email'] =         magic($_REQUEST['cforms_email']);
	$cformsSettings['form'.$no]['cforms'.$no.'_bcc'] =           magic($_REQUEST['cforms_bcc']);
	$cformsSettings['form'.$no]['cforms'.$no.'_subject'] =       magic($_REQUEST['cforms_subject']);
	$cformsSettings['form'.$no]['cforms'.$no.'_emailpriority'] = $_REQUEST['emailprio'];
	$cformsSettings['form'.$no]['cforms'.$no.'_header'] =        preg_replace("/\\\+/", "\\",magic($_REQUEST['cforms_header']));
	$cformsSettings['form'.$no]['cforms'.$no.'_header_html'] =   preg_replace("/\\\+/", "\\",magic($_REQUEST['cforms_header_html']));
	$cformsSettings['form'.$no]['cforms'.$no.'_formdata'] =      ($_REQUEST['cforms_formdata_txt']?'1':'0').($_REQUEST['cforms_formdata_html']?'1':'0').
    															 ($_REQUEST['cforms_admin_html']?'1':'0').($_REQUEST['cforms_user_html']?'1':'0') ;
	$cformsSettings['form'.$no]['cforms'.$no.'_space'] =         $_REQUEST['cforms_space'];

    ## quickly get old vals
    $t=explode('$#$',$cformsSettings['form'.$no]['cforms'.$no.'_csubject']);

    if( $_REQUEST['cforms_confirm'] && $cformsSettings['form'.$no]['cforms'.$no.'_confirm']==1 ){
        $t[0] = 													  preg_replace("/\\\+/", "\\",magic($_REQUEST['cforms_csubject']));
	    $cformsSettings['form'.$no]['cforms'.$no.'_cattachment'][0] = $_REQUEST['cforms_cattachment'];
	    $cformsSettings['form'.$no]['cforms'.$no.'_cmsg'] =     	  preg_replace("/\\\+/", "\\",magic($_REQUEST['cforms_cmsg']));
	    $cformsSettings['form'.$no]['cforms'.$no.'_cmsg_html'] =	  preg_replace("/\\\+/", "\\",magic($_REQUEST['cforms_cmsg_html']));

	}

    $cformsSettings['form'.$no]['cforms'.$no.'_confirm'] =		$_REQUEST['cforms_confirm']?'1':'0';

    if( $_REQUEST['cforms_ccsubject']!='' )
		$t[1] = preg_replace("/\\\+/", "\\",magic($_REQUEST['cforms_ccsubject']));

    $cformsSettings['form'.$no]['cforms'.$no.'_csubject'] =		$t[0].'$#$'.$t[1];


	$cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_form'] = 	 $_REQUEST['cforms_mp_form']?true:false;
	if ( $_REQUEST['cforms_mp_form']==true && $_REQUEST['cforms_mp_next']=='' )
		$cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_next'] = -1;
    else
		$cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_next'] = $_REQUEST['cforms_mp_next'];

	$cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_first'] = 		$_REQUEST['cforms_mp_first']?true:false;
	$cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_email'] =  		$_REQUEST['cforms_mp_email']?true:false;
	$cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_reset'] = 		$_REQUEST['cforms_mp_reset']?true:false;
	$cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_resettext']=	magic($_REQUEST['cforms_mp_resettext']);
	$cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_back']     = 	$_REQUEST['cforms_mp_back']?true:false;
	$cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_backtext'] =	magic($_REQUEST['cforms_mp_backtext']);
	if( $_REQUEST['cforms_mp_form'] ){
		$cformsSettings['form'.$no]['cforms'.$no.'_ajax']       = '0';
    $cformsSettings['form'.$no]['cforms'.$no.'_dontclear']  = false;
	} else
		$cformsSettings['form'.$no]['cforms'.$no.'_ajax'] = 			$_REQUEST['cforms_ajax']?'1':'0';


	$cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'] = 		'01'; ### default
	$cformsSettings['form'.$no]['cforms'.$no.'_tafCC'] = 	   		$_REQUEST['cforms_tafCC']?'1':'0';

	if ( isset($_REQUEST['cforms_taftrick']) )
		$cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'] = 	'31';

	if ( isset($_REQUEST['cforms_tellafriend']) )
		$cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'] =	'1'.($_REQUEST['cforms_tafdefault']?'1':'0') ;


	if ( isset($_REQUEST['cforms_commentrep']) )
		$cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'] =	'2'.($_REQUEST['cforms_commentXnote']?'1':'0') ;


	$cformsSettings['form'.$no]['cforms'.$no.'_tracking'] =      preg_replace("/\\\+/", "\\",$_REQUEST['cforms_tracking']);


	### reorder fields
	if(isset($_REQUEST['field_order']) && $_REQUEST['field_order']<>'') {
		$j=0;

		$result = preg_match_all('/allfields\[\]=f([^&]+)&?/',$_REQUEST['field_order'],$order);
		$order  = $order[1];
		$tempcount = isset($_REQUEST['AddField'])?($field_count-$_POST['AddFieldNo']):($field_count);
		while($j < $tempcount)
		{
				$new_f = $order[$j]-1;
				if ( $j <> $new_f )
						$cformsSettings['form'.$no]['cforms'.$no.'_count_field_'.($j+1)] = $all_fields[$new_f];
		$j++;
		}

	} ### if order changed


	### new field added (will actually be added below!)
	if( isset($_REQUEST['AddField']) && isset($_REQUEST['field_count_submit']) ){

	        $field_count = $_POST['field_count_submit'] + $_POST['AddFieldNo'];
	        $cformsSettings['form'.$no]['cforms'.$no.'_count_fields'] = $field_count;

	        $_POST['AddFieldPos'] = ($_POST['AddFieldPos']=="0")?1:$_POST['AddFieldPos']; ###safety

	        ### need to insert empty fields in between?
	        if( $_POST['AddFieldPos']<>'' && $_POST['AddFieldPos']<$_POST['field_count_submit'] ){
	            for($i = $_POST['field_count_submit']; $i >= $_POST['AddFieldPos']; $i--)
	                $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . (int)($i+$_POST['AddFieldNo'])] = $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . (int)($i)];

	            for($i = $_POST['AddFieldPos']; $i < ($_POST['AddFieldPos']+$_POST['AddFieldNo']); $i++)
	                $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . (int)($i)] = '';
	        }

	}

    update_option('cforms_settings',$cformsSettings);
	echo '<div id="message" class="updated fade"><p>'.__('Form settings updated.', 'cforms').'</p>'.$usermsg.'</div>';
?>