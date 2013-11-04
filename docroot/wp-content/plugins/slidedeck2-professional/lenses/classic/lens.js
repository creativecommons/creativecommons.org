(function($){
    SlideDeckLens['classic'] = function(slidedeck){
        var ns = 'classic';
        var deck = $(slidedeck).slidedeck();
        var elems = {};
            // The SlideDeck DOM element itself
            elems.slidedeck = deck.deck;
            // The SlideDeck's frame
            elems.frame = elems.slidedeck.closest('.lens-' + ns);
            // The slides within the SlideDeck
            elems.horizontalSlides = deck.slides;
			// The spines within the SlideDeck
            elems.horizontalSpines = deck.spines;
		
		
		// Get the accent color
	    elems.accentColor = elems.frame.find('.accent-color').css('color');
		
		// Append element for border styling inside spines
		elems.horizontalSpines.append('<span class="sd2-spine-inner">&nbsp;</span>');
        
        deck.slides.each(function(ind){            
            var $slide = deck.slides.eq(ind);            
            $slide.find('.sd2-node-caption a.play-video-alternative').addClass('accent-color');
        });
        
        deck.loaded(function(deck){
        	var playAlts = elems.slidedeck.find('a.play-video-alternative');
        	// Add click events to the individual playAlts.
			playAlts.each(function(){
				var elem = $(this);
				
                // Append the icon-shape element and use it as the Raphael paper.
                elem.append('<span class="icon-shape-wrapper"><span class="icon-shape"></span></span>');
                var iconWrapper = elem.find('.icon-shape');
                var width = iconWrapper.width();
                var height = iconWrapper.height();
                var paper = Raphael( iconWrapper[0], width, height );

                var pointerShape = paper.path( "M8.42,0C3.77,0,0,3.77,0,8.42s3.77,8.42,8.42,8.42s8.42-3.77,8.42-8.42S13.07,0,8.42,0z   M7,13.708V3.132l5.8,5.287L7,13.708z" );
                //var shapeElement = $( pointerShape.node );
                pointerShape.attr({
                  stroke: 'none',
                  fill: elems.accentColor
                });
                
                // Define the data property to adjust the color.
                iconWrapper.data('slidedeck-accent-shape', pointerShape);
                
			});
        	
        });
        
    };
    
    $(document).ready(function(){
        $('.lens-classic .slidedeck').each(function(){
            if(typeof($.data(this, 'lens-classic')) == 'undefined' || $.data(this, 'lens-classic') == null){
                $.data(this, 'lens-classic', new SlideDeckLens['classic'](this));
            }
        });
    });
})(jQuery);