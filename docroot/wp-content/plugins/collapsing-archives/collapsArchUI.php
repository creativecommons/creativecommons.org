<?php
/*
Collapsing Archives version: 1.3.2
Copyright 2007 Robert Felty

This work is largely based on the Fancy Archives plugin by Andrew Rader
(http://nymb.us), which was also distributed under the GPLv2. I have tried
contacting him, but his website has been down for quite some time now. See the
CHANGELOG file for more information.

This file is part of Collapsing Archives

    Collapsing Archives is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Collapsing Archives is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Archives; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

check_admin_referer();

$options=get_option('collapsArchOptions');
$widgetOn=0;
$number='%i%';
if (empty($options)) {
  $number = '-1';
} elseif (!isset($options['%i%']['title']) || 
    count($options) > 1) {
  $widgetOn=1; 
}

if( isset($_POST['resetOptions']) ) {
  if (isset($_POST['reset'])) {
    delete_option('collapsArchOptions');   
		$widgetOn=0;
    $number = '-1';
  }
} elseif (isset($_POST['infoUpdate'])) {
  $style=$_POST['collapsArchStyle'];
  $defaultStyles=get_option('collapsArchDefaultStyles');
  $selectedStyle=$_POST['collapsArchSelectedStyle'];
  $defaultStyles['selected']=$selectedStyle;
  $defaultStyles['custom']=$_POST['collapsArchStyle'];

  update_option('collapsArchStyle', $style);
  update_option('collapsArchSidebarId', $_POST['collapsArchSidebarId']);
  update_option('collapsArchDefaultStyles', $defaultStyles);

  if ($widgetOn==0) {
    include('updateOptions.php');
  }
}
include('processOptions.php');
?>
<div class=wrap>
 <form method="post">
  <h2><? _e('Collapsing Archives Options', 'collapsArch'); ?></h2>
  <fieldset name="Collapsing Archives Options">
    <p>
 <?php _e('Id of the sidebar where collapsing pages appears:', 'collapsArch'); ?>
   <input id='collapsArchSidebarId' name='collapsArchSidebarId' type='text' size='20' value="<?php echo
   get_option('collapsArchSidebarId')?>" onchange='changeStyle("collapsArchStylePreview","collapsArchStyle", "collapsArchDefaultStyles", "collapsArchSelectedStyle", false);' />
   <table>
     <tr>
       <td>
  <input type='hidden' id='collapsArchCurrentStyle' value="<?php echo
stripslashes(get_option('collapsArchStyle')) ?>" />
  <input type='hidden' id='collapsArchSelectedStyle'
  name='collapsArchSelectedStyle' />
<label for="collapsArchStyle"><?php _e('Select style:', 'collapsArch'); ?></label>
       </td>
       <td>
       <select name='collapsArchDefaultStyles' id='collapsArchDefaultStyles'
         onchange='changeStyle("collapsArchStylePreview","collapsArchStyle", "collapsArchDefaultStyles", "collapsArchSelectedStyle", false);' />
       <?php
    $url = get_settings('siteurl') . '/wp-content/plugins/collapsing-archives';
       $styleOptions=get_option('collapsArchDefaultStyles');
       //print_r($styleOptions);
       $selected=$styleOptions['selected'];
       foreach ($styleOptions as $key=>$value) {
         if ($key!='selected') {
           if ($key==$selected) {
             $select=' selected=selected ';
           } else {
             $select=' ';
           }
           echo '<option' .  $select . 'value="'.
               stripslashes($value) . '" >'.$key . '</option>';
         }
       }
       ?>
       </select>
       </td>
       <td><?php _e('Preview', 'collapsArch'); ?><br />
       <img style='border:1px solid' id='collapsArchStylePreview' alt='preview'/>
       </td>
    </tr>
    </table>
    <?php _e('You may also customize your style below if you wish', 'collapsArch'); ?><br />
   <input type='button' value='<?php _e('restore current style', 'collapsArch'); ?>'
onclick='restoreStyle();' /><br />
   <textarea onchange='changeStyle("collapsArchStylePreview","collapsArchStyle", "collapsArchDefaultStyles", "collapsArchSelectedStyle", true);' cols='78' rows='10' id="collapsArchStyle"name="collapsArchStyle"><?php echo stripslashes(get_option('collapsArchStyle'))?></textarea>
    </p>
<script type='text/javascript'>

function changeStyle(preview,template,select,selected,custom) {
  var preview = document.getElementById(preview);
  var pageStyles = document.getElementById(select);
  var selectedStyle;
  var hiddenStyle=document.getElementById(selected);
  var pageStyle = document.getElementById(template);
  if (custom==true) {
    selectedStyle=pageStyles.options[pageStyles.options.length-1];
    selectedStyle.value=pageStyle.value;
    selectedStyle.selected=true;
  } else {
    for(i=0; i<pageStyles.options.length; i++) {
      if (pageStyles.options[i].selected == true) {
        selectedStyle=pageStyles.options[i];
      }
    }
  }
  hiddenStyle.value=selectedStyle.innerHTML
  preview.src='<?php echo $url ?>/img/'+selectedStyle.innerHTML+'.png';
  var sidebarId=document.getElementById('collapsArchSidebarId').value;

  var theStyle = selectedStyle.value.replace(/#[a-zA-Z]+\s/g, '#'+sidebarId + ' ');
  pageStyle.value=theStyle
}

function restoreStyle() {
  var defaultStyle = document.getElementById('collapsArchCurrentStyle').value;
  var pageStyle = document.getElementById('collapsArchStyle');
  pageStyle.value=defaultStyle;
}
  changeStyle('collapsArchStylePreview','collapsArchStyle', 'collapsArchDefaultStyles', 'collapsArchSelectedStyle', false);

</script>
  </fieldset>
  <div class="submit">
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 'collapsArch'); ?> &raquo;" />
  </div>
 </form>
</div>
