(function($){
    var ajaxOptions = [
        "options[validateImages]",
        "options[imageSource]",
        "options[use-custom-post-excerpt]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
})(jQuery);
