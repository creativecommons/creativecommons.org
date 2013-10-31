(function($){
    window.TwitterSource = {
        elems: {},
        
        initialize: function(){
            var self = this;
            
            this.elems.form = $('#slidedeck-update-form');
            this.slidedeck_id = $('#slidedeck_id').val();
            
            this.elems.form.delegate('#options-twitter_search_or_user-user, #options-twitter_search_or_user-search', 'change', function(event){
                switch( event.target.id ){
                    case 'options-twitter_search_or_user-user':
                        $('li.twitter-search').hide();
                        $('li.twitter-username').show();
                    break;
                    case 'options-twitter_search_or_user-search':
                        $('li.twitter-username').hide();
                        $('li.twitter-search').show();
                    break;
                }
            })
        }
    };
    
    $(document).ready(function(){
        TwitterSource.initialize();
    });
    
    var ajaxOptions = [
        "options[twitter_username]",
        "options[twitter_q]",
        "options[twitter_search_or_user]",
        "options[useGeolocationImage]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
})(jQuery);
