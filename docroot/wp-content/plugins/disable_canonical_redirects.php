<?php
/*
Plugin Name: Disable Canonical URL Redirection
Description: Disables the "Canonical URL Redirect" features of WordPress 2.3 and above.
Version: 1.0
Author: Mark Jaquith
Author URI: http://markjaquith.com/
*/

remove_filter('template_redirect', 'redirect_canonical');

?>
