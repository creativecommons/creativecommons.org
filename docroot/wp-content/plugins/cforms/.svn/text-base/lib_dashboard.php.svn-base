<?php
### Show entries on dashboard for WP2.7+
function cforms_dashboard_27_setup() {
	wp_add_dashboard_widget( 'cforms_dashboard', __( 'Recent cforms entries','cforms' ), 'cforms_dashboard' );
}


### Show entries on dashboard
function cforms_dashboard() {
	global $wpdb, $plugindir, $wp_db_version, $cformsSettings;

	if (!current_user_can('track_cforms')) return;

	$WHERE='';
	for($i=0; $i<$cformsSettings['global']['cforms_formcount']; $i++){
		$no = ($i==0)?'':($i+1);
		if ( $cformsSettings['form'.$no]['cforms'.$no.'_dashboard'] == '1' )
			$WHERE .= "'$no',";
	}

	if ( $WHERE <> '')
		$WHERE = "WHERE form_id in (".substr($WHERE,0,-1).")";
	else
		return;

	$entries = $wpdb->get_results("SELECT * FROM {$wpdb->cformssubmissions} $WHERE ORDER BY sub_date DESC LIMIT 0,5");

	if ( $wp_db_version < 6846 ){
		$content = "<h3>" . __('Recent cforms entries','cforms') . " <a href='admin.php?page=".$plugindir."/cforms-database.php'>&raquo;</a> </h3>";
		$content.= "<ul style='font-size:0.8em'>";
	}
	else {
		$content = "<p class=\"youhave\">" . __('Recent cforms entries','cforms') . " <a href='admin.php?page=".$plugindir."/cforms-database.php'>&raquo;</a> </p>";
		$content.= "<ul>";
	}

	if( count($entries)>0 ){
		foreach($entries as $entry)
				$content.= '<li><img style="vertical-align:middle;" alt="" src="'.$cformsSettings['global']['cforms_root'].'/images/cformsicon.gif">'.$cformsSettings['form'.$entry->form_id]['cforms'.$entry->form_id.'_fname']." [<a href='admin.php?page=".$plugindir."/cforms-database.php&d-id=$entry->id#entry$entry->id'>$entry->email</a>] @ $entry->sub_date</li>";
	}
	else
		$content.= '<li>'.__('No entries yet','cforms').'</li>';

	$content.= '</ul>';

	echo $content;
}
?>