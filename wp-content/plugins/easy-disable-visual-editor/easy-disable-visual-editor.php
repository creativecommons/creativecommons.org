<?php
/*
Plugin Name: Easy Disable Visual Editor
Plugin URI: http://sivel.net/wordpress/easy-disable-visual-editor/
Description: Disables the Visual editor globally.
Author: Matt Martz
Author URI: http://sivel.net/
Version: 1.0

        Copyright (c) 2008 Matt Martz (http://sivel.net)
        Easy Disable Visual Editor is released under the GNU General Public License (GPL)
        http://www.gnu.org/licenses/gpl-2.0.txt

*/

if ( is_admin () )
	add_filter ( 'user_can_richedit' , create_function ( '$a' , 'return false;' ) , 50 );
?>
