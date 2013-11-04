<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.1 Plugin: WP-PageNavi 2.20									|
|	Copyright (c) 2007 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://lesterchan.net															|
|																							|
|	File Information:																	|
|	- Page Navigation Options Page												|
|	- wp-content/plugins/pagenavi/pagenavi-options.php					|
|																							|
+----------------------------------------------------------------+
*/


### Variables Variables Variables
$base_name = plugin_basename('pagenavi/pagenavi-options.php');
$base_page = 'admin.php?page='.$base_name;
$mode = trim($_GET['mode']);
$pagenavi_settings = array('pagenavi_options');


### Form Processing 
if(!empty($_POST['do'])) {
	// Decide What To Do
	switch($_POST['do']) {
		case __('Update Options', 'wp-pagenavi'):
			$pagenavi_options = array();
			$pagenavi_options['pages_text'] = addslashes($_POST['pagenavi_pages_text']);
			$pagenavi_options['current_text'] = addslashes($_POST['pagenavi_current_text']);
			$pagenavi_options['page_text'] = addslashes($_POST['pagenavi_page_text']);
			$pagenavi_options['first_text'] = addslashes($_POST['pagenavi_first_text']);
			$pagenavi_options['last_text'] = addslashes($_POST['pagenavi_last_text']);
			$pagenavi_options['next_text'] = addslashes($_POST['pagenavi_next_text']);
			$pagenavi_options['prev_text'] = addslashes($_POST['pagenavi_prev_text']);
			$pagenavi_options['dotright_text'] = addslashes($_POST['pagenavi_dotright_text']);
			$pagenavi_options['dotleft_text'] = addslashes($_POST['pagenavi_dotleft_text']);
			$pagenavi_options['style'] = intval(trim($_POST['pagenavi_style']));
			$pagenavi_options['num_pages'] = intval(trim($_POST['pagenavi_num_pages']));
			$pagenavi_options['always_show'] = intval(trim($_POST['pagenavi_always_show']));
			$update_pagenavi_queries = array();
			$update_pagenavi_text = array();
			$update_pagenavi_queries[] = update_option('pagenavi_options', $pagenavi_options);
			$update_pagenavi_text[] = __('Page Navigation Options', 'wp-pagenavi');
			$i=0;
			$text = '';
			foreach($update_pagenavi_queries as $update_pagenavi_query) {
				if($update_pagenavi_query) {
					$text .= '<font color="green">'.$update_pagenavi_text[$i].' '.__('Updated', 'wp-pagenavi').'</font><br />';
				}
				$i++;
			}
			if(empty($text)) {
				$text = '<font color="red">'.__('No Page Navigation Option Updated', 'wp-pagenavi').'</font>';
			}
			break;
		// Uninstall WP-PageNavi
		case __('UNINSTALL WP-PageNavi', 'wp-pagenavi') :
			if(trim($_POST['uninstall_pagenavi_yes']) == 'yes') {
				echo '<div id="message" class="updated fade">';
				echo '<p>';
				foreach($pagenavi_settings as $setting) {
					$delete_setting = delete_option($setting);
					if($delete_setting) {
						echo '<font color="green">';
						printf(__('Setting Key \'%s\' has been deleted.', 'wp-pagenavi'), "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					} else {
						echo '<font color="red">';
						printf(__('Error deleting Setting Key \'%s\'.', 'wp-pagenavi'), "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					}
				}
				echo '</p>';
				echo '</div>'; 
				$mode = 'end-UNINSTALL';
			}
			break;
	}
}


### Determines Which Mode It Is
switch($mode) {
		//  Deactivating WP-PageNavi
		case 'end-UNINSTALL':
			$deactivate_url = 'plugins.php?action=deactivate&amp;plugin=pagenavi/pagenavi.php';
			if(function_exists('wp_nonce_url')) { 
				$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_pagenavi/pagenavi.php');
			}
			echo '<div class="wrap">';
			echo '<h2>'.__('Uninstall WP-PageNavi', 'wp-pagenavi').'</h2>';
			echo '<p><strong>'.sprintf(__('<a href="%s">Click Here</a> To Finish The Uninstallation And WP-PageNavi Will Be Deactivated Automatically.', 'wp-pagenavi'), $deactivate_url).'</strong></p>';
			echo '</div>';
			break;
	// Main Page
	default:
		$pagenavi_options = get_option('pagenavi_options');
?>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<div class="wrap"> 
	<h2><?php _e('Page Navigation Options', 'wp-pagenavi'); ?></h2> 
	<fieldset class="options">
		<legend><?php _e('Page Navigation Text', 'wp-pagenavi'); ?></legend>
		<table width="100%"  border="0" cellspacing="3" cellpadding="3">
			<tr valign="top">
				<th align="left" width="30%"><?php _e('Text For Number Of Pages', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_pages_text" value="<?php echo stripslashes($pagenavi_options['pages_text']); ?>" size="50" /><br />
					%CURRENT_PAGE% - <?php _e('The current page number.', 'wp-pagenavi'); ?><br />
					%TOTAL_PAGES% - <?php _e('The total number of pages.', 'wp-pagenavi'); ?>
				</td>
			</tr>
			<tr valign="top">
				<th align="left" width="30%"><?php _e('Text For Current Page', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_current_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['current_text'])); ?>" size="30" /><br />
					%PAGE_NUMBER% - <?php _e('The page number.', 'wp-pagenavi'); ?><br />
				</td>
			</tr>
			<tr valign="top">
				<th align="left" width="30%"><?php _e('Text For Page', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_page_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['page_text'])); ?>" size="30" /><br />
					%PAGE_NUMBER% - <?php _e('The page number.', 'wp-pagenavi'); ?><br />
				</td>
			</tr>
			<tr valign="top">
				<th align="left" width="30%"><?php _e('Text For First Post', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_first_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['first_text'])); ?>" size="30" />
				</td>
			</tr>
			<tr valign="top">
				<th align="left" width="30%"><?php _e('Text For Last Post', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_last_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['last_text'])); ?>" size="30" />
				</td>
			</tr>
			<tr valign="top">
				<th align="left" width="30%"><?php _e('Text For Next Post', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_next_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['next_text'])); ?>" size="30" />
				</td>
			</tr>
			<tr valign="top">
				<th align="left" width="30%"><?php _e('Text For Previous Post', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_prev_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['prev_text'])); ?>" size="30" />
				</td>
			</tr>
			<tr valign="top">
				<th align="left" width="30%"><?php _e('Text For Next ...', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_dotright_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['dotright_text'])); ?>" size="30" />
				</td>
			</tr>
			<tr valign="top">
				<th align="left" width="30%"><?php _e('Text For Previous ...', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_dotleft_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['dotright_text'])); ?>" size="30" />
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="options">
		<legend><?php _e('Page Navigation Options', 'wp-pagenavi'); ?></legend>
		<table width="100%"  border="0" cellspacing="3" cellpadding="3">
			<tr valign="top"> 
				<th align="left" width="30%"><?php _e('Page Navigation Style', 'wp-pagenavi'); ?></th>
				<td align="left">
					<select name="pagenavi_style" size="1">
						<option value="1"<?php selected('1', $pagenavi_options['style']); ?>><?php _e('Normal', 'wp-pagenavi'); ?></option>
						<option value="2"<?php selected('2', $pagenavi_options['style']); ?>><?php _e('Drop Down List', 'wp-pagenavi'); ?></option>
					</select>
				</td> 
			</tr>
			 <tr valign="top">
				<th align="left" width="30%"><?php _e('Number Of Many Pages To Show?', 'wp-pagenavi'); ?></th>
				<td align="left">
					<input type="text" name="pagenavi_num_pages" value="<?php echo stripslashes($pagenavi_options['num_pages']); ?>" size="4" />
				</td>
			</tr>
			<tr valign="top"> 
				<th align="left" width="30%"><?php _e('Always Show Page Navigation?', 'wp-pagenavi'); ?></th>
				<td align="left">
					<select name="pagenavi_always_show" size="1">
						<option value="1"<?php selected('1', $pagenavi_options['always_show']); ?>><?php _e('Yes', 'wp-pagenavi'); ?></option>
						<option value="0"<?php selected('0', $pagenavi_options['always_show']); ?>><?php _e('No', 'wp-pagenavi'); ?></option>
					</select>
				</td> 
			</tr>
		</table>
	</fieldset>
	<div align="center">
		<input type="submit" name="do" class="button" value="<?php _e('Update Options', 'wp-pagenavi'); ?>" />&nbsp;&nbsp;<input type="button" name="cancel" value="<?php _e('Cancel', 'wp-pagenavi'); ?>" class="button" onclick="javascript:history.go(-1)" /> 
	</div>
