<?php
/*******************************************************************************
BUTTONSNAP CLASS LIBRARY By Owen Winkler
http://asymptomatic.net
WordPress Downloads are at http://redalt.com/downloads
Version: 1.3.1
*******************************************************************************/

if (!class_exists('buttonsnap')) :
class buttonsnap
{
	var $script_output = false;
	var $buttons = array('post'=>array(),'page'=>array(),'any'=>array());
	var $markers = array();

	function sink_hooks()
	{
		add_action('edit_form_advanced', array(&$this, 'edit_form_advanced'));
		add_action('edit_page_form', array(&$this, 'edit_page_form'));
		add_filter('mce_plugins', array(&$this, 'mce_plugins'));
	}

	function go_solo()
	{
		$dispatch = isset($_POST['buttonsnapdispatch']) ? $_POST['buttonsnapdispatch'] : @$_GET['buttonsnapdispatch'];
		if($dispatch != '') {
			auth_redirect();
			$selection = isset($_POST['selection']) ? $_POST['selection'] : @$_GET['selection'];
			$selection = apply_filters($dispatch, $selection);
			die($selection);
		}
		if(isset($_GET['docss'])) {
			auth_redirect();
			do_action('marker_css');
			die();
		}
	}

	function edit_form_advanced()
	{
		if (!$this->script_output) {
			$this->output_script('post');
			$this->script_output = true;
		}
	}

	function edit_page_form()
	{
		if (!$this->script_output) {
			$this->output_script('page');
			$this->script_output = true;
		}
	}

	function mce_plugins($plugins)
	{
		if (count($this->markers) > 0) {

			echo "var buttonsnap_markers = new Array(\n";
			$comma = '';
			foreach ($this->markers as $k => $v) {
				echo "{$comma}\"{$k}\"";
				$comma = "\n,";
			}
			echo "\n);\n";
			echo "var buttonsnap_classes = new Array(\n";
			$comma = '';
			foreach ($this->markers as $k => $v) {
				echo "{$comma}\"{$v}\"";
				$comma = "\n,";
			}
			echo "\n);\n";

?>

function TinyMCE_buttonsnap_initInstance(inst) {
	tinyMCE.importCSS(inst.getDoc(), "<?php echo $this->plugin_uri(); ?>?docss=true");
}

function TinyMCE_buttonsnap_parseAttributes(attribute_string) {
	var attributeName = "";
	var attributeValue = "";
	var withInName;
	var withInValue;
	var attributes = new Array();
	var whiteSpaceRegExp = new RegExp('^[ \n\r\t]+', 'g');
	var titleText = tinyMCE.getLang('lang_buttonsnap_more');
	var titleTextPage = tinyMCE.getLang('lang_buttonsnap_page');

	if (attribute_string == null || attribute_string.length < 2)
		return null;

	withInName = withInValue = false;

	for (var i=0; i<attribute_string.length; i++) {
		var chr = attribute_string.charAt(i);

		if ((chr == '"' || chr == "'") && !withInValue)
			withInValue = true;
		else if ((chr == '"' || chr == "'") && withInValue) {
			withInValue = false;

			var pos = attributeName.lastIndexOf(' ');
			if (pos != -1)
				attributeName = attributeName.substring(pos+1);

			attributes[attributeName.toLowerCase()] = attributeValue.substring(1).toLowerCase();

			attributeName = "";
			attributeValue = "";
		} else if (!whiteSpaceRegExp.test(chr) && !withInName && !withInValue)
			withInName = true;

		if (chr == '=' && withInName)
			withInName = false;

		if (withInName)
			attributeName += chr;

		if (withInValue)
			attributeValue += chr;
	}

	return attributes;
}

function TinyMCE_buttonsnap_cleanup(type, content) {
	switch (type) {
		case "initial_editor_insert":
			content = TinyMCE_buttonsnap_cleanup("insert_to_editor", content);
			alert('foo');

			break;

		case "insert_to_editor":
			var startPos = 0;

			for(z=0;z<buttonsnap_markers.length;z++) {
				var startPos = 0;
				while ((startPos = content.indexOf('<!--' + buttonsnap_markers[z] + '-->', startPos)) != -1) {
					// Insert image
					var contentAfter = content.substring(startPos + 7 + buttonsnap_markers[z].length);
					content = content.substring(0, startPos);
					content += '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" ';
					content += ' width="100%" height="40px" ';
					content += 'alt="" class="' + buttonsnap_classes[z] + '" />';
					content += contentAfter;

					startPos++;
				}
			}
			break;

		case "get_from_editor":
			var startPos = -1;
			while ((startPos = content.indexOf('<img', startPos+1)) != -1) {
				var endPos = content.indexOf('/>', startPos);
				var attribs = TinyMCE_buttonsnap_parseAttributes(content.substring(startPos + 4, endPos));

				for(z=0;z<buttonsnap_classes.length;z++) {
					if (attribs['class'] == buttonsnap_classes[z]) {
						endPos += 2;

						var embedHTML = '<!--' + buttonsnap_markers[z] + '-->';

						// Insert embed/object chunk
						chunkBefore = content.substring(0, startPos);
						chunkAfter = content.substring(endPos);
						content = chunkBefore + embedHTML + chunkAfter;
						break;
					}
				}
			}
			break;
	}

	return content;
}

<?php
			$plugins[] = 'buttonsnap';
		}
		return $plugins;
	}

