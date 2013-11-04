<?php
/*
please see cforms.php for more information
*/

### db settings
global $wpdb;
$wpdb->cformssubmissions	= $wpdb->prefix . 'cformssubmissions';
$wpdb->cformsdata       	= $wpdb->prefix . 'cformsdata';

### new global settings container, will eventually be the only one!
$cformsSettings = get_option('cforms_settings');

$plugindir   = $cformsSettings['global']['plugindir'];
$cforms_root = $cformsSettings['global']['cforms_root'];

### check if pre-9.0 update needs to be made
if( $cformsSettings['global']['update'] )
	require_once (dirname(__FILE__) . '/update-pre-9.php');

### SMPT sever configured?
$smtpsettings=explode('$#$',$cformsSettings['global']['cforms_smtp']);

### Check Whether User Can Manage Database
check_access_priv();


### if all data has been erased quit
if ( check_erased() )
	return;

if ( isset($_REQUEST['deletetables']) ) {

	$wpdb->query("DROP TABLE IF EXISTS $wpdb->cformssubmissions");
	$wpdb->query("DROP TABLE IF EXISTS $wpdb->cformsdata");

    $cformsSettings['global']['cforms_database'] = '0';
    update_option('cforms_settings',$cformsSettings);

	?>
	<div id="message" class="updated fade">
		<p>
		<strong><?php echo sprintf (__('cforms tracking tables %s have been deleted.', 'cforms'),'(<code>cformssubmissions</code> &amp; <code>cformsdata</code>)') ?></strong>
			<br />
			<?php _e('Please backup/clean-up your upload directory, chances are that when you turn tracking back on, existing (older) attachments may be <u>overwritten</u>!') ?>
			<br />
			<small><?php _e('(only of course, if your form includes a file upload field)') ?></small>
		</p>
	</div>
	<?php

} else if( isset($_REQUEST['cforms_rsskeysnew']) ) {

	### new RSS key computed
	$cformsSettings['global']['cforms_rsskeyall'] = md5(rand());
	update_option('cforms_settings',$cformsSettings);

} else if( isset($_REQUEST['restoreallcformsdata']) )
	require_once(dirname(__FILE__) . '/lib_options_up.php');

// Update Settings
if( isset($_REQUEST['SubmitOptions']) ) {

    $cformsSettings['global']['cforms_show_quicktag'] = $_REQUEST['cforms_show_quicktag']?'1':'0';
	$cformsSettings['global']['cforms_sec_qa'] = 		magic($_REQUEST['cforms_sec_qa']);
	$cformsSettings['global']['cforms_codeerr'] = 		magic($_REQUEST['cforms_codeerr']);
	$cformsSettings['global']['cforms_database'] = 		$_REQUEST['cforms_database']?'1':'0';
	$cformsSettings['global']['cforms_showdashboard'] = $_REQUEST['cforms_showdashboard']?'1':'0';
	$cformsSettings['global']['cforms_datepicker'] = 	$_REQUEST['cforms_datepicker']?'1':'0';
	$cformsSettings['global']['cforms_dp_date'] = 		magic($_REQUEST['cforms_dp_date']);
	$cformsSettings['global']['cforms_dp_days'] = 		magic($_REQUEST['cforms_dp_days']);
	$cformsSettings['global']['cforms_dp_start'] = 		$_REQUEST['cforms_dp_start']==''?'0':$_REQUEST['cforms_dp_start'];
	$cformsSettings['global']['cforms_dp_months'] = 	magic($_REQUEST['cforms_dp_months']);

	$nav=array();
	$nav[0]=magic($_REQUEST['cforms_dp_prevY']);
	$nav[1]=magic($_REQUEST['cforms_dp_prevM']);
	$nav[2]=magic($_REQUEST['cforms_dp_nextY']);
	$nav[3]=magic($_REQUEST['cforms_dp_nextM']);
	$nav[4]=magic($_REQUEST['cforms_dp_close']);
	$nav[5]=magic($_REQUEST['cforms_dp_choose']);
	$cformsSettings['global']['cforms_dp_nav'] = $nav;

 	$cformsSettings['global']['cforms_inexclude']['ex'] = '';
  if( $_REQUEST['cforms_inc-or-ex']=='exclude' )
  	$cformsSettings['global']['cforms_inexclude']['ex'] = '1';

 	$cformsSettings['global']['cforms_inexclude']['ids'] = $_REQUEST['cforms_include'];

	$cformsSettings['global']['cforms_commentsuccess'] =magic($_REQUEST['cforms_commentsuccess']);
	$cformsSettings['global']['cforms_commentWait'] =  	$_REQUEST['cforms_commentWait'];
	$cformsSettings['global']['cforms_commentParent'] =	$_REQUEST['cforms_commentParent'];
	$cformsSettings['global']['cforms_commentHTML'] =	magic($_REQUEST['cforms_commentHTML']);
	$cformsSettings['global']['cforms_commentInMod'] =	magic($_REQUEST['cforms_commentInMod']);
	$cformsSettings['global']['cforms_avatar'] =	   	$_REQUEST['cforms_avatar'];

	$cformsSettings['global']['cforms_crlf']['h'] =	   	$_REQUEST['cforms_crlfH']?'1':'0';
	$cformsSettings['global']['cforms_crlf']['b'] =	   	$_REQUEST['cforms_crlf']?'1':'0';

	$smtpsettings[0] = $_REQUEST['cforms_smtp_onoff']?'1':'0';
	$smtpsettings[1] = $_REQUEST['cforms_smtp_host'];
	$smtpsettings[2] = magic($_REQUEST['cforms_smtp_user']);
	if ( !preg_match('/^\*+$/',$_REQUEST['cforms_smtp_pass']) ) {
		$smtpsettings[3] = magic($_REQUEST['cforms_smtp_pass']);
		}
	$smtpsettings[4] = $_REQUEST['cforms_smtp_ssltls'];
	$smtpsettings[5] = $_REQUEST['cforms_smtp_port'];
    $smtpsettings[6] = $_REQUEST['cforms_smtp_pop']?'1':'0';
    $smtpsettings[7] = $_REQUEST['cforms_smtp_pop_host'];
    $smtpsettings[8] = $_REQUEST['cforms_smtp_pop_port'];
    $smtpsettings[9] = $_REQUEST['cforms_smtp_pop_ln'];
	if ( !preg_match('/^\*+$/',$_REQUEST['cforms_smtp_pop_pw']) ) {
		$smtpsettings[10] = magic($_REQUEST['cforms_smtp_pop_pw']);
		}

	$cformsSettings['global']['cforms_smtp'] = implode('$#$',$smtpsettings) ;

	$cformsSettings['global']['cforms_upload_err1'] = magic($_REQUEST['cforms_upload_err1']);
	$cformsSettings['global']['cforms_upload_err2'] = magic($_REQUEST['cforms_upload_err2']);
	$cformsSettings['global']['cforms_upload_err3'] = magic($_REQUEST['cforms_upload_err3']);
	$cformsSettings['global']['cforms_upload_err4'] = magic($_REQUEST['cforms_upload_err4']);
	$cformsSettings['global']['cforms_upload_err5'] = magic($_REQUEST['cforms_upload_err5']);

	$cap = array();
	$cap['i'] = $_REQUEST['cforms_cap_i'];
	$cap['w'] = $_REQUEST['cforms_cap_w'];
	$cap['h'] = $_REQUEST['cforms_cap_h'];
	$cap['c'] = $_REQUEST['cforms_cap_c'];
	$cap['l'] = $_REQUEST['cforms_cap_l'];
	$cap['bg']= $_REQUEST['cforms_cap_b'];
	$cap['f'] = $_REQUEST['cforms_cap_f'];
	$cap['fo']= $_REQUEST['cforms_cap_fo'];
	$cap['foqa']= $_REQUEST['cforms_cap_foqa'];
	$cap['f1']= $_REQUEST['cforms_cap_f1'];
	$cap['f2']= $_REQUEST['cforms_cap_f2'];
	$cap['a1']= $_REQUEST['cforms_cap_a1'];
	$cap['a2']= $_REQUEST['cforms_cap_a2'];
	$cap['c1']= $_REQUEST['cforms_cap_c1'];
	$cap['c2']= $_REQUEST['cforms_cap_c2'];
	$cap['ac']= $_REQUEST['cforms_cap_ac'];

    ###	update new settings container
	$cformsSettings['global']['cforms_show_quicktag_js'] = $_REQUEST['cforms_show_quicktag_js']?true:false;
	$cformsSettings['global']['cforms_rssall'] = $_REQUEST['cforms_rss']?true:false;
	$cformsSettings['global']['cforms_rssall_count'] = $_REQUEST['cforms_rsscount'];
    $cformsSettings['global']['cforms_captcha_def'] = $cap;

    update_option('cforms_settings',$cformsSettings);

	// Setup database tables ?
	if ( isset($_REQUEST['cforms_database']) && $_REQUEST['cforms_database_new']=='true' ) {

		if ( $wpdb->get_var("show tables like '$wpdb->cformssubmissions'") <> $wpdb->cformssubmissions ){

			$sql = "CREATE TABLE " . $wpdb->cformssubmissions . " (
					  id int(11) unsigned auto_increment,
					  form_id varchar(3) default '',
					  sub_date timestamp,
					  email varchar(40) default '',
					  ip varchar(15) default '',
					  PRIMARY KEY  (id) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
			dbDelta($sql);

			$sql = "CREATE TABLE " . $wpdb->cformsdata . " (
					  f_id int(11) unsigned auto_increment primary key,
					  sub_id int(11) unsigned NOT NULL,
					  field_name varchar(100) NOT NULL default '',
					  field_val text) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
			dbDelta($sql);

            ###check
	        if( $wpdb->get_var("show tables like '$wpdb->cformssubmissions'") <> $wpdb->cformssubmissions ) {
	            ?>
	            <div id="message" class="updated fade">
	                <p><strong><?php echo sprintf(__('ERROR: cforms tracking tables %s could not be created.', 'cforms'),'(<code>cformssubmissions</code> &amp; <code>cformsdata</code>)') ?></strong></p>
	            </div>
	            <?php
			    $cformsSettings['global']['cforms_database'] = '0';
			    update_option('cforms_settings',$cformsSettings);
            }else{
	            ?>
	            <div id="message" class="updated fade">
	                <p><strong><?php echo sprintf(__('cforms tracking tables %s have been created.', 'cforms'),'(<code>cformssubmissions</code> &amp; <code>cformsdata</code>)') ?></strong></p>
	            </div>
	            <?php
            }

		} else {

			$sets = $wpdb->get_var("SELECT count(id) FROM $wpdb->cformssubmissions");
			?>
			<div id="message" class="updated fade">
				<p><strong><?php echo sprintf(__('Found existing cforms tracking tables with %s records!', 'cforms'),$sets) ?></strong></p>
			</div>
			<?php
		}
	}

}

