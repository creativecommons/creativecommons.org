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
var ToolTipper;(function(a){ToolTipper=function(c,d){var e=a(c),b=this;this.options={namespace:"",speed:"fast",delay:250,offsetX:0,offsetY:0,maxWidth:350};this.options=a.extend(this.options,d);this.prep(e);a("body").delegate(".tooltip, .tooltipper","mouseenter",function(f){b.show(this)}).delegate(".tooltip, .tooltipper","mouseleave",function(f){b.hide(this)})};ToolTipper.prototype.build=function(d){var b=a(d),c=a.data(d,"tooltipper-message");a("body").append('<span class="tooltipper '+this.options.namespace+'" style="display:none;">'+c+"</span>");$tooltipper=a("body").find(".tooltipper:last");a.data(d,"tooltipper-tip",$tooltipper);a.data(d,"tooltipper-elem",b);a.data($tooltipper[0],"tooltipper-tip",$tooltipper);a.data($tooltipper[0],"tooltipper-elem",b);return $tooltipper};ToolTipper.prototype.prep=function(c){var b=this;c.each(function(e){var d=c.eq(e);a.data(this,"tooltipper-message",this.title);d.removeAttr("title");a.data(this,"tooltipper-elem",d)})};ToolTipper.prototype.show=function(c){var d=a.data(c,"tooltipper-tip");if(!d){d=this.build(c)}var b=a.data(c,"tooltipper-elem");clearTimeout(b[0].timer);var e=b.offset();d.css("max-width",this.options.maxWidth);d.css({top:e.top+this.options.offsetY-d.outerHeight(),left:e.left+this.options.offsetX,opacity:0,display:"block"}).stop().animate({top:e.top+this.options.offsetY-d.outerHeight(),opacity:1},this.options.speed)};ToolTipper.prototype.hide=function(d){var c=this,b=a.data(d,"tooltipper-elem"),e=a.data(d,"tooltipper-tip");b[0].timer=setTimeout(function(){var f=e.offset();e.animate({top:f.top-5,opacity:0},c.options.speed,function(){e.css({display:"none"})})},c.options.delay)};jQuery.fn.tooltipper=function(b){var c=a.data(this,"ToolTipper");if(!c){c=a.data(this,"ToolTipper",new ToolTipper(this,b))}return this}})(jQuery);