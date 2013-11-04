<?php

### supporting WP2.6 wp-load & custom wp-content / plugin dir
if ( file_exists('../../abspath.php') )
	include_once('../../abspath.php');
else
	$abspath='../../../../../';

if ( file_exists( $abspath . 'wp-load.php') )
	require_once( $abspath . 'wp-load.php' );
else
	require_once( $abspath . 'wp-config.php' );

if( !current_user_can('track_cforms') )
	wp_die("access restricted.");

### mini firewall

global $wpdb;

$wpdb->cformssubmissions	= $wpdb->prefix . 'cformssubmissions';
$wpdb->cformsdata       	= $wpdb->prefix . 'cformsdata';

### new global settings container, will eventually be the only one!
$cformsSettings = get_option('cforms_settings');

### get form names
for ($i=1; $i <= $cformsSettings['global']['cforms_formcount']; $i++){
	$n = ( $i==1 )?'':$i;
	$fnames[$i]=stripslashes($cformsSettings['form'.$n]['cforms'.$n.'_fname']);
}

$format = $_GET['format'];
$sub_ids = $_GET['ids'];
$sortBy = $_GET['sortBy'];
$sortOrder = $_GET['sortOrder'];
$charset = $_GET['enc'];

$qtype = $_GET['qtype'];
$query = $_GET['query'];

$tempfile = dirname(__FILE__)."/data.tmp";

### get form id from name
$query = str_replace('*','',$query);
$form_ids = false;
if ( $qtype == 'form_id' && $query <> '' ){

	$forms = $cformsSettings['global']['cforms_formcount'];

	for ($i=0;$i<$forms;$i++) {
		$no = ($i==0)?'':($i+1);

		if ( preg_match( '/'.$query.'/i', $cformsSettings['form'.$no]['cforms'.$no.'_fname'] ) ){
        	$form_ids = $form_ids . "'$no',";
		}
	}
	$querystr = ( !$form_ids )?'$%&/':' form_id IN ('.substr($form_ids,0,-1).')';
}else{
	$querystr = '%'.$query.'%';
}


if ( $form_ids )
	$where = "AND $querystr";
elseif ( $query<>'' )
	$where = "AND $qtype LIKE '$querystr'";
else
	$where = '';


if ( !$sortBy || $sortBy=='undefined' )
	$sortBy = 'id';
if ( !$sortOrder || $sortOrder=='undefined' )
	$sortOrder = 'desc';

if ($sub_ids<>'') {

	$in_list = ($sub_ids<>'all')?'AND id in ('.substr($sub_ids,0,-1).')':'';

	$count = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->cformssubmissions} WHERE TRUE $where $in_list");

    if( !is_writable($tempfile) ){
		$err = sprintf( __('File (data.tmp) in %s not writable! %sPlease adjust its file permissions/ownership!','cforms'),"\r\n\r\n --->  <code>".dirname(__FILE__)."\r\n\r\n","\r\n\r\n");

	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Content-Type: application/force-download");
	    header("Content-Type: text/download");
	    header("Content-Type: text/txt");
	    header("Content-Disposition: attachment; filename=\"error.txt\"");
	    header("Content-Transfer-Encoding: binary");
	    header("Content-Length: " .(string)(strlen($err)) );
		echo $err;
		die();
    }

    $temp = fopen($tempfile, "w");

    ### UTF8 header
    if ( $charset=='utf-8' )
        fwrite($temp, pack("CCC",0xef,0xbb,0xbf));

	switch ( $format ){
		case 'xml': getXML(); break;
		case 'csv': getCSVTAB('csv'); break;
		case 'tab': getCSVTAB('tab'); break;
	}

    fclose($temp);

	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Type: application/force-download");
	header("Content-Type: text/download");
	header("Content-Type: text/$format");
	header("Content-Disposition: attachment; filename=\"formdata." . $format . "\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: " .(string)(filesize($tempfile)) );
    ob_clean();
    flush();

    readfile( $tempfile );

    $temp = fopen($tempfile, "w");
    fclose($temp);

	exit();

}

