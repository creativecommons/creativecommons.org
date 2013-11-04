/*!
 * jQuery Simple Modal plugin
 * 
 * A simple modal library
 * 
 * @author dtelepathy
 * @version 1.0.1
 */

/*!
Copyright 2012 digital-telepathy  (email : support@digital-telepathy.com)

This file is part of SlideDeck.

SlideDeck is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

SlideDeck is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SlideDeck.  If not, see <http://www.gnu.org/licenses/>.
*/
var SimpleModal = function(options){
    this.options = {
        namespace: "slidedeck",
        context: "",
        hideOnOverlayClick: true,
        hideOnEscape: true,
        speedIn: 500,
        speedOut: 500,
        onComplete: null,
        onCleanup: null,
        onClosed: null
    };
    
    this.elems = {};
    
    this.initialize(options);
    
    return this;
};

(function($){
    // Build the mask ID using the namespace option
    SimpleModal.prototype._maskId = function(){
        var arr = [];
        if(this.options.namespace !== "") arr.push(this.options.namespace);
        if(this.options.context !== "") arr.push(this.options.context);
        arr.push('simplemodal-mask');
        
        return arr.join('-');
    };
    
    // Build the modal ID using the namespace and context option
    SimpleModal.prototype._modalId = function(){
        var arr = [];
        if(this.options.namespace !== "") arr.push(this.options.namespace);
        if(this.options.context !== "") arr.push(this.options.context);
        arr.push('simplemodal');
        
        return arr.join('-');
    };
    
    // Build the modal pieces
    SimpleModal.prototype.build = function(){
        var self = this,
            modalId = this._modalId(),
            maskId = this._maskId();
        
        this.elems.modal = $('#' + modalId);
        this.elems.mask = $('#' + maskId);
        
        if(this.elems.modal.length < 1){
            $('body').append('<div id="' + modalId + '" class="simplemodal" style="display:none;" />');
            this.elems.modal = jQuery('#' + modalId);
        }
        
        if(this.elems.mask.length < 1){
            $('body').append('<div id="' + maskId + '" class="simplemodal-mask" style="display:none;"><div id="' + maskId + '-inner" class="simplemodal-mask-inner"></div></div>');
            this.elems.mask = $('#' + maskId);
            
            this.elems.mask.bind('click', function(){
                if(self.options.hideOnOverlayClick === true)
                    self.close();
            });
        }
        
        this.position = this.elems.modal.css('position');
        
        $(document).bind('keyup', function(event){
            if(event.keyCode == 27)
                if(self.options.hideOnEscape === true)
                    self.close();
        });
    };

    // Close the modal dialog
    SimpleModal.prototype.close = function(){
        var self = this;
        
        // onCleanup callback
        if(typeof(this.options.onCleanup) == 'function')
            this.options.onCleanup(this);
        
        this.elems.mask.fadeOut(this.options.speedOut);
        this.elems.modal.fadeOut(this.options.speedOut, function(){
            self.elems.modal.css({
                '-webkit-transition': '',
                '-moz-transition': '',
                '-o-transition': '',
                'transition': ''
            });
            
            // onClosed callback
            if(typeof(self.options.onClosed) == 'function')
                self.options.onClosed(self);
        });
        this.elems.modal.removeClass('open');
    };
    
    // Initialize and setup
    SimpleModal.prototype.initialize = function(options){
        var self = this;
        
        this.options = $.extend(this.options, options);
        this.elems.$window = $(window);
        
        this.build();
        
        this.elems.$window.resize(function(){
            self.reposition();
        });
    };

    // Open the modal dialog
    SimpleModal.prototype.open = function(data){
        var self = this;
        
        this.elems.modal.html(data);
        
        this.elems.mask.fadeIn(this.options.speedIn);
        this.elems.modal.fadeIn(this.options.speedIn, function(){
            self.elems.modal.css({
                '-webkit-transition': 'top 0.5s ease-in-out',
                '-moz-transition': 'top 0.5s ease-in-out',
                '-o-transition': 'top 0.5s ease-in-out',
                'transition': 'top 0.5s ease-in-out'
            });
        });
        this.reposition();
        this.elems.modal.addClass('open');
        
        // onComplete callback
        if(typeof(this.options.onComplete) == 'function')
            this.options.onComplete(this);
    };
    
    SimpleModal.prototype.reposition = function(){
        var modalHeight = this.elems.modal.outerHeight();
        var windowHeight = this.elems.$window.height();
        var scrollTop = window.scrollTop || window.scrollY;
        var modalTop = this.elems.modal.offset().top;
        var documentHeight = $(document).height();
        
        switch(this.position){
            default:
            case "fixed":
                if(modalTop + modalHeight > windowHeight){
                    if(modalHeight > windowHeight) {
                        this.elems.modal.css({
                            top: 20,
                            marginTop: 0
                        });
                    } else {
                        this.elems.modal.css({
                            top: '50%',
                            marginTop: 0 - (modalHeight / 2)
                        });
                    }
                } else {
                    this.elems.modal.css({
                        top: '50%',
                        marginTop: 0 - (modalHeight / 2)
                    });
                }
            break;
            
            case "absolute":
                var maxTop = documentHeight - modalHeight - 40;
                
                this.elems.modal.css({
                    top: Math.min(scrollTop, maxTop) + 20,
                    marginTop: 0
                });
            break;
        }
    };
})(jQuery);
