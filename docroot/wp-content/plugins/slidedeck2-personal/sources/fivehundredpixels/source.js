(function($){
    var ajaxOptions = [
    	"options[fivehundredpixels_username]",
    	"options[fivehundredpixels_feed_type]",
    	"options[fivehundredpixels_category]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
})(jQuery);
