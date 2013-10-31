<?php
/**
 * All the code required for handling OpenID comments.  These functions should not be considered public, 
 * and may change without notice.
 */


// -- WordPress Hooks
add_action( 'preprocess_comment', 'openid_process_comment', -90);
add_action( 'init', 'openid_setup_akismet');
add_action( 'akismet_spam_caught', 'openid_akismet_spam_caught');
add_action( 'comment_post', 'update_comment_openid', 5 );
add_filter( 'option_require_name_email', 'openid_option_require_name_email' );
add_action( 'sanitize_comment_cookies', 'openid_sanitize_comment_cookies', 15);
add_action( 'openid_finish_auth', 'openid_finish_comment', 10, 2 );
if( get_option('openid_enable_approval') ) {
	add_filter('pre_comment_approved', 'openid_comment_approval');
}
add_filter( 'get_comment_author_link', 'openid_comment_author_link');
if( get_option('openid_enable_commentform') ) {
	add_action( 'wp', 'openid_js_setup', 9);
	add_action( 'wp_footer', 'openid_comment_profilelink', 10);
	add_action( 'comment_form', 'openid_comment_form', 10);
}
add_filter( 'openid_user_data', 'openid_get_user_data_form', 6, 2);
add_action( 'delete_comment', 'unset_comment_openid' );

add_action( 'init', 'openid_recent_comments');


/**
 * Ensure akismet runs before OpenID.
 */
function openid_setup_akismet() {
	if (has_filter('preprocess_comment', 'akismet_auto_check_comment')) {
		remove_action('preprocess_comment', 'akismet_auto_check_comment', 1);
		add_action('preprocess_comment', 'akismet_auto_check_comment', -99);
	}
}


/**
 * Akismet caught this comment as spam, so no need to do OpenID discovery on the URL.
 */
function openid_akismet_spam_caught() {
	remove_action( 'preprocess_comment', 'openid_process_comment', -90);
}

/**
 * Intercept comment submission and check if it includes a valid OpenID.  If it does, save the entire POST
 * array and begin the OpenID authentication process.
 *
 * regarding comment_type: http://trac.wordpress.org/ticket/2659
 *
 * @param array $comment comment data
 * @return array comment data
 */
function openid_process_comment( $comment ) {
	if ( array_key_exists('openid_skip', $_REQUEST) && $_REQUEST['openid_skip'] ) return $comment;
	if ( $comment['comment_type'] != '' ) return $comment;

	if ( array_key_exists('openid_identifier', $_POST) ) {
		$openid_url = $_POST['openid_identifier'];
	} elseif ( $_REQUEST['login_with_openid'] ) {
		$openid_url = $_POST['url'];
	}

	@session_start();
	unset($_SESSION['openid_posted_comment']);

	if ( !empty($openid_url) ) {  // Comment form's OpenID url is filled in.
		$_SESSION['openid_comment_post'] = $_POST;
		$_SESSION['openid_comment_post']['comment_author_openid'] = $openid_url;
		$_SESSION['openid_comment_post']['openid_skip'] = 1;

		openid_start_login($openid_url, 'comment');

		// Failure to redirect at all, the URL is malformed or unreachable.

		// Display an error message only if an explicit OpenID field was used.  Otherwise,
		// just ignore the error... it just means the user entered a normal URL.
		if (array_key_exists('openid_identifier', $_POST)) {
			openid_repost_comment_anonymously($_SESSION['openid_comment_post']);
		}
	}

	// duplicate name and email check from wp-comments-post.php
	if ( $comment['comment_type'] == '') {
		openid_require_name_email();
	}

	return $comment;
}


/**
 * Duplicated code from wp-comments-post.php to check for presence of comment author name and email 
 * address.
 */
function openid_require_name_email() {
	$user = wp_get_current_user();
	global $comment_author, $comment_author_email;
	
	if ( get_option('require_name_email') && !$user->ID ) { 
		if ( 6 > strlen($comment_author_email) || '' == $comment_author ) {
			wp_die( __('Error: please fill the required fields (name, email).', 'openid') );
		} elseif ( !is_email($comment_author_email)) {
			wp_die( __('Error: please enter a valid email address.', 'openid') );
		}
	}
}


/**
 * This filter callback simply approves all OpenID comments, but later it could do more complicated logic
 * like whitelists.
 *
 * @param string $approved comment approval status
 * @return string new comment approval status
 */
function openid_comment_approval($approved) {
	return ($_SESSION['openid_posted_comment'] ? 1 : $approved);
}


