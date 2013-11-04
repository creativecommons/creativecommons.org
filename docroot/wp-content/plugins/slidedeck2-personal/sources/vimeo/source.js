(function($){
    window.VimeoSource = {
        elems: {},
        
        updateVimeoPlaylists: function(){
            var self = this;
            
            $.ajax({
                url: ajaxurl + "?action=update_vimeo_playlists&vimeo_username=" + $('#options-vimeo_username').val(),
                type: "GET",
                success: function(data){
                    $('#vimeo-user-playlists').html( data ).find('.fancy').fancy();
                    SlideDeckPreview.ajaxUpdate();
                }
            });
        },
        
        updateVideoThumbnail: function( url, newLi ){
            var self = this;
            
            $.ajax({
                url: ajaxurl + "?action=update_video_thumbnail&video_url=" + url,
                type: "GET",
                success: function(data){
                    if( data.indexOf('invalid') != -1 ){
                        newLi.find('.thumbnail').css({
                            backgroundImage: "url('" + data + "')"
                        });
                    }else{
                        newLi.find('.thumbnail').removeClass('loading').css({
                        	backgroundImage: "url('" + data + "')"
                        });
                    }
                }
            });
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.form = $('#slidedeck-update-form');
            this.slidedeck_id = $('#slidedeck_id').val();
            
            // Vimeo Username 
            this.elems.form.delegate('.vimeo-username-ajax-update', 'click', function(event){
                event.preventDefault();
                self.updateVimeoPlaylists();
            });
        }
    };
    
    $(document).ready(function(){
        VimeoSource.initialize();
    });
        
    var ajaxOptions = [
        "options[vimeo_album]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
})(jQuery);

