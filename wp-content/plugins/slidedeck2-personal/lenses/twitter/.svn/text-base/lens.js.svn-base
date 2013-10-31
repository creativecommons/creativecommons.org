(function($){
    SlideDeckLens['twitter'] = function(slidedeck){
        var ns = 'twitter';
        var deck = $(slidedeck).slidedeck();
        var elems = {};
			elems.slidedeck = deck.deck,
            elems.frame = elems.slidedeck.closest('.lens-' + ns),
            elems.slides = deck.slides,
            elems.retweetBtn = elems.frame.find('.sd-node-twitter-retweet'),
            elems.favoriteBtn = elems.frame.find('.sd-node-tool-favorite'),
            elems.openProfileBtn = elems.frame.find('.sd-node-open-profile'),
            elems.deckHeader = elems.frame.find('.sd-node-twitter-header'),
            elems.twitterUserName = elems.deckHeader.find('.sd-node-twitter-user-name'),
            elems.twitterDescription = elems.deckHeader.find('.sd-node-twitter-user-description'),
            elems.tweetDiv = elems.slidedeck.find('.sd-node-tweet'),
            elems.sendMessageHeaderHandle = elems.frame.find('.sd-node-twitter-header-handle'),
            elems.fauxHandle = elems.frame.find('.faux-reply-to-handle'),
            elems.deckToolBar = elems.frame.find('.sd-node-twitter-tools'),
			elems.replyTool = elems.deckToolBar.find('.sd-node-tool-reply'),
			elems.replyDiv = elems.deckToolBar.find('.sd-node-twitter-tools-reply'),
			animating = false,
			elems.replyButton = elems.deckToolBar.find('.sd-node-tool-reply'),
			elems.toolBarButtons = elems.deckToolBar.find('.sd-node-twitter-tools-list li > a'),
			elems.toolBarWidth = elems.deckToolBar.outerWidth(),
			elems.messageCounter = elems.deckToolBar.find('.sd-node-reply-count-number'),
			elems.messageBox = elems.deckToolBar.find('.sd-node-reply-area'),
			elems.cancelTweetBtn = elems.replyDiv.find('.sd-node-cancel-tweet'),
			elems.fauxHandle = elems.replyDiv.find('.faux-reply-to-handle'),
			elems.fauxHandleText = elems.fauxHandle.html();
        
    	this.initialize = function(){
    		this.fontSize();
    		this.sizes();
    		this.addOverlay();
    		this.deckToolActions();
    		this.deckTools();
    	};
    	
    	function setCounter(){
				elems.fauxHandleText = elems.fauxHandle.html();
				var messageLength = elems.messageBox.val().length+elems.fauxHandleText.length+1;
				elems.messageCounter.html(messageLength);
				if(messageLength > 140){
					elems.messageCounter.addClass('over-limit');
				}else{
					elems.messageCounter.removeClass('over-limit');
				}
			}
			
			setCounter();
    	
    	
    	this.deckTools = function(){
    		function setToolHrefs(activeSlide){
    			var tweetDeets = activeSlide.find('.sd-node-tweet-deets');
    			var tweetID = tweetDeets.find('.data-tweetid').html();
    			var twitterHandle = tweetDeets.find('.data-twitter-handle').html();
    			var twitterName = tweetDeets.find('.data-twitter-name').html();
    			var twitterDescription = tweetDeets.find('.data-twitter-description').html();
	    		elems.retweetBtn.attr('href', 'https://twitter.com/intent/retweet?tweet_id='+tweetID);
	    		elems.favoriteBtn.attr('href', 'https://twitter.com/intent/favorite?tweet_id='+tweetID);
				elems.openProfileBtn.attr('href', 'https://twitter.com/'+twitterHandle);
				elems.twitterUserName.find('.sd-node-twitter-header-link').html(twitterName);
				elems.twitterUserName.find('a.sd-node-twitter-header-link').attr('href', 'https://twitter.com/'+twitterHandle);
				elems.twitterDescription.html(twitterDescription);
				elems.sendMessageHeaderHandle.html(twitterHandle);
				elems.fauxHandle.html('@'+twitterHandle);
				elems.messageBox.css('text-indent', elems.fauxHandle.width()+6);
    		}
    		
    		if(deck.slides.eq(0).find('.slidesVertical').length){
	    		var activeSlide = deck.slides.eq(0).find('.slidesVertical').find('dd.verticalSlide_1');
	    		setToolHrefs(activeSlide);
    		}else{
    			var activeSlide = elems.slides.eq(deck.current-1);
    			setToolHrefs(activeSlide);
    		}
    		
            // Get the old complete option
    		var oldComplete = deck.options.complete;
    		
	    	deck.setOption('complete', function(){
                // If the old complete option was a function, run it
	    	    if(typeof(oldComplete) == 'function')
                    oldComplete(deck);
                    
            	var activeSlide = elems.slides.eq(deck.current-1);
				
	    		setToolHrefs(activeSlide);
	    		setCounter();
	    	});
	    	
	    	
	    	if(deck.slides.eq(0).find('.slidesVertical').length){
	    	    var oldVerticalComplete = deck.vertical().options.complete;
		    	deck.vertical().options.complete = function(vDeck){
		    	    if(typeof(oldVerticalComplete) == 'function')
                        oldVerticalComplete(vDeck);
                        
		    		var activeSlide = vDeck.slides.eq(vDeck.current);
		    		setToolHrefs(activeSlide);
		    		setCounter();
		    	}	
	    	}
    	}
    	
    	this.addOverlay = function(){
    		//elems.slidedeck.height(elems.frame.height());
			$('<div class="sd-node-overlay"></div>').appendTo(elems.frame);
			elems.deckOverlay = elems.frame.find('.sd-node-overlay');
    	};
    	
    	this.fontSize = function(){
    	    var self = this;
    	    
            deck.slides.each(function(ind){
                var slide = deck.slides.eq(ind);
                var verticalSlides = slide.find('.slidesVertical dd');
                
                if(verticalSlides.length){
                    verticalSlides.each(function(vInd){
                        self.fontScale(verticalSlides.eq(vInd));
                    });
                } else {
                    self.fontScale(slide);
                }
            });
    	};
    	
    	this.fontScale = function(slide){
    	    var factor = .7;
            var slideTitle = slide.find('.slide-title');
            var lineHeight = parseInt(slideTitle.css('line-height').replace(/([^\d]+)/, ""));
            var lines = Math.floor((slide.height() / lineHeight) * factor);
            briBriFlex(slideTitle, lines);
    	};
    	
    	this.deckToolActions = function(){
				
				elems.replyDivPadding = parseInt(elems.replyDiv.css('padding-left'),10)+parseInt(elems.replyDiv.css('padding-right'), 10);
				
				
				elems.messageBox.css('width', elems.replyDiv.width()-elems.replyDivPadding/2-2);
				elems.messageBox.css('text-indent', elems.fauxHandle.width()+6);
				
				elems.messageBox.keypress(function(e){
					if(e.which == 13){
						e.preventDefault();
					}
				})
				
				elems.messageBox.keyup(function(e){
					setCounter();
					var messageLimit = 164;
					if(elems.messageBox.val().length+elems.fauxHandleText.length > messageLimit){
						var messageText = elems.messageBox.val();
						sliceDist = messageLimit-elems.fauxHandleText.length;
						messageTextSLiced = messageText.slice(0,sliceDist);
						elems.messageBox.val(messageTextSLiced);
						setCounter();
					}
				})
				
			function animateReplyCallback(){
				animating = false;
			}
			
			
			function animatereply(openMessageBtn){
				if(!animating){
					if(elems.deckToolBar.hasClass('open') || !openMessageBtn){
						if(elems.frame.hasClass('sd2-medium')){
							animHeight = -elems.frame.height();
						}else if(elems.frame.hasClass('sd2-small')){
							animHeight = 38;
						}else{
							animWid = elems.toolBarWidth;
						}
						elems.replyButton.removeClass('active');
						elems.deckToolBar.removeClass('open');
						deck.setOption('keys', true);
						deck.pauseAutoPlay  = false;
						elems.deckOverlay.fadeOut('280');
					}else if(openMessageBtn){
						if(elems.frame.hasClass('sd2-medium')){
							animHeight = 0;
						}else if(elems.frame.hasClass('sd2-small')){
							animHeight = elems.frame.height()*.7;
						}else{
							animWid = elems.slidedeck.width()*.85;
						}
						deck.setOption('keys', false);
						deck.pauseAutoPlay  = true;
						elems.deckOverlay.fadeIn('200');
						elems.replyButton.addClass('active');
						elems.deckToolBar.addClass('open');
					}
					var animating = true;
					anamationSpeed = 300;
					if(elems.frame.hasClass('sd2-medium')){
						elems.replyDiv.animate({
							'bottom': animHeight
						}, anamationSpeed, animateReplyCallback());
					}else if(elems.frame.hasClass('sd2-small')){
						elems.deckToolBar.animate({
							'height': animHeight
						}, anamationSpeed, animateReplyCallback());
					}else{
						elems.deckToolBar.animate({
							'width': animWid
						},anamationSpeed, animateReplyCallback());
					}
				};
			};
			
			var sendTweetButton = elems.frame.find('.sd-node-twitter-tools-tweet-text');
			sendTweetButton.click(function(){
				elems.fauxHandleText = elems.fauxHandle.html();
				jQuery(this).attr('href', 'https://twitter.com/intent/tweet?text='+elems.fauxHandleText+' '+elems.messageBox.val());
			});
			
			elems.toolBarButtons.click(function(e){
				elems.toolBarButtons.removeClass('active');
				var openMessageBtn = false;
				if(jQuery(this).hasClass('sd-node-tool-reply')){
					var openMessageBtn = true;
					e.preventDefault();
				}
				
				if(!animating){
					animatereply(openMessageBtn);
				}
				
				if(!jQuery(this).hasClass('sd-node-tool-reply')){
					jQuery(this).addClass('active');
				};
			});
			
			elems.deckOverlay.click(function(){
				animatereply();
			});
			
			elems.cancelTweetBtn.click(function(e){
				e.preventDefault();
				animatereply();
			});

		};
		
		this.sizes = function(){
			
			elems.replyDivPadding = parseInt(elems.replyDiv.css('padding-left'),10)+parseInt(elems.replyDiv.css('padding-right'), 10);
			if(elems.frame.hasClass('sd2-medium')){
				elems.frame.append(elems.replyDiv);
				elems.replyDiv = elems.frame.find('.sd-node-twitter-tools-reply');
				elems.replyDiv.css({
					'bottom': -elems.frame.height(),
					'width': (elems.frame.width()-168)-(parseInt(elems.replyDiv.css('padding-left'),10)+parseInt(elems.replyDiv.css('padding-right'),10))
				});
				elems.cancelTweetBtn = elems.replyDiv.find('.sd-node-cancel-tweet');
				elems.messageCounter = elems.replyDiv.find('.sd-node-reply-count-number');
				elems.messageBox = elems.replyDiv.find('.sd-node-reply-area');
				elems.cancelTweetBtn = elems.replyDiv.find('.sd-node-cancel-tweet');
				elems.fauxHandle = elems.replyDiv.find('.faux-reply-to-handle');
				elems.fauxHandleText = elems.fauxHandle.html();
			}else if(elems.frame.hasClass('sd2-small')){
					//elems.slidedeck.find('.slidesVertical dd').css('padding-top', elems.deckHeader.height())
					if(!elems.frame.hasClass('.hide-description')){
						var arrows = elems.frame.find('.deck-navigation.horizontal');
						var posTop = parseInt(arrows.css('top'),10);
						posTop = posTop + (elems.frame.find('.sd-node-twitter-header').outerHeight()/2);
						arrows.css('top', posTop);
					}
			}else{
				elems.replyDiv.css('width', (elems.slidedeck.width()*.85)-elems.toolBarButtons.width()-elems.replyDivPadding);
			}
		}
    	
    	this.initialize();
    };
    
    $(document).ready(function(){
        $('.lens-twitter .slidedeck').each(function(){
            if(typeof($.data(this, 'lens-twitter')) == 'undefined' || $.data(this, 'lens-twitter') == null){
                $.data(this, 'lens-twitter', new SlideDeckLens['twitter'](this));
            }
        });
    });
})(jQuery);