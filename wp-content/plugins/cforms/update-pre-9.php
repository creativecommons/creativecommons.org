<?php
###
### run update process
###
?>
<div class="wrap" id="top">
		<div id="icon-cforms-global" class="icon32"><br/></div><h2><?php _e('Migrating cforms settings from pre-v9.0 versions', 'cforms') ?></h2>

<?php
	if( isset($_POST['migrate']) ){

		$cformsSettings['global']['update'] = false;

		newSettings(true);
		oldSettings(true);

		update_option('cforms_settings',$cformsSettings);

		$alloptions =  $wpdb->query("DELETE FROM `$wpdb->options` WHERE option_name LIKE 'cforms%' AND option_name <> 'cforms_settings'");

		echo '<p>'.sprintf(__('Phew. All settings successfully transferred. Please <a href="%s">continue here</a>.', 'cforms'),'?page='.$cformsSettings['global']['plugindir'].'/cforms-options.php#datepicker').'</p>';
	    die();
    }
?>


<p class="ex">
	<?php _e('All settings found are listed below. If the findings make sense, please scroll down and continue.', 'cforms') ?><br/>
    <?php _e('Now before you do that, be aware that this might erase all of your cforms settings, <strong>so please make a DB backup</strong> to be on the safe side!', 'cforms') ?>
</p>

<?php

### show all movable root options
$temp = newSettings(false);
if( $temp <> '' )
	echo '<h3>'.__('Some new settings found:', 'cforms').'</h3><div style="font:normal 10px monospace";>'.$temp.'</div>';

###check options now
echo '<h3>'.__('Old wp_options() settings, to be moved to new settings container:', 'cforms').'</h3>';
echo '<div style="font:normal 10px monospace";>'.oldSettings(false).'</div>';

###check options now
echo '<h3>'.__('Start migration', 'cforms').'</h3>';
?>
<p class="ex">
	<?php _e('Again, make sure you have a backup of your WP database, before continuing!', 'cforms') ?><br/>
</p>
<form action="#" method="post">
	<input id="migrate" name="migrate" type="submit" title="<?php _e('Migrate now!', 'cforms') ?>" style="width:200px" value="<?php _e('Migrate now!', 'cforms') ?>">
</form>

<?php
cforms_footer();
echo '</div>';
die();

##
## check more recent settings
##
function newSettings( $doit ){

	global $cformsSettings;
	$p = '';

	$keys = array_keys( $cformsSettings );
	asort($keys);
	foreach( $keys as $k ){
	    $s = str_repeat('&nbsp;',30-strlen($k));
	    if ( strpos($k,'rss')!==false ){
	        if ( strpos($k,'all')!==false )
            	if ( $doit ){
					$cformsSettings['global'][$k]=$cformsSettings[$k];
                    unset( $cformsSettings[$k] );
                } else
	            	$p .= "[$k] $s ---> [global][$k]<br/>";
	        else{
                preg_match('/.*cforms(.?)_.*/',$k,$r);
            	if ( $doit ){
					$cformsSettings["form{$r[1]}"][$k]=$cformsSettings[$k];
                    unset( $cformsSettings[$k] );
                } else
	                $p .= "[$k] $s ---> [form{$r[1]}][$k]<br/>";
	        }
	    }elseif ( strpos($k,'show_quick')!==false || strpos($k,'cforms_captcha_def')!==false ){
            if ( $doit ){
				$cformsSettings['global'][$k] = $cformsSettings[$k];
                unset( $cformsSettings[$k] );
            } else
		        $p .= "[$k] $s ---> [global][$k]<br/>";
	    }
	}
	return $p;
}

##
## check more recent settings
##
function oldSettings( $doit ){

	global $cformsSettings, $wpdb;
    $p = '';

	$alloptions	 = $wpdb->get_results("SELECT * FROM `$wpdb->options` WHERE option_name LIKE 'cforms%' ORDER BY option_name");
	$firstForm = array('cforms_action','cforms_action_page','cforms_ajax','cforms_bcc','cforms_cmsg','cforms_cmsg_html','cforms_confirm',
	                'cforms_count_fields','cforms_csubject','cforms_customnames','cforms_dashboard','cforms_email','cforms_emailrequired','cforms_failure','cforms_fname',
	                'cforms_formdata','cforms_fromemail','cforms_header','cforms_header_html','cforms_limittxt','cforms_maxentries','cforms_noattachments','cforms_popup',
	                'cforms_redirect','cforms_redirect_page','cforms_required','cforms_showpos','cforms_space','cforms_subject','cforms_submit_text','cforms_success',
	                'cforms_tellafriend','cforms_tracking','cforms_upload_dir','cforms_upload_ext','cforms_upload_size','cforms_working');

	$collect = array();

	foreach( $alloptions as $o ){
	    $k = $o->option_name;
	    $s = str_repeat('&nbsp;',30-strlen($k));

	    if( $k=='cforms_settings' )
	        continue;
	    else if ( strpos($k,'cforms_')!==false && strpos($k,'cforms_count_f')===false && !in_array($k,$firstForm) && !$cformsSettings['global'][$k] )
	        $collect['global'][$k] = get_option($k);
	    else if ( preg_match('/.*cforms(.{0,2})_.*/',$k,$r) )
	        $collect['form'.$r[1]][$k] = get_option($k);
	}

	asort($collect);

	foreach( array_keys($collect) as $k){
	    $s = str_repeat('&nbsp;',10-strlen($k));
		if ( !$doit )
    	    $p .= "[$k]$s<br/>";
	    foreach( array_keys($collect[$k]) as $v){
	        if ( $doit )
				$cformsSettings[$k][$v]=$collect[$k][$v];
	        else
		        $p .= str_repeat('&nbsp;',10).$v.'<br/>';
	    }
	}
	return $p;
}

?>