	function output_script($type = 'any')
	{
		echo '<script type="text/javascript">
		var buttonsnap_request_uri = "' . $this->plugin_uri() . '";
		var buttonsnap_wproot = "' . get_settings('siteurl') . '";
		</script>' . "\n";
echo <<< ENDSCRIPT

<script type="text/javascript">
addLoadEvent(function () { window.setTimeout('buttonsnap_addbuttons()',1000); });
var buttonsnap_mozilla = document.getElementById&&!document.all;
function buttonsnap_safeclick(e)
{
	if(!buttonsnap_mozilla) {
		e.returnValue = false;
		e.cancelBubble = true;
	}
}
function buttonsnap_addEvent( obj, type, fn )
{
	if (obj.addEventListener)
		obj.addEventListener( type, fn, false );
	else if (obj.attachEvent)
	{
		obj["e"+type+fn] = fn;
		obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
		obj.attachEvent( "on"+type, obj[type+fn] );
	}
}
function buttonsnap_newbutton(src, alt) {
	if(window.tinyMCE) {
		var anchor = document.createElement('A');
		anchor.setAttribute('href', 'javascript:;');
		anchor.setAttribute('title', alt);
		var newimage = document.createElement('IMG');
		newimage.setAttribute('src', src);
		newimage.setAttribute('alt', alt);
		newimage.setAttribute('class', 'mceButtonNormal');
		buttonsnap_addEvent(newimage, 'mouseover', function() {tinyMCE.switchClass(this,'mceButtonOver');});
		buttonsnap_addEvent(newimage, 'mouseout', function() {tinyMCE.switchClass(this,'mceButtonNormal');}); //restoreClass(this)
		buttonsnap_addEvent(newimage, 'mousedown', function() {tinyMCE.restoreAndSwitchClass(this,'mceButtonDown');});
		anchor.appendChild(newimage);
		brs = mcetoolbar.getElementsByTagName('BR');
		if(brs.length > 0)
			mcetoolbar.insertBefore(anchor, brs[0]);
		else
			mcetoolbar.appendChild(anchor);
	}
	else if(window.qttoolbar)
	{
		var anchor = document.createElement('input');
		anchor.type = 'button';
		anchor.value = alt;
		anchor.className = 'ed_button';
		anchor.title = alt;
		anchor.id = 'ed_' + alt;
		qttoolbar.appendChild(anchor);
	}
	return anchor;
}
function buttonsnap_newseparator() {
	if(window.tinyMCE) {
		var sep = document.createElement('IMG');

		sep.setAttribute('src', buttonsnap_wproot + '/wp-includes/js/tinymce/themes/advanced/images/spacer.gif');
		sep.className = 'mceSeparatorLine';
		sep.setAttribute('class', 'mceSeparatorLine');
		sep.setAttribute('height', '16');
		sep.setAttribute('width', '1');
		brs = mcetoolbar.getElementsByTagName('BR');
		if(brs.length > 0)
			mcetoolbar.insertBefore(sep, brs[0]);
		else
			mcetoolbar.appendChild(sep);
	}
}
function buttonsnap_settext(text) {
	if(window.tinyMCE) {
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, text);
		tinyMCE.execCommand("mceCleanup");
	} else {
		edInsertContent(edCanvas, text);
	}
}
function buttonsnap_ajax(dispatch) {
	if(window.tinyMCE) 	{
		selection = tinyMCE.getInstanceById('content').getSelectedText();
	}
	else 	{
		if (edCanvas.selectionStart || edCanvas.selectionStart == '0') {
			var startPos = edCanvas.selectionStart;
			var endPos = edCanvas.selectionEnd;

			if (startPos != endPos) {
				selection = edCanvas.value.substring(startPos, endPos);
			}
		}
		else if (document.getSelection)		{
			selection = 'document.getSelection';
		}
		else if (document.selection) 		{
			document.getElementById('content').focus();
		  	sel = document.selection.createRange();
			if (sel.text.length > 0) 			{
				selection = sel.text;
			}
			else 			{
				selection = '';
			}
		}
		else 		{
			selection = 'JAVASCRIPT ERROR FAILED TO GET SELECTED TEXT!';
		}
	}

	var ajax = new sack(buttonsnap_request_uri);
	ajax.setVar('buttonsnapdispatch', dispatch);
	ajax.setVar('selection', selection);
	ajax.onCompletion = function () {buttonsnap_settext(this.response);};
	ajax.runAJAX();
}
var mcetoolbar;
var qttoolbar = document.getElementById("ed_toolbar");
function buttonsnap_addbuttons () {
	if(window.tinyMCE) {
		try {
			var edit = document.getElementById(window.tinyMCE.getEditorId('content'));
			for(table = edit;table.tagName != 'TABLE';table = table.parentNode);
			mcetoolbar = table.rows[0].firstChild;
		}
		catch(e) {
			setTimeout('buttonsnap_addbuttons()', 5000);
			return;
		}
	}
	try {
ENDSCRIPT;

		switch($type) {
		case 'any':
			$this->buttons['any'] = array_merge($this->buttons['post'], $this->buttons['page'], $this->buttons['any']);
			break;
		default:
			$this->buttons[$type] = array_merge($this->buttons[$type], $this->buttons['any']);
		}
		$usebuttons = $this->buttons[$type];

		foreach ($usebuttons as $button) {
			if($button['type'] == 'separator') {
				echo "buttonsnap_newseparator();\n";
			}
			else {
				echo "newbtn = buttonsnap_newbutton('{$button['src']}', '{$button['alt']}');\n";
				switch($button['type']) {
				case 'text':
					echo "buttonsnap_addEvent(newbtn, 'click', function(e) {buttonsnap_settext(\"{$button['text']}\");buttonsnap_safeclick(e);});\n";
					break;
				case 'js':
					echo "buttonsnap_addEvent(newbtn, 'click', function(e) {" . $button['js'] . "buttonsnap_safeclick(e);});\n";
					break;
				case 'ajax':
					echo "buttonsnap_addEvent(newbtn, 'click', function(e) {buttonsnap_ajax(\"{$button['hook']}\");buttonsnap_safeclick(e);});\n";
					break;
				default:
					echo "buttonsnap_addEvent(newbtn, 'click', function(e) {alert(\"The :{$button->type}: button is an invalid type\");buttonsnap_safeclick(e);});\n";
				}
			}
		}
echo <<< MORESCRIPT
	}
	catch(e) {
		setTimeout('buttonsnap_addbuttons()', 5000);
	}
}
</script>

MORESCRIPT;
	}

	function textbutton($imgsrc, $alttext, $inserted, $type="any")
	{
		$this->buttons[$type][] = array('type'=>'text', 'src'=>$imgsrc, 'alt'=>$alttext, 'text'=>$inserted);
		return $this->buttons;
	}

	function jsbutton($imgsrc, $alttext, $js, $type="any")
	{
		$this->buttons[$type][] = array('type'=>'js', 'src'=>$imgsrc, 'alt'=>$alttext, 'js'=>$js);
		return $this->buttons;
	}

	function ajaxbutton($imgsrc, $alttext, $hook, $type="any")
	{
		$this->buttons[$type][] = array('type'=>'ajax', 'src'=>$imgsrc, 'alt'=>$alttext, 'hook'=>$hook);
		return $this->buttons;
	}

	function separator($type="any")
	{
		$this->buttons[$type][] = array('type'=>'separator');
		return $this->buttons;
	}

	function register_marker($marker, $cssclass)
	{
		$this->markers[$marker] = $cssclass;
	}

	function basename($src='')
	{
		if($src == '') $src = __FILE__;
		$name = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', $src);
		return str_replace('\\', '/', $name);
	}

	function plugin_uri($src = '')
	{
		return get_settings('siteurl') . '/wp-content/plugins/' . $this->basename($src);
	}

	function include_up($filename) {
		$c=0;
		while(!is_file($filename)) {
			$filename = '../' . $filename;
			$c++;
			if($c==30) {
				echo 'Could not find ' . basename($filename) . '.'; return '';
			}
		}
		return $filename;
	}

	function debug($foo)
	{
		$args = func_get_args();
		echo "<pre style=\"background-color:#ffeeee;border:1px solid red;\">";
		foreach($args as $arg1)
		{
			echo htmlentities(print_r($arg1, 1)) . "<br />";
		}
		echo "</pre>";
	}
}
$buttonsnap = new buttonsnap();
function buttonsnap_textbutton($imgsrc, $alttext, $inserted, $type="any") { global $buttonsnap; return $buttonsnap->textbutton($imgsrc, $alttext, $inserted, $type);}
function buttonsnap_jsbutton($imgsrc, $alttext, $js, $type="any") { global $buttonsnap; return $buttonsnap->jsbutton($imgsrc, $alttext, $js, $type);}
function buttonsnap_ajaxbutton($imgsrc, $alttext, $hook, $type="any") { global $buttonsnap; return $buttonsnap->ajaxbutton($imgsrc, $alttext, $hook, $type);}
function buttonsnap_separator($type="any") { global $buttonsnap; return $buttonsnap->separator($type);}

