(function($){
    window.PostsSource = {
        elems: {},
        
        updateTaxonomies: function(postType, filterByTax){
            var self = this;
            
            $.ajax({
                url: ajaxurl + "?action=slidedeck_available_filters&post_type=" + postType + "&slidedeck=" + this.slidedeck_id + "&filter_by_tax=" + filterByTax,
                type: "GET",
                success: function(data){
                    self.elems.filters.html(data);
                    self.elems.taxonomyLoading.hide(); // Hide the loading indicator
					self.elems.filters.find('input.fancy').fancy();
					
                    // Restore the chosen checkboxes...
                    var checkedItems = self.elems.filters.find('input[value="1"]:checked');
                    
                    if( checkedItems.length ){
		                var postType = $('[name="options[post_type]"]').find('option:selected').val();
                    	for (var i=0; i < checkedItems.length; i++) {
					  		var taxonomy = self.getTaxonomySlugFromNameAttr( $( checkedItems[i] ).attr('name') );
							self.updateTerms(postType, taxonomy);
						}
                    }
                }
            });
        },
        
        /**
         * Looks at the list of popular terms and if and of them
         * are checked in the main list, it checks them too. Usually
         * this is handled by WordPress in the PHP layer, but a post ID and
         * associated taxonomy info is needed for that and we don't have that.
         */
        checkPopularTermList: function(){
        	var fullList = this.elems.terms.find('.tabs-panel[id$="-all"] li');
        	var popList = this.elems.terms.find('.tabs-panel[id$="-pop"] li');
        	
        	for (var i=0; i < popList.length; i++) {
				var popularItem = $(popList[i]).find('input');
				
				if( fullList.find('input[value="' + popularItem.val() + '"]').is(':checked') ){
					$(popularItem).attr('checked', true);
				}
			};
        },
        
        /**
         * Looks at the number of term areas.
         * If there's more than one, we show the any/all dropdown.
         */
        rightSideModules: function(){
            var self = this;
            var moduleCount = self.elems.rightSide.find('div.taxonomy').length;
            var anyAllTaxonomies = self.elems.rightSide.find('#any-or-all-taxonomies');
            var trailblazer = self.elems.rightSide.find('.trailblazer');
            
            /**
             * Ooooohhhh! Fancy logic!!1!
             */
            if( moduleCount == 0 ){
                anyAllTaxonomies.hide();
                trailblazer.show();
            }else if( moduleCount > 1 ){
                anyAllTaxonomies.show();
                trailblazer.hide();
            }else{
                anyAllTaxonomies.hide();
                trailblazer.hide();
            }
        },
        
        /**
         * Updates the list of terms for a specific taxonomy.
         */
        updateTerms: function(postType, taxonomy){
            var self = this;
            
            $.ajax({
                url: ajaxurl + "?action=slidedeck_available_terms&post_type=" + postType + "&slidedeck=" + this.slidedeck_id + "&taxonomy=" + taxonomy,
                type: "GET",
                success: function(data){
                	self.elems.terms.find('.' + taxonomy).remove();
                    self.elems.terms.append(data);
                    
                    // Hide the loading indicator
                    self.elems.termsLoading.hide();
                    
                    // Uses WordPress' default functionality
                    self.elems.terms.find('.postbox.tagsdiv').each(function(){
	                    self.tagBoxInit( $(this) );
                    });
                    
                    // Syncs the checkboxes for categories
                    self.checkPopularTermList();
                    
                    // Decides whether or not to show the any/all dropdown.
                    self.rightSideModules();
                }
            });
        },
        
        tagBoxInit: function( box ){
        	if( !box.hasClass('initialized') ){
        		// Already initialized?
        		box.addClass('initialized');
        		
	            var t = tagBox;
	            var ajaxtag = box.find('div.ajaxtag');
	    		
	            box.find('.tagsdiv').each( function() {
	                tagBox.quickClicks(this);
	            });
	    
	            box.find('input.tagadd', ajaxtag).click(function(){
	                t.flushTags( $(this).closest('.tagsdiv') );
	            });
	    
	            box.find('div.taghint', ajaxtag).click(function(){
	                $(this).css('visibility', 'hidden').parent().siblings('.newtag').focus();
	            });
	    
	            box.find('input.newtag', ajaxtag).blur(function() {
	                if ( this.value == '' )
	                    $(this).parent().siblings('.taghint').css('visibility', '');
	            }).focus(function(){
	                $(this).parent().siblings('.taghint').css('visibility', 'hidden');
	            }).keyup(function(e){
	                if ( 13 == e.which ) {
	                    tagBox.flushTags( $(this).closest('.tagsdiv') );
	                    return false;
	                }
	            }).keypress(function(e){
	                if ( 13 == e.which ) {
	                    e.preventDefault();
	                    return false;
	                }
	            }).each(function(){
	                var tax = $(this).closest('div.tagsdiv').attr('id');
	                $(this).suggest( ajaxurl + '?action=ajax-tag-search&tax=' + tax, { delay: 500, minchars: 2, multiple: true, multipleSep: "," } );
	            });
	        
	            // tag cloud
	            box.find('a.tagcloud-link').click(function(){
	                tagBox.get( $(this).attr('id') );
	                $(this).unbind().click(function(){
	                    $(this).siblings('.the-tagcloud').toggle();
	                    return false;
	                });
	                return false;
	            });
        	}
        },
        
        showHideRightSide: function() {
        	var self = this;
            var filterByTax = $('[name="options[filter_by_tax]"]:checked').val();
            
            if( !filterByTax ){
                filterByTax = 0;
            }
            
            if( filterByTax ){
            	self.elems.contentSource.addClass('open');
            	self.elems.rightSide.show();
            }else{
            	self.elems.contentSource.removeClass('open');
            	self.elems.rightSide.hide();
            }
        },
        
        /**
         * Fetches the slug of the taxonomy from the name attribute
         * Will extract 'my-slug' from 'options[taxonomies][my-slug]'
         * 
         * This will basically do a regex search for the last bracketed
         * value and return it if one is found.
         *  
         */
        getTaxonomySlugFromNameAttr: function( name ){
        	var matches = name.match( /\[([a-zA-Z\-_]+)\]$/ );
        	if( typeof( matches[1] ) != 'undefined' ){
        		return matches[1];
        	}
        	
        	return false;
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.form = $('#slidedeck-update-form');
            this.elems.filters = $('#slidedeck-filters');
            this.elems.terms = $('#slidedeck-terms');
            this.elems.leftSide = $('.slidedeck-content-source.source-posts #content-source-posts .left');
            this.elems.rightSide = $('.slidedeck-content-source.source-posts #content-source-posts .right');
            this.elems.contentSource = this.elems.leftSide.closest('.slidedeck-content-source');
            this.elems.taxonomyLoading = self.elems.leftSide.find('.slidedeck-ajax-loading');
            this.elems.termsLoading = self.elems.rightSide.find('.slidedeck-ajax-loading');
            this.elems.preferredImageSizeRow = this.elems.form.find('#preferred-image-size-row');
           
            this.slidedeck_id = $('#slidedeck_id').val();
            
            // Check off the popular terms initially...
            self.checkPopularTermList();
            
            /**
             * Fired when the image source is changed.
             */
            this.elems.form.delegate('#options-postsImageSource', 'change', function(event){
                var imageSource = $(this).val();
                
                switch( imageSource ) {
                    case 'thumbnail':
                    case 'gallery':
                        self.elems.preferredImageSizeRow.slideDown();
                    break;
                    default:
                        self.elems.preferredImageSizeRow.slideUp();
                    break;
                }
            });
            
            /**
             * Fired when the post type is changed or the 
             * filter option is toggled on or off.
             */
            this.elems.form.delegate('[name="options[filter_by_tax]"], [name="options[post_type]"]', 'change', function(event){
                var postType = $('[name="options[post_type]"]').find('option:selected').val();

                var filterByTax = $('[name="options[filter_by_tax]"]:checked').val();
                
                if( !filterByTax ){
                    filterByTax = 0;
                }else{
                	self.elems.taxonomyLoading.show(); // Show the loading indicator
                }
                
				self.showHideRightSide();
				
                // Cleanup... This proveides a snappier feedback cycle.
                self.elems.terms.find('.taxonomy').remove();
                self.elems.filters.find('ul').remove();
                
                // Get the new taxonomies.
                self.updateTaxonomies(postType, filterByTax);
                
                // Decides whether or not to show the any/all dropdown.
                self.rightSideModules();
            });
            
            /**
             * Tab Switcher for hierarchial taxonomies
             */
            this.elems.form.delegate('.category-tabs li a', 'click', function(event){
            	event.preventDefault();
            	
				var t = $(this).attr("href");
				var taxonomy = self.getTaxonomySlugFromNameAttr( $(this).parents('.categorydiv').attr('name') );
				
			    $(this).parent().addClass("tabs").siblings("li").removeClass("tabs");
			    $("#" + taxonomy + "-tabs").siblings(".tabs-panel").hide();
			    $(t).show();
			    return false;            	
            });
            // Mirror the choice for the popular -> all lists.
            this.elems.form.delegate('.categorydiv input', 'click', function(event){
            	var id = $(this).val();
            	var checked = $(this).is(':checked');
            	
            	var targets = self.elems.form.find('input[value="' + id + '"]');
            	targets.attr('checked', checked);
            });
            
            /**
             * This handles the display of the terms 
             * list for each taxonomy.
             * Triggered when a taxonomy checkbox is ticked or when
             * the page loads with initial selections.
             */
            this.elems.form.delegate('#slidedeck-filters input', 'change', function(event){
                var postType = $('[name="options[post_type]"]').find('option:selected').val();
                var taxonomy = self.getTaxonomySlugFromNameAttr( $(this).attr('name') );
                var value = this.value;
                value = ( value == 1 ) ? true : false;
                
                if( value ){
                	// Append the chooser
                	self.elems.termsLoading.show(); // Show the loading indicator
	                self.updateTerms(postType, taxonomy);
                }else{
                	// Remove the chooser
                	self.elems.terms.find('.' + taxonomy).remove();
                }
                
                // Decides whether or not to show the any/all dropdown.
                self.rightSideModules();
            });
            
			self.showHideRightSide();
			
            self.elems.terms.find('.postbox.tagsdiv').each(function(){
                self.tagBoxInit( $(this) );
            });

        }
    };
    
    $(document).ready(function(){
        PostsSource.initialize();
    });
    
    var ajaxOptions = [
        "options[validateImages]",
        "options[postsImageSource]",
        "options[preferredImageSize]",
        "options[use-custom-post-excerpt]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
})(jQuery);
