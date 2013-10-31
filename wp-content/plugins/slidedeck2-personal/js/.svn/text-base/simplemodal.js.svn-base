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
var SimpleModal=function(a){this.options={namespace:"slidedeck",context:"",hideOnOverlayClick:true,hideOnEscape:true,speedIn:500,speedOut:500,onComplete:null,onCleanup:null,onClosed:null};this.elems={};this.initialize(a);return this};(function(a){SimpleModal.prototype._maskId=function(){var b=[];if(this.options.namespace!==""){b.push(this.options.namespace)}if(this.options.context!==""){b.push(this.options.context)}b.push("simplemodal-mask");return b.join("-")};SimpleModal.prototype._modalId=function(){var b=[];if(this.options.namespace!==""){b.push(this.options.namespace)}if(this.options.context!==""){b.push(this.options.context)}b.push("simplemodal");return b.join("-")};SimpleModal.prototype.build=function(){var d=this,c=this._modalId(),b=this._maskId();this.elems.modal=a("#"+c);this.elems.mask=a("#"+b);if(this.elems.modal.length<1){a("body").append('<div id="'+c+'" class="simplemodal" style="display:none;" />');this.elems.modal=jQuery("#"+c)}if(this.elems.mask.length<1){a("body").append('<div id="'+b+'" class="simplemodal-mask" style="display:none;"><div id="'+b+'-inner" class="simplemodal-mask-inner"></div></div>');this.elems.mask=a("#"+b);this.elems.mask.bind("click",function(){if(d.options.hideOnOverlayClick===true){d.close()}})}this.position=this.elems.modal.css("position");a(document).bind("keyup",function(e){if(e.keyCode==27){if(d.options.hideOnEscape===true){d.close()}}})};SimpleModal.prototype.close=function(){var b=this;if(typeof(this.options.onCleanup)=="function"){this.options.onCleanup(this)}this.elems.mask.fadeOut(this.options.speedOut);this.elems.modal.fadeOut(this.options.speedOut,function(){b.elems.modal.css({"-webkit-transition":"","-moz-transition":"","-o-transition":"",transition:""});if(typeof(b.options.onClosed)=="function"){b.options.onClosed(b)}});this.elems.modal.removeClass("open")};SimpleModal.prototype.initialize=function(c){var b=this;this.options=a.extend(this.options,c);this.elems.$window=a(window);this.build();this.elems.$window.resize(function(){b.reposition()})};SimpleModal.prototype.open=function(c){var b=this;this.elems.modal.html(c);this.elems.mask.fadeIn(this.options.speedIn);this.elems.modal.fadeIn(this.options.speedIn,function(){b.elems.modal.css({"-webkit-transition":"top 0.5s ease-in-out","-moz-transition":"top 0.5s ease-in-out","-o-transition":"top 0.5s ease-in-out",transition:"top 0.5s ease-in-out"})});this.reposition();this.elems.modal.addClass("open");if(typeof(this.options.onComplete)=="function"){this.options.onComplete(this)}};SimpleModal.prototype.reposition=function(){var b=this.elems.modal.outerHeight();var g=this.elems.$window.height();var e=window.scrollTop||window.scrollY;var c=this.elems.modal.offset().top;var d=a(document).height();switch(this.position){default:case"fixed":if(c+b>g){if(b>g){this.elems.modal.css({top:20,marginTop:0})}else{this.elems.modal.css({top:"50%",marginTop:0-(b/2)})}}else{this.elems.modal.css({top:"50%",marginTop:0-(b/2)})}break;case"absolute":var f=d-b-40;this.elems.modal.css({top:Math.min(e,f)+20,marginTop:0});break}}})(jQuery);