/**
 * If the comment contains a valid OpenID, skip the check for requiring a name and email address.  Even if
 * this data isn't provided in the form, we may get it through other methods, so we don't want to bail out
 * prematurely.  After OpenID authentication has completed (and $_REQUEST['openid_skip'] is set), we don't
 * interfere so that this data can be required if desired.
 *
 * @param boolean $value existing value of flag, whether to require name and email
 * @return boolean new value of flag, whether to require name and email
 * @see get_user_data
 */
function openid_option_require_name_email( $value ) {
	
	$comment_page = (defined('OPENID_COMMENTS_POST_PAGE') ? OPENID_COMMENTS_POST_PAGE : 'wp-comments-post.php');

	if ($GLOBALS['pagenow'] != $comment_page) {
		return $value;
	}

	if (array_key_exists('openid_skip', $_REQUEST) && $_REQUEST['openid_skip']) {
		return get_option('openid_no_require_name') ? false : $value;
	}
	
	// make sure we only process this once per request
	static $bypass;
	if ($bypass) {
		return $value;
	} else {
		$bypass = true;
	}


	if (array_key_exists('openid_identifier', $_POST)) {
		if( !empty( $_POST['openid_identifier'] ) ) {
			return false;
		}
	} else {
		global $comment_author_url;
		if ( !empty($comment_author_url) ) {
			return false;
		}
	}

	return $value;
}


/**
 * Make sure that a user's OpenID is stored and retrieved properly.  This is important because the OpenID
 * may be an i-name, but WordPress is expecting the comment URL cookie to be a valid URL.
 *
 * @wordpress-action sanitize_comment_cookies
 */
function openid_sanitize_comment_cookies() {
	if ( isset($_COOKIE['comment_author_openid_'.COOKIEHASH]) ) {

		// this might be an i-name, so we don't want to run clean_url()
		remove_filter('pre_comment_author_url', 'clean_url');

		$comment_author_url = apply_filters('pre_comment_author_url',
		$_COOKIE['comment_author_openid_'.COOKIEHASH]);
		$comment_author_url = stripslashes($comment_author_url);
		$_COOKIE['comment_author_url_'.COOKIEHASH] = $comment_author_url;
	}
}


/**
 * Add OpenID class to author link.
 *
 * @filter: get_comment_author_link
 **/
function openid_comment_author_link( $html ) {
	if( is_comment_openid() ) {
		if (preg_match('/<a[^>]* class=[^>]+>/', $html)) {
			return preg_replace( '/(<a[^>]* class=[\'"]?)/', '\\1openid_link ' , $html );
		} else {
			return preg_replace( '/(<a[^>]*)/', '\\1 class="openid_link"' , $html );
		}
	}
	return $html;
}


/**
 * Check if the comment was posted with OpenID, either directly or by an author registered with OpenID.  Update the comment accordingly.
 *
 * @action post_comment
 */
function update_comment_openid($comment_ID) {
	session_start();

	if ($_SESSION['openid_posted_comment']) {
		set_comment_openid($comment_ID);
		unset($_SESSION['openid_posted_comment']);
	} else {
		$comment = get_comment($comment_ID);

		if ( is_user_openid($comment->user_id) ) {
			set_comment_openid($comment_ID);
		}
	}

}


/**
 * Print jQuery call for slylizing profile link.
 *
 * @action: comment_form
 **/
function openid_comment_profilelink() {
	global $wp_scripts;

	if (comments_open() && is_user_openid() && $wp_scripts->query('openid')) {
		echo '<script type="text/javascript">stylize_profilelink()</script>';
	}
}


/**
 * Print jQuery call to modify comment form.
 *
 * @action: comment_form
 **/
function openid_comment_form() {
	global $wp_scripts;

	if (comments_open() && !is_user_logged_in() && isset($wp_scripts) && $wp_scripts->query('openid')) {
?>
		<span id="openid_comment">
			<label>
				<input type="checkbox" id="login_with_openid" name="login_with_openid" checked="checked" />
				<?php _e('Authenticate this comment using <span class="openid_link">OpenID</span>.', 'openid'); ?>
			</label>
		</span>
		<script type="text/javascript">jQuery(function(){ add_openid_to_comment_form('<?php echo site_url('index.php') ?>', '<?php echo wp_create_nonce('openid_ajax') ?>') })</script>
<?php
	}
}


