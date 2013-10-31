// For Wordpress > 2.5x
if ( tinyMCE.addI18n ){
	tinyMCE.addI18n('de.cforms',{
		desc : 'cforms formular hinzuf&uuml;gen'
	});
}
else
{
	// For Wordpress <= 2.3x
	tinyMCE.addToLang('cforms', {
		desc : 'cforms formular hinzuf&uuml;gen'
	});
}
