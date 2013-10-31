<?php
function cforms_addbuttons() {

		global $wp_db_version;
		global $cforms_root;

		// Check for WordPress 2.1+ and activated RTE
		if ( 3664 <= $wp_db_version && 'true' == get_user_option('rich_editing') ) {
				// add the button for wp21 in a new way
				add_filter("mce_plugins", "cforms_button_plugin", 0);
				add_filter('mce_buttons', 'cforms_button', 0);
				add_action('tinymce_before_init','cforms_button_script');
		}
		else {
				add_action('marker_css','cforms_css',0);
				// Do it in the old way with buttonsnap
				$button_image_url = $cforms_root . '/images/button.gif';
				buttonsnap_separator();

				$forms = get_option('cforms_formcount');
				for ($i=1;$i<=$forms;$i++)
						buttonsnap_register_marker('cforms'.(($i==1)?'':$i),'cformscss');

				buttonsnap_jsbutton($button_image_url, __('cforms', 'cforms'), 'cforms_buttonscript();');
		}

}

// used to insert button in wordpress 2.1x editor
function cforms_css() {
	global $cforms_root;
	echo ".cformscss{ background: #F0F0EE url(".$cforms_root."/images/cfii_code_ed.jpg) no-repeat top right; overflow:hidden; padding:0; width:400px; }";
}
// used to insert button in wordpress 2.1x editor
function cforms_button($buttons) {
		array_push($buttons, "separator", "cforms");
		return $buttons;
}

// Tell TinyMCE that there is a plugin (wp2.1)
function cforms_button_plugin($plugins) {
		array_push($plugins, "-cforms");
		return $plugins;
}

// Load the TinyMCE plugin : editor_plugin.js (wp2.1)
function cforms_button_script() {
		global $cforms_root;
		$pluginURL = $cforms_root .'/js/';

		$fns = '';
		$forms = get_option('cforms_formcount');	
		for ($i=0;$i<$forms;$i++) {
			$no = ($i==0)?'':($i+1);
			$fns .= '"'.get_option('cforms'.$no.'_fname').'",';
		}
		$fns = substr($fns,0,-1);

		echo 'var placeholder="'.__('placeholder for:','cforms').'";';
		echo 'var formnames=new Array('.$fns.');';
		echo 'var purl="'.$pluginURL.'"; tinyMCE.loadPlugin("cforms", "'.$pluginURL.'");'."\n";
		return;
}

// Load the Script for the Button(wp2.1)
function insert_cforms_script() {
		global $cforms_root;
		
		$options = '';
		$forms = get_option('cforms_formcount');	
		for ($i=0;$i<$forms;$i++) {
			$no = ($i==0)?'':($i+1);
			$options .= '<option value=\"'.$no.'\">'.get_option('cforms'.$no.'_fname').'</option>';
		}
		?>
<style>
#cformsins{
	font-size:11px;
	width:100%;
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
			          
	function closeInsert(){
		var el = document.getElementById("quicktags");
		el.removeChild(document.getElementById("cformsins"));
	}							
	function insertSomething(){
		buttonsnap_settext('<!--cforms'+document.getElementById("cfselect").value+'-->');
		//edInsertContent(edCanvas, '<!--cforms'+document.getElementById("cfselect").value+'-->');
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
	
		rp.innerHTML =	"<form onSubmit=\"insertSomething();\" action=\"#\"><label for=\"nodename\"><?php _e('Your forms:','cforms')?></label>"+
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
//
// only insert buttons if enabled!
//
if(get_option('cforms_show_quicktag') == true) {
		add_action('init', 'cforms_addbuttons');
		add_action('edit_page_form', 'insert_cforms_script');
		add_action('edit_form_advanced', 'insert_cforms_script');
}
?>
