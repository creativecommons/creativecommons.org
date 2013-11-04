<?php
/**
 * SlideDeck Flash Message
 * 
 * A suite of functions to set "flash" messages (ala CakePHP or RoR) and to set short term
 * cookies in general since WordPress does not support sessions or session variables. Based
 * off of the stand-alone FlashMessage plugin also developed by digital-telepathy.
 * 
 * @package SlideDeck
 * @subpackage dtlabs
 * @author dtelepathy
 * @version 1.1.0
*/

/*
Copyright 2012 digital-telepathy  (email : support@digital-telepathy.com)

This file is part of SlideDeck.

SlideDeck is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

SlideDeck is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SlideDeck.  If not, see <http://www.gnu.org/licenses/>.
*/
class SlideDeckFlashMessage {
    static $namespace = 'slidedeck-flash-message';
    static $version = '1.1.0';
    
    static $flash = "";
    static $flash_error = false;
    
    static function cookie_name( $name ) {
        return implode( "-", array( self::$namespace, $name ) );
    }
    
    static function delete_cookie( $name ) {
        self::set_cookie( $name, 1, -31536000 );
    }
    
    static function get_cookie( $name ) {
        if( !isset( $_COOKIE[self::cookie_name( $name )] ) ) {
            return false;
        }
        return $_COOKIE[self::cookie_name( $name )];
    }

    static function get_cookies() {
        self::$flash = self::get_cookie( 'flash' );
        self::$flash_error = self::get_cookie( 'flash_error' );
        self::delete_cookie( 'flash' );
        self::delete_cookie( 'flash_error' );
    }

    static function set_cookie( $name, $val, $expire ) {
        setcookie( self::cookie_name( $name ), $val, time() + $expire, SITECOOKIEPATH, COOKIE_DOMAIN, false );
    }
}
add_action('admin_init', array( 'SlideDeckFlashMessage', 'get_cookies' ) );