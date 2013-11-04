<?php
/*
please see cforms.php for more information
*/

### new Global Settings container, will eventually be the only one!
$cformsSettings = get_option('cforms_settings');

$plugindir   = $cformsSettings['global']['plugindir'];
$cforms_root = $cformsSettings['global']['cforms_root'];

?>
<div class="wrap" id="top">
		<div id="icon-cforms-help" class="icon32"><br/></div><h2><?php _e('Help','cforms')?></h2>

		<p>
        	<?php _e('Here you\'ll find plenty of examples and documentation that should help you configure <strong>cforms</strong>.', 'cforms'); ?>
			<?php echo sprintf(__('This manual/help page is also available as a %shttp://www.deliciousdays.com/download/cforms-manual.pdf%sPDF document%s.', 'cforms'),'<a href="','">','</a> <img style="vertical-align:middle;" src="'.$cforms_root.'/images/adobe.gif"/>'); ?>
		</p>
		<p>
        	<?php _e('If cforms provides great services to you and/or your business, please consider making a donation to support future development.', 'cforms'); ?>:
        	<form action="http://www.deliciousdays.com/cforms-plugin/#donation"><input type="submit" class="button" id="donatebutton" value="<?php _e('Thank you!', 'cforms'); ?>" title="<?php _e('Thank you!', 'cforms'); ?>"/></form>
		</p>

		<p class="cftoctitle"><?php _e('Table of Contents', 'cforms'); ?></p>
		<ul class="cftoc">
			<li><a href="#guide" onclick="setshow(17)"><?php _e('Basic steps, a small guide', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#inserting" onclick="setshow(18)"><?php _e('Inserting a form', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#fields" onclick="setshow(19)"><?php _e('Configuring form input fields', 'cforms'); ?></a> &raquo;
			<ul style="margin-top:7px	">
				<li><a href="#taf" onclick="setshow(19)"><?php _e('Special <em>Tell A Friend</em> input fields', 'cforms'); ?></a> &raquo;</li>
				<li><a href="#commentrep" onclick="setshow(19)"><?php _e('Special <em>WP Comment Feature</em> input fields', 'cforms'); ?> &raquo;</a></li>
				<li><a href="#qa" onclick="setshow(19)"><?php _e('SPAM protection: Q &amp; A', 'cforms'); ?></a> &raquo;</li>
				<li><a href="#captcha" onclick="setshow(19)"><?php _e('SPAM protection: Captcha', 'cforms'); ?></a> &raquo;</li>
				<li><a href="#hfieldsets" onclick="setshow(19)"><?php _e('Fieldsets', 'cforms'); ?></a> &raquo;</li>
				<li><a href="#regexp" onclick="setshow(19)"><?php _e('Using regular expressions with form fields', 'cforms'); ?></a> &raquo;</li>
			</ul></li>
			<li><a href="#customerr" onclick="setshow(20)"><?php _e('Custom error messages &amp; input field titles', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#hook" onclick="setshow(21)"><?php _e('Advanced: cforms APIs &amp; (Post-)Processing of submitted data', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#dynamicforms" onclick="setshow(22)"><?php _e('Advanced: Real-time creation of dynamic forms', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#variables" onclick="setshow(23)"><?php _e('Using variables in email subjects &amp; messages', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#multipage" onclick="setshow(30)"><?php _e('Multi page forms', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#CSS" onclick="setshow(24)"><?php _e('Styling your forms', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#troubles" onclick="setshow(25)"><?php _e('Need more help?', 'cforms'); ?></a> &raquo;</li>
		</ul>

        <div class="cflegend op-closed" id="p17" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            <a id="guide" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Basic steps, a small guide', 'cforms')?>
        </div>

		<div class="cf-content" id="o17">
			<p><?php _e('Admittedly, <strong>cforms</strong> is not the easiest form mailer plugin but it may be the most flexible. The below outline should help you get started with the default form.', 'cforms'); ?></p>
			<ol style="margin:10px 0 0 100px;">
				<li><?php echo sprintf(__('First take a look at the <a href="%s">default form</a>', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#anchorfields'); ?>
					<ul style="margin:10px 0 0 30px;">
						<li><?php _e('Verify that it contains all the fields you need, are they in the right order', 'cforms'); ?> <img style="vertical-align:middle;" src="<?php echo $cforms_root; ?>/images/move.gif" alt="" title=""/>?</li>
						<li><?php _e('Check the field labels (field names), if needed make your adjustments', 'cforms'); ?> &nbsp;<button type="button" name="wrench" style="vertical-align:middle;" disabled="disabled" class="wrench"></button> </li>
						<li><?php _e('Check the flags for each field (check boxes to the right).', 'cforms'); ?></li>
						<li><?php echo sprintf(__('Want to include SPAM protection? Choose between <a href="%s" %s>Q&amp;A</a>, <a href="%s" %s>captcha</a> add an input field accordingly and configure <a href="%s" %s>here</a>.', 'cforms'),'#qa','onclick="setshow(19)"','#captcha','onclick="setshow(19)"','?page=' . $plugindir . '/cforms-global-settings.php#visitorv','onclick="setshow(13)"'); ?></li>
					</ul>
				</li>
				<li><?php echo sprintf(__('Check if the <a href="%s" %s>email admin</a> for your form is configured correctly.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#anchoremail','onclick="setshow(2)"'); ?></li>
				<li><?php echo sprintf(__('Decide if you want the visitor to receive an <a href="%s" %s>auto confirmation message</a> upon form submission.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#autoconf','onclick="setshow(5)"'); ?></li>
				<li><?php echo sprintf(__('Would you like <a href="%s" %s>to track</a> form submission via the database?', 'cforms'),'?page=' . $plugindir . '/cforms-global-settings.php#tracking','onclick="setshow(14)"'); ?></li>
				<li><?php echo sprintf(__('<a href="%s" %s>Add the default form</a> to a post or page.', 'cforms'),'#inserting','onclick="setshow(18)"'); ?></li>
				<li><?php _e('Give it a whirl.', 'cforms'); ?></li>
			</ol>
		</div>


		<div class="cflegend op-closed" id="p18" title="<?php _e('Expand/Collapse', 'cforms') ?>">
        	<a id="inserting" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Inserting a form', 'cforms')?>
        </div>

		<div class="cf-content" id="o18">
			<h3><strong><?php _e('Editing posts and pages:', 'cforms'); ?></strong></h3>

			<p class="helpimg"><img src="<?php echo $cforms_root; ?>/images/example-tiny.png"  alt=""/><br /><?php _e('TinyMCE Support', 'cforms'); ?></p>
			<p><?php echo sprintf(__('If you like to do it the \'code\' way, make sure to use %1s to include them in your <em>Pages/Posts</em>. With %2s being <u>your form NAME</u>.', 'cforms'),'<code>&lt;!--cforms name="XYZ"--&gt;</code>','<code>XYZ</code>'); ?></p>
			<p><?php echo sprintf(__('A more elegant and safer way is to use the <strong>TinyMCE Button</strong> (double check if <a href="%3s" %s>Button Support</a> is enabled!).', 'cforms'),'?page=' . $plugindir . '/cforms-global-settings.php#wpeditor','onclick="setshow(12)"'); ?></p>
			<p><?php echo sprintf(__('For backwards compatibility, the old-fashioned way is still supported: %1s for the first form and/or %2s for your other forms.', 'cforms'),'<code>&lt;!--cforms--&gt;</code>','<code>&lt;!--cforms<span style="color:red; font-weight:bold;">X</span>--&gt;</code>'); ?></p>


			<h3><strong><?php _e('Via PHP function call:', 'cforms'); ?></strong></h3>
			<p><?php echo sprintf(__('Alternatively, you can specifically insert a form (into the sidebar for instance etc.) per the PHP function call %1s, or alternatively %2s for the default/first form and/or %2s for any other form.', 'cforms'),'<code>insert_cform(\'XYZ\');</code>','<code>insert_cform();</code>','<code>insert_cform(\'<span style="color:red; font-weight:bold;">X</span>\');</code>'); ?></p>

			<p class="ex"><strong><?php _e('Note:', 'cforms'); ?></strong> <?php echo sprintf(__('"%1s" represents the number of the form, starting with %2s ..and so forth.', 'cforms'),'<span style="color:red; font-weight:bold;">X</span>','<span style="color:red; font-weight:bold;">2</span>, 3,4'); ?></p>
		</div>


		<div class="cflegend op-closed" id="p19" title="<?php _e('Expand/Collapse', 'cforms') ?>">
        	<a id="fields" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Configuring form input fields', 'cforms')?>
        </div>

		<div class="cf-content" id="o19">
			<p><?php echo sprintf(__('All supported input fields are listed below, highlighting the expected <em><u>formats</u></em> for their associated %sField Names <sup>*)</sup>%s. Form labels (<em>Field Names</em>) permit the use of <strong>HTML</strong>, see examples below.', 'cforms'),'<a class="infobutton" href="#" name="it14">','</a>'); ?></p>

			<p class="ex" style="display:none; width:400px;" id="it14"><?php _e('While the <em>Field Names</em> are usually just the label of a field (e.g. "Your Name"), they can contain additional information to support special functionality (e.g. default values, regular expressions for extended field validation etc.)', 'cforms'); ?></p>
			<p class="helpimg" style="width:400px;"><img src="<?php echo $cforms_root; ?>/images/example-wizard.png"  alt=""/><br /><?php _e('A new <em>wizard like</em> mode allows you to configure more complex settings in case all the pipes "|" and pounds "#" are overwhelming.', 'cforms'); ?></p>

			<ul style="margin:10px 0 0 100px; list-style:square;">
				<li><a href="#textonly" onclick="setshow(19)"><?php 	_e('Text only elements', 'cforms'); ?></a></li>
				<li><a href="#datepicker" onclick="setshow(19)"><?php _e('Javascript Date Picker input field', 'cforms'); ?></a></li>
				<li><a href="#single" onclick="setshow(19)"><?php 	_e('Single-, Password &amp; Multi-line fields', 'cforms'); ?></a></li>
				<li><a href="#select" onclick="setshow(19)"><?php 	_e('Select / drop down box &amp; radio buttons', 'cforms'); ?></a></li>
				<li><a href="#multiselect" onclick="setshow(19)"><?php _e('Multi-select box', 'cforms'); ?></a></li>
				<li><a href="#check" onclick="setshow(19)"><?php 		_e('Check boxes', 'cforms'); ?></a></li>
				<li><a href="#checkboxgroup" onclick="setshow(19)"><?php _e('Check box groups', 'cforms'); ?></a></li>
				<li><a href="#ccme" onclick="setshow(19)"><?php 		_e('CC:me check box', 'cforms'); ?></a></li>
				<li><a href="#multirecipients" onclick="setshow(19)"><?php _e('Multiple recipients drop down box', 'cforms'); ?></a></li>
				<li><a href="#hidden" onclick="setshow(19)"><?php 	_e('Hidden fields', 'cforms'); ?></a></li>
				<li><a href="#qa" onclick="setshow(19)"><?php 		_e('SPAM protection: Q&amp;A input field', 'cforms'); ?></a></li>
				<li><a href="#captcha" onclick="setshow(19)"><?php 	_e('SPAM protection: Captcha input field', 'cforms'); ?></a></li>
				<li><a href="#upload" onclick="setshow(19)"><?php 	_e('File attachments / upload', 'cforms'); ?></a></li>
				<li><a href="#taf" onclick="setshow(19)"><?php 		_e('Special <em>Tell A Friend</em> input fields', 'cforms'); ?></a></li>
				<li><a href="#commentrep" onclick="setshow(19)"><?php _e('Special <em>WP Comment Feature</em> input fields', 'cforms'); ?></a></li>
			</ul>


		<br style="clear:both;"/>

		<p class="fieldtitle" id="textonly">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Text only elements (no input)', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-text.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('text paragraph %1$s css class %1$s optional style', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright"><code><?php _e('Please make sure...', 'cforms'); ?>|mytextclass|font-size:9x; font-weight:bold;</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright"><code><?php echo sprintf(__('Check %s here %s for more info. %s', 'cforms'),'&lt;a href="http://mysite.com"&gt;','&lt;/a&gt;','||font-size:9x;'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2"><?php _e('HTML: the <code>text paragraph</code> supports HTML. If you need actual &lt;, &gt; in your text please use the proper HTML entity.', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="ball" colspan="2"><?php _e('The above expression applies the custom class "<code>mytextclass</code>" <strong>AND</strong> the specific styles "<code>font-size:9x; font-weight:bold;</code>" to the paragraph.', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="ball" colspan="2"><?php echo sprintf(__('If you specify a <code>css class</code>, you also need to define it in your current form theme file, <a href="%s">here</a>.', 'cforms'),'?page=' . $plugindir . '/cforms-css.php'); ?></td>
			</tr>
		</table>

		<br style="clear:both;"/>

		<p class="fieldtitle" id="datepicker">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Javascript Date Picker', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-dp.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value %1$s <a href="%2$s">regular expression</a>', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>','#regexp'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Arrival Date', 'cforms'); ?>|mm/dd/yyyy|^[0-9][0-9]/[0-9][0-9]/[0-9][0-9][0-9][0-9]$</code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('The example above will set a <em>default value</em> of "mm/dd/yyyy" so users know the expected format. The <strong>regexp</strong> at the end ensures that only this format is accepted. <strong>NOTE:</strong> You also need to <a href="%s" %s>configure the date picker options</a> to match the date format ("mm/dd/yyyy" !)', 'cforms'),'?page=' . $plugindir . '/cforms-global-settings.php#datepicker','onclick="setshow(9)"'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
				<table class="dateinfo" width="100%">
					<tr><td colspan="3"><strong><?php _e('Supported Date Formats (see "Plugin Settings" tab)', 'cforms');?><br /></strong></td></tr>
					<tr><th><?php _e('Field', 'cforms');?></th><th><?php _e('Full Form', 'cforms');?></th><th><?php _e('Short Form', 'cforms');?></th></tr>
					<tr><td><strong><?php _e('Year', 'cforms');?></strong></td><td><?php _e('yyyy (4 digits)', 'cforms');?></td><td><?php _e('yy (2 digits)', 'cforms');?></td></tr>
					<tr><td><strong><?php _e('Month', 'cforms');?></strong></td><td><?php _e('mmm (name)', 'cforms');?></td><td><?php _e('mm (2 digits)', 'cforms');?></td></tr>
					<tr><td><strong><?php _e('Day of Month', 'cforms');?></strong></td><td><?php _e('dd (2 digits)', 'cforms');?></td><td></td></tr>
				</table>
				</td>
			</tr>
		</table>

		<br style="clear:both;"/>

		<p class="fieldtitle" id="single">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Single, Password &amp; Multi line input fields', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-single.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value %1$s <a href="%2$s">regular expression</a>', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>','#regexp'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Reference', 'cforms'); ?>#|xxx-xx-xxx|^[0-9A-Z-]+$</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Your &lt;u&gt;Full&lt;/u&gt; Name', 'cforms'); ?>||^[A-Za-z- \.]+$</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code>&lt;acronym title="<?php echo sprintf(__('We need your email address for confirmation."%sYour EMail', 'cforms'),'&gt;'); ?>&lt;/acronym&gt;</code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('You can of course omit the <em>default value</em> as in Example 2.', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="select">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Select boxes &amp; radio buttons', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-dropdown.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s option1 %2$s value1 %1$s option2 %2$s value2 %1$s option3...', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>','<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Your age#12-18|kiddo#19 to 30|young#31 to 45#45+ |older', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Omitting the <code>field name</code> will result in not showing a label to the left of the field.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('The <strong>option</strong> parameter determines the text displayed to the visitor, <strong>value</strong> what is being sent in the email.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Is no <strong>value</strong> explicitly given, then the shown option text is the value sent in the email.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Note:', 'cforms'); ?><br /><?php echo sprintf(__('<strong>Select box marked "Required":</strong> Using a minus symbol %1$s for the value (after %2$s), will mark an option as invalid! Example:<br /><code>Your age#Please pick your age group|-#12 to 18|kiddo#19 to 30|young#31 to 45#45+ |older</code>. <br />"Please pick..." is shown but not considered a valid value.', 'cforms'),'<code>-</code>','<span style="color:red; font-weight:bold;">|</span>'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Note:', 'cforms'); ?><br /><?php echo sprintf(__('<strong>Radio buttons marked "Required":</strong> You can choose to not preselect a radio button upon form load, yet make a user selection mandatory for the form to validate.', 'cforms')); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>

		<p class="fieldtitle" id="multiselect">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Multi select boxes', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-ms.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s option1 %2$s value1 %1$s option2 %2$s value2 %1$s option3...', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>','<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Pick#red#blue#green#yellow#orange', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('&lt;strong&gt;Select&lt;/strong&gt;#Today#Tomorrow#This Week#Next Month#Never', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Multi select fields can be set to <strong>Required</strong>. If so and unless at least one option is selected the form won\'t validate.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('If <code>value1,2,..</code> are not specified, the values delivered in the email default to <code>option1,2,...</code>.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Examples for specific values could be the matching color codes: e.g. <code>red|#ff0000</code>', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="check">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Check boxes', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-checkbox.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name <u>left</u> %s field name <u>right</u>', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('#please check if you\'d like more information', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('You can freely choose on which side of the check box the label appears (e.g. <code>#label-right-only</code>).', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('If <strong>both</strong> left and right labels are provided, only the <strong>right one</strong> will be considered.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Check boxes can be flagged "<strong>Required</strong>" to support special use cases, e.g.: when you require the visitor to confirm that he/she has read term &amp; conditions, before submitting the form.', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="checkboxgroup">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Check box groups', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-grp.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s chk box1 label%2$schk box1 value %1$s chk box2 label %3$s chk box3...', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>','<span style="color:red; font-weight:bold;">|</span>','<span style="color:red; font-weight:bold;">##</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Select Color#green|00ff00 #red|ff0000 #purple|8726ac #yellow|fff90f', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Two # (<code>##</code>) in a row will force a new line! This helps to better structure your check box group.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Similar to <strong>multi-select boxes</strong> (see above), <strong>Check box groups</strong> allow you to deploy several check boxes (with their labels and corresponding values) that form one logical field. The result submitted via the form email is a single line including all checked options.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('If no explicit <strong>value</strong> (text after the pipe symbol \'%1$s\') is specified, the provided check box label is both label &amp; submitted value.', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('None of the check boxes within a group can be made "Required".', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="ccme">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('CC: option for visitors', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-cc.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name <u>left</u> %s field name <u>right</u>', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('#please cc: me', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('If the visitor chooses to be CC\'ed, <strong>no</strong> additional auto confirmation email (<a href="%s" %s>if configured</a>) is sent out!', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#autoconf','onclick="setshow(5)"'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Please also see <em>check boxes</em> above.', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="multirecipients">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Multiple form mail recipients', 'cforms'); ?>
		</p>


		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-multi.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s Name1 | email address(es) %1$s Name2 | email address(es)%1$s Name3...', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Send to#Joe|joe@mail.com#Pete|pete@mail.com#Hillary|hillary@mail.com', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="bleft"></td><td class="bright">
					<code><?php _e('Send to#Sales|sales1@mail.com, sales2@mail.com, sales3@mail.com#Support|admin@mail.com#HR|hr1@mail.scom, hr2@mail.com', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Adding the above field to a form, disables the form\'s specific admin email address setting.', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>

		<p class="fieldtitle" id="hidden">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Hidden input fields', 'cforms'); ?>
		</p>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('extra-data', 'cforms'); ?>|fixed,hidden text</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('post-data-meta', 'cforms'); ?>|{custom_field_1}</code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Hidden fields can contain fixed/preset values or <strong>{variables}</strong> which reference custom fields of posts or pages.', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="qa">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Visitor verification (Q&amp;A)', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-vv.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php _e('--', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('--', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('No <code>field name</code> required, the field has no configurable label per se, as it is determined at run-time from the list of <strong>Question &amp; Answers</strong> provided <a href="%s" %s>here</a>.', 'cforms'),'?page=' . $plugindir . '/cforms-global-settings.php#visitorv','onclick="setshow(13)"'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('It makes sense to encapsulate this field inside a FIELDSET, to do that simply add a <code>New Fieldset</code> field before this one.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<strong>PLEASE NOTE</strong> that by default the captcha and visitor verification (Q&amp;A) field are <strong>not</strong> shown for logged in users! This can be changed under Global Settings.', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="captcha">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Captcha', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-cap.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php _e('field name', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Enter code', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Spam Protection|title:We don\'t like spam bots|err:Please enter the CAPTCHA code correctly! If text is unreadable, try reloading.', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Alternatively or in addition to the above <strong>Visitor verification</strong> feature, you can have the visitor provide a captcha response.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<strong>PLEASE NOTE</strong> that by default the captcha and visitor verification (Q&amp;A) field are <strong>not</strong> shown for logged in users! This can be changed under Global Settings.', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="upload">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Attachments / File Upload Box', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-upload.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php _e('form label', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Please select a file', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('Please double-check the <a href="%s" %s>Global Settings</a> for proper configuration of the <code>File Upload</code> functionality (allowed extensions, file size etc.).', 'cforms'),'?page='.$plugindir.'/cforms-global-settings.php#upload','onclick="setshow(11)"'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('Please enable <a href="%s" %s>Database Input Tracking</a> on the Global Settings page to ensure a unique upload ID per attachment and to avoid accidentally overwriting an attachment.', 'cforms'),'?page='.$plugindir.'/cforms-global-settings.php#tracking','onclick="setshow(14)"'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="taf">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Tell a Friend input fields', 'cforms'); ?>
		</p>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-t-a-f.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:<br />of all 4 fields', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value %1$s <a href="#regexp">regular expression</a>', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Fields:', 'cforms'); ?></td><td class="bright">
					<code><strong><?php _e('T-A-F * Your Name', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('T-A-F * Your Email <em>(make sure it\'s checked \'Email\')</em>', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('T-A-F * Friend\'s Name', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('T-A-F * Friend\'s Email <em>(make sure it\'s checked \'Email\')</em>', 'cforms'); ?></strong></code>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('To get it working:', 'cforms'); ?></strong>
					<ol>
						<li><?php echo sprintf(__('The <a href="%s" %s>Tell A Friend feature</a> needs to be <strong>enabled for the respective form</strong> (<em>check if it\'s the right one!</em>), otherwise you won\'t see the above input fields in the [<em>Field Type</em>] select box.', 'cforms'),'?page='.$plugindir.'/cforms-options.php#tellafriend','onclick="setshow(6)"'); ?></li>
						<li><?php echo sprintf(__('The <a href="%s" %s>auto confirmation</a> message will be used as a <strong>message template</strong> and needs to be defined. See example below.', 'cforms'),'?page='.$plugindir.'/cforms-options.php#cforms_cmsg','onclick="setshow(5)"'); ?></li>
						<li><?php echo sprintf(__('There are <a href="%s" %s>three additional</a>, <em>predefined variables</em> that can be used in the <a href="%s" %s>message template</a>.', 'cforms'),'#tafvariables','onclick="setshow(23)"','?page='.$plugindir.'/cforms-options.php#cforms_cmsg','onclick="setshow(5)"'); ?></li>
						<li><?php echo _e('<strong>Add the form</strong> to your post/page php templates (see deployment options further below).', 'cforms'); ?></li>
						<li><img style="float:right;" src="<?php echo $cforms_root; ?>/images/example-t-a-f2.png"  alt=""/><?php echo _e('Tell-A-Friend <strong>enable your posts/pages</strong> by checking the T-A-F field in the WP post (page) editor.', 'cforms'); ?></li>
					</ol>

				</td>
			</tr>
		</table>
		<br />
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('Here is an example of how to setup the TXT part of the <a href="%s" %s>auto confirmation message</a> as a Tell-A-friend template:', 'cforms'),'?page='.$plugindir.'/cforms-options.php#cforms_cmsg','onclick="setshow(5)"'); ?>

				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<code>
					<?php _e('Hello {Friend\'s Name}','cforms'); ?>,<br />
					<?php  _e('{Your Name} left you this message:','cforms'); ?><br />
					<?php  _e('{Optional Comment}','cforms'); ?><br />
					<?php  _e('The message was sent in reference to','cforms'); ?> {Title}:<br />
					{Excerpt}<br />
					{Permalink}<br />
					--<br />
					<?php  _e('This email is sent, as a courtesy of website.com, located at http://website.com. The person who sent this email to you, {Your Name}, gave an email address of {Your Email}. {Your Name} logged into website.com from IP {IP}, and sent the email at {Time}.','cforms'); ?><br />
					</code>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('Note:', 'cforms'); ?></strong> <?php _e('In addition to the above TXT message you can, of course, add an HTML counterpart.', 'cforms'); ?>
				</td>
			</tr>
		</table>
		<br />
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('Recommended Implementation Options:', 'cforms'); ?></strong>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<em>Alternative 1:</em> The actual form will not show on the WP front page, but in the individual post/page view.', 'cforms'); ?>
					<ol>
						<li><?php echo sprintf(__('Simply add a <code>&lt;?php insert_cform(<em>#</em>); ?&gt;</code> (# = <a href="%s" %s>your form id</a>) to your existing <code>single.php</code> and/or <code>page.php</code> template, e.g:', 'cforms'),'#inserting','onclick="setshow(18)"');?>

<code  style="font-size: 11px;"><br />
[...]<br />
&lt;?php the_content('&lt;p&gt;Read the rest of this entry &raquo;&lt;/p&gt;'); ?&gt;<br />
<strong style="color:red;">&lt;?php if ( is_tellafriend( $post-&gt;ID ) ) insert_cform(#); ?&gt;</strong><br />
[...]
</code>
						</li>
						<li><?php echo _e('Suggestion: For a less crowded layout, optionally add some Javascript code to show/hide the form.', 'cforms'); ?></li>
					</ol>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<em>Alternative 2:</em> A Tell-A-Friend link is shown under every eligible post/page, displayed both on the blog\'s front page &amp; individual post &amp; page view.', 'cforms'); ?>

					<ol>
						<li><?php  _e('This requires a new WP page created (make note of the page ID or permalink), with its own page template (a clone of page.php will do). Add the following code to the new <strong>page template</strong>:', 'cforms'); ?>

<code  style="font-size: 11px;"><br />
[...]<br />
&lt;?php the_content('&lt;p&gt;Read the rest of this page &raquo;&lt;/p&gt;');?&gt;<br />
<strong style="color:red;">&lt;h3&gt; &lt;?php echo 'E-Mail "'.get_the_title( $_GET['pid'] ).'" to a friend:'; ?&gt; &lt;/p&gt;<br />
&lt;?php if ( is_tellafriend( $_GET['pid'] ) ) insert_cform(#); ?&gt;</strong><br />
[...]
</code>
						</li>
						<li><?php echo _e('In <em>single.php &amp; index.php</em> and/or <em>page.php</em> add beneath the "the_content()" call the link to the new page created above, e.g.:', 'cforms'); ?>

<code  style="font-size: 11px;"><br />
[...]<br />
&lt;?php the_content('&lt;p&gt;Read the rest of this entry &raquo;&lt;/p&gt;'); ?&gt;<br />
<strong style="color:red;">&lt;?php <br />
if ( is_tellafriend( $post-&gt;ID ) ) <br />
 &nbsp; &nbsp; echo '&lt;a href="[your-new-page]?&amp;pid='.$post-&gt;ID.'" title="Tell-A-Friend form"&gt;Tell a friend!&lt;/a&gt;'; <br />
?&gt;</strong><br />
[...]<br />
</code>
						</li>
						<li><?php echo _e('Replace <strong>[your-new-page]</strong> with <strong>the permalink</strong> of your newly created page.', 'cforms'); ?></li>
					</ol>

				</td>
			</tr>
		</table>

		<br style="clear:both;"/>

		<p class="fieldtitle" id="commentrep">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('WP Comment Feature input fields', 'cforms'); ?>
		</p>
		<p style="margin:5px 30px"><?php _e('The beauty is, using one form, you can now offer your readers to either leave a comment behind or simply send a note to the post editor while being able to fully utilize all security aspects of cforms.', 'cforms'); ?></p>
		<div style="float:right" align="center">
			<img class="helpimg" style="float:none" src="<?php echo $cforms_root; ?>/images/example-crep1.png"   alt=""/><br /><br />
			<img class="helpimg" style="float:none" src="<?php echo $cforms_root; ?>/images/example-crep2sm.png" alt=""/><br />
			<?php _e('Example Configuration', 'cforms'); ?>
		</div>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><?php _e('Special Fields:', 'cforms'); ?></td><td class="bright">
					<code><strong><?php _e('Comment Author', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('Author\'s Email', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('Author\'s URL', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('Author\'s Comment', 'cforms'); ?></strong></code><br />
					<code><strong>Subscribe To Comments <small><?php _e('(if plugin installed)', 'cforms'); ?></small></strong></code><br />
					<code><strong>Comment Luv <small><?php _e('(if plugin installed)', 'cforms'); ?></small></strong></code>
				</td>
			</tr>
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:<br />for top 4 fields', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name  %1$s  default value  %1$s  <a href="#regexp">regular expression</a>', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>

			<tr><td class="bleft" colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="bleft"><?php _e('Special Field', 'cforms'); ?>:</td><td class="bright">
					<code><strong><?php _e('Select: Email/Comment', 'cforms'); ?></strong></code>
				</td>
			</tr>
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s text <em>comment</em> %2$s 0 %1$s text <em>to author</em> %2$s 1', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>','<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Send as#regular comment|0#email to post author|1', 'cforms'); ?></code></td>
			</tr>
			<tr><td class="bleft" colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('To get it working:', 'cforms'); ?></strong>
					<ol>
						<li><?php echo sprintf(__('Turn on the <a href="%s" %s>WP Comment feature</a> for the given form. (<em>Make sure it\'s the right one!</em>), otherwise you won\'t see the above input fields in the [<em>Field Type</em>] select box.', 'cforms'),'?page='.$plugindir.'/cforms-options.php#commentrep','onclick="setshow(7)"'); ?></li>
						<li><?php _e('Modify this form to include all the necessary (new) input fields, make them required or not, add regexp, anti SPAM fields or even custom err messages. All up to you. Or better yet, start with the built-in preset: "<strong>Advanced: WP comment...</strong>" form.', 'cforms'); ?></li>
						<li><?php echo sprintf(__('Edit your WP Theme template for comments. Remove the current <strong><u>form tag</u></strong> entirely (<code  style="color:red">&lt;form action=&quot;...&lt;/form&gt;</code>). Instead replace with a PHP call to cforms: <code  style="color:red">&lt;?php insert_cform(X); ?&gt;</code> with <strong>X</strong> being <u>omitted</u> if the form is your default form or starting at <strong>\'2\'</strong> (with single quotes!) for any subsequent form #. %sSee example comments.php here!%s', 'cforms'),'<a href="'.$cforms_root.'/cforms-example-comments.php.txt">','</a>'); ?></li>
						<li><?php echo sprintf(__('Double check the extended <a href="%s" %s>WP comment feature settings here</a> (especially the Ajax specific ones!). ', 'cforms'),'?page='.$plugindir.'/cforms-global-settings.php#wpcomment','onclick="setshow(28)"'); ?></li>
						<li><?php echo '<strong>'.__('Important:','cforms').'</strong> '; _e('To make Ajax work in case there are no comments yet, make sure that the comment container <strong>is always</strong> being rendered.', 'cforms'); ?></li>
					</ol>
				</td>
			</tr>
		</table>

		<br />
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('Suggestions:', 'cforms'); ?></strong>
					<ol>
						<li><?php echo sprintf(__('I recommend you choose the <strong>wide_form.css</strong> theme under the <a href="%s">Styling</a> menu. And adjust to your liking.', 'cforms'),'?page='.$plugindir.'/cforms-css.php'); ?></li>
						<li><?php _e('If you intend to make certain fields "required", I further recommend you add the text "<em>required</em>" to the input field label and set this style: <code  style="color:red">span.reqtxt, span.emailreqtxt {...</code> to <code  style="color:red">display:none;</code> (using the CSS editor on the <em>Styling</em> page)', 'cforms'); ?></li>
					</ol>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('Other comment plugins?', 'cforms'); ?></strong> <?php _e('cforms\' WP comment feature supports the following comment plugins:', 'cforms') ?> <a href="http://www.fiddyp.co.uk/commentluv-wordpress-plugin/">Comment Luv</a>, <a href="http://txfx.net/code/wordpress/subscribe-to-comments/">Subscribe To Comment</a> &amp; <a href="http://www.raproject.com/ajax-edit-comments-20/">WP Ajax Edit Comments</a>.
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('Tutorial:', 'cforms'); ?></strong> <?php echo sprintf(__('<a href="%s">Here you\'ll find</a> a comprehensive write up by Erum Munir on how to use cforms in combination with the Subscribe-To plugin, and a more <a href="%s">general one</a> for using cforms as a stand in replacement for the default WP comment functionality.', 'cforms'), 'http://www.erummunir.com/76/cforms-ii-the-all-in-one-contact-form-solution','http://www.erummunir.com/154/cforms-ii-more-on-using-as-a-comment-form'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<p class="fieldtitle" id="hfieldsets">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Fieldsets', 'cforms'); ?>
		</p>

   		<p style="margin:10px 30px;"><?php _e('Fieldsets are definitely part of good form design, they are form elements that are used to create individual sections of content within a given form.', 'cforms'); ?></p>

		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-fieldsets.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php _e('fieldset name', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('My Fieldset', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Fieldsets can begin anywhere, simply add a <strong>New Fieldset</strong> field between or before your form elements.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Fieldsets do not need to explicitly be closed, a <strong>New Fieldset</strong> element will automatically close the existing (if there is one to close) and reopen a new one.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<strong>End Fieldset</strong> <u>can</u> be used, but it works without just as well.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('If there is no closing <strong>End Fieldset</strong> element, the plugin assumes that it needs to close the set just before the submit button', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both; "/>


		<p class="fieldtitle" id="regexp">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Using regular expressions with form fields', 'cforms'); ?>
		</p>

		<p style="margin:10px 30px;"><?php _e('A regular expression (regex or regexp for short) is a special text string for describing a search pattern, according to certain syntax rules. Many programming languages support regular expressions for string manipulation, you can use them here to validate user input. Single/Multi line input fields:', 'cforms'); ?></p>

		<!-- no img for regexps-->
		<table class="hf" cellspacing="2" border="4" width="95%">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value %1$s regular expression', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:<br />US zip code', 'cforms'); ?></td><td class="bright">
					<code><?php _e('zip code', 'cforms'); ?>||^\d{5}$)|(^\d{5}-\d{4}$</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:<br />US phone #', 'cforms'); ?></td><td class="bright">
					<code><?php _e('phone', 'cforms'); ?>||^[\(]?(\d{0,3})[\)]?[\s]?[\-]?(\d{3})[\s]?[\-]?(\d{4})[\s]?[x]?(\d*)$</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Special Example:<br />comparing two input fields', 'cforms'); ?></td><td class="bright">
					<code><?php _e('please repeat email', 'cforms'); ?>||<span style="color:red">cf2_field_2</span></code></td>
			</tr>
			<tr>
				<td class="bright" colspan="2">
					<?php echo '<strong style="color:red">'.__('Important:','cforms').'</strong>';?><br />
					<?php _e('<strong>If you need to compare two input fields (e.g. email verification):</strong> simply use the regexp field (see special example above, to point to the <u>HTML element ID</u> of the field you want to compare the current one to. To find the <u>HTML element ID</u> you would have to look into the html source code of the form (e.g.', 'cforms'); ?> <code style="color:red">cf2_field_2</code>).
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('GENERAL:', 'cforms'); ?></strong>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Ensure that the input field in question is tagged \'<strong>Required</strong>\'!', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<code>^</code> <?php _e('and', 'cforms'); ?> <code>$</code> <?php _e('define the start and the end of the input', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					"<code>ab*</code>": <?php _e('...matches a string that has an "a" followed by zero or more "b\'s" ("a", "ab", "abbb", etc.);', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					"<code>ab+</code>": <?php _e('...same, but there\'s at least one b ("ab", "abbb", etc.);', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					"<code>[a-d]</code>": <?php _e('...a string that has lowercase letters "a" through "d"', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('More information can be found <a href="%s">here</a>, a great regexp repository <a href="%s">here</a>.', 'cforms'),'http://weblogtoolscollection.com/regex/regex.php','http://regexlib.com'); ?>
				</td>
			</tr>
		</table>
	</div>


		<div class="cflegend op-closed" id="p20" title="<?php _e('Expand/Collapse', 'cforms') ?>">
        	<a id="customerr" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Custom error messages &amp; input field titles', 'cforms')?>
        </div>

		<div class="cf-content" id="o20">
			<p><?php echo sprintf(__('On top of their labels, input fields can have titles, too. Simply append a %s to a given field configuration string.', 'cforms'),'<code>|title:XXX</code>'); ?></p>
			<p><?php echo sprintf(__('If you like to add custom error messages (next to your generic <a href="%s" %s>success</a> and <a href="%s" %s>error</a> messages) for your input fields, simply append a %s to a given <em>definition string/field name</em>. HTML is supported.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#cforms_success','onclick="setshow(1)"','?page=' . $plugindir . '/cforms-options.php#cforms_failure','onclick="setshow(1)"','<code>|err:XXX</code>'); ?></p>
			<p class="ex"><?php echo sprintf(__('Please note the order of these special attributes, first %s (if applicable), then %s.', 'cforms'),'<code>|title:XXX</code>','<code>|err:XXX</code>');?></p>

			<table class="hf" cellspacing="2" border="4" width="95%">
				<tr>
					<td class="bleft"><span class="abbr" title="<?php _e('Extended entry format for the Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
					<td class="bright"><?php echo sprintf(__('field name %1$s your title here %3$s %2$s your error message %3$s', 'cforms'),'<span style="color:red; font-weight:bold;">|title:<em>','<span style="color:red; font-weight:bold;">|err:<em>','</em></span>'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><?php _e('Example 1:', 'cforms'); ?></td><td class="bright">
						<code><?php _e('Your Name|title:Only alphabetic characters allowed!', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft"><?php _e('Example 2:', 'cforms'); ?></td><td class="bright">
						<code><?php _e('Your Name|title:Please provide your first and last name!|err:Please enter your full name.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft"><?php _e('Example 3:', 'cforms'); ?></td><td class="bright">
						<code><?php _e('Your age#12-18|kiddo#19 to 30|young#31 to 45#45+ |older', 'cforms'); ?><?php _e('|err: your age is &lt;strong&gt;important&lt;/strong&gt; to us.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Note:', 'cforms'); ?></strong>
					</td>
				</tr>
				<tr>
					<td class="ball" colspan="2">
						<?php _e('<strong>Custom error messages</strong> can be applied to any input field that can be flagged "<strong>Required</strong>", <strong>titles</strong> to any input field.', 'cforms'); ?>
					</td>
				</tr>
			</table>
		</div>


		<div class="cflegend op-closed" id="p21" title="<?php _e('Expand/Collapse', 'cforms') ?>">
        	<a id="hook" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Advanced: cforms APIs &amp; (Post-)Processing of submitted data', 'cforms')?>
        </div>

		<div class="cf-content" id="o21">
			<br/ >
			<table class="hf" cellspacing="2" border="4" width="95%">
				<tr>
					<td class="apiH" colspan="2"><span class="abbr" title="<?php _e('API Function :: get_cforms_entries()', 'cforms'); ?>"><?php _e('API Function', 'cforms'); ?></span> &nbsp;&nbsp;&nbsp; <strong>get_cforms_entries(&nbsp;$fname,&nbsp;$from,&nbsp;$to,&nbsp;$sort,&nbsp;$limit&nbsp;,$sortdir&nbsp;)</strong></td>
				</tr>
				<tr>
					<td class="bright" colspan="2"><span class="abbr"><?php _e('Description', 'cforms'); ?>:</span> &nbsp;&nbsp;&nbsp; <?php _e('This function allows to conveniently retrieve submitted data from the cforms tracking tables.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bright" colspan="2"><span class="abbr"><?php _e('Parameters', 'cforms'); ?>:</span></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">$fname&nbsp;::&nbsp;<?php _e('[text]', 'cforms'); ?></code></strong></td>
					<td class="bright"><?php _e('text string (regexp pattern), e.g. the form name', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">$from,&nbsp;$to&nbsp;::&nbsp;<?php _e('[date]', 'cforms'); ?></code></strong></td>
					<td class="bright"><?php _e('DATETIME string (format: Y-m-d H:i:s). Date &amp; time defining the target period, e.g.', 'cforms'); ?><strong style="color:red;"> 2008-09-17 15:00:00</strong></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">$sort&nbsp;::&nbsp;<?php _e('[text]', 'cforms'); ?></code></strong></td>
					<td class="bright"><strong style="color:red;">'form'</strong>, <strong style="color:red;">'id'</strong>, <strong style="color:red;">'date'</strong>, <strong style="color:red;">'ip'</strong> <?php _e('or', 'cforms'); ?> <strong style="color:red;">'email'</strong><?php _e(' or any other form input field, e.g. \'Your Name\'', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">$limit&nbsp;::&nbsp;<?php _e('[number]', 'cforms'); ?></code></strong></td>
					<td class="bright"><?php _e('limiting the number of results, \'\' (empty or false) = no limits!', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">$sortdir&nbsp;::&nbsp;<?php _e('[text]', 'cforms'); ?></code></strong></td>
					<td class="bright"><strong style="color:red;">asc</strong>, <strong style="color:red;">desc</strong></td>
				</tr>
				<tr><td class="bright" colspan="2"><span class="abbr"><?php _e('Output', 'cforms'); ?>:</span></td></tr>
				<tr><td class="bright" colspan="2"><?php _e('This function will return a set of stored form submissions in a multi-dimensional array.', 'cforms'); ?></td></tr>
				<tr><td class="ball" colspan="2"><span class="abbr"><?php _e('Examples', 'cforms'); ?></span></td></tr>
				<tr><td class="ball" colspan="2"><code>$array = get_cforms_entries();   /* all data, no filters */</code></td></tr>
				<tr><td class="ball" colspan="2"><code>$array = get_cforms_entries('contact',false,false,'date',5,'desc');   /* last 5 submissions of "my contact form", order by date */</code></td></tr>
				<tr><td class="ball" colspan="2"><code>$array = get_cforms_entries(false,date ("Y-m-d H:i:s", time()-(3600*2)));   /* all submissions in the last 2 hours */</code></td></tr>
				<tr><td class="ball" colspan="2">
                <span class="abbr"><?php _e('Example: Table Output', 'cforms'); ?></span><br /><br />
                <pre style="font-size: 11px; background:#EAEAEA;">$array = get_cforms_entries();   /* all data, no filters */

echo '&lt;table&gt;';
echo '&lt;tr&gt;&lt;th&gt;Name&lt;/th&gt;&lt;th&gt;Email&lt;/th&gt;&lt;th&gt;Website&lt;/th&gt;&lt;/tr&gt;';
foreach( $array as $e ){
	echo '&lt;tr&gt;&lt;td&gt;' . $e['data']['Your Name'] . '&lt;/td&gt;&lt;td&gt;' . $e['data']['Email'] . '&lt;/td&gt;&lt;td&gt;' . $e['data']['Website'] . '&lt;/td&gt;&lt;tr&gt;';
}
echo '&lt;/table&gt;';</pre></td></tr>
			</table>
			<br />
			<table class="hf" cellspacing="2" border="4" width="95%">
				<tr>
					<td class="apiH" colspan="2"><span class="abbr" title="<?php _e('API Function :: cf_extra_comment_data()', 'cforms'); ?>"><?php _e('API Function', 'cforms'); ?></span> &nbsp;&nbsp;&nbsp; <strong>cf_extra_comment_data(&nbsp;$commentID&nbsp;)</strong></td>
				</tr>
				<tr>
					<td class="bright" colspan="2"><span class="abbr"><?php _e('Description', 'cforms'); ?>:</span> &nbsp;&nbsp;&nbsp; <?php _e('This function retrieves all extra data submitted (besides the default Author, Email, URL, Message fields) per a given comment context. This function should be called from within the "comment LOOP".', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bright" colspan="2"><span class="abbr"><?php _e('Parameters', 'cforms'); ?>:</span></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">$commentID&nbsp;::&nbsp;<?php _e('[number]', 'cforms'); ?></code></strong></td>
					<td class="bright"><?php _e('The comment ID is expected.', 'cforms'); ?></td>
				</tr>
				<tr><td class="bright" colspan="2"><span class="abbr"><?php _e('Output', 'cforms'); ?>:</span></td></tr>
				<tr><td class="bright" colspan="2"><?php _e('This function will return a set of stored comment data in a multi-dimensional array.', 'cforms'); ?></td></tr>
				<tr><td class="ball" colspan="2"><span class="abbr"><?php _e('Example', 'cforms'); ?></span> <?php echo sprintf(__('(see also the %sonline tutorial%s in the cforms forum)', 'cforms'),'<a href="http://www.deliciousdays.com/cforms-forum/troubleshooting/tutorial-wp-comment-feature-adding-and-using-extra-fields/">','</a>'); ?></td></tr>
				<tr><td class="ball" colspan="2"><code>$xtra_comment_data = cf_extra_comment_data( get_comment_ID() );   /* all data, no filters */</code></td></tr>
			</table>

			<p>
            	<?php _e('(Post-)Processing of submitted data is really for hard core deployments, where <em>real-time manipulation</em> of a form &amp; fields are required.', 'cforms'); ?>
				<?php _e('If you require the submitted data to be manipulated, and or sent to a 3rd party or would like to make use of the data otherwise, here is how:', 'cforms'); ?>
            </p>

			<table class="hf" cellspacing="2" border="4" width="95%">
				<tr>
					<td class="apiH" colspan="2"><span class="abbr" title="<?php _e('Custom functions to (post-)process user input', 'cforms'); ?>"><?php _e('Available User Functions', 'cforms'); ?></span>&nbsp;&nbsp;<?php _e('(see <strong>my-functions.php</strong> file (plugin root directory), including examples)', 'cforms');?></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">my_cforms_filter()</code></strong></td>
					<td class="bright"><?php _e('function gets triggered <strong>after</strong> user input validation, but <strong>before</strong> processing input data', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">my_cforms_ajax_filter()</code></strong></td>
					<td class="bright"><?php _e('function gets called <strong>after</strong> input validation, but <strong>before</strong> processing input data', 'cforms'); ?> <?php _e('(nonAjax)', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">my_cforms_action()</code></strong></td>
					<td class="bright"><?php _e('function gets called <strong>just before</strong> sending the email', 'cforms'); ?> <?php _e('(Ajax)', 'cforms'); ?></td>
				</tr>
                <tr>
                    <td class="bleft"><strong><code class="codehighlight">my_cforms_logic()</code></strong></td>
                    <td class="bright"><?php _e('function gets called <strong>at</strong> various stages of input processing', 'cforms'); ?></td>
                </tr>
			</table>
			<p class="ex" ><?php echo sprintf(__('%s can reside in your /plugins/cforms-custom folder to protect it from future (auto) upgrades.', 'cforms'),'<strong>my-functions.php</strong>'); ?></p>
		</div>


		<div class="cflegend op-closed" id="p22" title="<?php _e('Expand/Collapse', 'cforms') ?>">
        	<a id="dynamicforms" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Advanced: Real-time creation of dynamic forms', 'cforms')?>
        </div>

		<div class="cf-content" id="o22">
			<p><?php _e('Again, this is for the advanced user who requires ad-hoc creation of forms.', 'cforms'); ?></p>

			<p><strong><?php _e('A few things to note on dynamic forms:', 'cforms'); ?></strong></p>
			<ol>
				<li><?php _e('Dynamic forms only work in <strong>non-Ajax</strong> mode.', 'cforms');?></li>
				<li><?php _e('Each dynamic form references and thus requires <strong>a base form defined</strong> in the cforms form settings. All its settings will be used, except the form (&amp;field) definition.', 'cforms');?></li>
				<li><?php _e('Any of the form fields described in the plugins\' <strong>HELP!</strong> section can be dynamically generated.', 'cforms');?></li>
				<li><?php echo sprintf(__('Function call to generate dynamic forms: %s with', 'cforms'),'<code>insert_custom_cform($fields:array,$form-no:int);</code> ');?>

	                <br /><br />
	                <code>$form-no</code>: <?php _e('empty string for the first (default) form and <strong>2</strong>,3,4... for any subsequent form', 'cforms'); ?><br />
	                <code>$fields</code> :

	                <pre style="font-size: 11px;"><code style="background:none;">
	            $fields['label'][n]      = '<?php _e('field name', 'cforms'); ?>';           <?php _e('<em>field name</em> format described above', 'cforms'); ?>

	            $fields['type'][n]       = 'input field type';     default: 'textfield';
	            $fields['isreq'][n]      = true|false;             default: false;
	            $fields['isemail'][n]    = true|false;             default: false;
	            $fields['isclear'][n]    = true|false;             default: false;
	            $fields['isdisabled'][n] = true|false;             default: false;
	            $fields['isreadonly'][n] = true|false;             default: false;

	            n = 0,1,2...</code></pre></li>
	    	</ol>


	        <strong><?php _e('Form input field types (\'type\'):', 'cforms'); ?></strong>
	        <ul style="list-style:none;">
	        <li>
	            <table class="cf_dyn_fields">
	                <tr><td><strong><?php _e('Basic fields', 'cforms'); ?></strong></td><td></td><td class="cf-wh">&nbsp;</td><td><strong><?php _e('Special T-A-F fields', 'cforms'); ?></strong></td><td></td></tr>
	                <tr><td><?php _e('Text paragraph', 'cforms'); ?>:</td><td> <code>textonly</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('T-A-F * Your Name', 'cforms'); ?>:</td><td> <code>yourname</code></td></tr>
	                <tr><td><?php _e('Single input field', 'cforms'); ?>:</td><td> <code>textfield</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('T-A-F * Your Email', 'cforms'); ?>:</td><td> <code>youremail</code></td></tr>
	                <tr><td><?php _e('Multi line field', 'cforms'); ?>:</td><td> <code>textarea</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('T-A-F * Friend\'s Name', 'cforms'); ?>:</td><td> <code>friendsname</code></td></tr>
	                <tr><td><?php _e('Hidden field', 'cforms'); ?>:</td><td> <code>hidden</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('T-A-F * Friend\'s Name', 'cforms'); ?>:</td><td> <code>friendsemail</code></td></tr>
	                <tr><td><?php _e('Password field', 'cforms'); ?>:</td><td> <code>pwfield</code></td></tr>
	                <tr><td><?php _e('Date picker field', 'cforms'); ?>:</td><td> <code>datepicker</code></td><td class="cf-wh">&nbsp;</td><td><strong><?php _e('WP Comment Feature', 'cforms'); ?></strong></td><td></td></tr>
	                <tr><td><?php _e('Check boxes', 'cforms'); ?>:</td><td> <code>checkbox</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Comment Author', 'cforms'); ?>:</td><td> <code>author</code></td></tr>
	                <tr><td><?php _e('Check boxes groups', 'cforms'); ?>:</td><td> <code>checkboxgroup</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Author\'s Email', 'cforms'); ?>:</td><td> <code>email</code></td></tr>
	                <tr><td><?php _e('Drop down fields', 'cforms'); ?>:</td><td> <code>selectbox</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Author\'s URL', 'cforms'); ?>:</td><td> <code>url</code></td></tr>
	                <tr><td><?php _e('Multi select boxes', 'cforms'); ?>:</td><td> <code>multiselectbox</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Author\'s Comment', 'cforms'); ?>:</td><td> <code>comment</code></td></tr>
	                <tr><td><?php _e('Radio buttons', 'cforms'); ?>:</td><td> <code>radiobuttons</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Select: Email/Comment', 'cforms'); ?>:</td><td> <code>send2author</code></td></tr>
	                <tr><td><?php _e('\'CC\' check box', 'cforms'); ?> <sup>*)</sup>:</td><td> <code>ccbox</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Subscribe To Comments', 'cforms'); ?>:</td><td> <code>subscribe</code></td></tr>
	                <tr><td><?php _e('Multi-recipients field', 'cforms'); ?> <sup>*)</sup>:</td><td> <code>emailtobox</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Comment Luv', 'cforms'); ?>:</td><td> <code>luv</code></td></tr>
	                <tr><td><?php _e('Spam/Q&amp;A verification', 'cforms'); ?> <sup>*)</sup>:</td><td> <code>verification</code></td></tr>
	                <tr><td><?php _e('Spam/captcha verification', 'cforms'); ?>:</td><td> <code>captcha</code></td></tr>
	                <tr><td><?php _e('File upload fields', 'cforms'); ?> <sup>*)</sup>:</td><td> <code>upload</code></td></tr>
	                <tr><td><?php _e('Begin of a fieldset', 'cforms'); ?>:</td><td> <code>fieldsetstart</code></td></tr>
	                <tr><td><?php _e('End of a fieldset', 'cforms'); ?>:</td><td> <code>fieldsetend</code></td></tr>
	            </table>
	        </li>
	        <li><sup>*)</sup> <em><?php _e('Should only be used <strong>once</strong> per generated form!', 'cforms'); ?></em></li>
	        </ul>

        <br />

		<a id="ex1"></a>
        <strong><?php _e('Simple example:', 'cforms'); ?></strong>
        <ul style="list-style:none;">
        <li>
        <pre style="font-size: 11px;"><code style="background:none;">
$fields = array();

$formdata = array(
		array('<?php _e('Your Name|Your Name', 'cforms'); ?>','textfield',0,1,0,1,0),
		array('<?php _e('Your Email', 'cforms'); ?>','textfield',0,0,1,0,0),
		array('<?php _e('Your Message', 'cforms'); ?>','textarea',0,0,0,0,0)
		);

$i=0;
foreach ( $formdata as $field ) {
	$fields['label'][$i]        = $field[0];
	$fields['type'][$i]         = $field[1];
	$fields['isdisabled'][$i]   = $field[2];
	$fields['isreq'][$i]        = $field[3];
	$fields['isemail'][$i]      = $field[4];
	$fields['isclear'][$i]      = $field[5];
	$fields['isreadonly'][$i++] = $field[6];
}

insert_custom_cform($fields,'');    //<?php _e('Call default form with two defined fields', 'cforms'); ?></code></pre>
        </li>
        </ul>

        <br />

		<a id="ex2"></a>
        <?php _e('<strong>More advanced example</strong> (file access)', 'cforms'); ?><strong>:</strong>
        <ul style="list-style:none;">
        <li>
        <pre style="font-size:11px"><code style="background:none;">
$fields['label'][0]  ='<?php _e('Your Name|Your Name', 'cforms'); ?>';
$fields['type'][0]   ='textfield';
$fields['isreq'][0]  ='1';
$fields['isemail'][0]='0';
$fields['isclear'][0]='1';
$fields['label'][1]  ='<?php _e('Email', 'cforms'); ?>';
$fields['type'][1]   ='textfield';
$fields['isreq'][1]  ='0';
$fields['isemail'][1]='1';
$fields['label'][2]  ='<?php _e('Please pick a month for delivery:', 'cforms'); ?>||font-size:14px; padding-top:12px; text-align:left;';
$fields['type'][2]   ='textonly';

$fields['label'][3]='<?php _e('Deliver on#Please pick a month', 'cforms'); ?>|-#';

$fp = fopen(dirname(__FILE__).'/months.txt', "r"); // <?php _e('Need to put this file into your themes dir!', 'cforms'); ?>

while ($nextitem = fgets($fp, 512))
	$fields['label'][3] .= $nextitem.'#';

fclose ($fp);

$fields['label'][3]  = substr( $fields['label'][3], 0, strlen($fields['label'][3])-1 );  //<?php _e('Remove the last \'#\'', 'cforms'); ?>
$fields['type'][3]   ='selectbox';
$fields['isreq'][3]  ='1';
$fields['isemail'][3]='0';

insert_custom_cform($fields,5);    //<?php _e('Call form #5 with new fields', 'cforms'); ?></code></pre>
        </li>
        </ul>

        <?php _e('With <code>month.txt</code> containing all 12 months of a year:', 'cforms'); ?>
        <ul style="list-style:none;">
        <li>
        <pre><code style="background:none;">
<?php _e('January', 'cforms'); ?>

<?php _e('February', 'cforms'); ?>

<?php _e('March', 'cforms'); ?>

...</code></pre>
        </li>
        </ul>

		</div>


		<div class="cflegend op-closed" id="p23" title="<?php _e('Expand/Collapse', 'cforms') ?>">
        	<a id="variables" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Using variables in email subject and messages', 'cforms')?>
        </div>

		<div class="cf-content" id="o23">
			<p>
				<?php echo sprintf(__('<strong>Subjects and messages</strong> for emails both to the <a href="%s" %s>form admin</a> as well as to the <a href="%s" %s>visitor</a> (auto confirmation, CC:) support insertion of pre-defined variables and/or any of the form input fields.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#anchoremail','onclick="setshow(2)"','?page=' . $plugindir . '/cforms-options.php#autoconf','onclick="setshow(5)"'); ?>
			</p>
			<p class="ex"><?php _e('Note that the variable names are case sensitive!', 'cforms'); ?></p>

			<table class="hf" cellspacing="2" border="4">
				<tr>
					<td class="bright" colspan="2"><span class="abbr" title="<?php _e('Case sensitive!', 'cforms'); ?>"><strong><?php _e('Predefined variables:', 'cforms'); ?></strong></span></td>
				</tr>
				<tr>
					<td class="bleft"><code>{BLOGNAME}</code></td>
					<td class="bright"><?php _e('Inserts the Blog\'s name.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{Form Name}</code></td>
					<td class="bright"><?php _e('Inserts the form name (per your configuration).', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{PostID}</code></td>
					<td class="bright"><?php _e('Inserts the ID of the post the form is shown in.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{Referer}</code></td>
					<td class="bright"><?php _e('Inserts the HTTP referer information (if available).', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{Page}</code></td>
					<td class="bright"><?php _e('Inserts the WP page the form was submitted from.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{Date}</code></td>
					<td class="bright"><?php _e('Inserts the date of form submission (per your general WP settings).', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{Time}</code></td>
					<td class="bright"><?php _e('Inserts the time of form submission (per your general WP settings).', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{IP}</code></td>
					<td class="bright"><?php _e('Inserts visitor IP address.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{ID}</code></td>
					<td class="bright"><?php _e('Inserts a unique and referenceable form ID (provided that DB Tracking is enabled!)', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{CurUserID}</code></td>
					<td class="bright"><?php _e('Inserts the ID of the currently logged-in user.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{CurUserName}</code></td>
					<td class="bright"><?php _e('Inserts the Name of the currently logged-in user.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{CurUserEmail}</code></td>
					<td class="bright"><?php _e('Inserts the Email Address of the currently logged-in user.', 'cforms'); ?></td>
				</tr>
                <tr>
                    <td class="bleft"><code>{CurUserFirstName}</code></td>
                    <td class="bright"><?php _e('Inserts the First Name of the currently logged-in user.', 'cforms'); ?></td>
                </tr>
                <tr>
                    <td class="bleft"><code>{CurUserLastName}</code></td>
                    <td class="bright"><?php _e('Inserts the Last Name of the currently logged-in user.', 'cforms'); ?></td>
                </tr>

				<tr>
					<td class="bleft"><em><?php _e('Special:', 'cforms'); ?></em></td>
					<td class="bright"><?php echo sprintf(__('A single %s (period) on a line inserts a blank line.', 'cforms'),'"<code>.</code>"'); ?></td>
				</tr>

				<tr>
					<td class="bright" colspan="2">&nbsp;</td>
				</tr>

				<tr id="tafvariables">
					<td class="bright" colspan="2"><span class="abbr" title="<?php _e('Case sensitive!', 'cforms'); ?>"><strong><?php _e('Predefined variables for Tell-A-Friend forms:', 'cforms'); ?></strong></span></td>
				</tr>
				<tr>
					<td class="bleft"><code>{Permalink}</code></td>
					<td class="bright"><?php _e('Inserts the URL of the WP post/page.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{Author}</code></td>
					<td class="bright"><?php _e('Inserts the Author\'s name (<em>Nickname</em>).', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{Title}</code></td>
					<td class="bright"><?php _e('Inserts the WP post or page title.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{Excerpt}</code></td>
					<td class="bright"><?php _e('Inserts the WP post or page excerpt.', 'cforms'); ?></td>
				</tr>

				<tr>
					<td class="bright" colspan="2">&nbsp;</td>
				</tr>

				<tr>
					<td class="bright" colspan="2">
						<span class="abbr" title="<?php _e('Case sensitive!', 'cforms'); ?>"><strong><?php _e('Custom variables (referencing input fields):', 'cforms'); ?></strong></span>
					</td>
				</tr>
				<tr>
					<td class="bright" colspan="2">
						<?php echo sprintf(__('Alternatively to the cforms predefined variables, you can also reference data of any of your form\'s input fields by one of the 3 ways described below.', 'cforms')); ?>
					</td>
				</tr>
				<tr>
					<td class="bleft"><code>{<em><?php _e('field label', 'cforms'); ?></em>}</code></td>
					<td class="bright"><?php _e('With <em>field label</em> being the <u>exact</u> field label as it is being tracked and sent in the admin email!', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{<em><?php _e('XYZ', 'cforms'); ?></em>}</code></td>
					<td class="bright"><?php _e('In case you\'re using the <u>custom input field NAMES &amp; ID\'s</u>, the reference is the <u>id:</u> of the field.', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><code>{<em>_field<strong>NN</strong></em>}</code></td>
					<td class="bright"><?php _e('With <em>NN</em> being the position of the field on the form configuration page.', 'cforms'); ?></td>
				</tr>

				<tr>
					<td class="ball" colspan="2">
						<?php _e('Example:', 'cforms'); ?><br />
						<?php echo sprintf(__('Suppose this is the input field definition string: %sYour Website%s', 'cforms'),'<span style="padding:0 4px; font-family:monospace; background:#f2f2f2;">','[id:homepage]|http://</span>'); ?><br />
						<?php _e('The corresponding variables would be:', 'cforms'); ?>
						<?php echo sprintf(__('%1$s{Your Website}%2$s , %1$s{homepage}%2$s, or %1$s%3$s%2$s (assuming it is on the 4th position) respectively.', 'cforms'),'<span style="padding:0 4px; font-family:monospace; background:#f2f2f2;">','</span>','{_field4}'); ?>
					</td>
				</tr>
			</table>
			<br />
			<table class="hf" cellspacing="2" border="4" width="75%">
				<tr>
					<td class="bright" style="padding:10px; background:#fdcbaa;" colspan="2"><?php echo '<strong>'.__('Important:','cforms').'</strong> '; _e('If you are using multiple input fields with <strong>the same</strong> recorded field label (you can always check the "Tracking" menu tab for how the fields are stored), e.g:', 'cforms'); ?><br />
<pre style="font-size:11px"><code style="background:none">
<strong>Size</strong>#250gr.#500gr#1kg circa
<strong>Size</strong>#450gr.#700gr#1.2kg circa
<strong>Size</strong>#650gr.#800gr#1.5kg circa
</code></pre>
					<br />

					<?php echo sprintf(__('Results in the first field labeled %1$s to be addressed with %2$s. The second instance of %1$s can be addressed by %3$s, and so on...', 'cforms'),'\'<strong>Size</strong>\'','<code class="codehighlight">{Size}</code>','<code class="codehighlight">{Size__2}</code>'); ?>
					</td>
				</tr>
			</table>
			<br />
			<table class="hf" cellspacing="2" border="4" width="75%">
				<tr>
					<td class="bright" colspan="2"><?php echo sprintf(__('Here is an example for a simple <a href="%s" %s>Admin HTML message</a> <em>(you can copy and paste the below code or change to your liking)</em>:', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#cforms_header_html','onclick="setshow(3)"'); ?></td>
				</tr>

				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('HTML code:', 'cforms'); ?></strong><br />
						<?php echo '<p>&lt;p style="background:#fafafa; text-align:center; font:10px arial"&gt;' . sprintf(__('a form has been submitted on %s, via: %s [IP %s]', 'cforms'),'{Date}','{Page}','{IP}') . '&lt;/p&gt;</p>'; ?>
					</td>
				</tr>
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Output:', 'cforms'); ?></strong><br />
						<?php echo '<p style="background:#fafafa; text-align:center; font:10px arial">' . __('a form has been submitted on June 13, 2007 @ 9:38 pm, via: / [IP 184.153.91.231]', 'cforms') . '</p>'; ?>
					</td>
				</tr>
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Note:', 'cforms'); ?></strong> <?php _e('With this limited message you\'d want to enable the option "Include pre formatted form data table in HTML part"', 'cforms'); ?><br />
					</td>
				</tr>
			</table>
			<br />
			<table class="hf" cellspacing="2" border="4" width="75%">
				<tr>
					<td class="bright" colspan="2"><?php echo sprintf(__('Here is another example for a more detailed <a href="%s" %s>Admin HTML message</a>:', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#cforms_header_html','onclick="setshow(3)"'); ?></td>
				</tr>

				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('HTML code:', 'cforms'); ?></strong><br />
						<?php echo '<p>&lt;p&gt;'.__('{Your Name} just submitted {Form Name}. You can get in touch with him/her via &lt;a href="mailto:{Email}"&gt;{Email}&lt;/a&gt; and might want to check out his/her web page at &lt;a href="{Website}"&gt;{Website}&lt;/a&gt;', 'cforms') . '&lt;/p&gt;</p><p>&lt;p&gt;' .  __('The message is:', 'cforms') . '&lt;br/ &gt;<br />'.__('{Message}', 'cforms') . '&lt;/p&gt;</p>'; ?>
					</td>
				</tr>
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Output:', 'cforms'); ?></strong><br />
						<?php echo '<p>' . __('John Doe just submitted MY NEW FORM. You can get in touch with him/her via <a href="mailto:#">john.doe@doe.com</a> and might want to check out his/her web page at <a href="#">http://website.com</a>', 'cforms') . '</p>'; ?>
						<?php echo '<p>' . __('The message is:', 'cforms') . '<br />'; ?>
						<?php echo  __('Hey there! Just wanted to get in touch. Give me a ring at 555-...', 'cforms') . '</p>'; ?>
					</td>
				</tr>
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Note:', 'cforms'); ?></strong> <?php _e('With this more detailed message you can disable the option "Include pre formatted form data table in HTML part" since you already have all fields covered in the actual message/header.', 'cforms'); ?><br />
					</td>
				</tr>
			</table>
			<br />
			<table class="hf" cellspacing="2" border="4" width="75%">
				<tr>
					<td class="bright" colspan="2"><?php echo sprintf(__('And a final example for a <a href="%s" %s>HTML auto confirmation message</a>:', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#cforms_cmsg_html','onclick="setshow(5)"'); ?></td>
				</tr>

				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('HTML code:', 'cforms'); ?></strong><br />
						<?php echo '<p>&lt;div style="text-align:center; color:#aaa; border-bottom:1px solid #aaa"&gt; &lt;strong&gt;' . __('auto confirmation message', 'cforms') . ', {Date}&lt;/strong&gt; &lt;/div&gt;&lt;br /&gt;</p>'; ?>
						<?php echo '&lt;p&gt;&lt;strong&gt;' . __('Dear {Your Name},', 'cforms') . '&lt;/strong&gt;&lt;/p&gt;<br />'; ?>
						<?php echo '&lt;p&gt;' . __('Thank you for your note!', 'cforms') . '&lt;/p&gt;<br />'; ?>
						<?php echo '&lt;p&gt;' . __('We will get back to you as soon as possible.', 'cforms') . '&lt;/p&gt;<br />'; ?>
					</td>
				</tr>
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Output:', 'cforms'); ?></strong><br />
						<?php echo '<div style="text-align:center; color:#aaa; border-bottom:1px solid #aaa"><strong>' . __('auto confirmation message', 'cforms') . ', June 13, 2007 @ 5:03 pm</strong></div><br />'; ?>
						<?php echo '<p><strong>' . __('Dear John Doe,', 'cforms') . '</strong></p>'; ?>
						<?php echo '<p>' . __('Thank you for your note!', 'cforms') . '</p>'; ?>
						<?php echo '<p>' . __('We will get back to you as soon as possible.', 'cforms') . '</p>'; ?>
					</td>
				</tr>
			</table>
		</div>


		<div class="cflegend op-closed" id="p30" title="<?php _e('Expand/Collapse', 'cforms') ?>">
        	<a id="multipage" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Multi page forms', 'cforms')?>
        </div>

		<div class="cf-content" id="o30">
			<p><?php echo sprintf(__('Multi-page-forms support chaining of several forms and gather user input across all linked forms. Inserting a multi page form is easy, simply insert the %s first form %s of the series into your post or page.', 'cforms'),'<strong>','</strong>'); ?></p>

			<p align="center" style="margin: 20px 0px 20px 10px; float: right; width: 410px;"><img src="<?php echo $cforms_root; ?>/images/example-mp.png"  alt=""/></p>
			<table class="hf" cellspacing="2" border="4">
				<tr>
					<td class="bright" colspan="2"><strong><?php _e('Multi-part/-page form features:', 'cforms'); ?></strong></td>
				</tr>
				<tr>
					<td class="ball" colspan="2"><?php _e('Defining first, next and last form via configuration', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="ball" colspan="2"><?php _e('Overriding "next form" at run-time (dynamically) via my-functions.php', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="ball" colspan="2"><?php _e('Optionally send/suppress partial admin emails on a per form basis', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="ball" colspan="2"><?php _e('A form reset button', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="ball" colspan="2"><?php _e('A form back button', 'cforms'); ?></td>
				</tr>
			</table>

			<table class="hf" cellspacing="2" border="4" style="margin-top:10px;">
				<tr>
					<td class="bright" colspan="2"><strong><?php _e('Example (eg. using 3 forms):', 'cforms'); ?></strong></td>
				</tr>
				<tr>
					<td class="ball"><code><?php _e('form 1,2,3:', 'cforms'); ?></code></td>
					<td class="ball"><?php _e('select main check box to enable as multi-part forms', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="ball"><code><?php _e('form 1:', 'cforms'); ?></code></td>
					<td class="ball"><?php _e('(a) check "Suppress admin email.."', 'cforms'); ?><br /><?php _e('(b) check "This is the first form.."', 'cforms'); ?><br /><?php _e('(c) select "form 2" as next form', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="ball"><code><?php _e('form 2:', 'cforms'); ?></code></td>
					<td class="ball"><?php _e('(a) check "Suppress admin email.."', 'cforms'); ?><br /><?php _e('(b) select "form 3" as next form', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="ball"><code><?php _e('form 3:', 'cforms'); ?></code></td>
					<td class="ball"><?php _e('(a) make sure to not! have "Suppress admin email.." selected', 'cforms'); ?><br /><?php _e('(b) select "last form" to stop further routing', 'cforms'); ?></td>
				</tr>
				<tr>
					<td class="ball" colspan="2"><?php _e('Optionally add Reset &amp; Back buttons where appropriate.', 'cforms'); ?></td>
				</tr>
                <tr>
                    <td class="ball" colspan="2"><?php _e('Further, it makes sense to change "Submit Button" text (to e.g. "Continue") &amp; the "success message" to rather announce the next form.', 'cforms'); ?></td>
                </tr>
		</table>

        <p class="ex"><strong><?php _e('Important Notes:', 'cforms'); ?></strong></p>
        <ul style="margin-top:10px;">
            <li><?php echo sprintf(__('Accessing %1$s {custom variables} %2$s in the final form differs from how you would reference these in individual forms. Use the %1$s mail() %2$s example in my-functions.php to examine the user data array; e.g. %1$s{Email}%2$s would become %1$s{cf_form_Email}%2$s (for the first form of the series).', 'cforms'),'<strong>','</strong>'); ?></li>
            <li><?php echo sprintf(__('%1s File attachments %2s will not be included in the admin email unless the upload fields are on the last form. However, they will be stored and tracked.', 'cforms'),'<strong>','</strong>'); ?></li>
            <li><?php echo sprintf(__('Once the multi page form support is enabled, %1$sAjax is being disabled%2$s for this form.', 'cforms'),'<strong>','</strong>'); ?></li>
        </ul>

		</div>


		<div class="cflegend op-closed" id="p24" title="<?php _e('Expand/Collapse', 'cforms') ?>">
        	<a id="CSS" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Styling Your Forms (CSS theme files)', 'cforms')?>
        </div>

		<div class="cf-content" id="o24">
			<p><?php echo sprintf(__('Please see the <a href="%s">Styling page</a> for theme selection and editing options.', 'cforms'),'?page=' . $plugindir . '/cforms-css.php'); ?></p>
			<p><?php _e('cforms comes with a few theme examples (some of them may require adjustments to work with <strong>your</strong> forms!) but you can of course create your own theme file -based on the default <strong>cforms.css</strong> file- and put it in the <code>/styling</code> directory.', 'cforms'); ?></p>
			<p class="ex"><?php echo sprintf(__('With v8.5+ cforms supports a separate custom user folder to store your tailored CSS, font and image files! Simply create the folder: %s and move your CSS (including <strong>all</strong> images!), font &amp; background image files (CAPTCHA) to it.', 'cforms'),'<strong>/plugins/cforms-custom</strong>'); ?></p>
			<p><?php echo sprintf(__('You might also want to study the <a href="%s">PDF guide on cforms CSS &amp; a web screencast</a> I put together to give you a head start.', 'cforms'),'http://www.deliciousdays.com/cforms-forum?forum=1&amp;topic=428&amp;page=1'); ?></p>
			<p class="ex"><?php _e('Your form <strong>doesn\'t</strong> look like the preview image, or your individual changes don\'t take effect, check your global WP theme CSS! It may overwrite some or many cforms CSS declarations. If you don\'t know how to trouble shoot, take a look at the Firefox extension "Firebug" - an excellent CSS troubleshooting tool!', 'cforms'); ?></p>
		</div>


		<div class="cflegend op-closed" id="p25" title="<?php _e('Expand/Collapse', 'cforms') ?>">
        	<a id="troubles" class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Need more help?', 'cforms')?>
        </div>

		<div class="cf-content" id="o25">
			<p><?php echo sprintf(__('For up-to-date information first check the %sFAQs%s &amp; %scforms forum%s and comment section on the plugin homepage.', 'cforms'),'<a href="http://www.deliciousdays.com/cforms-forum/?forum=3&amp;topic=4&amp;page=1">','</a>','<a href="http://www.deliciousdays.com/cforms-forum">','</a>'); ?></p>
		</div>

	<?php cforms_footer(); ?>
</div>