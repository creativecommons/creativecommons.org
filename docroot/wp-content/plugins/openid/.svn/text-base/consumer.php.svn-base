<?php
/**
 * Functions related to the OpenID Consumer.
 */


// hooks for getting user data
add_filter('openid_auth_request_extensions', 'openid_add_sreg_extension', 10, 2);
add_filter('openid_auth_request_extensions', 'openid_add_ax_extension', 10, 2);

add_filter( 'xrds_simple', 'openid_consumer_xrds_simple');

/**
 * Get the internal OpenID Consumer object.  If it is not already initialized, do so.
 *
 * @return Auth_OpenID_Consumer OpenID consumer object
 */
function openid_getConsumer() {
	static $consumer;

	if (!$consumer) {
		require_once 'Auth/OpenID/Consumer.php';

		$store = openid_getStore();
		$consumer = new Auth_OpenID_Consumer($store);
		if( null === $consumer ) {
			openid_error('OpenID consumer could not be created properly.');
			openid_enabled(false);
		}

	}

	return $consumer;
}


/**
 * Send the user to their OpenID provider to authenticate.
 *
 * @param Auth_OpenID_AuthRequest $auth_request OpenID authentication request object
 * @param string $trust_root OpenID trust root
 * @param string $return_to URL where the OpenID provider should return the user
 */
function openid_redirect($auth_request, $trust_root, $return_to) {
	do_action('openid_redirect', $auth_request, $trust_root, $return_to);

	$message = $auth_request->getMessage($trust_root, $return_to, false);

	if (Auth_OpenID::isFailure($message)) {
		return openid_error('Could not redirect to server: '.$message->message);
	}

	$_SESSION['openid_return_to'] = $message->getArg(Auth_OpenID_OPENID_NS, 'return_to');

	// send 302 redirect or POST
	if ($auth_request->shouldSendRedirect()) {
		$redirect_url = $auth_request->redirectURL($trust_root, $return_to);
		wp_redirect( $redirect_url );
	} else {
		openid_repost($auth_request->endpoint->server_url, $message->toPostArgs());
	}
}


/**
 * Finish OpenID Authentication.
 *
 * @return String authenticated identity URL, or null if authentication failed.
 */
function finish_openid_auth() {
	@session_start();

	$consumer = openid_getConsumer();
	if ( array_key_exists('openid_return_to', $_SESSION) ) {
		$openid_return_to = $_SESSION['openid_return_to'];
	}
	if ( empty($openid_return_to) ) {
		$openid_return_to = openid_service_url('consumer');
	}

	$response = $consumer->complete($openid_return_to);

	unset($_SESSION['openid_return_to']);
	openid_response($response);

	switch( $response->status ) {
		case Auth_OpenID_CANCEL:
			openid_message(__('OpenID login was cancelled.', 'openid'));
			openid_status('error');
			break;

		case Auth_OpenID_FAILURE:
			openid_message(sprintf(__('OpenID login failed: %s', 'openid'), $response->message));
			openid_status('error');
			break;

		case Auth_OpenID_SUCCESS:
			openid_message(__('OpenID login successful', 'openid'));
			openid_status('success');

			$identity_url = $response->identity_url;
			$escaped_url = htmlspecialchars($identity_url, ENT_QUOTES);
			return $escaped_url;

		default:
			openid_message(__('Unknown Status. Bind not successful. This is probably a bug.', 'openid'));
			openid_status('error');
	}

	return null;
}


/**
 * Begin login by activating the OpenID consumer.
 *
 * @param string $url claimed ID
 * @return Auth_OpenID_Request OpenID Request
 */
function openid_begin_consumer($url) {
	static $request;

	@session_start();
	if ($request == NULL) {
		set_error_handler( 'openid_customer_error_handler');

		$consumer = openid_getConsumer();
		$request = $consumer->begin($url);

		restore_error_handler();
	}

	return $request;
}


/**
 * Start the OpenID authentication process.
 *
 * @param string $claimed_url claimed OpenID URL
 * @param string $action OpenID action being performed
 * @param string $finish_url stored in user session for later redirect
 * @uses apply_filters() Calls 'openid_auth_request_extensions' to gather extensions to be attached to auth request
 */
function openid_start_login( $claimed_url, $action, $finish_url = null) {
	if ( empty($claimed_url) ) return; // do nothing.

	$auth_request = openid_begin_consumer( $claimed_url );

	if ( null === $auth_request ) {
		openid_status('error');
		openid_message(sprintf(
			__('Could not discover an OpenID identity server endpoint at the url: %s', 'openid'),
			htmlentities($claimed_url)
		));

		return;
	}

	@session_start();
	$_SESSION['openid_action'] = $action;
	$_SESSION['openid_finish_url'] = $finish_url;

	$extensions = apply_filters('openid_auth_request_extensions', array(), $auth_request);
	foreach ($extensions as $e) {
		if (is_a($e, 'Auth_OpenID_Extension')) {
			$auth_request->addExtension($e);
		}
	}

	$return_to = openid_service_url('consumer', 'login_post');
	$return_to = apply_filters('openid_return_to', $return_to);

	$trust_root = openid_trust_root($return_to);

	openid_redirect($auth_request, $trust_root, $return_to);
	exit(0);
}


/**
 * Build an Attribute Exchange attribute query extension if we've never seen this OpenID before.
 */
function openid_add_ax_extension($extensions, $auth_request) {
	if(!get_user_by_openid($auth_request->endpoint->claimed_id)) {
		require_once('Auth/OpenID/AX.php');

		if ($auth_request->endpoint->usesExtension(Auth_OpenID_AX_NS_URI)) {
			$ax_request = new Auth_OpenID_AX_FetchRequest();
			$ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/friendly', 1, true));
			$ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/email', 1, true));
			$ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson', 1, true));

			$extensions[] = $ax_request;
		}
	}

	return $extensions;
}


/**
 * Build an SReg attribute query extension if we've never seen this OpenID before.
 */
function openid_add_sreg_extension($extensions, $auth_request) {
	if(!get_user_by_openid($auth_request->endpoint->claimed_id)) {
		require_once('Auth/OpenID/SReg.php');

		if ($auth_request->endpoint->usesExtension(Auth_OpenID_SREG_NS_URI_1_0) || $auth_request->endpoint->usesExtension(Auth_OpenID_SREG_NS_URI_1_1)) {
			$extensions[] = Auth_OpenID_SRegRequest::build(array(),array('nickname','email','fullname'));
		}
	}

	return $extensions;
}


/**
 * Finish OpenID authentication.
 *
 * @param string $action login action that is being performed
 * @uses do_action() Calls 'openid_finish_auth' hook action after processing the authentication response.
 */
function finish_openid($action) {
	$identity_url = finish_openid_auth();
	do_action('openid_finish_auth', $identity_url, $action);
}


/**
 *
 * @uses apply_filters() Calls 'openid_consumer_return_urls' to collect return_to URLs to be included in XRDS document.
 */
function openid_consumer_xrds_simple($xrds) {

	if (get_option('openid_xrds_returnto')) {
		// OpenID Consumer Service
		$return_urls = array_unique(apply_filters('openid_consumer_return_urls', array(openid_service_url('consumer', 'login_post'))));
		if (!empty($return_urls)) {
			$xrds = xrds_add_simple_service($xrds, 'OpenID Consumer Service', 'http://specs.openid.net/auth/2.0/return_to', $return_urls);
		}
	}

	return $xrds;
}




