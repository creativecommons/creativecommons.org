<?php

	$noDISP = '1'; $no='';
	if( $_REQUEST['no']<>'1' )
		$noDISP = $no = $_REQUEST['no'];

	for ( $i=(int)$noDISP; $i < $cformsSettings['global']['cforms_formcount']; $i++) {  // move all forms "to the left"

		$n = ($i==1)?'':$i;
		unset( $cformsSettings['form'.$n] );

		foreach(array_keys($cformsSettings['form'.($i+1)]) as $key){
            $newkey = ( strpos($key,'form2_')!==false )?str_replace('2_','_',$key):str_replace(($i+1).'_',$i.'_',$key);
			$cformsSettings['form'.$n][$newkey] = $cformsSettings['form'.($i+1)][$key];
		}

	}

    unset( $cformsSettings['form'.$cformsSettings['global']['cforms_formcount']] );

	$FORMCOUNT=$FORMCOUNT-1;

	if ( $FORMCOUNT>1 ) {
		if( isset($_REQUEST['no']) && (int)$_REQUEST['no'] > $FORMCOUNT ) // otherwise stick with the current form
			$no = $noDISP = $FORMCOUNT;
	} else {
		$noDISP = '1'; $no='';
	}
	$cformsSettings['global']['cforms_formcount'] = (string)($FORMCOUNT);

	update_option('cforms_settings',$cformsSettings);

	echo '<div id="message" class="updated fade"><p>'. __('Form deleted', 'cforms').'.</p></div>';
?>