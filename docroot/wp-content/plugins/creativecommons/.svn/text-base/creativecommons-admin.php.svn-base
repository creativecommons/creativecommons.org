<?php

/** 
 * Admin pages for CC plugin
 */


# Declare varibles here
$cc_db_rss_table = $wpdb->prefix . "cc_rss_feeds";

# Hook for adding admin menus
add_action('admin_menu', 'cc_plugin_add_pages');


function cc_plugin_add_pages() {
	# Add a new submenu under Manage:
	add_options_page('CC Settings', 'CC Settings', 'manage_options', __FILE__, 'cc_manage_options');
}

# Options -> CC Settings page in WP admin

function cc_manage_options() {

	global $post_msg;
	global $wpdb;
	global $cc_db_rss_table;
  
	if ( isset($_REQUEST['do_add_feed']) && "Add" == $_REQUEST['do_add_feed'] ) {
		cc_admin_add_feed();
	}
  
	if ( isset($_REQUEST['do_edit_feed']) && "Edit" == $_REQUEST['do_edit_feed'] ) {
		cc_admin_edit_feed();
	}
  
	if ( isset($_REQUEST['do_delete_feed']) && "Delete" == $_REQUEST['do_delete_feed'] ) {
		cc_admin_delete_feed();
	}
  
	$feedlist = cc_admin_get_feeds();

	echo <<< END_OF_ADMIN
<div class="wrap">
	<div id="statusmsg">${post_msg}</div>
	<h2>CC Settings</h2>
	<h3>RSS Importer</h3>
	<p><strong>Current feeds</strong></p>
	${feedlist}
	<h4>Add Feed</h4>
	<form method="post">
		<table style="text-align: left;" cellpadding="3">
			<tr>
				<td>=></td>
				<td><input type='text' name='feed_name' style="width: 15ex;"/></td>
				<td><input type='text' name='feed_url' style="width: 40ex;"/></td>
				<td><input type='text' name='entries' style="width: 8ex;"/></td>
				<td><input type='text' name='charcount' style="width: 6ex;"/></td>
				<td><input type='text' name='groupby' style="width: 15ex;"/></td>
				<td><input type='checkbox' name="nl2p" value="1"/></td>
				<td><input type='submit' name="do_add_feed" value="Add"/></td>
			</tr>
		</table>
	</form>
</div>

END_OF_ADMIN;

}


function cc_admin_get_feeds() {

	global $wpdb;
	global $cc_db_rss_table;

	$feeds = $wpdb->get_results("SELECT * FROM $cc_db_rss_table;");

	if ( ! count($feeds) ) {
		$feedlist = "<span style='color: red;'><strong>No feeds configured.</strong></span>\n";
		return $feedlist;
	}

	$feedlist = <<< FEED_LIST
<script type="text/javascript">
function set_feedid(feedid) {
	var form_el = document.getElementById('edit_feed');
	var feed_el = document.createElement('input');
	feed_el.setAttribute('type', 'hidden');
	feed_el.setAttribute('name', 'feed_id');
	feed_el.setAttribute('value', feedid);
	form_el.appendChild(feed_el);
}
</script>
<form method="post" id="edit_feed">
	<table style="text-align: left;" cellpadding="3">
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>URL</th>
			<th># Entries</th>
			<th>#Chars</th>
			<th>Group by</th>
			<th>\\n2&lt;p&gt;</th>
			<th>Actions</th>
		</tr>

FEED_LIST;

	foreach ( $feeds as $feed ) {
		$nl2p = $feed->nl2p ? "checked='checked'" : "";
		$feedlist .= <<< FEED_LIST
		<tr>
			<td>{$feed->id}</td>
			<td><input type='text' name='feed_name-{$feed->id}' value='{$feed->name}' style="width: 15ex;"/></td>
			<td><input type='text' name='feed_url-{$feed->id}' value='{$feed->url}' style="width: 40ex;"/></td>
			<td><input type='text' name='entries-{$feed->id}' value='{$feed->entries}' style="width: 8ex;"/></td>
			<td><input type='text' name='charcount-{$feed->id}' value='{$feed->charcount}' style="width: 6ex;"/></td>
			<td><input type='text' name='groupby-{$feed->id}' value='{$feed->groupby}' style="width: 15ex;"/></td>
			<td><input type='checkbox' name='nl2p-{$feed->id}' $nl2p/></td>
			<td>
				<input type="submit" name="do_edit_feed" value="Edit" onclick="set_feedid({$feed->id});"/>&nbsp;
				<input type="submit" name="do_delete_feed" value="Delete" onclick="set_feedid({$feed->id});"/>
			</td>
		</tr>

FEED_LIST;
	}

	$feedlist .= "	</table>";
	$feedlist .= "</form>";
  
	return $feedlist;

}


function cc_admin_add_feed() {

	global $wpdb;
	global $cc_db_rss_table;

	$sql = sprintf("
		INSERT INTO %s (name, url, entries, charcount, groupby, nl2p)
		VALUES ('%s', '%s', '%s', '%s', '%s', '%s')
		",
		$cc_db_rss_table,
		$wpdb->escape($_REQUEST['feed_name']),
		$wpdb->escape($_REQUEST['feed_url']),
		$wpdb->escape($_REQUEST['entries']),
		$wpdb->escape($_REQUEST['charcount']),
		$wpdb->escape($_REQUEST['groupby']),
		$wpdb->escape($_REQUEST['nl2p'])
	);
	$results = $wpdb->query($sql);
  
}


function cc_admin_edit_feed() {

	global $wpdb;
	global $cc_db_rss_table;

	if ( ! isset($_REQUEST['feed_id']) ) {
		return false;
	}

	$feed_id = $_REQUEST['feed_id'];

	$sql = sprintf("
		UPDATE %s SET
			name = '%s',
			url = '%s',
			entries = '%s',
			charcount = '%s',
			groupby = '%s',
			nl2p = '%s'
		WHERE id = '%s'
		",
		$cc_db_rss_table,
		$wpdb->escape($_REQUEST["feed_name-$feed_id"]),
		$wpdb->escape($_REQUEST["feed_url-$feed_id"]),
		$wpdb->escape($_REQUEST["entries-$feed_id"]),
		$wpdb->escape($_REQUEST["charcount-$feed_id"]),
		$wpdb->escape($_REQUEST["groupby-$feed_id"]),
		isset($_REQUEST["nl2p-$feed_id"]) ? 1 : 0,
		$wpdb->escape($feed_id)
	);
	$results = $wpdb->query($sql);
  
}


function cc_admin_delete_feed() {

	global $wpdb;
	global $cc_db_rss_table;
  
	$sql = sprintf("
		DELETE FROM %s
		WHERE id = '%s'
		",
		$cc_db_rss_table,
		$wpdb->escape($_REQUEST['feed_id'])
	);
	$results = $wpdb->query($sql);

}

?>
