// For Wordpress > 2.5x
if ( tinyMCE.addI18n ){
	tinyMCE.addI18n('en.cforms',{
		desc : 'Insert a form'
	});
}
else
{
	// For Wordpress <= 2.3x
	tinyMCE.addToLang('cforms', {
		desc : 'Insert a form'
	});
}
