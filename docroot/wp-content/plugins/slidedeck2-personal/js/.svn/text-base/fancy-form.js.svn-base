/*!
 * jQuery Fancy Form plugin
 * 
 * Spice up your form with unique, intuitive form interactions
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
var FancyForm;(function(a){FancyForm=function(b){var c=a(b);this.fancy(c)};FancyForm.prototype.fancyCheckbox=function(b){b.wrap('<label class="fancy-checkbox" />');var c=b.closest(".fancy-checkbox");c.append('<span class="on"><span>On</span></span><span class="off"><span>Off</span></span>').addClass(b[0].checked?"on":"off").delegate('input[type="checkbox"]',"click",function(d){if(this.checked){c.removeClass("off").addClass("on")}else{c.removeClass("on").addClass("off")}})};FancyForm.prototype.fancyRadios=function(c){var b=c.closest("label");b.wrapAll('<span class="fancy-radios" />');b.filter(":first").addClass("first");b.filter(":last").addClass("last");c.each(function(f){var e=c.eq(f);var d=b.eq(f);d.delegate('input[type="radio"]',"click",function(g){if(this.checked){b.removeClass("on");d.addClass("on")}}).addClass("radio-"+f);if(this.checked){d.addClass("on")}})};FancyForm.prototype.fancySelect=function(d){var c=this;d.wrap('<span class="fancy-select" />');var f=d.closest(".fancy-select");var b=d.find("option");f.width(d.outerWidth()).bind("click",function(g){c.hideOptions();c.showOptions(d)});f.append('<span class="selected" />');var e=f.find(".selected");e.text(b.filter(":selected").text());d.bind("change",function(){d.closest(".fancy-select").find(".selected").text(d.find("option:selected").text())})};FancyForm.prototype.showOptions=function(d){var k=this;var h=d.find("option");a("body").bind("click.fancySelect",function(m){var l=a(m.target);if((l.closest("#fancyform-options-dropdown").length<1)&&(l.closest(".fancy-select").length<1)){k.hideOptions()}});this.dropdown=a("#fancyform-options-dropdown");if(this.dropdown.length<1){a("body").append('<div id="fancyform-options-dropdown"><span class="options"></span></div>');this.dropdown=a("#fancyform-options-dropdown");this.dropdown.delegate(".option","click",function(){var n=a.data(k.dropdown[0],"dom-select");var l=n.find("option");var o=a(this).attr("data-value");var m="";l.each(function(p){if(this.selected){m=this.value}this.selected=(this.value==o);if(this.selected==true){n.siblings(".selected").text(this.text)}});k.hideOptions();if(m!=o){n.trigger("change")}}).bind("click",function(){k.hideOptions()})}a.data(this.dropdown[0],"dom-select",d);this.dropdown.find("span.option").remove();var i="";h.each(function(l){i+='<span class="option'+(l==h.length-1?" last":"")+(this.selected==true?" selected":"")+'" data-value="'+this.value+'">'+this.text+"</span>"});var f=this.dropdown.find(".options");f.append(i);this.dropdown.css({left:"-999em"}).show();var g=d.closest(".fancy-select").offset(),c=a(window).height(),e=a(window).scrollTop(),b=this.dropdown.outerHeight(),j=f.find(".option").outerHeight();if(((b+g.top)>(c+e))&&(c>b)&&((g.top-e)>b)){this.dropdown.addClass("invert");g.top=g.top-b}else{this.dropdown.removeClass("invert")}this.dropdown.css({top:g.top,left:g.left,"min-width":d.closest(".fancy-select").outerWidth()});f.css("max-height",j*10)};FancyForm.prototype.hideOptions=function(){if(this.dropdown){this.dropdown.find("span.option").remove();this.dropdown.hide()}a("body").unbind("click.fancySelect")};FancyForm.prototype.fancyText=function(b){b.addClass("fancy-text")};FancyForm.prototype.fancy=function(d){var c=this;var b={};d.each(function(f){var e=d.eq(f);if(e.is("input")){switch(e.prop("type")){case"radio":if(!b[e.prop("name")]){c.fancyRadios(d.filter('[name="'+e.prop("name")+'"]'));b[e.prop("name")]=true}break;case"checkbox":c.fancyCheckbox(e);break;case"text":default:c.fancyText(e);break}}else{if(e.is("select")){c.fancySelect(e)}}})};jQuery.fn.fancy=function(){var b=a.data(this,"FancyForm");if(!b){b=a.data(this,"FancyForm",new FancyForm(this))}return this}})(jQuery);