### check for abspath.php
abspath_check();

?>

<div class="wrap" id="top">
    <div id="icon-cforms-global" class="icon32"><br/></div><h2><?php _e('Global Settings','cforms')?></h2>

    <?php
    ###debug "easter egg"
    if ( isset($_POST['showinfo']) ){

        echo '<h2>'.__('Debug Info (all major setting groups)', 'cforms').'</h2><br/><pre style="font-size:11px;background-color:#F5F5F5;">';
        echo print_r(array_keys($cformsSettings),1)."</pre>";
        echo '<h2>'.__('Debug Info (all cforms settings)', 'cforms').'</h2><br/><pre style="font-size:11px;background-color:#F5F5F5;">'.print_r($cformsSettings,1)."</pre>";

        cforms_footer();
        echo '</div>';
        die();
    }
    ?>
    <p><?php _e('All settings and configuration options on this page apply to all forms.', 'cforms') ?></p>

	<form enctype="multipart/form-data" id="cformsdata" name="mainform" method="post" action="">
		<input type="hidden" name="cforms_database_new" value="<?php if($cformsSettings['global']['cforms_database']=="0") echo 'true'; ?>"/>

		<fieldset id="wpcomment" class="cformsoptions">
			<div class="cflegend op-closed" id="p28" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('WP Comment Feature Settings', 'cforms')?>
            </div>

			<div class="cf-content" id="o28">
				<p><?php _e('Find below the additional settings for cforms WP comment feature.', 'cforms') ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL"><label for="cforms_commentsuccess"><strong><?php _e('Comment Success Message', 'cforms'); ?></strong></label></td>
					<td class="obR"><table><tr><td><textarea class="resizable" rows="80px" cols="200px" name="cforms_commentsuccess" id="cforms_commentsuccess"><?php echo stripslashes(htmlspecialchars($cformsSettings['global']['cforms_commentsuccess'])); ?></textarea></td></tr></table></td>
				</tr>

				<tr class="obSEP"><td colspan="2"></td></tr>

				<tr class="ob space15">
					<td class="obL"></td><td class="obR"><strong><?php _e('Ajax Settings', 'cforms'); ?></strong></td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_commentWait"><strong><?php _e('Wait time for new comments (in seconds)', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_commentWait" name="cforms_commentWait" value="<?php echo stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_commentWait'] )); ?>"/></td>
				</tr>
				<tr class="ob space15">
					<td class="obL"><label for="cforms_commentParent"><strong><?php _e('Parent Comment Container', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_commentParent" name="cforms_commentParent" value="<?php echo stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_commentParent'] )); ?>"/> <a class="infobutton" href="#" name="it8"><?php _e('Note &raquo;', 'cforms'); ?></a></td>
				</tr>
				<tr id="it8" class="infotxt"><td>&nbsp;</td><td class="ex"><?php _e('The HTML <strong>element ID</strong> of the parent element containing<br />all comments, for example:', 'cforms'); ?><br />
						<code>
						[...]&lt;/h2&gt;<br />
						&lt;ol id="<u style="color:#f37891">mycommentlist</u>"&gt;<br />
						&nbsp;&nbsp;&lt;li id="comment-126"&gt;[...]<br />
						</code></td>
                </tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_commentInMod"><strong><?php _e('Comment in moderation', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_commentInMod" name="cforms_commentInMod" value="<?php echo stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_commentInMod'] )); ?>"/></td>
				</tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_commentHTML"><strong><?php _e('New comment HTML template', 'cforms'); ?></strong></label></td>
					<td class="obR" style="padding-bottom:10px;">
						<table><tr><td><textarea class="resizable" rows="80px" cols="200px" name="cforms_commentHTML" id="cforms_commentHTML"><?php echo stripslashes(htmlspecialchars($cformsSettings['global']['cforms_commentHTML'])); ?></textarea><a class="infobutton" href="#" name="it9"><?php _e('Supported Variables &raquo;', 'cforms'); ?></a>&nbsp;&nbsp;&nbsp;<a class="infobutton" href="#" name="it9b"><?php _e('Default Template &raquo;', 'cforms'); ?></a></td></tr></table>
					</td>
                </tr>
				<tr id="it9" class="infotxt"><td>&nbsp;</td><td class="ex">
						<table class="hf">
							<tr><td class="bleft">{moderation}</td><td class="bright"><em><?php _e('Comment in moderation', 'cforms'); ?></em></td></tr>
							<tr><td class="bleft">{id}</td><td class="bright"><?php _e('New comment ID', 'cforms'); ?></td></tr>
							<tr><td class="bleft">{usercomment}</td><td class="bright"><?php _e('Comment Text', 'cforms'); ?></td></tr>
							<tr><td class="bleft">{author}</td><td class="bright"><?php _e('Comment Author', 'cforms'); ?></td></tr>
							<tr><td class="bleft">{url}</td><td class="bright"><?php _e('The author\'s website', 'cforms'); ?></td></tr>
							<tr><td class="bleft">{date}</td><td class="bright"><?php _e('Current date.', 'cforms'); ?></td></tr>
							<tr><td class="bleft">{time}</td><td class="bright"><?php _e('Current time.', 'cforms'); ?></td></tr>
							<tr><td class="bleft">{avatar}</td><td class="bright"><?php _e('User avatar.', 'cforms'); ?></td></tr>
						</table>
                </td></tr>
				<tr id="it9b" class="infotxt"><td>&nbsp;</td><td class="ex">
<code>
&lt;li class="alt" id="comment-{id}"&gt;<br />
{avatar}<br />
&lt;cite&gt;&lt;a href="{url}" rel="external nofollow"&gt;{author}&lt;/a&gt;&lt;/cite&gt; Says:<br />
{moderation}<br />
&lt;br/&gt;<br />
&lt;small class="commentmetadata"&gt;<br />
&lt;a href="#comment-{id}"&gt;{date}, {time}&lt;/a&gt;<br />
&lt;/small&gt;<br />
&lt;p&gt;{usercomment}&lt;/p&gt;<br />
&lt;/li&gt;
</code>
                </td></tr>

				<tr class="ob space15">
					<td class="obL">&nbsp;</td><td class="obR"><strong><?php _e('Avatar Settings', 'cforms'); ?></strong></td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_avatar"><strong><?php _e('Size (in pixel)', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_avatar" name="cforms_avatar" value="<?php echo stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_avatar'] )); ?>"/></td>
				</tr>
				</table>
			</div>
		</fieldset>

		<fieldset id="inandexclude" class="cformsoptions">
			<div class="cflegend op-closed" id="p27" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Include cforms header data only on specific pages', 'cforms')?>
            </div>

			<div class="cf-content" id="o27">
				<p><?php _e('Specify the ID(s) of <strong>pages or posts</strong> separated by comma on which you\'d like to show or not show cforms. The cforms header will only be included specifically on those pages, helping to maintain all other pages neat.', 'cforms') ?></p>

				<table class="form-table">
				<tr class="ob inexclude">
					<td class="obL"><label for="cforms_include"><strong><?php _e('Page / Post ID(s)', 'cforms'); $ex = ($cformsSettings['global']['cforms_inexclude']['ex']=='1'); ?></strong></label></td>
					<td class="obR">
              <input class="allchk"<?php echo !$ex?' checked="checked"':''; ?> type="radio" id="include" value="include" name="cforms_inc-or-ex"/><label for="include"><?php _e('include', 'cforms') ?></label>  <input class="allchk"<?php echo $ex?' checked="checked"':''; ?> type="radio" id="exclude" value="exclude" name="cforms_inc-or-ex"/><label for="exclude"><?php _e('exclude', 'cforms') ?></label><br />
              <input type="text" id="cforms_include" name="cforms_include" value="<?php echo stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_inexclude']['ids'] )); ?>"/><br />
              <?php _e('Leave empty to include cforms header files throughout your blog', 'cforms') ?>
          </td>
				</tr>
				</table>
			</div>
		</fieldset>

		<fieldset id="popupdate" class="cformsoptions">
			<div class="cflegend op-closed" id="p9" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Popup Date Picker', 'cforms')?>
            </div>

			<div class="cf-content" id="o9">
				<p><?php echo sprintf(__('If you\'d like to offer a Javascript based date picker for more convenient date entry, enable this feature here. This will add a <strong>new input field</strong> for you to add to your form. See <a href="%s" %s>Help!</a> for more info and <strong>date formats</strong>.', 'cforms'),'?page='.$plugindir.'/cforms-help.php#datepicker','onclick="setshow(19)"') ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_datepicker" name="cforms_datepicker" <?php if($cformsSettings['global']['cforms_datepicker']=="1") echo "checked=\"checked\""; ?>/><label for="cforms_datepicker"><strong><?php _e('Enable Javascript date picker', 'cforms') ?></strong></label> ** <a class="infobutton" href="#" name="it10"><?php _e('Note &raquo;', 'cforms'); ?></a></td>
				</tr>
				<tr id="it10" class="infotxt"><td>&nbsp;</td><td class="ex"><?php _e('Note that turning on this feature will result in loading an additional Javascript file to support the date picker.', 'cforms') ?></td></tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_dp_date"><strong><?php _e('Date Format', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_date" name="cforms_dp_date" value="<?php echo stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_dp_date'] )); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_dp_days"><strong><?php _e('Days (Columns)', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_days" name="cforms_dp_days" value="<?php echo stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_dp_days'] )); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_dp_months"><strong><?php _e('Months', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_months" name="cforms_dp_months" value="<?php echo stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_dp_months'] )); ?>"/></td>
				</tr>
				<tr class="ob">
					<?php $nav = $cformsSettings['global']['cforms_dp_nav']; ?>
					<td class="obL"><label for="cforms_dp_prevY"><strong><?php _e('Previous Year', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_prevY" name="cforms_dp_prevY" value="<?php echo stripslashes(htmlspecialchars( $nav[0] )); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_dp_prevM"><strong><?php _e('Previous Month', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_prevM" name="cforms_dp_prevM" value="<?php echo stripslashes(htmlspecialchars( $nav[1] )); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_dp_nextY"><strong><?php _e('Next Year', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_nextY" name="cforms_dp_nextY" value="<?php echo stripslashes(htmlspecialchars( $nav[2] )); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_dp_nextM"><strong><?php _e('Next Month', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_nextM" name="cforms_dp_nextM" value="<?php echo stripslashes(htmlspecialchars( $nav[3] )); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_dp_close"><strong><?php _e('Close', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_close" name="cforms_dp_close" value="<?php echo stripslashes(htmlspecialchars( $nav[4] )); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_dp_choose"><strong><?php _e('Choose Date', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_choose" name="cforms_dp_choose" value="<?php echo stripslashes(htmlspecialchars( $nav[5] )); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_dp_start"><strong><?php _e('Week start day', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_dp_start" name="cforms_dp_start" value="<?php echo stripslashes(htmlspecialchars( $cformsSettings['global']['cforms_dp_start'] )); ?>"/> <?php _e('0=Sunday, 1=Monday, etc.', 'cforms'); ?></td>
				</tr>
				</table>
			</div>
		</fieldset>


		<fieldset id="smtp" class="cformsoptions">
			<div class="cflegend op-closed" id="p10" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Mail Server Settings', 'cforms')?>
            </div>

			<div class="cf-content" id="o10">

				<p><?php _e('cforms produces RFC compliant emails with CRLF (carriage-return/line-feed) as line separators. If your mail server adds additional line breaks to the email, you may want to try and turn on the below option.', 'cforms') ?>
				<table class="form-table">
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_crlfH" name="cforms_crlfH" <?php if($cformsSettings['global']['cforms_crlf']['h']=="1") echo "checked=\"checked\""; ?>/><label for="cforms_crlfH"><?php echo sprintf(__('Separate lines in email %sheader%s with LF only (CR suppressed)', 'cforms'),'<strong>','</strong>') ?></label></td>
				</tr>
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_crlf" name="cforms_crlf" <?php if($cformsSettings['global']['cforms_crlf']['b']=="1") echo "checked=\"checked\""; ?>/><label for="cforms_crlf"><?php echo sprintf(__('Separate lines in email %sbody%s with LF only (CR suppressed)', 'cforms'),'<strong>','</strong>') ?></label></td>
				</tr>
				<tr class="obSEP"><td colspan="2"></td></tr>
				</table>

				<p><img style="vertical-align:middle;margin-right:10px;" src="<?php echo $cforms_root; ?>/images/phpmailer.png" alt="phpmailerV2"/> <?php _e('In a normal WP environment you do not need to configure these settings!', 'cforms') ?>
				<?php _e('In case your web hosting provider doesn\'t support the <strong>native PHP mail()</strong> command feel free to configure <strong>cforms</strong> to utilize an external <strong>SMTP mail server</strong> to deliver the emails.', 'cforms') ?></p>
				<?php
				$userconfirm = $cformsSettings['global']['cforms_confirmerr'];
				if ( $smtpsettings[0]=='1' && $smtpsettings[4]<>'' && ($userconfirm&32)==0 ){
					if ( isset($_GET['cf_confirm']) && $_GET['cf_confirm']=='confirm32' ){
						$cformsSettings['global']['cforms_confirmerr'] = $userconfirm|32;
						update_option('cforms_settings',$cformsSettings);
						echo '<div id="message32" class="updated fade"><p>ok</p></div>';
                    } else {
						$text = '<strong>'.__('Important:','cforms').'</strong> '.__('If you require SSL / TLS make sure your webserver/PHP environment permits it! If in doubt, check with your web hosting company regarding <strong>openssl</strong> support.', 'cforms');
						echo '<div id="message32" class="updated fade"><p>'.$text.'</p><p><a href="?page='.$plugindir.'/cforms-global-settings.php&cf_confirm=confirm32" class="rm_button allbuttons">'.__('Remove Message','cforms').'</a></p></div>';
					}
				}
				?>

				<table class="form-table">
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_smtp_onoff" name="cforms_smtp_onoff" <?php if($smtpsettings[0]=="1") echo "checked=\"checked\""; ?>/><label for="cforms_smtp_onoff"><strong><?php _e('Enable an external SMTP server', 'cforms') ?></strong></label> ** <a class="infobutton" href="#" name="it11"><?php _e('Note &raquo;', 'cforms'); ?></a></td>
				</tr>
				<tr id="it11" class="infotxt"><td>&nbsp;</td><td class="ex"><?php echo sprintf(__('To avoid additional sources of error, cformsII v6.4 and beyond includes the PHPmailer 2.0 scripts, now <strong>supporting</strong> both <strong>SSL</strong> and <strong>TLS</strong> for authentication.','cforms')); ?></td></tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_smtp_host"><strong><?php _e('SMTP server address', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_smtp_host" name="cforms_smtp_host" value="<?php echo stripslashes(htmlspecialchars($smtpsettings[1])); ?>"/></td>
				</tr>
				<tr class="ob space15">
					<td class="obL"><label for="cforms_smtp_ssl"><strong><?php _e('Secure Connection', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<input type="radio" class="allchk" id="cforms_smtp_none" value="" name="cforms_smtp_ssltls" <?php echo ($smtpsettings[4]=='')?'checked="checked"':''; ?>/><label for="cforms_smtp_none"><strong><?php _e('No', 'cforms'); ?></strong></label><br />
						<input type="radio" class="allchk" id="cforms_smtp_ssl" value="ssl" name="cforms_smtp_ssltls" <?php echo ($smtpsettings[4]=='ssl')?'checked="checked"':''; ?>/><label for="cforms_smtp_ssl"><strong><?php _e('SSL (e.g. gmail)', 'cforms'); ?></strong></label><br />
						<input type="radio" class="allchk" id="cforms_smtp_tls" value="tls" name="cforms_smtp_ssltls" <?php echo ($smtpsettings[4]=='tls')?'checked="checked"':''; ?>/><label for="cforms_smtp_tls"><strong><?php _e('TLS', 'cforms'); ?></strong></label>
					</td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_smtp_port"><strong><?php _e('Port', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_smtp_port" name="cforms_smtp_port" value="<?php echo stripslashes(htmlspecialchars($smtpsettings[5])); ?>"/> <?php _e('Usually 465 (e.g. gmail) or 587', 'cforms'); ?></td>
				</tr>
				<tr class="ob space15">
					<td class="obL">&nbsp;</td>
					<td class="obR"><strong><?php _e('SMTP Authentication (leave blank if not needed!):', 'cforms'); ?></strong></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_smtp_user"><strong><?php _e('Username', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_smtp_user" name="cforms_smtp_user" value="<?php echo stripslashes(htmlspecialchars($smtpsettings[2])); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_smtp_pass"><strong><?php _e('Password', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_smtp_pass" name="cforms_smtp_pass" value="<?php echo str_repeat('*',strlen($smtpsettings[3])); ?>"/></td>
				</tr>

				<tr class="obSEP"><td colspan="2"></td></tr>

				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_smtp_pop" name="cforms_smtp_pop" value="1" <?php if($smtpsettings[6]=="1") echo "checked=\"checked\""; ?>/><label for="cforms_smtp_pop"><strong><?php _e('POP before SMTP', 'cforms') ?></strong></label></td>
				</tr>

				<?php if( $smtpsettings[6]=="1" ): ?>
				<tr class="ob">
					<td class="obL"><label for="cforms_smtp_pop_host"><strong><?php _e('POP server address', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_smtp_pop_host" name="cforms_smtp_pop_host" value="<?php echo stripslashes(htmlspecialchars($smtpsettings[7])); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_smtp_pop_port"><strong><?php _e('POP Port', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_smtp_pop_port" name="cforms_smtp_pop_port" value="<?php echo stripslashes(htmlspecialchars($smtpsettings[8])); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_smtp_pop_ln"><strong><?php _e('POP Login', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_smtp_pop_ln" name="cforms_smtp_pop_ln" value="<?php echo stripslashes(htmlspecialchars($smtpsettings[9])); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_smtp_pop_pw"><strong><?php _e('POP Password', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_smtp_pop_pw" name="cforms_smtp_pop_pw" value="<?php echo str_repeat('*',strlen($smtpsettings[10])); ?>"/></td>
				</tr>
                <?php endif; ?>
				</table>
			</div>
		</fieldset>


		<fieldset id="upload" class="cformsoptions">
			<div class="cflegend op-closed" id="p11" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Global File Upload Settings', 'cforms')?>
            </div>

			<div class="cf-content" id="o11">
				<p>
					<?php echo sprintf(__('Configure and double-check these settings in case you are adding a "<code>File Upload Box</code>" to your form (also see the <a href="%s" %s>Help!</a> for further information).', 'cforms'),'?page='.$plugindir.'/cforms-help.php#upload','onclick="setshow(19)"'); ?>
					<?php echo sprintf(__('Form specific settings (directory path etc.) have been moved to <a href="%s" %s>here</a>.', 'cforms'),'?page='.$plugindir.'/cforms-options.php#fileupload','onclick="setshow(0)"'); ?>
				</p>

				<p class="ex">
					<?php _e('Also, note that by adding a <em>File Upload Box</em> to your form, the Ajax (if enabled) submission method will (automatically) <strong>gracefully degrade</strong> to the standard method, due to general HTML limitations.', 'cforms') ?>
					<?php _e('Below, error messages shown in case something goes awry:', 'cforms') ?>
				</p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL"><label for="cforms_upload_err5"><strong><?php _e('File type not allowed', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<table><tr><td><textarea rows="80px" cols="280px" class="errmsgbox resizable" name="cforms_upload_err5" id="cforms_upload_err5" ><?php echo stripslashes(htmlspecialchars($cformsSettings['global']['cforms_upload_err5'])); ?></textarea></td></tr></table>
					</td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_upload_err1"><strong><?php _e('Generic (unknown) error', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<table><tr><td><textarea rows="80px" cols="280px" class="errmsgbox resizable" name="cforms_upload_err1" id="cforms_upload_err1" ><?php echo stripslashes(htmlspecialchars($cformsSettings['global']['cforms_upload_err1'])); ?></textarea></td></tr></table>
					</td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_upload_err2"><strong><?php _e('File is empty', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<table><tr><td><textarea  rows="80px" cols="280px" class="errmsgbox resizable" name="cforms_upload_err2" id="cforms_upload_err2" ><?php echo stripslashes(htmlspecialchars($cformsSettings['global']['cforms_upload_err2'])); ?></textarea></td></tr></table>
					</td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_upload_err3"><strong><?php _e('File size too big', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<table><tr><td><textarea rows="80px" cols="280px" class="errmsgbox resizable" name="cforms_upload_err3" id="cforms_upload_err3" ><?php echo stripslashes(htmlspecialchars($cformsSettings['global']['cforms_upload_err3'])); ?></textarea></td></tr></table>
					</td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_upload_err4"><strong><?php _e('Error during upload', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<table><tr><td><textarea rows="80px" cols="280px" class="errmsgbox resizable" name="cforms_upload_err4" id="cforms_upload_err4" ><?php echo stripslashes(htmlspecialchars($cformsSettings['global']['cforms_upload_err4'])); ?></textarea></td></tr></table>
					</td>
				</tr>
				</table>
			</div>
		</fieldset>


		<fieldset id="wpeditor" class="cformsoptions">
			<div class="cflegend op-closed" id="p12" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('WP Editor Button support', 'cforms')?>
            </div>

			<div class="cf-content" id="o12">
				<p><?php _e('If you would like to use editor buttons to insert your cforms please enable them below.', 'cforms') ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL"><img src="<?php echo $cforms_root; ?>/images/button.gif" alt=""/></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_show_quicktag" name="cforms_show_quicktag" <?php if($cformsSettings['global']['cforms_show_quicktag']=="1") echo "checked=\"checked\""; ?>/> <label for="cforms_show_quicktag"><strong><?php _e('Enable TinyMCE', 'cforms') ?></strong> <?php _e('&amp; Code editor buttons', 'cforms') ?></label></td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_show_quicktag_js"><strong><?php _e('Fix TinyMCE error', 'cforms'); ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_show_quicktag_js" name="cforms_show_quicktag_js" <?php if( $cformsSettings['global']['cforms_show_quicktag_js']==true) echo "checked=\"checked\""; ?>/> <?php _e('Select in case you experience a TinyMCE editor error caused by other plugins.', 'cforms') ?></td>
				</tr>
				</table>
			</div>
		</fieldset>

		<fieldset id="captcha" class="cformsoptions">
			<div class="cflegend op-closed" id="p26" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('CAPTCHA Image Settings', 'cforms')?>
            </div>

			<div class="cf-content" id="o26">
				<p><?php _e('Below you can find a few switches and options to change the default look of the captcha image. Feel free to upload your own backgrounds and fonts to the respective directories (<code>cforms/captchabg/</code> &amp; <code>cforms/captchafonts/</code> or your custom folder: <code>/plugins/cforms-custom/</code> ).', 'cforms') ?></p>

				<?php
					$cap = $cformsSettings['global']['cforms_captcha_def'];
					$h = prep2( $cap['h'],25 );
					$w = prep2( $cap['w'],115 );
					$c = prep2( $cap['c'],'000066' );
					$l = prep2( $cap['l'],'000066' );
					$f = prep2( $cap['f'],'font4.ttf' );
					$a1 = prep2( $cap['a1'],-12 );
					$a2 = prep2( $cap['a2'],12 );
					$f1 = prep2( $cap['f1'],17 );
					$f2 = prep2( $cap['f2'],19 );
					$bg = prep2( $cap['bg'],'1.gif' );
					$c1 = prep2( $cap['c1'],4 );
					$c2 = prep2( $cap['c2'],5 );
					$i  = prep2( $cap['i'],'i' );
					$ac = prep2( $cap['ac'],'abcdefghijkmnpqrstuvwxyz23456789' );

					$img = "&amp;c1={$c1}&amp;c2={$c2}&amp;ac={$ac}&amp;i={$i}&amp;w={$w}&amp;h={$h}&amp;c={$c}&amp;l={$l}&amp;f={$f}&amp;a1={$a1}&amp;a2={$a2}&amp;f1={$f1}&amp;f2={$f2}&amp;b={$bg}";

					$fonts = '<select name="cforms_cap_f" id="cforms_cap_f">'.cf_get_files('captchafonts',$f,'ttf').'</select>';
					$backgrounds = '<select name="cforms_cap_b" id="cforms_cap_b">'.cf_get_files('captchabg',$bg,'gif').'</select>';

				?>

				<div style="position:absolute; z-index:9999;">
				<div id="mini" onmousedown="coreXY('mini',event)" style="top:0px; left:10px; display:none; margin-left:5%;">
					<div class="north"><span id="mHEX">FFFFFF</span><div onmousedown="$cfS('mini').display='none';">x</div></div>
					<div class="south" id="mSpec" style="HEIGHT: 128px; WIDTH: 128px;" onmousedown="coreXY('mCur',event)">
						<div id="mCur" style="TOP: 86px; LEFT: 68px;"></div>
						<img src="<?php echo $cforms_root; ?>/images/circle.png" onmousedown="return false;" alt=""/>
						<img src="<?php echo $cforms_root; ?>/images/resize.gif" id="mSize" onmousedown="coreXY('mSize',event); return false;" alt=""/>
					</div>
				</div>
				</div>

				<table class="form-table">
				<tr class="ob">
					<td class="obL"><strong><?php _e('Preview Image', 'cforms') ?></strong><br /><span id="pnote" style="display:none; color:red;"><?php _e('Don\'t forget to save your changes!', 'cforms'); ?></span></td>
					<td class="obR" id="adminCaptcha">
                        <a title="<?php _e('Reload Captcha Image', 'cforms'); ?>" href="javascript:resetAdminCaptcha('<?php echo $cforms_root; ?>')"><?php _e('Reload Captcha Image', 'cforms'); ?> &raquo;</a>
					</td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_cap_fo"><strong><?php _e('Force display', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_cap_fo" name="cforms_cap_fo" value="1" <?php if($cap['fo']) echo "checked=\"checked\""; ?>/><label for="cforms_cap_fo"><?php _e('Force CAPTCHA display for logged in users', 'cforms') ?></label></td>
				</tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_cap_w"><strong><?php _e('Width', 'cforms') ?></strong></label></td>
					<td class="obR">
						<input class="cap" type="text" id="cforms_cap_w" name="cforms_cap_w" value="<?php echo $w; ?>"/>
						<label for="cforms_cap_h" class="second-l"><strong><?php _e('Height', 'cforms') ?></strong></label><input class="cap" type="text" id="cforms_cap_h" name="cforms_cap_h" value="<?php echo $h; ?>"/>
					</td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="inputID1"><strong><?php _e('Border Color', 'cforms') ?></strong></label></td>
					<td class="obR">
						<input class="cap" type="text" id="inputID1" name="cforms_cap_l" onclick="javascript:currentEL=1;" value="<?php echo $l; ?>"/><input type="button" name="col-border" class="colorswatch" style="background-color:#<?php echo $l; ?>" id="plugID1" onclick="this.blur(); currentEL=1; $cfS('mini').display='block';"/>
					</td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_cap_b"><strong><?php _e('Background Image', 'cforms') ?></strong></label></td>
					<td class="obR">
						<?php echo $backgrounds; ?>
					</td>
				</tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_cap_f"><strong><?php _e('Font Type', 'cforms') ?></strong></label></td>
					<td class="obR">
						<?php echo $fonts; ?>
					</td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_cap_f1"><strong><?php _e('Min Size', 'cforms') ?></strong></label></td>
					<td class="obR">
						<input class="cap" type="text" id="cforms_cap_f1" name="cforms_cap_f1" value="<?php echo $f1; ?>"/>
						<label for="cforms_cap_f2" class="second-l"><strong><?php _e('Max Size', 'cforms') ?></strong></label><input class="cap" type="text" id="cforms_cap_f2" name="cforms_cap_f2" value="<?php echo $f2; ?>"/>
					</td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_cap_a1"><strong><?php _e('Min Angle', 'cforms') ?></strong></label></td>
					<td class="obR">
						<input class="cap" type="text" id="cforms_cap_a1" name="cforms_cap_a1" value="<?php echo $a1; ?>"/>
						<label for="cforms_cap_a2" class="second-l"><strong><?php _e('Max Angle', 'cforms') ?></strong></label><input class="cap" type="text" id="cforms_cap_a2" name="cforms_cap_a2" value="<?php echo $a2; ?>"/>
					</td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="inputID2"><strong><?php _e('Color', 'cforms') ?></strong></label></td>
					<td class="obR">
						<input class="cap" type="text" id="inputID2" name="cforms_cap_c" onclick="javascript:currentEL=2;" value="<?php echo $c; ?>"/><input type="button" name="col-border" class="colorswatch" style="background-color:#<?php echo $c; ?>" id="plugID2" onclick="this.blur(); currentEL=2; $cfS('mini').display='block';"/>
					</td>
				</tr>

				<tr class="ob space15">
					<td class="obL">&nbsp;</td>
                    <td class="obR"><strong><?php _e('Number of shown characters', 'cforms') ?></strong></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_cap_c1"><strong><?php _e('Minimum', 'cforms') ?></strong></label></td>
					<td class="obR">
						<input class="cap" type="text" id="cforms_cap_c1" name="cforms_cap_c1" value="<?php echo $c1; ?>"/>
						<label for="cforms_cap_c2" class="second-l"><strong><?php _e('Maximum', 'cforms') ?></strong></label><input class="cap" type="text" id="cforms_cap_c2" name="cforms_cap_c2" value="<?php echo $c2; ?>"/>
					</td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_cap_ac"><strong><?php _e('Allowed characters', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_cap_ac" name="cforms_cap_ac" value="<?php echo $ac; ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_cap_i" name="cforms_cap_i" value="i" <?php if($cap['i']=='i') echo "checked=\"checked\""; ?>/><label for="cforms_cap_i"><?php _e('User response is treated case insensitive', 'cforms') ?></label></td>
				</tr>
				</table>
			</div>
		</fieldset>


		<fieldset id="visitorv" class="cformsoptions">
			<div class="cflegend op-closed" id="p13" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Visitor Verification Settings (Q&amp;A)', 'cforms')?>
            </div>

			<div class="cf-content" id="o13">
				<p><?php _e('Getting a lot of <strong>SPAM</strong>? Use these Q&amp;A\'s to counteract spam and ensure it\'s a human submitting the form. To use in your form, add the corresponding input field "<code>Visitor verification</code>" preferably in its own FIELDSET!', 'cforms') ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL"><label for="cforms_cap_foqa"><strong><?php _e('Force display', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_cap_foqa" name="cforms_cap_foqa" value="1" <?php if($cap['foqa']) echo "checked=\"checked\""; ?>/><label for="cforms_cap_foqa"><?php _e('Force Q&amp;A display for logged in users', 'cforms') ?></label></td>
				</tr>

				<tr class="ob space15">
					<td class="obL">&nbsp;</td>
					<td class="obR"><a class="infobutton" href="#" name="it12"><?php _e('Note &raquo;', 'cforms'); ?></a></td>
				</tr>
				<tr id="it12" class="infotxt"><td>&nbsp;</td><td class="ex">
							<?php _e('The below error/failure message is also used for <strong>captcha</strong> verification!', 'cforms') ?><br />
							<?php echo sprintf(__('Depending on your personal preferences and level of SPAM security you intend to put in place, you can also use <a href="%s" %s>cforms\' CAPTCHA feature</a>!', 'cforms'),'?page='.$plugindir.'/cforms-help.php#captcha','onclick="setshow(19)"'); ?>
				</td></tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_codeerr"><?php _e('<strong>Failure message</strong><br />(for a wrong answer)', 'cforms'); ?></label></td>
					<td class="obR">
						<table><tr><td><textarea class="resizable" rows="80px" cols="280px" name="cforms_codeerr" id="cforms_codeerr" ><?php echo stripslashes(htmlspecialchars($cformsSettings['global']['cforms_codeerr'])); ?></textarea></td></tr></table>
					</td>
				</tr>

				<?php $qa = stripslashes(htmlspecialchars($cformsSettings['global']['cforms_sec_qa'])); ?>

				<tr class="ob">
					<td class="obL"><label for="cforms_sec_qa"><?php _e('<strong>Questions &amp; Answers</strong><br />format: Q=A', 'cforms') ?></label></td>
					<td class="obR"><table><tr><td><textarea class="resizable" rows="80px" cols="280px" name="cforms_sec_qa" id="cforms_sec_qa" ><?php echo $qa; ?></textarea></td></tr></table></td>
				</tr>
				</table>
			</div>
		</fieldset>


		<fieldset id="tracking" class="cformsoptions">
			<div class="cflegend op-closed" id="p14" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Database Input Tracking', 'cforms')?>
            </div>

			<div class="cf-content" id="o14">
				<p><?php _e('If you like to track your form submissions also via the database, please enable this feature below. If required, two new tables will be created and you\'ll see a new sub tab "<strong>Tracking</strong>" under the cforms menu.', 'cforms') ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><a class="infobutton" href="#" name="it13"><?php _e('Note &raquo;', 'cforms'); ?></a></td>
				</tr>
				<tr id="it13" class="infotxt"><td>&nbsp;</td><td class="ex"><?php echo sprintf(__('If you\'ve enabled the <a href="%s" %s>auto confirmation message</a> feature or have included a <code>CC: me</code> input field, you can optionally configure the subject line/message of the email to include the form tracking ID by using the variable <code>{ID}</code>.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#autoconf','onclick="setshow(5)"'); ?></td></tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_database"><strong><?php _e('Enable Database Tracking', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_database" name="cforms_database" <?php if($cformsSettings['global']['cforms_database']=="1") echo "checked=\"checked\""; ?>/> <?php _e('Will create two new tables in your WP database.', 'cforms') ?></td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_showdashboard"><strong><?php _e('Show on dashboard', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_showdashboard" name="cforms_showdashboard" <?php if($cformsSettings['global']['cforms_showdashboard']=="1") echo "checked=\"checked\""; ?>/> <?php _e('Make sure to enable your forms individually as well!', 'cforms') ?></td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_rss"><strong><?php _e('Enable global RSS', 'cforms'); ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_rss" name="cforms_rss" <?php if( $cformsSettings['global']['cforms_rssall'] ) echo "checked=\"checked\""; ?>/> <?php _e('Enables an RSS feed to track all new submissions across all forms.', 'cforms'); ?></td>
				</tr>

				<?php if( current_user_can('track_cforms') && $cformsSettings['global']['cforms_rssall'] ) : ?>
				<tr class="ob">
					<td class="obL"></td>
					<td class="obR">
						<?php $j = $cformsSettings['global']['cforms_rssall_count']; $j = (int)abs($j)>20 ? 20:(int)abs($j); ?>
						<select name="cforms_rsscount" id="cforms_rsscount"><?php for ($i=1;$i<=20;$i++) echo '<option'.(($i==$j)?' selected="selected"':'').'>' .$i. '</option>'; ?></select>
                    	<label for="cforms_rsscount"><?php _e('Number of shown RSS entries', 'cforms'); ?></label>
                    </td>
				</tr>
				<tr class="ob">
					<td class="obL" style="padding-top:8px; vertical-align:top;"><label for="cforms_rsskey"><strong><?php _e('RSS Feed Security Key', 'cforms'); ?><br /><br /></strong></label></td>
					<td class="obR">
						<input name="cforms_rsskeyall" id="cforms_rsskey" value="<?php echo $cformsSettings['global']['cforms_rsskeyall'];  ?>" />
						<input type="submit" name="cforms_rsskeysnew" id="cforms_rsskeysnew" value="<?php _e('Reset RSS Key', 'cforms');  ?>" class="allbuttons"  onclick="javascript:document.mainform.action='#tracking';"/>
						<br /><?php _e('The complete RSS URL &raquo;', 'cforms'); echo '<br />'.get_option('siteurl').'?cformsRSS='.urlencode('-1$#$').$cformsSettings['global']['cforms_rsskeyall']; ?>
					</td>
				</tr>
				<?php endif; ?>

				</table>
			</div>
		</fieldset>

	    <div class="cf_actions" id="cf_actions">
	        <div class="cflegend op-closed" id="p31"><div class="blindplus"></div><p><?php _e('Admin Actions','cforms'); ?></p></div>
	        <div class="cf-content" id="o31">
				<p class="m1"><input type="submit" name="showinfo" title="<?php _e('outputs -for debug purposes- all cforms settings', 'cforms') ?>" class="allbuttons addbutton" value="<?php _e('Produce Debug Output', 'cforms') ?>"/></p>

				<p class="m4"><input type="button" class="jqModalDelAll allbuttons deleteall" value="<?php _e('Uninstalling / Removing cforms', 'cforms'); ?>"/></p>
				<?php if ( $wpdb->get_var("show tables like '$wpdb->cformssubmissions'") == $wpdb->cformssubmissions ) :?>
				<p class="m2"><input id="deletetables" type="submit" title="<?php _e('Be careful with this one!', 'cforms') ?>" name="deletetables" class="allbuttons deleteall" value="<?php _e('Delete cforms Tracking Tables', 'cforms') ?>" onclick="return confirm('<?php _e('Do you really want to erase all collected data?', 'cforms') ?>');"/></p>
				<?php endif; ?>

                <p class="m4"><input type="button" class="jqModalBackup allbuttons" value="<?php _e('Backup &amp; Restore All Settings', 'cforms'); ?>"/></p>
	            <p class="m5"><input type="submit" name="SubmitOptions" class="allbuttons updbutton formupd" value="<?php _e('Update Settings &raquo;', 'cforms') ?>" onclick="javascript:document.mainform.action='#'+getFieldset(focusedFormControl);" /></p>
	        </div>
	    </div>

	</form>

	<?php cforms_footer(); ?>
</div>

<div class="jqmWindow" id="cf_backupbox">
    <div class="cf_ed_header jqDrag"><?php _e('Backup &amp; Restore All Settings','cforms'); ?></div>
    <div class="cf_ed_main_backup">
        <form enctype="multipart/form-data" action="" name="backupform" method="post">
            <div class="controls">

				<p class="ex"><?php _e('Restoring all settings will overwrite all form specific &amp; global settings!', 'cforms') ?></p>
				<p>
                	<input type="submit" name="saveallcformsdata" title="<?php _e('Backup all settings now!', 'cforms') ?>" class="allbuttons" value="<?php _e('Backup all settings now!', 'cforms') ?>" onclick="javascript:jQuery('#cf_backupbox').jqmHide();"/>&nbsp;&nbsp;&nbsp;
                	<input type="file" id="importall" name="importall" size="25" /><input type="submit" name="restoreallcformsdata" title="<?php _e('Restore all settings now!', 'cforms') ?>" class="allbuttons deleteall" value="<?php _e('Restore all settings now!', 'cforms') ?>" onclick="return confirm('<?php _e('With a broken backup file, this action may erase all your settings! Do you want to continue?', 'cforms') ?>');"/>
				</p>
				<em><?php _e('PS: Individual form configurations can be backup up on the respective form admin page.', 'cforms') ?></em>
                <p class="cancel"><a href="#" id="cancel" class="jqmClose"><img src="<?php echo $cforms_root; ?>/images/dialog_cancel.gif" alt="<?php _e('Cancel', 'cforms') ?>" title="<?php _e('Cancel', 'cforms') ?>"/></a></p>

            </div>
            <input type="hidden" name="noSub" value="<?php echo $noDISP; ?>"/>
        </form>
    </div>
</div>
<div class="jqmWindow" id="cf_delall_dialog">
    <div class="cf_ed_header jqDrag"><?php _e('Uninstalling / Removing cforms','cforms'); ?></div>
    <div class="cf_ed_main_backup">
        <form action="" name="deleteform" method="post">
            <div id="cf_target_del"><?php _e('Warning!','cforms'); ?></div>
            <div class="controls">
				<p><?php _e('Generally, simple deactivation of cforms does <strong>not</strong> erase any of its data. If you like to quit using cforms for good, please erase all data before deactivating the plugin.', 'cforms') ?></p>
				<p><strong><?php _e('This is irrevocable!', 'cforms') ?></strong>&nbsp;&nbsp;&nbsp;<br />
					 <input type="submit" name="cfdeleteall" title="<?php _e('Are you sure you want to do this?!', 'cforms') ?>" class="allbuttons deleteall" value="<?php _e('DELETE *ALL* CFORMS DATA', 'cforms') ?>" onclick="return confirm('<?php _e('Final Warning!', 'cforms') ?>');"/>

                <p class="cancel"><a href="#" id="cancel" class="jqmClose"><img src="<?php echo $cforms_root; ?>/images/dialog_cancel.gif" alt="<?php _e('Cancel', 'cforms') ?>" title="<?php _e('Cancel', 'cforms') ?>"/></a></p>
            </div>
        </form>
    </div>
</div>
<?php
function cf_get_files($dir,$currentfile,$ext){
	global	$cformsSettings;

	$s = $cformsSettings['global']['cforms_IIS'];
	$presetsdir		= $cformsSettings['global']['cforms_root_dir'] .$s.'..'.$s .'cforms-custom';
	$list 			= '';
	$allfiles		= array();

	if ( file_exists($presetsdir) ){

		$list .= '<option disabled="disabled" style="background:#e4e4e4">&nbsp;&nbsp;*** ' .__('custom files','cforms'). ' ***&nbsp;&nbsp;</option>';

		if ($handle = opendir($presetsdir)) {
		    while (false !== ($file = readdir($handle))) {
		        if (eregi('\.'.$ext.'$',$file) && $file != "." && $file != ".." && filesize($presetsdir.'/'.$file) > 0)
					$list .= '<option value="../../cforms-custom/'.$file.'"'.(('../../cforms-custom/'.$file==$currentfile)?' style="background:#fbd0d3" selected="selected"':'').'>' .$file. '</option>';
		    }
		    closedir($handle);
		}

		$list .= '<option disabled="disabled" style="background:#e4e4e4">&nbsp;&nbsp;*** ' .__('cform css files','cforms'). ' ***&nbsp;&nbsp;</option>';
	}

	$presetsdir		= $cformsSettings['global']['cforms_root_dir'].$s. $dir .$s;
	if ($handle = opendir($presetsdir)) {
	    while (false !== ($file = readdir($handle))) {
	        if (eregi('\.'.$ext.'$',$file) && $file != "." && $file != ".." && filesize($presetsdir.$file) > 0)
				$list .= '<option value="'.$file.'"'.(($file==$currentfile)?' style="background:#fbd0d3" selected="selected"':'').'>' .$file. '</option>';
	    }
	    closedir($handle);
	}

    return ($list=='')?'<li>'.__('Not available','cforms').'</li>':$list;
}


### strip stuff
function prep2($v,$d) {
	return ($v<>'')?stripslashes(htmlspecialchars($v)):$d;
}
?>