<?php


### plugin removal
if( isset($_POST['cfdeleteall']) && !function_exists("wp_get_current_user") ) {

	### supporting WP2.6 wp-load & custom wp-content / plugin dir
	if ( file_exists('abspath.php') )
		include_once('abspath.php');
	else
		$abspath = dirname(__FILE__) . '/../../../';

	if ( file_exists( $abspath . 'wp-load.php') )
		require_once( $abspath . 'wp-load.php' );
	else
		require_once( $abspath . 'wp-config.php' );

	require (ABSPATH . WPINC . '/pluggable.php');

	global $current_user,$user_ID;
	$u = get_currentuserinfo();

	if( is_user_logged_in() && in_array('administrator',$current_user->roles) ) {
		$alloptions =  $wpdb->query("DELETE FROM `$wpdb->options` WHERE option_name LIKE 'cforms%'");
		$wpdb->query("DROP TABLE IF EXISTS $wpdb->cformssubmissions");
		$wpdb->query("DROP TABLE IF EXISTS $wpdb->cformsdata");
	}

    ### deactivate cforms plugin
	$curPlugs = get_settings('active_plugins');
	array_splice($curPlugs, array_search( 'cforms', $curPlugs), 1 ); // Array-function!
	update_option('active_plugins', $curPlugs);
	header('Location: plugins.php?deactivate=true');

}


### backup/download cforms settings
$buffer='';
function download_cforms(){
	global $buffer, $wpdb, $cformsSettings;
	$br="\n";

	if( isset($_REQUEST['savecformsdata']) || isset($_REQUEST['saveallcformsdata']) ) {

		if( isset($_REQUEST['savecformsdata']) ){
	        $noDISP = '1'; $no='';
	        if( $_REQUEST['noSub']<>'1' )
	            $noDISP = $no = $_REQUEST['noSub'];

	    	$buffer .= SaveArray($cformsSettings['form'.$no]);
//	    	$buffer .= SaveArray($cformsSettings['form'.$no]).$br;
			$filename = 'form-settings.txt';
		}else{
	    	$buffer .= SaveArray($cformsSettings);
//	    	$buffer .= SaveArray($cformsSettings).$br;
			$filename = 'all-cforms-settings.txt';
		}

        ob_end_clean();
		header('Pragma: public;');
		header('Expires: 0;');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0;');
		header('Content-Type: application/force-download;');
		header('Content-Type: application/octet-stream;');
		header('Content-Type: application/download;');
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		header('Content-Transfer-Encoding: binary;');
		header('Content-Length: ' .(string)(strlen($buffer)) . ';' );
        flush();
		print $buffer;
		exit(0);
	}
}

### backup/download cforms settings :: save the array
function SaveArray($vArray){
	global $buffer;
    // Every array starts with chr(1)+"{"
    $buffer .=  "\0{";

    // Go through the given array
    reset($vArray);
    while (true)
    {
        $Current = current($vArray);
        $MyKey = addslashes(strval( key($vArray) ));
        if (is_array($Current)) {
            $buffer .= $MyKey."\0";
            SaveArray($Current);
            $buffer .= "\0";
        } else {
            $Current = addslashes($Current);
            $buffer .= "$MyKey\0$Current\0";
        }

        ++$i;

        while ( next($vArray)===false )
            if (++$i > count($vArray)) break;

        if ($i > count($vArray)) break;
    }
    $buffer .= "\0}";
}



### check user access
function check_access_priv($r='manage_cforms'){
	if( !current_user_can($r) ){
		$err = '<div class="wrap"><div id="icon-cforms-error" class="icon32"><br/></div><h2>'.__('cforms error','cforms').'</h2><div class="updated fade" id="message"><p>'.__('You do not have the proper privileges to access this page.','cforms').'</p></div></div>';
		die( $err );
    }
}



