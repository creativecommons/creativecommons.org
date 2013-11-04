(function($){
    window.DailymotionSource = {
        elems: {},
        
        updateDailymotionPlaylists: function(){
            var self = this;
            
            $.ajax({
                url: ajaxurl + "?action=update_dailymotion_playlists&dailymotion_username=" + $('#options-dailymotion_username').val(),
                type: "GET",
                success: function(data){
                    $('#dailymotion-user-playlists').html( data ).find('.fancy').fancy();
                    SlideDeckPreview.ajaxUpdate();
                }
            });
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.form = $('#slidedeck-update-form');
            this.slidedeck_id = $('#slidedeck_id').val();
            
            // Prevent enter key from submitting text field for add video to list
            this.elems.form.delegate('#add-video-field', 'keydown', function(event){
                if( 13 == event.keyCode){
                    event.preventDefault();
                    $('.list-of-videos-add').click();
                    return false;
                }
                return true;
            });
            
            // Dailymotion Username 
            this.elems.form.delegate('.dailymotion-username-ajax-update', 'click', function(event){
                event.preventDefault();
                self.updateDailymotionPlaylists();
            });
        }
    };
    
    $(document).ready(function(){
        DailymotionSource.initialize();
    });
        
    var ajaxOptions = [
        "options[dailymotion_playlist]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
})(jQuery);

