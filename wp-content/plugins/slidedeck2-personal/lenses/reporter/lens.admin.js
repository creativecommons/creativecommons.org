(function($){
	var ajaxOptions = [
        "options[show-title]",
        "options[show-excerpt]",
        "options[show-readmore]",
        "options[show-author]",
        "options[show-author-avatar]",
        "options[navigation-type]",
        "options[nav-date-format]",
        "options[transparent-background]",
        "options[image-border-width]",
        "options[transparent-image-border]"
	];
	
	for(i = 0; i < ajaxOptions.length; i++){
		// Apply Ajax Updates to these...
		SlideDeckPreview.updates[ajaxOptions[i]] = function($elem, value){
	    	SlideDeckPreview.ajaxUpdate();
	    };		
		
		SlideDeckPreview.ajaxOptions.push(ajaxOptions[i]);
	}
	
    SlideDeckPreview.updates['options[lensVariations]'] = function($elem, value){
    	var accentColorInput = $('#options-accentColor');
    	var swatch = $('.miniColors-trigger');
    	var colors = {
    		light: '#333333',
    		dark: '#ffffff'
    	};
    	var borderColors = {
    		light: 'rgb(255,255,255)',
    		dark: 'rgb(0,0,0)'
    	};
    	
    	// Adjust the preview colors for the transaprent option.
    	if( !SlideDeckPreview.elems.slidedeckFrame.hasClass('sd2-transparent-image-border') ){
    		borderColors = {
	    		light: 'rgb(236,236,236)',
	    		dark: 'rgb(67,67,67)'
    		};
    	}
    	
    	// Use Raphaelto check the hex value against the defaults
    	hexColor = Raphael.color( swatch.css('background-color') ).hex;
    	
    	// iterate through the pre defined colors...
		for (var key in colors ) {
			/**
			 * If the currently selected color is one of the pre defined
			 * colors, then we need to switch it to the other readable value.
			 */
			if( hexColor == colors[key] ){
				accentColorInput.val( colors[value] ).change();
				swatch.css( { 'background-color': colors[value] } );
			}
			
			// Remove all the color classes. 
			var color = colors[key];
			SlideDeckPreview.elems.slidedeckFrame.removeClass(SlideDeckPrefix + key);
		};
		
		// Add the new color class.
		SlideDeckPreview.elems.slidedeckFrame.addClass(SlideDeckPrefix + value);
		
		// Change border/inner shadow data
		var borderElem = SlideDeckPreview.elems.slidedeckFrame.find('div.image .slide-image');
		var borderShadowElem = SlideDeckPreview.elems.slidedeckFrame.find('div.image .border');
		
		if( borderElem.length )
			borderElem.attr('style', borderElem.attr('style').replace(/(border.*)(rgb\(.+\))(.*)/, "$1" + borderColors[value] + "$3" ) );
		
		if( borderShadowElem.length )
			borderShadowElem.attr('style', borderShadowElem.attr('style').replace(/(box\-shadow.*)(rgb\(.+\))(.*)/, "$1" + borderColors[value] + "$3" ) );
		
        // Change accent shape data
        var icons = SlideDeckPreview.elems.slidedeckFrame.find('.button-nav .icon-shape-prev-next');
        if( icons.length ){
            for (var i=0; i < icons.length; i++) {
                SlideDeckPreview.elems.iframe[0].contentWindow.jQuery.data( icons[i], 'prev-next-arrows' ).attr('fill', colors[value]);
            }
        }
		
    };
	
})(jQuery);