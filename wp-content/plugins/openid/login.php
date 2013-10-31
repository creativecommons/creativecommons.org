<?php
/**
 * All the code required for handling logins via wp-login.php.  These functions should not be considered public, 
 * and may change without notice.
 */


add_action( 'login_head', 'openid_wp_login_head');
add_action( 'login_form', 'openid_wp_login_form');
add_action( 'register_form', 'openid_wp_register_form', 9);
add_action( 'register_post', 'openid_register_post', 10, 3);
add_action( 'openid_finish_auth', 'openid_finish_login', 10, 2);
add_filter( 'registration_errors', 'openid_clean_registration_errors', -99);
add_filter( 'registration_errors', 'openid_registration_errors');
add_action( 'init', 'openid_login_errors' );


/**
 * Authenticate user to WordPress using OpenID.
 *
 * @param mixed $user authenticated user object, or WP_Error or null
 */
function openid_authenticate($user) {
	if ( array_key_exists('openid_identifier', $_POST) && $_POST['openid_identifier'] ) {

		$redirect_to = array_key_exists('redirect_to', $_REQUEST) ? $_REQUEST['redirect_to'] : null;
		openid_start_login($_POST['openid_identifier'], 'login', $redirect_to);

		// if we got this far, something is wrong
		global $error;
		$error = openid_message();
		$user = new WP_Error( 'openid_login_error', $error );

	} else if ( array_key_exists('finish_openid', $_REQUEST) ) {

		$identity_url= $_REQUEST['identity_url'];

		if ( !wp_verify_nonce($_REQUEST['_wpnonce'], 'openid_login_' . md5($identity_url)) ) {
			$user = new WP_Error('openid_login_error', 'Error during OpenID authentication.  Please try again. (invalid nonce)');
		}

		if ( $identity_url ) {
			$user_id = get_user_by_openid($identity_url);
			if ( $user_id ) {
				$user = new WP_User($user_id);
			} else {
				$user = new WP_Error('openid_registration_closed', __('Your have entered a valid OpenID, but this site is not currently accepting new accounts.', 'openid'));
			}
		} else if ( array_key_exists('openid_error', $_REQUEST) ) {
			$user = new WP_Error('openid_login_error', htmlentities2($_REQUEST['openid_error']));
		}

	}

	return $user;
}
add_action( 'authenticate', 'openid_authenticate' );


/**
 * Action method for completing the 'login' action.  This action is used when a user is logging in from
 * wp-login.php.
 *
 * @param string $identity_url verified OpenID URL
 */
function openid_finish_login($identity_url, $action) {
	if ($action != 'login') return;
		
	// create new user account if appropriate
	$user_id = get_user_by_openid($identity_url);
	if ( $identity_url && !$user_id && get_option('users_can_register') ) {
		$user_data =& openid_get_user_data($identity_url);
		openid_create_new_user($identity_url, $user_data);
	}
	
	// return to wp-login page
	$url = get_option('siteurl') . '/wp-login.php';
	if (empty($identity_url)) {
		$url = add_query_arg('openid_error', openid_message(), $url);
	}

	$url = add_query_arg( array( 
		'finish_openid' => 1, 
		'identity_url' => urlencode($identity_url), 
		'redirect_to' => $_SESSION['openid_finish_url'],
		'_wpnonce' => wp_create_nonce('openid_login_' . md5($identity_url)), 
	), $url);
		
	wp_safe_redirect($url);
	exit;
}


/**
 * Setup OpenID errors to be displayed to the user.
 */
function openid_login_errors() {
	$self = basename( $GLOBALS['pagenow'] );
	if ($self != 'wp-login.php') return;

	if ( array_key_exists('openid_error', $_REQUEST) ) {
		global $error;
		$error = htmlentities2($_REQUEST['openid_error']);
	}
}


/**
 * Add style and script to login page.
 */
function openid_wp_login_head() {
	openid_style();
}


/**
 * Add OpenID input field to wp-login.php
 *
 * @action: login_form
 **/
function openid_wp_login_form() {
	echo '<hr id="openid_split" style="clear: both; margin-bottom: 1.0em; border: 0; border-top: 1px solid #999; height: 1px;" />';

	echo '
	<p style="margin-bottom: 8px;">
		<label style="display: block; margin-bottom: 5px;">' . __('Or login using an OpenID', 'openid') . '<br />
		<input type="text" name="openid_identifier" id="openid_identifier" class="input openid_identifier" value="" size="20" tabindex="25" /></label>
	</p>

	<p style="font-size: 0.9em; margin: 8px 0 24px 0;" id="what_is_openid">
		<a href="http://openid.net/what/" target="_blank">'.__('Learn about OpenID', 'openid').'</a>
	</p>';
}


/**
 * Add information about registration to wp-login.php?action=register 
 *
 * @action: register_form
 **/
function openid_wp_register_form() {
	echo '
	<div style="width:100%;">'; //Added to fix IE problem

	if (get_option('openid_required_for_registration')) {
		$label = __('Register using an OpenID:', 'openid');
		echo '
		<script type="text/javascript">
			jQuery(function() {
				jQuery("#registerform > p:first").hide();
				jQuery("#registerform > p:first + p").hide();
				jQuery("#reg_passmail").hide();
				jQuery("p.submit").css("margin", "1em 0");
				var link = jQuery("#nav a:first");
				jQuery("#nav").text("").append(link);
			});
		</script>';
	} else {
		$label = __('Or register using an OpenID:', 'openid');

		echo '<hr id="openid_split" style="clear: both; margin-bottom: 1.5em; border: 0; border-top: 1px solid #999; height: 1px;" />';

		echo '
		<script type="text/javascript">
			jQuery(function() {
				jQuery("#reg_passmail").insertBefore("#openid_split");
				jQuery("p.submit").css("margin", "1em 0").clone().insertBefore("#openid_split");
			});
		</script>';
	}

	echo '
		<p>
			<label style="display: block; margin-bottom: 5px;">' . $label . '<br />
			<input type="text" name="openid_identifier" id="openid_identifier" class="input openid_identifier" value="" size="20" tabindex="25" /></label>
		</p>

		<p style="float: left; font-size: 0.8em; margin: 0.8em 0;" id="what_is_openid">
			<a href="http://openid.net/what/" target="_blank">'.__('Learn about OpenID', 'openid').'</a>
		</p>

	</div>';

}


/**
 * Clean out registration errors that don't apply.
 */
function openid_clean_registration_errors($errors) {
	if (get_option('openid_required_for_registration') || !empty($_POST['openid_identifier'])) {
		$new = new WP_Error();
		foreach ($errors->get_error_codes() as $code) {
			if (in_array($code, array('empty_username', 'empty_email'))) continue;

			$message = $errors->get_error_message($code);
			$data = $errors->get_error_data($code);
			$new->add($code, $message, $data);
		}

		$errors = $new;
	}

	if (get_option('openid_required_for_registration') && empty($_POST['openid_identifier'])) {
		$errors->add('openid_only', __('<strong>ERROR</strong>: ', 'openid') . __('New users must register using OpenID.', 'openid'));
	}

	return $errors;
}

/**
 * Handle WordPress registration errors.
 */
function openid_registration_errors($errors) {
	if (!empty($_POST['openid_identifier'])) {
		$errors->add('invalid_openid', __('<strong>ERROR</strong>: ', 'openid') . openid_message());
	}

	return $errors;
}


/**
 * Handle WordPress registrations.
 */
function openid_register_post($username, $password, $errors) {
	if ( !empty($_POST['openid_identifier']) ) {
		wp_signon();
	}
}
?>