function openid_repost_comment_anonymously($post) {
	$comment_page = (defined('OPENID_COMMENTS_POST_PAGE') ? OPENID_COMMENTS_POST_PAGE : 'wp-comments-post.php');

	$html = '
	<h1>'.__('OpenID Authentication Error', 'openid').'</h1>
	<p id="error">'.__('We were unable to authenticate your claimed OpenID, however you '
	. 'can continue to post your comment without OpenID:', 'openid').'</p>

	<form action="' . site_url("/$comment_page") . '" method="post">
		<p>Name: <input name="author" value="'.$post['author'].'" /></p>
		<p>Email: <input name="email" value="'.$post['email'].'" /></p>
		<p>URL: <input name="url" value="'.$post['url'].'" /></p>
		<textarea name="comment" cols="80%" rows="10">'.stripslashes($post['comment']).'</textarea>
		<input type="submit" name="submit" value="'.__('Submit Comment').'" />';
	foreach ($post as $name => $value) {
		if (!in_array($name, array('author', 'email', 'url', 'comment', 'submit'))) {
			$html .= '
		<input type="hidden" name="'.$name.'" value="'.$value.'" />';
		}
	}
	
	$html .= '</form>';
	openid_page($html, __('OpenID Authentication Error', 'openid'));
}


/**
 * Action method for completing the 'comment' action.  This action is used when leaving a comment.
 *
 * @param string $identity_url verified OpenID URL
 */
function openid_finish_comment($identity_url, $action) {
	if ($action != 'comment') return;

	if (empty($identity_url)) {
		openid_repost_comment_anonymously($_SESSION['openid_comment_post']);
	}
		
	openid_set_current_user($identity_url);
		
	if (is_user_logged_in()) {
		// simulate an authenticated comment submission
		$_SESSION['openid_comment_post']['author'] = null;
		$_SESSION['openid_comment_post']['email'] = null;
		$_SESSION['openid_comment_post']['url'] = null;
	} else {
		// try to get user data from the verified OpenID
		$user_data =& openid_get_user_data($identity_url);

		if (!empty($user_data['display_name'])) {
			$_SESSION['openid_comment_post']['author'] = $user_data['display_name'];
		}
		if (!empty($user_data['user_email'])) {
			$_SESSION['openid_comment_post']['email'] = $user_data['user_email'];
		}
		$_SESSION['openid_comment_post']['url'] = $identity_url;
	}
		
	// record that we're about to post an OpenID authenticated comment.
	// We can't actually record it in the database until after the repost below.
	$_SESSION['openid_posted_comment'] = true;

	$comment_page = (defined('OPENID_COMMENTS_POST_PAGE') ? OPENID_COMMENTS_POST_PAGE : 'wp-comments-post.php');

	openid_repost(site_url("/$comment_page"), array_filter($_SESSION['openid_comment_post']));
}


/**
 * Mark the specified comment as an OpenID comment.
 *
 * @param int $id id of comment to set as OpenID
 */
function set_comment_openid($id) {
	$comment = get_comment($id);
	$openid_comments = get_post_meta($comment->comment_post_ID, 'openid_comments', true);
	if (!is_array($openid_comments)) {
		$openid_comments = array();
	}
	$openid_comments[] = $id;
	update_post_meta($comment->comment_post_ID, 'openid_comments', array_unique($openid_comments));
}


/**
 * Unmark the specified comment as an OpenID comment
 *
 * @param int $id id of comment to set as OpenID
 */
function unset_comment_openid($id) {
	$comment = get_comment($id);
	$openid_comments = get_post_meta($comment->comment_post_ID, 'openid_comments', true);

	if (is_array($openid_comments) && in_array($id, $openid_comments)) {
		$new = array();
		foreach($openid_comments as $c) {
			if ($c == $id) continue;
			$new[] = $c;
		}
		update_post_meta($comment->comment_post_ID, 'openid_comments', array_unique($new));
	}
}


/**
 * Retrieve user data from comment form.
 *
 * @param string $identity_url OpenID to get user data about
 * @param reference $data reference to user data array
 * @see get_user_data
 */
function openid_get_user_data_form($data, $identity_url) {
	if ( array_key_exists('openid_comment_post', $_SESSION) ) {
		$comment = $_SESSION['openid_comment_post'];
	}

	if ( !isset($comment) || !$comment) {
		return $data;
	}

	if ($comment['email']) {
		$data['user_email'] = $comment['email'];
	}

	if ($comment['author']) {
		$data['nickname'] = $comment['author'];
		$data['user_nicename'] = $comment['author'];
		$data['display_name'] = $comment['author'];
	}

	return $data;
}


/**
 * Remove the CSS snippet added by the Recent Comments widget because it breaks entries that include the OpenID logo.
 */
function openid_recent_comments() {
	global $wp_widget_factory;

	if ( $wp_widget_factory && array_key_exists('WP_Widget_Recent_Comments', $wp_widget_factory->widgets) ) {
		// this is an ugly hack because remove_action doesn't actually work the way it should with objects
		foreach ( array_keys($GLOBALS['wp_filter']['wp_head'][10]) as $key ) {
			if ( strpos($key, 'WP_Widget_Recent_Commentsrecent_comments_style') === 0 ) {
				remove_action('wp_head', $key);
				return;
			}
		}
	}
}

?>
