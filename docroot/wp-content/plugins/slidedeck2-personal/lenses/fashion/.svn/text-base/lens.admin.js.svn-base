(function($){
    var ajaxOptions = [
        "options[navigation-type]"
    ];
    for(i = 0; i < ajaxOptions.length; i++){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[i]);
    }
    
    SlideDeckPreview.updates['options[show-title-rule]'] = function($elem, value){
        value = value == 1 ? true : false;
        if(value){
            SlideDeckPreview.elems.slidedeckFrame.addClass(SlideDeckPrefix + 'show-title-rule');
        } else {
            SlideDeckPreview.elems.slidedeckFrame.removeClass(SlideDeckPrefix + 'show-title-rule');
        }
    };
    
    SlideDeckPreview.updates['options[show-shadow]'] = function($elem, value){
        value = value == 1 ? true : false;
        if(value){
            SlideDeckPreview.elems.slidedeckFrame.addClass(SlideDeckPrefix + 'show-shadow');
        } else {
            SlideDeckPreview.elems.slidedeckFrame.removeClass(SlideDeckPrefix + 'show-shadow');
        }
    };
})(jQuery);