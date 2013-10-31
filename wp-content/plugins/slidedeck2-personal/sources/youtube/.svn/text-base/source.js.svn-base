(function($){
    window.YouTubeSource = {
        elems: {},
        
        updateYouTubePlaylists: function(){
            var self = this;
            $.ajax({
                url: ajaxurl + "?action=update_youtube_playlists&youtube_username=" + $('#options-youtube_username').val(),
                type: "GET",
                success: function(data){
                    $('#youtube-user-playlists').html( data ).find('.fancy').fancy();
                    SlideDeckPreview.ajaxUpdate();
                }
            });
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.form = $('#slidedeck-update-form');
            this.slidedeck_id = $('#slidedeck_id').val();
            
            // YouTube Username 
            this.elems.form.delegate('.youtube-username-ajax-update', 'click', function(event){
                event.preventDefault();
                self.updateYouTubePlaylists();
            });
            // Prevent enter key from submitting text fields
            this.elems.form.delegate('#options-youtube_username', 'keydown', function(event){
                if( 13 == event.keyCode){
                    event.preventDefault();
                    $('.youtube-username-ajax-update').click();
                    return false;
                }
                return true;
            });
            
            this.elems.form.delegate('#options-search_or_user-user, #options-search_or_user-search', 'change', function(event){
                switch( event.target.id ){
                    case 'options-search_or_user-user':
                        $('li.youtube-search').hide();
                        $('li.youtube-username').show();
                    break;
                    case 'options-search_or_user-search':
                        $('li.youtube-username').hide();
                        $('li.youtube-search').show();
                    break;
                }
            });
        }
    };
    
    $(document).ready(function(){
        YouTubeSource.initialize();
    });
        
    var ajaxOptions = [
        "options[youtube_playlist]",
        "options[youtube_q]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
})(jQuery);

