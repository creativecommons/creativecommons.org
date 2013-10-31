<?php

###
### please see cforms.php for more information
###

### new global settings container, will eventually be the only one!
$cformsSettings = get_option('cforms_settings');

$plugindir   = $cformsSettings['global']['plugindir'];
$cforms_root = $cformsSettings['global']['cforms_root'];

### check if pre-9.0 update needs to be made
if( $cformsSettings['global']['update'] )
	require_once (dirname(__FILE__) . '/update-pre-9.php');

### Check Whether User Can Manage Database
check_access_priv();

### if all data has been erased quit
if ( check_erased() )
	return;


### default to 1 & get real #
$FORMCOUNT=$cformsSettings['global']['cforms_formcount'];

if(isset($_REQUEST['addbutton'])){
	require_once(dirname(__FILE__) . '/lib_options_add.php');

} elseif(isset($_REQUEST['dupbutton'])) {
	require_once(dirname(__FILE__) . '/lib_options_dup.php');

} elseif( isset($_REQUEST['uploadcformsdata']) ) {
	require_once(dirname(__FILE__) . '/lib_options_up.php');

} elseif(isset($_REQUEST['delbutton']) && $FORMCOUNT>1) {
	require_once(dirname(__FILE__) . '/lib_options_del.php');

} else {

	### set paramters to default, if not exists
	$noDISP='1';$no='';
	if( isset($_REQUEST['switchform']) ) { ### only set when hitting form chg buttons
		if( $_REQUEST['switchform']<>'1' )
			$noDISP = $no = $_REQUEST['switchform'];
	}
	else if( isset($_REQUEST['go']) ) { ### only set when hitting form chg buttons
		if( $_REQUEST['pickform']<>'1' )
			$noDISP = $no = $_REQUEST['pickform'];
	}
	else{
		if( isset($_REQUEST['noSub']) && (int)$_REQUEST['noSub']>1 ) ### otherwise stick with the current form
			$noDISP = $no = $_REQUEST['noSub'];
	}

}

### PRESETS
if ( isset($_REQUEST['formpresets']) )
	require_once(dirname(__FILE__) . '/lib_options_presets.php');


### default: $field_count = what's in the DB
$field_count = $cformsSettings['form'.$no]['cforms'.$no.'_count_fields'];


### check if T-A-F action is required
$alldisabled=false;
$allenabled=0;
if( isset($_REQUEST['addTAF']) || isset($_REQUEST['removeTAF']) )
{

	$posts = $wpdb->get_results("SELECT ID FROM $wpdb->posts");

	if ( isset($_REQUEST['addTAF']) ){

		foreach($posts as $post) {
			if ( add_post_meta($post->ID, 'tell-a-friend', '1', true) )
				$allenabled++;
		}

	} else if ( isset($_REQUEST['removeTAF']) ){
		$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = 'tell-a-friend'");
		$alldisabled=true;
	}

}


### Update Settings
if( isset($_REQUEST['SubmitOptions']) || isset($_REQUEST['AddField']) || array_search("X", $_REQUEST) ){
	require_once(dirname(__FILE__) . '/lib_options_sub.php');
}


### new RSS key computed
if( isset($_REQUEST['cforms_rsskeysnew']) ) {
	$cformsSettings['form'.$no]['cforms'.$no.'_rsskey'] = md5(rand());
	update_option('cforms_settings',$cformsSettings);
}


### delete field if we find one and move the rest up
$deletefound = 0;
if(strlen($cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . $field_count]) > 0) {

	$temp_count = 1;
	while($temp_count <= $field_count) {

		if(isset($_REQUEST['DeleteField' . $temp_count])) {
			$deletefound = 1;
			$cformsSettings['form'.$no]['cforms'.$no.'_count_fields'] = ($field_count - 1);
		}

		if($deletefound && $temp_count<$field_count) {
			$temp_val = $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . ($temp_count+1)];
			$cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . ($temp_count)] = $temp_val;
		}

		$temp_count++;
	} ### while

	if($deletefound == 1) {  ### now delete
	  	unset( $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . $field_count] );
		$field_count--;
	}
    update_option('cforms_settings',$cformsSettings);
} ### if


### check possible errors
require_once(dirname(__FILE__) . '/lib_options_err.php');


###
### prep drop down box for form selection
###
$formlistbox = ' <select id="pickform" name="pickform">';
for ($i=1; $i<=$FORMCOUNT; $i++){
	$j   = ( $i > 1 )?$i:'';
	$sel = ($noDISP==$i)?' selected="selected"':'';
	$formlistbox .= '<option value="'.$i.'" '.$sel.'>'.stripslashes($cformsSettings['form'.$j]['cforms'.$j.'_fname']).'</option>';
}
$formlistbox .= '</select>';


### make sure at least the default FROM: address is set
if ( $cformsSettings['form'.$no]['cforms'.$no.'_fromemail'] == '' ){
	$cformsSettings['form'.$no]['cforms'.$no.'_fromemail'] = '"'.get_option('blogname').'" <wordpress@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])) . '>';
    update_option('cforms_settings',$cformsSettings);
}

### check if HTML needs to be enabled
$fd = $cformsSettings['form'.$no]['cforms'.$no.'_formdata'];
if( strlen($fd)<=2 ) {
	$fd .= ( $cformsSettings['form'.$no]['cforms'.$no.'_header_html']<>''  )?'1':'0';
	$fd .= ( $cformsSettings['form'.$no]['cforms'.$no.'_cmsg_html']<>'' )?'1':'0';
	$cformsSettings['form'.$no]['cforms'.$no.'_formdata'] = $fd;
    update_option('cforms_settings',$cformsSettings);
}

### check for abspath.php
abspath_check();