</div>
</form> 

<!-- Uninstall WP-PageNavi -->
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<div class="wrap"> 
	<h2><?php _e('Uninstall WP-PageNavi', 'wp-pagenavi'); ?></h2>
	<p style="text-align: left;">
		<?php _e('Deactivating WP-PageNavi plugin does not remove any data that may have been created, such as the page navigation options. To completely remove this plugin, you can uninstall it here.', 'wp-pagenavi'); ?>
	</p>
	<p style="text-align: left; color: red">
		<strong><?php _e('WARNING:', 'wp-pagenavi'); ?></strong><br />
		<?php _e('Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.', 'wp-pagenavi'); ?>
	</p>
	<p style="text-align: left; color: red">
		<strong><?php _e('The following WordPress Options will be DELETED:', 'wp-pagenavi'); ?></strong><br />
	</p>
	<table width="70%"  border="0" cellspacing="3" cellpadding="3">
		<tr class="thead">
			<td align="center"><strong><?php _e('WordPress Options', 'wp-pagenavi'); ?></strong></td>
		</tr>
		<tr>
			<td valign="top" style="background-color: #eee;">
				<ol>
				<?php
					foreach($pagenavi_settings as $settings) {
						echo '<li>'.$settings.'</li>'."\n";
					}
				?>
				</ol>
			</td>
		</tr>
	</table>
	<p>&nbsp;</p>
	<p style="text-align: center;">
		<input type="checkbox" name="uninstall_pagenavi_yes" value="yes" />&nbsp;<?php _e('Yes', 'wp-pagenavi'); ?><br /><br />
		<input type="submit" name="do" value="<?php _e('UNINSTALL WP-PageNavi', 'wp-pagenavi'); ?>" class="button" onclick="return confirm('<?php _e('You Are About To Uninstall WP-PageNavi From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.', 'wp-pagenavi'); ?>')" />
	</p>
</div> 
</form>
<?php
} // End switch($mode)
?>