### add cforms menu
function cforms_menu() {
	global $wpdb, $submenu;

	$cformsSettings = get_option('cforms_settings');
    $p = $cformsSettings['global']['plugindir'];

	$tablesup = ($wpdb->get_var("show tables like '$wpdb->cformssubmissions'") == $wpdb->cformssubmissions)?true:false;

	$o = $p.'/cforms-options.php';

    if (function_exists('add_menu_page')) {
		add_menu_page(__('cformsII', 'cforms'), __('cformsII', 'cforms'), 'manage_cforms', $o, '', $cformsSettings['global']['cforms_root'].'/images/cformsicon.gif');
	}
	elseif (function_exists('add_management_page')) {
		add_management_page(__('cformsII', 'cforms'), __('cformsII', 'cforms'), 'manage_cforms', $o);
	}

	if (function_exists('add_submenu_page')) {
		add_submenu_page($o, __('Form Settings', 'cforms'), __('Form Settings', 'cforms'), 'manage_cforms', $o);
		add_submenu_page($o, __('Global Settings', 'cforms'), __('Global Settings', 'cforms'), 'manage_cforms', $p.'/cforms-global-settings.php');
		if ( ($tablesup || isset($_REQUEST['cforms_database'])) && !isset($_REQUEST['deletetables']) )
			add_submenu_page($o, __('Tracking', 'cforms'), __('Tracking', 'cforms'), 'track_cforms', $p.'/cforms-database.php');
		add_submenu_page($o, __('Styling', 'cforms'), __('Styling', 'cforms'), 'manage_cforms', $p.'/cforms-css.php');
		add_submenu_page($o, __('Help!', 'cforms'), __('Help!', 'cforms'), 'manage_cforms', $p.'/cforms-help.php');
	}
}



### cforms init
function cforms_init() {
	global $wpdb;

	$plugindir   = basename(dirname(__FILE__));
	$sep = strpos(dirname(__FILE__), '\\') !==false ? '\\' : '/';

	$role = get_role('administrator');
	if(!$role->has_cap('manage_cforms')) {
		$role->add_cap('manage_cforms');
	}
	if(!$role->has_cap('track_cforms')) {
		$role->add_cap('track_cforms');
	}

	### try to adjust cforms.js automatically
	$jsContent = $jsContentNew = '';
	if ( $fhandle = fopen(dirname(__FILE__).'/js/cforms.js', "r") ) {
		$jsContent = fread($fhandle, filesize(dirname(__FILE__).'/js/cforms.js'));
	    fclose($fhandle);

		$URIprefix = get_option('siteurl');
		$pathToAjax = $URIprefix . '/wp-content/plugins/cforms/lib_ajax.php';

        if ( defined('WP_CONTENT_URL') )
			$pathToAjax = $URIprefix.'/'.WP_CONTENT_URL.'/plugins/'.$plugindir. '/lib_ajax.php';

        if ( defined('WP_PLUGIN_URL') )
			$pathToAjax = $URIprefix.'/'.WP_PLUGIN_URL.'/'.$plugindir. '/lib_ajax.php';

        if ( defined('PLUGINDIR') )
			$pathToAjax = $URIprefix.'/'.PLUGINDIR.'/'.$plugindir. '/lib_ajax.php';

       	$jsContentNew = str_replace('\'/wp-content/plugins/cforms/lib_ajax.php\'',"'{$pathToAjax}'",$jsContent);
	}
	if ( $jsContentNew<>'' && $jsContentNew<>$jsContent && ($fhandle = fopen(dirname(__FILE__).$sep.'js'.$sep.'cforms.js', "w")) ) {
	    fwrite($fhandle, $jsContentNew);
	    fclose($fhandle);
	}

	### save ABSPATH for ajax routines
	if ( defined('ABSPATH') && ($fhandle = fopen(dirname(__FILE__).$sep.'abspath.php', "w")) ) {
	    fwrite($fhandle, "<?php \$abspath = '". addslashes(ABSPATH) . "'; ?>\n");
	    fclose($fhandle);
	}

}



