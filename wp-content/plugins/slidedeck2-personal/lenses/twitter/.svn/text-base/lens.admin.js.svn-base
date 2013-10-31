(function($){
    var ajaxOptions = [
        "options[show-tools]"
    ];
    for(i = 0; i < ajaxOptions.length; i++){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[i]);
    }
    
    var oldBodyFont = SlideDeckPreview.updates['options[bodyFont]'];
    SlideDeckPreview.updates['options[bodyFont]'] = function($elem, value){
        var font = SlideDeckFonts[value];
        
        if(typeof(oldBodyFont) == 'function') oldBodyFont($elem, value);
        
        SlideDeckPreview.elems.slidedeckFrame.find('.sd-node-twitter-user-name').css('font-family', font.stack);
    };
    
    var oldTitleFont = SlideDeckPreview.updates['options[titleFont]'];
    SlideDeckPreview.updates['options[titleFont]'] = function($elem, value){
        if(typeof(oldTitleFont) == 'function') oldTitleFont($elem, value);
        
        SlideDeckPreview.elems.iframe[0].contentWindow.jQuery.data(SlideDeckPreview.elems.slidedeck[0], 'lens-twitter').fontSize();
    };

    var oldHyphenate = SlideDeckPreview.updates['options[hyphenate]'];
    SlideDeckPreview.updates['options[hyphenate]'] = function($elem, value){
        if(typeof(oldHyphenate) == 'function') oldHyphenate($elem, value);
        
        SlideDeckPreview.elems.iframe[0].contentWindow.jQuery.data(SlideDeckPreview.elems.slidedeck[0], 'lens-twitter').fontSize();
    };
})(jQuery);