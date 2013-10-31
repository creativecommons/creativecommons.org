<?php

global $wp_db_version;

### supporting WP2.6 wp-load & custom wp-content / plugin dir
if ( file_exists('../abspath.php') )
	require_once('../abspath.php');
else
	$abspath='../../../../';

require_once($abspath.'wp-blog-header.php');

### new global settings container, will eventually be the only one!
$cformsSettings = get_option('cforms_settings');

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>cforms</title>
	<link type="text/css" rel="stylesheet" href="<?php echo $cformsSettings['global']['cforms_root']; ?>/js/insertdialog<?php if ($wp_db_version>=6846) echo '25'; ?>.css"></link>
	<script language="javascript" type="text/javascript" src="<?php echo $cformsSettings['global']['tinyURI']; ?>/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $cformsSettings['global']['tinyURI']; ?>/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $cformsSettings['global']['tinyURI']; ?>/utils/form_utils.js"></script>

	<script language="javascript" type="text/javascript">
	<!--

	tinyMCEPopup.onInit.add( function(){window.setTimeout(function(){document.getElementById('nodename').focus();},500);} );

	<?php
	$fns = ''; $options = '';
	$forms = $cformsSettings['global']['cforms_formcount'];
	for ($i=0;$i<$forms;$i++) {
		$no = ($i==0)?'':($i+1);
		$options .= '<option value="'.($i+1).'">'.stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_fname']).'</option>';
		$fns .= '"'.$cformsSettings['form'.$no]['cforms'.$no.'_fname'].'",';
	}
	$fns = substr($fns,0,-1);
	echo 'var formnames=new Array('.$fns.');';
	?>

	function init() {
		mcTabs.displayTab('tab', 'panel');
	}

	function insertSomething() {
		no  = document.forms[0].nodename.value;
		html = '<span title="'+formnames[no-1]+'" class="mce_plugin_cforms_img">'+formnames[no-1]+'</span>';

		tinyMCEPopup.execCommand("mceBeginUndoLevel");
		tinyMCEPopup.execCommand('mceInsertContent', false, html);
	 	tinyMCEPopup.execCommand("mceEndUndoLevel");
	   	tinyMCEPopup.close();
	}
	//-->
	</script>
	<base target="_self" />
</head>
<body id="cforms" onLoad="tinyMCEPopup.executeOnLoad('init();');" style="display: none">
	<form onSubmit="insertSomething();" action="#">
	<div class="tabs">
		<ul>
			<li id="tab"><span><a href="javascript:mcTabs.displayTab('tab','panel');"><?php  _e('Pick a form','cforms'); ?></a></span></li>
		</ul>
	</div>
	<div class="panel_wrapper">
		<div id="panel" class="panel current">
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="cflabel"><label for="nodename"><?php  _e('Your forms:','cforms'); ?></label></td>
					<td class="cfinput"><select id="nodename" name="nodename"/><?php  echo $options; ?></select>
				</tr>
			</table>
		</div>

	</div>
	<div class="mceActionPanel">
		<div style="float: left">
				<input type="button" id="insert" name="insert" value="<?php  _e('Insert','cforms'); ?>" onClick="insertSomething();" />
		</div>
		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="<?php  _e('Cancel','cforms'); ?>" onClick="tinyMCEPopup.close();" />
		</div>
	</div>
</form>
</body>
</html>