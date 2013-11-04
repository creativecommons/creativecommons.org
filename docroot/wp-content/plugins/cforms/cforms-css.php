<?php
###
### Please see cforms.php for more information
###

### DB settings
$wpdb->cformssubmissions	= $wpdb->prefix . 'cformssubmissions';
$wpdb->cformsdata       	= $wpdb->prefix . 'cformsdata';

### New global settings container, will eventually be the only one!
$cformsSettings = get_option('cforms_settings');

$cformsSettings = get_option('cforms_settings');
$plugindir   = $cformsSettings['global']['plugindir'];
$s = $cformsSettings['global']['cforms_IIS'];

### CSS styles
$style		= $cformsSettings['global']['cforms_css'];
$stylefile	= $cformsSettings['global']['cforms_root_dir']."{$s}styling{$s}".$style;

### check if pre-9.0 update needs to be made
if( $cformsSettings['global']['update'] )
	require_once (dirname(__FILE__) . '/update-pre-9.php');

### Check Whether User Can Manage Database
check_access_priv();

### if all data has been erased quit
if ( $cformsSettings['global']['cforms_formcount'] == '' ){
	?>
	<div class="wrap">
		<div id="icon-cforms-css" class="icon32"><br/></div><h2><?php _e('Styling your forms','cforms')?></h2>

	<h2><?php _e('All cforms data has been erased!', 'cforms') ?></h2>
	<p><?php _e('Please go to your <strong>Plugins</strong> tab and either disable the plugin, or toggle its status (disable/enable) to revive cforms!', 'cforms') ?></p>
	</div>
	<?php
	die;
}

###
### Enable/Disable LabelIDs ?
###

if(isset($_POST['label-ids'])){
	$cformsSettings['global']['cforms_labelID'] = $cformsSettings['global']['cforms_labelID']?'0':'1';
	update_option('cforms_settings',$cformsSettings);
}
else if(isset($_POST['li-ids'])){
	$cformsSettings['global']['cforms_liID'] = $cformsSettings['global']['cforms_liID']?'0':'1';
	update_option('cforms_settings',$cformsSettings);
}
else if(isset($_POST['no-css'])){
	$cformsSettings['global']['cforms_no_css'] = $cformsSettings['global']['cforms_no_css']?'0':'1';
	update_option('cforms_settings',$cformsSettings);
}

###
### Select new CSS?
###

if(!empty($_POST['save_css'])){

	$newcss = stripslashes( $_POST['csseditor'] );

	if(is_writeable($stylefile)) {

	    $f = fopen($stylefile, 'w+');
	    fwrite($f, $newcss);
	    fclose($f);

	    echo ' <div id="message" class="updated fade"><p><strong>'. __('The stylesheet has been updated.', 'cforms') .'</strong></p></div>'."\n";

	} else

	    echo ' <div id="message" class="updated fade"><p><strong>'. __('Write Error! Please verify write permissions on the style file.', 'cforms') .'</strong></p></div>'."\n";

} else if ( !empty($_POST['chg_css']) ){

	$cformsSettings['global']['cforms_css'] = $_POST['style'];
	update_option('cforms_settings',$cformsSettings);

	$style = $cformsSettings['global']['cforms_css'];
	$stylefile  = $cformsSettings['global']['cforms_root_dir']."{$s}styling{$s}".$style;
	echo ' <div id="message" class="updated fade"><p><strong>'. __('New theme selected.', 'cforms') .'</strong></p></div>'."\n";
}


### check for abspath.php
abspath_check();

