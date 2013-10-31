<?php
	$noDISP='1'; $no='';
	if( isset($_REQUEST['no']) ) {
		if( $_REQUEST['no']<>'1' )
			$noDISP = $no = $_REQUEST['no'];
	}

	$FORMCOUNT++;
	$cformsSettings['global']['cforms_formcount'] =(string)($FORMCOUNT);

	### new settings container
    foreach( array_keys($cformsSettings['form'.$no]) as $k ){
		$tmp = preg_match('/cforms\d*_(.*)/',$k, $kk);
        if( strpos($k,'_fname')!==false )
			$cformsSettings['form'.$FORMCOUNT]['cforms'.$FORMCOUNT.'_'.$kk[1]] = $cformsSettings['form'.$no][$k].' ('.__('copy of form #', 'cforms').($no==''?'1':$no).')';
		else
			$cformsSettings['form'.$FORMCOUNT]['cforms'.$FORMCOUNT.'_'.$kk[1]] = $cformsSettings['form'.$no][$k];
	}

    echo '<div id="message" class="updated fade"><p>'.__('The form has been duplicated, you\'re now working on the copy.', 'cforms').'</p></div>';

	update_option('cforms_settings',$cformsSettings);

	//set $no afterwards: need it to duplicate fields
	$no = $noDISP = $FORMCOUNT;

?>