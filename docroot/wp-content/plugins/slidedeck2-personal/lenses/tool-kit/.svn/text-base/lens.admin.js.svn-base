(function($){
	var ajaxOptions = [
        "options[navigation-type]",
        "options[frame]",
        "options[text-position]",
        "options[nav-arrow-style]",
        "options[deck-arrows]"
	];
	for(i = 0; i < ajaxOptions.length; i++){
		SlideDeckPreview.ajaxOptions.push(ajaxOptions[i]);
	}
    
    /**
     * Special cases for avoiding the conflicting caption and navigation
     */
    // Move the navigation if the text is moved into a confliccting position
    SlideDeckPreview.updates['options[text-position]'] = function($elem, value){
        if( $('#options-navigation-style').val() == 'nav-default' ){
            var navPosition = $('#options-navigation-position');
            switch(value){
                case 'title-pos-top':
                    navPosition.val('nav-pos-bottom');
                break;
                case 'title-pos-bottom':
                    navPosition.val('nav-pos-top');
                break;
                case 'title-pos-left':
                    navPosition.val('nav-pos-right');
                break;
                case 'title-pos-right':
                    navPosition.val('nav-pos-left');
                break;
            }
        }
        SlideDeckPreview.ajaxUpdate();
    };
    // Move the text (caption) position if the navigation position is moved
    SlideDeckPreview.updates['options[navigation-position]'] = function($elem, value){
        if( $('#options-navigation-style').val() == 'nav-default' ){
            var textPosition = $('#options-text-position');
            switch(value){
                case 'nav-pos-top':
                    textPosition.val('title-pos-bottom');
                break;
                case 'nav-pos-bottom':
                    textPosition.val('title-pos-top');
                break;
                case 'nav-pos-left':
                    textPosition.val('title-pos-right');
                break;
                case 'nav-pos-right':
                    textPosition.val('title-pos-left');
                break;
            }
        }
        SlideDeckPreview.ajaxUpdate();
    };
    // Move the navigation position based on the caption position if the nav style is
    // moved into the slide area. 
    SlideDeckPreview.updates['options[navigation-style]'] = function($elem, value){
        if( $('#options-navigation-style').val() == 'nav-default' ){
            var navPosition = $('#options-navigation-position').val();
            var textPosition = $('#options-text-position');
            switch(navPosition){
                case 'nav-pos-bottom':
                    textPosition.val('title-pos-top');
                break;
                case 'nav-pos-top':
                    textPosition.val('title-pos-bottom');
                break;
                case 'nav-pos-right':
                    textPosition.val('title-pos-left');
                break;
                case 'nav-pos-left':
                    textPosition.val('title-pos-right');
                break;
            }
        }
        SlideDeckPreview.ajaxUpdate();
    };
    /**
     * End of special cases for avoiding the conflicting caption and navigation
     */
    
    
    SlideDeckPreview.updates['options[nav-opaque]'] = function($elem, value){
        value = value == 1 ? true : false;
        if(value){
            SlideDeckPreview.elems.slidedeckFrame.addClass(SlideDeckPrefix + 'nav-opaque');
        } else {
            SlideDeckPreview.elems.slidedeckFrame.removeClass(SlideDeckPrefix + 'nav-opaque');
        }
    };
    
    SlideDeckPreview.updates['options[arrow-style]'] = function($elem, value){
        $elem.find('option').each(function(){
            if(this.value != value){
                SlideDeckPreview.elems.slidedeckFrame.removeClass(SlideDeckPrefix + this.value);
            } else {
                SlideDeckPreview.elems.slidedeckFrame.addClass(SlideDeckPrefix + this.value);
            }
        });
    };
    
    SlideDeckPreview.updates['options[text-color]'] = function($elem, value){
        $elem.find('option').each(function(){
            if(this.value != value){
                SlideDeckPreview.elems.slidedeckFrame.removeClass(SlideDeckPrefix + this.value);
            } else {
                SlideDeckPreview.elems.slidedeckFrame.addClass(SlideDeckPrefix + this.value);
            }
        });
    };
})(jQuery);