### check for abspath.php
function abspath_check(){
	global $cformsSettings;
	if ( !file_exists( dirname(__FILE__).$cformsSettings['global']['cforms_IIS'].'abspath.php' ) ){
    	echo '<div class="updated fade"><p>'.
        	__('It appears that cforms was not able to create <strong>abspath.php</strong> in your cforms plugin folder. Please check file/folder permissions (plugins/cforms), then <strong>re-activate</strong> cforms.', 'cforms').
            '</p><p>'.
            __('If the problem persists, please create a file (using your preferred text editor) manually with the following content:', 'cforms').
            '<p><code>&lt;?php $abspath = \''.addslashes(ABSPATH).'\'; ?&gt;</code></p>'.
            '<p>'.__('Save the file as abspath.php and ftp to your cforms folder.', 'cforms').'</p></div>';
        }
}



### get WP plugin dir
function get_cf_plugindir(){
	$cr = defined('PLUGINDIR') ? get_option('siteurl') .'/'. PLUGINDIR . '/' : get_option('siteurl') . '/wp-content/plugins/';
	$cr = defined('WP_CONTENT_URL') ? WP_CONTENT_URL.'/plugins/' : $cr;
	$cr = defined('WP_PLUGIN_URL') ? WP_PLUGIN_URL .'/' : $cr;
	return $cr;
}



### cforms JS scripts
function cforms_scripts() {
	global $wp_scripts, $localversion;

	### get options
	$cformsSettings = get_option('cforms_settings');
	$r=$cformsSettings['global']['cforms_root'];

	### global settings
	$request_uri = get_request_uri();

    if ( version_compare(strval($wp_scripts->registered['jquery']->ver), strval("1.4.2") ) === -1 ){
		wp_deregister_script('jquery');
	    wp_register_script('jquery',$r.'/js/jquery.js',false,'1.4.2');
    	wp_enqueue_script('jquery');
    }

   	echo '<link rel="stylesheet" type="text/css" href="' . $r . '/cforms-admin.css" />' . "\n";

	if ( strpos($request_uri,'cforms-options')!==false ){
		echo '<link rel="stylesheet" type="text/css" href="' . $r . '/styling/calendar.css" />' . "\n";
	    wp_register_script('cforms_admin_cal',$r.'/js/cformsadmincal.js',false,$localversion);
	    wp_enqueue_script('cforms_admin_cal');
	}

    wp_deregister_script('prototype');

    wp_register_script('cforms_interface',$r.'/js/interface.js',false,$localversion);
    wp_register_script('cforms_admin',$r.'/js/cformsadmin.js',false,$localversion);

    wp_enqueue_script('cforms_interface');
    wp_enqueue_script('cforms_admin');
}