function getCSVTAB($format='csv'){
	global $fnames, $wpdb, $count, $temp, $where, $in_list, $sortBy, $sortOrder, $cformsSettings, $charset;

     $results = $wpdb->get_results(
             "SELECT ip, id, sub_date, form_id, field_name,field_val FROM {$wpdb->cformsdata},{$wpdb->cformssubmissions} WHERE sub_id=id $where $in_list ORDER BY $sortBy $sortOrder, f_id ASC",
             "ARRAY_A"
     );

	/*
	mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
	@mysql_select_db(DB_NAME) or die( "Unable to select database");

 	$sql = "SELECT ip, id, sub_date, form_id, field_name,field_val FROM {$wpdb->cformsdata},{$wpdb->cformssubmissions} WHERE sub_id=id $where $in_list ORDER BY $sortBy $sortOrder, f_id ASC";
	$r = mysql_query($sql);
	*/
	
	$br="\n";
	$buffer=array();
	$body='';

    $sub_id='';
	$format = ($format=="csv")?",":"\t";
    $ipTab = ($_GET['addip']?$format:'');

	$head = ($_GET['header']=='true')?$format . $format . $ipTab:'';

    $last_n = '';

	foreach( $results as $key => $entry ) {

	### while( $entry = mysql_fetch_array($r) ){

		if ( $entry[field_name]=='page' || strpos($entry[field_name],'Fieldset')!==false )
			continue;

        $next_n = ( $entry[form_id]=='' )?'1':$entry[form_id];

		if( $sub_id<>$entry[id] ){   ### new record starts

			if ( $buffer[body]<>'' ){
                if( $_GET['header']=='true' && $buffer[last_n]<>$buffer[last2_n])
					fwrite($temp, $buffer[head] . $br . $buffer[body] . $br);
				else
					fwrite($temp, $buffer[body] . $br);
            }
            $buffer[body]   = $body;  ### save 1 line
            $buffer[head]   = $head;  ### save 1 line
            $buffer[last2_n]= $buffer[last_n];
            $buffer[last_n] = $last_n;

			$body  = __('Form','cforms').': "' . encData($fnames[$next_n]). '"'. $format .'"'. encData($entry[sub_date]) .'"' . $format . ($_GET['addip']?$entry[ip].$format:'');
			$head  = ($_GET['header']=='true')?$format . $format . $ipTab:'';
			$last_n = $next_n;

			$sub_id = $entry[id];
		}

		$url='';
        $urlTab='';
        if( $_GET['addurl'] && strpos($entry[field_name],'[*') ){

            preg_match('/.*\[\*(.*)\]$/i',$entry[field_name],$t);
            $no   = $t[1]==''?$entry[form_id]:($t[1]==1?'':$t[1]);

		    $urlTab = $format;
			$entry[field_name] = substr($entry[field_name],0,strpos($entry[field_name],'[*'));

            $t = explode( '$#$',stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_upload_dir'])) );
            $fdir = $t[0];
            $fdirURL = $t[1];

			$subID = $cformsSettings['form'.$no]['cforms'.$no.'_noid'] ? '' : $entry[id].'-';

            if ( $fdirURL=='' )
                $url = $cformsSettings['global']['cforms_root'].
                		substr( $fdir, strpos($fdir,$cformsSettings['global']['plugindir']) + strlen($cformsSettings['global']['plugindir']),  strlen($fdir) );
            else
                $url = $fdirURL;

			$url .= '/'.$subID.strip_tags($entry[field_val]) .$format;
		}

        $head .= ($_GET['header']=='true')?'"'.encData(stripslashes($entry[field_name])).'"' . $format . $urlTab:'';
		$body .= '"' . str_replace('"','""', encData(stripslashes($entry[field_val]))) . '"' . $format . $url;

	} ### foreach


   	### clean up buffer
    if ( $buffer[body]<>'' ){
        if( $_GET['header']=='true' && $buffer[last_n]<>$buffer[last2_n])
            fwrite($temp, $buffer[head] . $br . $buffer[body] . $br);
        else
            fwrite($temp, $buffer[body] . $br);
    }

    ### clean up last body
	if( $_GET['header']=='true' && $buffer[last_n]<>$next_n)
	    fwrite($temp, $head . $br . $body . $br);
	else
	    fwrite($temp, $body . $br);

/*
	mysql_free_result($r);
	mysql_close();
*/
	return;
}



function getXML(){
	global $fnames, $wpdb, $count, $temp, $where, $in_list, $sortBy, $sortOrder, $cformsSettings, $charset;

	if( $charset=='utf-8' )
		fwrite($temp, "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<entries>\n");
	else
		fwrite($temp, "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<entries>\n");

	$results = $wpdb->get_results(
	       "SELECT ip, id, sub_date, form_id, field_name,field_val FROM {$wpdb->cformsdata},{$wpdb->cformssubmissions} WHERE sub_id=id $where $in_list ORDER BY $sortBy $sortOrder, f_id ASC",
	       "ARRAY_A"
	);
	/*
	mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
	@mysql_select_db(DB_NAME) or die( "Unable to select database");

 	$sql = "SELECT ip, id, sub_date, form_id, field_name,field_val FROM {$wpdb->cformsdata},{$wpdb->cformssubmissions} WHERE sub_id=id $where $in_list ORDER BY $sortBy $sortOrder, f_id ASC";
	$r = mysql_query($sql);
	*/
	
    $sub_id ='';
    foreach( $results as $key => $entry ) {
	### while( $entry = mysql_fetch_array($r) ){

	        if ( $entry[field_name]=='page' || strpos($entry[field_name],'Fieldset')!==false )
	            continue;

	        $n = ( $entry[form_id]=='' )?'1':$entry[form_id];
	        if( $sub_id<>$entry[id] ){

	            if ( $sub_id<>'' )
	            	fwrite($temp, "</entry>\n");

	            fwrite($temp, '<entry form="'.encData( $fnames[$n]).'" date="'.encData( $entry[sub_date] ).'"'.($_GET['addip']?' ip="'.$entry[ip].'"':'').">\n");

	            $sub_id = $entry[id];
	        }
	        fwrite($temp, '<data col="'.encData( stripslashes($entry[field_name]) ).'"><![CDATA['.encData( stripslashes($entry[field_val]) ).']]></data>'."\n");

	} ### while

	/*
	mysql_free_result($r);
	mysql_close();
	*/
	
	if($sub_id<>'')
	 fwrite($temp, "</entry>\n</entries>\n");

	return;
}

function encData ( $d ){
	global $charset;
	$d = str_replace('"','&quot;',$d);
	return ( $charset=='utf-8' ) ? $d : utf8_decode($d);
}

?>