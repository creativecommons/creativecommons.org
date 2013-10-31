<?php
ob_start();
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

global $wpdb;

$wpdb->cformssubmissions	= $wpdb->prefix . 'cformssubmissions';
$wpdb->cformsdata       	= $wpdb->prefix . 'cformsdata';

### new global settings container, will eventually be the only one!
$cformsSettings = get_option('cforms_settings');

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

$qtype = $_POST['qtype'];
$query = $_POST['query'];

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
	$where = "WHERE $querystr";
elseif ( $query<>'' )
	$where = "WHERE $qtype LIKE '$querystr'";
else
	$where = '';

if (!$sortname)
	$sortname = 'id';
if (!$sortorder) $sortorder = 'desc';
	$sort = "ORDER BY $sortname $sortorder";
if (!$page)
	$page = 1;
if (!$rp)
	$rp = 10;

$start = (($page-1) * $rp);
$limit = "LIMIT $start, $rp";

for ($i=1; $i <= $cformsSettings['global']['cforms_formcount']; $i++){
	$n = ( $i==1 )?'':$i;
	$fnames[$i]=stripslashes($cformsSettings['form'.$n]['cforms'.$n.'_fname']);
}


### total count
if ( $qtype=='id' )
	$total = 1;
else{
	$sql = "SELECT count(id) FROM {$wpdb->cformssubmissions} $where";
	$total = $wpdb->get_var($sql);
}

### get results
$sql="SELECT * FROM {$wpdb->cformssubmissions} $where $sort $limit";
$result = $wpdb->get_results($sql);


header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: text/xml");

$xml = "<?xml version=\"1.0\"?>\n";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$total</total>";

foreach ($result as $entry) {
	$n = ( $entry->form_id=='' )?'1':$entry->form_id;
	$xml .= "<row id='".$entry->id."'>";
	$xml .= "<cell><![CDATA[".$entry->id."]]></cell>";
	$xml .= "<cell><![CDATA[".( $fnames[$n] )."]]></cell>";
	$xml .= "<cell><![CDATA[".( $entry->email )."]]></cell>";
	$xml .= "<cell><![CDATA[".( $entry->sub_date )."]]></cell>";
	$xml .= "<cell><![CDATA[".( $entry->ip )."]]></cell>";
	$xml .= "</row>";
}

$xml .= "</rows>";
ob_end_clean();
echo $xml;
?>