### some css for arranging the table fields in wp-admin
function cforms_options_page_style() {

	global $localversion;
	$cformsSettings = get_option('cforms_settings');
	$nav = $cformsSettings['global']['cforms_dp_nav'];

	echo "\n<!-- Start Of Script Generated By cformsII v".$localversion." [Oliver Seidel | www.deliciousdays.com] -->\n";
    echo '<script type="text/javascript">'."\n/* <![CDATA[ */\n".
         'Date.dayNames = ['.stripslashes($cformsSettings['global']['cforms_dp_days']).'];'."\n".
         'Date.abbrDayNames = ['.stripslashes($cformsSettings['global']['cforms_dp_days']).'];'."\n".
         'Date.monthNames = ['.stripslashes($cformsSettings['global']['cforms_dp_months']).'];'."\n".
         'Date.abbrMonthNames = ['.stripslashes($cformsSettings['global']['cforms_dp_months']).'];'."\n".
         'Date.firstDayOfWeek = 0;'."\n".
         'Date.fullYearStart = "20";'."\n".
         'jQuery.dpText = { TEXT_PREV_YEAR:"'.stripslashes($nav[0]).'",'. ### Previous year
         'TEXT_PREV_MONTH:"'.stripslashes($nav[1]).'",'.
         'TEXT_NEXT_YEAR:"'.stripslashes($nav[2]).'",'.
         'TEXT_NEXT_MONTH:"'.stripslashes($nav[3]).'",'.
         'TEXT_CLOSE:"'.stripslashes($nav[4]).'",'.
         'TEXT_CHOOSE_DATE:"'.stripslashes($nav[5]).'",'.
         'ROOT:"'.$cformsSettings['global']['cforms_root'].'"};'."\n\n";
?>
jQuery(function() {

if( jQuery(".cf_timebutt1").length>0 && jQuery(".cf_timebutt2").length>0 ){
    jQuery(".cf_timebutt1").clockpick({military:true, layout:'horizontal', starthour : 0,endhour : 23,showminutes : true, valuefield : 'cforms_starttime' });
    jQuery(".cf_timebutt2").clockpick({military:true, layout:'horizontal', starthour : 0,endhour : 23,showminutes : true, valuefield : 'cforms_endtime' });
}

if( jQuery(".cf_date").length>0 ){

    Date.format = "dd/mm/yyyy";
    jQuery(".cf_date").datePicker( );

    jQuery('#cforms_startdate').bind(
        'dpClosed',
        function(e, selectedDates)
        {
            var d = selectedDates[0];
            if (d) {
                d = new Date(d);
                jQuery('#cforms_enddate').dpSetStartDate(d.asString());
            }
        }
    );
    jQuery('#cforms_enddate').bind(
        'dpClosed',
        function(e, selectedDates)
        {
            var d = selectedDates[0];
            if (d) {
                d = new Date(d);
                jQuery('#cforms_startdate').dpSetEndDate(d.asString());
            }
        }
    );

}
});
<?php
	echo  "/* ]]> */\n".'</script>'."\n";
	echo '<!-- End Of Script Generated By cformsII -->'."\n\n";
}



### footer
function cforms_footer() {
	global $localversion;
?>	<p style="padding-top:50px; font-size:11px; text-align:center;">
		<em>
			<?php echo sprintf(__('For more information and support, visit the <strong>cforms</strong> %s support forum %s. ', 'cforms'),'<a href="http://www.deliciousdays.com/cforms-forum/" title="cforms support forum">','</a>') ?>
			<?php _e('Translation provided by Oliver Seidel, for updates <a href="http://deliciousdays.com/cforms-plugin">check here.</a>', 'cforms') ?>
		</em>
	</p>
	<p align="center">Version v<?php echo $localversion; ?></p>
<?php
}



### plugin uninstalled?
function check_erased() {
	global $cformsSettings;
    if ( $cformsSettings['global']['cforms_formcount'] == '' ){
		?>
		<div class="wrap">
		<div id="icon-cforms-global" class="icon32"><br/></div><h2><?php _e('All cforms data has been erased!', 'cforms') ?></h2>
	    <p class="ex" style="padding:5px 35px 10px 41px;"><?php _e('Please go to your <strong>Plugins</strong> tab and either disable the plugin, or toggle its status (disable/enable) to revive cforms!', 'cforms') ?></p>
	    <p class="ex" style="padding:5px 35px 10px 41px;"><?php _e('In case disabling/enabling doesn\'t seem to properly set the plugin defaults, try login out and back in and <strong>don\'t select the checkbox for activation</strong> on the plugin page.', 'cforms') ?></p>
	    </div>
		<?php
	    return true;
	}
	return false;
}


### get_magic_quotes_gpc() workaround
if ( !function_exists(get_magic_quotes_gpc) ) {
	function get_magic_quotes_gpc(){
		return false;
	}
}
function magic($v){
  global $wp_version;
  $vercomp = (version_compare(strval($wp_version), strval('2.9'), '>=') == 1);
	return ( get_magic_quotes_gpc() || $vercomp ) ? $v : addslashes($v);
}
?>