?>
<div class="wrap" id="top">
		<div id="icon-cforms-css" class="icon32"><br/></div><h2><?php _e('Styling your forms','cforms')?></h2>

	<p><?php _e('Please select a theme file that comes closest to what you\'re looking for and apply your own custom changes via the editor below.', 'cforms') ?></p>
	<p><?php _e('This is <strong>optional</strong> of course, if you\'re happy with the default look and feel, no need to do anything here.', 'cforms') ?></p>

	<form id="selectcss" method="post" action="" name="selectcss">
			 <fieldset class="cformsoptions">
	            <div class="cflegend op-closed" style="padding-left:10px;" title="<?php _e('Expand/Collapse', 'cforms') ?>">
	                <a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><?php _e('Select a form style', 'cforms')?>
	            </div>

			<div class="cf-content">
				<table>
				<tr valign="top">

					<td>
						<table>
							<tr valign="middle">
								<td width="300" align="right" style="font-size:10px;"><?php _e('Please choose a theme file <br />to style your forms' , 'cforms') ?></td>
								<td align="center">
									<?php ### include all css files
										$d   = $cformsSettings['global']['cforms_root_dir']."{$s}styling";
										$dCustom = $cformsSettings['global']['cforms_root_dir']."{$s}..{$s}cforms-custom";

										$exists = file_exists($d);
										if ( $exists == false )
											echo '<p><strong>' . __('Please make sure that the <code>/styling</code> folder exists in the cforms plugin directory!', 'cforms') . '</strong></p>';

										else {
											?>
											<select style="cursor:pointer;" name="style"><?php


												if (file_exists($dCustom)){
													echo '<option disabled="disabled" style="background:#e4e4e4">&nbsp;&nbsp;*** '.__('custom css files','cforms').' ***&nbsp;&nbsp;</option>';

													### customer CSS files
													$allcustomCSS = array();
													$dir = opendir($dCustom);
													while ( $dir && ($f = readdir($dir)) ) {
														if( eregi("\.css$",$f) ){
															array_push($allcustomCSS, $f);
														}
													}
													sort($allcustomCSS);
													foreach ( $allcustomCSS as $f ) {
														if( strpos($style,$f)!==false )
														    	echo '<option style="background:#fbd0d3" selected="selected" value="../../cforms-custom/'.$f.'">'.$f.'</option>'."\n";
														else
																echo '<option value="../../cforms-custom/'.$f.'">'.$f.'</option>';
													}

													echo '<option disabled="disabled" style="background:#e4e4e4">&nbsp;&nbsp;*** '.__('cform css files','cforms').' ***&nbsp;&nbsp;</option>';
												}

												### core CSS files
												$allCSS = array();
												$dir = opendir($d);
												while ( $dir && ($f = readdir($dir)) ) {
													if( eregi("\.css$",$f) && !eregi("calendar\.css$",$f) ){
														array_push($allCSS, $f);
													}
												}
												sort($allCSS);
												foreach ( $allCSS as $f ) {
													if( $f==$style )
													    	echo '<option style="background:#fbd0d3" selected="selected" value="'.$f.'">'.$f.'</option>'."\n";
													else
															echo '<option value="'.$f.'">'.$f.'</option>';
												}

											?></select>
									<?php } ?>
								</td>
								<td>
									<input type="submit" name="chg_css" class="allbuttons stylebutton" value="<?php _e('Select Style &raquo;', 'cforms'); ?>"/>
								</td>
							</tr>
							<tr style="height:200px;">
								<td colspan="3">
									<p class="ex"><?php _e('For comprehensive customization support you may choose to turn on <strong>label &amp; list element ID\'s</strong>. This way each input field &amp; label can be specifically addressed via CSS styles.', 'cforms') ?> </p>

									<input type="submit" name="label-ids" id="label-ids" class="allbuttons" value="<?php if ( $cformsSettings['global']['cforms_labelID']=='' || $cformsSettings['global']['cforms_labelID']=='0' ) _e('Activate Label IDs', 'cforms'); else  _e('Deactivate Label IDs', 'cforms'); ?>" />
									<?php if ( $cformsSettings['global']['cforms_labelID']=='1' ) echo __('Currently turned on ', 'cforms') . '<img class="turnedon" src="' . $cforms_root.'/images/ok.gif" alt=""/>'; ?>
									<br />
									<input type="submit" name="li-ids" id="li-ids" class="allbuttons" value="<?php if ( $cformsSettings['global']['cforms_liID']=='' || $cformsSettings['global']['cforms_liID']=='0' ) _e('Activate List Element IDs', 'cforms'); else  _e('Deactivate List Element IDs', 'cforms'); ?>" />
									<?php if ( $cformsSettings['global']['cforms_liID']=='1' ) echo __('Currently turned on ', 'cforms') . '<img class="turnedon" src="' . $cforms_root.'/images/ok.gif" alt=""/>'; ?>
									<br />
									<br />
									<input type="submit" name="no-css" id="no-css" class="allbuttons deleteall" style="height:30px" value="<?php if ( $cformsSettings['global']['cforms_no_css']=='' || $cformsSettings['global']['cforms_no_css']=='0' ) _e('Deactivate CSS styling altogether!', 'cforms'); else  _e('Reactivate CSS styling!', 'cforms'); ?>" />
									<?php if ( $cformsSettings['global']['cforms_no_css']=='1' ) echo __('Theme is disabled', 'cforms') . '<img class="turnedon" src="' . $cforms_root.'/images/ok.gif" alt=""/>'; ?>

								</td>
							</tr>
							<tr>
								<td colspan="3">
										<p><?php echo sprintf(__('You might also want to study the <a href="%s">PDF guide on cforms CSS &amp; a web screencast</a> I put together to give you a head start.', 'cforms'),'http://www.deliciousdays.com/cforms-forum/css-styling-and-layout/css-customization-guide-1'); ?></p>
								</td>
							</tr>

						</table>
					</td>

					<td>
						<?php if ( $exists ) {

								$existsjpg = file_exists($d.'/'.$style.'.jpg');
								if ( $existsjpg )
									echo __('PREVIEW:', 'cforms').'<br /><img height="228px" width="300px" src="' . $cforms_root.'/styling/'.$style.'.jpg' . '" alt="' . __('Theme Preview', 'cforms') . '" title="' . __('Theme Preview', 'cforms').': ' . $style .'"/>';

						}?>
					</td>

				</tr>
				</table>
                </div>
			</fieldset>
	 </form>
<?php
###
### Edit current style
###
?>
	<form id="editcss" method="post" action="" name="editcss">
			 <fieldset class="cformsoptions">
	            <div id="p15" class="cflegend op-closed" title="<?php _e('Expand/Collapse', 'cforms') ?>">
	                <a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Basic CSS editor: ', 'cforms'); echo '<span style="color:#D54E21;">'.$style.'</span>' ?>
	            </div>

				<div class="cf-content" id="o15">
	                <p><?php _e('Use this simple editor to further tailor your forms\' style to meet your requirements. Currently you\'re editing: ', 'cforms'); echo '<span style="color:#D54E21;">'.$style.'</span>' ?></p>
	                <p><input type="submit" name="save_css" class="allbuttons updbutton" value="<?php _e('Update Settings &raquo;', 'cforms'); ?>" onclick="javascript:document.mainform.action='#fileupload';" /></p>

	                <textarea rows="16" cols="118" id="stylebox" name="csseditor"><?php

	                    if( is_file($stylefile) && filesize($stylefile) > 0) {

	                        $f = "";
	                        $f = fopen($stylefile, 'r');
	                        $file = fread($f, filesize($stylefile));
	                        echo $file;
	                        fclose($f);

	                    } else
	                        echo __('Sorry. The file you are looking for doesn\'t exist.', 'cforms');

	                ?></textarea>
				</div>

		  </fieldset>
	</form>
</div>