(function($){
	var ajaxOptions = [
        "options[navigation-type]",
        "options[autoplay-indicator]"
	];
	for(i = 0; i < ajaxOptions.length; i++){
		SlideDeckPreview.ajaxOptions.push(ajaxOptions[i]);
	}
})(jQuery);