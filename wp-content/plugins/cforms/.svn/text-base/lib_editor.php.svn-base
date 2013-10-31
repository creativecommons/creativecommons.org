<?php

### adding TinyMCE button
function cforms_addbuttons() {
		global $wp_db_version, $cforms_root;

		### WordPress 2.5+
		if ( $wp_db_version >= 6846 && 'true' == get_user_option('rich_editing') ) {
				add_filter( 'mce_external_plugins', 'cforms_plugin');
				add_filter( 'mce_buttons', 'cforms_button');
		}
		### WordPress 2.1+
		elseif ( 3664 <= $wp_db_version && 'true' == get_user_option('rich_editing') ) {
				add_filter("mce_plugins", "cforms_plugin");
				add_filter('mce_buttons', 'cforms_button');
				add_action('tinymce_before_init','cforms_button_script');
		}
}



### used to insert button in wordpress 2.1x & 2.5x editor
function cforms_button($buttons) {
		array_push($buttons, "separator", "cforms");
		return $buttons;
}



### adding to TinyMCE
function cforms_plugin($plugins) {
	global $cforms_root,$wp_db_version;

	if ( $wp_db_version >= 6846 )
		$plugins['cforms'] = $cforms_root.'/js/editor_plugin25.js';
	else
		array_push($plugins, "-cforms");

	return $plugins;
}



### Load the TinyMCE plugin : editor_plugin.js (wp2.1)
function cforms_button_script() {

		global $cforms_root,$wp_db_version;
		$pluginURL = $cforms_root .'/js/';

		$fns = getAllformNames();

		echo 'var placeholder="'.__('placeholder for:','cforms').'";';
		echo 'var formnames=new Array('.$fns.');';
		echo 'tinyMCE.loadPlugin("cforms", "'.$pluginURL.'");'."\n";
		echo 'var purl="'.$pluginURL.'";'."\n";
		return;
}



### retrieve all form names
function getAllformNames() {
		global $cformsSettings;
		$fns = '';
		$forms = $cformsSettings['global']['cforms_formcount'];
		for ($i=0;$i<$forms;$i++) {
			$no = ($i==0)?'':($i+1);
			$fns .= '"'.$cformsSettings['form'.$no]['cforms'.$no.'_fname'].'",';
		}
		return substr($fns,0,-1);
}



### Load the Script for the Button(wp2.1)
function insert_cforms_script() {
		global $cforms_root, $wp_db_version, $cformsSettings;

		$options = '';
        $forms = $cformsSettings['global']['cforms_formcount'];
		for ($i=0;$i<$forms;$i++) {
			$no = ($i==0)?'':($i+1);
			$options .= '<option value=\"'.sanitize_title_with_dashes($cformsSettings['form'.$no]['cforms'.$no.'_fname']).'\">'.$cformsSettings['form'.$no]['cforms'.$no.'_fname'].'</option>';
		}

		$fns = getAllformNames();
		?>
<style>
#cformsins{
	font-size:11px;
<?php if ( $wp_db_version < 6846 ) echo "width:100%;"; ?>
	margin:2px 4px 5px 4px;
	text-align:center;
	padding:2px 0;
	border-top:2px solid #247FAB;
	border-bottom:2px solid #247FAB;
}
#cformsins form{
	background: #F0F0EE url(<?php echo $cforms_root ?>/images/cfii_code_ed.jpg) no-repeat top right;
	overflow:hidden;
	padding:2px 0;
	}
#cformsins label{
	font-variant:small-caps;
	font-size:14px;
	padding-right:10px;
	line-height:25px;
}
#cfselect {
	font-size:12px;
	width:210px;
}
#cancel,
#insert{
	font-size:11px;
	margin-left:10px;
	width:120px!important;
}
</style>
<script type="text/javascript">
var globalPURL = "<?php echo $cforms_root ?>";

var placeholder = "<?php _e('placeholder for:','cforms') ?>";
var formnames = new Array(<?php echo $fns; ?>);
var purl = globalPURL+'/js/';

function closeInsert(){
    var el = document.getElementById("quicktags");
    el.removeChild(document.getElementById("cformsins"));
}
function insertSomething(){
    buttonsnap_settext('<!--cforms name="'+document.getElementById("cfselect").value+'"-->');
    closeInsert();
}
function cforms_buttonscript() {
        if ( document.getElementById("cformsins") ) {
            return closeInsert();
        }

        function edInsertContent(myField, myValue) {
            //IE support
            if (document.selection) {
                myField.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                myField.focus();
            }
            //MOZILLA/NETSCAPE support
            else if (myField.selectionStart || myField.selectionStart == '0') {
                var startPos = myField.selectionStart;
                var endPos = myField.selectionEnd;
                myField.value = myField.value.substring(0, startPos)
                              + myValue
                              + myField.value.substring(endPos, myField.value.length);
                myField.focus();
                myField.selectionStart = startPos + myValue.length;
                myField.selectionEnd = startPos + myValue.length;
            } else {
                myField.value += myValue;
                myField.focus();
            }
        }

    var rp = document.createElement("div");
    var el = document.getElementById("quicktags");

    rp.setAttribute("id","cformsins");

    rp.innerHTML =  "<form onSubmit=\"insertSomething();\" action=\"#\"><label for=\"nodename\"><?php _e('Your forms:','cforms')?></label>"+
            "<select id=\"cfselect\" name=\"nodename\"/><?php echo $options ?></select>"+
            "<input type=\"button\" id=\"insert\" name=\"insert\" value=\"<?php _e('Insert','cforms') ?>\" onclick=\"javascript:insertSomething()\" />"+
            "<input type=\"button\" id=\"cancel\" name=\"cancel\" value=\"<?php _e('Cancel','cforms') ?>\" onclick=\"javascript:closeInsert()\" />"+
            "</form>";

    el.appendChild(rp);

}
</script>
<?php
		return;
}



### only insert buttons if enabled!
if($cformsSettings['global']['cforms_show_quicktag'] == true) {

	add_action('init', 'cforms_addbuttons');

    ### TinyMCE error fix
	if( !$cformsSettings['global']['cforms_show_quicktag_js'] ) {
		add_action('edit_page_form', 'insert_cforms_script');
		add_action('edit_form_advanced', 'insert_cforms_script');
	}else
		add_action('admin_head', 'insert_cforms_script');

}
?>