?>
<div class="wrap" id="top">
	<div id="icon-cforms-settings" class="icon32"><br/></div><h2><?php _e('Form Settings','cforms')?></h2>

	<form enctype="multipart/form-data" id="cformsdata" name="mainform" method="post" action="#">
		<table class="chgformbox" title="<?php _e('Navigate to your other forms.', 'cforms') ?>">
		<tr>
            <td class="chgL">
            	<label for="switchform" class="bignumber"><?php _e('Navigate to', 'cforms') ?> </label>
                <?php echo $formlistbox; ?><input type="submit" class="allbuttons go" id="go" name="go" value="<?php _e('Go', 'cforms');?>"/>
            </td>
            <td class="chgM">
                <?php
                for ($i=1; $i<=$FORMCOUNT; $i++) {
                    $j   = ( $i > 1 )?$i:'';
                    echo '<input id="switchform" title="'.stripslashes($cformsSettings['form'.$j]['cforms'.$j.'_fname']).'" class="allbuttons chgbutton'.(($i <> $noDISP)?'':'hi').'" type="submit" name="switchform" value="'.$i.'"/>';
                }
                ?>
        	</td>
			</tr>
        </table>
		<input type="hidden" name="no" value="<?php echo $noDISP; ?>"/>
		<input type="hidden" name="noSub" value="<?php echo $noDISP; ?>" />

	    <p>
	        <?php echo sprintf(__('<strong>cforms</strong> allows you <a href="%s" %s>to insert</a> one or more custom designed contact forms, which on submission (preferably via Ajax) will send the visitor info via email and optionally stores the feedback in the database.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#inserting','onclick="setshow(18)"'); ?>
	        <?php echo sprintf(__('<a href="%s" %s>Here</a> is a quick step by step quide to get you up and running quickly.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#guide','onclick="setshow(17)"'); ?>
	    </p>

		<table class="mainoptions">
		<tr>
			<td class="chgL">
            	<label for="cforms_fname" class="bignumber"><?php _e('Form Name', 'cforms') ?></label>
				<input id="cforms_fname" name="cforms_fname" class="cforms_fname" size="40" value="<?php echo stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_fname']);  ?>" title="<?php _e('You may give each form an optional name to better identify incoming emails.', 'cforms') ?>"/>
				<input title="<?php _e('Enables or disables Ajax support for this form.', 'cforms') ?>" id="cforms_ajax" type="checkbox" class="allchk cforms_ajax" name="cforms_ajax" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_ajax']=="1") echo "checked=\"checked\""; ?>/>
				<label title="<?php _e('Enables or disables Ajax support for this form.', 'cforms') ?>" for="cforms_ajax" class="bignumber"><?php _e('Ajax enabled', 'cforms') ?></label>
			</td>
        </tr>
        </table>

	<fieldset id="anchorfields" class="cf-content">

		<p>
			<?php echo sprintf(__('Please see the <strong>Help!</strong> section for information on how to deploy the various <a href="%s" %s>supported fields</a>,', 'cforms'),'?page='.$plugindir.'/cforms-help.php#fields','onclick="setshow(19)"') . ' ' .
					   sprintf(__('set up forms using <a href="%s" %s>FIELDSETS</a>,', 'cforms'), '?page='.$plugindir.'/cforms-help.php#hfieldsets','onclick="setshow(19)"') .
					   sprintf(__('use <a href="%s" %s>default values</a> &amp; <a href="%s" %s>regular expressions</a> for single &amp; multi-line fields. ', 'cforms'),'?page='.$plugindir.'/cforms-help.php#single','onclick="setshow(19)"','?page='.$plugindir.'/cforms-help.php#regexp','onclick="setshow(19)"') .
					   sprintf(__('Besides the generic success &amp; failure messages below, you can add <a href="%s" %s>custom error messages</a>.', 'cforms'),'?page='.$plugindir.'/cforms-help.php#customerr','onclick="setshow(20)"'); ?>
		</p>

		<div class="tableheader">
        	<div id="cformswarning" style="display:none"><?php echo __('Please save the new order of fields (<em>Update Settings</em>)!','cforms'); ?></div>
        	<div>
	            <div class="fh1" title="<?php _e('Can be a simple label or a more complex expression. See Help!', 'cforms'); ?>"><br /><span class="abbr"><?php _e('Field Name', 'cforms'); ?></span></div>
	            <div class="fh2" title="<?php _e('Pick one of the supported input field types.', 'cforms'); ?>"><br /><span class="abbr"><?php _e('Type', 'cforms'); ?></span></div>
	            <div><img src="<?php echo $cforms_root; ?>/images/ic_required.gif" title="<?php _e('Makes an input field required for proper form validation.', 'cforms'); ?>" alt="" /><br /><?php _e('required', 'cforms'); ?></div>
	            <div><img src="<?php echo $cforms_root; ?>/images/ic_email.gif" title="<?php _e('Makes the field required and verifies the email address.', 'cforms'); ?>" alt="" /><br /><?php _e('e-mail', 'cforms'); ?></div>
	            <div><img src="<?php echo $cforms_root; ?>/images/ic_clear.gif" title="<?php _e('Clears the field (default value) upon focus.', 'cforms'); ?>" alt="" /><br /><?php _e('auto-clear', 'cforms'); ?></div>
	            <div><img src="<?php echo $cforms_root; ?>/images/ic_disabled.gif" title="<?php _e('Grey\'s out a form field (field will be completely disabled).', 'cforms'); ?>" alt="" /><br /><?php _e('disabled', 'cforms'); ?></div>
	            <div><img src="<?php echo $cforms_root; ?>/images/ic_readonly.gif" title="<?php _e('Form field will be readonly!', 'cforms'); ?>" alt="" /><br /><?php _e('read-only', 'cforms'); ?></div>
       		</div>
		</div>

   		<div id="allfields" class="groupWrapper">

                    <?php

                    $isTAF = (int)substr($cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'],0,1);
					$ti = 1;

                    ### pre-check for verification field
                    $ccboxused=false;
                    $emailtoboxused=false;
                    $verificationused=false;
                    $captchaused=false;
                    $fileupload=false; ### only for hide/show options

                    $alternate=' ';
                    $fieldsadded = false;

                    for($i = 1; $i <= $field_count; $i++) {
                            $allfields[$i] = $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . $i];
                            if ( strpos($allfields[$i],'verification')!==false )    $verificationused = true;
                            if ( strpos($allfields[$i],'captcha')!==false )         $captchaused = true;
                            if ( strpos($allfields[$i],'emailtobox')!==false )      $emailtoboxused = true;
                            if ( strpos($allfields[$i],'ccbox')!==false )           $ccboxused = true;
                            if ( strpos($allfields[$i],'upload')!==false )          $fileupload = true; //needed for config
                    }

                    for($i = 1; $i <= $field_count; $i++) {

                        $field_stat = explode('$#$', $allfields[$i] );

                        ### default vals
                        $field_name = __('New Field', 'cforms');
                        $field_type = 'textfield';
                        $field_required = '0';
                        $field_emailcheck = '0';
                        $field_clear = '0';
                        $field_disabled = '0';
                        $field_readonly = '0';

                        if(sizeof($field_stat) >= 3) {
                            $field_name = stripslashes(htmlspecialchars($field_stat[0]));
                            $field_type = $allfields[$i] = $field_stat[1];
                            $field_required = $field_stat[2];
                            $field_emailcheck = $field_stat[3];
                            $field_clear = $field_stat[4];
                            $field_disabled = $field_stat[5];
                            $field_readonly = $field_stat[6];
                        }
                        else if(sizeof($field_stat) == 1){
                            $cformsSettings['form'.$no]['cforms'.$no.'_count_field_' . $i] = __('New Field', 'cforms').'$#$textfield$#$0$#$0$#$0$#$0$#$0';
                            $fieldsadded = true;
                        }

                    	switch ( $field_type ) {
	                       case 'emailtobox':   $specialclass = 'style="background:#CBDDFE"'; break;
	                        case 'ccbox':       $specialclass = 'style="background:#D8FFCA"'; break;
	                        case 'verification':
	                        case 'captcha':     $specialclass = 'style="background:#D1B6E9"'; break;
	                        case 'textonly':    $specialclass = 'style="background:#E1EAE6"'; break;
	                        case 'fieldsetstart':
	                        case 'fieldsetend': $specialclass = 'style="background:#ECFEA5"'; break;
	                        default:            $specialclass = ''; break;
                        }

                    	$alternate = ($alternate=='')?' rowalt':''; ?>

                    	<div id="f<?php echo $i; ?>" class="groupItem<?php echo $alternate; ?>">

                        	<div class="itemContent">

	                            <span class="itemHeader<?php echo ($alternate<>'')?' altmove':''; ?>" title="<?php _e('Drag me','cforms')?>"><?php echo (($i<10)?'0':'').$i; ?></span>

	                            <input tabindex="<?php echo $ti++ ?>" title="<?php _e('Please enter field definition', 'cforms'); ?>" class="inpfld" <?php echo $specialclass; ?> name="field_<?php echo($i); ?>_name" id="field_<?php echo($i); ?>_name" size="30" value="<?php echo ($field_type == 'fieldsetend')?'--':$field_name; ?>" /><span title="<?php echo $cforms_root.'/js/include/'; ?>"><input value="" type="submit" onfocus="this.blur()" class="wrench jqModal" title="<?php _e('Edit', 'cforms'); ?>"/></span><select tabindex="<?php echo $ti++ ?>" title="<?php _e('Pick a field type', 'cforms'); ?>" class="fieldtype selfld" <?php echo $specialclass; ?> name="field_<?php echo($i); ?>_type" id="field_<?php echo($i); ?>_type">

                                <option value="fieldsetstart" <?php echo($field_type == 'fieldsetstart'?' selected="selected"':''); ?>><?php _e('New Fieldset', 'cforms'); ?></option>
                                <option value="textonly" <?php echo($field_type == 'textonly'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Text only (no input)', 'cforms'); ?></option>
                                <option value="textfield" <?php echo($field_type == 'textfield'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Single line of text', 'cforms'); ?></option>
                                <option value="textarea" <?php echo($field_type == 'textarea'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Multiple lines of text', 'cforms'); ?></option>
                                <option value="checkbox" <?php echo($field_type == 'checkbox'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Check Box', 'cforms'); ?></option>
                                <option value="checkboxgroup" <?php echo($field_type == 'checkboxgroup'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Check Box Group', 'cforms'); ?></option>
                                <option value="radiobuttons" <?php echo($field_type == 'radiobuttons'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Radio Buttons', 'cforms'); ?></option>
                                <option value="selectbox" <?php echo($field_type == 'selectbox'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Select Box', 'cforms'); ?></option>
                                <option value="multiselectbox" <?php echo($field_type == 'multiselectbox'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Multi Select Box', 'cforms'); ?></option>
                                <option value="upload" <?php echo($field_type == 'upload'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('File Upload Box', 'cforms'); ?></option>
                                <option<?php if ( $cformsSettings['global']['cforms_datepicker']<>'1' ) echo ' disabled="disabled" class="disabled"'; ?> value="datepicker" <?php echo($field_type == 'datepicker'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Date Entry/Dialog', 'cforms'); ?></option>
                                <option value="pwfield" <?php echo($field_type == 'pwfield'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Password Field', 'cforms'); ?></option>
                                <option value="hidden" <?php echo($field_type == 'hidden'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Hidden Field', 'cforms'); ?></option>
                                <option value="fieldsetend" <?php echo($field_type == 'fieldsetend'?' selected="selected"':''); ?>><?php _e('End Fieldset', 'cforms'); ?></option>

                                <option value="" class="disabled" disabled="disabled">                   <?php _e('--------- Special ------------', 'cforms'); ?></option>
                                <option<?php if ( $ccboxused && $field_type<>"ccbox" ) echo ' disabled="disabled" class="disabled"'; ?> value="ccbox" <?php echo($field_type == 'ccbox'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('CC: option for user', 'cforms'); ?></option>
                                <option<?php if ( $emailtoboxused && $field_type<>"emailtobox" ) echo ' disabled="disabled" class="disabled"'; ?>  value="emailtobox" <?php echo($field_type == 'emailtobox'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Multiple Recipients', 'cforms'); ?></option>
                                <option<?php if ( $verificationused && $field_type<>"verification" ) echo ' disabled="disabled" class="disabled"'; ?>  value="verification" <?php echo($field_type == 'verification'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Visitor verification (Q&amp;A)', 'cforms'); ?></option>
                                <option<?php if ( $captchaused && $field_type<>"captcha" ) echo ' disabled="disabled" class="disabled"'; ?>  value="captcha" <?php echo($field_type == 'captcha'?' selected="selected"':''); ?>>&nbsp;&nbsp;<?php _e('Captcha verification (image)', 'cforms'); ?></option>

                                <?php if ( $isTAF<>1 ) $dis=' disabled="disabled" class="disabled"'; else $dis=''; ?>
                                <option value="" class="disabled" disabled="disabled"><?php _e('----- T-A-F form fields ------', 'cforms'); ?></option>
                                <option<?php echo $dis; ?> value="yourname" <?php echo($field_type == 'yourname'?' selected="selected"':''); ?>><?php _e('T-A-F * Your Name', 'cforms'); ?></option>
                                <option<?php echo $dis; ?> value="youremail" <?php echo($field_type == 'youremail'?' selected="selected"':''); ?>><?php _e('T-A-F * Your Email', 'cforms'); ?></option>
                                <option<?php echo $dis; ?> value="friendsname" <?php echo($field_type == 'friendsname'?' selected="selected"':''); ?>><?php _e('T-A-F * Friend\'s Name', 'cforms'); ?></option>
                                <option<?php echo $dis; ?> value="friendsemail" <?php echo($field_type == 'friendsemail'?' selected="selected"':''); ?>><?php _e('T-A-F * Friend\'s Email', 'cforms'); ?></option>

                                <?php if ( $isTAF<>'2' ) $dis=' disabled="disabled" class="disabled"'; else $dis=''; ?>
                                <option value="" class="disabled" disabled="disabled"><?php _e('--- WP comment form fields ---', 'cforms'); ?></option>
                                <option<?php echo $dis; ?> value="cauthor" <?php echo($field_type == 'cauthor'?' selected="selected"':''); ?>><?php _e('Comment Author', 'cforms'); ?></option>
                                <option<?php echo $dis; ?> value="email" <?php echo($field_type == 'email'?' selected="selected"':''); ?>><?php _e('Author\'s Email', 'cforms'); ?></option>
                                <option<?php echo $dis; ?> value="url" <?php echo($field_type == 'url'?' selected="selected"':''); ?>><?php _e('Author\'s URL', 'cforms'); ?></option>
                                <option<?php echo $dis; ?> value="comment" <?php echo($field_type == 'comment'?' selected="selected"':''); ?>><?php _e('Author\'s Comment', 'cforms'); ?></option>
                                <option<?php echo $dis; ?> value="send2author" <?php echo($field_type == 'send2author'?' selected="selected"':''); ?>><?php _e('Select: Email/Comment', 'cforms'); ?></option>

                                <?php if ( class_exists('sg_subscribe') ) : ?>
                                    <option<?php echo $dis; ?> value="subscribe" <?php echo($field_type == 'subscribe'?' selected="selected"':''); ?>><?php _e('Subscribe To Comments', 'cforms'); ?></option>
                                <?php endif; ?>
                                <?php if ( function_exists('commentluv_setup') ) : ?>
                                    <option<?php echo $dis; ?> value="luv" <?php echo($field_type == 'luv'?' selected="selected"':''); ?>><?php _e('Comment Luv', 'cforms'); ?></option>
                                <?php endif; ?>

                            	</select><?php

                            echo '<input tabindex="'.($ti++).'" '.(($field_count<=1)?'disabled="disabled"':'').' class="'.(($field_count<=1)?'noxbutton':'xbutton').'" type="submit" name="DeleteField'.$i.'" value="" title="'.__('Remove input field', 'cforms').'" alt="'.__('Remove input field', 'cforms').'" onfocus="this.blur()"/>';

                            if( in_array($field_type,array('hidden','checkboxgroup', 'fieldsetstart','fieldsetend','ccbox','captcha','verification','textonly')) )
                                echo '<img class="chkno" src="'.$cforms_root.'/images/chkbox_grey.gif" alt="'.__('n/a', 'cforms').'" title="'.__('Not available.', 'cforms').'"/>';
                            else
                                echo '<input tabindex="'.($ti++).'" class="allchk fieldisreq chkfld" type="checkbox" title="'.__('input required', 'cforms').'" name="field_'.($i).'_required" value="required"'.($field_required == '1'?' checked="checked"':'').'/>';


                            if( ! in_array($field_type,array('textfield','youremail','friendsemail','email')) )
                                echo '<img class="chkno" src="'.$cforms_root.'/images/chkbox_grey.gif" alt="'.__('n/a', 'cforms').'" title="'.__('Not available.', 'cforms').'"/>';
                            else
                                echo '<input tabindex="'.($ti++).'" class="allchk fieldisemail chkfld" type="checkbox" title="'.__('email required', 'cforms').'" name="field_'.($i).'_emailcheck" value="required"'.($field_emailcheck == '1'?' checked="checked"':'').'/>';


                            if( ! in_array($field_type,array('pwfield','textarea','textfield','datepicker','yourname','youremail','friendsname','friendsemail','email','author','url','comment')) )
                                echo '<img class="chkno" src="'.$cforms_root.'/images/chkbox_grey.gif" alt="'.__('n/a', 'cforms').'" title="'.__('Not available.', 'cforms').'"/>';
                            else
                                echo '<input tabindex="'.($ti++).'" class="allchk fieldclear chkfld" type="checkbox" title="'.__('clear field', 'cforms').'" name="field_'.($i).'_clear" value="required"'.($field_clear == '1'?' checked="checked"':'').'/>';


                            if( ! in_array($field_type,array('pwfield','textarea','textfield','datepicker','checkbox','checkboxgroup','selectbox','multiselectbox','radiobuttons','upload')) )
                                echo '<img class="chkno" src="'.$cforms_root.'/images/chkbox_grey.gif" alt="'.__('n/a', 'cforms').'" title="'.__('Not available.', 'cforms').'"/>';
                            else
                                echo '<input tabindex="'.($ti++).'" class="allchk fielddisabled chkfld" type="checkbox" title="'.__('disabled', 'cforms').'" name="field_'.($i).'_disabled" value="required"'.($field_disabled == '1'?' checked="checked"':'').'/>';


                            if( ! in_array($field_type,array('pwfield','textarea','textfield','datepicker','checkbox','checkboxgroup','selectbox','multiselectbox','radiobuttons','upload')) )
                                echo '<img class="chkno" src="'.$cforms_root.'/images/chkbox_grey.gif" alt="'.__('n/a', 'cforms').'" title="'.__('Not available.', 'cforms').'"/>';
                            else
                                echo '<input tabindex="'.($ti++).'" class="allchk fieldreadonly chkfld" type="checkbox" title="'.__('read-only', 'cforms').'" name="field_'.($i).'_readonly" value="required"'.($field_readonly == '1'?' checked="checked"':'').'/>';

                        ?></div> <!--itemContent-->

                    </div> <!--groupItem-->

            <?php   }   ### for loop
                    if( $fieldsadded )
                        update_option('cforms_settings',$cformsSettings);
            ?>
		</div> <!--groupWrapper-->

		<p class="addfieldbox">
            <input tabindex="<?php echo $ti++;?>" type="submit" name="AddField" class="allbuttons addbutton" title="<?php _e('Add more input field(s)', 'cforms'); ?>" value="** <?php _e('Add', 'cforms'); ?> **" onfocus="this.blur()" onclick="javascript:document.mainform.action='#anchorfields';" />
        	<input tabindex="<?php echo $ti++;?>" type="text" name="AddFieldNo" value="1" class="addfieldno"/><?php _e('new field(s) @ position', 'cforms'); ?>
			<select tabindex="<?php echo $ti++;?>" name="AddFieldPos" class="addfieldno">
			<?php
	            for($i = 1; $i <= $field_count; $i++)
    	        	echo '<option value="'.$i.'">'.$i.'</option>';
			?>
            </select>

	        <input type="hidden" name="field_order" value="" />
	        <input type="hidden" name="field_count_submit" value="<?php echo($field_count); ?>" />
        </p>

	</fieldset>


    <?php if( $fileupload) : ?>
	<fieldset id="fileupload" class="cformsoptions">
			<div class="cflegend op-closed" id="p0" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('File Upload Settings', 'cforms')?>
            </div>

			<div class="cf-content" id="o0">
				<p>
					<?php echo sprintf(__('Configure and double-check these settings in case you are adding a "<code>File Upload Box</code>" to your form (also see the <a href="%s" %s>Help!</a> for further information).', 'cforms'),'?page='.$plugindir.'/cforms-help.php#upload','onclick="setshow(19)"'); ?>
					<?php echo sprintf(__('You may also want to verify the global, file upload specific  <a href="%s" %s>error messages</a>.', 'cforms'),'?page='.$plugindir.'/cforms-global-settings.php#upload','onclick="setshow(11)"'); ?>
				</p>

			    <?php
			    $temp = explode( '$#$',stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_upload_dir'])) );
			    $fileuploaddir = $temp[0];
			    $fileuploaddirurl = $temp[1];
				if ( $fileupload && !file_exists($fileuploaddir) ) {
			        echo '<div class="updated fade"><p>' . __('Can\'t find the specified <strong>Upload Directory</strong> ! Please verify that it exists!', 'cforms' ) . '</p></div>';
			    }
				?>
				<table class="form-table">
				<tr class="ob space15">
					<td class="obL"><label for="cforms_upload_dir"><strong><?php _e('Upload directory (absolute path)', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_upload_dir" name="cforms_upload_dir" value="<?php echo $fileuploaddir; ?>"/> <?php _e('e.g. /home/user/www/wp-content/my-upload-dir', 'cforms') ?></td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_upload_dir_url"><strong><?php _e('Upload directory URL (relative path/URL)', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_upload_dir_url" name="cforms_upload_dir_url" value="<?php echo $fileuploaddirurl; ?>"/> <?php _e('e.g. /wp-content/my-upload-dir', 'cforms') ?></td>
				</tr>

				<tr class="ob space10">
					<td class="obL"><label for="cforms_upload_noid"><strong><?php _e('Disable noid- (tracking ID) prefix', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_upload_noid" name="cforms_upload_noid" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_noid']=='1') echo "checked=\"checked\""; ?>/></td>
				</tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_upload_ext"><strong><?php _e('Allowed file extensions', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_upload_ext" name="cforms_upload_ext" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_upload_ext'])); ?>"/> <?php _e('[empty=all files are allowed]', 'cforms') ?></td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_upload_size"><strong><?php _e('Maximum file size<br />in kilobyte', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_upload_size" name="cforms_upload_size" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_upload_size'])); ?>"/></td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_noattachments"><strong><?php _e('Do not email attachments', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_noattachments" name="cforms_noattachments" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_noattachments']=='1') echo "checked=\"checked\""; ?>/><br /><?php echo sprintf(__('<u>Note</u>: Attachments are stored on the server &amp; can be accessed via the <a href="%s" %s>cforms tracking</a> tables.', 'cforms'),'?page='. $plugindir.'/cforms-global-settings.php#tracking','onclick="setshow(14)"'); ?></td>
				</tr>
				</table>
			</div>
		</fieldset>
    <?php endif; ?>


		<fieldset class="cformsoptions" id="anchormessage">
			<div class="cflegend op-closed" id="p1" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Messages, Text and Button Label', 'cforms')?>
            </div>

			<div class="cf-content" id="o1">
				<p><?php echo sprintf(__('These are the messages displayed to the user on successful (or failed) form submission. These messages are form specific, a general message for entering a wrong <strong>visitor verification code</strong> can be found <a href="%s" %s>here</a>.', 'cforms'),'?page='.$plugindir.'/cforms-global-settings.php#visitorv','onclick="setshow(13)"'); ?></p>

				<table class="form-table">

				<tr class="ob">
					<td class="obL"><label for="cforms_submit_text"><strong><?php _e('Submit button text', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_submit_text" id="cforms_submit_text" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_submit_text']));  ?>" /></td>
				</tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_working"><strong><?php _e('Waiting message', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_working" id="cforms_working" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_working']));  ?>" /></td>
				</tr>
				<tr class="ob space15">
					<td class="obL"><label for="cforms_required"><strong><?php _e('"required" label', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_required" id="cforms_required" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_required'])); ?>"/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_emailrequired"><strong><?php _e('"email required" label', 'cforms'); ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_emailrequired" id="cforms_emailrequired" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_emailrequired'])); ?>"/></td>
				</tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_success"><?php _e('<strong>Success message</strong><br />when form filled out correctly', 'cforms'); ?></label></td>
					<td class="obR">
                    	<table><tr>
						<td><textarea class="resizable" rows="80px" cols="200px" name="cforms_success" id="cforms_success"><?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_success'])); ?></textarea></td>
						<td><input class="allchk" type="checkbox" id="cforms_popup1" name="cforms_popup1" <?php if(substr($cformsSettings['form'.$no]['cforms'.$no.'_popup'],0,1)=="y") echo "checked=\"checked\""; ?>/><label for="cforms_popup1"><?php _e('Opt. Popup Msg', 'cforms'); ?></label></td>
                    	</tr></table>
					</td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_failure"><?php _e('<strong>Failure message</strong><br />when missing fields or wrong field<br />formats (regular expr.)', 'cforms'); ?></label></td>
					<td class="obR">
                    	<table><tr>
						<td><textarea class="resizable" rows="80px" cols="200px" name="cforms_failure" id="cforms_failure" ><?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_failure'])); ?></textarea></td>
						<td><input class="allchk" type="checkbox" id="cforms_popup2" name="cforms_popup2" <?php if(substr($cformsSettings['form'.$no]['cforms'.$no.'_popup'],1,1)=="y") echo "checked=\"checked\""; ?>/><label for="cforms_popup2"><?php _e('Opt. Popup Msg', 'cforms'); ?></label></td>
                    	</tr></table>
					</td>
				</tr>
				<tr class="ob space15">
					<td class="obL"><label for="cforms_showposa"><strong><?php _e('Show messages', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_showposa" name="cforms_showposa" <?php if(substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],0,1)=="y") echo "checked=\"checked\""; ?>/><label for="cforms_showposa"><?php _e('Above form', 'cforms'); ?></label><br />
						<input class="allchk" type="checkbox" id="cforms_showposb" name="cforms_showposb" <?php if(substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],1,1)=="y") echo "checked=\"checked\""; ?>/><label for="cforms_showposb"><?php _e('Below form', 'cforms'); ?></label>
					</td>
				</tr>

				<tr class="obSEP"><td colspan="2"></td></tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_jump"><strong><?php _e('Jump to Error', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_jump" name="cforms_jump" <?php if(substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],4,1)=="y") echo "checked=\"checked\""; ?>/><label for="cforms_jump"><?php _e('(Only Javascript)', 'cforms'); ?></label>
					</td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_errorLI"><strong><?php _e('Fancy Error messages', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_errorLI" name="cforms_errorLI" <?php if(substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],2,1)=="y") echo "checked=\"checked\""; ?>/><label for="cforms_errorLI"><?php _e('Enhanced display of errors (see also new theme CSS classes)', 'cforms'); ?></label>
					</td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_errorINS"><strong><?php _e('Embedded Custom Error<br />Messages', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_errorINS" name="cforms_errorINS" <?php if(substr($cformsSettings['form'.$no]['cforms'.$no.'_showpos'],3,1)=="y") echo "checked=\"checked\""; ?>/><label for="cforms_errorINS"><?php _e('Field specific error messages will be shown above input field', 'cforms'); ?></label>
					</td>
				</tr>
		 		</table>
			</div>
		</fieldset>


		<fieldset class="cformsoptions" id="anchoremail">
			<div class="cflegend op-closed" id="p2" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Core Form Admin / Email Options', 'cforms')?>
            </div>

			<div class="cf-content" id="o2">
				<p><?php echo sprintf(__('These settings determine basic cforms behaviour and the form email recipient(s). Both %s and %s formats are valid, but check if your mail server does accept the format of choice!', 'cforms'),'"<strong>xx@yy.zz</strong>"','"<strong>abc &lt;xx@yy.zz&gt;</strong>"') ?></p>
				<p><?php _e('The default FROM: address is based on your blog\'s name and the WP default address. It can be changed, but I highly recommend you do not, as it may render the plugin useless. If you do change the FROM: address, triple check if all admin emails are being sent/received! ', 'cforms') ?></p>

				<table class="form-table">

                <tr class="ob">
                    <td class="obL"><strong><?php _e('Core options', 'cforms') ?></strong></td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_formaction" name="cforms_formaction" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_formaction']) echo "checked=\"checked\""; ?>/><label for="cforms_formaction"><?php echo sprintf(__('Disable %s multipart/form-data enctype %s, e.g. to enable salesforce.com', 'cforms'),'<strong>','</strong>'); ?></label>
		 			</td>
                </tr>

				<tr class="ob space10">
					<td class="obL"></td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_dontclear" name="cforms_dontclear" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_dontclear']) echo "checked=\"checked\""; ?>/><label for="cforms_dontclear"><?php echo sprintf(__('%sDo not reset%s input fields after submission', 'cforms'),'<strong>','</strong>'); ?></label>
		 			</td>
	  			</tr>

				<?php if( $cformsSettings['global']['cforms_showdashboard'] == '1' ) : ?>
					<tr class="ob space10">
						<td class="obL"></td>
						<td class="obR"><input class="allchk" type="checkbox" id="cforms_dashboard" name="cforms_dashboard" <?php if($o=$cformsSettings['form'.$no]['cforms'.$no.'_dashboard']=='1') echo "checked=\"checked\""; ?>/><label for="cforms_dashboard"><?php echo sprintf(__('Show new entries on %sdashboard%s', 'cforms'),'<strong>','</strong>') ?></label></td>
		  			</tr>
				<?php endif; ?>

				<?php if( $cformsSettings['global']['cforms_database'] == '1' ) : ?>
					<tr class="ob">
						<td class="obL"></td>
						<td class="obR"><input class="allchk" type="checkbox" id="cforms_notracking" name="cforms_notracking" <?php if( $cformsSettings['form'.$no]['cforms'.$no.'_notracking'] ) echo "checked=\"checked\""; ?>/><label for="cforms_notracking"><?php echo sprintf(__('%sExclude this form%s from database tracking', 'cforms'),'<strong>','</strong>') ?></label></td>
		  			</tr>
				<?php endif; ?>

				<tr class="ob">
					<td class="obL"></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_customnames" name="cforms_customnames" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_customnames']=='1') echo "checked=\"checked\""; ?>/><label for="cforms_customnames"><?php echo sprintf(__('Use %scustom input field NAMES &amp; ID\'s%s', 'cforms'),'<strong>','</strong>') ?></label> <a class="infobutton" href="#" name="it4"><?php _e('Read note &raquo;', 'cforms'); ?></a></td>
				</tr>

				<tr id="it4" class="infotxt"><td>&nbsp;</td><td class="ex"><?php _e('This feature replaces the default NAMEs/IDs (e.g. <strong>cf_field_12</strong>) with <em>custom ones</em>, either derived from the field label you have provided or by specifically declaring it via <strong>[id:XYZ]</strong>,e.g. <em>Your Name[id:the-name]</em>. This will for instance help to feed data to third party systems (requiring certain input field names in the $_POST variable).', 'cforms') ?></td></tr>

				<tr class="ob">
					<td class="obL"></td>
					<td class="obR"><input <?php echo ($isTAF==1||$isTAF==2)?'disabled="disabled" class="allchk disabled"':'class="allchk"'; ?> type="checkbox" id="cforms_taftrick" name="cforms_taftrick" <?php if($isTAF=='3') echo "checked=\"checked\""; ?>/><label for="cforms_taftrick"><?php echo sprintf(__('%sExtra variables%s e.g. {Title}', 'cforms'),'<strong>','</strong>') ?></label> <a class="infobutton" href="#" name="it5"><?php _e('Read note &raquo;', 'cforms'); ?></a></td>
				</tr>

				<tr id="it5" class="infotxt"><td>&nbsp;</td><td class="ex"><?php echo sprintf(__('There are <a href="%s" %s>three additional</a>, <em>predefined variables</em> that belong to the Tell-A-Friend feature but can be enabled here without actually turning on T-A-F.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#tafvariables','onclick="setshow(23)"'); ?> <strong><u><?php _e('Note:','cforms')?></u></strong> <?php _e('This will add two more hidden fields to your form to ensure that all data is available also in AJAX mode.','cforms')?></td></tr>

				<tr class="ob">
					<td class="obL"></td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_hide" name="cforms_hide" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_hide']) echo "checked=\"checked\""; ?>/><label for="cforms_hide"><?php echo sprintf(__('%sHide form%s after successful submission', 'cforms'),'<strong>','</strong>'); ?></label>
		 			</td>
				</tr>

				<tr class="obSEP"><td colspan="2"></td></tr>

				<tr class="ob">
					<td class="obL"><strong><?php _e('Submission Limit', 'cforms'); ?></strong></td>
					<td class="obR"><input type="text" id="cforms_maxentries" name="cforms_maxentries" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_maxentries'])); ?>"/><label for="cforms_maxentries"><?php _e('<u>total</u> # of submissions accepted [<strong>empty or 0 (zero) = off</strong>] (tracking must be enabled!)', 'cforms') ?></label></td>
				</tr>

				<tr class="ob">
					<td class="obL" style="padding-top:7px"><strong><?php _e('Start Date', 'cforms'); ?></strong></td>
					<?php $date = explode(' ',stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_startdate'])) ); ?>
					<td class="obR">
                    	<input type="text" class="cf_date" id="cforms_startdate" name="cforms_startdate" value="<?php echo $date[0]; ?>"/>
                        <input type="text" id="cforms_starttime" name="cforms_starttime" value="<?php echo $date[1]; ?>"/><a class="cf_timebutt1" href="javascript:void(0);"><img src="<?php echo $cforms_root; ?>/images/clock.gif" alt="" title="<?php _e('Time entry.', 'cforms') ?>"/></a>
						<label for="cforms_startdate"><?php
						$dt='x';
                        if( strlen($cformsSettings['form'.$no]['cforms'.$no.'_startdate'])>1 ):
                            $dt = cf_make_time(stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_startdate'])) - time();
							if( $dt>0 ):
	                                echo __('The form will be available in ', 'cforms').sec2hms($dt);
	                            else:
	                                echo __('The form is available now.', 'cforms');
							endif;
						endif;
                        ?>
                        </label>
                    </td>
				</tr>

				<tr class="ob">
					<td class="obL" style="padding-top:7px"><strong><?php _e('End Date', 'cforms'); ?></strong></td>
					<?php $date = explode(' ',stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_enddate'])) ); ?>
					<td class="obR">
                    	<input type="text" class="cf_date" id="cforms_enddate" name="cforms_enddate" value="<?php echo $date[0]; ?>"/>
                        <input type="text" id="cforms_endtime" name="cforms_endtime" value="<?php echo $date[1]; ?>"/><a class="cf_timebutt2" href="javascript:void(0);"><img src="<?php echo $cforms_root; ?>/images/clock.gif" alt="" title="<?php _e('Time entry.', 'cforms') ?>"/></a>
						<label for="cforms_startdate"><?php
                        if( $dt=='x' && strlen($cformsSettings['form'.$no]['cforms'.$no.'_enddate'])>1 ):
                            $dt = cf_make_time(stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_enddate'])) - time();
							if( $dt>0 ):
	                                echo __('The form will be available for another ', 'cforms').sec2hms($dt);
	                            else:
	                                echo __('The form is not available anymore.', 'cforms');
							endif;
						endif;
                        ?></label>
                    </td>
				</tr>

				<?php if( $cformsSettings['form'.$no]['cforms'.$no.'_maxentries'] <> '' || $cformsSettings['form'.$no]['cforms'.$no.'_startdate'] <> '' || $cformsSettings['form'.$no]['cforms'.$no.'_enddate'] <> '' ) : ?>
				<tr class="ob">
	            	<td class="obL"><label for="cforms_limittxt"><strong><?php _e('Limit text', 'cforms'); ?></strong></label></td>
	                <td class="obR"><table><tr><td><textarea class="resizable" rows="80px" cols="200px" name="cforms_limittxt" id="cforms_limittxt"><?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_limittxt'])); ?></textarea></td></tr></table></td>
				</tr>
				<?php endif; ?>

				<tr class="obSEP"><td colspan="2"></td></tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_redirect"><?php _e('<strong>Redirect</strong><br />options:', 'cforms'); ?></label></td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_redirect" name="cforms_redirect" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_redirect']) echo "checked=\"checked\""; ?>/><label for="cforms_redirect"><?php _e('Enable alternative success page (redirect)', 'cforms'); ?></label><br />
						<input name="cforms_redirect_page" id="cforms_redirect_page" value="<?php echo ($cformsSettings['form'.$no]['cforms'.$no.'_redirect_page']);  ?>" />
		 			</td>
				</tr>

				<tr class="obSEP"><td colspan="2"></td></tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_action"><?php _e('<strong>Send form data</strong><br /> to an alternative page:', 'cforms'); ?></label></td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_action" name="cforms_action" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_action']) echo "checked=\"checked\""; ?>/><label for="cforms_action"><?php _e('Enable alternative form action!', 'cforms'); ?></label><br />
						<input name="cforms_action_page" id="cforms_action_page" value="<?php echo ($cformsSettings['form'.$no]['cforms'.$no.'_action_page']);  ?>" /> <a class="infobutton" href="#" name="it2"><?php _e('Please read note &raquo;', 'cforms'); ?></a>
		 			</td>
				</tr>

				<tr id="it2" class="infotxt"><td>&nbsp;</td><td class="ex"><?php _e('If you enable an alternative <strong>form action</strong> you <u>will loose any cforms application logic</u> (spam security, field validation, DB tracking etc.) in non-ajax mode! This setting is really only for developers that require additional capabilities around forwarding of form data and will turn cforms into a front-end only, a "form builder" so to speak.', 'cforms') ?></td></tr>

				<tr class="obSEP"><td colspan="2"></td></tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_rss"><strong><?php _e('RSS feed', 'cforms'); ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_rss" name="cforms_rss" <?php if( $cformsSettings['form'.$no]['cforms'.$no.'_rss'] ) echo "checked=\"checked\""; ?>/> <?php _e('Enable RSS feed to track new submissions', 'cforms'); ?>  <a class="infobutton" href="#" name="it10"><?php _e('Please read note &raquo;', 'cforms'); ?></a></td>
				</tr>

				<tr id="it10" class="infotxt"><td>&nbsp;</td><td class="ex"><?php echo sprintf(__('For the RSS feed to work you must have %sDatabase Tracking%s turned on under <em>Global-Settings</em>! In order to pick &amp; include input fields in your feed your Tracking page must show at least one record for reference.', 'cforms'),'<strong>','</strong>'); ?></td></tr>

				<?php if( current_user_can('track_cforms') && $cformsSettings['form'.$no]['cforms'.$no.'_rss'] ) : ?>
				<tr class="ob">
					<td class="obL"></td>
					<td class="obR">
						<?php $j = $cformsSettings['form'.$no]['cforms'.$no.'_rss_count']; $j = (int)abs($j)>20 ? 20:(int)abs($j); ?>
						<select name="cforms_rsscount" id="cforms_rsscount"><?php for ($i=1;$i<=20;$i++) echo '<option'.(($i==$j)?' selected="selected"':'').'>' .$i. '</option>'; ?></select>
                    	<label for="cforms_rsscount"><?php _e('Number of shown RSS entries', 'cforms'); ?></label>
                    </td>
				</tr>

				<tr class="ob">
					<td class="obL"></td>
					<td class="obR">
						<label for="cforms_rssfields[]"><?php _e('Form fields included in feed:', 'cforms'); ?></label>
                    	<select name="cforms_rssfields[]" id="cforms_rssfields"  multiple="multiple">
                        <?php
                        	$f = $cformsSettings['form'.$no]['cforms'.$no.'_rss_fields'];
							$entries = $wpdb->get_results("SELECT * FROM {$wpdb->cformsdata} WHERE sub_id = (SELECT id FROM {$wpdb->cformssubmissions} WHERE form_id='$no' LIMIT 0,1)");
							foreach($entries as $e){
                            	if ($e->field_name <> 'page') echo '<option value="'.$e->field_name.'"'.( array_search($e->field_name,$f)!==false?' selected="selected"':'' ).'>'.stripslashes($e->field_name).'</option>';
                            }
                        ?>
						</select>
                    </td>
				</tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_rsskey"><strong><?php _e('RSS Feed Security Key', 'cforms'); ?></strong></label></td>
					<td class="obR">
						<input name="cforms_rsskey" id="cforms_rsskey" value="<?php echo $cformsSettings['form'.$no]['cforms'.$no.'_rsskey'];  ?>" />
						<input type="submit" name="cforms_rsskeysnew" id="cforms_rsskeysnew" value="<?php _e('Reset RSS Key', 'cforms');  ?>" class="allbuttons"  onclick="javascript:document.mainform.action='#anchoremail';"/>
                    </td>
				</tr>
				<tr class="ob">
					<td class="obL"></td>
					<td class="obR"><?php _e('The complete RSS URL &raquo;', 'cforms'); echo '<br />'.get_option('siteurl').'?cformsRSS='.$no.urlencode('$#$').$cformsSettings['form'.$no]['cforms'.$no.'_rsskey']; ?></td>
				</tr>
				<?php endif; ?>
				</table>
			</div>
		</fieldset>


		<fieldset class="cformsoptions" id="emailoptions">
			<div class="cflegend op-closed" id="p3" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Admin Email Message Options', 'cforms')?>
            </div>

			<div class="cf-content" id="o3">
				<p><?php _e('Generally, emails sent to the admin and the submitting user can be both in plain text and HTML. The TXT part <strong>is required</strong>, HTML is <strong>optional</strong>.', 'cforms'); ?></p>
				<p><?php echo sprintf(__('Below you\'ll find the settings for both the <strong>TXT part</strong> of the admin email as well as the <strong>optional HTML part</strong> of the message. Both areas permit the use of any of the <strong>pre-defined variables</strong> or <strong>data from input fields</strong>. <a href="%s" %s>Please see the documentation on the HELP page</a> (including HTML message examples!).', 'cforms'),'?page='. $plugindir.'/cforms-help.php#variables','onclick="setshow(23)"'); ?></p>

				<table class="form-table">
                <tr class="ob space15">
                    <td class="obL"></td>
                    <td class="obR"><input class="allchk" type="checkbox" id="cforms_emailoff" name="cforms_emailoff" <?php if($cformsSettings['form'.$no]['cforms'.$no.'_emailoff']=='1') echo "checked=\"checked\""; ?>/><label for="cforms_emailoff"><?php echo sprintf(__('%sTurn off%s admin email', 'cforms'),'<strong>','</strong>') ?></label></td>
                </tr>
				</table>

				<table class="form-table<?php if( $cformsSettings['form'.$no]['cforms'.$no.'_emailoff']=='1' ) echo " hidden"; ?>">
                <tr class="ob space15">
					<td class="obL"><label for="cforms_fromemail"><strong><?php _e('FROM: email address', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_fromemail" id="cforms_fromemail" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_fromemail'])); ?>" /></td>
				</tr>

				<tr class="ob space15">
					<td class="obL"><label for="cforms_email"><strong><?php _e('Admin email address(es)', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_email" id="cforms_email" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_email'])); ?>" /> <a class="infobutton" href="#" name="it1"><?php _e('More than one "<strong>form admin</strong>"? &raquo;', 'cforms'); ?></a></td>
				</tr>

				<tr id="it1" class="infotxt"><td>&nbsp;</td><td class="ex"><?php echo sprintf(__('Simply add additional email addresses separated by a <strong style="color:red">comma</strong>. &nbsp; <em><u>Note:</u></em> &nbsp; If you want the visitor to choose from any of these per select box, you need to add a "<code>Multiple Recipients</code>" input field <a href="#anchorfields">above</a> (see the HELP section for <a href="%s" %s>more details</a>. If <strong>no</strong> "Multiple Recipients" input field is defined above, the submitted form data will go out to <strong>every address listed</strong>!', 'cforms'),'?page='.$plugindir.'/cforms-help.php#multirecipients','onclick="setshow(19)"'); ?></td></tr>

				<tr class="ob">
					<td class="obL"><label for="cforms_bcc"><strong><?php _e('BCC', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_bcc" id="cforms_bcc" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_bcc'])); ?>" /></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_subject"><strong><?php _e('Subject admin email', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_subject" id="cforms_subject" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_subject'])); ?>" /> <?php echo sprintf(__('<a href="%s" %s>Variables</a> allowed.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#variables','onclick="setshow(23)"'); ?></td>
				</tr>

                <tr class="ob">
                    <td class="obL"></td>
                    <td class="obR">
						<?php $p = ((int)$cformsSettings['form'.$no]['cforms'.$no.'_emailpriority']>0)?(int)$cformsSettings['form'.$no]['cforms'.$no.'_emailpriority']:3; ?>
						<select name="emailprio" id="emailprio"><?php for ($i=1;$i<=5;$i++) echo '<option'.(($i==$p)?' selected="selected"':'').'>' .$i. '</option>'; ?></select>
                        <label for="emailprio"><?php echo sprintf(__('Email %spriority%s (1 = High, 3 = Normal, 5 = Low)', 'cforms'),'<strong>','</strong>') ?></label>
                    </td>
                </tr>

				<tr class="ob space15">
					<td class="obL" style="padding-bottom:0"><label for="cforms_header"><?php _e('<strong>Admin TEXT message</strong><br />(Header)', 'cforms') ?></label></td>
					<td class="obR" style="padding-bottom:0">
                    	<table><tr>
						<td><textarea class="resizable" rows="80px" cols="200px" name="cforms_header" id="cforms_header" ><?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_header'])); ?></textarea></td>
						<td><?php echo sprintf(__('<a href="%s" %s>Variables</a> allowed.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#variables','onclick="setshow(23)"'); ?></td>
                    	</tr></table>
		 			</td>
				</tr>
				<tr class="ob">
					<td class="obL" style="padding-top:0"><?php _e('(Footer)','cforms')?></td>
					<td class="obR" style="padding-top:0"><input class="allchk" type="checkbox" id="cforms_formdata_txt" name="cforms_formdata_txt" <?php if(substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],0,1)=='1') echo "checked=\"checked\""; ?>/><label for="cforms_formdata_txt"><?php _e('<strong>Include</strong>  user input at the bottom of the admin email', 'cforms') ?></label></td>
				</tr>
				<tr class="ob">
					<td class="obL" style="padding-top:0">&nbsp;</td>
					<td class="obR" style="padding-top:0"><input type="text" name="cforms_space" id="cforms_space" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_space'])); ?>" /><label for="cforms_space"><?php _e('(# characters) : spacing between labels &amp; data, for plain txt version only', 'cforms') ?></label></td>
				</tr>

				<tr class="ob space20">
					<td class="obL"><label for="cforms_admin_html"><strong><?php _e('Enable HTML', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_admin_html" name="cforms_admin_html" <?php if($o=substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],2,1)=='1') echo "checked=\"checked\""; ?>/></td>
				</tr>

				<tr class="ob <?php if( !$o=='1' ) echo "hidden"; ?>">
					<td class="obL" style="padding-bottom:0"><label for="cforms_header_html"><?php _e('<strong>Admin HTML message</strong><br />(Header)', 'cforms') ?></label></td>
					<td class="obR" style="padding-bottom:0">
                    	<table><tr>
						<td><textarea class="resizable" rows="80px" cols="200px" name="cforms_header_html" id="cforms_header_html" ><?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_header_html'])); ?></textarea></td>
						<td><?php echo sprintf(__('<a href="%s" %s>Variables</a> allowed.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#variables','onclick="setshow(23)"'); ?></td>
                    	</tr></table>
		 			</td>
				</tr>
				<tr class="ob <?php if( !$o=='1' ) echo "hidden"; ?>">
					<td class="obL" style="padding-top:0"><?php _e('(Footer)','cforms')?></td>
					<td class="obR" style="padding-top:0"><input class="allchk" type="checkbox" id="cforms_formdata_html" name="cforms_formdata_html" <?php if(substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],1,1)=='1') echo "checked=\"checked\""; ?>/><label for="cforms_formdata_html"><?php _e('<strong>Include</strong> user input at the bottom of the admin email', 'cforms') ?></label></td>
				</tr>
				<tr><td>&nbsp;</td><td><a class="infobutton" href="#" name="it3"><?php _e('\'Don\'t like the default form data block in your admin email?  &raquo;', 'cforms'); ?></a></td></tr>
				<tr id="it3" class="infotxt"><td>&nbsp;</td><td class="ex"><strong><u><?php _e('Note:','cforms')?></u></strong> <?php _e('To avoid sending ALL of the submitted user data (especially for very long forms) to the form admin simply <strong>uncheck</strong> "<em>Include user input ...</em>" and instead specify the fields you\'d like to receive via the use of <strong>custom variables</strong>.', 'cforms'); ?></td></tr>
				</table>
			</div>
		</fieldset>


		<fieldset class="cformsoptions <?php if( !$ccboxused ) echo "hidden"; ?>" id="cc">
			<div class="cflegend op-closed" id="p4" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('CC Settings', 'cforms')?>
            </div>

			<div class="cf-content" id="o4">
				<p><?php _e('This is the subject of the CC email that goes out the user submitting the form and as such requires the <strong>CC:</strong> field in your form definition above.', 'cforms') ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL"><label for="cforms_ccsubject"><strong><?php _e('Subject CC', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_ccsubject" id="cforms_ccsubject" value="<?php $t=explode('$#$',$cformsSettings['form'.$no]['cforms'.$no.'_csubject']); echo stripslashes(htmlspecialchars($t[1])); ?>" /> <?php echo sprintf(__('<a href="%s" %s>Variables</a> allowed.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#variables','onclick="setshow(23)"'); ?></td>
				</tr>
				</table>

			</div>
		</fieldset>


		<fieldset class="cformsoptions" id="autoconf">
			<div class="cflegend op-closed" id="p5" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Auto Confirmation', 'cforms')?>
            </div>

			<div class="cf-content" id="o5">
				<p><?php _e('These settings apply to an auto response/confirmation sent to the visitor. If enabled AND your form contains a "<code>CC me</code>" field <strong>AND</strong> the visitor selected it, no extra confirmation email is sent!', 'cforms') ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR">
						<input class="allchk" type="checkbox" id="cforms_confirm" name="cforms_confirm" <?php if($o=$cformsSettings['form'.$no]['cforms'.$no.'_confirm']=="1") echo "checked=\"checked\""; ?>/><label for="cforms_confirm"><strong><?php _e('Activate auto confirmation', 'cforms') ?></strong></label><br />
						<a class="infobutton" href="#" name="it8"><?php _e('Please read note &raquo;', 'cforms'); ?></a>
		 			</td>
				</tr>
				<tr id="it8" class="infotxt"><td>&nbsp;</td><td class="ex"><?php _e('For the <em>auto confirmation</em> feature to work, make sure to mark at least one field <code>Email</code>, otherwise <strong>NO</strong> auto confirmation email will be sent out! If multiple fields are checked "Email", only the first in the list will receive a notification.', 'cforms') ?></td></tr>

                <?php if( $o=="1" ) :?>
				<tr class="ob">
					<td class="obL"><label for="cforms_csubject"><strong><?php _e('Subject auto confirmation', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_csubject" id="cforms_csubject" value="<?php $t=explode('$#$',$cformsSettings['form'.$no]['cforms'.$no.'_csubject']); echo stripslashes(htmlspecialchars($t[0])); ?>" /> <?php echo sprintf(__('<a href="%s" %s>Variables</a> allowed.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#variables','onclick="setshow(23)"'); ?></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_cmsg"><strong><?php _e('TEXT message', 'cforms') ?></strong></label></td>
					<td class="obR">
                    	<table><tr>
						<td><textarea class="resizable" rows="80px" cols="200px" name="cforms_cmsg" id="cforms_cmsg" ><?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_cmsg'])); ?></textarea></td>
						<td><?php echo sprintf(__('<a href="%s" %s>Variables</a> allowed.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#variables','onclick="setshow(23)"'); ?></td>
                    	</tr></table>
		 			</td>
				</tr>
				<tr class="ob space15">
					<td class="obL"><label for="cforms_user_html"><strong><?php _e('Enable HTML', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_user_html" name="cforms_user_html" <?php if($o2=substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],3,1)=='1') echo "checked=\"checked\""; ?>/></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_cmsg_html"><strong><?php _e('HTML message', 'cforms') ?></strong></label></td>
					<td class="obR">
                    	<table><tr>
						<td><textarea class="resizable" rows="80px" cols="200px" name="cforms_cmsg_html" id="cforms_cmsg_html" ><?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_cmsg_html'])); ?></textarea></td>
						<td><?php echo sprintf(__('<a href="%s" %s>Variables</a> allowed.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#variables','onclick="setshow(23)"'); ?></td>
                    	</tr></table>
		 			</td>
				</tr>

			    <?php
			    $a=$cformsSettings['form'.$no]['cforms'.$no.'_cattachment'][0];
                $err='';
				$t = (substr($a,0,1)=='/')?$a:dirname(__FILE__).$cformsSettings['global']['cforms_IIS'].$a;
				if ( $t<>'' && !file_exists( $t ) ) {
			        $err = '<br /><p class="error">' . sprintf(__('Can\'t find the specified <strong>Attachment</strong> (%s)! Please verify the server path!', 'cforms' ),$t) . '</p>';
			    }
				?>

				<tr class="ob">
					<td class="obL"><label for="cforms_cattachment"><strong><?php _e('Attachment', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" name="cforms_cattachment" id="cforms_cattachment" value="<?php echo stripslashes(htmlspecialchars($a)); ?>" /> <?php echo sprintf(__('File path: relative to the cforms plugin folder or an absolute path.', 'cforms')); ?><?php echo $err; ?></td>
				</tr>
                <?php endif; ?>

				</table>
			</div>
		</fieldset>


		<fieldset class="cformsoptions" id="multipage">
			<div class="cflegend op-closed" id="p29" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Multi-Part / Multi-Page Forms', 'cforms')?>
            </div>

			<div class="cf-content" id="o29">
				<p><?php _e('If enabled, new options will be shown below.', 'cforms'); ?> <label for="cforms_mp_form"><?php _e('Mark this form to belong to a series of forms:', 'cforms') ?></label> <input class="allchk" type="checkbox" id="cforms_mp_form" name="cforms_mp_form" <?php if( $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_form']=='1' ) echo "checked=\"checked\""; ?>/></p>

				<?php if( $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_form'] ) : ?>

				<table class="form-table">
				<tr class="ob">
					<td class="obL"><strong><?php _e('Email &amp; Tracking', 'cforms') ?></strong></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_mp_email" name="cforms_mp_email" <?php if( $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_email']=='1' ) echo "checked=\"checked\""; ?>/><label for="cforms_mp_email"><?php _e('Suppress admin email and DB tracking for *this* form', 'cforms') ?></label></td>
				</tr>

				<tr class="ob">
					<td class="obL"><strong><?php _e('First Form', 'cforms') ?></strong></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_mp_first" name="cforms_mp_first" <?php if( $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_first']=='1' ) echo "checked=\"checked\""; ?>/><label for="cforms_mp_first"><?php _e('This is the *first* form of a series of forms', 'cforms') ?></label></td>
				</tr>

				<tr class="obSEP"><td colspan="2"></td></tr>

                <tr class="ob">
					<td class="obL"><strong><?php _e('Reset Button', 'cforms') ?></strong></td>
                    <td class="obR"><input class="allchk" type="checkbox" id="cforms_mp_reset" name="cforms_mp_reset" <?php if( $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_reset']=='1' ) echo "checked=\"checked\""; ?>/><label for="cforms_mp_reset"><?php _e('Add a reset button to this form (reset to the first form in a series)', 'cforms') ?></label></td>
				</tr>

				<tr class="ob">
					<td class="obL"><strong><?php _e('Text', 'cforms') ?></strong></td>
					<td class="obR"><input type="text" id="cforms_mp_resettext" name="cforms_mp_resettext" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_resettext'])); ?>"/><label for="cforms_mp_resettext"><?php _e('Text for reset button', 'cforms') ?></label></td>
				</tr>

				<tr class="obSEP"><td colspan="2"></td></tr>

                <tr class="ob">
					<td class="obL"><strong><?php _e('Back Button', 'cforms') ?></strong></td>
                    <td class="obR"><input class="allchk" type="checkbox" id="cforms_mp_back" name="cforms_mp_back" <?php if( $cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_back']=='1' ) echo "checked=\"checked\""; ?>/><label for="cforms_mp_back"><?php _e('Add a back button to this form (back to the previous form)', 'cforms') ?></label></td>
				</tr>

				<tr class="ob">
					<td class="obL"><strong><?php _e('Text', 'cforms') ?></strong></td>
					<td class="obR"><input type="text" id="cforms_mp_backtext" name="cforms_mp_backtext" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_backtext'])); ?>"/><label for="cforms_mp_backtext"><?php _e('Text for back button', 'cforms') ?></label></td>
				</tr>

				<tr class="obSEP"><td colspan="2"></td></tr>

				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR">
					<?php
	                    $formlistbox = ' <select id="picknextform" name="cforms_mp_next"'. ($cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_last']=='1'?' disabled="disabled"':'') .'>';
	                    for ($i=1; $i<=$FORMCOUNT; $i++){
	                        $j   = ( $i > 1 )?$i:'';
	                        $sel = ($cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_next']==$cformsSettings['form'.$j]['cforms'.$j.'_fname'])?' selected="selected"':'';
	                        $formlistbox .= '<option '.$sel.'>'.$cformsSettings['form'.$j]['cforms'.$j.'_fname'].'</option>';
	                    }
                        $formlistbox .= '<option style="background:#F2D7E0;" value="-1" '.(($cformsSettings['form'.$no]['cforms'.$no.'_mp']['mp_next']=='-1')?' selected="selected"':'').'>'.__('* stop here (last form) *', 'cforms').'</option>';
                        $formlistbox .= '</select>';
                        echo $formlistbox;
                    ?>
                        <?php _e('Please choose the next form after this', 'cforms') ?>
		 			</td>
				</tr>
				</table>
				<?php endif; ?>
			</div>
		</fieldset>


		<fieldset class="cformsoptions" id="tellafriend">
			<div class="cflegend op-closed" id="p6" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('Tell-A-Friend Form Support', 'cforms')?>
            </div>

			<div class="cf-content" id="o6">
				<?php
					if ( $allenabled <> false )
						echo '<div id="tafmessage" class="updated fade"><p>'.$allenabled.' '. __('posts and pages processed and tell-a-friend <strong>enabled</strong>.', 'cforms'). ' </p></div>';
					else if ( $alldisabled )
						echo '<div id="tafmessage" class="updated fade"><p>'. __('All posts &amp; pages processed and tell-a-friend <strong>disabled</strong>.', 'cforms'). ' </p></div>';
				?>

                <p class="ex"><?php echo sprintf(__('BEFORE turning on this feature, please see the Help section for <a href="%s" %s>more details.</a>', 'cforms'),'?page='. $plugindir.'/cforms-help.php#taf','onclick="setshow(19)"'); ?></p>
				<p><?php _e('If enabled, new field types will be made available to cover tell-a-friend requirements.', 'cforms'); ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_tellafriend" name="cforms_tellafriend" <?php if( $isTAF==1 ) echo "checked=\"checked\""; ?>/><label for="cforms_tellafriend"><strong><?php _e('Enable Tell-A-Friend', 'cforms') ?></strong></label></td>
				</tr>

				<?php if( $isTAF==1 ) : ?>
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_tafCC" name="cforms_tafCC" <?php if( $cformsSettings['form'.$no]['cforms'.$no.'_tafCC']=='1' ) echo "checked=\"checked\""; ?>/><label for="cforms_tafCC"><strong><?php _e('CC: User submitting the form', 'cforms') ?></strong></label></td>
				</tr>
				<tr class="ob">
					<td class="obL">&nbsp;</td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_tafdefault" name="cforms_tafdefault" <?php if( substr($cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'],1,1)=='1' ) echo "checked=\"checked\""; ?>/><label for="cforms_tafdefault"><strong><?php _e('T-A-F enable <strong>new posts/pages</strong> by default', 'cforms') ?></strong></label></td>
				</tr>

				<tr class="ob space20">
					<td class="obL"><label for="migrate"><?php _e('<strong>Batch T-A-F dis-/enable</strong><br />all your previous posts.', 'cforms') ?></label></td>
					<td class="obR">
						<input type="submit" title="<?php _e('This will add a T-A-F custom field per post/page.', 'cforms') ?>" name="addTAF" class="allbuttons" style="width:150px;" value="<?php _e('Enable all', 'cforms') ?>" onclick="document.mainform.action='#tellafriend'; return confirm('<?php _e('Do you really want to enable all previous posts and pages for T-A-F?', 'cforms') ?>');"/>
						<input type="submit" title="<?php _e('This will remove the T-A-F custom field on all posts/pages.', 'cforms') ?>" name="removeTAF" class="allbuttons" style="width:150px;" value="<?php _e('Disable all', 'cforms') ?>" onclick="document.mainform.action='#tellafriend'; return confirm('<?php _e('Do you really want to disable all previous posts and pages for T-A-F?', 'cforms') ?>');"/>
						<span><a class="infobutton" href="#" name="it9"><?php _e('Please read note &raquo;', 'cforms'); ?></a></span>
		 			</td>
				</tr>
				<tr id="it9" class="infotxt"><td>&nbsp;</td><td class="ex"><?php echo __('You will find a <strong>cforms Tell-A-Friend</strong> checkbox on your <strong>admin/edit page</strong> (typically under "Post/Author")! <br /><u>Check it</u> if you want to have the form to appear on the given post or page.', 'cforms');?></td></tr>
				<?php endif; ?>

				</table>

			</div>
		</fieldset>


		<fieldset class="cformsoptions" id="commentrep">
			<div class="cflegend op-closed" id="p7" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('WP Comment Feature', 'cforms')?>
            </div>

			<div class="cf-content" id="o7">

				<p><?php _e('cforms can be used to replace your <em>default Wordpress comment form</em> (on posts &amp; pages), allowing your readers to either <strong>comment on the post</strong> or <strong>alternatively send the post author a note</strong>!', 'cforms') ?></p>
				<p><?php echo sprintf(__('There will be additional, comment specific <em>input field types</em> available with this feature turned on. For an easy start, use the <em>WP comment form preset</em>. <a href="%s" %s>Configuration details.</a>', 'cforms'),'?page='. $plugindir.'/cforms-help.php#commentrep','onclick="setshow(19)"'); ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL"><label for="cforms_commentrep"><strong><?php _e('WP comment form', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_commentrep" name="cforms_commentrep" <?php if( $isTAF==2 ) echo "checked=\"checked\""; ?>/><label for="cforms_commentrep"><?php _e('Enable this form to optionally (user determined) act as a WP comment form', 'cforms') ?></label></td>
				</tr>
				<tr class="ob">
					<td class="obL"><label for="cforms_commentXnote"><strong><?php _e('Comment notification', 'cforms') ?></strong></label></td>
					<td class="obR"><input class="allchk" type="checkbox" id="cforms_commentXnote" name="cforms_commentXnote" <?php if( substr($cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'],1,1)=='1' ) echo "checked=\"checked\""; ?>/><label for="cforms_commentXnote"><?php _e('Extra comment notification (check your autoresponder setting, too!)', 'cforms') ?></label></td>
				</tr>

	            <?php if( $isTAF==2 ) : ?>
	                <tr><td>&nbsp;</td><td><a class="infobutton" href="#" name="it6"><?php _e('<em>Tell a friend</em> or <em>WP comment</em>? &raquo;', 'cforms'); ?></a></td></tr>
					<tr id="it6" class="infotxt"><td>&nbsp;</td><td class="ex"><?php echo sprintf(__('This feature and T-A-F (above) are mutually exclusive. If you need both features, please create a new form for T-A-F.<br />Again, see the <a href="%s" %s>help section</a> on proper use.', 'cforms'),'?page='. $plugindir.'/cforms-help.php#commentrep','onclick="setshow(19)"'); ?></td></tr>
	                <tr><td>&nbsp;</td><td><a class="infobutton" href="#" name="it7"><?php _e('Important additional configuration requirements &raquo;', 'cforms'); ?></a></td></tr>
					<tr id="it7" class="infotxt"><td>&nbsp;</td><td class="ex"><?php echo sprintf(__('Please see the extended <a href="%s" %s>WP comment options under <em>Global Settings</em></a> for additional configuration requirements. Especially concerning Ajax comment submission! Further, if you enable \'extra comment notification\' make sure you disable the autoresponder, unless you want to thank the user for his/her comment ;-) !', 'cforms'),'?page='. $plugindir.'/cforms-global-settings.php#wpcomment','onclick="setshow(19)"'); ?></td></tr>
	            <?php endif; ?>

				</table>

				<p><?php _e('cforms\' WP comment feature supports the following comment plugins:', 'cforms') ?> <a href="http://www.fiddyp.co.uk/commentluv-wordpress-plugin/">Comment Luv</a>, <a href="http://txfx.net/code/wordpress/subscribe-to-comments/">Subscribe To Comment</a> &amp; <a href="http://www.raproject.com/ajax-edit-comments-20/">WP Ajax Edit Comments</a>!</p>
			</div>
		</fieldset>


		<fieldset class="cformsoptions" id="readnotify">
			<div class="cflegend op-closed" id="p8" title="<?php _e('Expand/Collapse', 'cforms') ?>">
            	<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><div class="blindplus"></div><?php _e('3rd Party Read-Notification Support', 'cforms')?>
            </div>

			<div class="cf-content" id="o8">
				<p><?php echo sprintf(__('If you\'d like to utilize 3rd party email tracking such as %s or %s, add the respective suffix (e.g.: %s) here:', 'cforms'),'<strong>readnotify.com</strong>','<strong>didtheyreadit.com</strong>','<code>.readnotify.com</code>') ?></p>

				<table class="form-table">
				<tr class="ob">
					<td class="obL"><label for="cforms_tracking"><strong><?php _e('Suffix for email tracking', 'cforms') ?></strong></label></td>
					<td class="obR"><input type="text" id="cforms_tracking" name="cforms_tracking" value="<?php echo stripslashes(htmlspecialchars($cformsSettings['form'.$no]['cforms'.$no.'_tracking'])); ?>"/></td>
				</tr>
				</table>
			</div>
		</fieldset>

	    <div class="cf_actions" id="cf_actions">
	        <div class="cflegend op-closed" id="p31"><div class="blindplus"></div><p><?php _e('Admin Actions','cforms'); ?></p></div>
	        <div class="cf-content" id="o31">
                <p class="m1">
                <input class="allbuttons addbutton" type="submit" name="addbutton" title="<?php _e('adds a new form with default values', 'cforms'); ?>" value="<?php _e('Add new form', 'cforms'); ?>"/><br />
                <input class="allbuttons dupbutton" type="submit" name="dupbutton" title="<?php _e('clones the current form', 'cforms'); ?>" value="<?php _e('Duplicate current form', 'cforms'); ?>"/>
                </p>
				<?php
	            	if ( (int)$cformsSettings['global']['cforms_formcount'] > 1)
    	        		echo '<p class="m2"><input class="allbuttons deleteall" title="'.__('Clicking this button WILL delete this form.', 'cforms').'" type="submit" onclick="return confirm(\''.__('This will delete the current form!', 'cforms').'\')" name="delbutton" value="'.__('Delete current form (!)', 'cforms').'"/></p>';
        		?>
				<p class="m3"><input type="button" class="jqModalInstall allbuttons" name="<?php echo $cforms_root; ?>/js/include/" id="preset" value="<?php _e('Install a form preset', 'cforms'); ?>"/></p>
				<p class="m4"><input type="button" class="jqModalBackup allbuttons" name="backup" id="backup" value="<?php _e('Backup and Restore Settings', 'cforms'); ?>"/></p>
	            <p class="m5"><input type="submit" name="SubmitOptions" class="allbuttons updbutton formupd" value="<?php _e('Update Settings &raquo;', 'cforms') ?>" onclick="javascript:document.mainform.action='#'+getFieldset(focusedFormControl);" /></p>
	        </div>
	    </div>

		</form>

	<?php cforms_footer(); ?>
</div>

<?php
add_action('admin_footer', 'insert_cfmodal');
function insert_cfmodal(){
	global $cforms_root,$noDISP;
?>
	<div class="jqmWindow" id="cf_editbox">
		<div class="cf_ed_header jqDrag"><?php _e('Input Field Settings','cforms'); ?></div>
		<div class="cf_ed_main">
			<div id="cf_target"></div>
			<div class="controls"><a href="#" id="ok" class="jqmClose"><img src="<?php echo $cforms_root; ?>/images/dialog_ok.gif" alt="<?php _e('OK', 'cforms') ?>" title="<?php _e('OK', 'cforms') ?>"/></a><a href="#" id="cancel" class="jqmClose"><img src="<?php echo $cforms_root; ?>/images/dialog_cancel.gif" alt="<?php _e('Cancel', 'cforms') ?>" title="<?php _e('Cancel', 'cforms') ?>"/></a></div>
		</div>
	</div>
	<div class="jqmWindow" id="cf_installbox">
		<div class="cf_ed_header jqDrag"><?php _e('cforms Out-Of-The-Box Form Repository','cforms'); ?></div>
		<div class="cf_ed_main">
			<form action="" name="installpreset" method="post">
				<div id="cf_installtarget"></div>
				<div class="controls"><a href="#" id="okInstall" class="jqmClose"><img src="<?php echo $cforms_root; ?>/images/dialog_ok.gif" alt="<?php _e('Install', 'cforms') ?>" title="<?php _e('OK', 'cforms') ?>"/></a><a href="#" id="cancelInstall" class="jqmClose"><img src="<?php echo $cforms_root; ?>/images/dialog_cancel.gif" alt="<?php _e('Cancel', 'cforms') ?>" title="<?php _e('Cancel', 'cforms') ?>"/></a></div>
				<input type="hidden" name="noSub" value="<?php echo $noDISP; ?>"/>
			</form>
		</div>
	</div>
	<div class="jqmWindow" id="cf_backupbox">
		<div class="cf_ed_header jqDrag"><?php _e('Backup &amp; Restore Form Settings','cforms'); ?></div>
		<div class="cf_ed_main_backup">
			<form enctype="multipart/form-data" action="" name="backupform" method="post">
				<div class="controls">

	                <input type="submit" id="savecformsdata" name="savecformsdata" class="allbuttons backupbutton"  value="<?php _e('Backup current form settings', 'cforms'); ?>" onclick="javascript:jQuery('#cf_backupbox').jqmHide();" /><br />
	                <label for="upload"><?php _e(' or restore previously saved settings:', 'cforms'); ?></label>
	                <input type="file" id="upload" name="importall" size="25" />
	                <input type="submit" name="uploadcformsdata" class="allbuttons restorebutton" value="<?php _e('Restore from file', 'cforms'); ?>" onclick="javascript:jQuery('#cf_backupbox').jqmHide();" />

                    <p class="cancel"><a href="#" id="cancel" class="jqmClose"><img src="<?php echo $cforms_root; ?>/images/dialog_cancel.gif" alt="<?php _e('Cancel', 'cforms') ?>" title="<?php _e('Cancel', 'cforms') ?>"/></a></p>

        	    </div>
				<input type="hidden" name="noSub" value="<?php echo $noDISP; ?>"/>
			</form>
		</div>
	</div>
<?php
}
?>