// For Wordpress > 2.5x
if ( tinyMCE.addI18n ){
	tinyMCE.addI18n('hu.cforms',{
		desc : 'cforms űrlap beszúrása'
	});
}
else
{
	// For Wordpress <= 2.3x
	tinyMCE.addToLang('cforms', {
		desc : 'cforms &#369;rlap besz&uacute;r&aacute;sa'
	});
}
