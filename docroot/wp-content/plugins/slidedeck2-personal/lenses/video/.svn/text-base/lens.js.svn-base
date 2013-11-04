(function($){
	SlideDeckLens['video'] = function(slidedeck){
        var slidedeck = $(slidedeck);
        var slidedeckFrame = slidedeck.closest('.slidedeck-frame');
        var deck = slidedeck.slidedeck();
        var deckElement = slidedeck;
	    
        // Center the title on the medium sized
        if( slidedeckFrame.hasClass('sd2-medium') ){
            var theTitle = deck.slides.find('h3.slide-title');

            for (var i=0; i < theTitle.length; i++) {
                var thisTitle = $( theTitle[i] );
                thisTitle.css({
                    position: 'absolute',
                    top: '50%',
                    left: 0,
                    marginTop: Math.round( thisTitle.outerHeight() / 2 ) * -1,
                    marginLeft: thisTitle.outerHeight()
                });
            };
        }
	};
    
    $(document).ready(function(){
        $('.lens-video .slidedeck').each(function(){
            if(typeof($.data(this, 'lens-video')) == 'undefined'){
                $.data(this, 'lens-video', new SlideDeckLens['video'](this));
            }
        });
    });
})(jQuery);