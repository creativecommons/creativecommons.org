(function($){
    // Get ready to trigger the ajax update.
    $(window).bind( 'load.instagram_token',function(){
        if( document.location.search.match(/&token=(.+)/) ){
            SlideDeckPreview.ajaxUpdate();
            $(window).unbind('load.instagram_token');
        }
    });
    
    var ajaxOptions = [
        "options[instagram_username]",
        "options[instagram_access_token]",
        "options[instagram_recent_or_likes]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
    
    $('#slidedeck-content-control').delegate('#get-instagram-access-token-link', 'click', function(event){
        event.preventDefault();
        
        var data = $('#slidedeck-update-form').serialize();
            data = data.replace(/_wpnonce([^\=]*)\=([a-zA-Z0-9]+)/gi, "");
            data = data.replace(/action\=([^\&]+)/, "");
        
        $.ajax({
            url: this.href,
            data: data,
            dataType: "JSON",
            type: "post",
            success: function(data){
                if(data.valid == true){
                    window.onbeforeunload = null;
                    document.location.href = data.url;
                }
            }
        });
    });
})(jQuery);