function buttonsnap_textbutton_post($imgsrc, $alttext, $inserted) { global $buttonsnap; return $buttonsnap->textbutton($imgsrc, $alttext, $inserted, 'post');}
function buttonsnap_jsbutton_post($imgsrc, $alttext, $js) { global $buttonsnap; return $buttonsnap->jsbutton($imgsrc, $alttext, $js, 'post');}
function buttonsnap_ajaxbutton_post($imgsrc, $alttext, $hook) { global $buttonsnap; return $buttonsnap->ajaxbutton($imgsrc, $alttext, $hook, 'post');}
function buttonsnap_separator_post() { global $buttonsnap; return $buttonsnap->separator('post');}

function buttonsnap_textbutton_page($imgsrc, $alttext, $inserted) { global $buttonsnap; return $buttonsnap->textbutton($imgsrc, $alttext, $inserted, 'page');}
function buttonsnap_jsbutton_page($imgsrc, $alttext, $js) { global $buttonsnap; return $buttonsnap->jsbutton($imgsrc, $alttext, $js, 'page');}
function buttonsnap_ajaxbutton_page($imgsrc, $alttext, $hook) { global $buttonsnap; return $buttonsnap->ajaxbutton($imgsrc, $alttext, $hook, 'page');}
function buttonsnap_separator_page() { global $buttonsnap; return $buttonsnap->separator('page');}

function buttonsnap_dirname($src = '') {global $buttonsnap; return dirname($buttonsnap->plugin_uri($src));}
function buttonsnap_register_marker($marker, $cssclass) {global $buttonsnap; return $buttonsnap->register_marker($marker, $cssclass);}
endif;
if (!defined('ABSPATH')) {
  require_once($buttonsnap->include_up('wp-config.php'));
  $buttonsnap->go_solo();
}
else {
	$buttonsnap->sink_hooks();
}

?>
