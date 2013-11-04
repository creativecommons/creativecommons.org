(function($){
	var ajaxOptions = [
        "options[show-title]",
        "options[show-excerpt]",
        "options[show-readmore]",
        "options[show-author]",
        "options[show-author-avatar]",
        "options[spineTitleLength]",
        "options[spineWidth]",
        "options[show-spine-titles]",
        "options[indexType]"
	];
	
	for(i = 0; i < ajaxOptions.length; i++){
		// Apply Ajax Updates to these...
		SlideDeckPreview.updates[ajaxOptions[i]] = function($elem, value){
	    	SlideDeckPreview.ajaxUpdate();
	    };		
		
		SlideDeckPreview.ajaxOptions.push(ajaxOptions[i]);
	}
	
	// Spine Inactive Color (background)
    SlideDeckPreview.updates['options[inactiveSpineColor]'] = function($elem, value){
        var footerStyles = SlideDeckPreview.elems.iframeContents.find("#slidedeck-footer-styles");
        var cssText = footerStyles.text().replace(/\.sd2-spine-background-color\{background-color:([\#0-9a-fA-F]+);?\}/gi, ".sd2-spine-background-color{background-color:" + value + "}");
        footerStyles.text(cssText);
        
        // Fall back for IE < 9
        if(ie < 9){
            SlideDeckPreview.elems.slidedeckFrame.find('.sd2-spine-background-color').css('background-color', value);
        }
    };
    
    // Spine Active Color (background)
    SlideDeckPreview.updates['options[activeSpineColor]'] = function($elem, value){
        var footerStyles = SlideDeckPreview.elems.iframeContents.find("#slidedeck-footer-styles");
        var cssText = footerStyles.text().replace(/\.sd2-spine-background-color(\.active|:hover)\{background-color:([\#0-9a-fA-F]+);?\}/gi, ".sd2-spine-background-color$1{background-color:" + value + "}");
        footerStyles.text(cssText);
        
        // Fall back for IE < 9
        if(ie < 9){
            SlideDeckPreview.elems.slidedeckFrame.find('.sd2-spine-background-color:hover').css('background-color', value);
            SlideDeckPreview.elems.slidedeckFrame.find('.sd2-spine-background-color.active').css('background-color', value);
        }
    };
    
    // Spine Inactive Title Color (background)
    SlideDeckPreview.updates['options[inactiveSpineTitleColor]'] = function($elem, value){
        var footerStyles = SlideDeckPreview.elems.iframeContents.find("#slidedeck-footer-styles");
        var cssText = footerStyles.text().replace(/\.sd2-spine-title-color\{color:([\#0-9a-fA-F]+);?\}/gi, ".sd2-spine-title-color{color:" + value + "}");
        footerStyles.text(cssText);
        
        // Fall back for IE < 9
        if(ie < 9){
            SlideDeckPreview.elems.slidedeckFrame.find('.sd2-spine-title-color').css('color', value);
        }
    };
    
    // Spine Active Title Color (background)
    SlideDeckPreview.updates['options[activeSpineTitleColor]'] = function($elem, value){
        var footerStyles = SlideDeckPreview.elems.iframeContents.find("#slidedeck-footer-styles");
        var cssText = footerStyles.text().replace(/\.sd2-spine-title-color(\.active|:hover)\{color:([\#0-9a-fA-F]+);?\}/gi, ".sd2-spine-title-color$1{color:" + value + "}");
        footerStyles.text(cssText);
        
        // Fall back for IE < 9
        if(ie < 9){
            SlideDeckPreview.elems.slidedeckFrame.find('.sd2-spine-title-color:hover').css('color', value);
            SlideDeckPreview.elems.slidedeckFrame.find('.sd2-spine-title-color.active').css('color', value);
        }
    };
	
})(jQuery);