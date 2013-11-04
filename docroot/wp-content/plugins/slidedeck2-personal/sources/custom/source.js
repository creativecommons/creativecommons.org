var sd_layoutoptions = {};

(function($, window, undefined){
	window.SlideDeckSourceCustom = {
		elems: {},
		editId: null,
		currentModal: "",
		
        addSlide: function(elem){
            var self = this;
            var url = elem.href;
            var classname = "";
            
            if(elem.href.match(/slide_id=([\d]+)/)){
                // Set an ID to note modification of an existing slide for the editing modal upon the new slide type choice
                this.editId = elem.href.match(/slide_id=([\d]+)/)[1];
                classname = 'change-slide-type';
            } else {
                // Un-set the ID so the edit modal for the new slide is brought up after choosing type
                this.editId = null;
                classname = 'add-slide';
            }
            
            // Current modal open is this modal already
            if(this.currentModal == elem){
                this.close();
                return false;
            }
            
            $.get(url, function(html){
                self.open(html, elem, classname);
            });
        },
        
        // Clean up the flyout element for change to another state
        cleanup: function(){
            this.elems.slideEditor.removeClass('add-slide change-slide-type edit-slide');
            this.elems.contentControl.find('.slide').removeClass('loading');
            this.currentModal = "";
        },
        
        // Cleanup and hide the flyout
        close: function(){
            this.cleanup();
            this.elems.slideEditor.hide();
        },
        
        // Delete a slide from a SlideDeck and update the slides list
		deleteSlide: function(elem){
			var self = this;
			var $elem = $(elem);
			
			// Close any active slide editor flyout
			SlideDeckSourceCustom.close();
			
			$.ajax({
				url: elem.href,
				type: "POST",
				success: function(data){
					if(data == "true"){
						$elem.closest('.slide').fadeOut(250, function(){
							$(this).remove();
							
							var $slides = SlideDeckSourceCustom.elems.contentControl.find('.slide');
							if($slides.length == 1){
								$slides.find('.remove').hide();
							} else {
								$slides.find('.remove').show();
							}
							
							self.renumber();
							
							SlideDeckPreview.ajaxUpdate();
						});
					}
				}
			})
		},
		
		// Edit a slide
		editSlide: function(elem){
			var self = this;
			var $elem = $(elem);
			var url = elem.href;
			var $loading = $.data(elem, '$loading');
			var $slide = $.data(elem, '$slide');
			
			// Current modal open is this modal already
			if(this.currentModal == elem){
			    this.close();
			    return false;
			}
			
			if(!$slide){
			    $slide = $elem.closest('li.slide');
			    $.data(elem, '$slide', $slide);
			}
			
			if(!$loading){
			    $slide.append('<span class="slide-loading"></span>');
			    $loading = $slide.find('.slide-loading');
			    $.data(elem, '$loading', $loading);
			}
			
			$slide.addClass('loading');
			
			$.ajax({
			    url: url,
			    cache: false,
			    success: function(html){
			        self.open(html, elem, 'edit-slide');
			    }
			})
		},
		
		renumber: function(){
		    var $slides = this.elems.contentControl.find('.slide');
		    $slides.each(function(ind){
		        $slides.eq(ind).find('.slide-number').text(ind + 1);
		    });
		},
		
		// Update the slide management area and the SlideDeck preview
		updateContentControl: function(html, callback){
			var self = this;
			
			if(typeof(html) != 'undefined'){
				this.elems.contentControl.html(html);
				SlideDeckPreview.ajaxUpdate();
			}
			
			this.elems.contentControl.find('.slides-sortable').sortable({
			    items: 'li.slide',
			    start: function(event, ui){
			        SlideDeckSourceCustom.close();
			    },
				update: function(event, ui){
					var data = $('#slidedeck-update-form').serialize();
						// Modify the default action
						data = data.replace(/action\=([a-zA-Z0-9\-_+]+)/, "action=slidedeck_update_slide_order");
						// Remove the _wpnonce value to prevent default actions
						data = data.replace(/\&_wpnonce\=([a-zA-Z0-9\-_+]+)/, "");
					
					$.ajax({
						url: ajaxurl,
						data: data,
						type: "POST",
						success: function(data){
							SlideDeckPreview.ajaxUpdate();
						}
					});
					
					var $sortableList = ui.item.parent();
					var $sortableLis = $sortableList.find('li.slide');
					
					for( var i = 0; i < $sortableLis.length; i++ ){
					    $( $sortableLis[i] ).find('.slide-number').html( i + 1 );
					}
				}
			});
            
            if(typeof(callback) == 'function'){
                callback();
            }
		},
		
		// Open the flyout
		open: function(html, elem, classname){
		    var $elem = $(elem);
		    var classname = classname || "";
		    
		    // If this is not a thumbnail link, adjust $elem's context to be the thumbnail link
		    if(!$elem.hasClass('thumbnail')){
		        // Only adjust $elem context if the elem link has a Slide ID
		        if(elem.href.match(/slide_id=([\d]+)/)){
    		        var slideId = elem.href.match(/slide_id=([\d]+)/)[1];
    		        var $elem = this.elems.contentControl.find('.slides-sortable .slide-id-' + slideId + ' .thumbnail');
		        }
		    }
		    
		    var offset = $elem.offset();
		    
		    this.elems.slideEditor.html('<span class="hanging-chad"></span>' + html).css({
                top: offset.top,
                left: "-999em"
            }).show().find('.fancy').fancy();
            
            this.cleanup();
            this.elems.slideEditor.addClass(classname);
            
            var $width = this.elems.slideEditor.width();
            var $windowWidth = $(window).width();
            
            if((offset.left + $width) > $windowWidth){
                var correction = (((offset.left + $width) - $windowWidth) - 10)
                
                offset.left = offset.left - correction;
                this.elems.slideEditor.find('.hanging-chad').css({
                    marginLeft: correction 
                });
            }
            
            this.elems.slideEditor.css({
                left: offset.left
            });
            
            var tinyParams = tinyMCEPreInit.mceInit.slidedeck;
            tinyParams.mode = "specific_textareas";
            tinyParams.editor_selector = "slidedeck_mceEditor";
            
            tinyMCE.init(tinyParams);
            
            this.currentModal = elem;
		},
		
		initialize: function(){
			var self = this;
			
			this.elems.contentControl = $('#slidedeck-content-control');
			
			// Fail silently if this isn't a custom SlideDeck
			if(!this.elems.contentControl.hasClass('custom-slidedeck'))
				return false;
			
			this.elems.slideEditor = $('#slidedeck-custom-slide-editor');
			if(this.elems.slideEditor.length < 1){
			    $('body').append('<div id="slidedeck-custom-slide-editor"></div>');
			    this.elems.slideEditor = $('#slidedeck-custom-slide-editor');
			}
			
			// Slide management interactions
            this.elems.contentControl.delegate('.slide .thumbnail', 'click', function(event){
            	event.preventDefault();
            	self.editSlide(this);
            }).delegate('.slide .remove', 'click', function(event){
            	event.preventDefault();
            	if(confirm("Are you sure you want to delete this slide?")){
	            	self.deleteSlide(this);
            	}
            }).delegate('.add-new-slide a', 'click', function(event){
            	event.preventDefault();
            	self.addSlide(this);
            });
            
            // Generic Upgrade modal
            SlideDeckPlugin.UpgradeModal = {
                open: function(data){
                    var self = this;
                    
                    if(!this.modal){
                        this.modal = new SimpleModal({
                            context: 'professional-upsell'
                        });
                    }
                    this.modal.open(data);
                }
            };
            
            // Flyout Close Button
            this.elems.slideEditor.delegate('.cancel', 'click', function(event){
                event.preventDefault();
                
                if(this.href.match(/slide_id=([\d]+)/)){
                    self.editSlide(this);
                } else {
                    self.close();
                }
            })
            .delegate('.slide-type-header .change', 'click', function(event){
                event.preventDefault();
                self.addSlide(this);
            })
            // Editor flyout form submit
            .delegate('#slidedeck-custom-slide-editor-form', 'submit', function(event){
                event.preventDefault();
                
                var $form = $(this);
                
                $.ajax({
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    type: $form.attr('method'),
                    success: function(data){
                        self.updateContentControl(data);
                        self.close();
                    }
                });
            })
            // Editor flyout choose slide type submit
            .delegate('#slidedeck-choose-slide-type form', 'submit', function(event){
                event.preventDefault();
                
                var $form = $(this);
                
                $.ajax({
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    type: $form.attr('method'),
                    success: function(data){
                        self.updateContentControl(data, function(){
                            // When adding a slide, use the last in the list
                            var $slide = SlideDeckSourceCustom.elems.contentControl.find('.slide:last .thumbnail');
                            // When modifying a slide, use the one selected for modification
                            if(SlideDeckSourceCustom.editId != null){
                                $slide = SlideDeckSourceCustom.elems.contentControl.find('.slide-id-' + SlideDeckSourceCustom.editId + ' .thumbnail');
                            }
                            
                            // Trigger the edit view for the newly modified/added slide
                            $slide.click();
                        });
                    }
                });
            })
            // Editor flyout choose slide type radio submit on click
            .delegate('#slidedeck-choose-slide-type form input[type="radio"]', 'click', function(event){
                $(this).closest('form').submit();
            })
            .delegate('#slidedeck-choose-slide-type form li.slide-type.disabled label', 'click', function(event){
                event.preventDefault();
                
                $.get(ajaxurl + "?action=slidedeck_upsell_modal_content&feature=slide-types-" + $(this).attr('data-for'), function(data){
                    SlideDeckPlugin.UpgradeModal.open(data);
                });
            })
            // Editor flyout choose layout
            .delegate('.slide-content-fields li.layout input[type="radio"]', 'click', function(event) {
                var layoutoption = sd_layoutoptions[this.value];
                
                self.elems.slideEditor.find('.slide-content-fields li.layout label').removeClass('active-layout');
                $(this).closest('li.layout label').addClass('active-layout');
                
                
                $('.slide-content-fields').find('li.option').not(layoutoption.fields).slideUp();
                $('.slide-content-fields').find(layoutoption.fields).slideDown();
                
                if ( layoutoption.positions ) {
                    self.elems.slideEditor.find('li.text-position strong').html(layoutoption.proper + ' Position');
                    self.elems.slideEditor.find('li.text-position label input').parent('label').hide().removeClass('on');
                    for (var k in layoutoption.positions){
                        var pos = layoutoption.positions[k];
                        self.elems.slideEditor.find('li.text-position label input[value='+pos+']').parent('label').show();
                        if ( pos === "right" || pos === "bottom" ) {
                            self.elems.slideEditor.find('li.text-position label input[value='+pos+']').click().parent('label').addClass('on');
                        }
                    }
                    
                    self.elems.slideEditor.find('li.text-position').slideDown();
                }
                
            })
            // Choose where the text will reside
            .delegate('li.text-position label input[type="radio"]', 'click', function(event) {
                self.elems.slideEditor.find('.slide-content-fields li.text-position label').removeClass('active-position');
                $(this).closest('label').addClass('active-position');
            });
            
            if(this.elems.contentControl.find('.slide.empty-slide').length){
                this.addSlide(this.elems.contentControl.find('.slide.empty-slide .thumbnail')[0]);
            }
            
            this.updateContentControl();
		}
	};
	
	$(function(){
		SlideDeckSourceCustom.initialize();
	});
})(jQuery, window, null);
