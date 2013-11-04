(function($){
    $(document).ready(function(){
        $('#options-feedUrl').bind('keyup', function(event){
            if(event.keyCode == 13){
                event.preventDefault();
            }
        });
    });
    
    var ajaxOptions = [
        "options[validateImages]",
        "options[imageSource]",
        "options[use-custom-post-excerpt]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
})(jQuery);
