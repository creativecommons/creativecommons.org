(function($){
    SlideDeckSkin['fashion'] = function(slidedeck){
        var ns = 'fashion';
        var deck = $(slidedeck).slidedeck();
        var elems = {};
            // The SlideDeck DOM element itself
            elems.slidedeck = deck.deck;
            // The SlideDeck's frame
            elems.frame = elems.slidedeck.closest('.lens-' + ns);
            // The slides within the SlideDeck
            elems.horizontalSlides = deck.slides;
			
			elems.frame.append('<div class="slidedeck-nav"><div class="accent-bar-top accent-color-background">&nbsp;</div><div class="deck-navigation-inner"></div></div>');
			
			elems.horizontalSlides.each(function(ind){
				$(this).find('.slide-title span').last().css({
					'padding-right': '0.3em'
				});
			});
		
        var sdNavDeck;
        
        var settings = {
        	navItemWidth: 30,
        	navigationParent: $(elems.frame).find('.slidedeck-nav'),
        	navigationInner: $(elems.frame).find('.deck-navigation-inner'),
            slideCount: elems.horizontalSlides.length
        };
        settings.navigationWidth = settings.navigationInner.width();
        
        function generateNav( multi, navItemsPerSlide ){
        	if( !navItemsPerSlide ) var navItemsPerSlide = false;
        	var navHTML = '<dl class="sd-nav">';
        	var counted;
        	var remainingSlides;
        	var deficitSlides;
            elems.horizontalSlides.each(function(ind){
                var count = ind + 1;
                if(count == 1){
                    navHTML += '<dd><table class="deck-navigation-table" border="0" cellpadding="0" cellspacing="0"><tr>';
                }
                navHTML += '<td><a href="#nav-'+count+'"><span class="deck-nav-front"><span class="deck-nav-inner">'+ count +'</span></span><span class="deck-nav-back accent-color-background">&nbsp;</span>';
                if( $(this).hasClass('has-image') ){
                	navHTML += '<span class="deck-navigation-image-tip"><span>&nbsp;</span></span>';
                }else if( $(this).hasClass('no-image') ){
                	navHTML += '<span class="deck-navigation-image-tip no-image"><span>&nbsp;</span></span>';
                }
                navHTML += '</a></td>';
                if( ( (count % navItemsPerSlide ) === 0 ) && count != settings.slideCount ) {
                	counted = count;
                	remainingSlides = (elems.horizontalSlides.length - counted);
                	deficitSlides = (navItemsPerSlide - remainingSlides);
                    navHTML += '</tr></table></dd><dd><table class="deck-navigation-table" border="0" cellpadding="0" cellspacing="0"><tr>';
                }
                if( count == settings.slideCount ) {
                	if( deficitSlides ){
                		for( var i=0; i<deficitSlides; i++ ){
                			navHTML += '<td><span class="spacer">&nbsp;</span></td>';
                		}
                	}
                    navHTML += '</tr></table></dd>';
                }
            });
            navHTML +='</dl>';
            return navHTML;
        };
        
        // Check to see if the number of slides force a multi-layered deck nav or not
        if( ( settings.slideCount * settings.navItemWidth ) < settings.navigationWidth ){
        	settings.navigationParent.removeClass('paged');
            elems.navItems = generateNav( false );
        } else {
            settings.navigationParent.addClass('paged');
            settings.navigationInner.append('<a href="#prev-nav" class="deck-pagination prev"><span class="front">Previous<span class="deck-pagination-inner">&nbsp;</span></span><span class="back accent-color-background">&nbsp;</span></a><a href="#next-nav" class="deck-pagination next"><span class="front">Next<span class="deck-pagination-inner">&nbsp;</span></span><span class="back accent-color-background">&nbsp;</span></a>');
			
			elems.navPrev = settings.navigationInner.find('a.deck-pagination.prev');
            elems.navNext = settings.navigationInner.find('a.deck-pagination.next');
            
			settings.navigationWidth = settings.navigationInner.width();
            settings.navItemsPerSlide = Math.floor( settings.navigationWidth / settings.navItemWidth );
            elems.navItems = generateNav( true, settings.navItemsPerSlide );
        }
        settings.navigationInner.append( elems.navItems );
        elems.navDeck = elems.frame.find('dl.sd-nav');
        elems.navDeck.wrap('<div class="sd-nav-wrapper"></div>');
        elems.navDeckNavItems = elems.navDeck.find('a');
        
    	elems.navigationImageTips = elems.frame.find('span.deck-navigation-image-tip > span');
        elems.horizontalSlides.each(function(ind){
            var $horizontalSlide = elems.horizontalSlides.eq(ind);
            if( $horizontalSlide.hasClass('has-image') ){
    			var backgroundImageUrl = $horizontalSlide.css('background-image');
    			if(backgroundImageUrl == "none"){
    			    if($horizontalSlide.find('.sd2-slide-background').length){
        			    backgroundImageUrl = $horizontalSlide.find('.sd2-slide-background').css('background-image');
    			    }
    			}
    			if(backgroundImageUrl.match( /url\([\"\'](.*)[\"\']\)/ )){
        			backgroundImageUrl = backgroundImageUrl.match( /url\([\"\'](.*)[\"\']\)/ )[1];
    			}
    			
				var thumbnailUrl = $horizontalSlide.attr('data-thumbnail-src');
				
    			if( ie <= 8.0 ){
    				elems.navigationImageTips.eq(ind).css({
    					background: 'none'
    				});
    				elems.navigationImageTips[ind].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + thumbnailUrl + "', sizingMethod='scale')";
    				$horizontalSlide.css({
    					background: 'none'
    				});
    				elems.horizontalSlides[ind].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + imgurl + "', sizingMethod='scale')";
    			} else {
    				elems.navigationImageTips.eq(ind).css({
    					backgroundImage: 'url(' + thumbnailUrl + ')'
    				});
    			}
        	}
        });        
        
        elems.slidedeck.slidedeck().loaded(function(){
        	elems.slidedeck.activeSlide = elems.slidedeck.find('dd.active');
        	elems.slidedeck.activeSlideIndex = elems.horizontalSlides.index(elems.slidedeck.activeSlide);
            elems.currentNav = $(elems.frame).find('.sd-nav a:eq('+ [elems.slidedeck.activeSlideIndex] +')');
        	elems.currentNav.addClass('active');
            settings.currentNavSlideNumber = Math.floor( elems.slidedeck.activeSlideIndex / settings.navItemsPerSlide );
        	
        	/*
             * Initiate the Thumbnail Nav SlideDeck
             */ 
            sdNavDeck = elems.navDeck.slidedeck({
                scroll: false,
                keys: false,
                hideSpines: true,
                speed: 250,
                cycle: true,
                start: settings.currentNavSlideNumber + 1,
                complete: function( deck ){
                    if( elems.slidedeck.slidedeck().options.cycle == false ){
                        if( deck.current == deck.slides.length ){
                            $(elems.navNext).addClass('disabled');
                            $(elems.navPrev).removeClass('disabled');
                        }else if( deck.current == 1 ){
                            $(elems.navPrev).addClass('disabled');
                            $(elems.navNext).removeClass('disabled');
                        }else{
                            $(elems.navPrev).removeClass('disabled');
                            $(elems.navNext).removeClass('disabled');
                        }
                    }
                }
            }).loaded(function(){
                var slidedeckNav = elems.frame.find('.slidedeck-nav');
                var slidedeckNavInner = elems.frame.find('.slidedeck-nav .deck-navigation-table');
                slidedeckNav.addClass('behind');
                slidedeckNavInner.bind("mouseenter mouseleave", function(event){
                    if( event.type == "mouseenter" ){
                        slidedeckNav.removeClass('behind');
                    }else{
                        slidedeckNav.addClass('behind');
                    }
                });
                

                
                var currentStartSlide = settings.currentNavSlideNumber + 1;
                if( deck.options.cycle == false ){
                    sdNavDeck.options.cycle = false;
                    if( currentStartSlide == sdNavDeck.slides.length ){
                        $(elems.navNext).addClass('disabled');
                    }else if( currentStartSlide == 1 ){
                        $(elems.navPrev).addClass('disabled');
                    }
                }
                elems.navDeckNavItems.each(function(){
                    $(this).click(function(e){
                        e.preventDefault();
                        var navIndex = elems.navDeckNavItems.index($(this));
                        elems.navDeckNavItems.removeClass('active');
                        $(this).addClass('active');
                        deck.goTo( navIndex + 1 );
                    });
                });
            });
            
            /*
             * Bind Click events to the Buttons - prev() and next() methods
             */
            elems.frame.find('a.deck-pagination').click(function(e){
                e.preventDefault();
                sdNavDeck.options.pauseAutoPlay = true;
                if( $(this).hasClass('prev') ) {
                    sdNavDeck.prev();
                } else {
                    sdNavDeck.next();
                }
            });
        	
	        elems.frame.find('.deck-navigation').bind('click', function(event){
	            event.preventDefault();
	            var direction = this.href.split('#')[1];
	            
	            deck.pauseAutoPlay = true;
	            if(direction == "next"){
	                deck.next();
	            } else if(direction == "prev"){
	                deck.prev();
	            }
	        });
	        /*
             * Get old complete() of the SlideDeck if it exists, and then set new complete() and append old one to it.
             * */
            var oldBefore = elems.slidedeck.slidedeck().options.before;
            var oldComplete = elems.slidedeck.slidedeck().options.complete;
            var mainDeck = elems.slidedeck.slidedeck();
            
            elems.slidedeck.slidedeck().options.before = function(deck){
                if(typeof(oldBefore) == 'function')
                    oldBefore(deck);
                              
                var oldIndex = elems.slidedeck.activeSlideIndex;
                var currentSlide = mainDeck.current;
                var currentIndex = currentSlide - 1;
                var navSlideNumber = Math.floor( currentIndex / settings.navItemsPerSlide );
                
                elems.navDeckNavItems.removeClass('active');
                $(elems.navDeckNavItems[currentIndex]).addClass('active');
                
                if( navSlideNumber != settings.currentNavSlideNumber ){
                    sdNavDeck.goTo( navSlideNumber + 1 );
                    settings.currentNavSlideNumber = navSlideNumber;
                }
            };
            
            elems.slidedeck.slidedeck().options.complete = function(deck){
                if(typeof(oldComplete) == 'function')
                    oldComplete(deck);
                
                // Stub
            }
        });
        
        deck.slides.each(function(ind){            
            var $slide = deck.slides.eq(ind);
            var $title = $slide.find('.sd2-slide-title .sd2-slide-title-inner');
            
            $slide.find('.sd2-slide-title, .sd2-slide-title a').removeClass('accent-color');
            
            // The title is linked
            if($title.find('a').length){
                var $title = $title.find('a');
            }
            
            var text = jQuery.trim( $title.text() );
            var textArr = text.split(" ");
            
            var first = true;
            for(var w in textArr){
                if ( first == true ) {
                    textArr[w] = '<span class="first">' + textArr[w] + '</span>';
                } else {
                    textArr[w] = '<span>' + textArr[w] + '</span>';
                }
                first = false;
            }
            
            $title.html('<span class="before-rule">&nbsp;</span>' + textArr.join("") + '<span class="after-rule">&nbsp;</span>');
                        
            // Add accent bar on top
            $slide.find('div.sd2-node-caption').append('<div class="accent-bar-top accent-color-background">&nbsp;</div>');
            
        });
        
        elems.slidedeck.find('.play-video-alternative').addClass('accent-color-background');
    };
    
    $(document).ready(function(){
        $('.lens-fashion .slidedeck').each(function(){
            if(typeof($.data(this, 'lens-fashion')) == 'undefined' || $.data(this, 'lens-fashion') == null){
                $.data(this, 'lens-fashion', new SlideDeckSkin['fashion'](this));
            }
        });
    });
})(jQuery);