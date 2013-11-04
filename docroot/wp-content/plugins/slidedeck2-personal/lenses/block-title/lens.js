(function($){
    SlideDeckLens['block-title'] = function(slidedeck){
        var ns = 'block-title';
        var deck = $(slidedeck).slidedeck();
        var elems = {};
            // The SlideDeck DOM element itself
            elems.slidedeck = deck.deck;
            // The SlideDeck's frame
            elems.frame = elems.slidedeck.closest('.lens-' + ns);
            // The slides within the SlideDeck
            elems.horizontalSlides = deck.slides;
		
		// Only for IE - detect background image url and update style for DD element
    	if( ie <= 8.0 ){
    		elems.horizontalSlides.each(function(ind){
    			if( $(elems.horizontalSlides[ind]).css('background-image') != 'none' ){
    				var imgurl = $(elems.horizontalSlides[ind]).css('background-image').match( /url\([\"\'](.*)[\"\']\)/ )[1];
    				$(elems.horizontalSlides[ind]).css({
    					background: 'none'
    				});
    				elems.horizontalSlides[ind].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + imgurl + "', sizingMethod='scale')";
    			};
    		});
    	}
        
        deck.slides.each(function(ind){
            var $slide = deck.slides.eq(ind);
            var $title = $slide.find('.slide-title, .sd2-slide-title .sd2-slide-title-inner');
            
            $slide.find('.sd2-slide-title, .sd2-slide-title a').removeClass('accent-color');
            
            // The title is linked
            if($title.find('a').length){
                var $title = $title.find('a');
            }
            
            var text = jQuery.trim( $title.text() );
            var textArr = text.split(" ");
            for(var w in textArr){
                textArr[w] = '<span class="accent-color-background">' + textArr[w] + '</span>';
            }
            $title.html(textArr.join(""));
            
            $slide.find('.sd2-node-caption .play-video-alternative').addClass('accent-color-background').removeClass('accent-color');
        });
        
        /**
         * For this lens, the structure necessary deviates a bit too much.
         * When the viewer clicks the "Play Video" link, we'll trigger a click
         * on the generic play button in the template. 
         */
        $('.slide-type-video .play-video-alternative').bind( "click", function( event ){
            event.preventDefault();
            var parentSlide = $(this).parents('dd');
            var playButton = parentSlide.find('.video-wrapper .cover .play-video-button');
            playButton.click();
            
            /**
             * We'll also need to hide some of the visual elements... 
             */
            parentSlide.addClass('sd2-hide-slide-content');
        } );
        $('.slide-type-video .play-video-button').bind( "click", function( event ){
            event.preventDefault();
            var parentSlide = $(this).parents('dd');
            
            /**
             * We'll also need to hide some of the visual elements... 
             */
            parentSlide.addClass('sd2-hide-slide-content');
        } );
    };
    
    $(document).ready(function(){
        $('.lens-block-title .slidedeck').each(function(){
            if(typeof($.data(this, 'lens-block-title')) == 'undefined' || $.data(this, 'lens-block-title') == null){
                $.data(this, 'lens-block-title', new SlideDeckLens['block-title'](this));
            }
        });
    });
})(jQuery);