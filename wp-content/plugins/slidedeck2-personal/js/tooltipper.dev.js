/*!
 * jQuery ToolTipper Plugin
 * 
 * Quick tooltip plugin for jQuery
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
var ToolTipper;

(function($){
    ToolTipper = function(elems, options){
        var $elems = $(elems),
            self = this;
        
        this.options = {
            namespace: "",
            speed: "fast",
            delay: 250,
            offsetX: 0,
            offsetY: 0,
            maxWidth: 350
        };
        this.options = $.extend(this.options, options);
        
        this.prep($elems);
        
        $('body').delegate('.tooltip, .tooltipper', 'mouseenter', function(event){
            self.show(this);
        }).delegate('.tooltip, .tooltipper', 'mouseleave', function(event){
            self.hide(this);
        });
    };
    
    ToolTipper.prototype.build = function(elem){
        var $elem = $(elem),
            message = $.data(elem, 'tooltipper-message');
        
        $('body').append('<span class="tooltipper ' + this.options.namespace + '" style="display:none;">' + message + '</span>');
        $tooltipper = $('body').find('.tooltipper:last');
        
        // Store references on the element
        $.data(elem, 'tooltipper-tip', $tooltipper);            // The tool tip
        $.data(elem, 'tooltipper-elem', $elem);                 // The element
        // Store references on the element's tip
        $.data($tooltipper[0], 'tooltipper-tip', $tooltipper);  // The tool tip
        $.data($tooltipper[0], 'tooltipper-elem', $elem);       // The element
        
        return $tooltipper;
    };
    
    ToolTipper.prototype.prep = function($elems){
        var self = this;
        
        $elems.each(function(ind){
            var $elem = $elems.eq(ind);
            
            $.data(this, 'tooltipper-message', this.title);
            $elem.removeAttr('title');
            
            $.data(this, 'tooltipper-elem', $elem);
        });
    };
    
    ToolTipper.prototype.show = function(elem){
        var tip = $.data(elem, 'tooltipper-tip');
        
        // Create the tooltip if it doesn't exist
        if(!tip)
            tip = this.build(elem);
        
        var $elem = $.data(elem, 'tooltipper-elem');
        
        clearTimeout($elem[0].timer);
        
        // Prevent initiating animation during an animation
        var offset = $elem.offset();
        
        tip.css('max-width', this.options.maxWidth);
        tip.css({
            top: offset.top + this.options.offsetY - tip.outerHeight(),
            left: offset.left + this.options.offsetX,
            opacity: 0,
            display: 'block'
        }).stop().animate({
            top: offset.top + this.options.offsetY - tip.outerHeight(),
            opacity: 1
        }, this.options.speed);
    };
    
    ToolTipper.prototype.hide = function(elem){
        var self = this,
            $elem = $.data(elem, 'tooltipper-elem'),
            tip = $.data(elem, 'tooltipper-tip');
        
        $elem[0].timer = setTimeout(function(){
            var offset = tip.offset();
            
            tip.animate({
                top: offset.top - 5,
                opacity: 0
            }, self.options.speed, function(){
                tip.css({
                    display: 'none'
                });
            });
        }, self.options.delay);
    };
    
    jQuery.fn.tooltipper = function(options){
        var data = $.data(this, 'ToolTipper');
        if(!data)
            data = $.data(this, 'ToolTipper', new ToolTipper(this, options));
        
        return this;
    };
})(jQuery);