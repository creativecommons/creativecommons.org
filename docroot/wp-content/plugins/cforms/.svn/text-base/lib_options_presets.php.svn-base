<?php

	$dirSep = strpos(dirname(__FILE__), '\\') !==false ? '\\' : '/';
	$file = dirname(__FILE__) . $dirSep . 'formpresets'. $dirSep . $_REQUEST['formpresets'];

    if( is_file($file) && filesize($file) > 0)
        $fields = file($file);
    else {
        echo '<div id="message" class="updated fade"><p><strong>'.__('Sorry, this form preset can\'t be loaded. I Can\'t find file ', 'cforms').'<br />'.$file.'</strong></p></div>';
        return;
    }

	$i = 1;
	$taf = false;
	foreach( $fields as $field ){
		if ( strpos($field,'~~~')===false ) continue;

		$data = explode('~~~',$field);
		if( $data[0]=='ff' ){
			$cformsSettings['form'.$no]["cforms{$no}_count_field_{$i}"] = str_replace(array("\n","\r"),array('',''),$data[1]);
			$i++;
		}
		else if( $data[0]=='mx' ){
			$cformsSettings['form'.$no]["cforms{$no}_maxentries"] = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='su' ){
			$cformsSettings['form'.$no]["cforms{$no}_submit_text"] = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='lt' ){
			$cformsSettings['form'.$no]["cforms{$no}_limittxt"] = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='hd' ){
			$cformsSettings['form'.$no]["cforms{$no}_hide"] = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='ri' ){
			$cformsSettings['form'.$no]["cforms{$no}_required"] = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='re' ){
			$cformsSettings['form'.$no]["cforms{$no}_emailrequired"] = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='tf' ){
			$cformsSettings['form'.$no]["cforms{$no}_tellafriend"] =  str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='tt' ){
			$cformsSettings['form'.$no]["cforms{$no}_cmsg"] =  str_replace('|nl|',"\r\n",$data[1]) ;
			$cformsSettings['form'.$no]["cforms{$no}_cmsg_html"] =  str_replace('|nl|',"<br />\r\n",$data[1]) ;
			$cformsSettings['form'.$no]["cforms{$no}_confirm"] =  '1';
			$taf = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='ts' ){
			$cformsSettings['form'.$no]["cforms{$no}_csubject"] = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='cs' ){
			$cformsSettings['global']['cforms_css'] = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
		else if( $data[0]=='dp' ){
			$cformsSettings['global']['cforms_datepicker'] = str_replace(array("\n","\r"),array('',''),$data[1]);
		}
	}

	$max = $cformsSettings['form'.$no]["cforms{$no}_count_fields"];
	for ( $j=$i; $j<=$max; $j++)
		$cformsSettings['form'.$no]["cforms{$no}_count_field_{$j}"] = '';

	$cformsSettings['form'.$no]["cforms{$no}_count_fields"] = ($i-1);

	?>
	<div id="message" class="updated fade"><p><strong>
		<?php
		_e('Your form has been populated with the preset input fields.', 'cforms');
		if( $taf==2 ){
			echo '<br />'.sprintf(__('Please note, that in order to make this form work, the <strong>%s</strong> has been turned on, too!','cforms'),__('WP comment feature','cforms'));
			echo '<br />'.__('Check with the HELP page on how to <u>properly</u> use this cforms feature and check all your settings below!','cforms');
		} else if( $taf==11 ){
			echo '<br />'.sprintf(__('Please note, that in order to make this form work, the <strong>%s</strong> has been turned on, too!','cforms'),__('TAF feature','cforms'));
			echo '<br />'.__('Check with the HELP page on how to <u>properly</u> use this cforms feature and check all your settings below!','cforms');
		}
		?>
	</strong></p></div>
	<?php

?>