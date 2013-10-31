<?php

include( YARPP_DIR . '/lang/words-' . word_file_lang() . '.php' );

function word_file_lang() {
	if ( !defined('WPLANG') )
		return 'en_US';
	$lang = substr(WPLANG, 0, 2);
	switch ( $lang ) {
		case 'de':
			return 'de_DE';
		case 'it':
			return 'it_IT';
		case 'pl':
			return 'pl_PL';
		case 'bg':
			return 'bg_BG';
		case 'fr':
			return 'fr_FR';
		case 'cs':
			return 'cs_CZ';
		case 'nl':
			return 'nl_NL';
		default:
			return 'en_US';
	}
}

function paypal_directory() {
	if ( !defined('WPLANG') )
		return 'en_US/';
	$lang = substr(WPLANG, 0, 2);
	switch ( $lang ) {
		case 'fr':
			return 'fr_FR/';
		case 'de':
			return 'de_DE/';
		case 'it':
			return 'it_IT/';
		case 'ja':
			return 'ja_JP/';
		case 'es':
			return 'es_XC/';
		case 'nl':
			return 'nl_NL/';
		case 'pl':
			return 'pl_PL/';
		case 'zh':
			if (preg_match("/^zh_(HK|TW)/i",WPLANG))
				return 'zh_HK/';
			// actually zh_CN, but interpret as default zh:
			return 'zh_XC/';
		default:
			return 'en_US/';
	}
}

?>